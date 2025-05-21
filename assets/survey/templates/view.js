$(function () {
    //
    let XHR = null;
    //
    $(".jsFilterSearchBtn").click(
        function (event) {
            event.preventDefault();
            getTemplates();
        }
    );

    $(".jsFilterClearBtn").click(
        function (event) {
            event.preventDefault();
            $(".jsFilterKeyword").val("");
            getTemplates();
        }
    );


    $(".jsAddSurveyTemplateBtn").click(
        function (event) {
            event.preventDefault();
            startTemplateAddProcess();
        }
    );
    //
    async function getTemplates() {
        $("#jsTemplatesArea").html(generateLoadingRow())
        //
        if (XHR !== null) {
            XHR.abort();
        }
        //
        const response = await makeSecureCallToApiServer(
            `surveys/templates?keyword=${$(".jsFilterKeyword").val().trim()}`,
            {
                method: "GET"
            }
        );
        //
        if (response.data.length === 0) {
            return $("#jsTemplatesArea").html(generateErrorRow())
        }

        generateDataRows(response.data)
    }

    //
    function generateErrorRow() {
        return `
            <tr>
                <td colspan="4">
                    <p class="alert alert-info text-center">
                        No templates found.
                    </p>
                </td>
            </tr>
        `;
    }

    function generateLoadingRow() {
        return `
           <tr>
                <td colspan="4">
                    <div class="alert text-center">
                        <i class="fa fa-spinner fa-spin text-large"></i>
                    </div>
                </td>
            </tr>
        `;
    }

    function generateDataRows(data) {
        //
        let tr = "";

        data.map(function (template) {
            tr += `<tr class="jsSurveyTemplateRow" data-id="${template.template_sid}">`

            tr += ` <td class="vam">`
            tr += template.title
            tr += ` </td>`

            tr += ` <td class="vam">`
            tr += `     <button type="button" class="btn btn-link jsPreviewSurveyTemplate">`
            tr += `         ${template.number_of_questions} Questions`
            tr += `     </button>`
            tr += ` </td>`

            tr += ` <td class="vam">`
            if (template.is_recurring) {
                tr += `Every ${template.recur_number} ${template.recur_type}`
            } else {
                tr += `-`;
            }
            tr += ` </td>`

            tr += ` <td class="vam text-right">`
            tr += `     <button type="button" class="btn btn-orange jsPreviewSurveyTemplate">`
            tr += `         <i class="fa fa-eye"></i>`
            tr += `         Preview`
            tr += `     </button>`
            tr += `     <button type="button" class="btn btn-warning jsEditSurveyTemplate">`
            tr += `         <i class="fa fa-edit"></i>`
            tr += `         Edit`
            tr += `     </button>`
            tr += `     <button type="button" class="btn btn-danger jsDeleteSurveyTemplate">`
            tr += `         <i class="fa fa-trash"></i>`
            tr += `         Delete`
            tr += `     </button>`
            tr += ` </td>`

            tr += `</tr>`
        });

        $("#jsTemplatesArea").html(tr);
    }

    $(document).on("click", ".jsPreviewSurveyTemplate", function () {
        const templateId = $(this).closest(".jsSurveyTemplateRow").data("id");
        previewTemplate(templateId);
    });

    async function previewTemplate(templateId) {

        if (previewModal !== undefined) {
            previewModal.closeModal()
        }
        previewModal = $.msSiteModal();
        previewModal.setContent("");
        previewModal.loader(true);
        // Logic to preview the template
        //
        try {
            const response = await makeSecureCallToApiServer(
                `surveys/templates/${templateId}`,
                {
                    method: "GET"
                }
            );

            loadPreview(response.data);
        } catch (error) {
            previewModal.closeModal();
            return handleErrorResponse(error);
        }
    }


    let previewModal;


    function loadPreview(question) {
        //
        let questionsHTML = "";
        question.questions.map(function (sq) {
            questionsHTML += getQuestionPreview(sq, "", "preview");
        });
        let html = "";
        html += `
            <div class="row">
                <div class="col-sm-12">
                    <label>
                        Title
                    </label>
                    <p>${question.title}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>
                        Description
                    </label>
                    <p>${question.description}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>
                        Recur
                    </label>
                    <p>${question.is_recurring ? `Every ${question.recur_number} ${question.recur_type}` : "-"}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label>
                        Questions
                    </label>
                    ${questionsHTML}
                </div>
            </div>
        `;
        //
        previewModal.setContent(html)
        previewModal.loader(false)
    }
    //
    window.getTemplates = getTemplates;

    getTemplates();
});

