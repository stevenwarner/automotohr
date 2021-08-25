<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF18 csB9">
                    Employees Listing
                </h1>
                <hr>
            </div>
        </div>
        <!--  -->
        <div class="cardContainer">
            <!-- Filter -->
            <div class="row">
                <div class="col-sm-5 col-xs-12">
                    <label class="csF16 csB7">Employees</label>
                    <select id="jsFilterEmployees" multiple>
                        <?php foreach($Employees as $emp): ?>
                            <option value="<?=$emp['Id'];?>"><?=$emp['Name'].$emp['Role'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <div class="col-sm-4 col-xs-12">
                    <label class="csF16 csB7">Status</label>
                    <select id="jsFilterStatus">
                        <option value="-1">All</option>
                        <option value="1">On Payroll</option>
                        <option value="0">Not On Payroll</option>
                    </select>
                </div>
                <div class="col-sm-3 col-xs-12">
                    <br>
                    <span class="pull-right" style="margin-top: 5px;">
                        <button class="btn btn-success csF16 csB7 jsFilterApplyBtn">Apply Filter</button>
                        <button class="btn btn-black csF16 csB7 jsFilterClearBtn">Clear Filter</button>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col">Employee</th>
                                <th class="text-center" scope="col">SSN</th>
                                <th class="text-center" scope="col">DOB</th>
                                <th class="text-center" scope="col">Status</th>
                                <th class="text-center" scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($Employees as $emp):?>
                                <!--  -->
                                <tr 
                                    data-name="<?=$emp['Name'];?>"
                                    data-id="<?=$emp['Id'];?>"
                                    data-status="<?=$emp['OnPayroll'];?>
                                ">
                                    <td class="vam">
                                        <p><?=$emp['Name'].$emp['Role'];?></p>
                                        <p class="csF14">(<?=$emp['Email'];?>)</p>
                                    </td>
                                    <td class="vam text-center"><?=!empty($emp['SSN']) ? $emp['SSN'] : 'N/A';?></td>
                                    <td class="vam text-center"><?=!empty($emp['DOB']) ? formatDateToDB($emp['DOB'], DB_DATE, DATE) : 'N/A';?></td>
                                    <td class="vam text-center">
                                        <label class="label label-<?=$emp['OnPayroll'] == 1 ? 'success' : 'danger';?> csW"><?=$emp['OnPayroll'] == 1 ? 'On Payroll' : 'Not On Payroll';?></label>
                                    </td>
                                    <td class="vam text-center">
                                        <a href="<?=base_url("payroll/employee/add/".($emp['Id'])."");?>" class="btn btn-success">
                                            <i class="fa fa-<?=$emp['OnPayroll'] ? 'eye' : 'plus-square'; ?>" aria-hidden="true"></i>&nbsp;<?=$emp['OnPayroll'] ? 'View Details' : 'Add To Payroll'; ?>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-success jsEBCBtn">
                                            <i class="fa fa-money" aria-hidden="true"></i>&nbsp;Manage Bank Accounts
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add System Model -->
<link rel="stylesheet" href="<?=base_url("assets/css/SystemModel.css");?>">
<script src="<?=base_url("assets/js/SystemModal.js");?>"></script>

<script>
    $(function(){
        //
        $('#jsFilterStatus').select2({ minimumResultsForSearch: -1});
        $('#jsFilterEmployees').select2();
        //
        $('.jsFilterApplyBtn').click(function(e){
            //
            e.preventDefault();
            //
            var o = {};
            //
            o.employeeIds = $('#jsFilterEmployees').val() || [];
            o.status = $('#jsFilterStatus').val();
            //
            $('tbody tr').hide(0);
            //
            if(o.employeeIds.length == 0){
                //
                if(o.status == -1){
                    $('tbody tr').show(0);
                } else{
                    $('tbody tr[data-status="'+(o.status)+'"]').show(0);
                }
            } else{
                o.employeeIds.map(function(emp){
                    //
                    var str = '[data-id="'+(emp)+'"]';
                    //
                    if(o.status != -1){
                        str += '[data-status="'+(o.status)+'"]';
                    }
                    console.log(str)
                    //
                    $('tbody tr'+(str)+'').show(0);
                });
            }
        });
        //
        $('.jsFilterClearBtn').click(function(e){
            //
            e.preventDefault();
            //
            $('tbody tr').show(0);
            //
            $('#jsFilterStatus').select2('val', -1);
            $('#jsFilterEmployees').select2('val', null);
        });
        //
        $('.jsEBCBtn').click(function(event){
            //
            event.preventDefault();
            //
            var employeeId = $(this).closest('tr').data('id');
            //
            Modal({
                Id: "jsEBAModal",
                Title: $(this).closest('tr').data('name')+" Bank Accounts",
                Body:  "<div id=\"jsEBAModalBody\"></div>",
                Loader: "jsEBAModalLoader",
            }, function(){
                $.get("<?=base_url("payroll/get_employee_ba");?>/"+employeeId)
                .done(function(resp){
                    $('#jsEBAModalBody').html(resp);
                    ml(false, 'jsEBAModalLoader');
                });
            });
        });
    });
</script>