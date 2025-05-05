$(function addNewSurvey() {
    let XHR = null;
    //
    const surveyObject = {
        name: "test survey",
        description: "",
        isAnonymous: false,
        questionsObject: {}
    };
    //
    $(".jsAddNewSurvey").click(function (e) {
        e.preventDefault();
        startAddProcess();
    });

    $(document).on(
        "click",
        ".jsSurveyType",
        function (e) {
            //
            e.preventDefault()
            const option = $(this).data("key")

            if (option === "new") {
                initiateNewSurveyProcess();
            } else {
                // todo use the template
            }
        }
    );

    function startAddProcess() {
        Modal({
            Id: "jsAddNewSurveyModal",
            Loader: "jsAddNewSurveyModalLoader",
            Body: '<div id="jsAddNewSurveyModalBody"></div>',
            Title: "",
            cancel: {
                text: 'Cancel'
            }
        }, function () {
            // loadInitialScreen(); 
            // initiateNewSurveyProcess(); 
            setInterval(loadQuestionScreen, 3000);

        });
    }

    /**
     * loads the initial screen
     */
    function loadInitialScreen()
    {
        if (XHR !== null) {
            XHR.abort();
        }
        //
        XHR = $
            .ajax({
                url: baseUrl("survey/templates/view/initial"),
                method: "GET",
            })
            .always(function () {
                XHR = null;
             })
            .fail(function (er) {
                handleErrorResponse(er);
             })
            .done(function (resp) {
                $("#jsAddNewSurveyModalBody").html(resp);
                ml(false, "jsAddNewSurveyModalLoader")
             });
    }


    function initiateNewSurveyProcess() {
        if (XHR !== null) {
            XHR.abort();
        }
        //
        XHR = $
            .ajax({
                url: baseUrl("survey/templates/view/new"),
                method: "GET",
            })
            .always(function () {
                XHR = null;
             })
            .fail(function (er) {
                handleErrorResponse(er);
             })
            .done(function (resp) {
                $("#jsAddNewSurveyModalBody").html(resp);
                ml(false, "jsAddNewSurveyModalLoader");
                // make sure there is no duplication
                $("#jsSurveyForm").validate().destroy();
                // attach a new instance
                $("#jsSurveyForm").validate({
                    rules: {
                        survey_name: {
                            required: true,
                            minlength: 4,
                            maxlength: 255,
                        }
                    },
                    messages: {
                        survey_name: {
                            required: "Survey name is required."
                        }
                    },
                    submitHandler: function (form) {
                        const ref = callButtonHook($(".jsSurveyBtn"), true);
                        //
                        surveyObject.name = $(".jsSurveyName").val().trim();
                        surveyObject.description = $(".jsSurveyDescription").val().trim();
                        surveyObject.isAnonymous = $(".jsSurveyAnonymous").prop("checked");

                        loadQuestionScreen(ref)

                        callButtonHook(ref, false);
                    }
                });
             });
    }
    
    
    function loadQuestionScreen() {
        if (XHR !== null) {
            XHR.abort();
        }
        //
        XHR = $
            .ajax({
                url: baseUrl("survey/templates/view/questions"),
                method: "GET",
            })
            .always(function () {
                XHR = null;
             })
            .fail(function (er) {
                handleErrorResponse(er);
             })
            .done(function (resp) {
                $("#jsAddNewSurveyModalBody").html(resp);
                $(".jsSurveyDisplayTitle").text(surveyObject.name);
                ml(false, "jsAddNewSurveyModalLoader");                   
            });
    }

    startAddProcess();
});