<div id="popup1" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                <h4 class="modal-title tille-style">CONTACT OUR TALENT PARTNERS</h4>
            </div>
            
            <div class="modal-body">
                <div class="form-wrapper">
                    <form method="post" action="" class="form" id="schedule-free-demo-form">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <input type="text" name="name" class="demo-form-fields" required="required" placeholder="Name">
                                        <?php echo form_error('name');?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <input type="email" name="email" id="email_id" class="demo-form-fields" required="required" placeholder="Email">
                                        <?php echo form_error('email');?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <input type="text" name="phone_number" class="demo-form-fields" required="required" placeholder="Phone">
                                        <?php echo form_error('phone_number');?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <input type="text" name="job_role" class="demo-form-fields" placeholder="Title">
                                        <?php echo form_error('title'); ?>
                                    </div>
                                </div>
                            </div>
                            
<!--                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="label_schedule_free_damo_date_time">Title</label>
                                    <div class="hr-select-dropdown">
                                        <select class="demo-form-fields" name="job_role">
                                            <option value="">- Please Select -</option>
                                            <option value="President/CEO">President/CEO</option>
                                            <option value="Talent Acquisition - VP">Talent Acquisition - VP</option>
                                            <option value="HR - VP/CPO">HR - VP/CPO</option>
                                            <option value="Talent Acquisition - Mgr/Dir">Talent Acquisition - Mgr/Dir</option>
                                            <option value="HR - Mgr/Dir">HR - Mgr/Dir</option>
                                            <option value="Operations">Operations</option>
                                            <option value="HRIS">HRIS</option>
                                            <option value="Recruiter">Recruiter</option>
                                            <option value="Agency">Agency</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <?php //echo form_error('job_role');?>
                                </div>
                            </div>-->
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <input type="text" name="company_name" class="demo-form-fields" required="required" placeholder="Company Name">
                                        <?php echo form_error('company_name'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($this->uri->segment(1) == 'schedule_your_free_demo') { ?>
                                <div class="col-xs-12">
                                    <div class="form-group autoheight">
                                        <label class="label_schedule_free_damo_date_time">Your Message</label>
                                        <textarea id="client_message" name="client_message" class="demo-form-fields demo-form-fields-textarea" rows="10"></textarea>
                                    </div>        
                                </div>
                            <?php } /*elseif ($this->uri->segment(1) == 'demo') { ?>
                                <div class="col-xs-12">
                                    <div class="col-xs-7 set_date_time_padding_left">
                                        <div class="form-group autoheight">
                                            <label class="label_schedule_free_damo_date_time">Select a Date</label>
                                            <input type="date" name="schedule_date" class="demo-form-fields" required="required">
                                            <?php echo form_error('schedule_date');?>
                                        </div>
                                    </div>
                                    <div class="col-xs-5 set_date_time_padding_right">
                                        <div class="form-group autoheight">
                                            <label class="label_schedule_free_damo_date_time">Time</label>
                                            <div class="hr-select-dropdown">
                                                <select class="demo-form-fields" name="schedule_time" required="required">
                                                    <option value="09:00:00">09 : am</option><option value="10:00:00">10 : am</option>
                                                    <option value="11:00:00">11 : am</option><option value="12:00:00">12 : pm</option>
                                                    <option value="13:00:00">01 : pm</option><option value="14:00:00">02 : pm</option>
                                                    <option value="15:00:00">03 : pm</option><option value="16:00:00">04 : pm</option>
                                                    <option value="17:00:00">05 : pm</option><option value="18:00:00">06 : pm</option>
                                                </select>
                                            </div>
                                            <?php echo form_error('schedule_time');?>
                                        </div>
                                    </div>
                                </div>
                            <?php } */?>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                    <label class="label_schedule_free_damo_date_time">Number of Employees</label>
                                    <div class="hr-select-dropdown">
                                        <select class="demo-form-fields" name="company_size" required="required">
                                            <option value="1-5">1 - 5</option>
                                            <option value="6-25">6 - 25</option>
                                            <option value="26-50">26 - 50</option>
                                            <option value="51-100">51 - 100</option>
                                            <option value="101-250">101 - 250</option>
                                            <option value="251-500">251 - 500</option>
                                            <option value="501+">501+</option>
                                        </select>
                                    </div>
                                    <?php echo form_error('schedule_time');?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <label>Subscribe to our weekly newsletter!</label>
                                        <label class="control control--radio" style="margin-top: 20px;">
                                            Yes
                                            <input checked="checked" class="video_source" type="radio" name="newsletter_subscribe" value="1">
                                            <div class="control__indicator"></div>
                                        </label>
                                        <label class="control control--radio" style="margin-top: 20px; margin-left: 15px;">
                                            No
                                            <input class="video_source" type="radio" name="newsletter_subscribe" value="0">
                                            <div class="control__indicator"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                    </div>
                                    <?php echo form_error('g-recaptcha-response'); ?>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="form-group autoheight">
                                     <div class="form-group autoheight">
                                        <input type="submit" value="SCHEDULE MY FREE DEMO" class="demo-form-fields demo-form-fields-btn" id="schedule-free-demo-form-submit">
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </form>
                </div>      
            </div>

            <div class="modal-footer" id="">
                <div class="row">
                    <div class="col-md-2 text-left">
                        <div id="loader" class="loader" style="display: none;">
                            <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
    $('#schedule-free-demo-form-submit').click(function () {
       $("#schedule-free-demo-form").validate({
            ignore: [],
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                phone_number:{
                    required: true,
                },
                company_name:{
                    required: true,
                },
                schedule_date:{
                    required: true,
                },
                schedule_time:{
                    required: true,
                }
            },
            messages: {
                name: {
                    required: 'Please provide user name.',
                },
                email: {
                    required: 'Please provide valid email.',
                },
                phone_number: {
                    required: 'Please provide valid phone number',
                },
                company_name: {
                    required: 'Please provide company name.',
                },
                schedule_date: {
                    required: 'Please select schedule date.',
                },
                schedule_time: {
                    required: 'Please Select schedule time',
                }
            },
            submitHandler: function (form) {
                var myurl = "<?= base_url() ?>demo/check_already_applied";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {email: $('#email_id').val()},
                    dataType: "json",
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        if (obj == 0) {
                            // alertify.error('You already applied for demo, we will get back to you');
                            // return false;
                            // alertify.success('Thank you for applying for free demo again');
                            form.submit();
                        }else{
                            $("#schedule-free-demo-form-submit").attr("disabled", true); 
                            form.submit();
                        }
                    },
                    error: function (data) {
                        alertify.error('Sorry we will fix that issue');
                    }
                });  
            }
        });        
        
    });
</script>

        