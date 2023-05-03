<!--  -->
<div class="container">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "employee_state_tax", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            State tax information
                        </h1>
                    </div>
                </div>
                <!-- Body -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Msssage</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--All records-->
                                    <?php if ($work_address == 0) { ?>
                                        <tr>
                                            <td>Employee working address is missing.</td>
                                            <td><a class="btn btn-orange csF16 js jsNavBarAction" data-id="employee_profile" href="javascript:void(0)">Go to Personal details</a> </td>
                                        </tr>
                                    <?php } ?>
                                    <?php if ($federal_tax == 0) { ?>
                                        <tr>
                                            <td>Employee Federal Tax Information is missing.</td>
                                            <td><a class="btn btn-orange csF16 js jsNavBarAction" data-id="employee_federal_tax" href="javascript:void(0)">Go to Federal tax</a>   </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
