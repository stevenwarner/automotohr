<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    function validate_friend_form() {
        $("#friend-form").validate({
            // Specify the validation error messages
            rules: {
                sender_name: {
                    required: true
                },
                receiver_name: {
                    required: true
                },
                receiver_email: {
                    required: true
                }


            },
            messages: {
                sender_name: "Please provide your name",
                receiver_name: "Please provide receiver name",
                receiver_email: "Please provide valid email address",
            }
        });

        if ($('#friend-form').valid()) {

            $('#friend-form').submit();


        }

    }
    $(document).ready(function() {
        $('.g-recaptcha-err').each(function() {
            $(this).hide();
        });




        $('.eeo_check').click(function() {
            if ($('#eeo_yes').is(':checked')) {
                //show form
                $('.questionnaire-form').slideDown('slow');

                $("input[name='us_citizen']").prop('required', true);
                $("input[name='group_status']").prop('required', true);
                $("input[name='veteran']").prop('required', true);
                $("input[name='disability']").prop('required', true);
                $("input[name='gender']").prop('required', true);


            } else {
                //hide form
                $('.questionnaire-form').slideUp('slow');

                $("input[name='us_citizen']").prop('required', false);
                $("input[name='group_status']").prop('required', false);
                $("input[name='veteran']").prop('required', false);
                $("input[name='disability']").prop('required', false);
                $("input[name='gender']").prop('required', false);
                $("#visa_status").prop('required', false);

            }

        });

        $('.citizen_check').click(function() {
            if ($('#citizen-yes').is(':checked')) {

                $("#visa_status").prop('required', false);
                $(".visa_status_div").css('display', 'none');

            } else {
                $("#visa_status").prop('required', true);
                $(".visa_status_div").css('display', 'block');

            }
        });
    });



    function fSendTellAFriendEmail() {
        $('#form_tell_a_friend').validate({
            rules: {
                sender_mame: {
                    required: true
                },
                receiver_name: {
                    required: true
                },
                receiver_email: {
                    required: true,
                    pattern: /\b[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}\b/i
                },
                message: {
                    required: true
                }

            }
        });

        var gReCaptchaResponse = grecaptcha.getResponse();

        //console.log(gReCaptchaResponse);

        if ($('#form_tell_a_friend').valid()) {
            if (gReCaptchaResponse != '') {
                $('.g-recaptcha-err').each(function() {
                    $(this).hide();
                });

                $('#form_tell_a_friend').submit();
            } else {
                $('.g-recaptcha-err').each(function() {
                    $(this).show();
                });
            }
            //console.log($('#form_tell_a_friend').valid());
        }
    }
</script>
<div class="modal modal-fullscreen fade" id="tellAFriendModal" tabindex="-1" role="dialog" aria-labelledby="tellAFriendModal">
    <div class="modal-dialog custom-popup" role="document">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading" id="myModalLabel">Share This Job With Your Friend</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="tellafriend-job-title"><span>Recommend :</span><?php echo (isset($job_details['Title']) ? $job_details['Title'] : ''); ?></h3>
                    </div>
                </div>
                <div class="form-wrp">
                    <form class="popup-form" action="" method="post" name="friend-form" id="friend-form">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Name:<span class="staric">*</span></label>
                                    <input class="form-control" type="text" placeholder="Your Name" name="sender_name" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Email:<span class="staric">*</span></label>
                                    <input class="form-control" type="email" placeholder="Your Email" name="sender_email" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Friend's Name:<span class="staric">*</span></label>
                                    <input class="form-control" type="text" placeholder="Your Friend's Name" name="receiver_name" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Friend's Email Address:<span class="staric">*</span></label>
                                    <input class="form-control" type="email" name="receiver_email" required="required" placeholder="Receiver Email Address">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Your Comment(s):<span class="staric">*</span></label>
                                    <textarea class="form-control textarea" name="comment" required="required" placeholder=""></textarea>
                                </div>
                            </div>
                            <?php if (is_subdomain_of_automotohr()) { ?>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="form-group">
                                        <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                        <input type="hidden" id="captcha" name="captcha" value="" />
                                        <p class="g-recaptcha-err error">Prove Your are a Human!</p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <input type="hidden" name='job_sid' value="<?php echo $job_details['sid']; ?>">
                                <input type="hidden" name="action" value="friendShare">
                                <input class="siteBtn bg-color" type="button" onclick="validate_friend_form()" value="Send">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>