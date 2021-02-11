<style>
    .signature_status_bulb {
        width: 22px; 
        height: 22px;  
        display: block; 
        margin-left: auto; 
        margin-right: auto;
    }
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo base_url('dashboard'); ?>">
                                        <i class="fa fa-chevron-left"></i>Dashboard</a>
                                    Assigned Documents
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header full-width">
                            <h1 class="section-ttile">Assigned Documents Library</h1>
                            <strong> Information:</strong> If you are unable to view the authorized documents library, kindly reload the page.
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"> 
                        <?php if (!empty($documents_list)) { ?>
                            <div class="table-responsive full-width">
                                <table class="table table-plane js-uncompleted-docs">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-4">Document Name</th>
                                            <th class="col-lg-2">Assigned Date</th>
                                            <th class="col-lg-2">Document Signature Status</th>
                                            <th class="col-lg-4 text-center" colspan="3">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $assigned_documents = array_reverse($documents_list);  ?>
                                        <?php foreach ($documents_list as $document) { ?>
                                            <?php if ($document['archive'] != 1 && $document['status'] != 0) { ?>
                                                <tr>
                                                    <td class="col-lg-4">
                                                        <?php
                                                            echo $document['document_title'] . '&nbsp;';

                                                            if (isset($document['assigned_date']) && $document['assigned_date'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Assigned At: </b>" . reset_datetime(array('datetime' => $document['assigned_date'], '_this' => $this));
                                                            }

                                                            if (isset($document['signature_timestamp']) && $document['signature_timestamp'] != '0000-00-00 00:00:00') {
                                                                echo "<br><b>Consent At: </b>" . reset_datetime(array('datetime' => $document['signature_timestamp'], '_this' => $this));
                                                            }
                                                        ?>
                                                    </td>
                                                    <td class="col-lg-2">
                                                        <?php echo reset_datetime(array('datetime' => $document['assigned_by_date'], '_this' => $this)); ?>
                                                    </td>
                                                    <td class="col-lg-2">
                                                        <?php if(!empty($document['authorized_signature'])) { ?>
                                                            <img class="img-responsive text-center signature_status_bulb" title="Document Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/on.gif'); ?>">
                                                        <?php } else { ?>
                                                            <img class="img-responsive text-center signature_status_bulb" title="Document Not Signed" data-toggle="tooltip" data-placement="top" class="img-responsive" src="<?php echo site_url('assets/manage_admin/images/off.gif'); ?>">
                                                        <?php } ?>
                                                    </td>    
                                                    <td class="col-lg-1">
                                                        <a href="<?= base_url('hr_documents_management/perform_action_on_document_content'. '/' . $document['sid'] . '/submitted/assigned_document/print');?>" target="_blank" class="btn btn-success btn-sm btn-block">Print</a>
                                                    </td>

                                                    <td class="col-lg-1">
                                                        <a href="<?= base_url('hr_documents_management/perform_action_on_document_content'. '/' . $document['sid'] . '/submitted/assigned_document/download');?>" target="_blank" class="btn btn-success btn-sm btn-block">Download</a>
                                                    </td>
                                                        

                                                        
                                                    <td class="col-lg-2">
                                                        <?php $btn_show = empty($document['authorized_signature']) ?  'btn blue-button btn-sm btn-block' : 'btn btn-success btn-sm btn-block'; ?>
                                                        <?php $btn_text= empty($document['authorized_signature']) ?  'Sign Doc - Not Completed' : 'Sign Doc - Completed'; ?>
                                                        <a class="<?php echo $btn_show; ?>" href="<?php echo  base_url('view_assigned_authorized_document' . '/' . $document['sid']); ?>">
                                                            <?php echo $btn_text; ?>
                                                        </a>    
                                                    </td>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo $links; ?>
                        <?php } else { ?>
                            <h1 class="section-ttile text-center"> No Document Assigned! </h1>   
                        <?php } ?>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        if($('.js-uncompleted-docs tbody tr').length == 0){
            $('.js-uncompleted-docs').html('<h1 class="section-ttile text-center"> No Document Assigned! </h1>');
        }
    });
</script>
