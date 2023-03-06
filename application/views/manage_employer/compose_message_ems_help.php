<?php
$get_data_user = $this->session->userdata('logged_in');
$company_id = $get_data_user['company_detail']['sid'];

$getCompanyHelpboxInfo = get_company_helpbox_info($company_id);
$toemail = $getCompanyHelpboxInfo[0]['box_support_email'];
?>
<div class="">
    <!--  -->
    <div class="row">
        <div class="col-sm-12">
            <p class="text-danger" style="font-size: 16px">
                <strong>
                    <em>Note: Please briefly describe any issues or Questions you have. Your sent email can be found in your "Outbox" of "Private Messages" on your Employee dashboard.</em>
                </strong>
            </p>
        </div>
    </div>
    <br>
    <!--  -->
    <div class="row">
        <br>
        <div class="col-sm-12">
            <label><strong>Subject</strong><span class="text-danger"> * </span></label>
            <input type="text" class="form-control" id="jsSubject" />
        </div>
    </div>

    <!--  -->
    <div class="row">
        <br>
        <div class="col-sm-12">
            <label><strong>Message</strong><span class="text-danger"> * </span></label>
            <textarea class="ckeditor" id="jsBody"></textarea>
        </div>
    </div>
    <!--  -->
    <div class="row">
        <br>
        <div class="col-sm-12">
            <button class="btn btn-success jsSubmitCompanyContactBtn">Send Email</button>
        </div>
    </div>
</div>