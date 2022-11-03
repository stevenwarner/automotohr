<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print - Time off Report <?=date('m/d/Y H:i', strtotime('now'));?></title>
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css');?>">
    <style>
        *{
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <p>Company: <strong><?=$session['company_detail']['CompanyName'];?></strong></p>
                <p>Employee Name: <strong><?=ucwords($session['employer_detail']['first_name'].' '.$session['employer_detail']['last_name']);?> <?=remakeEmployeeName($session['employer_detail'], false);?></strong></p>
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
        <table class="table table-striped table-condensed table-bordered">
            <caption></caption>
            <thead>
                <tr>
                    <th scope="col">Employee</th>
                    <th scope="col">Policy</th>
                    <th scope="col">Time Taken</th>
                    <th scope="col">Start Date</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Approved/Rejected By</th>
                    <th scope="col">Approved/Rejected Date</th>
                    <th scope="col">Approver Comments</th>
                    <th scope="col">Status</th>
                    <th scope="col">Submitted Date</th>
                    <th scope="col">Employee Comments</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(!empty($data)){
                    foreach($data as $row){
                        echo '<tr>';
                        echo '  <td>'.( ucwords($row['first_name'].' '.$row['last_name']) ).' <br /> '.( remakeEmployeeName($row, false) ).' <br /> '.( !empty($row['employee_number']) ? $row['employee_number'] : $row['employeeId'] ).'</td>';
                        echo '  <td>'.( $row['title'] ).'</td>';
                        echo '  <td>'.( $row['consumed_time'] ).'</td>';
                        echo '  <td>'.( DateTime::createfromformat('Y-m-d', $row['request_from_date'])->format('m/d/Y') ).'</td>';
                        echo '  <td>'.( DateTime::createfromformat('Y-m-d', $row['request_to_date'])->format('m/d/Y') ).'</td>';
                        //
                        if (!empty($row['approvalInfo']['approverName']) && !empty($row['approvalInfo']['approverRole'])) {
                            echo '  <td>'.$row['approvalInfo']['approverName'].'<br>'.$row['approvalInfo']['approverRole'].'</td>';
                        } else {
                            echo '  <td>-</td>';
                        }
                        //
                        if (!empty($row['approvalInfo']['approverDate'])) {
                            echo '  <td>'.DateTime::createfromformat('Y-m-d H:i:s', $row['approvalInfo']['approverDate'])->format('m/d/Y').'</td>';
                        } else {
                            echo '  <td>-</td>';
                        }
                        //
                        if (!empty($row['approvalInfo']['approverNote'])) {
                            echo '  <td>'.$row['approvalInfo']['approverNote'].'</td>';
                        } else {
                            echo '  <td>-</td>';
                        }
                        //
                        $status = $row['status']; 
                        //
                        if ($status == 'approved') {
                            echo '<td><p class="text-success"><b>APPROVED</b></p></td>';
                        } else if ($status == 'rejected') {
                            echo '<td><p class="text-danger"><b>REJECTED</b></p></td>';
                        } else if ($status == 'pending') {
                            echo '<td><p class="text-warning"><b>PENDING</b></p></td>';
                        }
                        //
                        echo '<td>'.DateTime::createfromformat('Y-m-d H:i:s', $row['created_at'])->format('m/d/Y').'</td>';
                        echo '<td>'.$row['reason'].'</td>';
                        //
                        echo '</tr>';
                    }
                } else{
                    echo '<tr><td colspan="5">Sorry, no records found.</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>

    <script>
        window.print();
        window.onafterprint = window.close;
    </script>
</body>
</html>