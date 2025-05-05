<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-heading-text text-medium text-center">
                        <strong>
                            <i class="fa fa-plus-circle text-orange"></i>
                            <span class="jsSurveyDisplayTitle"></span>
                        </strong>
                    </h1>
                </div>
                <div class="panel-body" style="min-height: 60vh;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h1 class="text-medium text-center">
                                        <strong>
                                            Add a field
                                        </strong>
                                    </h1>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="component-block panel-heading-text text-large">
                                        <strong>
                                            <i class="fa fa-bars"></i>
                                            Description
                                        </strong>
                                    </div>
                                    <hr />
                                </div>
                            </div>

                            <div class="component-block panel-heading-text text-large">
                                <strong>
                                    <i class="fa fa-check-square"></i>
                                    Dropdown
                                </strong>
                            </div>
                            <div class="component-block panel-heading-text text-large">
                                <strong>
                                    <i class="fa fa-font"></i>
                                    Open ended
                                </strong>
                            </div>
                            <div class="component-block panel-heading-text text-large">
                                <strong>
                                    <i class="fa fa-star"></i>
                                    Rating
                                </strong>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-8">
                        <div id="jsSurveyQuestionHolder"></div>
                    </div>
                    <div class="col-md-4">

                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <button type="button" class="btn btn-black btn-default">
                            <i class="fa fa-arrow-left"></i>
                            Basic
                        </button>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="submit" class="btn btn-black jsSaveSurveyAsTemplateBtn">
                            <i class="fa fa-file"></i>
                            Save as template
                        </button>
                        <button type="submit" class="btn btn-orange jsSaveSurveyBtn">
                            <i class="fa fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .component-block {
        cursor: pointer;
        background: #ffffff;
        border: 1px solid #d9d9d9;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        border-radius: 4px;
        /* width: 172px; */
        min-height: 40px;
        font-size: 15px;
        color: #3f4648;
        margin-bottom: 10px;
        /* display: block; */
        text-align: left;
        padding-left: 15px;
        /* float: left; */
    }

    .component-block:hover {
        background-color: #f1f1f1;
    }
</style>