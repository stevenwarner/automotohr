<?php
    $company_sid = '';

    if ($this->uri->segment(2) == 'my_offer_letter' || $this->uri->segment(2) == 'send_requested_resume') {
        $company_sid = $company_info['sid'];
    } else {
        $company_sid = $session['company_detail']['sid'];
    }

	$footer_logo_data  = get_footer_logo_data($company_sid);
	$footer_logo_status = $footer_logo_data['footer_powered_by_logo'];
	$footer_logo_type = $footer_logo_data['footer_logo_type'];
	$footer_logo_text = $footer_logo_data['footer_logo_text'];
	$footer_logo_image = $footer_logo_data['footer_logo_image'];

	$footer_copyright_data  = get_footer_copyright_data($company_sid);
	$copyright_status = $footer_copyright_data['copyright_company_status'];
	$copyright_company_name = $footer_copyright_data['copyright_company_name']; ?>

	<footer class="ob-footer <?=in_array('iframe', $this->uri->segment_array()) ? 'hidden' : '';?>">
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                        <div class="hr-lanugages">
                            <div id="google_translate_element"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                        <div class="copy-right-text">
<?php                       if ($copyright_status == 1) {
                                $company_name = $copyright_company_name;
                            } else {
                                $company_name = STORE_NAME;
                            } 
                            
                            $footer_copyright_text = "Copyright &copy; ". date('Y') .' '. $company_name." All Rights Reserved"; ?>
                            <p title="<?php echo $footer_copyright_text; ?>"><?php echo $footer_copyright_text; ?></p>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 text-center">
                        <?php   if ($footer_logo_status == 1) { ?>
                                    <a class="<?php if ($footer_logo_type == 'text') { echo 'copy-right-text text-white'; } else { echo 'footer-text-logo';}?>" href="<?php echo STORE_FULL_URL_SSL; ?>" target="_blank">
                       <?php        if ($footer_logo_type == 'default') { ?>
                                        Powered by <img src="<?php echo base_url('assets/images/ahr_logo_138X80_wt.png'); ?>">
                        <?php       } else if ($footer_logo_type == 'text') { ?>
                                        Powered by <?php echo $footer_logo_text; ?>
                        <?php       } else if ($footer_logo_type == 'logo') { ?>
                                        Powered by <img src="<?php echo AWS_S3_BUCKET_URL . $footer_logo_image; ?>" class="upload_logo_image">
                        <?php       } ?>
                                    </a>
                        <?php   } ?>  
                    </div>
                    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                        <div class="social-links">
                            <ul>
                                <li>
                                    <a class="google-plus" href="<?php
                                    $g_url = get_slug_data('google_plus_url', 'settings');
                                    
                                    if (!empty($g_url)) {
                                        echo $g_url;
                                    } else {
                                        echo "https://plus.google.com/u/0/b/102383789585278120218/+Automotosocialjobs/posts";
                                    } ?>"  target="_blank"><i class="fa fa-google-plus"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="twitter"  href="<?php
                                    $t_url = get_slug_data('twitter_url', 'settings');
                                    
                                    if (!empty($t_url)) {
                                        echo $t_url;
                                    } else {
                                        echo "https://twitter.com/AutomotoSocial";
                                    } ?>" target="_blank"><i class="fa fa-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a class="facebook"  href="<?php
                                    $f_url = get_slug_data('facebook_url', 'settings');
                                    
                                    if (!empty($f_url)) {
                                        echo $f_url;
                                    } else {
                                        echo "https://www.facebook.com/automotosocialjobs";
                                    }
                                    ?>" target="_blank"><i class="fa fa-facebook"></i>
                                    </a>
                                </li>                            
                                <li><a class="linkedin"  href="<?php
                                    $l_url = get_slug_data('linkedin_url', 'settings');
                                    if (!empty($l_url)) {
                                        echo $l_url;
                                    } else {
                                        echo "https://www.linkedin.com/grp/home?gid=6735083&goback=%2Egna_6735083";
                                    }
                                    ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                <?php
                                    $y_url = get_slug_data('youtube_url', 'settings');
                                    if (!empty($y_url)) {
                                ?>
                                <li><a class="youtube"  href="<?php echo $y_url; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
                                <?php } ?>
                                <?php
                                    $i_url = get_slug_data('instagram_url', 'settings');
                                    if (!empty($i_url)) {
                                ?>
                                <li><a class="instagram"  href="<?php echo $i_url; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                <?php } ?>
                                <?php   $gl_url = get_slug_data('glassdoor_url', 'settings');
                                        if (!empty($gl_url)) { ?>
                                        <li><a class="glassdoor"  href="<?php echo $gl_url; ?>" target="_blank"><img src="<?= base_url() ?>assets/images/glassdoor.png"></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
	        </div>
	    </div>
	</footer>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'de,es,fr,pt,it,zh-CN,zh-TW',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
        //PTO Related Common functions
        function pto_formated_minutes(pto_format,default_slot,Minutes, report = false){
            var finalResult = '';
            if(Minutes == 0 && !report){
                finalResult = 'Infinite';
            }else{
                var D = 0;
                var H = 0;
                var M = 0;
                if(pto_format == 'D:H:M'){
                    var D = parseInt((Minutes)/(default_slot * 60));
                    var H = parseInt(((Minutes)%(default_slot * 60))/60);
                    var M = parseInt(((Minutes)%(default_slot * 60))%60);
                    finalResult = D + ' Day(s) ' + H + ' Hour(s) ' + M + 'Minute(s)';
                }else if(pto_format == 'H:M'){
                    var H = parseInt((Minutes)/60);
                    var M = parseInt((Minutes)%60);
                    finalResult = H + ' Hour(s) ' + M + 'Minute(s)';
                }else if(pto_format == 'D'){
                    var D = ((Minutes)/(default_slot * 60)).toFixed(2);
                    finalResult = D + ' Day(s) ';
                }else if(pto_format == 'M'){
                    var M = Minutes;
                    finalResult =  M + 'Minute(s)';
                }else if(pto_format == 'H'){
                    var H = ((Minutes)/60).toFixed(2);
                    finalResult = H + ' Hour(s) ';
                }
            }
            return finalResult;
        }
        function fetchDefaultSlot(){
            $.post(baseURI+'handler', {
                action: 'fetch_emp_default_slot',
                employer_sid: emp_id
            }, function(resp) {
                //
                if(resp.Status === false){
                    console.log('failed to load slot');
                    return;
                }
                //
                default_slot = resp.Records['default_slot'];
            });
        }
        function getLoginInfo(){
            $.post(baseURI+'handler', {
                action: 'fetch_login_info'
            }, function(resp) {
                //
                if(resp.Status === false){
                    console.log('failed to load info');
                    return;
                }
                //
                emp_id = resp.Records['employee_id'];
            });
        }
        function fetchPtoFormat(){
            $.post(baseURI+'handler', {
                action: 'fetch_company_pto_format'
            }, function(resp) {
                //
                if(resp.Status === false){
                    console.log('failed to pto format');
                    return;
                }
                //
                pto_format = resp.Records['format'];
//                default_slot = resp.Records['default_slot'];
                if(pto_format == 'D:H:M'){
                    $('.days-div').show();
                    $('.hours-div').show();
                    $('.minutes-div').show();
                }else if(pto_format == 'H:M'){
                    $('.hours-div').show();
                    $('.hours-div').removeClass('col-sm-4');
                    $('.hours-div').addClass('col-sm-6');
                    $('.minutes-div').show();
                    $('.minutes-div').removeClass('col-sm-4');
                    $('.minutes-div').addClass('col-sm-6');
                }else if(pto_format == 'D'){
                    $('.days-div').show();
                    $('.days-div').removeClass('col-sm-4');
                    $('.days-div').addClass('col-sm-12');
                }else if(pto_format == 'M'){
                    $('.minutes-div').show();
                    $('.minutes-div').removeClass('col-sm-4');
                    $('.minutes-div').addClass('col-sm-12');
                }else if(pto_format == 'H'){
                    $('.hours-div').show();
                    $('.hours-div').removeClass('col-sm-4');
                    $('.hours-div').addClass('col-sm-12');
                }

            });
        }
        function dayComponent(col,val,id){
            var day = '';
            day = '<div class="col-sm-'+col+'"><input placeholder="Day" type="number" class="form-control js-add-plan-day" id="day-'+id+'" value="'+val+'" style="height: 41px;"><span class="cs-error js-error" style="display: none;"></span> </div>';

            return day;
        }
        function hourComponent(col,val,id){
            var hour = '';
            hour = '<div class="col-sm-'+col+'"><input placeholder="Hours" type="number" class="form-control js-add-plan-hour" id="hour-'+id+'" value="'+val+'" style="height: 41px;"><span class="cs-error js-error" style="display: none;"></span> </div>';

            return hour;
        }
        function minuteComponent(col,val,id){
            var minute = '';
            minute = '<div class="col-sm-'+col+'"><input placeholder="Minutes" type="number" class="form-control js-add-plan-minute" id="minute-'+id+'" value="'+val+'" style="height: 41px;"><span class="cs-error js-error" style="display: none;"></span> </div>';

            return minute;
        }
        function fetchPlanInputs(hours, sid, action){
            var inputs = '';
            if(pto_format == 'D:H:M'){
                var D = parseInt((hours)/(default_slot * 60));
                var H = parseInt(((hours)%(default_slot * 60))/60);
                var M = parseInt(((hours)%(default_slot * 60))%60);
                inputs += action == 'add' ? dayComponent(4,D,sid) : editDayComponent(4,D,sid);
                inputs += action == 'add' ? hourComponent(4,H,sid) : editHourComponent(4,H,sid);
                inputs += action == 'add' ? minuteComponent(4,M,sid) : editMinuteComponent(4,M,sid);

            }else if(pto_format == 'H:M'){
                var H = parseInt(((hours)/(60)));
                var M = parseInt(((hours)%(60)));
                inputs += action == 'add' ? hourComponent(6,H,sid) : editHourComponent(6,H,sid);
                inputs += action == 'add' ? minuteComponent(6,M,sid) : editMinuteComponent(6,M,sid);

            }else if(pto_format == 'D'){
                var D = ((hours)/(default_slot * 60)).toFixed(2);
                inputs += action == 'add' ? dayComponent(12,D,sid) : editDayComponent(12,D,sid);

            }else if(pto_format == 'M'){
                var M = hours;
                inputs += action == 'add' ? minuteComponent(12,M,sid) : editMinuteComponent(12,M,sid);;

            }else if(pto_format == 'H'){
                var H = ((hours)/60).toFixed(2);
                inputs += action == 'add' ? hourComponent(12,H,sid) : editHourComponent(12,H,sid);

            }
            return inputs;
        }

        function convertIntoMinutes($days = 0, $hours = 0, $minutes = 0){
            var $totalMinutes = parseInt(Number($days * default_slot * 60) + Number($hours * 60) + Number($minutes));
            return $totalMinutes;
        }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <?php
    $class = strtolower($this->router->fetch_class()); 
    if($class == 'time_off' || in_array('employee_management_system', $this->uri->segment_array()) || in_array('dashboard', $this->uri->segment_array())) { ?>
        <?php $this->load->view('timeoff/scripts'); ?>
    <?php } ?>
    <?php
    if($class == 'performance_management') { ?>
        <?php $this->load->view("{$pp}scripts"); ?>
    <?php } ?>
    

     <!--  -->
    <script>
        $(function(){
            var targets = {
                count: $('#js-notification-count'),
                box: $('#js-notification-box')
            };

            loadNotifications();

            function loadNotifications(){
                $.get("<?=base_url('notifications');?>/get_notifications", function(resp) {
                    //
                    if(resp.Status === false){
                        console.log(resp.Response);
                        targets.count.parent().find('i').removeClass('faa-shake animated');
                        return;
                    }
                    //
                    var rows = '';
                    resp.Data.map(function(v){
                        rows += '<li>';
                        rows += '    <a href="'+( v['link'] )+'">';
                        rows += '        <span class="pull-left">'+( v['title'] )+' <b>('+( v['count'] )+')</b></span>';
                        rows += '        <span class="pull-right"><i class="fa fa-eye"></i></span>';
                        rows += '    </a>';
                        rows += '</li>';
                    });
                    //
                    targets.count.text(resp.Data.length);
                    targets.box.prepend(rows);
                    targets.count.parent().find('i').addClass('faa-shake animated');
                });
            }
        });

        // 
        $(document).on('click', '.jsPageBTN', function(event) {

            //
            event.preventDefault();
            //
            $(this).toggleClass('fa-minus-circle');
            $(this).toggleClass('fa-plus-circle');
            //
            $('.jsPageBody[data-page="' + ($(this).data('target')) + '"]').toggle();

        });

        footer_fixer();
            //
        function footer_fixer() {
            //
    if ($('.csPageWrap').length === 0) {
        return;
    }
            //
            var wh = $(document).height() - $('.csPageWrap').height();
            var fh = $('footer').height();
            $('footer').css('margin-top', (wh - fh) + 'px')
        }

        //
        $(document).on('click', '.jsToggleHelp', function(event) {
            //
            event.preventDefault();
            //
            $('.jsToggleHelpArea[data-help="' + ($(this).data('target')) + '"]').toggle();
        });


        //
function loadTitles() {
    $('[title][placement="left"]').tooltip({ placement: 'left auto', trigger: "hover" });
    $('[title][placement="right"]').tooltip({ placement: 'right auto', trigger: "hover" });
    $('[title][placement="top"]').tooltip({ placement: 'top auto', trigger: "hover" });
    $('[title]').tooltip({ placement: 'bottom auto', trigger: "hover" });
}


$(document).ready(loadTitles);

    </script>
    <style>
    .notification-bell {
        border: none;
        margin: 0px 20px 0 0;
        margin-top: -10px;
        font-size: 25px;
        border-radius: 5px;
        cursor: pointer;
        border: none;
        color: #fff;
        height: 65px;
        background-color: transparent;
        position: relative;
    }
    .notification-count {
        position: absolute;
        top: 4px;
        right: -10px;
        font-size: 12px;
        color: #fff;
        padding: 5px;
        width: 25px;
        height: 25px;
        line-height: 15px;
        background-color: #81b431;
        border-radius: 100%;
    }
    .count-increament {
        background-color: #b4052c;
    }
    .notify-me .dropdown-menu.dropdown-menu-wide {
        max-width: 350px !important;
        width: 100%;
        left: auto;
        right: 125px;
        margin-top: 12px;
        padding: 0;
        max-height: 300px;
        border-radius: 0;
        z-index: 9999;
        border: none;
    }
    .notify-me .dropdown-menu.dropdown-menu-wide {
        min-height: 300px;
        max-height: 400px;
    }
    .notify-me .dropdown-menu:before {
        content: '';
        position: absolute;
        top: -21px;
        left: 0;
        right: 0;
        width: 0;
        height: 0;
        margin: auto;
        border-left: 16px solid transparent;
        border-right: 16px solid transparent;
        border-bottom: 20px solid #fff;
    }
    .notify-me .dropdown-menu > li {
        display: inline-block;
        width: 100%;
        vertical-align: top;
        margin-left: 0;
    }
    .notify-me .dropdown-menu > li a {
        display: block;
        float: left;
        width: 100%;
        text-transform: capitalize;
        padding: 25px 10px;
        border: 2px solid #e0e0e0;
        margin: -1px 0;
        background-color: #FFFFFF;
        color: #000000;
    }
    .notify-me .dropdown-menu > li a:hover {
        background-color: #81b431;
        color: #ffffff;
    }
    .notify-me .dropdown-menu:before {
        content: '';
        position: absolute;
        top: -21px;
        right: 17px;
        width: 0;
        height: 0;
        border-left: 16px solid transparent;
        border-right: 16px solid transparent;
        border-bottom: 20px solid #fff;
    }
    .faa-shake, .faa-shake.animated, .faa-shake.animated-hover:hover {
        -webkit-animation: bell-shake 8s ease infinite;
        animation: bell-shake 8s ease infinite;
    }

    @media only screen and (max-width: 576px) {
        .notify-me{ text-align: center !important; float: none !important; }
        .notify-me .fa{ color: #ffffff !important; }
        .notify-me .dropdown-menu.dropdown-menu-wide{ right: 10px !important; }
    }
    @-webkit-keyframes bell-shake {
      1% { -webkit-transform: rotateZ(15deg); transform-origin: 50% 10%;}
      2% { -webkit-transform: rotateZ(-15deg); transform-origin: 50% 10%;}
      3% { -webkit-transform: rotateZ(20deg); transform-origin: 50% 10%;}
      4% { -webkit-transform: rotateZ(-20deg); transform-origin: 50% 10%;}
      5% { -webkit-transform: rotateZ(15deg); transform-origin: 50% 10%;}
      6% { -webkit-transform: rotateZ(-15deg); transform-origin: 50% 10%;}
      7% { -webkit-transform: rotateZ(0); transform-origin: 50% 10%;}
      100% { -webkit-transform: rotateZ(0); transform-origin: 50% 10%;}
    }

    @-moz-keyframes bell-shake {
      1% { -moz-transform: rotateZ(15deg); transform-origin: 50% 0%; }
      2% { -moz-transform: rotateZ(-15deg); transform-origin: 50% 0%;}
      3% { -moz-transform: rotateZ(20deg); transform-origin: 50% 0%; }
      4% { -moz-transform: rotateZ(-20deg); transform-origin: 50% 0%;}
      5% { -moz-transform: rotateZ(15deg); transform-origin: 50% 0%; }
      6% { -moz-transform: rotateZ(-15deg); transform-origin: 50% 0%;}
      7% { -moz-transform: rotateZ(0); transform-origin: 50% 0%;   }
      100% { -moz-transform: rotateZ(0); transform-origin: 50% 0%;  }
    }

    @keyframes bell-shake {
      1% { transform: rotateZ(15deg); transform-origin: 50% 0%; }
      2% { transform: rotateZ(-15deg); transform-origin: 50% 0%;}
      3% { transform: rotateZ(20deg); transform-origin: 50% 0%; }
      4% { transform: rotateZ(-20deg); transform-origin: 50% 0%;}
      5% { transform: rotateZ(15deg); transform-origin: 50% 0%; }
      6% { transform: rotateZ(-15deg); transform-origin: 50% 0%;}
      7% { transform: rotateZ(0); transform-origin: 50% 0%;   }
      100% { transform: rotateZ(0); transform-origin: 50% 0%;   }
    }

</style>

<script src="<?=base_url("assets/js/common.js");?>"></script>
<script src="<?=base_url('assets/payroll/js/payroll_company_onboard.js');?>?v=<?=time();?>"></script>