 <!-- Documents -->
 <div class="panel panel-default">
     <div class="panel-heading">
         <h1 class="panel-heading-text text-medium">
             <strong>Upload Documents</strong>
         </h1>
     </div>
     <div class="panel-body">
         <div class="row">
             <div class="col-lg-12">
                 <div class="form-group">
                     <label for="document_title">Title <strong class="text-danger">*</strong></label>
                     <input type="text" class="form-control" id="document_title" name="document_title" />
                 </div>
             </div>
         </div>
         <div class="row">
             <div class="col-lg-12">
                 <div class="form-group">
                     <input type="file" class="form-control hidden" id="report_documents" name="report_documents" />
                 </div>
             </div>
         </div>
     </div>
     <div class="panel-footer text-right">
         <button class="btn btn-orange jsAddDocument" type="button">
             <i class="fa fa-plus"></i>
             Add Document
         </button>
     </div>
 </div>
 <!-- Manage Documents -->
 <div class="panel panel-default">
     <div class="panel-heading">
         <h1 class="panel-heading-text text-medium">
             <strong>Manage Documents</strong>
         </h1>
     </div>
     <div class="panel-body jsDocumentsArea">
         <?php if ($report["documents"]) : ?>
             <div class="row jsFirst">
                 <?php foreach ($report["documents"] as $document) : ?>
                     <div class="col-sm-3">
                         <div class="widget-box">
                             <div class="attachment-box full-width jsFileBox" data-id="<?= $document["sid"]; ?>">
                                 <h4 style="padding: 5px;" class="text-white">
                                     <?= $document["title"]; ?>
                                 </h4>
                                 <p style=" margin-left: 5px;" class="text-white">
                                     <small>

                                         <?= formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE); ?>
                                         <br>

                                         <?= remakeEmployeeName($document); ?>
                                     </small>
                                 </p>
                                 <div class="status-panel">
                                     <div class="row">
                                         <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                             <button class="btn btn-info jsViewFile">
                                                 <i class="fa fa-eye"></i>
                                             </button>
                                             <a target="_blank" href="<?= base_url("compliance_safety_reporting/file/download/" . $document["sid"]); ?>" class="btn btn-info btn-info">
                                                 <i class="fa fa-download"></i>
                                             </a>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 <?php endforeach; ?>
             </div>
         <?php else : ?>
             <div class=" alert alert-info text-center">
                 No documents found.
             </div>
         <?php endif; ?>
     </div>
 </div>