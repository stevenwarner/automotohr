<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-heading-text text-medium">
            <strong>Upload Files</strong>
        </h1>
    </div>
    <div class="panel-body">
        <input type="hidden" id="jsItemId" value="" />
        <input type="hidden" id="jsReportId" value="" />
        <input type="hidden" id="jsIncidentId" value="" />
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="document_title">Title <strong class="text-danger">*</strong></label>
                    <input type="text" class="form-control" id="jsItemDocumentTitle" name="item_document_title" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                <input type="file" class="hidden" id="jsItemDocuments" name="item_documents" />
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer text-right">
        <button class="btn btn-orange jsAddItemDocument" type="button">
            <i class="fa fa-plus"></i>
            Add File
        </button>
    </div>
 </div>