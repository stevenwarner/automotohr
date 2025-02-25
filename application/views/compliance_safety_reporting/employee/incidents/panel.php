 <!-- Courses in Progress Start -->
 <div class="panel panel-default">
     <div class="panel-heading">
         <div class="row">
             <div class="col-sm-6">
                 <h1 class="panel-heading-text text-medium">
                     <strong><?= $panel["title"]; ?></strong>
                 </h1>
                 <p class="text-small text-danger">
                     <i class="fa fa-info-circle" aria-hidden="true"></i>
                     &nbsp;<?= $panel["sub_title"]; ?>
                 </p>
             </div>
         </div>
     </div>
     <div class="panel-body">
         <div class="row">
             <?php if ($panel["data"]) : ?>
                 <?php foreach ($panel["data"] as $item): ?>
                     <?php $this->load->view("compliance_safety_reporting/employee/incidents/display_box", [
                            "display_box_data" => $item
                        ]); ?>
                 <?php endforeach; ?>
             <?php else: ?>
                 <div class="col-sm-12">
                     <div class="alert alert-info text-center">
                         No report incidents found.
                     </div>
                 </div>
             <?php endif; ?>
         </div>
     </div>
 </div>
 <!-- Courses in Progress End -->