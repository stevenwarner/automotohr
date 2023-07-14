<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>">
</head>
<body>
<div class="container" style="margin-top: 50px;">
        <div class="row">
                <h3>Managers with Pending Document Actions</h3>
                <h4><?=$company_name;?></h4>
                <h4><?=date('m-d-Y H:i:s', strtotime('now'));?></h4>
                <hr />
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Document</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($managers)) {
                            foreach ($managers as $manager) {
                        ?>
                                <tr>
                                    <td>
                                        <?= getUserNameBySID($manager['user_sid']); ?>
                                    </td>
                                    <td>
                                        <?= $manager['document_name']; ?> <br>(Verification)
                                    </td>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="3">
                                    <p class="alert alert-info text-center">
                                        No records found
                                    </p>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    <script>
        window.print();
        window.onafterprint = function(){ window.close(); }
    </script>
</body>
</html>