<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <p class="csF20 csB6">
                    Employees
                </p>
            </div>
            <div class="col-sm-6 col-xs-12 text-right">
                <button class="btn btn-black jsFilterBTN csF16">
                    <i class="fa fa-sliders csF16" aria-hidden="true"></i>&nbsp;Filter
                </button>
            </div>
        </div>
        <!--  -->
        <div class="cs-separator"></div>

        <!-- Filter -->
        <div class="ma10 mb10 jsFilterBox dn">
            <div class="row">
                <!-- Employees -->
                <div class="col-md-8 col-xs-12">
                    <label class="csF14">Employees</label>
                    <select id="jsFilterEmployees" multiple>
                        <option value="0">All</option>
                    </select>
                </div>
                <!-- Buttons -->
                <div class="col-md-4 col-xs-12 text-right">
                    <br />
                    <button class="btn btn-orange">
                        <i class="fa fa-filter csF16" aria-hidden="true"></i>&nbsp;Apply Filter
                    </button>
                    <button class="btn btn-black">
                        <i class="fa fa-times-circle csF16" aria-hidden="true"></i>&nbsp;Clear Filter
                    </button>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="cardContainer ma10">
            <!--  -->
            <?=GetTabHeader(
                [[
                    'Text' => 'Payroll Employees <span class="jsEmployeeCountPayroll">(0)</span>',
                    'Slug' => 'payroll',
                    'Link' => base_url("payroll/employees/payroll")
                ], 
                [
                    'Text' => 'Employees <span class="jsEmployeeCountNormal">(0)</span>',
                    'Slug' => 'normal',
                    'Link' => base_url("payroll/employees/normal")
                ]],
                $SelectedTab
            );?>

            <!-- Main content area  -->
            <div class="tab-content csPR">
                <!--  -->
                <?php $this->load->view('loader_new', ['id' => 'payroll_employees']); ?>
                <div class="table-responsive" id="jsPayrollEmployeesTable" data-company_sid="<?php echo $company_sid; ?>">
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col" class="vam">
                                    <p class="csF14 csW m0">Employee</p>
                                </th>
                                <th scope="col" class="vam text-right">
                                    <p class="csF14 csW m0">Joined Date <i class="fa fa-info-circle csCP" data-title="Note" data-content="Please follow the checklist in order to complete the onboard." aria-hidden="true"></i></p>
                                </th>
                                <th scope="col" class="vam text-right">
                                    <p class="csF14 csW m0">Onboard Date <i class="fa fa-info-circle csCP" data-title="Note" data-content="Please follow the checklist in order to complete the onboard." aria-hidden="true"></i></p>
                                </th>
                                <th scope="col" class="vam text-right">
                                    <p class="csF14 csW m0">Onboard Status <i class="fa fa-info-circle csCP" data-title="Note" data-content="Please follow the checklist in order to complete the onboard." aria-hidden="true"></i></p>
                                </th>
                                <th scope="col" class="vam text-right">
                                    <p class="csF14 csW m0">Actions</p>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="jsPayrollEmployeesDataHolder"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add System Model -->
<link rel="stylesheet" href="<?=base_url(_m("assets/css/SystemModel", 'css'));?>">
<script src="<?=base_url(_m("assets/js/SystemModal"));?>"></script>