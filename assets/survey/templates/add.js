$(function () {
    let XHR = null;
    let saveBtnRef;
    //
    let siteModalRef;
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

    $(document).on("keyup", "#jsTemplateTitle", function (event) {
        template.title = $(this).val();
    });

    $(document).on("keyup", "#jsTemplateDescription", function (event) {
        template.description = $(this).val();
    });

    $(document).on("click", "#jsTemplateRecur", function (event) {
        template.is_recurring = $(this).prop("checked");
        if (template.is_recurring) {
            $(".jsRecurringSurveyBox").removeClass("hidden");
        } else {
            $(".jsRecurringSurveyBox").addClass("hidden");
        }
    });

    $(document).on("keyup", "#jsTemplateRecurNumber", function (event) {
        template.recur_number = $(this).val();
    });

    $(document).on("change", "#jsTemplateRecurType", function (event) {
        template.recur_type = $(this).val();
    });

    $(document).on("click", ".jsTemplateSaveBtn", function (event) {
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
            $(".jsTemplateSaveBtn"),
            true
        );
        try {
            const response = await makeSecureCallToApiServer(
                "surveys/templates/create",
                {
                    method: "POST",
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

    function startTemplateAddProcess() {
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
            const response = await makeSecureCallToApiServer("surveys/templates/view", {
                method: "GET",
            });

            siteModalRef.setContent(response);
            setView();
            loadQuestionsView();
            siteModalRef.loader(false);
            showView("basic");
            $("#jsAddQuestionsArea").sortable({
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
        $("#jsTemplateTitle").val(template.title);
        $("#jsTemplateDescription").val(template.description);
        $("#jsTemplateRecur").prop("checked", template.is_recurring);
        $("#jsTemplateRecurNumber").val(template.recur_number);
        $("#jsTemplateRecurType option[value='" + (template.recur_type) + "']").prop("selected", true);
        //
        if (template.is_recurring == false) {
            $(".jsRecurringSurveyBox").addClass("hidden");
        } else {
            $(",jsRecurringSurveyBox").removeClass("hidden");
        }
    }

    /**
     * Callback for add question
     *
     * @param {*} questionObj
     */
    function saveQuestionToQuestions(questionObj) {
        // hide the loader
        ml(false, modalLoaderId);
        // push the question to questions array
        questionsArray.push(questionObj);
        // load questions view
        loadQuestionsView();
    }

    /**
     * Load questions on view
     *
     * @returns
     */
    function loadQuestionsView() {
        // check the length of questions
        if (!questionsArray.length) {
            return $("#jsAddQuestionsArea").html(
                '<p class="alert alert-info text-center">No questions found yet.</p>'
            );
        }
        // set default trs
        let tr = "";
        //
        questionsArray.map(function (questionObj) {
            if (questionObj.description !== undefined) {
                tr += getDescriptionPreview(questionObj)
            } else {
                tr += getQuestionPreview(questionObj)
            }
        });
        //
        $("#jsAddQuestionsArea").html(tr);
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
        console.log("Add ", view)
        //
        $("#sectionMain").addClass("hidden");
        $("#sectionAddQuestionBox").addClass("hidden");
        $("#sectionEditQuestionBox").addClass("hidden");
        $("#sectionAddDescription").addClass("hidden");
        $("#jsAddEditDescriptionId").val("");
        siteModalRef.setButtons("");

        setTimeout(function () {
            //
            if (view === "basic") {
                $("#sectionMain").removeClass("hidden");
                siteModalRef.setButtons(`<button type="button" class="btn btn-orange jsTemplateSaveBtn"><i class="fa fa-save"></i>Save Changes</button>`);
            } else if (view === "add_question") {
                $("#sectionAddQuestionBox").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToAddCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsAddQuestionSaveBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Save Question</button>
                `);
            } else if (view === "edit_question") {
                $("#sectionEditQuestionBox").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToAddCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsEditQuestionSaveBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Update Question</button>
                `);
            } else if (view === "add_description") {
                $("#sectionAddDescription").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToAddCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsAddEditDescription"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Save Changes</button>
                `);
            } else if (view === "edit_description") {
                $("#sectionAddDescription").removeClass("hidden");
                siteModalRef.setButtons(`
                    <button type="button" class="btn btn-black jsBackToAddCoursePage"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back</button>
                    <button type="button" class="btn btn-orange jsAddModifyDescription"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Save Changes</button>
                `);
            }
        }, 10)
    }

    // Add question process
    $(document).on(
        "click",
        ".jsTemplateAddQuestion",
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
        ".jsTemplateAddDescription",
        function (event) {
            event.preventDefault();
            showView("add_description");
            if (!CKEDITOR.instances["jsAddEditDescription"]) {
                CKEDITOR.replace("jsAddEditDescription", {
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] }
                    ]
                });
            }
            CKEDITOR.instances["jsAddEditDescription"].setData("");
        }
    );

    $(document).on(
        "click",
        ".jsTemplateEditDescription",
        function (event) {
            event.preventDefault();
            const descriptionId = $(this).closest(".jsQuestionDescription").data("id").replace("desc_", "");
            const descriptionObject = getTheQuestionObjById(descriptionId);

            showView("edit_description");

            if (!CKEDITOR.instances["jsAddEditDescription"]) {
                CKEDITOR.replace("jsAddEditDescription", {
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] }
                    ]
                });
            }

            CKEDITOR.instances["jsAddEditDescription"].setData(
                descriptionObject.description
            );

            $("#jsAddEditDescriptionId").val(descriptionObject.question_id);
        }
    );

    $(document).on(
        "click",
        ".jsAddEditDescription",
        function (event) {
            event.preventDefault();

            const description = CKEDITOR.instances["jsAddEditDescription"].getData();

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
        ".jsAddModifyDescription",
        function (event) {
            event.preventDefault();

            const description = CKEDITOR.instances["jsAddEditDescription"].getData();
            const descriptionId = $("#jsAddEditDescriptionId").val();

            if (!description.trim() || $("<div>").html(description).text().trim() === "") {
                _error("Description cannot be empty.");
                return;
            }
            //
            questionsArray = questionsArray.map(function (existingQuestion) {
                if (existingQuestion.question_id === descriptionId) {
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
        ".jsRemoveDescription",
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
        ".jsBackToAddCoursePage",
        function (event) {
            event.preventDefault();
            showView("basic");

        }
    );
    $(document).on(
        "click",
        ".jsRemoveQuestion",
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
        ".jsEditQuestion",
        function (event) {
            event.preventDefault();
            event.stopPropagation();
            const questionId = $(this).closest(".jsQuestionView").data("id");

            const singleQuestion = getTheQuestionObjById(questionId);

            showView("edit_question");

            loadEditQuestionView(singleQuestion, function (obj) {
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
        let sortedIds = $("#jsAddQuestionsArea").sortable("toArray", { attribute: "data-id" });

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
    window.startTemplateAddProcess = startTemplateAddProcess;
});

