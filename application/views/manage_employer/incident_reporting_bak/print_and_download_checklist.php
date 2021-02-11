<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <title><?php echo $title; ?></title>
</head>

<style type="text/css">
    @font-face {
        font-family: 'Conv_SCRIPTIN';
        src: url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.eot') ?>');
        src: local('☺'), url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.woff') ?>') format('woff'),
        url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.ttf') ?>') format('truetype'),
        url('<?php echo base_url('assets/employee_panel/fonts/SCRIPTIN.svg') ?>') format('svg');
        font-weight: normal;
        font-style: normal;
    }
    @page { margin: 0 }
    body { margin: 0 }
    .sheet {
        margin: 0;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
        page-break-after: always;
        font-size: 14px;
        font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
    }

    /** Paper sizes **/
    body.A3           .sheet { width: 297mm; height: 419mm }
    body.A3.landscape .sheet { width: 420mm; height: 296mm }
    body.A4           .sheet { width: 210mm; /*height: 296mm;*/ margin: 5mm; }
    body.A4.landscape .sheet { width: 297mm; height: 209mm }
    body.A5           .sheet { width: 148mm; height: 209mm }
    body.A5.landscape .sheet { width: 210mm; height: 147mm }

    /** Padding area **/
    .sheet.padding-10mm { padding: 10mm }
    .sheet.padding-15mm { padding: 15mm }
    .sheet.padding-20mm { padding: 20mm }
    .sheet.padding-25mm { padding: 25mm }

    /** For screen preview **/
    @media screen {
        /*body { background: #e0e0e0 }*/
        .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
            margin: 5mm;
        }
    }

    /** Fix for Chrome issue #273306 **/
    @media print {
        body.A3.landscape { width: 420mm }
        body.A3, body.A4.landscape { width: 297mm }
        body.A4, body.A5.landscape { width: 210mm }
        body.A5                    { width: 148mm }
    }

    .sheet-header {
        float: left;
        width: 100%;
        padding: 0 0 2px 0;
        margin: 0 0 5px 0;
        border-bottom: 5px solid #000;
    }
    .header-logo{
        float: left;
        width: 100%;
    }
    .center-col{
        float: left;
        width: 100%;
        text-align: center;
        margin-top: 14px;
    }
    .center-col h2,
    .center-col p{
        margin: 0 0 5px 0;
    }
    .right-header{
        float: left;
        width: 20%;
        text-align: center;
    }
    .right-header h3,
    .right-header p{
        margin: 0 0 5px 0;
    }
    .right-header p{
        font-size: 14px;
    }
    .w9-table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 5px;
        border: 1px solid #000;
        text-align: left;
        border-collapse: collapse;
    }
    .w9-table thead > tr > th,
    .w9-table tbody > tr > th,
    .w9-table tfoot > tr > th,
    .w9-table thead > tr > td,
    .w9-table tbody > tr > td,
    .w9-table tfoot > tr > td {
        padding: 4px;
        border: 1px solid #000;
        vertical-align: top;
    }
    .bg-gray{
        background-color: #C9C9C9;
    }
    .value-box {
        float: left;
        width: 100%;
        min-height: 20px;
        padding: 3px;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .w9-table.no-border{
        border:none !important;
    }
    .w9-table.no-border thead tr th,
    .w9-table.no-border tbody tr td{
        border:none !important;
    }
    ul{
        list-style: none;
    }
    .signature-field{
        border: 0;
        padding: 0 36px;
        font-size: 24px;
        font-weight: bold;
        font-family: 'Conv_SCRIPTIN';
        word-spacing: 15px;
        line-height: 56px;}
</style>
<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4" id="safety_checklist">
    <section class="sheet padding-10mm">
        <article class="sheet-header">
            <div class="header-logo">
              <h2 style="margin: 0;"><?php echo $company_name; ?></h2>
              <small><?php echo $action_date; ?>: <b><?php echo date('Y-m-d'); ?></b><br><?php echo $action_by; ?>: <b><?php echo $employee_name; ?></b></small>
            </div>
            <div class="center-col">
                <h2><?php echo $title; ?></h2>
            </div>
        </article>
        <?php if (sizeof($questions) > 0) { ?>
            <table class="w9-table">
                <thead>
                    <tr class="bg-gray">
                        <th colspan="2">
                            <strong>Note</strong> (Field is required with * sign.)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3">
                            <label><strong>1.</strong> Your Full Name : <span>*</span></label>
                            <span class="value-box bg-gray"></span>
                        </td>
                    </tr>
                    <?php 
                        foreach ($questions as $key => $question) { 
                            $question_type = $question['question_type'];
                            $label =  strip_tags($question['label']);
                            $is_required = $question['is_required'] ? '*' : '';
                            $options = explode(',', $question['options']);

                            $select_box = array("multi select", "single select");
                            $input_box = array("text", "time", "date");

                            if ($question_type == 'multi select') {
                                $hint = 'You can select multiple items.';
                            } else if ($question_type == 'single select') {
                                $hint = 'You can select only one item.';
                            }
                    ?>
                            <?php if ($question_type == 'textarea') { ?>   
                                <tr>
                                    <td colspan="3">
                                        <label>
                                            <strong><?php echo $key+1; ?></strong> 
                                            <?php echo $label; ?>: <span><?php echo $is_required; ?></span>
                                        </label>
                                        <div style="border: 1px dotted #000; padding:5px; min-height: 145px;" class="div-editable fillable_input_field bg-gray" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>
                                    </td>
                                </tr>
                            <?php } elseif (in_array($question_type, $input_box)) { ?>
                                <tr>
                                    <td colspan="3">
                                        <label>
                                            <strong><?php echo $key+1; ?></strong> 
                                            <?php echo $label; ?>: <span><?php echo $is_required; ?></span>
                                        </label>
                                        <span class="value-box bg-gray"></span>
                                    </td>
                                </tr>
                            <?php } elseif ($question_type == 'signature') { ?>   
                                <tr>
                                    <td width="5%">
                                        <strong>Sign<br/>Here</strong>
                                    </td>
                                    <td width="60%">
                                        <div style="float: left;"><strong><small>Signature</small></strong></div>
                                    </td>
                                    <td>
                                        <span><strong><small>Date</small></strong></span><span></span>
                                    </td>
                                </tr>
                            <?php } elseif ($question_type == 'radio') { ?>
                                <tr>
                                    <td colspan="3">
                                        <strong><?php echo $key+1; ?></strong> 
                                        <?php echo $label; ?>: <span><?php echo $is_required; ?></span> 
                                        <table class="w9-table no-border">
                                            <tr>
                                                <td width="500">
                                                    <input type="checkbox" disabled="disabled">
                                                    <label for="sole_proprietor">Yes</label>
                                                </td>
                                                <td width="500">
                                                    <input type="checkbox" disabled="disabled">
                                                    <label for="c_corporation">No</label>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            <?php } elseif (in_array($question_type, $select_box)) { ?>
                                <tr>
                                    <td colspan="3">
                                        <strong><?php echo $key+1; ?></strong> 
                                        <?php echo $label; ?>: <span><?php echo $is_required; ?></span> 
                                        <?php foreach ($options as $option) { ?>
                                            <table class="w9-table no-border">
                                                <tr>
                                                    <td width="500">
                                                        <input type="checkbox" disabled="disabled">
                                                        <label for="sole_proprietor">
                                                            <?php echo $option; ?>
                                                        </label>
                                                    </td>
                                                </tr>
                                            </table>    
                                        <?php } ?> 
                                    </td>
                                </tr>
                            <?php } ?>    
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <article class="sheet-header">
                <div class="center-col">
                    <h2>No Questions Scheduled For This Type</h2>
                </div>
            </article>
        <?php } ?>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>
    <script type="text/javascript">

        $(window).on( "load", function() { 
            var action = '<?php echo $action; ?>';

            if (action == 'print') {
                setTimeout(function(){
                    window.print();
                }, 1000);

                window.onafterprint = function(){
                    window.close();
                }
            } else if (action == 'download') {
                var draw = kendo.drawing;
                draw.drawDOM($("#safety_checklist"), {
                    avoidLinks: false,
                    paperSize: "auto",
                    multiPage: true,
                    margin: { bottom: "1cm" },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    $('#myiframe').attr("src",data);
                    kendo.saveAs({
                        dataURI: data,
                        fileName: '<?php echo "safety_checklist.pdf"; ?>',
                    });
                });
                setTimeout(function(){
                    window.close();
                }, 1000);    
            }
        });    
    </script>        
</body>
</html>