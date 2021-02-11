<div class="page-banner">
    <div class="button-panel" style="position: relative;">
<!--        <a class="site-btn" href="<?= base_url('register') ?>">Get The First 15 Days For Free</a>-->
    </div>
</div>
<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <div class="hr-demo-page-wrapper">
                    <div class="hr-demo-image">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <img src="<?= base_url() ?>assets/images/monitors_ahr.png" alt="Schedule your free demo at <?php echo STORE_NAME; ?>"><!-- final-demo-png-new.png -->
                    </div>
                    <div class="hr-demo-form">
                        <h2><?php echo STORE_NAME; ?> - See it to Believe it</h2>
                        <div class="view-company-by-candidate">
                            <h4 class="text-center text-color"><strong>HOW DO THE BEST JOB CANDIDATES VIEW YOUR COMPANY?</strong></h4>
                            <ul class="col-lg-offset-1">
                                <li>Improve Your Employer Brand</li>
                                <li>Hiring is not just about getting more applications, it’s about getting better ones that will make your competitors jealous. </li>
                                <li>When it comes to attracting top candidates, your employer brand influences the applicants you receive.</li>
                                <li>Of course you think your company is awesome, but how do job candidates perceive your employer brand? </li>
                                <li>Whether your career page needs a revamp or you’re just looking for new inspiration to ignite your employer brand, we’re here to help.</li>
                                <li>So, how does your employer brand shape up?<br/> Hop on a call with one of our Talent experts to learn how you can improve your employer brand and start attracting top talent today.
