
 <!-- Manage Documents -->
 <div class="panel panel-default">
     <div class="panel-heading">
         <h1 class="panel-heading-text text-medium">
             <strong>Manage Incidents</strong>
         </h1>
     </div>
     <div class="panel-body">
         <?php if ($report["incidents"]) : ?>
             <div class="table-responsive">
                 <table class="table table-striped">
                     <thead>
                         <tr>
                             <th class="bg-black" scope="col">Incident</th>
                             <th class="bg-black" scope="col">Last Modified</th>
                             <th class="bg-black" scope="col">Actions</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php foreach ($report["incidents"] as $item) : ?>
                             <tr data-id="<?= $item["sid"]; ?>">
                                 <td style="vertical-align: middle;">
                                     <?= $item["compliance_incident_type_name"]; ?>
                                 </td>
                                 <td style="vertical-align: middle;">
                                     <?= remakeEmployeeName($item); ?>
                                     <br>
                                     <?= formatDateToDB($item['updated_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                 </td>
                                 <td style="vertical-align: middle;">
                                     <a target="_blank" href="<?= base_url("csp/report/{$report["sid"]}/incident/edit/" . $item["sid"]); ?>" class="btn btn-orange">
                                         <i class="fa fa-eye"></i>
                                     </a>
                                 </td>
                             </tr>
                         <?php endforeach; ?>
                     </tbody>
                 </table>
             </div>
         <?php else : ?>
             <div class=" alert alert-info text-center">
                 No incidents found.
             </div>
         <?php endif; ?>
     </div>
 </div>