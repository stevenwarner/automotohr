<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Direct Deposit Form</title>
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css');?>">
    
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/alertify.min.css"/>
    <link rel="stylesheet" href="<?= base_url() ?>assets/alertifyjs/css/themes/default.min.css"/>
    
    <style>
        @font-face {
            font-family: 'Conv_SCRIPTIN';
            src: url('../fonts/SCRIPTIN.eot');
            src: local('?'), url('../fonts/SCRIPTIN.woff') format('woff'),
            url('../fonts/SCRIPTIN.ttf') format('truetype'),
            url('../fonts/SCRIPTIN.svg') format('svg');
            font-weight: normal;
            font-style: normal;
        }

        @page {
            margin: 0
        }

        body {
            margin: 0
        }

        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
            font-size: 14px;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        }

        /** Paper sizes **/
        body.A3 .sheet {
            width: 297mm;
            height: 419mm
        }

        body.A3.landscape .sheet {
            width: 420mm;
            height: 296mm
        }

        body.A4 .sheet {
            width: 210mm; /*height: 296mm;*/
            margin: 5mm;
        }

        body.A4.landscape .sheet {
            width: 297mm;
            height: 209mm
        }

        body.A5 .sheet {
            width: 148mm;
            height: 209mm
        }

        body.A5.landscape .sheet {
            width: 210mm;
            height: 147mm
        }

        /** Padding area **/
        .sheet.padding-10mm {
            padding: 10mm
        }

        .sheet.padding-15mm {
            padding: 15mm
        }

        .sheet.padding-20mm {
            padding: 20mm
        }

        .sheet.padding-25mm {
            padding: 25mm
        }

        /** For screen preview **/
        @media screen {
            body {
                /*background: #e0e0e0*/
            }

            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0, 0, 0, .3);
                margin: 5mm;
            }
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.A3.landscape {
                width: 420mm
            }

            body.A3, body.A4.landscape {
                width: 297mm
            }

            body.A4, body.A5.landscape {
                width: 210mm
            }

            body.A5 {
                width: 148mm
            }
        }

        .sheet-header {
            float: left;
            width: 100%;
            padding: 0 0 2px 0;
            margin: 0 0 5px 0;
            border-bottom: 5px solid #000;
        }

        .header-logo {
            float: left;
            width: 20%;
        }

        .center-col {
            float: left;
            width: 60%;
            text-align: center;
        }

        .center-col h2,
        .center-col p {
            margin: 0 0 5px 0;
        }

        .right-header {
            float: left;
            width: 20%;
            text-align: center;
        }

        .right-header h3,
        .right-header p {
            margin: 0 0 5px 0;
        }

        .right-header p {
            font-size: 14px;
        }
    </style>

    <style>
        #jsPDF input{ border: 0 !important; border-bottom: 1px solid #000000 !important; width: 80%; font-size: 14px; font-weight: bold}
        #jsPDF p{ font-size: 16px;}
        #jsPDF .row{ margin-top: 10px;}
    </style>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/style.css">
