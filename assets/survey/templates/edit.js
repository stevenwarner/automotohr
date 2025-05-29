$(function () {
    let XHR = null;
    let saveBtnRef;
    //
    let siteModalRef;
    let templateId = 0;
    // set the object
    let template = {
        title: "",
        description: "",
        category: "",
        is_recurring: false,
        recur_type: "days",
        recur_number: 0,
        available_date: 0,
        questions: [],
    };
    // set the default questions array
    let questionsArray = [];

    $(document).on("keyup", "#jsTemplateEditTitle", function (event) {
        template.title = $(this).val();
    });

    $(document).on("keyup", "#jsTemplateEditDescription", function (event) {
        template.description = $(this).val();
    });

    $(document).on("click", "#jsTemplateEditRecur", function (event) {
        template.is_recurring = $(this).prop("checked");
        if (template.is_recurring) {
            $(".jsRecurringSurveyBox").removeClass("hidden");
        } else {
            $(".jsRecurringSurveyBox").addClass("hidden");
        }
    });

    $(document).on("keyup", "#jsTemplateEditRecurNumber", function (event) {
        template.recur_number = $(this).val();
    });

    $(document).on("change", "#jsTemplateEditRecurType", function (event) {
        template.recur_type = $(this).val();
    });

    $(document).on("click", ".jsTemplateEditSaveBtn", function (event) {
        event.preventDefault();

        //
        if (!template.title) {
            _error("Title is required.");
            return;
        }
        //
        if (template.is_recurring && !template.recur_number) {
            _error("Recur number is required.");
            return;
        }
        //
        if (questionsArray.length === 0) {
            _error("Please add at least one question.")
            return;
        }

        template.questions = questionsArray;

        saveTemplate();
    });


    async function saveTemplate() {
        saveBtnRef = callButtonHook(
            $(".jsTemplateEditSaveBtn"),
            true
        );
        try {
            const response = await makeSecureCallToApiServer(
                `surveys/templates/${templateId}`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    data: JSON.stringify(template),
                    caches: false
                }
            );

            siteModalRef.closeModal();
            _success(response.message, function () {
                // later on refresh templates
                getTemplates();
            })

        } catch (error) {
            handleErrorResponse(error)
            callButtonHook(saveBtnRef, false);
        }
    }

    function startTemplateEditProcess(idOfTemplate) {
        templateId = idOfTemplate;
        // start the modal
        siteModalRef = $.msSiteModal();
        siteModalRef.loader(true).open();
        // generate view
        generateView();
    }

    // generate template view
    async function generateView() {
        if (XHR !== null) {
            XHR.abort();
        }
        // reset the object
        template = {
            title: "",
            description: "",
            category: "",
            is_recurring: false,
            recur_type: "days",
            recur_number: 0,
            available_date: 0,
        };

        try {
            const response = await makeSecureCallToApiServer(`surveys/templates/view/edit/${templateId}`, {
                method: "GET",
            });
            // set the template
            template = response.data;
            // set the questions
            questionsArray = template.questions;
            // ste the view
            siteModalRef.setContent(response.view);
            // set the view
            setView();
            loadQuestionsView(true);
            siteModalRef.loader(false);
            showView("basic");
            $("#jsEditQuestionsArea").sortable({
                stop: function (event) {
                    console.log(event);
                    processQuestions();
                },
                onchange: function (event) {
                    console.log(event);
                }
            })
        } catch (error) {
            handleErrorResponse(error);
            siteModalRef.closeModal()
        }
    }

    // set view
    function setView() {
        $("#jsTemplateEditTitle").val(template.title);
        $("#jsTemplateEditDescription").val(template.description);
        $("#jsTemplateEditRecur").prop("checked", template.is_recurring);
        $("#jsTemplateEditRecurNumber").val(template.recur_number);
        $("#jsTemplateEditRecurType option[value='" + (template.recur_type) + "']").prop("selected", true);
        // //
        if (!template.is_recurring) {
            $(".jsRecurringSurveyBox").addClass("hidden");
        } else {
            $(".jsRecurringSurveyBox").removeClass("hidden");
        }
    }

    /**
     * Load questions on view
     *
     * @returns
     */
    function loadQuestionsView(initialSet = false) {
        // check the length of questions
        if (!questionsArray.length) {
            return $("#jsEditQuestionsArea").html(
                '<p class="alert alert-info text-center">No questions found yet.</p>'
            );
        }
        // set default trs
        let tr = "";
        //
        questionsArray.map(function (questionObj, i) {

            if (questionObj.description !== undefined || questionObj.question_type === "tag") {
                if (initialSet) {
                    questionObj.plainDescription = $("<div>").html(questionObj.question_content).text().trim();
                    questionObj.description = questionObj.question_content;
                    questionObj.slug = getSlug(questionObj.question_content);
                    questionsArray[i] = questionObj;
                }
                tr += getDescriptionPreview(questionObj, true)
            } else {
                if (initialSet) {
                    questionObj.choice_list = JSON.parse(questionObj.choices_json);
                    questionObj.question_required = questionObj.is_required;
                    questionsArray[i] = questionObj;
                }
                tr += getQuestionPreview(questionObj, "", true)
            }
        });
        //
        $("#jsEditQuestionsArea").html(tr);
    }

    /**
     * Delete question
     * @param {int} questionId
     */
    function deleteQuestion(questionId) {
        //
        questionsArray = questionsArray.filter(function (questionObj) {
            return questionObj.question_id == questionId ? false : true;
        });
        //
        loadQuestionsView();
    }

    /**
     * get question
     * @param {int} questionId
     */
    function getTheQuestionObjById(questionId) {
        //
        let question = questionsArray.filter(function (questionObj) {
            return questionObj.question_id == questionId ? true : false;
        });
        //
        return question[0];
    }

    /**
     * Toggle view
     * @param {*} view 
     */
    function showView(view) {
        console.log("Edit")
        //
        $("#sectionMain").addClass("hidden");
        $("#sectionAddQuestionBox").addClass("hidden");
        $("#sectionEditQuestionBox").addClass("hidden");
        $("#sectionEditDescription").addClass("hidden");
        $("#jsEditEditDescriptionId").val("");
        siteModalRef.setButtons("");

        setTimeout(function () {
            //
            if (view === "basic") {
                $("#sectionMain").removeClass("hidden");
                siteModalRef.setButtons(`<button type="button" class="btn btn-orange jsTemplateEditSaveBtn"><i class="fa fa-save"></i>Save Changes</button>`);
            } else if (view === "add_question") {
                $("#sectionAddQuestionBox").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToEditCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsAddQuestionSaveBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Save Question</button>
                `);
            } else if (view === "edit_question") {
                $("#sectionEditQuestionBox").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToEditCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsEditQuestionSaveBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Update Question</button>
                `);
            } else if (view === "edit_description") {
                $("#sectionEditDescription").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToEditCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsEditEditDescription"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Save Changes</button>
                `);
            } else if (view === "edit_edit_description") {
                $("#sectionEditDescription").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToEditCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsEditModifyDescription"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Save Changes</button>
                `);
            }
        }, 10)
    }

    // Add question process
    $(document).on(
        "click",
        ".jsTemplateEditAddQuestion",
        function (event) {
            event.preventDefault();
            showView("add_question");
            loadAddQuestionView(function (obj) {
                obj.tag = getTheLastDescription();
                questionsArray.push(obj);
                loadQuestionsView();
                showView("basic");
            })
        }
    );

    $(document).on(
        "click",
        ".jsTemplateEditAddDescription",
        function (event) {
            event.preventDefault();
            showView("edit_description");
            if (!CKEDITOR.instances["jsEditEditDescription"]) {
                CKEDITOR.replace("jsEditEditDescription", {
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] }
                    ]
                });
            }
            CKEDITOR.instances["jsEditEditDescription"].setData("");
        }
    );

    $(document).on(
        "click",
        ".jsTemplateEditEditDescription",
        function (event) {
            event.preventDefault();
            const descriptionId = $(this).closest(".jsQuestionDescription").data("id").replace("desc_", "");
            const descriptionObject = getTheQuestionObjById(descriptionId);

            console.log(descriptionId)
            console.log(descriptionObject)

            showView("edit_edit_description");

            if (!CKEDITOR.instances["jsEditEditDescription"]) {
                CKEDITOR.replace("jsEditEditDescription", {
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] }
                    ]
                });
            }

            CKEDITOR.instances["jsEditEditDescription"].setData(
                descriptionObject.description
            );

            $("#jsEditEditDescriptionId").val(descriptionObject.question_id);
        }
    );

    $(document).on(
        "click",
        ".jsEditEditDescription",
        function (event) {
            event.preventDefault();

            const description = CKEDITOR.instances["jsEditEditDescription"].getData();

            if (!description.trim() || $("<div>").html(description).text().trim() === "") {
                _error("Description cannot be empty.");
                return;
            }
            //
            questionsArray.push({
                question_id: generateRandomAndUniqueId(),
                description: description,
                plainDescription: $("<div>").html(description).text().trim(),
                slug: getSlug(description),
                questions: [],
            });

            showView("basic")
            loadQuestionsView();
        }
    );

    $(document).on(
        "click",
        ".jsEditModifyDescription",
        function (event) {
            event.preventDefault();

            const description = CKEDITOR.instances["jsEditEditDescription"].getData();
            const descriptionId = $("#jsEditEditDescriptionId").val();

            if (!description.trim() || $("<div>").html(description).text().trim() === "") {
                _error("Description cannot be empty.");
                return;
            }
            //
            questionsArray = questionsArray.map(function (existingQuestion) {
                if (existingQuestion.question_id == descriptionId) {
                    console.log("Found it")
                    existingQuestion.description = description;
                    existingQuestion.plainDescription = $("<div>").html(description).text().trim();
                    existingQuestion.slug = getSlug(description);
                }
                return existingQuestion;
            });

            //
            showView("basic");
            loadQuestionsView();
        }
    );

    // delete question
    $(document).on(
        "click",
        ".jsRemoveEditDescription",
        function (event) {
            event.preventDefault();
            const questionId = $(this).closest(".jsQuestionDescription").data("id").replace("desc_", "");

            _confirm(
                "Do you want to delete this question?",
                function () {
                    deleteQuestion(questionId)
                }
            );
        }
    );
    // close button
    $(document).on(
        "click",
        ".jsBackToEditCoursePage",
        function (event) {
            event.preventDefault();
            showView("basic");

        }
    );
    $(document).on(
        "click",
        ".jsRemoveEditQuestion",
        function (event) {
            event.preventDefault();
            const questionId = $(this).closest(".jsQuestionView").data("id");

            _confirm(
                "Do you want to delete this question?",
                function () {
                    deleteQuestion(questionId)
                }
            );
        }
    );

    $(document).on(
        "click",
        ".jsEditEditQuestion",
        function (event) {
            event.preventDefault();
            event.stopPropagation();
            const questionId = $(this).closest(".jsQuestionView").data("id");

            const singleQuestion = getTheQuestionObjById(questionId);

            showView("edit_question");

            loadEditQuestionView(singleQuestion, function (obj) {
                console.log(obj)

                updateQuestionToQuestions(obj, obj.question_id)
                loadQuestionsView();
                showView("basic");
            })
        }
    );


    function updateQuestionToQuestions(questionObj, questionId) {
        questionsArray = questionsArray.map(function (existingQuestion) {
            return existingQuestion.question_id === questionId ? questionObj : existingQuestion;
        });
    }


    function processQuestions() {
        let sortedIds = $("#jsEditQuestionsArea").sortable("toArray", { attribute: "data-id" });

        let newQuestionsArray = [];
        let currentDescription = "";
        //
        sortedIds.map(function (id) {
            if (id.substr(0, 5) === "desc_") {
                question = getTheQuestionObjById(id.replace("desc_", ""));
                newQuestionsArray.push(question);
                currentDescription = question.plainDescription;
            } else {
                let localQuestion = getTheQuestionObjById(id);
                localQuestion.tag = currentDescription;
                newQuestionsArray.push(localQuestion)
            }
        });

        questionsArray = newQuestionsArray;
    }

    function getTheLastDescription() {
        let lastDescription = "";
        //
        questionsArray.map(function (questionObj) {
            if (questionObj.description !== undefined) {
                lastDescription = questionObj.description;
            }
        });
        //
        return lastDescription;
    }
    window.startTemplateEditProcess = startTemplateEditProcess;
});

