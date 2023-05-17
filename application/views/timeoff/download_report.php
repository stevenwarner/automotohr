<?php 
    $exportFileName = 'Timeoff Report '.date('m-d-Y H:i', strtotime('now'));
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Download - Time off Report <?=date('m/d/Y H:i', strtotime('now'));?></title>
        <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css');?>">
        <style>
        * {
            font-size: 14px;
        }

        </style>
    </head>

    <body>
        <div style="width: 750px;" id="grid">
            <div class="row">
                <div class="col-xs-6">
                    <p>Company: <strong><?=$session['company_detail']['CompanyName'];?></strong></p>
                    <p>Employee Name:
                        <strong><?=ucwords($session['employer_detail']['first_name'].' '.$session['employer_detail']['last_name']);?>
                            <?=remakeEmployeeName($session['employer_detail'], false);?></strong></p>
                    <p>Report Period: <strong>
                            <?php 
                    if($this->input->get('start', true) && $this->input->get('end', true)){
                        echo $this->input->get('start', true).' - '.$this->input->get('end', true);
                    } else if($this->input->get('start', true)){
                        echo $this->input->get('start', true).' - N/A';
                    } else if($this->input->get('end', true)){
                        echo 'N/A - '.$this->input->get('end', true);
                    } else{
                        echo 'N/A';
                    }
                    ?>
                        </strong>
                    <p>
                </div>
                <div class="col-xs-6">
                    <p class="text-right">Report Date <strong><?=date('m/d/Y H:i', strtotime('now'));?></strong></p>
                </div>
                <hr />
            </div>
            <div class="row" style="margin: 5px 5px;">
                <div class="col-lg-2" style="background: rgba(242, 222, 222, .5); padding: 16px;"></div>
                <div class="col-lg-10" style="padding: 6px; font-weight: 700;">The allotted time off has been consumed.</div>
            </div>
            <div class="col-sm-12">
                <table class="table table-striped table-condensed table-bordered">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Employee</th>
                            <th scope="col">Policy</th>
                            <th scope="col">Time Taken</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                if(!empty($data)){
                    foreach($data as $row){
                        $processRequest = splitTimeoffRequest($row);
                        //
                        if ($processRequest['type'] == 'multiple') {
                            //
                            foreach ($processRequest['requestData'] as $split) {
                                echo generateTimeoffRequestSlot($split, 'multiple');
                            }
                            //
                        } else {
                            echo generateTimeoffRequestSlot($processRequest['requestData'], 'single');
                        }
                    }
                } else{
                    echo '<tr><td colspan="5">Sorry, no records found.</td></tr>';
                } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>">
        </script>
        <script type="text/javascript">
        $(function() {
            var draw = kendo.drawing;
            draw.drawDOM($("#grid"), {
                    avoidLinks: false,
                    paperSize: "A4",
                    multiPage: true,
                    margin: {
                        bottom: "1cm"
                    },
                    scale: 0.8
                })
                .then(function(root) {
                    return draw.exportPDF(root);
                })
                .done(function(data) {
                    kendo.saveAs({
                        dataURI: data,
                        fileName: "<?=$exportFileName;?>.pdf",
                    });
                });
        });
        </script>
    </body>

</html>
