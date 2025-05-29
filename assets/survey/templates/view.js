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
        previewModal.setContent("").open();
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
            questionsHTML += generateQuestionPreviewView(sq, "", "preview");
        });
        let html = "";
        html += `
            <table class="table table-bordered">
                <tr>
                    <th class="text-small">Title</th>
                    <td class="text-medium">${question.title}</td>
                </tr>
                <tr>
                    <th class="text-small">Description</th>
                    <td class="text-medium">${question.description || "-"}</td>
                </tr>
                <tr>
                    <th class="text-small">Recur</th>
                    <td class="text-medium">${question.is_recurring ? `Every ${question.recur_number} ${question.recur_type}` : "-"}</td>
                </tr>
            </table>
            <hr />
            ${questionsHTML}
        `;
        //
        previewModal.setContent(html)
        previewModal.loader(false)
    }
    //
    window.getTemplates = getTemplates;

    // getTemplates();
    startTemplateAddProcess();
});

