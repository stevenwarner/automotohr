<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="modal fade" id="myModalFriend" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog custom-popup" role="document">
        <div class="modal-content">
            <div class="modal-header border-none">
                <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title modal-heading" id="myModalLabelFriend">Share this Job with Your Friend</h4>
            </div>
            <div class="modal-body">
                <div class="form-wrp">
                    <form class="popup-form" action="" method="post" name="friend-form" id="friend-form">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Name:<span class="staric">*</span></label>
                                    <input class="form-fields" type="text"  placeholder="Your Name" name="sender_name" required="required" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Email:<span class="staric">*</span></label>
                                    <input class="form-fields" type="email"  placeholder="Your Email" name="sender_email" required="required" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Friend's Name:<span class="staric">*</span></label>
                                    <input class="form-fields" type="text"  placeholder="Your Friend's Name" name="receiver_name" required="required" >
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label>Your Friend's Email Address:<span class="staric">*</span></label>
                                    <input class="form-fields" type="email"  name="receiver_email" required="required"  placeholder="Receiver Email Address">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="form-group autoheight">
                                    <label>Your Comment(s):<span class="staric">*</span></label>
                                    <textarea class="form-control textarea"  name="comment" required="required" placeholder="Enter Comments"></textarea>

                                </div>
                            </div>
                            <?php if(is_subdomain_of_automotohr()){ ?>
                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                    <div class="g-recaptcha" data-sitekey="6Les2Q0TAAAAAAyeysl-dZsPUm98_6K2fNkyNCwI"></div>
                                    <input type="hidden" id="captcha" name="captcha" value="" />
                                    <p class="g-recaptcha-err error">Prove Your are a Human!</p>
                                </div>
                            <?php } ?>
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <input type="hidden" name='job_sid'  value="<?php echo $job_details['sid']; ?>">
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
