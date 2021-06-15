<!-- Reviewees -->
<div class="panel panel-theme">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-11">
                <p class="csF16 csB7 csW mb0">Select Reviewees <small>(The reviewee's are the employee's against which the review will run)</small></p>
            </div>
            <div class="col-xs-1">
                <span class="pull-right">
                    <i class="fa fa-minus-circle csCP csF18 csB7 jsPageBTN" aria-hidden="true" data-target="basic"></i>
                </span>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="panel-body pl0 pr0 jsPageBody" data-page="basic">
        
        <!-- Info -->
        <div class="row">
            <div class="col-xs-12">
                <p class="csF16"><i class="fa fa-info-circle csF18 csB7" aria-hidden="true"></i>&nbsp;<em class="csInfo">Use the Rule Settings to define which workers are included (or excluded) from this review</em>.</p>
            </div>
        </div>
        
        <!-- Main -->
        <div class="row">
            <br />
            <!-- Filter -->
            <div class="col-md-4 col-xs-12">
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <!--  -->
                        <div class="row">
                            <div class="col-md-10 col-xs-9">
                                <p class="csF16 csB7 csW mb0">Rule Settings</p>
                            </div>
                            <div class="col-md-2 col-xs-3">
                                <button class="btn btn-black btn-xs"><i class="fa fa-refresh" aria-hidden="true" title="Reset the filter to default" placement="top"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Employee(s)</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2" multiple></select>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Role(s)</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2" multiple></select>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Department(s)</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2" multiple></select>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Team(s)</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2" multiple></select>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Job Title(s)</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2" multiple></select>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Employment Type(s)</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2" multiple></select>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7 bbb">Exclude Employee(s)</p>
                    </div>
                </div>

                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Employee(s)</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2" multiple></select>
                    </div>
                </div>
                
                <!--  -->
                <div class="row">
                    <br>
                    <div class="col-md-12 col-xs-12">
                        <p class="csF14 csB7">Hire Date</p>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select2"></select>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-md-8 col-xs-12">
                <div class="panel panel-theme">
                    <div class="panel-heading">
                        <p class="csF16 csB7"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Buttons -->
<div class="row">
    <div class="col-sm-12">
        <div class="bbb"></div>
        <br />
        <span class="pull-right">
            <button class="btn btn-orange csF16"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i>&nbsp; Save & Next</button>
            <button class="btn btn-black csF16"><i class="fa fa-arrow-circle-o-left" aria-hidden="true"></i>&nbsp; Back To Schedule</button>
            <button class="btn btn-black csF16"><i class="fa fa-archive" aria-hidden="true"></i>&nbsp; Finish Later</button>
        </span>
    </div>
    <div class="clearfix"></div>
</div>



<script>$('.select2').select2({
    placeholder: "Please select"
});</script>