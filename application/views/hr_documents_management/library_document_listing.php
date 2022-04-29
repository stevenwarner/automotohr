<style>
    .signature_status_bulb {
        width: 22px; 
        height: 22px;  
        display: block; 
        margin-left: auto; 
        margin-right: auto;
    }

    .alertify-button-cancel {
        color: #518401 !important;
    }

    
</style>
<?php $archive_section = 'no'; ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-xs-2 col-sm-2">
                            <br />
                            <a class="btn btn-info btn-block mb-2 csRadius5" href="<?php echo base_url('dashboard'); ?>">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Dashboard</a>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header full-width">
                            <h1 class="section-ttile">Documents Library</h1>
                            <strong> Information:</strong> If you are unable to view the documents library, kindly reload the page.
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12" style="min-height: 500px;"> 
                        <?php if (!empty($documents_list)) { ?>
                            <div class="table-responsive full-width table-outer">
                                <table class="table table-striped table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="col-lg-4">Document Name</th>
                                            <th class="col-lg-3">Employee / Applicant</th>
                                            <th class="col-lg-2 text-center">Created Date</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <?php $assigned_documents = array_reverse($documents_list);  ?>
                                        <?php foreach ($documents_list as $document) { ?>
                                               
                                                <tr class="">
                                                    <td class="col-lg-3">
                                                        <?php
                                                            echo $document['document_title'] . '&nbsp; <br />';
                                                            echo "(".(!empty($document['offer_letter_type']) ? "Offer Letter - ".( ucwords($document['offer_letter_type']) )."" : "Document - ".( ucwords($document['document_type']) )."") . ')&nbsp;';
                                                        ?>
                                                    </td>
                                                    <td class="col-lg-4">
                                                        <?php 
                                                            $user_type = '';
                                                            $user_name = '';
                                                            if ($document['user_type'] == 'applicant') {
                                                                $user_type = 'Applicant';
                                                                $user_name = get_applicant_name($document['user_sid']);
                                                            } else {
                                                                $user_type = 'Employee';        
                                                                $user_name = getUserNameBySID($document['user_sid']);
                                                            }
                                                            echo $user_name ."<br /> <b>(".$user_type.")</b>";
                                                        ?>
                                                    </td>
                                                    <td class="col-lg-2  text-center">
                                                        <?php echo reset_datetime(array('datetime' => $document['date_created'], '_this' => $this)); ?>
                                                    </td>
                                                  
                                                    </tr>   
                                          
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

    $('.archive_document').on('click', function () {
        var document_sid = $(this).attr('document_sid');
        alertify.confirm('Confirm', "Are you sure you want to archive this authorized  document?",
            function () {
                setTimeout(function() { 
                    archive_type(document_sid);
                }, 0);
            },
            function () {
                alertify.alert('Note', 'Archive process terminated.'); 
            }).set('labels', {ok: 'Yes', cancel: 'No'});
        
    });

    function archive_type (document_sid) {
        
        alertify.confirm('Action', "Do you want to archive this authorized document for all managers?",
            function () {
                var user_sid = '<?php echo $employer_sid; ?>';
                modify_assign_document(document_sid, 'archive', 'multiple');
            },
            function () {
                modify_assign_document(document_sid, 'archive', 'single');
            }).set('labels', {ok: 'Yes', cancel: 'No, just for me'});
    }

    $('.activate_document').on('click', function () {
        var document_sid = $(this).attr('document_sid');
        alertify.confirm('Confirm', "Are you sure you want to activate this authorized document?",
            function () {
                setTimeout(function() { 
                    activate_type(document_sid);
                }, 0);
            },
            function () {
                alertify.alert('Note', 'Activate process terminated.'); 
            }).set('labels', {ok: 'Yes', cancel: 'No'});
        
    });

    function activate_type (document_sid) {
        
        alertify.confirm('Action', "Do you want to activate this authorized document for all managers?",
            function () {
                var user_sid = '<?php echo $employer_sid; ?>';
                modify_assign_document(document_sid, 'active', 'multiple');
            },
            function () {
                modify_assign_document(document_sid, 'active', 'single');
            }).set('labels', {ok: 'Yes', cancel: 'No, just for me'});
    }

    function modify_assign_document (document_sid, action_name, action_type) {
        var user_sid = '<?php echo $employer_sid; ?>';
        var archive_url = '<?= base_url('hr_documents_management/handler') ?>';

        var form_data = new FormData();
        form_data.append('action', 'modify_authorized_document');
        form_data.append('document_sid', document_sid);
        form_data.append('user_sid', user_sid);
        form_data.append('action_name', action_name);
        form_data.append('action_type', action_type);

        $.ajax({
            url: archive_url,
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function (resp) {
                alertify.alert('SUCCESS!', resp.Response, function(){
                    window.location.reload();
                });            },
            error: function () {
            }
        });
    }
</script>
<!-- Archive For Me -->
<style>
.btn-success{
        background-color: #3554dc  !important;
    }
    </style>