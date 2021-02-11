<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print</title>
    <!--  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
</head>
<body>
    
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-sm-12">
                <h3>Employee(s) with pending document(s) </h3>
                <h4><?=$company_name;?></h4>
                <h4><?=date('m-d-Y H:i:s', strtotime('now'));?></h4>
                <hr />
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Email</th>
                            <th>Document Count</th>
                            <th>Document(s)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $k => $v) { ?>
                            <tr>
                                <td><?=remakeEmployeeName($v);?></td>
                                <td><?=$v['email'];?></td>
                                <td class="col-sm-1"><?=sizeof($v['Documents']);?></td>
                                <td class="col-sm-4">
                                    <?php
                                        foreach ($v['Documents'] as $k1 => $v1) {
                                            echo '<p>- '.( $v1['Title'] ).' ('.( $v1['Type'] ).')</p>';
                                        }
                                    ?>
                                </td>
                                <td class="col-sm-1">Pending</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        window.print();
        window.onafterprint = function(){ window.close(); }
    </script>
</body>
</html>