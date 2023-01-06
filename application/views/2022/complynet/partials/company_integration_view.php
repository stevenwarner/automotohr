<hr>
<div class="row">
    <div class="col-sm-12 text-right">
        <button class="btn btn-success jsShowAllJobRoles">Show ComplyNet Job Roles</button>
        <button class="btn btn-success jsShowAllDepartments">Show ComplyNet Departments</button>
        <button class="btn btn-success jsRefreshCompany">Refresh</button>
        <button class="btn btn-success jsSyncCompany">Sync</button>
    </div>
</div>
<br>
<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong> Details</strong>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <tr>
                    <th scope="col" class="col-sm-3">ComplyNet Company Id</th>
                    <td><?=$company['complynet_company_sid'];?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">ComplyNet Location Id</th>
                    <td><?=$company['complynet_location_sid'];?></td>
                </tr>
                <tr>
                    <th scope="col" class="col-sm-3">Integrated At</th>
                    <td><?=formatDateToDB($company['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
       <strong> Departments</strong>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Department Id</th>
                        <th scope="col">Department Name</th>
                        <th scope="col">ComplyNet Department Id</th>
                        <th scope="col">ComplyNet Department Name</th>
                        <th scope="col">DateTime</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($departments as $department){
                    ?>
                    <tr>
                        <td><?=$department['department_sid'];?></td>
                        <td><?=$department['department_name'];?></td>
                        <td><?=$department['complynet_department_sid'];?></td>
                        <td><?=$department['complynet_department_name'];?></td>
                        <td><?=formatDateToDB($department['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--  -->
<div class="panel panel-default">
    <div class="panel-heading">
       <strong> Employees</strong>
    </div>
   

    <ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#oncomplynet"><b>On ComplyNet</b></a></li>
  <li><a data-toggle="tab" href="#offcomplynet"><b>Off ComplyNet</b></a></li>
</ul>

<div class="tab-content">
  <div id="oncomplynet" class="tab-pane fade in active">
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Employee Id</th>
                        <th scope="col">Email</th>
                        <th scope="col">ComplyNet Id</th>
                        <th scope="col">DateTime</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($employees as $employee){
                    ?>
                    <tr>
                        <td><?=$employee['employee_sid'];?></td>
                        <td>
                        <?php 
                        $empData = json_decode($employee['complynet_json']);
                        echo $empData[0]->FirstName.' '.$empData[0]->LastName.'<br>';
                        echo $employee['email'];
                        ?>
                        </td>
                        <td><?=$employee['complynet_employee_sid'];?></td>
                        <td><?=formatDateToDB($employee['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></td>
                        <td><a class='showdetail btn btn-success' data-details='<?php echo $employee['complynet_json'];?>' href='#'><b>Detail</b></a></td>
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>



  <div id="offcomplynet" class="tab-pane fade">
  <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <caption></caption>
                <thead>
                    <tr>
                        <th scope="col">Employee Id</th>
                        <th scope="col">Email</th>
                        <th scope="col">DateTime</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($offcomplynetmployees as $offemployee){
                    ?>
                    <tr>
                        <td><?=$offemployee['sid'];?></td>
                        <td>
                        <?php 
                       echo $offemployee['first_name'].' '.$offemployee['last_name'].'<br>';
                       echo $employee['email'];
                        ?>
                        </td>
                        <td><?=formatDateToDB($offemployee['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></td>
                        <td>
                            <?php if($offemployee['first_name']=='' || $offemployee['last_name']=='' || $offemployee['email']=='' || $offemployee['job_title'] =='' || $offemployee['department_sid'] == '' || $offemployee['department_sid'] ==0 ){?>
                            <a class='showdetailreason btn btn-danger' data-details='<?php echo json_encode($offemployee);?>' href='#'><b>Reason</b></a>
                      <?php  }else{ ?>
                            <button class="btn btn-success jsSyncEmployee" employee-sid='<?=$offemployee['sid'];?>'>Sync</button>
                <?php } ?>
                       
                        </td>

                   
                    </tr>
                    <?php
                } ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
 

  
</div>


</div>



<script>

$('.showdetail').click(function(event){ 


    var emptable = '';
    var detailvalue = $(this).attr('data-details');

   let obj = JSON.parse(detailvalue);

emptable += '<div class="table-responsive"><table class="table table-striped">';

emptable += '        <tbody>';
   $.each(obj[0],function(key,val){
    emptable += '                <tr>';
emptable += '                    <th scope="col" class="col-sm-3">'+key+'</th>';
emptable += '                    <td>'+val+'</td>';
emptable += '               </tr>';

   });

emptable += '            </tbody></table></div>';


var mymodal = $('#file_preview_modal');
mymodal.find('.modal-title').text('Employee Details');
mymodal.find('.modal-body').html(emptable);

mymodal.modal('show');


});



$('.showdetailreason').click(function(event){ 

var emptable = '';
var emptyfields='';
var detailvalue = $(this).attr('data-details');
let obj = JSON.parse(detailvalue);
if(obj.first_name==''){
    emptyfields +="First Name <br>"
}
if(obj.last_name==''){
    emptyfields +="Last Name <br>"
}

if(obj.email==''){
    emptyfields +="Email <br>"
}

if(obj.job_title==''){
    emptyfields +="Job Title <br>"
}

if(obj.department_sid=='' || obj.department_sid==0){
    emptyfields +="Department "
}

emptable += '<div class="table-responsive"><table class="table table-striped">';

emptable += '        <tbody>';

emptable += '               <tr>';
emptable += '                <td scope="col"><b>These Fields Are Required</b> <br><br><p class="text-danger">'+emptyfields+'</p></td>';
emptable += '                </tr>';


emptable += '            </tbody></table></div>';


var mymodal = $('#file_preview_modal');
mymodal.find('.modal-title').text('Employee Details');
mymodal.find('.modal-body').html(emptable);

mymodal.modal('show');


});



</script>