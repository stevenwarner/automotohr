<div class="hr-box">
    <div class="hr-box-header">
        <strong>Applicant Information</strong>
    </div>
    <div class="table-responsive hr-innerpadding">
        <table class="table table-bordered table-hover table-striped">
            
            <tbody>
                <tr>
                    <th class="col-xs-2">Full Name</th>
                    <td>
                        <?php echo $employer['first_name'] . ' ' . $employer['last_name']; ?>
                    </td>
                </tr>
                <tr>
                    <th class="col-xs-2">Address</th>
                    <td>
                        <span><?php echo ($employer['address'] != '' ? $employer['address'] . ', ' : '') ; ?></span>
                        <span><?php echo ($employer['city'] != '' ? $employer['city'] . ', ' : '') ; ?></span>
                        <span><?php echo ($employer['zipcode'] != '' ? $employer['zipcode'] . ', '  : '') ; ?></span>
                        <?php $state_info = db_get_state_name($employer['state']); ?>
                        <?php if(!empty($state_info)) { ?>
                            <span><?php echo $state_info['state_name'] . ', ' ; ?></span>
                            <span><?php echo $state_info['country_name']; ?></span>
                        <?php } ?>


                    </td>
                </tr>
                <tr>
                    <th class="col-xs-2">Email</th>
                    <td>
                        <?php echo strtolower($employer['email']) ; ?>
                    </td>
                </tr>
                <tr>
                    <th class="col-xs-2">Phone</th>
                    <td>
                        <?php echo  $employer['phone_number']; ?>
                    </td>
                </tr>
                <!--<tr>
                    <th class="col-xs-2">Date Applied</th>
                    <td>
                        <?php /*echo convert_date_to_frontend_format($employer['new_application_date'], true); */?>
                    </td>
                </tr>-->
                <?php if(isset($interview_conducted_by) && !empty($interview_conducted_by)) { ?>
                <tr>
                    <th class="col-xs-2">Interview Conducted By</th>
                    <td><span class="text-success"><?php echo ucwords($interview_conducted_by['first_name'] . ' ' . $interview_conducted_by['last_name'] )?></span></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

