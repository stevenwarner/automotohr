<!-- Main Start -->
<?php // echo '<pre>'; print_r($documents); echo '</pre>'; ?>
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
                        <span class="page-heading down-arrow"><a class="dashboard-link-btn" href="<?php echo base_url('hr_documents'); ?>"><i class="fa fa-chevron-left"></i>Admin HR Documents</a><?php echo $title; ?> </span>
                    </div>
                    <div class="create-job-wrap">
                        <?php
                        if (count($documents) == 0 && count($offerLetters) == 0) {
                            ?>
                            <div class="archived-document-area">
                                <div class="cloud-icon"><i class="fa fa-cloud-upload"></i></div>
                                <div class="archived-heading-area">
                                    <?php if ($page == 'all') { ?>
                                        <h2>All Documents Have Been Archived... Upload New HR Docs!</h2>
                                    <?php } else if ($page == 'archive') { ?>
                                        <h2>No Archived Document!</h2>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php
                        } else {
                            if (count($documents) > 0) {
                                ?>
                                <div class="table-responsive">
                                    <h3>Employee: <b><?php echo $userDetail['first_name']; ?> <?php echo $userDetail['last_name']; ?></b></h3>
                                    <div class="hr-document-list">
                                        <table class="hr-doc-list-table">
                                            <thead>
                                                <tr>                                                
                                                    <th>Document Name</th>
                                                    <th>Sent On</th>
                                                    <th>Acknowledged</th>
                                                    <th>Downloaded</th>
                                                    <th>Uploaded</th>
                                                    <!--<th style="text-align: right" >Action</th>-->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($documents as $document) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo ucfirst($document['document_name']); ?></td>
                                                        <td><?php echo date_time_not_day($document['sent_on']); ?></td>
                                                        <td><?php
                                                            if ($document['acknowledged']) {
                                                                echo '<i class="fa fa-check"></i> ' . date_time_not_day($document['acknowledged_date']);
                                                            } else {
                                                                echo '<i class="fa fa-times"></i>';
                                                            }
                                                            ?></td>
                                                        <td><?php
                                                            if ($document['downloaded']) {
                                                                echo '<i class="fa fa-check"></i> ' . date_time_not_day($document['downloaded_date']);
                                                            } else {
                                                                echo '<i class="fa fa-times"></i>';
                                                            }
                                                            ?></td>
                                                        <td><?php
                                                            if ($document['uploaded']) {
                                                                echo '<i class="fa fa-check"></i> ' . date_time_not_day($document['uploaded_date']);
                                                            } else {
                                                                echo '<i class="fa fa-times"></i>';
                                                            }
                                                            ?></td>
            <!--                                                        <td>
                                                            <a  href="javascript:;" onclick="send_reminder(this.id)" id="<?php echo $document['userDocumentSid'] ?>" class="action-btn">
                                                                <i class="fa fa-refresh"></i>
                                                                <span class="btn-tooltip">Send Reminder</span>
                                                            </a>
                                                        </td>-->
                                                    </tr>
                                                <?php }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="btn-panel">                                
                                            <a class="delete-all-btn active-btn"  onclick="send_reminder(this.id)" id="<?php echo $documents[0]['userDocumentSid'] ?>"  id="ej_active" href="javascript:;">Send Reminder</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } else {
                                ?>
                                <table class="hr-doc-list-table">
                                    <thead>
                                        <tr>                                                
                                            <th>Document Name</th>
                                            <th>Sent On</th>
                                            <th>Acknowledged</th>
                                            <th>Downloaded</th>
                                            <th>Uploaded</th>
                                            <!--<th style="text-align: right" >Action</th>-->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5">No Document Avaliable</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Main End -->
<script type = "text/javascript">
    function send_reminder(id) {
        url = "<?= base_url() ?>hr_documents/send_document_reminder";
        alertify.confirm('Confirmation', "Are you sure you want to send a Document Reminder?",
                function () {
                    $.post(url, {user_document_sid: id})
                            .done(function (data) {
//                                console.log(data);
                                location.reload();
                            });
                },
                function () {
                });
    }
</script>