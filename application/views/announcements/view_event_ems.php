<style>
    #js-message-body img{ max-width: 100%; }
</style>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="btn-panel">
                    <a href="<?php echo base_url('list_announcements'); ?>" class="btn btn-info"><i class="fa fa-angle-left"></i> Announcements</a>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <!-- <h1 class="section-ttile"><?php echo ucwords($event[0]["type"] . ' Announcement'); ?></h1> -->
                    <h1 class="section-ttile"><?php echo ucwords($event[0]["title"]); ?></h1>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="dashboard-conetnt-wrp">
                            <div class="announcement-detail">
                                <div class="post-options">
                                    <ul>
                                        <li><?=reset_datetime(array('datetime' => $event[0]["display_start_date"], '_this' => $this), true); ?></li>
                                        <!-- <li><?php //echo date_with_time($event[0]["display_start_date"]) ; ?></li> -->
                                        <li><?php echo ucwords($event[0]["type"] . ' Announcement'); ?></li>
                                    </ul>
                                    <span class="post-author">By <?php echo $event[0]["first_name"] . ' ' . $event[0]["last_name"]?></span>
                                </div>
                                <?php if ($validate_image_flag == true) { ?>
                                    <figure>
                                        <img class="img-responsive" src="<?= !empty($event[0]['section_image']) ? AWS_S3_BUCKET_URL . $event[0]['section_image'] : base_url('assets/images/no-img.jpg'); ?>"/>
                                    </figure>
                                <?php }?>
                                <div class="text full-width" id="js-message-body">
                                    <?php echo !empty($event[0]["message"]) ? $event[0]["message"] : 'N/A'; ?>
                                </div>
                                <?php if($event[0]["type"] == 'New Hire'){?>
                                    <h4 style="color: #000;">
                                        New Hire Bio
                                    </h4>
                                    <div class="text full-width">
                                        <?php echo !empty($event[0]["new_hire_bio"]) ? $event[0]["new_hire_bio"] : 'N/A'; ?>
                                    </div>
                                <?php }?>
                                <?php if ($validate_flag == true) { ?>
                                    <?php if($event[0]["section_video_source"] == 'youtube') { ?>
                                        <div class="text full-width">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $event[0]["section_video"]; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    <?php } elseif($event[0]["section_video_source"] == 'vimeo') { ?>
                                        <div class="text full-width">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe id="vimeo_player" src="https://player.vimeo.com/video/<?php echo $event[0]["section_video"]; ?>?title=0&byline=0&portrait=0&autoplay=0&loop=0&muted=0&controls=1" frameborder="0"webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>  
                                            </div>
                                        </div>    
                                    <?php } else { ?>
                                        <div class="text full-width">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <video id="my-video" class="video-js" controls preload="auto" poster="<?php echo base_url().'assets/images/affiliates/affiliate-0.png'; ?>" data-setup="{}">
                                                    <source src="<?php echo base_url().'assets/uploaded_videos/' .$event[0]["section_video"]; ?>" type='video/mp4'>
                                                    <p class="vjs-no-js">
                                                      To view this video please enable JavaScript, and consider upgrading to a web browser that
                                                      <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                                                    </p>
                                                </video>
                                            </div>
                                        </div>    
                                    <?php } ?>
                                <?php } ?>

                                <?php if(!empty($related_documented)){?>
                                    <div class="text full-width">
                                        <h2>Documents</h2>
                                    </div>
                                    <!-- table -->
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr style="background-color: #0000ff; color: #fff;">
                                                <th>Document</th>
                                                <th>Document Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach($related_documented as $document){ ?>
                                                <tr data-id="<?=$document['sid'];?>">
                                                    <td><?=$document['document_name'];?></td>
                                                    <td><?=$document['document_type'];?></td>
                                                    <td>
                                                        <button class="btn btn-info btn-5 js-view" style="border-radius: 5px;">View</button>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4"> -->
                <?php //$this->load->view('manage_employer/employee_hub_right_menu'); ?>
            <!-- </div> -->
        </div>
    </div>
</div>
<script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>
<script type="text/javascript">
    
</script>

<style>
    .loader{ position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; background: rgba(255,255,255,.8); }
    .loader i{ text-align: center; top: 50%; left: 50%; font-size: 40px; position: relative; }
</style>

<script>
    
    $(function(){
        var documents = <?=json_encode($related_documented);?>;

        $('.js-view').click(function(e){
            e.preventDefault();
            // Get document
            var doc = getDocument($(this).closest('tr').data('id'));
            //
            if(Object.keys(doc).length == 0){
                alertify.alert('ERROR!', 'Document not found.');
                return;
            }
            //
            loadModal(doc);
        });

        //
        function loadModal(doc){
            var
            modal = '',
            d = '',
            iframeURL = '',
            spinner = '',
            printBtnURL = '',
            downloadBtnURL = '';

            // For video
            if(
                doc['document_type'] == "mp4" || doc['document_type'] == "m4a" || 
                doc['document_type'] == "m4v" || doc['document_type'] == "f4v" || 
                doc['document_type'] == "f4a" || doc['document_type'] == "m4b" || 
                doc['document_type'] == "m4r" || doc['document_type'] == "f4b" || 
                doc['document_type'] == "mov" || doc['document_type'] == 'mp3'){
                d += '<video id="my-video" class="video-js" controls preload="auto" poster="<?=base_url('assets/images/affiliates/affiliate-0.png');?>" data-setup="{}">';
                d += '    <source src="<?=base_url('assets/uploaded_videos').'/';?>'+( doc['document_code'] )+'>';
                d += '    <p class="vjs-no-js">';
                d += '        To view this video please enable JavaScript, and consider upgrading to a web browser that';
                d += '        <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>';
                d += '    </p>';
                d += '</video>';
            } else if(
                doc['document_type'] == "png" || doc['document_type'] == "jpg" || 
                doc['document_type'] == "jpeg"){
                d +=' <figure>';
                d +='     <img class="img-responsive" src="<?=AWS_S3_BUCKET_URL;?>'+( doc['document_code'] )+'"/>';
                d +=' </figure>';
            } else if(
                doc['document_type'] == "doc" || doc['document_type'] == "docx" || 
                doc['document_type'] == "xlx" || doc['document_type'] == "xlxs"){
                downloadBtnURL = "<?=base_url('hr_documents_management/download_upload_document');?>/"+doc['document_code'];
                spinner = '<div class="loader"><i class="fa fa-spinner fa-spin"></i></div>';
                iframeURL = "https://view.officeapps.live.com/op/embed.aspx?src="+( encodeURI("<?=AWS_S3_BUCKET_URL;?>"+doc['document_code']) )+"";
                printBtnURL = iframeURL;
                d += '<iframe id="preview_iframe" src="'+( iframeURL )+'" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
            } else {
                downloadBtnURL = "<?=base_url('hr_documents_management/download_upload_document');?>/"+doc['document_code'];
                spinner = '<div class="loader"><i class="fa fa-spinner fa-spin"></i></div>';
                iframeURL = "https://docs.google.com/gview?url="+( encodeURI("<?=AWS_S3_BUCKET_URL;?>"+doc['document_code']) )+"&embedded=true";
                printBtnURL = iframeURL;
                d += '<iframe id="preview_iframe" src="'+( iframeURL )+'" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
            }

            modal+= '<div class="modal fade" id="modal-id">';
            modal+= '    <div class="modal-dialog modal-lg">';
            modal+= '        <div class="modal-content">';
            modal+= '            <div class="modal-header">';
            modal+= '                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            modal+= '                <h4 class="modal-title">'+( doc['document_name'] )+'</h4>';
            modal+= '            </div>';
            modal+= '            <div class="modal-body" style="min-height: 400px;">';
            modal+= spinner                
            modal+= d                
            modal+= '            </div>';
            modal+= '            <div class="modal-footer">';
            if(printBtnURL != '')
            modal+= '                <a href="'+( printBtnURL )+'" target="_blank" class="btn btn-info js-btn" style="background-color: #0000ff; color: #ffffff;">Print</a>';
            if(downloadBtnURL != '')
            modal+= '                <a href="'+( downloadBtnURL )+'" class="btn btn-info js-btn" style="background-color: #0000ff; color: #ffffff;">Download</a>';
            modal+= '                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
            modal+= '            </div>';
            modal+= '        </div>';
            modal+= '    </div>';
            modal+= '</div>';

            //
            $('#modal-id').remove();
            $('body').append(modal);
            $('#modal-id').modal();
            //
            if(iframeURL != '') loadIframe(iframeURL, '#preview_iframe');
        }

        //
        function loadIframe(url, target){
            try {
                if($(target).contents()[0].body.text == ''){
                    $(target).prop('src', url);
                    setTimeout(function(){
                        loadIframe(url, target);
                    }, 3000);
                }
            } catch(e) {
                $('.loader').remove();
                console.log('Iframe is loaded.');
            }
        }

        //
        function getDocument(sid){
            if(documents.length === 0) return {};
            //
            var i = 0, il = documents.length;
            //
            for (i; i < il; i++) {
                if(documents[i]['sid'] == sid) return documents[i];
            }
            //
            return {};
        }
    })
</script>