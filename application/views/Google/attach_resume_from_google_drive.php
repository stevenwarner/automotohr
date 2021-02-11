<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/alertify.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/alertifyjs/css/themes/default.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets') ?>/css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"/>
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo base_url('assets') ?><!--/css/public-form-style.css">-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo base_url('assets') ?><!--/css/responsive.css">-->

    <script src="<?php echo base_url('assets') ?>/js/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url('assets') ?>/js/jquery.datetimepicker.js"></script>
    <script src="<?php echo base_url('assets') ?>/alertifyjs/alertify.min.js"></script>

    <script src="<?php echo base_url('assets') ?>/js/functions.js"></script>


    <style>
        body {
            overflow-x: hidden !important;
        }
        .label {
            color: #000;
            margin-bottom: 20px;

        }



        .label span {
            font-size: 18px;
            margin-left: 10px;
        }

        .invoice-fields{
            float: left;
            width: 100%;
            height: 40px;
            border:1px solid #ccc;
            border-radius: 5px;
            color: #000;
            padding: 0 5px;
            background-color: #fff;
            font-size: 18px;
        }
        .invoice-fields option{
            padding: 0 5px;
        }
        .universal-form-style-v2 .invoice-fields.error{
            border-color: #ff0000;
        }
        .universal-form-style-v2 ul{
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .universal-form-style-v2 ul li{
            margin: 0 0 15px 0;
            position: relative;
            height: 85px;
        }

        .invoice-fields{
            padding: 0 10px;
            border:0;
            font-weight: 600;
            background-color: #ffffff;
            border: 2px solid #aaa;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 10px;
            width: 80%;
        }
        .form-btn {
            background-color: #0DA45F;
            border: medium none;
            border-radius: 5px;
            color: #fff;
            /*font-weight: bold;*/
            margin-right: 2px;
            margin-top: 10px;
            margin-left: 10px;
            padding: 11px 0;
            width: 20%;
            font-size: 18px;
        }

        .spinner {
            position: relative;
            color: #ffffff;
            text-align: left;
            padding: 8px;
        }
        .spinner i, #cc_spinner{
            font-size: 18px;
        }


        .hieght-adjuster {
            height: 100px;
        }

        .disabled-btn, .disabled-btn:hover {
            background-color: #ccc !important;
            color: #606060 !important;
            cursor:not-allowed !important;
        }
        .container-buybtn{
            display: inline-block;
            width: 100%;
            position: relative;
        }

        .container-buybtn input[type="radio"],
        .container-buybtn label{
            margin: 0;
            background-color: #0099ff;
            border-radius: 50px;
            color: #fff;
            display: inline-block;
            font-size: 18px;
            font-weight: 600;
            padding: 10px 38px;
            text-transform: capitalize;
            cursor: pointer;
        }
        .container-buybtn input[type="radio"]{
            opacity: 0;
        }
        .container-buybtn input[type="radio"]:checked ~ label {
            background-color: #00a700;
        }

        .bg-light-blue {
            background-color: lightblue;
        }

        .label .well span {
            display: inline-block;
            margin-top: 20px;
            font-size: 35px;
        }

        .google-gradiant {
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#0da45f+0,fed250+50,7db9e8+100,4083f7+100 */
            background: #0da45f; /* Old browsers */
            background: -moz-linear-gradient(-45deg,  #0da45f 0%, #fed250 50%, #7db9e8 100%, #4083f7 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(-45deg,  #0da45f 0%,#fed250 50%,#7db9e8 100%,#4083f7 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(135deg,  #0da45f 0%,#fed250 50%,#7db9e8 100%,#4083f7 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0da45f', endColorstr='#4083f7',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */

        }

        body .alertify .ajs-header{
            background-color: #00a700;
            color: #fff;
        }
        body .alertify .ajs-commands button.ajs-close{
            background-color: #fff;
            border-radius: 100%;
        }

        .alertify .ajs-footer .ajs-buttons .ajs-button.ajs-ok {
            color: #fff !important;
            background-color: #00a700;
            cursor: pointer;
            border-radius: 5px;
        }
        .alertify .ajs-footer .ajs-buttons .ajs-button.ajs-ok:hover{
            background-color: #006400;
        }

        .alertify .ajs-footer .ajs-buttons .ajs-cancel {
            background-color: #252524 !important;
            border-radius: 5px;
            cursor: pointer;
            color: #fff !important;
        }
        .alertify .ajs-footer .ajs-buttons .ajs-cancel:hover{
            background-color: #454544 !important;
        }

        .ajs-close {
            margin-top: 10px;
        }

        .ajs-header {
            font-size: 20px;
            font-weight: 700;
        }

        .ajs-content {
            font-size: 16px;
        }
        @media (max-width:480px){
            .label .well span{
                font-size: 20px;
            }
            .invoice-fields,
            .form-btn{
                width: 100%;
                margin: 2px 0;
                float: left;
            }
            .form-btn{
                font-size: 12px;
                line-height: 18px;
            }
            .label span{
                margin: 0 0 10px 0;
            }
        }
    </style>
</head>
<body>
<!-- Wrapper Start -->
<div class="wrapper">
    <!-- Header Start -->
    <header class="header header-position" style="background-color: #000000; ">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 style="color: #fff; text-align: center; background-color:#000; width:100%; padding: 20px;"><i class="fa fa-cloud" aria-hidden="true"></i> <?php echo $title; ?></h2>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->
    <div class="clear"></div>
    <!-- Main Start -->
    <div class="main" style="margin-top: 50px;">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div style="padding: 15px; min-height: 250px;" class="bg-success text-success">
                        <h3 style="margin-left: 10px;">Instructions for Google Drive</h3>
                        <ul style="font-size: 14px;">
                            <li>Click on <b>Google Drive</b> Panel</li>
                            <li>Click on <b>Browse</b></li>
                            <li>From Popup Window Login with your <b>Google Drive Credentials</b></li>
                            <li>Give Permission to <b><?php echo STORE_NAME; ?></b> to access your files on your <b>Google Drive</b></li>
                            <li>Select your Desired File</li>
                            <li>Click on <b>Select</b></li>
                            <li>Wait until the file is attached to your Job Application</li>
                            <li>Window will automatically close after successful attachment</li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div style="padding: 15px; min-height: 250px;" class="bg-info text-info">
                        <h3 style="margin-left: 10px;">Instructions for Dropbox</h3>
                        <ul style="font-size: 14px;">
                            <li>Click on <b>Dropbox</b> Panel</li>
                            <li>Click on <b>Browse</b></li>
                            <li>From Popup Window Login with your <b>Dropbox Credentials</b></li>
                            <li>Give Permission to <b><?php echo STORE_NAME; ?></b> to access your files on your <b>Dropbox</b></li>
                            <li>Select your Desired File</li>
                            <li>Click on <b>Choose</b></li>
                            <li>Wait until the file is attached to your Job Application</li>
                            <li>Window will automatically close after successful attachment</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="label" for="attachment_from_google">
                                <div class="well well-google">
                                    <div class="col-lg-2">
                                        <img src="<?php echo base_url() ?>/assets/images/00_google_drive.png" class="img-responsive img-thumbnail"/>
                                    </div>
                                    <div class="col-lg-10">
                                        <span>Google Drive</span>
                                        <div class="container-buybtn">
                                            <input type="radio" id="attachment_from_google" name="attachment_from" value="google" class="attach_from" checked="checked">
                                        </div>
                                        <div id="attach_from_google">
                                            <input type="text" value="" placeholder="" id="google_drive_resume" readonly name="google_drive_resume" class="invoice-fields search-job">
                                            <button id="btn-google" class="form-btn" onclick="loadPicker();" type="button">Browse</button>
                                        </div>
                                        <div id="cc_spinner" class="spinner spinner-google" style="display: none;">
                                            <i class="fa fa-refresh fa-spin"></i> Processing...
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                </div>
                            </label>

                            <label class="label"  for="attachment_from_dropbox">

                            <div class="well well-dropbox">
                                <div class="col-lg-2">
                                    <img src="<?php echo base_url() ?>/assets/images/00_dropbox.png" class="img-responsive img-thumbnail"/>
                                </div>
                                <div class="col-lg-10">
                                    <span>Dropbox</span>
                                    <div class="container-buybtn">
                                        <input type="radio" id="attachment_from_dropbox" name="attachment_from" value="dropbox" class="attach_from">
                                    </div>
                                    <div id="attach_from_dropbox">
                                        <input type="text" value="" placeholder="" id="dropbox_resume" readonly name="dropbox_resume" class="invoice-fields search-job">
                                        <button id="dropboxbutton" class="form-btn" onclick="" type="button">Browse</button>
                                    </div>
                                    <div id="cc_spinner" class="spinner spinner-dropbox" style="display: none;">
                                        <i class="fa fa-refresh fa-spin"></i> Processing...
                                    </div>
                                </div>
                            </label>

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>




                    <div class="universal-form-style-v2">

                        <!--<button class="btn btn-default" onclick="getFileContent();" type="button">Get File Content</button>-->
                        <input type="hidden" id="resume_url" name="resume_url" value=""/>
                        <input type="hidden" id="resume_on_aws" name="resume_on_aws" value=""/>
                        <input type="hidden" id="unique_key" name="unique_key" value="<?php echo $unique_key; ?>"/>

                        <!-- The Google API Loader script. -->
                        <script type="text/javascript"
                                src="https://apis.google.com/js/api.js?onload=onApiLoad"></script>
                        <script type="text/javascript" src="https://apis.google.com/js/client.js"></script>

                        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>

                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('.cc_spinner').hide();
                            });

                            var myUrl = "<?php echo base_url('/Google/ajax_responder') ?>";
                            var myToken = '';
                            var myFileId = '';
                            var myDocumentInfo = '';
                            var myFileName = '';

                            // The Browser API key obtained from the Google Developers Console.
                            //var developerKey = 'AIzaSyCsLuOy3reRTH0KIs02w6XaRGnQs1hW3Cg'; //Hamid
                            var developerKey = 'AIzaSyA5vw6HFHvGMLfH4AfZQgwp0q6dwTOoGm0'; //Dev
                            // var developerKey = 'AIzaSyB4obm0Q7O4kCV30je8TqPNAo70uA8Mk6U'; //Dev
                            //todo:Make this dynamic Later

                            // The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
                            //var clientId = "499875028110-ocqp59ssk7lbsvfrd1u1mforvl4ebc46.apps.googleusercontent.com" //Hamid
                            var clientId = "295266289960-f4ai87f6si1vk1ec526pqr0h73n37lvm.apps.googleusercontent.com"; //Dev
                            // var clientId = "145015201408-am8hodqg44j3gi5483494mhjp7tikakj.apps.googleusercontent.com"; //Dev
                            //todo:Make this dynamic Later

                            // Scope to use to access user's photos.
                            /*//var scope = ['https://www.googleapis.com/auth/photos'];*/
                            var scope = ['https://www.googleapis.com/auth/drive'];

                            var pickerApiLoaded = false;
                            var oauthToken;

                            //Start Picker
                            function loadPicker() {
                                gapi.load('auth', {'callback': onAuthApiLoad});
                                gapi.load('picker', {'callback': onPickerApiLoad});
                            }

                            // Use the API Loader script to load google.picker and gapi.auth.
                            /*
                             function onApiLoad() {
                             gapi.load('auth', {'callback': onAuthApiLoad});
                             gapi.load('picker', {'callback': onPickerApiLoad});
                             }
                             */

                            function onAuthApiLoad() {
                                window.gapi.auth.authorize(
                                    {
                                        'client_id': clientId,
                                        'scope': scope,
                                        'immediate': false
                                    },
                                    handleAuthResult);
                            }

                            function onPickerApiLoad() {
                                pickerApiLoaded = true;
                                createPicker();
                            }

                            function handleAuthResult(authResult) {
                                if (authResult && !authResult.error) {
                                    oauthToken = authResult.access_token;

                                    myToken = authResult.access_token;
                                    loadDriveApi();
                                    createPicker();

                                }
                            }

                            // Create and render a Picker object for picking user Photos.
                            function createPicker() {
                                if (pickerApiLoaded && oauthToken) {
                                    var picker = new google.picker.PickerBuilder().
                                        addView(google.picker.ViewId.DOCS).
                                        setOAuthToken(oauthToken).
                                        setDeveloperKey(developerKey).
                                        setCallback(pickerCallback).
                                        build();
                                    picker.setVisible(true);
                                }
                            }

                            // A simple callback implementation.
                            function pickerCallback(data) {
                                var url = 'nothing';
                                if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                                    var doc = data[google.picker.Response.DOCUMENTS][0];
                                    url = doc[google.picker.Document.URL];

                                    myFileId = doc.id;
                                    myFileName = doc.name;

                                    $('#google_drive_resume').val(doc.name);
                                    console.log(doc.id);

                                    myDocumentInfo = JSON.stringify(doc);

                                    printFile(doc.id);
                                }
                                var message = 'You picked: ' + url;
                                //document.getElementById('result').innerHTML = message;

                                if (myFileId != '') {
                                    getFileContent();
                                }

                            }


                            /**
                             * Load Drive API client library.
                             */
                            function loadDriveApi() {
                                /*gapi.client.load('drive', 'v2', listFiles);*/
                                gapi.client.load('drive', 'v2');
                            }


                            function getFileInfo(fileId) {
                                var request = gapi.client.drive.files.get({
                                    'fileId': fileId
                                });
                            }

                            function printFile(fileId) {
                                var request = gapi.client.drive.files.get({
                                    'fileId': fileId
                                });
                                request.execute(function (resp) {
                                    /*
                                     console.log('Title: ' + resp.title);
                                     console.log('Description: ' + resp.description);
                                     console.log('MIME type: ' + resp.mimeType);
                                     console.log(resp.exportLinks['application/rtf']); */

                                    $('#resume_url').val(resp.exportLinks['application/rtf']);
                                });
                            }

                            function getFileContent() {
                                $('.spinner-google').show();
                                $('#mySubmitBtn').attr('disabled', 'disabled');
                                var fileUrl = $('#resume_url').val();

                                $(document).ajaxStop(function () {
                                    console.log('Ajax Stopped.');
                                });

                                var uniqueKey = $('#unique_key').val();

                                //'&url=' + encodeURI(fileUrl) +
                                var myRequest = $.ajax({
                                    url: myUrl,
                                    data: '&unique_key=' + uniqueKey + '&perform_action=getfilecontent&token=' + myToken + '&document=' + myFileId + '&docinfo=' + encodeURI(myDocumentInfo),
                                    type: 'POST'
                                });

                                myRequest.success(function (response) {
                                    $('#resume_on_aws').val(response);


                                    $('.spinner-google').hide();
                                    $('#mySubmitBtn').removeAttr('disabled');

                                    alertify.alert('Resume', 'Successfully Attached Resume!',
                                        function () {
                                            window.close();

                                        });
                                });


                            }

                            //Dropbox Button
                            $(document).ready(function () {


                                options = {

                                    // Required. Called when a user selects an item in the Chooser.
                                    success: function(files) {
                                        $('.tracking-filter a').removeClass('dropbox-dropin-btn');
                                        $('.tracking-filter a').removeClass('dropbox-dropin-success');
                                        $('.tracking-filter a').addClass('form-btn');
                                        $('.tracking-filter a').css('text-align', 'center');
                                        $('.tracking-filter a').css('text-decoration', 'none');
                                        $('.tracking-filter a').html('Dropbox');


                                        $('.spinner-dropbox').show();
                                        //console.log(files);

                                        var fileName = files[0].name;
                                        var fileUrl = files[0].link;
                                        var uniqueKey = $('#unique_key').val();

                                        $('#dropbox_resume').val(fileName);

                                        var dataToSend = '&name=' + encodeURI(fileName)
                                                        + '&url=' + encodeURI(fileUrl)
                                                        + '&perform_action=dropboxgetfilecontent'
                                                        + '&unique_key=' + uniqueKey;

                                        var myRequest = $.ajax({
                                            url: myUrl,
                                            data: dataToSend,
                                            type: 'POST'
                                        });

                                        myRequest.success(function (response) {
                                            $('.spinner-dropbox').hide();

                                            alertify.alert('Resume', 'Successfully Attached Resume!',
                                                function () {
                                                    window.close();

                                                });
                                        });

                                        //alert("Here's the file link: " + files[0].link)
                                    },

                                    // Optional. Called when the user closes the dialog without selecting a file
                                    // and does not include any parameters.
                                    cancel: function() {

                                    },

                                    // Optional. "preview" (default) is a preview link to the document for sharing,
                                    // "direct" is an expiring link to download the contents of the file. For more
                                    // information about link types, see Link types below.
                                    linkType: "preview", // or "direct"

                                    // Optional. A value of false (default) limits selection to a single file, while
                                    // true enables multiple file selection.
                                    multiselect: false, // or true

                                    // Optional. This is a list of file extensions. If specified, the user will
                                    // only be able to select files with these extensions. You may also specify
                                    // file types, such as "video" or "images" in the list. For more information,
                                    // see File types below. By default, all extensions are allowed.
                                    extensions: ['.pdf', '.doc', '.docx'],
                                };


                                $('#dropboxbutton').on('click', function () {
                                    Dropbox.choose(options);
                                });

                                //var myDropboxButton = Dropbox.createChooseButton(options);


                                //$('#dropboxbutton').replaceWith(myDropboxButton);

                                $('.tracking-filter a').removeClass('dropbox-dropin-btn');
                                $('.tracking-filter a').removeClass('dropbox-dropin-default');
                                $('.tracking-filter a').addClass('form-btn');
                                $('.tracking-filter a').css('text-align', 'center');
                                $('.tracking-filter a').css('text-decoration', 'none');
                                $('.tracking-filter a').html('Dropbox');

                                $('.tracking-filter a span').css('display', 'none');

                            });


                            $('#attach_from_dropbox button').attr('disabled', 'disabled');
                            $('#attach_from_dropbox button').addClass('disabled-btn');
                            $('.well-google').css('background-color', 'lightgreen');
                            $('.well-dropbox').css('background-color', '#f5f5f5');
                            $('.label .well-google span').css('color', '#fff');


                            $('.attach_from').each(function () {
                                $(this).on('click, change', function () {
                                    var selected = $(this).val();
                                    //console.log(selected);
                                    if (selected == 'google') {
                                        focus_google();
                                    } else {
                                        focus_dropbox();
                                    }
                                });

                                $('#google_drive_resume').on('focus', function(){
                                    //console.log('in focus');
                                    focus_google();
                                });

                                $('#dropbox_resume').on('focus', function(){
                                    //console.log('in focus');
                                    focus_dropbox();
                                });
                            });
                            function focus_google(){
                                $('.well-google').css('background-color', 'lightgreen');
                                $('.well-dropbox').css('background-color', '#f5f5f5');
                                $('.label .well-google span').css('color', '#fff');
                                $('.label .well-dropbox span').css('color', '#000');
                                $('#attach_from_dropbox button').addClass('disabled-btn');
                                $('#attach_from_dropbox button').attr('disabled', 'disabled');
                                $('#attach_from_google button').removeClass('disabled-btn');
                                $('#attach_from_google button').removeAttr('disabled');
                            }
                            function focus_dropbox(){
                                $('.well-dropbox').css('background-color', 'lightblue');
                                $('.well-google').css('background-color', '#f5f5f5');
                                $('.label .well-dropbox span').css('color', '#fff');
                                $('.label .well-google span').css('color', '#000');
                                $('#attach_from_dropbox button').removeClass('disabled-btn');
                                $('#attach_from_dropbox button').removeAttr('disabled');
                                $('#attach_from_google button').addClass('disabled-btn');
                                $('#attach_from_google button').attr('disabled', 'disabled');
                                $('#attach_from_dropbox button').css('background-color', '#007DE4');
                            }

                        </script>

                        <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="fxv34wq9c1hw4eh"></script>


                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<!-- Wrapper End -->

</body>
</html>
