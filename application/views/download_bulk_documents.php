<?php $slug = $userInfo['first_name'] . ' ' . $userInfo['last_name']; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/font-awesome.css">
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <title>Export Document(s)</title>
    <style>
        .cs-main {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
        }

        .cs-box {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .cs-box i {
            font-size: 90px;
        }

        .cs-box p {
            font-size: 16px;
        }

        .cs-box h6 {
            font-size: 16px;
        }
    </style>
</head>

<body style="overflow: hidden;">
    <div class="cs-main">
        <div class="col-sm-4 cs-box">
            <i class="fa fa-download"></i>
            <h5><strong id="js-dc">0</strong> of <strong id="js-dt"></strong> documents</h5>
            <div class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                </div>
            </div>
            <p>Please wait while we are loading documents.</p>

        </div>
    </div>

    <script>
        //
        var has = {
            'I9': "<?= isset($documents['I9']['sid']) ? "false" : "null"; ?>",
            'W9': "<?= isset($documents['W9']['sid']) ? "false" : "null"; ?>",
            'W4': "<?= isset($documents['W4']['sid']) ? "false" : "null"; ?>",
          //  'W4MN': "<?//= isset($documents['W4MN']['sid']) ? "true" : "null"; ?>",

            'direct_deposit': "<?= !empty($documents['direct_deposit']) ? "false" : "null"; ?>",
            'dependents': "<?= !empty($documents['dependents']) ? "false" : "null"; ?>",
            'emergency_contacts': "<?= !empty($documents['emergency_contacts']) ? "false" : "null"; ?>",
            'drivers_license': "<?= !empty($documents['drivers_license']) ? "false" : "null"; ?>",
            'occupational_license': "<?= !empty($documents['occupational_license']) ? "false" : "null"; ?>",
        };


        // ucwords
        String.prototype.ucwords = function() {
            str = this.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
                function(s) {
                    return s.toUpperCase();
                });
        };

        // fix
        String.prototype.fix = function() {
            str = this.toLowerCase();
            return str.replace(/_/g, ' ').ucwords();
        };

        //
        $(function() {

            let assigned = <?= json_encode($documents['Assigned']); ?>;
            
            console.log(assigned)
            let assignedLength = assigned.length;
            console.log(assignedLength)
            let dt = 10;
            let dc = 0;
            let gd = 0;
            let token = "<?= $token; ?>";

            // General Information Documents
            if (has['direct_deposit'] != "null") assignedLength++;
            if (has['dependents'] != "null") assignedLength++;
            if (has['emergency_contacts'] != "null") assignedLength++;
            if (has['drivers_license'] != "null") assignedLength++;
            if (has['occupational_license'] != "null") assignedLength++;
            //
            if (has['I9'] != "null") assignedLength++;
            if (has['W9'] != "null") assignedLength++;
            if (has['W4'] != "null") assignedLength++;
           // if (has['W4MN'] != "null") assignedLength++;

            //
            $('#js-dt').text(assignedLength);

            //
            if (has['I9'] != "null") {
                // Check for if
                checkI9('Adding I9 form to export');
            } else if (has['W9'] != "null") {
                // Check for if
                checkW9('Adding W9 form to export');
            } else if (has['W4'] != "null") {
                // Check for if
                checkW4('Adding W4 form to export');
            } /*else if (has['W4MN'] != "null") {
                // Check for if
                checkW4MN('Adding W4MN form to export');

            } */ else if (has['direct_deposit'] != "null") {
                //
                exportGDocument('direct_deposit');
            } else if (has['dependents'] != "null") {
                //
                exportGDocument('dependents');
            } else if (has['emergency_contacts'] != "null") {
                //
                exportGDocument('emergency_contacts');
            } else if (has['drivers_license'] != "null") {
                //
                exportGDocument('drivers_license');
            } else if (has['occupational_license'] != "null") {
                //
                exportGDocument('occupational_license');
            } else {
                nextDocument();
            }

            //
            function checkI9(s) {

                if (has['I9'] == "null" || has['I9'] == "false") {
                    checkW9('Adding W9 form to export');
                    return;
                }

                if (has['I9'] !== true) {
                    dc++;
                    m(s);
                    checkW9('Adding W9 form to export');
                    return;
                }
                //
                setTimeout(() => {
                    checkI9(s);
                }, 1000);
            }

            //
            function checkW9(s) {
                if (has['W9'] == "null" || has['W9'] == "false") {
                    checkW4('Adding W4 form to export');
                    return;
                }
                if (has['W9'] == true) {
                    dc++;
                    m(s);
                    checkW4('Adding W4 form to export');
                    return;
                }
                //
                setTimeout(() => {
                    checkW9(s);
                }, 1000);
            }

            //
            function checkW4(s) {
                if (has['W4'] == "null" || has['W4'] == "false") {
                      exportGDocument('direct_deposit');
                     // checkW4MN('Adding W4 State form to export');
                    return;
                }
                if (has['W4'] === true) {
                    dc++;
                    m(s);
                    exportGDocument('direct_deposit');
                   // checkW4MN('Adding W4 State form to export');
                   //

                    return;
                }
                //
                setTimeout(() => {
                    checkW4(s);
                }, 1000);
            }


            //
            /*
            function checkW4MN(s) {

                if (has['W4MN'] == "null" || has['W4MN'] == "false") {
                    exportGDocument('direct_deposit');
                    return;
                }
                if (has['W4MN'] === true) {
                    dc++;
                    m(s);
                    exportGDocument('direct_deposit');
                    return;
                }
                //
                setTimeout(() => {
                    checkW4MN(s);
                }, 1000);
            }
            */

            //
            function exportGDocument(s) {
                //
                if (s == 'direct_deposit' && has['direct_deposit'] == "null") {
                    exportGDocument('dependents');
                    return;
                }
                //
                if (s == 'dependents' && has['dependents'] == "null") {
                    exportGDocument('emergency_contacts');
                    return;
                }
                //
                if (s == 'emergency_contacts' && has['emergency_contacts'] == "null") {
                    exportGDocument('drivers_license');
                    return;
                }
                //
                if (s == 'drivers_license' && has['drivers_license'] == "null") {
                    exportGDocument('occupational_license');
                    return;
                }
                //
                if (s == 'occupational_license' && has['occupational_license'] == "null") {
                    //
                    startMoveProcess(getDocument());
                    return;
                }
                //
                dc++;
                //
                m(`Adding <strong>"${s.fix()}"</strong> to export.`);
                //
                getGIDocument(s);
            }

            //
            function getGIDocument(s) {
                //
                $.get(`<?= base_url('hr_documents_management/getGeneralDocument'); ?>/${s}/<?= $user_sid; ?>/<?= $user_type; ?>`, (resp) => {
                    //
                    if (resp == '' || resp.length == 0) {
                        nextDocument(s);
                        return;
                    }
                    let o = {
                        title: s,
                        content: resp
                    };
                    //
                    $('#js-export-area div').html(o.content);
                    //
                    generatePDF($('#js-export-area div'), o);
                });
            }

            //
            function checkDocument(i, s) {
                if (has[i] !== undefined) {
                    dc++;
                    m(s);
                    return;
                }
                //
                setTimeout(() => {
                    checkDocument(i, s);
                }, 1000);
            }

            //
            function m(message) {
                //
                let sc = (100 / assignedLength) * dc;
                $('#js-dc').text(dc);
                $('.cs-box p').html(message);
                $('.progress-bar').attr('aria-valuenow', sc);
                $('.progress-bar').attr('aria-valuemin', sc);
                $('.progress-bar').css('width', sc + '%');
            }

            //
            function startGeneratingPDFs() {
                //
                if (
                    (has['I9'] != "null" && has['I9'] !== true) ||
                    (has['W9'] != "null" && has['W9'] !== true) ||
                    (has['W4'] != "null" && has['W4'] !== true) 
                   // (has['W4MN'] != "null" && has['W4MN'] !== true)

                ) {
                    setTimeout(startGeneratingPDFs, 2000);
                    return;
                }
                //
                startMoveProcess(getDocument());
            }

            //
            function getDocument() {
                dc++;
                //
                let sc = assigned[gd];
                //
                if (sc === undefined) return false;
                //
                gd++;
                //
                m(`Adding <strong>"${sc.document_title}"</strong> to export.`);
                //
                return sc;
            }

            //
            function startMoveProcess(dct) {
                if (dct === false) {
                    generateZip();
                    return false;
                }
                //
                if (
                    dct.document_type == 'confidential' ||
                    dct.document_type == 'uploaded' ||
                    dct.offer_letter_type == 'uploaded'
                ) {
                    console.log(dct.document_title)
                    uploadDocument({
                        title: dct.document_title,
                        orig_filename: dct.document_original_name,
                        s3_filename: dct.document_s3_name
                    });
                } else if (
                    dct.document_type == 'generated' ||
                    dct.offer_letter_type == 'generated'
                ) {
                    getSubmittedDocument(dct);
                } else {
                    if (dct.document_sid == 0) {
                        uploadDocument({
                            title: dct.document_title,
                            orig_filename: dct.document_original_name,
                            s3_filename: dct.document_s3_name
                        });
                    } else {

                        getSubmittedDocument(dct);
                    }
                }
            }

            function getSubmittedDocument(dct) {
                //
                $.get(`<?= base_url('hr_documents_management/getSubmittedDocument'); ?>/${dct.sid}/submitted/assigned_document/${dct.document_type}`, (resp) => {
                    var obj = jQuery.parseJSON(resp);
                    $('#js-export-area div').html(obj.html);
                    let o = {
                        title: dct.document_title,
                        content: obj.html
                    };
                    //
                    if (dct.document_type == 'hybrid_document') o.file = dct.document_s3_name;
                    // Check for existing base64
                    if (o.content.indexOf('data:application/pdf;base64,') !== -1) {
                        o.content = o.content.replace(/data:application\/pdf;base64,/, '');
                        uploadDocument(o, o.title);
                    } else {
                        $('#js-export-area div').html(obj.html);
                        //
                        if ($('#jsContentArea').find('select').length >= 0) {
                            $('#jsContentArea').find('select').map(function(i) {
                                //
                                $(this).addClass('js_select_document');
                                $(this).prop('name', 'selectDD' + i);
                            });
                        }
                        //
                        var form_input_data = obj.input_data;
                        //
                        if (form_input_data != null && form_input_data != '') {
                            //
                            form_input_data = Object.entries(form_input_data);
                            //
                            $.each(form_input_data, function(key, input_value) {
                                if (input_value[0].match(/select/) !== -1) {
                                    if (input_value[1] != null) {
                                        let cc = get_select_box_value(input_value[0], input_value[1]);
                                        $(`select.js_select_document[name="${input_value[0]}"]`).html('');
                                        $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);
                                        $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`);
                                    }
                                }
                            });
                        }
                        //
                        generatePDF($('#js-export-area div'), o);
                    }
                });
            }

            function get_select_box_value(select_box_name, select_box_val) {
                var data = select_box_val;
                let cc = '';

                if (select_box_val.indexOf(',') > -1) {
                    data = select_box_val.split(',');
                }


                if ($.isArray(data)) {
                    let modify_string = '';
                    $.each(data, function(key, value) {
                        if (modify_string == '') {
                            modify_string = ' ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                        } else {
                            modify_string = modify_string + ', ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                        }
                    });
                    cc = modify_string;
                } else {
                    cc = $(`select.js_select_document[name="${select_box_name}"] option[value="${select_box_val}"]`).text();
                }

                return cc;
            }

            var XHR = null;
            //
            function uploadDocument(d) {
                //
                if (XHR !== null) {
                    setTimeout(() => {
                        uploadDocument(d);
                    }, 1000);
                    return;
                }
                //
                XHR = $.post("<?= base_url('hr_documents_management/upload'); ?>", {
                    token: token,
                    data: d,
                    typo: 'document',
                    employeeSid: "<?= $user_sid; ?>",
                    userFullNameSlug: "<?= $slug; ?>"
                }, () => {
                    XHR = null;
                    nextDocument(d.title);
                }).fail(() => {
                    setTimeout(() => {
                        uploadDocument(d);
                    }, 1000);
                });
            }

            //
            function generateZip(d) {
                var user_sid = "<?= $user_sid; ?>";
                var user_type = "<?= $user_type; ?>";
                var company_sid = "<?= $company_sid; ?>";
                window.location.href = "<?= base_url('hr_documents_management/generate_zip'); ?>/" + token + "/<?= $slug; ?>/" + user_sid + "/" + user_type + "/" + company_sid;
                setTimeout(() => {
                    // window.close();
                }, 10000);
            }

            //
            function nextDocument(lastDocument) {
                //
                if (lastDocument == 'direct_deposit') {
                    exportGDocument('dependents');
                    return;
                }
                //
                if (lastDocument == 'dependents') {
                    exportGDocument('emergency_contacts');
                    return;
                }
                //
                if (lastDocument == 'emergency_contacts') {
                    exportGDocument('drivers_license');
                    return;
                }
                //
                if (lastDocument == 'drivers_license') {
                    exportGDocument('occupational_license');
                    return;
                }
                //
                setTimeout(() => {
                    startMoveProcess(getDocument());
                }, 1000);
            }

            //
            function generatePDF(
                target,
                o
            ) {
                let draw = kendo.drawing;
                draw.drawDOM(target, {
                        avoidLinks: false,
                        paperSize: "A4",
                        multiPage: true,
                        height: 500,
                        forcePageBreak: '.js-break',
                        margin: {
                            bottom: "1cm",
                            left: "1cm",
                            top: ".3cm",
                            right: "1cm"
                        },
                        scale: 0.8
                    })
                    .then(function(root) {
                        return draw.exportPDF(root);
                    })
                    .done(function(data) {
                        //
                        o.content = data;
                        //
                        uploadDocument(
                            o
                        );
                    });
            }
        })
    </script>


    <!--  -->
    <div style="float: left; margin-left: -1000px; width: 800px;">
        <?php
        if (count($documents['I9'])) {
            $this->load->view('2022/federal_fillable/form_i9_download_bulk', [
                'pre_form' => $documents['I9'],
                'doUpload' => 1,
                'token' => $token,
                'employeeSid' => $user_sid,
                'userFullNameSlug' => $slug
            ]);
        }

        if (count($documents['W9'])) {
            $this->load->view('form_w9/form_w9_pdf', [
                'pre_form' => $documents['W9'],
                'doUpload' => 1,
                'token' => $token,
                'employeeSid' => $user_sid,
                'userFullNameSlug' => $slug
            ]);
        }

        if (count($documents['W4'])) {
            //
            $assign_on = date("Y-m-d", strtotime($documents['W4']['sent_date']));
            $compare_date = date("Y-m-d", strtotime('2020-01-06'));
            //
            $this->load->view('form_w4/' . ($assign_on >= $compare_date ? "form_w4_2020_pdf" : "form_w4_pdf") . '', [
                'pre_form' => $documents['W4'],
                'doUpload' => 1,
                'token' => $token,
                'employeeSid' => $user_sid,
                'userFullNameSlug' => $slug
            ]);
        }

        //

        /*
        if (count($documents['W4MN'])) {
            //
            $formData = json_decode($documents['W4MN']['fields_json'], true);
            $e_signature_data = get_e_signature($company_sid, $user_sid, 'employee');
            $employerData = json_decode($documents['W4MN']['employer_json'], true);

            $this->load->view('v1/forms/2020_w4_mn_print_download', ['formData' => $formData, 'action' => 'download', 'doUpload' => '1', 'signature' => $e_signature_data['signature_bas64_image'], 'location' => 'green', 'employerData' => $employerData]);
            //
        }
        */

        ?>
    </div>


    <!--  -->
    <div id="js-export-area" style="
position: fixed;
left: -1000px;
top: 0;
max-width: 800px;
overflow: hidden;
padding: 16px;
font-size: 16px;
word-break: break-all; 
">
        <div class="A4"></div>
    </div>
</body>

</html>