<?php 
    $percentageCompleted = 0;
    //
    $trs = '';
    
    if(!empty($product['order_response'])) {
        if($product['order_response']['summary']) {
            $total = 0;
            $completed = 0;
            //
            foreach($product['order_response']['summary'] as $summary){
                //
                $total++;
                //
                if(strtolower($summary['status'])){
                    $completed++;
                }
                //
                $trs .= '<tr>';
                $trs .= '    <td class="text-left">'.( $summary['searchId'] ).'</td>';
                $trs .= '    <td class="text-left">'.( $summary['search'] ).'</td>';
                $trs .= '    <td class="text-center">'.( ucwords($summary['status']) ).'</td>';
                $trs .= '    <td class="text-center">'.( !empty($summary['result']) ? ucwords($summary['result']) : 'N/A' ).'</td>';
                $trs .= '    <td class="text-center">'.( $summary['flagged'] == 1? 'Yes' : 'No' ).'</td>';
                $trs .= '</tr>';
            }
            //
            $percentageCompleted = ceil($completed * 100 / $total);
        }
    }
?>

<div class="accurate-background-box">
    <div class="row">
        <div class="col-xs-4">
            <article class="accurate-background-box">
                <h2 class="post-title"><?php echo $product['product_name']; ?></h2>
                <figure>
                    <img src="<?php echo AWS_S3_BUCKET_URL.$product['product_image'];?>" alt="Assurehire image" />
                </figure>
            </article>
        </div>
        <div class="col-xs-8">
            <div class="accurate-background-box">
                <h2 class="post-title text-left">Current Saved Status
                    <span class="pull-right">
                        <a href="<?=$product['package_response']['orderStatus']['report_url']?>" target="_blank" class="btn btn-success jsViewReport">
                            View Report
                        </a>
                    </span>
                </h2>
                <table class="table table-bordered table-striped table-hover">
                    <caption></caption>
                    <tbody>
                        <tr>
                            <th scope="col" class="col-xs-4">Order Reference</th>
                            <td class="text-left"><?=$product['external_id']?></td>
                        </tr>
                        <tr>
                            <th scope="col" class="col-xs-4">Status</th>
                            <td class="text-left">
                                <?php 
                                if(!empty($product['order_response'])) {
                                    //
                                    echo $product['order_response']['status'];
                                } else{
                                    echo "Awaiting Candidate Response";
                                }
                                ?>    
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="col-xs-4">Completed Date</th>
                            <td class="text-left">
                                <?php 
                                    if(!empty($product['order_response'])) {
                                        //
                                        echo formatDateToDB(explode('.', $product['order_response']['completedDate'])[0], 'Y-m-d\TH:i:s', DATE);
                                    } else{
                                        echo "N/A";
                                    }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="col-xs-4">Percentage Complete</th>
                            <td class="text-left">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width:<?=$percentageCompleted;?>%">
                                        <?=$percentageCompleted;?>% Complete
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="col" class="col-xs-4">Notes</th>
                            <td class="text-left"><?=$product['package_response']['orderInfo']['specialInstruction']?></td>
                        </tr>
                        <?php if($session['employer_detail']['access_level_plus'] == 1) {?>
                        <tr>
                            <th scope="col" class="col-xs-4">Upload File</th>
                            <td class="text-left">
                                <div>
                                    <input type="file" name="files[]" class="hidden" />
                                </div>
                                <button class="btn btn-success jsUploadFile">Upload File</button>
                            </td>
                        </tr>
                        <?php } else {?>
                            <tr>
                                <th scope="col" class="col-xs-4">Uploaded File</th>
                                <td class="text-left">
                                    <?php if(!empty($product['s3_filename'])) { ?>
                                        <button data-file="<?=$product['s3_filename'];?>" class="btn btn-success jsViewUploadedFile">View File</button>
                                    <?php } else {?>
                                        N/A
                                    <?php }?>
                                </td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php if(!empty($product['order_response'])) { ?>
    <div class="accurate-background-box">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="post-title text-left">Report Summary</h2>
                <br />
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col" class="">Search Reference</th>
                                <th scope="col" class="">Search</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Result</th>
                                <th scope="col" class="text-center">Flagged</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?=$trs;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<?php $this->load->view('iframeLoader'); ?>

<script>
    $(function(){
        //
        var hasError = false;
        var fileOBJ = {};
        var xhr = null;
        //
        var options = {
            allowedTypes: ['pdf','jpg','jpeg','png'],
            onError: function(error){
                hasError = error;
            },
            onSuccess: function(file){
                hasError = false;
                fileOBJ = file;
            }
        };

        //
        $('.jsViewUploadedFile').click(function(event){
            //
            event.preventDefault();
            //
            var file = $(this).data('file');
            $('#modal-id').modal('show')
            $('#modal-id .modal-title').html(file.replace(/_/g, ' '))
            //
            loadIframe("<?=AWS_S3_BUCKET_URL;?>"+file, '#jsFileViewer')
        });

        //
        <?php if(!empty($product['s3_filename'])) { ?>
            options.placeholderImage = "<?=$product['s3_filename'];?>";
        <?php } ?>
        //
        <?php if($session['employer_detail']['access_level_plus'] == 1) {?>
        $('input[type="file"]').mFileUploader(options);
        <?php } ?>
        <?php if($session['employer_detail']['access_level_plus'] == 1) {?>
        //
        $('.jsUploadFile').click(function(event){
            //
            event.preventDefault();
            //            
            if(hasError){
                return alertify.alert("Error!", hasError, function() {});
            }
            //            
            if(Object.keys(fileOBJ).length === 0){
                return alertify.alert("Error!", 'Please select a file first.', function() {});
            }
            //
            $(this).text('Uploading file...');
            //
            UploadFile($(this), fileOBJ);
        });
        //
        function UploadFile(_this, fileOBJ){
            //
            var formData = new FormData();
            //
            var postfix = [];
            postfix.push( "<?=$employer['first_name'].' '.$employer['last_name'];?>");
            postfix.push( "<?=$employer['user_type'];?>");
            postfix.push( "<?=$product['external_id'];?>");
            //
            formData.append('file', fileOBJ);
            formData.append('user_sid', "<?=$employer['sid'];?>");
            formData.append('user_type', "<?=$employer['user_type'];?>");
            formData.append('creator_sid', "<?=$session['employer_detail']['sid'];?>");
            formData.append('postfix', postfix);
            formData.append('module_name', 'background');
            //
            xhr = $.ajax({
                method: "POST",
                url: "<?=base_url('upload_secure_file');?>",
                processData: false,
                contentType: false,
                data: formData
            }).done(function(resp){
                //
                if(!resp.Status){
                    return alertify.alert('Error!', resp.Response, function(){});
                }
                //
                SaveFileToBackground(_this, resp.File.Name)
            });
        }
        //
        function SaveFileToBackground(_this, name){
            $.post(
                "<?=base_url('background_check/save_image');?>", {
                    sid: "<?=$product['sid'];?>",
                    name: name
                }
            ).done(function(){
                return alertify.alert('Success!', 'You have successfully uploaded a file.', function(){
                    _this.text('Upload File');
                });
            });
        }
        <?php } ?>
    });
</script>


<div class="modal fade" id="modal-id">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <iframe id="jsFileViewer" frameborder="0" style="width: 100%; height: 500px;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