</head>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
    <?php 
        $instruction = '';
        $employee_sid = '';
        $print_name = '';
        $last_update_date = '';
        $user_signature = '';
        $user_sid = isset($dd_user_sid) ? $dd_user_sid : '';
        $user_print_name = ucwords($cn['first_name'].' '.$cn['last_name']);
        $user_primary_signature = isset($users_sign_info) && isset($users_sign_info['signature_bas64_image']) ? $users_sign_info['signature_bas64_image'] : '';

        for($i = 0; $i < 1; $i++) {
            $instruction = isset($data[$i]) && isset($data[$i]['instructions']) ? $data[$i]['instructions'] : '';
            $employee_sid = isset($data[$i]) && isset($data[$i]['employee_id']) ? $data[$i]['employee_id'] : $user_sid;
            $print_name = isset($data[$i]) && isset($data[$i]['print_name']) ? $data[$i]['print_name'] : $user_print_name;
            $last_update_date = isset($data[$i]) && isset($data[$i]['consent_date']) ? date("m/d/Y", strtotime($data[$i]['consent_date'])) : '';
            $user_signature = isset($data[$i]) && isset($data[$i]['user_signature']) ? $data[$i]['user_signature'] : $user_primary_signature;
        }    
    ?>

     <div id="jsPDF"  style="width: 600px; margin: auto; padding: 5px;">
        <div>
            <div class="row">
                <div class="col-xs-12">
                    <h2>Direct Deposit Authorization</h2>
                </div>
                <?php if(isset($applicant)): ?>
                    <div class="col-xs-12">
                        <p><strong>Applicant Name:</strong> <?=ucwords($applicant['first_name'].' '.$applicant['last_name']);?></p>
                        <?php if(!empty($applicant['Title'])): ?> <p><strong>Job Title:</strong> <?=$applicant['Title'];?></p>
                        <?php elseif(!empty($applicant['desired_job_title'])): ?> <p><strong>Job Title:</strong> <?=$applicant['desired_job_title'];?></p>
                        <?php endif; ?>
                        <p><strong>Company:</strong> <?=$session['company_detail']['CompanyName'];?></p>
                    </div>
                <?php else: ?>
                    <div class="col-xs-12">
                        <p><strong>Employee Name:</strong> <?=ucwords($employee['first_name'].' '.$employee['last_name']);?></p>
                        <?php if(!empty($employee['Title'])): ?> <p><strong>Job Title:</strong> <?=$employee['Title'];?></p>
                        <?php elseif(!empty($employee['desired_job_title'])): ?> <p><strong>Job Title:</strong> <?=$employee['desired_job_title'];?></p>
                        <?php endif; ?>
                        <p><strong>Company:</strong> <?=$session['company_detail']['CompanyName'];?></p>
                    </div>
                <?php endif; ?>
                <div class="col-sm-12">
                <hr />
                </div>
            </div>


            <div class="row">
                <div class="col-xs-12">
                    <p><strong>Instructions <input type="text" style="width: 82% !important" value="<?php echo $instruction; ?>" /></strong></p>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <p><strong>Employee:</strong> Fill out and return to your employer</p>
                    <p><strong>Employer:</strong> Save for your files only.</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12">
                    <p class="text-justify">This document must be signed by employees requesting automatic deposit of paychecks and retained on file by the employer. Employees must attach a voided check for each of their accounts to help verify their account numbers and bank routing numbers.</p>
                </div>
            </div>

            <?php if(count($data)) {?>
            <?php   foreach($data as $k0 => $account) {?>
                <div class="row">
                    <div class="col-xs-12">
                        <p><strong>Account <?= $k0 + 1;?> <input type="text" style="width: 53% !important" value="<?=$account['account_title'];?>" /></strong></p>
                        <br />
                    </div>
                    <div class="col-xs-3">
                        <p><strong>Account <?= $k0 + 1;?> type:</strong></p>
                    </div>
                    <div class="col-xs-3">
                        <input type="radio" name="actype<?= $k0 + 1;?>" style="width: 20px;" value="checking" <?php echo $account['account_type'] == 'checking' ? 'checked="true"' : '';?>/>
                        <label>Checking</label>
                    </div>
                    <div class="col-xs-2">
                        <input type="radio" name="actype<?= $k0 + 1;?>" style="width: 20px;" value="saving" <?php echo $account['account_type'] == 'savings' ? 'checked="true"' : '';?>/>
                        <label>Saving</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <p>Bank routing number (ABA number): <input type="text" style="width: 53% !important" value="<?=$account['routing_transaction_number'];?>" /></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <p>Account number: <input type="text" style="width: 77.5% !important" value="<?=$account['account_number'];?>"  /></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xs-12">
                        <p>Percentage or dollar amount to be deposited to this account: <input type="text" style="width: 22% !important" value="<?=$account['account_percentage'];?>"  /></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <img width="600" src="<?php echo $account['voided_cheque_64'];?>"/>
                    </div>
                </div>
                <br />
            <?php   }?>
            <?php }?>


            <hr />

            <!--  -->
            <div class="row">
                <div class="col-xs-12">
                    <p><strong>Authorization</strong> (enter your company name in the blank space below) <input  type="text" style="width: 12% !important;"/></p>
                </div>
                <div class="col-xs-12">
                    <p>This authorizes <input type="text" style="width: 59% !important;" value="<?=isset($data[0]['CompanyName']) ? $data[0]['CompanyName'] : '';?>" />(the “Company”)</p>
                    <p class="text-justify">to send credit entries (and appropriate debit and adjustment entries), electronically or by any other commercially accepted method, to my (our) account(s) indicated below and to other accounts I (we) identify in the future (the “Account”). This authorizes the financial institution holding the Account to post all such entries. I agree that the ACH transactions authorized herein shall comply with all applicable U.S. Law. This authorization will be in effect until the Company receives a written termination notice from myself and has a reasonable opportunity to act on it.</p>
                </div>
            </div>
            
            <hr />
            
            <div class="row">
                <div class="col-xs-6">
                    <p><strong>Authorized signature:<strong></p>
                    <p>
                        <img style="max-height: <?=SIGNATURE_MAX_HEIGHT;?>" src="<?=$user_signature;?>"  id="draw_upload_img" />
                    </p>
                </div>
                <div class="col-xs-6">
                    <p><strong>Employee Number #:<strong></p>
                    <p><input type="text"  style="width: 100%" value="<?=!empty($employee_number) ? $employee_number : $users_sid;?>"  /></p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-6">
                    <p><strong>Print name:<strong></p>
                    <p><input type="text"  style="width: 100%" value="<?php echo ucwords($print_name); ?>" /></p>
                </div>
                <div class="col-xs-6">
                    <p><strong>Date:<strong></p>
                    <p><input type="text"  style="width: 100%" value="<?php echo $last_update_date; ?>" /></p>
                </div>
            </div>
            
        </div>
     </div>   
      
    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery-1.11.3.min.js'); ?>"></script>
    <script src="<?=base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?=base_url('assets/js/jquery.validate.min.js');?>"></script>
    <script src="<?= base_url() ?>assets/alertifyjs/alertify.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

    
    <script type="text/javascript">
    <?php if($type == 'download') { ?>
        $( window ).on( "load", function() {
            var draw = kendo.drawing;
            draw.drawDOM($("#jsPDF"), {
                avoidLinks: false,
                paperSize: "A4",
                multiPage: true,
                margin: { bottom: "2"},
                scale: 0.8
            })
            .then(function(root) {
                return draw.exportPDF(root);
            })
            .done(function(data) {
                kendo.saveAs({
                    dataURI: data,
                    fileName: "direct_deposit_form.pdf"
                });
                //
                setTimeout(() => {
                    window.close();   
                }, 5000);
            });
        });
    <?php } else if($type == 'print') { ?>
        window.print();
        window.onafterprint = function() { window.close(); }
    <?php }?>
    </script>
</body>
</html>
