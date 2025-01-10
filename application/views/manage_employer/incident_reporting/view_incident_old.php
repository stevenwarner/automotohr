<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area margin-top">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('incident_reporting_system/list_incidents')?>" class="dashboard-link-btn"><i class="fa fa-angle-left"> </i> Back</a>
                        <?php echo $title; ?>
                    </span>
                </div>
                <?php foreach($incident as $inc) { ?>
                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                <b><?php echo $inc['question'];?></b>
                                <tbody>
                                    <tr>
                                        <td><?php
                                            $ans = @unserialize($inc['answer']);
                                            if ($ans !== false) {
                                                echo implode(',', $ans);
                                            } else {
                                                echo ucfirst($inc['answer']);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
                <div class="table-responsive table-outer">
                    <div class="table-wrp data-table">
                        <table class="table table-bordered table-hover table-stripped">
                            <b>Report Type</b>
                            <tbody>
                            <tr>
                                <td><?= ucfirst($incident[0]['report_type']); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-responsive table-outer">
                    <div class="table-wrp data-table">
                        <table class="table table-bordered table-hover table-stripped">
                            <b>Related Documents</b>
                            <tbody>
                                <?php if(sizeof($files)>0){
                                    foreach($files as $file){?>
                                    <tr>
                                        <td><a href="<?php echo AWS_S3_BUCKET_URL . $file['file_code']?>" download="docs"><?php echo $file['file_name']; ?></a></td>
                                    </tr>
                                <?php }
                                } else{?>
                                    <tr>
                                        <td>No Documents Found</td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php if ($incident[0]['report_type'] == 'confidential') { ?>
                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped">
                                <b>Upload Docs :</b>
                                <tbody>

                                <tr>
                                    <td>
                                        <div class="form-wrp">
                                            <div class="form-group autoheight">
                                                <div class="upload-file form-control">
                                                    <span class="selected-file" id="name_docs">No file selected</span>
                                                    <input name="docs" id="docs" onchange="check_file('docs')" type="file">
                                                    <a href="javascript:;">Choose File</a>
                                                </div>
                                                <div id="file-upload-div" class="file-upload-box"></div>
                                                <div class="attached-files" id="uploaded-files" style="display: none;"></div>
                                            </div>
                                        </div>

                                        <div class="custom_loader">
                                            <div id="loader" class="loader" style="display: none">
                                                <i style="font-size: 25px; color: #81b431;"
                                                   class="fa fa-cog fa-spin"></i>
                                                <span>Uploading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="video-link" style="font-style: italic;"><b>Note.</b> Upload Multiple Documents One After Other </div>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php }?>

                <div class="table-responsive table-outer">
                    <div class="table-wrp data-table">
                        <b>Related Response</b>
                        <div class="respond">

                            <?php if(sizeof($comments)>0){
                                foreach($comments as $comment){
                                    $name = empty($comment['user2']) ? ucfirst($comment['user1']) : ucfirst($comment['user2']);
                                    $pp = empty($comment['user2']) ? $comment['pp1'] : $comment['pp2'];
                                    $url = empty($pp) ? base_url() . "assets/images/attachment-img.png" : AWS_S3_BUCKET_URL . $pp;


                                    ?>
                                    <article <?php echo empty($comment['user2']) ? '' : 'class="reply"';?>>
                                        <figure><img class="img-responsive" src="<?= $url ?>"></figure>
                                        <div class="text">
                                            <div class="message-header">
                                                <div class="message-title"><h2><?php echo $name;?></h2></div>
                                                <ul class="message-option">
                                                    <li>
                                                        <time><?php echo date('d M, Y',strtotime($comment['date_time']));?></time>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!--                                            <span class="span">This is CV</span>-->
                                            <p><?php echo strip_tags($comment['comment']);?></p>
                                        </div>
                                    </article>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td class="text-center"><span class="no-data">No Response Found</span></td>
                                </tr>
                            <?php }?>
                        </div>
                    </div>
                </div>

                <?php if(sizeof($comments)>0){?>
                    <div class="hr-box">
                        <div class="hr-box-header">
                            <strong>Reply To Response </strong>
                        </div>
                        <div class="hr-innerpadding">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="universal-form-style-v2">
                                        <form id="form_new_note" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="form-group autoheight">
                                                        <label for="note_txt">Reply</label>
        <!--                                                <script type="text/javascript" src="--><?php //echo site_url('assets/ckeditor/ckeditor.js'); ?><!--"></script>-->
                                                            <textarea class="form-control response" name="response" rows="8" cols="60" required></textarea>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                        <div class="form-group autoheight">
                                                            <button type="submit" class="btn btn-success" name="submit" value="submit">Reply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $('#form_new_note').submit(function(){
            var response = $('.response').val().trim();
            if(response.length === 0){
                alertify.error('Please provide your response');
                return false;
            }
        });
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
            $('.upload-file').hide();
            $('#uploaded-files').hide();
            $('#file-upload-div').append('<div class="form-group autoheight"><div class="pull-left"> <span class="selected-file" id="name_docs">'+fileName+'</span> </div> <div class="pull-right"> <input class="submit-btn btn btn-success" type="button" value="Upload" name="upload" id="upload" onclick="DoUpload()"> <input class="submit-btn btn btn-success" type="button" value="Cancel" name="cancel" onclick="CancelUpload();"> </div> </div> ');
        } else {
            $('#name_' + val).html('No file selected');
        }
    }

    function CancelUpload(){
        $('.upload-file').show();
        if($('#uploaded-files').html() != ''){
            $('#uploaded-files').show();
        }
        $('#file-upload-div').html("");
        $('#name_docs').html("No file selected");
    }

    function DoUpload(){
        var file_data = $('#docs').prop('files')[0];
        var form_data = new FormData();
        form_data.append('docs', file_data);
        form_data.append('inc_id', <?= $id ?>);
        $('#loader').show();
        $('#upload').addClass('disabled-btn');
        $('#upload').prop('disabled', true);
        $.ajax({
            url: '<?= base_url('incident_reporting_system/ajax_handler') ?>',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            data: form_data,
            success: function(data){
                $('#loader').hide();
                $('#upload').removeClass('disabled-btn');
                $('#upload').prop('disabled', false);
                alertify.success('New document has been uploaded');
                $('.upload-file').show();$('#uploaded-files').show();
                $('#uploaded-files').append('<div class="row"><div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"> <div id="uploaded-files-name"><b>Name:</b> '+file_data['name']+'</div> </div> <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 text-right"> <span><b>Status:</b> Uploaded</span> </div> </div>');
                $('#file-upload-div').html("");
                $('#name_docs').html("No file selected");
                if(data=="error"){
                    alert('Doc error');
                }
            },
            error: function(){
            }
        });
    }
</script>
<?php } else { ?>
    <?php 
        // $this->load->view('manage_employer/incident_reporting/view_incident'); 
        $this->load->view('manage_employer/incident_reporting/view_incident_new'); 
    ?>
<?php } ?>