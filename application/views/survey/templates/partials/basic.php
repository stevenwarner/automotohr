<div class="container">
    <div class="row">
        <div class="col-sm-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-heading-text text-medium">
                        <strong>
                            <i class="fa fa-plus-circle text-orange"></i>
                            New Survey
                        </strong>
                    </h1>
                </div>
                <form action="" id="jsSurveyForm">
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="survey_name" class="text-medium">
                                Survey name
                                <strong class="text-danger">*</strong>
                            </label>
                            <p class="text-danger text-small">
                                <em>
                                    Please provide a valid survey name.
                                </em>
                            </p>
                            <input name="survey_name" type="text" class="form-control jsSurveyName"
                                placeholder="Survey name." />
                        </div>

                        <div class="form-group">
                            <label for="survey_description" class="text-medium">
                                Survey Description
                            </label>
                            <p class="text-danger text-small">
                                <em>
                                    Provide a brief description of the survey.
                                </em>
                            </p>
                            <textarea name="survey_description" class="form-control jsSurveyDescription"
                                placeholder="Survey description." rows="5"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="control control--checkbox">
                                <input type="checkbox" name="survey_anonymous" class="jsSurveyAnonymous" />
                                Set anonymous
                                <div class="control__indicator"></div>
                            </label>
                            <p class="text-danger text-small">
                                <em>
                                    An anonymous survey means all results will be aggregated, admins won't know who are
                                    the
                                    users that responded, and mobile users will be notified at the beginning of the
                                    survey that
                                    it's anonymous. Please note this action can't be undone for this survey
                                </em>
                            </p>
                        </div>

                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-orange jsSurveyBtn">
                            <i class="fa fa-save"></i>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>