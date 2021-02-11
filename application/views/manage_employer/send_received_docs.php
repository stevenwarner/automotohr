<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php echo $title; ?> </span>
                    </div>
                    <div class="table-responsive table-outer">
                        <div class="table-wrp product-detail-area mylistings-wrp">
                            <table class="table" id="example"  data-order='[[ 0, "desc" ]]'>
                                <thead>
                                    <tr>
                                        <th>Document</th>
                                        <th>Sender</th>
                                        <th>Receiver</th>
                                        <th>Acknowledged</th>
                                        <th>Downloaded</th>
                                        <th>Uploaded</th>
                                    </tr> 
                                </thead>
                                <tbody>
                                    <?php foreach ($documents as $document) { ?>
                                        <tr>
                                            <td><?= $document["username"] ?></td>
                                            <td><?= $document["sender_sid"] ?></td>
                                            <td><?= $document["receiver_sid"] ?></td>
                                            <td><?= $document["acknowledged"] ?></td>
                                            <td><?= $document["acknowledged"] ?></td>
                                            <td><?= $document["uploaded"] ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/dataTables.bootstrap.min.css">

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            paging: false,
            info: false,
            stateSave: true
        });
    });
</script>