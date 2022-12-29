<!--  -->
<div class="panel panel-default _csMt20 _csPR _csR5">
    <div class="panel-heading">
        <h3 class="_csF16">Select a Survey Template</h3>
    </div>
    <div class="panel-body">
        <p class="_csF14">Select the best template based on the suggestions below or create your own survey from the ground up.</p>
        <strong class="_csF14 text-danger">Need help getting your survey started? <span class="_csF3">Check out this how-to guide.</span></strong>
        <hr>
        <!--  -->
        <h3 class="_csF14">TEMPLATES</h3>
        <p class="_csF14"><?= STORE_NAME; ?> templates are industry standard and capture the most important aspects of engagement. If you edit any question here, it will impact the comparison capabilities in the future.</p>
        <hr>
        <!--  -->
        <div class="row" id="surveysBoxContainer"></div>

        <hr>
        <div class="row">
            <div class="col-sm-12 _csMt20 _csMb20">
                <label class="control control--checkbox">
                    <input type="checkbox" class="jsCreateFormScratch"/> Start from scratch
                    <div class="control__indicator"></div>
                </label>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn _csB1 _csF2 _csR5 _csF14 ">
                    <i class="fa fa-times-circle _csF14" aria-hidden="true"></i>
                    Cancel
                </button>
                <button class="btn _csB4 _csF2 _csR5 _csF14 jsCreateSurvey">
                    <i class="fa fa-long-arrow-right _csF14" aria-hidden="true"></i>
                    Next
                </button>
            </div>
        </div>
    </div>
</div>