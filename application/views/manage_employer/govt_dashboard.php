<?= validation_errors(); ?>

<style>
    .js-form-div{ margin-left: -3000px; }
</style>

<script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

<?php $this->load->view('templates/_parts/admin_flash_message'); ?>
<div class="container-fluid" style="min-height: 500px;">
    <div class="rows">
        <div class="col-12">
            <div class="col-sm-12">
                <a class="pull-right btn btn-primary" href="<?= base_url("govt_user/logout") ?>" style="margin-bottom: 30px;">Logout</a>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                 <?php if(!empty($users)) {?> 
                    <tr>
                        <th><input type="checkbox" class="custom-control-input" id="checkall" style="width: 25px; height: 25px;" /></th>
                        <th>Employee Name</th>
                        <th>
                           <div class="btn-group pull-right">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Download <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" title="Select an option (Download Selected, Download All) to download I9 forms.">
                                    <li><a href="#" class="js-download-selected" title="It will download the resumes for the selected employees.">Download Selected</a></li>
                                    <li><a href="#" class="js-download-all" title="It will download the resumes of all the employees">Download All (<span><?=count($users)?></span>)</a></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                  <?php } ?>
                </thead>
                <tbody>
                    <?php if(!empty($users)) {?>      
                        <?php foreach ($users as $user) { ;?> 
                        <?php $user['full_name'] = ucwords(strtolower($user['full_name']));?>
                            <tr data-id="<?=$user['user_sid'];?>" data-type="<?=$user['form_type'];?>">
                                <td class="col-sm-1">
                                    <input type="checkbox" name="i9forms[]" formid="<?= $user['user_sid'] ?>" class="i9form-control-input" id="customCheck3" value="<?= base_url('govt_user/view_i9form/employee/' . $user['user_sid']) . '/download' ?>" style="width: 25px; height: 25px;" />
                                </td>
                                <td><?=$user['full_name'];?></td>
                                <td class="col-sm-2">
                               
                                    <a href="javascript:void(0)" class="btn btn-info btn-view" file_name="<?php echo $user['s3_filename'] ;?>" data-path="<?=$user['form_type'] == 'assigned' ? '' : ''.AWS_S3_BUCKET_URL.$user['s3_filename'].'';?>" title="Display I9 form"><i class="fa fa-info"></i></a> 
                                    <a href="javascript:void(0)" class="btn btn-success js-download-specific" title="It will download the resume of <?=$user['full_name'];?>"><i class="fa fa-download"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else {?>
                    <tr >
                     <td colspan="3" class="text-center cs-td-padding">No employees are found!</td>
                    </tr>
                    <?php };?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div>
<?php if(!empty($users)) {?>  
    <?php foreach ($users as $user) { ?> 
        <div id="i9form-wrapper<?= $user['user_sid'] ?>" class="col-sm-12 js-form-div" style="display: none;" data-id="<?=$user['user_sid']?>">
        <?php 
            if($user['form_type'] == 'assigned'){
                $user['pre_form'] = $this->govt_user_model->fetch_i9form($user['user_type'], $user['user_sid']);
                $this->load->view('manage_employer/govt_users/i9form_html',$user); 
            }else{ ?>
            <iframe src="<?=AWS_S3_BUCKET_URL.$user['s3_filename'];?>" frameborder="0" style="width: 100%;"></iframe>
           <?php }
        ?>
        </div>
    <?php } ?>
    <?php } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="i9formmodal" tabindex="-1">
    <div class="modal-dialog" role="document" style="width: 90%;">
        <div class="modal-content">
             <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">I9 Form</h4>
             </div>
             <div class="modal-body" id="i9formcontent" style="background:#fff">
             </div>
             <div id="document_modal_footer" class="modal-footer">

             </div>
        </div>
        
    </div>
     
</div>
 
<!-- Loader -->
<div id="my_loader" class="text-center my_loader js-loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">
            Please wait while we loading employees
        </div>
    </div>
</div>

<script>
    var pdfHolder = {};
    $(function(){
        var pdfHtmls = [],
        baseURI = "<?=base_url('assets/tmp');?>/",
        paginate = {
            totalRecords: <?=sizeof($users);?>
        };

        $('.js-download-all').click(function (e) {
            e.preventDefault();
            pdfHtmls = [];
            $('.i9form-control-input[type="checkbox"]').each(function () { 
                pdfHtmls.push({ 
                    userId: parseInt($(this).attr('formid')), 
                    pdf: $(this).closest('tr').data('type') == 'assigned' ? getPDF(parseInt($(this).attr('formid'))) : $(this).closest('tr').find('a.btn-view').data('path'),
                    type: $(this).closest('tr').data('type')
                });
            });
            downloadForms('all');
        });

        $('.js-download-specific').click(function (e) {
            e.preventDefault();
            pdfHtmls = [];
            //
            pdfHtmls.push({ 
                userId: parseInt($(this).closest('tr').data('id')), 
                pdf: $(this).closest('tr').data('type') == 'assigned' ? getPDF(parseInt($(this).closest('tr').data('id'))) : $(this).closest('tr').find('a.btn-view').data('path'),
                type: $(this).closest('tr').data('type')
            });
            downloadForms('single');
        });

        $('.js-download-selected').click(function (e) {
            e.preventDefault();
            pdfHtmls = [];
            $('.i9form-control-input[type="checkbox"]:checked').each(function () { 
                pdfHtmls.push({ 
                    userId: parseInt($(this).attr('formid')), 
                    pdf: $(this).closest('tr').data('type') == 'assigned' ? getPDF(parseInt($(this).attr('formid'))) : $(this).closest('tr').find('a.btn-view').data('path'),
                    type: $(this).closest('tr').data('type')
                });
            });
            if(pdfHtmls.length == 0){
                alertify.alert('ERROR!', 'Please, select atleast one employee.');
                return;
            }
            downloadForms('selected');
        });

        //
        function downloadForms(isType){
            //
            loader();
            $.post("<?=base_url('govt_user/download_forms');?>", {
                type: isType,
                ids: pdfHtmls
            }, function(resp) {
                loader('hide');
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response);
                    return false;
                }
                // redirect on download link
                window.location.href = baseURI+( resp.Link );
            });
        }

        function loader(showIt){
            if(showIt !== undefined) $('.js-loader').hide();
            else $('.js-loader').show();
        }

        function generateI9PDF(referenceNode){
            referenceNode.show();
            var draw = kendo.drawing;
            var ret = draw.drawDOM(referenceNode, {
                avoidLinks: false,
                paperSize: "auto",
                multiPage: true,
                margin: { bottom: "1cm" },
                scale: 0.8
            }).then(function(root){
                return draw.exportPDF(root);
            }).done(function(data){
                pdfHolder[referenceNode.data('id')] =  data ;
                referenceNode.hide();
                loader('hide');
            });
        }

        function getPDF(userId){
            return pdfHolder[userId];
        }

        //
        $('.js-form-div').map(function() { loader(); generateI9PDF($(this)); });
        
        $(document).on('click', ".btn-view", function () {
            $('#i9formcontent').html('<iframe src="'+( $(this).closest('tr').data('type') == 'assigned' ? getPDF($(this).closest('tr').data('id')) : $(this).data('path') )+'" style="width: 100%; height: 600px;"></iframe>');
            var file_name = $(this).attr("file_name");
            var document_download_url="http://localhost/ahr/hr_documents_management/download_upload_document/"+file_name;
            var footer_print_url ="https://docs.google.com/gview?url=https://automotohrattachments.s3.amazonaws.com/"+file_name;
            footer_content = '<a target="_blank" class="btn btn-success" href="'+document_download_url+'">Download</a>';
            footer_print_btn = '<a target="_blank" class="btn btn-success" href="'+footer_print_url+'" >Print</a>';
            $('#document_modal_footer').html(footer_content);
            $('#document_modal_footer').append(footer_print_btn);
            $('#i9formmodal').modal('show');
           
        });
        loader('hide');
    })

    $(document).on('change', '#checkall', function () {
        if (this.checked) {
            $(".i9form-control-input").prop('checked', true);
        } else {
            $(".i9form-control-input").prop('checked', false);
        }
    });


    $(document).on('click', ".btn-print", function () {
        printDiv('i9form-wrapper'+$(this).closest('tr').data('id')+'');
    });

    function printDiv(divName){
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
<style>
.cs-td-padding{
    padding-top:15px !important;
    padding-bottom:15px !important;
}
</style>