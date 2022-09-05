<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Print</title>
    <!--  -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
</head>

<body>

    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-sm-12">
                <h3>Employee(s) with pending document(s) </h3>
                <h4><?= $company_name; ?></h4>
                <h4><?= date('m-d-Y H:i:s', strtotime('now')); ?></h4>
                <hr />
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <?php if (in_array('w4', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                <th>W4 Status</th>
                            <?php } ?>
                            <?php if (in_array('i9', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                <th>I9 Status</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee) {

                            if (sizeof($employee['Documents'])) {

                                usort($employee['Documents'], 'dateSorter');

                                $w4_status = '<strong class="text-warning">Not Assigned</strong>';
                                $i9_status = '<strong class="text-warning">Not Assigned</strong>';

                                foreach ($employee['Documents'] as $ke => $v) {

                                    //
                                    if ($v['Title'] == "W4 Fillable") {
                                        if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                            if ($v['Status'] == "pending") {
                                                $w4_status = '<strong class="text-danger">Pending</strong>';
                                            } else {
                                                $w4_status = '<strong class="text-success">Completed</strong>';
                                            }
                                        }
                                    }


                                    //
                                    if ($v['Title'] == "I9 Fillable") {
                                        if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                            if ($v['Status'] == "pending") {
                                                $i9_status = '<strong class="text-danger">Pending</strong>';;
                                            } else {
                                                $i9_status = '<strong class="text-success">Completed</strong>';
                                            }
                                        }
                                    }
                                }
                            }

                        ?>
                            <tr>
                                <td><?= remakeEmployeeName($employee); ?></td>
                                <?php if (in_array('w4', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                    <td><?php echo $w4_status; ?></td>
                                <?php } ?>

                                <?php if (in_array('i9', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                    <td><?php echo $i9_status; ?> </td>
                                <?php } ?>


                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        window.print();
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>

</html>