
<div class="form_section_main width_100 d-flex flex-column align-items-center top-tweleverem-margin" id="freedemo">
    <div class="width_80 d-flex position-relative">
        <img src="<?= base_url('assets/v1/app/images/boy.webp'); ?>" class="form_boy-pic d-lg-flex d-none" alt="man image" />
        <div class="purple_bubble_div d-lg-flex d-none">
            <div class="purple_bubble"></div>
        </div>
        <div class="form_section px-5 d-flex flex-column justify-content-center">
            <div class="d-flex flex-column align-items-center mb-5">
                <p class="lightgrey heading-h4-grey heading opacity-eighty">
                <?php echo $homeContent['page']['sections']['section16']['heading']?>

                </p>
                <p class="darkgrey title"><?php echo $homeContent['page']['sections']['section16']['heading2']?></p>
            </div>
            <form method="post" action="<?= base_url('schedule_your_free_demo'); ?>" class="form" id="schedule-free-demo-form">
                <div class="form-group">
                    <input type="text" class="form-control" id="name" placeholder="Name*" name="name" required />
                    <?php echo form_error('name'); ?>
                </div>
                <div class="form-group mt-4">
                    <input type="email" class="form-control" id="email_id" placeholder="Email*" name="email" required />
                    <?php echo form_error('email'); ?>
                </div>
                <div class="form-group mt-4">
                    <input type="text" class="form-control" id="phone_number" placeholder="Phone Number*" name="phone_number" required />
                    <?php echo form_error('phone_number'); ?>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="title*" name="job_roles" required />
                            <?php echo form_error('title'); ?>

                        </div>
                    </div>
                    <div class="col-sm-6 col-12 mt-sm-0 mt-4">
                        <select class="form-select select-box" aria-label="Default select example" name="company_size">
                            <option selected>Employee Count*</option>
                            <option value="1-5">1 - 5</option>
                            <option value="6-25">6 - 25</option>
                            <option value="26-50">26 - 50</option>
                            <option value="51-100">51 - 100</option>
                            <option value="101-250">101 - 250</option>
                            <option value="251-500">251 - 500</option>
                            <option value="501+">501+</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mt-4">
                    <input type="text" class="form-control" placeholder="Company Name*" name="company_name" required />
                    <?php echo form_error('company_name'); ?>

                </div>
                <div class="form-group mt-4">
                    <textarea class="form-control textarea" id="client_message" rows="3" placeholder="Message" name="client_message"></textarea>
                </div>

                <div class="form-group mt-4">
                    <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                    <?php echo form_error('g-recaptcha-response'); ?>
                </div>

                <button class="button p-3 explore_btn schedule-btn d-flex text-white mt-4 width_100 mb-lg-0 mb-5 auto-schedule-btn" id="schedule-free-demo-form-submit" type="submit">
                    <p class="mb-0 btn-text">
                        Schedule Your No Obligation Consultation
                    </p>
                    <i class="fa-solid fa-arrow-right schedule-btn-adj top-button-icon ps-3"></i>
                </button>

            </form>
        </div>
        <div class="yellow-bubble-div d-lg-block d-none">
            <div class="yellow-bubble"></div>
        </div>
    </div>
    <div class="width_100 auto-schedule-btn-height"></div>
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
                }
            },
            submitHandler: function (form) {
                //
                                
                if($('#g-recaptcha-response').val() == ''){
                    alertify.alert('Captcha is required.');
                    return;
                }

                var myurl = "<?= base_url() ?>demo/check_already_applied";
                $.ajax({
                    type: "POST",
                    url: myurl,
                    data: {email: $('#email_id').val()},
                    dataType: "json",
                    success: function (data) {
                        var obj = jQuery.parseJSON(data);
                        if (obj == 0) {
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