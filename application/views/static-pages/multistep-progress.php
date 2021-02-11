<!-- Main Start -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="multistep-progress-form">
                    <!-- multistep form -->
                    <form class="msform">
                        <!-- progressbar -->
                        <ul class="progressbar">
                            <li class="active">create</li>
                            <li>Details</li>
                            <li>Advertise</li>
                            <li>Share</li>
                        </ul>
                        <!-- fieldsets -->
                        <fieldset>
                            <div class="job-title-text">                
                                <p>Enter the information about your job posting here.<br>
                                    Fields marked with an asterisk (<span>*</span>) are mandatory.
                                </p>
                            </div>
                            <div class="universal-form-style-v2">
                                <ul>
                                    <li class="form-col-50-left">
                                        <label>Title:<span class="staric">*</span></label>
                                        <input type="text" name="Title" class="invoice-fields">
                                    </li>
                                    <li class="form-col-50-right">
                                        <label>Job Type:</label>
                                        <div class="hr-select-dropdown">
                                            <select name="JobType" class="invoice-fields">
                                                <option value="Full Time">Full Time</option>	
                                                <option value="Part Time">Part Time </option>	
                                            </select>
                                        </div>
                                    </li>
                                    <li class="form-col-50-left">
                                        <label>Country:</label>
                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields">
                                                <option selected="" value=""> Select Country </option>
                                                <option>Canada</option>
                                                <option>United State</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="form-col-50-right">
                                        <label>State:</label><div class="hr-select-dropdown">
                                            <select class="invoice-fields">
                                                <option selected="" value=""> Select State </option>
                                                <option>Please Select Your Country </option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="form-col-50-left">
                                        <label>City:</label>
                                        <input type="text" name="Location_City" class="invoice-fields">
                                    </li>
                                    <li class="form-col-50-right">
                                        <label>Zip Code:</label>
                                        <input type="text" name="Location_ZipCode" class="invoice-fields">
                                    </li>
                                    <li class="form-col-50-left">
                                        <label>Listing Logo:</label>
                                        <div class="upload-file invoice-fields">
                                            <span id="name_pictures" class="selected-file">No file selected</span>
                                            <input type="file" name="pictures">
                                            <a href="javascript:;">Choose File</a>
                                        </div>
                                    </li>
                                    <li class="form-col-50-right">
                                        <label>Youtube video for this job:</label>
                                        <input type="text" placeholder="Youtube Video Link" value="" name="YouTube_Video" class="invoice-fields">
                                        <div style="font-style: italic;" class="video-link"><b>e.g.</b> https://www.youtube.com/watch?v=XXXXXXXXXXX </div>
                                    </li>
                                    <li class="form-col-100">
                                        <label>Screening Questionnaire:</label>
                                        <div class="hr-select-dropdown">						
                                            <select class="invoice-fields">
                                                <option value="" selected="">Select Screening Questionnaire</option>
                                                <option>Question 1</option>
                                                <option>Question 2</option>
                                                <option>Question 3</option>
                                                <option>Question 4</option>
                                                <option>Question 5</option>
                                                <option>Question 6</option>                             
                                            </select>
                                        </div>								
                                    </li>
                                </ul>
                            </div>
                            <input type="button" name="next" class="submit-btn next" value="Next" />
                        </fieldset>
                        <fieldset>
                            <div class="job-title-text">                
                                <p>Fields marked with an asterisk (<span>*</span>) are mandatory.</p>
                            </div>
                            <div class="universal-form-style-v2">
                                <ul>
                                    <li class="form-col-100">
                                        <label>Job Category(s):<span class="staric">*</span></label>
                                        <div class="hr-select-dropdown">
                                            <select class="invoice-fields">
                                                <option>- Please Select -</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="form-col-50-left">
                                        <label>Salary:</label>
                                        <input type="text" value="" id="salary" name="salary" class="invoice-fields">
                                    </li>
                                    <li class="form-col-50-right">
                                        <label>Salary Type:</label>
                                        <div class="hr-select-dropdown">
                                            <select name="SalaryType" class="invoice-fields">
                                                <option value="">Select Salary Type</option>
                                                <option value="per_hour">per hour</option>
                                                <option value="per_week">per week</option>
                                                <option value="per_month">per month</option>
                                                <option value="per_year">per year</option>
                                            </select>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div class="description-editor">
                                            <label>Job Description:<span class="staric">*</span></label>
                                            <textarea class="ckeditor"  name="JobDescription" id="JobDescription" 
                                                      cols="67" rows="6"><?php echo set_value('JobDescription'); ?></textarea>
                                                      <?php echo form_error('JobDescription'); ?>
                                        </div>
                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <div class="description-editor">
                                            <label>Job Requirements:</label>
                                            <textarea class="ckeditor"  name="JobRequirements" id="JobRequirements" 
                                                      cols="67" rows="6"><?php echo set_value('JobRequirements'); ?></textarea>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <input type="button" name="previous" class="previous submit-btn" value="Previous" />
                            <input type="button" name="next" class="next submit-btn" value="Next" />
                        </fieldset>
                        <fieldset>
                            <div class="top-search-area">
                                <div class="row">
                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                                        <div class="search-box universal-form-style-v2">
                                            <input class="invoice-fields" type="text" placeholder="Search job Boards">
                                            <button class="btn-search"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                                        <div class="cart-header">
                                            <span class="cart-info">
                                                <label class="cart-label cart-heading">
                                                    <i class="fa fa-shopping-cart"></i>
                                                </label>
                                                <span class="cart-value cart-heading">
                                                    <span>0</span>
                                                </span>
                                            </span>
                                            <a href="javascript:;" class="cart-button-link">View Cart</a>
                                            <div class="view-cart">
                                                <div class="cart-header-inner">
                                                    <p>Your cart ( 2 items )</p>
                                                </div>
                                                <div class="cart-body">
                                                    <article>
                                                        <figure><img src="<?= base_url() ?>assets/images/img-cart-1.jpg"></figure>
                                                        <div class="text">
                                                            <p>1 job listing - all other locations. Includes $10 service fee</p>
                                                            <p>0 Candidates</p>
                                                            <p>$10</p>
                                                        </div>
                                                        <a class="remove-item-btn" href="javascript:;">X</a>
                                                    </article>
                                                    <article>
                                                        <figure><img src="<?= base_url() ?>assets/images/job-arrive-logo.jpg"></figure>
                                                        <div class="text">
                                                            <p>5-Postings package</p>
                                                            <p>0 Candidates</p>
                                                            <p>$179.99</p>
                                                        </div>
                                                        <a class="remove-item-btn" href="javascript:;">X</a>
                                                    </article>
                                                </div>
                                                <div class="cart-footer">
                                                    <div class="sub-total-count">
                                                        <label>Subtotal:</label>
                                                        <p>$189</p>
                                                    </div>
                                                    <div class="check-out-btn">
                                                        <a href="javascript:;">Checkout</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tagline-area">
                                <h4><span style="color:#00a700;">Advertise your job</span> <br/>Target the right audience</h4>
                            </div>
                            <div class="advertising-boxes">
                                <article>
                                    <figure><img src="<?= base_url() ?>assets/images/indeed-logo.jpg"></figure>
                                    <h2 class="post-title">30 days job posting</h2>
                                    <div class="count-box">$499</div>
                                    <div class="button-panel">
                                        <a class="site-btn" href="">Add</a>
                                    </div>
                                </article>
                                <article>
                                    <figure><img src="<?= base_url() ?>assets/images/img-compny-6.png"></figure>
                                    <h2 class="post-title">30 days job posting</h2>
                                    <div class="count-box">$499</div>
                                    <div class="button-panel">
                                        <a class="site-btn" href="">Add</a>
                                    </div>
                                </article>
                                <article>
                                    <figure><img src="<?= base_url() ?>assets/images/img-compny-5.png"></figure>
                                    <h2 class="post-title">30 days job posting</h2>
                                    <div class="count-box">$499</div>
                                    <div class="button-panel">
                                        <a class="site-btn" href="">Add</a>
                                    </div>
                                </article>
                                <article>
                                    <figure><img src="<?= base_url() ?>assets/images/img-compny-2.png"></figure>
                                    <h2 class="post-title">30 days job posting</h2>
                                    <div class="count-box">$499</div>
                                    <div class="button-panel">
                                        <a class="site-btn" href="">Add</a>
                                    </div>
                                </article>
                            </div>
                            <input type="button" name="previous" class="previous submit-btn" value="Previous" />
                            <input type="button" name="next" class="next submit-btn" value="Next" />
                        </fieldset>
                        <fieldset>
                            <div class="social-media-section">
                                <div class="social-media-tagline">
                                    <h4> <p style="color:#00a700;" >SHARE THIS JOB ON SOCIAL NETWORKS</p>Share this job publishing on LinkedIn, Twitter, Facebook & Google Plus</h4>
                                </div>
                                <div class="share-icons">
                                    <ul>
                                        <li><a style="background-color:#df4a32;" href="javascript:;"><i class="fa fa-google-plus"></i>Google Plus</a></li>
                                        <li><a style="background-color:#007ab9;" href="javascript:;"><i class="fa fa-linkedin"></i>Linkedin</a></li>
                                        <li><a style="background-color:#3a589b;" href="javascript:;"><i class="fa fa-facebook"></i>Facebook</a></li>
                                        <li><a style="background-color:#33ccff;" href="javascript:;"><i class="fa fa-twitter"></i>Twitter</a></li>
                                    </ul>
                                </div>
                                <div class="social-media-tagline">
                                    <h4> <p style="color:#007ab9; text-transform: uppercase;" >Done. Take me to the job.</p></h4>
                                </div>
                            </div>
                            <input type="button" name="previous" class="previous submit-btn" value="Previous" /><!-- 
                            <input type="submit" name="submit" class="submit submit-btn" value="Submit" /> -->
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->
<script type="text/javascript">
//jQuery time
    var current_fs, next_fs, previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches

    $(".next").click(function () {
        if (animating)
            return false;
        animating = true;

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        //activate next step on progressbar using the index of next_fs
        $(".progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale current_fs down to 80%
                scale = 1 - (1 - now) * 0.2;
                //2. bring next_fs from the right(50%)
                left = (now * 50) + "%";
                //3. increase opacity of next_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'transform': 'scale(' + scale + ')'});
                next_fs.css({'left': left, 'opacity': opacity});
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
        });
    });

    $(".previous").click(function () {
        if (animating)
            return false;
        animating = true;

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //de-activate current step on progressbar
        $(".progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function (now, mx) {
                //as the opacity of current_fs reduces to 0 - stored in "now"
                //1. scale previous_fs from 80% to 100%
                scale = 0.8 + (1 - now) * 0.2;
                //2. take current_fs to the right(50%) - from 0%
                left = ((1 - now) * 50) + "%";
                //3. increase opacity of previous_fs to 1 as it moves in
                opacity = 1 - now;
                current_fs.css({'left': left});
                previous_fs.css({'transform': 'scale(' + scale + ')', 'opacity': opacity});
            },
            duration: 800,
            complete: function () {
                current_fs.hide();
                animating = false;
            },
        });
    });

    $(".submit").click(function () {
        return false;
    })
</script>