<div class="row">
    <div class="col-xs-12">
        <div style="padding: 30px 50px; background-color: lightgrey; height: 600px; overflow-x: hidden; overflow-y: scroll;" class="document_body_container">
            <div style="padding: 20px; background-color: white; min-height: 900px;" class="document_body">
               <iframe src="<?=AWS_S3_BUCKET_URL.($uploaded_document_s3_name);?>" frameborder="0" style="width: 100%; height: 2500px;"></iframe>
            </div>
        </div>
    </div>
</div>