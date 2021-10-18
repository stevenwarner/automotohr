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
                        <tr>
                            <th scope="col" class="col-xs-4">Upload File</th>
                            <td class="text-left">
                                <div>
                                    <input type="file" name="files[]" class="hidden" />
                                </div>
                                <button class="btn btn-success jsUploadFile">Upload File</button>
                            </td>
                        </tr>
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

<script>
    $(function(){
        //
        var hasError = false;
        var fileOBJ = {};
        var xhr = null;
        //
        $('input[type="file"]').mFileUploader({
            allowedTypes: ['pdf','jpg','jpeg','png'],
            onError: function(error){
                hasError = error;
            },
            onSuccess: function(file){
                hasError = false;
                fileOBJ = file;
            }
        });
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
            postfix.push( "<?=$employer['user_type'];?>");
            postfix.push( "<?=$employer['first_name'].' '.$employer['last_name'];?>");
            postfix.push( "<?=$product['external_id'];?>");
            //
            formData.append('file', fileOBJ);
            formData.append('postfix', postfix);
            //
            xhr = $.ajax({
                method: "POST",
                url: "<?=base_url('upload_secure_file');?>",
                processData: false,
                contentType: false,
                data: formData
            }).done(function(resp){
                //
                console.log(resp);
                _this.text('Upload File');
            });
        }
    });
</script>