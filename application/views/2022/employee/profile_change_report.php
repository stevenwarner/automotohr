<div class="container-fluid">
    <div class="row">
        <!--  -->
        <div class="col-sm-12 col-md-3">
            <div class="_csMt30 _csMb30">
                <?php $this->load->view('2022/sidebar'); ?>
            </div>
        </div>
        <!-- Main Area -->
        <div class="col-sm-12 col-md-9">
            <div class="_csMt30 _csMb30">
                <!--  -->
                <div class="row">
                    <div class="col-sm-12 col-md-8">
                        <h1 class="_csF18 _csMt0">Employee Profile Changes</h1>
                    </div>
                    <div class="col-sm-12 col-md-4 text-right">
                        <a href="" class="btn _csF14 _csB1 _csF2 _csR5">
                            <i class="fa fa-long-arrow-left _csF14" aria-hidden="true"></i>&nbsp;Dashboard
                        </a>
                    </div>
                    <hr />
                </div>
                <!-- Maim -->
                <div class="panel panel-default">
                    <div class="panel-heading _csB4">
                        <strong>Employee change report</strong>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <label>Select Employees</label>
                                <select name="" id="" multiple>
                                    <option value="0">1</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label>Start Date</label>
                                <input type="text" name="" id="" class="form-control jsStartDatePicker" readonly />
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <label>EndDate</label>
                                <input type="text" name="" id="" class="form-control jsEndDatePicker" readonly />
                            </div>
                        </div>
                        <br>
                        <!--  -->
                        <div class="row">
                            <div class="col-sm-12 text-right">
                                <button class="btn _csB3 _csF2 _csR5">Apply</button>
                                <button class="btn _csB1 _csF2 _csR5">Reset</button>
                            </div>
                        </div>
                        <br />
                        <!--  -->
                        <div class="row">

                            <div class="col-sm-12 text">
                                <h4 class="_csMb0"><strong>Total:</strong> 40 employees</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th class="_csB1 _csF2" scope="col">Employee</th>
                                                <th class="_csB1 _csF2" scope="col">Last Changed<br> Date & Time</th>
                                                <th class="_csB1 _csF2" scope="col">What <br> changed?</th>
                                                <th class="_csB1 _csF2 text-right" scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="_csVm"><strong>Mubashir Ahmed [Admin Plus]</strong></td>
                                                <td class="_csVm">3rd November, Wednesday 2022 15:20:00</td>
                                                <td class="_csVm">
                                                    <dl>
                                                        <dd>- Profile</dd>
                                                        <dd>- Direct Deposit Information</dd>
                                                        <dd>- Drivers License</dd>
                                                        <dd>- Occupational License</dd>
                                                    </dl>
                                                </td>
                                                <td class="text-right _csVm">
                                                    <button class="btn _csB3 _csF2 _csR5 _csF14">
                                                        <i class="fa fa-eye _csF14" aria-hidden="true"></i>&nbsp;
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>