We will take the time and walk you through the many ways that AutomotoHR can be tailor made and customized to your specific sourcing and hiring needs.</li>
                            </ul>
                        </div>
                        <div class="form-wrapper">
                            <ul>
                                <form method="post" action="" class="form" id="demo-form"> 
                                    <li>
                                        <label>First Name <span class="staric">*</span></label>
                                        <input type="text" name="first_name" class="demo-form-fields" required="required">
                                        <?php echo form_error('first_name');?>
                                    </li>
                                    <li>
                                        <label>Last Name <span class="staric">*</span></label>
                                        <input type="text" name="last_name" class="demo-form-fields" required="required">
                                        <?php echo form_error('last_name');?>
                                    </li>
                                    <li>
                                        <label>Job Role <span class="staric">*</span></label>
                                        <div class="hr-select-dropdown">
                                            <select class="demo-form-fields" name="job_role" required="required">
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
                                        <?php echo form_error('job_role');?>
                                    </li>
                                    <li>
                                        <label>Work Email <span class="staric">*</span></label>
                                        <input type="email" name="email" class="demo-form-fields" required="required">
                                        <?php echo form_error('email');?>
                                    </li>
                                    <li>
                                        <label>Phone Number <span class="staric">*</span></label>
                                        <input type="text" name="phone_number" class="demo-form-fields" required="required">
                                        <?php echo form_error('phone_number');?>
                                    </li>
                                    <li>
                                        <label>Company Name <span class="staric">*</span></label>
                                        <input type="text" name="company_name" class="demo-form-fields" required="required">
                                        <?php echo form_error('company_name');?>
                                    </li>
                                    <li>
                                        <label>Company Size <span class="staric">*</span></label>
                                        <div class="hr-select-dropdown">
                                            <select class="demo-form-fields" name="company_size" required="required">
                                                <option value="" selected="selected">Select Company Size</option>
                                                <option value="1-49">1-49</option>
                                                <option value="50-99">50-99</option>
                                                <option value="100-499">100-499</option>
                                                <option value="500-999">500-999</option>
                                                <option value="1000-2499">1000-2499</option>
                                                <option value="2500-4999">2500-4999</option>
                                                <option value="5000+">5000+</option>
                                            </select>
                                        </div>
                                        <?php echo form_error('company_size');?>
                                    </li>
                                    <li>
                                        <label>Country <span class="staric">*</span></label>
                                        <div class="hr-select-dropdown">
                                            <select class="demo-form-fields" id="country" name="country" onchange="getStates(this.value, <?php echo $states; ?>)" required="required">
                                                <option value="" selected="selected">Select Country</option>
                                                <?php foreach ($active_countries as $active_country) { ?>
                                                    <option value="<?php echo $active_country["sid"]; ?>">
                                                    <?php echo $active_country["country_name"]; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php echo form_error('country');?>
                                    </li>                                            
                                    <li>
                                        <label>State/Region <span class="staric">*</span></label>
                                        <div class="hr-select-dropdown">
                                            <select class="demo-form-fields" id="state" name="state" required="required">
                                                <option value="">Select State</option>  
                                                <option value="">Please select your Country</option>
                                            </select>
                                        </div>
                                        <?php echo form_error('state');?>
                                    </li>
                                    <li>
                                        <label>How did you hear about us? <span class="staric">*</span></label>
                                        <input type="text" name="client_source" id="client_source" class="demo-form-fields" required="required">
                                        <?php echo form_error('client_source');?>
                                    </li>
                                    <!-- <li class="fullwidth-row">
                                        <input type="checkbox" id="getcontent">
                                        <label for="getcontent">Get the latest content & news sent to your inbox.</label>
                                    </li>-->
                                    <li class="fullwidth-row autoheight">
                                        <label>Your Message</label>
                                        <textarea id="client_message" name="client_message" class="demo-form-fields demo-form-fields-textarea" rows="10"></textarea>
                                    </li>
                                    <li class="fullwidth-row">
                                        <input type="submit" value="Request Demo" onclick="validate_form()" class="site-btn">
                                    </li>
                                </form>
                            </ul>
                        </div>
                    </div>
                    <div class="demo-page-heading">
                        <h1>Join one of our Hiring Success advisors, for a personal full length Demo & see how to: </h1>
                    </div>
                    <div class="demo-page-list">
                        <ul class="col-lg-offset-1">
                            <li>Build your Company Career pages in minutes</li>
                            <li>Post to all of your preferred job boards</li>
                            <li>Hire on the go with your mobile optimized hiring system</li>
                            <li>Easily evaluate candidates and collaborate with teams to make the best hire</li>
                            <li>Maintain all of your Companies pertinent and legally required hiring information, government compliance forms and data all in one place, with a single log in.</li>
                        </ul>
                    </div>
                    <div class="address-panel">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">Contact one of our Talent Network Partners at</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <h5>Sales executive</h5>
                            <ul>                                
                                <li><i class="fa fa-phone"></i><?php echo TALENT_NETWORK_SALE_CONTACTNO; ?></li>
                                <li><a href="mailto:<?php echo TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo TALENT_NETWORK_SALES_EMAIL; ?></a></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                            <h5>Customer Service and Technical Support</h5>
                            <ul>                                
                                <li><i class="fa fa-phone"></i><?php echo TALENT_NETWORK_SUPPORT_CONTACTNO; ?></li>
                                <li><a href="mailto:<?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope"></i> <?php echo TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></li>
                            </ul>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo base_url('assets/js/additional-methods.min.js'); ?>"></script>
<script type="text/javascript">
    function validate_form() {
        $("#demo-form").validate({
            ignore: ":hidden:not(select)",
            rules: {
                first_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                last_name: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- .]+$/
                },
                company_name: {
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                client_source: {
                    required: true,
                    pattern: /^[a-zA-Z0-9\- ]+$/
                },
                client_message: {
                    //pattern: /^[a-zA-Z0-9\- .?]+$/
                }

            },
            messages: {
                first_name: {
                    required: 'Please provide first name',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                last_name: {
                    required: 'Please provide last name',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                company_name: {
                    required: 'Please provide your Company Name',
                    pattern: 'Letters, numbers, and dashes only please'
                },
                email: "Please provide valid email address",
                phone_number: "Please provide valid number",
                company_size: "Please provide your Company Size",
                state: "Please select your State",
                country: "Please select your Country",
                job_role: "Please provide your Job Role",
                client_source: {
                    required: "Please tell how did you find about us.",
                    pattern: 'Letters, numbers, and dashes only please'
                },
                client_message: {
                    //pattern: 'Message can be Alphabets Numerals Blank Space and . - ? only.'
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    function getStates(val, states) {
        var html = '';
        if (val == '') {
            $('#state').html('<option value="">Select State</option><option value="">Please select your Country</option>');
        } else {
            html = '<option value="">Select State</option>';
            allstates = states[val];
            for (var i = 0; i < allstates.length; i++) {
                var id = allstates[i].sid;
                var name = allstates[i].state_name;
                html += '<option value="' + name + '">' + name + '</option>';
            }
            $('#state').html(html);
        }
    }
</script>
<!-- Global site tag (gtag.js) - Google AdWords: 937581220 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-937581220"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'AW-937581220');
</script>
<!-- Event snippet for AutomotoHR conversion page -->
<script>
gtag('event', 'conversion', {'send_to': 'AW-937581220/X7THCOeGhHkQpLWJvwM'});
</script>