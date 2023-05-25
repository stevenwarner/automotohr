<!-- Main Start -->
<div class="main-content">
    <div class="container">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $(".tab_content").hide();
                            $(".tab_content:first").show();

                            $("ul.tabs li").click(function () {
                                $("ul.tabs li").removeClass("active");
                                $(this).addClass("active");
                                $(".tab_content").hide();
                                var activeTab = $(this).attr("rel");
                                $("#" + activeTab).fadeIn();
                            });

                        });
                    </script>
                    <div class="application-header">
                        <article>
                            <figure><img src="<?= base_url() ?>assets/images/applican-img.jpg"></figure>
                            <div class="text">
                                <h2>Peter Carry</h2>
                                <span>Applicant</span>
                            </div>
                        </article>
                    </div>
                    <div class="tabs-wrp">					
                        <ul class="tabs"> 
                            <li class="active" rel="tab1"><a href="javascript:;">Personal Info</a></li>
                            <li rel="tab2"><a href="javascript:;">Questionnaire</a></li>
                            <li rel="tab3"><a href="javascript:;">Notes</a></li>
                            <li rel="tab4"><a href="javascript:;">Messages</a></li>
                            <li rel="tab5"><a href="javascript:;">reviews</a></li>
                            <li rel="tab5"><a href="javascript:;">Calendar</a></li>
                        </ul>
                        <div class="tab_container">
                            <div id="tab1" class="tab_content">
                                <div class="universal-form-style-v2">
                                    <ul>
                                        <form>
                                            <div class="form-title-section">
                                                <h2>Personal Information</h2>
                                                <div class="form-btns">
                                                    <input type="submit" value="update">
                                                </div>												
                                            </div>
                                            <li class="form-col-50-left">
                                                <label>frist name:</label>
                                                <input class="invoice-fields error" type="text" name="fname">
                                                <label class="error">Please fill the required field</label>
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>last name:</label>
                                                <input class="invoice-fields" type="text" name="lname">
                                            </li>								
                                            <li class="form-col-50-left">
                                                <label>email:</label>
                                                <input class="invoice-fields" type="text" name="city">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>Primary number:</label>
                                                <input class="invoice-fields" type="text" name="zipcode">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>address:</label>
                                                <input class="invoice-fields" type="text" name="zipcode">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>city:</label>
                                                <input class="invoice-fields" type="text" name="zipcode">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>date applied:</label>
                                                <input class="invoice-fields" type="text" name="zipcode">
                                            </li>
                                            <li class="form-col-50-right">
                                                <label>zipcode:</label>
                                                <input class="invoice-fields" type="text" name="zipcode">
                                            </li>
                                            <li class="form-col-50-left">
                                                <label>country:</label>								
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields">
                                                        <option>Pakistan</option>
                                                        <option>India</option>
                                                        <option>Canada</option>
                                                        <option>Japan</option>
                                                        <option>USA</option>
                                                    </select>
                                                </div>								
                                            </li>
                                            <li class="form-col-50-right">	
                                                <label>state:</label>									
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields">
                                                        <option>Punjab</option>
                                                        <option>California</option>
                                                        <option>London</option>
                                                    </select>
                                                </div>								
                                            </li>
                                        </form>
                                    </ul>
                                </div>
                                <div class="applicant-video">
                                    <iframe src="http://player.vimeo.com/video/57564747" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                                </div>
                                <div class="employement-application-form universal-form-style-v2">
                                    <div class="form-title-section">
                                        <h2>Employment Application</h2>							
                                    </div>
                                    <ul>
                                        <form>
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>First Name <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="fname">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Middle Name <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="mname">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Last Name <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="lname">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Suffix <span class="staric">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">

                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Social Security Number <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="ssn">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Date of Birth <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="dob">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="autoheight">
                                                            Your date of birth is required and may be used for purposes directly related to the background check process and will not be used for any other purpose. Failure to provide your date of birth may cause a delay in processing your application for employment.
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Email Address <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="email" name="email">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Confirm Email Address <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="email" name="confirm-email">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="autoheight">
                                                            Your email address is required and is used for purposes directly related to the application process and/or legally required notifications. Your email address will not be shared or used for any other purpose.
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Current Residence <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="cr">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>How Long?</label>
                                                            <input class="invoice-fields" type="text" name="address-length">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>City <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="city">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>State <span class="staric">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>Punjab</option>
                                                                    <option>California</option>
                                                                    <option>London</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Zip Code <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="zip-code">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="checkbox-field">
                                                                <input id="none-usa" type="checkbox" name="checkbox">
                                                                <label for="none-usa">Non USA Address</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Former Residence<span class="staric">*</span></label>
                                                                    <input class="invoice-fields" type="text" name="cr">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>How Long?</label>
                                                                    <input class="invoice-fields" type="text" name="address-length">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>City <span class="staric">*</span></label>
                                                                    <input class="invoice-fields" type="text" name="city">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>State <span class="staric">*</span></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>Punjab</option>
                                                                            <option>California</option>
                                                                            <option>London</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Zip Code <span class="staric">*</span></label>
                                                                    <input class="invoice-fields" type="text" name="zip-code">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <li class="form-col-100 autoheight">
                                                                    <div class="checkbox-field">
                                                                        <input id="none-usa" type="checkbox" name="checkbox">
                                                                        <label for="none-usa">Non USA Address</label>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Former Residence<span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="cr">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>How Long?</label>
                                                            <input class="invoice-fields" type="text" name="address-length">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>City <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="city">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>State <span class="staric">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>Punjab</option>
                                                                    <option>California</option>
                                                                    <option>London</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Zip Code <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="zip-code">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="checkbox-field">
                                                                <input id="none-usa" type="checkbox" name="checkbox">
                                                                <label for="none-usa">Non USA Address</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li>
                                                            <label>Other Mailing Address</label>
                                                            <input class="invoice-fields" type="text" name="cr">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>City <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="city">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>State <span class="staric">*</span></label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>Punjab</option>
                                                                    <option>California</option>
                                                                    <option>London</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Zip Code <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="zip-code">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Primary Telephone <span class="staric">*</span></label>
                                                            <input class="invoice-fields" type="text" name="city">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Mobile Telephone </label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>+00921234567</option>
                                                                    <option>+00925238567</option>
                                                                    <option>+00929274567</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Other Telephone </label>
                                                            <input class="invoice-fields" type="text" name="zip-code">
                                                        </li>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li class="form-col-100 autoheight">
                                                                    <label class="autoheight">The position I am applying for is:<span class="staric">*</span></label>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li class="form-col-100 autoheight">
                                                                    <div class="hr-radio-btns">
                                                                        <input id="full-time" type="radio" value="">
                                                                        <label for="full-time">Full time</label>
                                                                    </div>
                                                                    <div class="hr-radio-btns">
                                                                        <input id="part-time" type="radio" value="">
                                                                        <label for="part-time">Part time</label>
                                                                    </div>
                                                                    <div class="hr-radio-btns">
                                                                        <input id="full-or-part" type="radio" value="">
                                                                        <label for="full-or-part">Full or Part time</label>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <li class="form-col-100 autoheight">
                                                                    <label class="autoheight">If you want to apply for more than one position, please list them in this field.</label>
                                                                    <input class="invoice-fields" type="text" name="office-help">
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>What date can you begin work?</label>
                                                            <input type="text" name="startdate" class="invoice-fields">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Expected compensation</label>
                                                            <input type="text" name="enddate" class="invoice-fields">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Do you have transportation to/from work?</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="" id="yes">
                                                                <label for="yes">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="" id="no">
                                                                <label for="no">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color-v2">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li class="form-col-100 autoheight">
                                                                    <label class="autoheight">Are you 18 years or older?</label>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li class="form-col-100 autoheight">
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" value="" id="yes1">
                                                                        <label for="yes1">Yes</label>
                                                                    </div>
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" value="" id="no1">
                                                                        <label for="no1">No</label>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-xs-12 col-sm-10">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Have you ever used or been known by any other names, including nicknames?</label>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                        <li class="form-col-100 autoheight">
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="" id="yes2">
                                                                <label for="yes2">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="" id="no2">
                                                                <label for="no2">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="comment-area">
                                                            <small>If yes, please explain and indicate name(s):</small>
                                                            <textarea class="form-col-100 invoice-fields"></textarea>
                                                            <span>512 Characters Left</span>
                                                            <p>When answering the following questions, do not include minor traffic infractions, ANY convictions for which the record has been sealed and/or expunged, and/or eradicated, any conviction for which probation has been successfully completed or otherwise discharged with the case having been judicially dismissed, any information regarding referrals to and/or participation in any pre-trial or post-trial diversion programs (California applicants only, do not include infractions involving marijuana offenses that occurred over two years ago). A conviction record will not necessarily be a bar to employment. Factors such as age, time of the offense, seriousness and nature of the violation, and rehabilitation will be taken into account.</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <li class="form-col-100 autoheight">
                                                                    <label class="autoheight">Have you ever plead Guilty, No Contest, or been Convicted of a Misdemeanor and/or Felony?</label>
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" value="" id="Felony-yes">
                                                                        <label for="Felony-yes">Yes</label>
                                                                    </div>
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" value="" id="Felony-no">
                                                                        <label for="Felony-no">No</label>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Have you been arrested for any matter for which you are now out on bail or have been released on your own recognizance pending trial?</label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="" id="recognizance-yes">
                                                                <label for="recognizance-yes">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" value="" id="recognizance-no">
                                                                <label for="recognizance-no">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">

                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <li class="form-col-100 autoheight">
                                                                    <label class="autoheight">If yes to either of the above questions, provide dates and details for each, including the case number and court where your case is/was handled:</label>
                                                                    <div class="comment-area">
                                                                        <textarea class="form-col-100 invoice-fields"></textarea>
                                                                        <span>512 Characters Left</span>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <li class="form-col-100 autoheight">
                                                            <label class="autoheight">Driver's License	A valid driver's license may be a requirement for the position for which you have applied.If so, do you currently have a valid driver’s license?</label>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="license-yes" value="">
                                                                <label for="license-yes">Yes</label>
                                                            </div>
                                                            <div class="hr-radio-btns">
                                                                <input type="radio" id="license-no" value="">
                                                                <label for="license-no">No</label>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Driver's license number:</label>
                                                            <input type="text" name="license-number" class="invoice-fields">
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>State of issue:</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>State</option>
                                                                    <option>State</option>
                                                                    <option>State</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                        <li>
                                                            <label>Expiration date:</label>
                                                            <input type="text" name="expiry-date" class="invoice-fields">
                                                        </li>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <li class="form-col-100 autoheight">
                                                                    <label class="autoheight">Within the last 5 years, have you ever plead Guilty, No Contest, or been Convicted of any traffic violation(s)?</label>
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" id="violation-yes" value="">
                                                                        <label for="violation-yes">Yes</label>
                                                                    </div>
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" id="violation-no" value="">
                                                                        <label for="violation-no">No</label>
                                                                    </div>
                                                                </li>
                                                                <li class="form-col-100 autoheight">
                                                                    <small class="autoheight">If yes , provide dates and details for each violation, including the case number and court where your case is/was handled:</small>
                                                                    <div class="comment-area">
                                                                        <textarea class="form-col-100 invoice-fields"></textarea>
                                                                        <span>512 Characters Left</span>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="education-level-block">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100">
                                                                <label>Education- High School</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
                                                                <label class="autoheight">Did you graduate?</label>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="" id="graduate-yes">
                                                                    <label for="graduate-yes">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="" id="graduate-no">
                                                                    <label for="graduate-no">no</label>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>City</label>
                                                                    <input type="text" class="invoice-fields">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>State</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>Punjab</option>
                                                                            <option>California</option>
                                                                            <option>London</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Dates of Attendance</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                                <span class="date-range-text">to</span>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="education-level-block">
                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                    <li class="form-col-100">
                                                                        <label>College/University</label>
                                                                        <input type="text" class="invoice-fields">
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                    <li class="form-col-100 autoheight">
                                                                        <label class="autoheight">Did you graduate?</label>
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                                    <li class="form-col-100 autoheight">
                                                                        <div class="hr-radio-btns">
                                                                            <input type="radio" value="" id="un-graduate-yes">
                                                                            <label for="un-graduate-yes">Yes</label>
                                                                        </div>
                                                                        <div class="hr-radio-btns">
                                                                            <input type="radio" value="" id="un-graduate-no">
                                                                            <label for="un-graduate-no">no</label>
                                                                        </div>
                                                                    </li>
                                                                </div>
                                                                <div class="form-col-100">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label>City</label>
                                                                            <input type="text" class="invoice-fields">
                                                                        </li>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label>State</label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields">
                                                                                    <option>Punjab</option>
                                                                                    <option>California</option>
                                                                                    <option>London</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                    </div>
                                                                </div>
                                                                <div class="form-col-100">
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label>Dates of Attendance</label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields">
                                                                                    <option>November</option>
                                                                                    <option>November</option>
                                                                                    <option>November</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label></label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields">
                                                                                    <option>2015</option>
                                                                                    <option>2015</option>
                                                                                    <option>2015</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                    </div>
                                                                    <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                                        <span class="date-range-text">to</span>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label></label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields">
                                                                                    <option>November</option>
                                                                                    <option>November</option>
                                                                                    <option>November</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                    </div>
                                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label></label>
                                                                            <div class="hr-select-dropdown">
                                                                                <select class="invoice-fields">
                                                                                    <option>2015</option>
                                                                                    <option>2015</option>
                                                                                    <option>2015</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                    </div>
                                                                </div>
                                                                <div class="form-col-100">
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label>Major</label>
                                                                            <input type="text" class="invoice-fields">
                                                                        </li>
                                                                    </div>
                                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                        <li>
                                                                            <label>Degree Earned</label>
                                                                            <input type="text" class="invoice-fields">
                                                                        </li>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="education-level-block">
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100">
                                                                <label>Other School</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
                                                                <label class="autoheight">Did you graduate?</label>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                            <li class="form-col-100 autoheight">
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="" id="un-graduate-yes">
                                                                    <label for="un-graduate-yes">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" value="" id="un-graduate-no">
                                                                    <label for="un-graduate-no">no</label>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>City</label>
                                                                    <input type="text" class="invoice-fields">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>State</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>Punjab</option>
                                                                            <option>California</option>
                                                                            <option>London</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Dates of Attendance</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                                <span class="date-range-text">to</span>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                            <option>November</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label></label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                            <option>2015</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                        <div class="form-col-100">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Major</label>
                                                                    <input type="text" class="invoice-fields">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Degree Earned</label>
                                                                    <input type="text" class="invoice-fields">
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Professional License Type</label>
                                                                    <input type="text" class="invoice-fields" name="">
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>Issuing Agency/State</label>
                                                                    <div class="hr-select-dropdown">
                                                                        <select class="invoice-fields">
                                                                            <option>California</option>
                                                                            <option>Canada</option>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                <li>
                                                                    <label>License Number</label>
                                                                    <input type="text" class="invoice-fields" name="">
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Employment	Current/Most Recent Employer</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Postion/Title</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Address</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>State</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>Califonia</option>
                                                                        <option>Califonia</option>
                                                                        <option>Califonia</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Telephone</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Dates of Employment</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Starting Compensation</label>
                                                                <input type="text" class="invoice-fields" >
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Ending Compensation</label>
                                                                <input type="text" class="invoice-fields" >
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Supervisor</label>
                                                                <input type="text" class="invoice-fields" >
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li class="autoheight">
                                                                <label>Reason for Leaving</label>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li class="autoheight">
                                                                <label class="contact-to-employee">May we contact this employer?</label>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" id="contact-yes" value="">
                                                                    <label for="contact-yes">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" id="contact-no" value="">
                                                                    <label for="contact-no">Yes</label>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100 autoheight">
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="form-col-100">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Previous Employer:</label>
                                                                        <input type="text" class="invoice-fields">
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Postion/Title</label>
                                                                        <input type="text" class="invoice-fields">
                                                                    </li>
                                                                </div>
                                                            </div>
                                                            <div class="form-col-100">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Address</label>
                                                                        <input type="text" class="invoice-fields">
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>City</label>
                                                                        <input type="text" class="invoice-fields">
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>State</label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields">
                                                                                <option>Califonia</option>
                                                                                <option>Califonia</option>
                                                                                <option>Califonia</option>
                                                                            </select>
                                                                        </div>
                                                                    </li>
                                                                </div>
                                                            </div>
                                                            <div class="form-col-100">
                                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Telephone</label>
                                                                        <input type="text" class="invoice-fields">
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Dates of Employment</label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields">
                                                                                <option>November</option>
                                                                                <option>November</option>
                                                                                <option>November</option>
                                                                            </select>
                                                                        </div>
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                                    <li>
                                                                        <label></label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields">
                                                                                <option>2015</option>
                                                                                <option>2015</option>
                                                                                <option>2015</option>
                                                                            </select>
                                                                        </div>
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                                    <span class="date-range-text">to</span>
                                                                </div>
                                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label></label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields">
                                                                                <option>November</option>
                                                                                <option>November</option>
                                                                                <option>November</option>
                                                                            </select>
                                                                        </div>
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                                    <li>
                                                                        <label></label>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields">
                                                                                <option>2015</option>
                                                                                <option>2015</option>
                                                                                <option>2015</option>
                                                                            </select>
                                                                        </div>
                                                                    </li>
                                                                </div>
                                                            </div>
                                                            <div class="form-col-100">
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Starting Compensation</label>
                                                                        <input type="text" class="invoice-fields" >
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Ending Compensation</label>
                                                                        <input type="text" class="invoice-fields" >
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                                    <li>
                                                                        <label>Supervisor</label>
                                                                        <input type="text" class="invoice-fields" >
                                                                    </li>
                                                                </div>
                                                            </div>
                                                            <div class="form-col-100">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <li class="autoheight">
                                                                        <label>Reason for Leaving</label>
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <li class="autoheight">
                                                                        <label class="contact-to-employee">May we contact this employer?</label>
                                                                        <div class="hr-radio-btns">
                                                                            <input type="radio" id="contact-yes" value="">
                                                                            <label for="contact-yes">Yes</label>
                                                                        </div>
                                                                        <div class="hr-radio-btns">
                                                                            <input type="radio" id="contact-no" value="">
                                                                            <label for="contact-no">Yes</label>
                                                                        </div>
                                                                    </li>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                    <li class="form-col-100 autoheight">
                                                                        <input type="text" class="invoice-fields">
                                                                    </li>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Previous Employer</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Postion/Title</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Address</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>City</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>State</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>Califonia</option>
                                                                        <option>Califonia</option>
                                                                        <option>Califonia</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Telephone</label>
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Dates of Employment</label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-1 col-md-1 col-xs-12 col-sm-12">
                                                            <span class="date-range-text">to</span>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                        <option>November</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6 select-year">
                                                            <li>
                                                                <label></label>
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                        <option>2015</option>
                                                                    </select>
                                                                </div>
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Starting Compensation</label>
                                                                <input type="text" class="invoice-fields" >
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Ending Compensation</label>
                                                                <input type="text" class="invoice-fields" >
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <li>
                                                                <label>Supervisor</label>
                                                                <input type="text" class="invoice-fields" >
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="form-col-100">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li class="autoheight">
                                                                <label>Reason for Leaving</label>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <li class="autoheight">
                                                                <label class="contact-to-employee">May we contact this employer?</label>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" id="contact-yes" value="">
                                                                    <label for="contact-yes">Yes</label>
                                                                </div>
                                                                <div class="hr-radio-btns">
                                                                    <input type="radio" id="contact-no" value="">
                                                                    <label for="contact-no">Yes</label>
                                                                </div>
                                                            </li>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <li class="form-col-100 autoheight">
                                                                <input type="text" class="invoice-fields">
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="bg-color">
                                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                <li class="form-col-100 autoheight">
                                                                    <label class="autoheight">Have you ever been laid off or terminated from any job or position? </label>
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" value="" id="terminated-yes">
                                                                        <label for="terminated-yes">Yes</label>
                                                                    </div>
                                                                    <div class="hr-radio-btns">
                                                                        <input type="radio" value="" id="terminated-no">
                                                                        <label for="terminated-no">No</label>
                                                                    </div>
                                                                </li>
                                                                <li class="form-col-100 autoheight">
                                                                    <small class="autoheight">If yes, please explain:</small>
                                                                    <div class="comment-area">
                                                                        <textarea class="form-col-100 invoice-fields"></textarea>
                                                                        <span>128 Characters Left</span>
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </ul>
                                </div>					         
                            </div><!-- #tab1 -->
                            <div id="tab2" class="tab_content">
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        $('.collapse').on('shown.bs.collapse', function () {
                                            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
                                        }).on('hidden.bs.collapse', function () {
                                            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
                                        });
                                    });
                                </script>	
                                <div class="tab-header-sec">
                                    <h2 class="tab-title">Screening Questionnaire</h2>
                                    <div class="tab-btn-panel">
                                        <span>Score : 5</span>
                                        <a href="javascript:;">Pass</a>
                                    </div>
                                    <p class="questionnaire-heading">Question’s / Answer’s</p>
                                </div>
                                <div class="panel-group-wrp">					      	
                                    <div class="panel-group" id="accordion">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                        Quisque odio felis, imperdiet feugiat nibh quis, scelerisque volutpat turpis ?
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    Donec felis urna, lobortis non auctor at, feugiat eget arcu. Praesent mattis condimentum aliquam. Proin mattis erat nunc, sit amet eleifend leo efficitur non. Sed auctor posuere condimentum. Praesent feugiat diam at leo mollis, vel congue urna feugiat. Donec dignissim odio ac dictum laoreet.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                        Quisque odio felis, imperdiet feugiat nibh quis, scelerisque volutpat turpis ?
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    Donec felis urna, lobortis non auctor at, feugiat eget arcu. Praesent mattis condimentum aliquam. Proin mattis erat nunc, sit amet eleifend leo efficitur non. Sed auctor posuere condimentum. Praesent feugiat diam at leo mollis, vel congue urna feugiat. Donec dignissim odio ac dictum laoreet.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                        <span class="glyphicon glyphicon-plus"></span>
                                                        Quisque odio felis, imperdiet feugiat nibh quis, scelerisque volutpat turpis ?
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseThree" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    Donec felis urna, lobortis non auctor at, feugiat eget arcu. Praesent mattis condimentum aliquam. Proin mattis erat nunc, sit amet eleifend leo efficitur non. Sed auctor posuere condimentum. Praesent feugiat diam at leo mollis, vel congue urna feugiat. Donec dignissim odio ac dictum laoreet.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- #tab2 --> 
                            <div id="tab3" class="tab_content">
                                <div class="universal-form-style-v2">
                                    <form>
                                        <div class="form-title-section">
                                            <h2>Applicant Notes</h2>
                                            <div class="form-btns">
                                                <input type="submit" value="save">
                                            </div>												
                                        </div>
                                        <div class="tab-header-sec">
                                            <p class="questionnaire-heading">Miscellaneous Notes</p>
                                        </div>
                                        <div class="applicant-notes">
                                            <div class="hr-ck-editor">
                                                <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                <textarea class="ckeditor" name="CompanyDescription" rows="8" cols="60" ></textarea>
                                            </div>
                                            <!-- <span class="notes-not-found">No Notes Found</span> -->
                                            <article class="notes-list">
                                                <h2><a href="javascript:;">Quisque odio felis, imperdiet feugiat nibh quis, scelerisque volutpat turpis ?</a></h2>
                                                <p>Donec felis urna, lobortis non auctor at, feugiat eget arcu. Praesent mattis condimentum aliquam. Proin mattis erat nunc, sit amet eleifend leo efficitur non. Sed auctor posuere condimentum. Praesent feugiat diam at leo mollis, vel congue urna feugiat. Donec dignissim odio ac dictum laoreet.</p>
                                            </article>
                                            <article class="notes-list">
                                                <h2><a href="javascript:;">Quisque odio felis, imperdiet feugiat nibh quis, scelerisque volutpat turpis ?</a></h2>
                                                <p>Donec felis urna, lobortis non auctor at, feugiat eget arcu. Praesent mattis condimentum aliquam. Proin mattis erat nunc, sit amet eleifend leo efficitur non. Sed auctor posuere condimentum. Praesent feugiat diam at leo mollis, vel congue urna feugiat. Donec dignissim odio ac dictum laoreet.</p>
                                            </article>
                                        </div>
                                    </form>
                                </div>				       
                            </div><!-- #tab3 --> 
                            <div id="tab4" class="tab_content">
                                <form action="" mehtod="post">
                                    <div class="form-title-section">
                                        <h2>messages</h2>
                                        <div class="form-btns message">
                                            <div class="btn-inner">
                                                <input type="file" class="choose-file-filed"> 
                                                <a href="" class="select-photo">Add Attachment</a>
                                            </div>
                                        </div>
                                        <div class="comment-box">
                                            <div class="textarea">
                                                <input class="message-subject" type="text" placeholder="Enter Subject (required)"/>
                                                <textarea></textarea>
                                            </div>
                                        </div>	
                                        <div class="comment-btn">
                                            <input type="submit" value="Send Message">
                                        </div>											
                                    </div>
                                </form>	
                                <div class="respond">
                                    <article>
                                        <figure><img src="<?= base_url() ?>assets/images/attachment-img.png"></figure>
                                        <div class="text">
                                            <div class="message-header">
                                                <div class="message-title"><h2>Peter Carry </h2></div>
                                                <ul class="message-option">
                                                    <li>
                                                        <time>12-18-2015</time>
                                                    </li>
                                                    <li>
                                                        <a class="action-btn" href="javascript:;">
                                                            <i class="fa fa-mail-forward"></i>
                                                            <span class="btn-tooltip">Resend</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="action-btn" href="javascript:;">
                                                            <i class="fa fa-file"></i>
                                                            <span class="btn-tooltip">Download File</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="action-btn remove" href="javascript:;">
                                                            <i class="fa fa-remove"></i>
                                                            <span class="btn-tooltip">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <span class="span">This is CV</span>
                                            <p>Send message with attachment</p>
                                        </div>
                                    </article>
                                    <article class="reply">
                                        <figure><img src="<?= base_url() ?>assets/images/attachment-img.png"></figure>
                                        <div class="text">
                                            <div class="message-header">
                                                <div class="message-title"><h2>Peter Carry </h2></div>
                                                <ul class="message-option">
                                                    <li>
                                                        <time>12-18-2015</time>
                                                    </li>
                                                    <li>
                                                        <a class="action-btn" href="javascript:;">
                                                            <i class="fa fa-mail-forward"></i>
                                                            <span class="btn-tooltip">Resend</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="action-btn" href="javascript:;">
                                                            <i class="fa fa-file"></i>
                                                            <span class="btn-tooltip">Download File</span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="action-btn remove" href="javascript:;">
                                                            <i class="fa fa-remove"></i>
                                                            <span class="btn-tooltip">Delete</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <span>This is CV</span>
                                            <p>Send message with attachment</p>
                                        </div>
                                    </article>
                                </div>			       
                            </div><!-- #tab4 --> 
                            <div id="tab5" class="tab_content">
                                <form action="" method="post">
                                    <div class="form-title-section">
                                        <h2>Calendar & Scheduling</h2>
                                        <div class="form-btns">
                                            <input type="submit" value="New">
                                        </div>												
                                    </div>
                                    <div class="form-col-100">
                                        <div class="cl-title">
                                            <h2>Interview Details</h2>
                                        </div>
                                        <div class="table-responsive calender-table">
                                            <table class="table event-table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <label class="group-label">
                                                                <i class="fa fa-calendar"></i>Date<span class="staric">*</span>
                                                            </label>
                                                        </th>
                                                        <th colspan="4">
                                                            <label class="group-label">
                                                                <i class="fa fa-clock-o"></i>Timezone<span class="staric">*</span>
                                                            </label>
                                                        </th>
                                                        <th>
                                                            <label class="group-label">
                                                                <span><i class="fa fa-map-marker"></i>Localtion</span>
                                                            </label>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="group-element radius-bottom-left">21-05-2015</div>
                                                        </td>
                                                        <td colspan="4">
                                                            <div class="group-element">
                                                                <div class="hr-select-dropdown">
                                                                    <select class="invoice-fields">
                                                                        <option>(GMT-06:00) Central Time US & Canada </option>
                                                                        <option>(GMT-06:00) Central Time US & Canada </option>
                                                                        <option>(GMT-06:00) Central Time US & Canada </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="group-element radius-bottom-left">21-05-2015</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-col-100">
                                        <div class="cl-title">
                                            <h2>Schedule</h2>
                                        </div>
                                        <div class="table-responsive calender-table">
                                            <table class="table event-table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <label class="group-label">Interviewer<span class="staric">*</span>
                                                            </label>
                                                        </th>
                                                        <th>
                                                            <label class="group-label">Time<span class="staric">*</span>
                                                            </label>
                                                        </th>
                                                        <th>
                                                            <label class="group-label">
                                                                <span>Room/Meeting ID</span>
                                                            </label>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="group-element radius-bottom-left">21-05-2015</div>
                                                        </td>
                                                        <td>
                                                            <div class="group-element">
                                                                09:00am    to    10:00am
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="group-element radius-bottom-left">21-05-2015</div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="form-col-100">
                                        <div class="applicant-comments-label">
                                            <input id="interviwer" type="checkbox">
                                            <label for="interviwer">Comments For Interviewers</label>
                                        </div>
                                        <div class="show-hide-comments">
                                            <div class="comment-box">
                                                <div class="textarea">
                                                    <textarea></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-col-100">
                                        <div class="applicant-comments-label">
                                            <input id="candidate-msg" type="checkbox">
                                            <label for="candidate-msg">Message To Candidate</label>
                                            <div class="add-attachment">
                                                <input type="file">
                                                <a href="javascript:;">Add Attachment</a>
                                            </div>
                                        </div>
                                        <div class="show-hide-comments">
                                            <div class="comment-box">
                                                <div class="textarea">
                                                    <input class="message-subject" type="text" placeholder="Enter Subject (required)"/>
                                                    <textarea></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-col-100">
                                        <input class="submit-btn" type="submit" value="Schedule">
                                        <input class="submit-btn btn-cancel" type="submit" value="Cancel Changes">
                                    </div>
                                </form>				       
                            </div><!-- #tab5 --> 
                        </div>	
                    </div>				
                </div>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    <aside class="side-bar">
                        <header class="sidebar-header">
                            <h1>Application Tracking</h1>
                        </header>
                        <div class="next-applicant">
                            <ul>
                                <li class="previous-btn"><a href="javascript:;"><i class="fa fa-chevron-left"></i>Prev</a></li>
                                <li class="next-btn"><a href="javascript:;">next<i class="fa fa-chevron-right"></i></a></li>
                            </ul>
                        </div>
                        <div class="widget-wrp">
                            <div class="hr-widget">
                                <h2>GENERAL APPLICATION <br>From Website on 28-Oct-2015</h2>
                                <div class="start-rating">
                                    <input id="input-21b" value="4" type="number" class="rating" min=0 max=5 step=0.2 data-size="xs">
                                </div>
                                <div class="applicant-status">
                                    candidate Status goes here
                                </div>
                            </div>
                            <div class="hr-widget">
                                <div class="attachment-header">
                                    <h4>Resume</h4>
                                    <div class="btn-inner">
                                        <input type="file" class="choose-file-filed"> 
                                        <a href="" class="select-photo"><i class="fa fa-plus"></i></a>
                                    </div>

                                </div>
                                <div class="attachment-header">
                                    <h4>Resume</h4>
                                    <div class="btn-inner">
                                        <input type="file" class="choose-file-filed"> 
                                        <a href="" class="select-photo"><i class="fa fa-plus"></i></a>
                                    </div>

                                </div>
                                <div class="hr-select-dropdown">
                                    <select class="invoice-fields">
                                        <option>Punjab</option>
                                        <option>California</option>
                                        <option>London</option>
                                    </select>
                                </div>
                                <div class="form-title-section">
                                    <div class="form-btns">
                                        <input type="submit" value="update">
                                    </div>												
                                </div>
                            </div>
                            <div class="hr-widget">
                                <div class="attachment-header">
                                    <h4>Attachments</h4>
                                </div>
                                <div class="browse-attachments">
                                    <ul>
                                        <li>
                                            <h4>Background Check</h4>
                                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                        <li>
                                            <h4>Behavioral Assessment</h4>
                                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                        <li>
                                            <h4>Reference Check</h4>
                                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                        <li>
                                            <h4>Skills Test</h4>
                                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                        <li>
                                            <h4>Video Interview</h4>
                                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                        <li>
                                            <h4>Add Schedule</h4>
                                            <a href="javascript:;">Browse<i class="fa fa-chevron-circle-right"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </aside>										
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->