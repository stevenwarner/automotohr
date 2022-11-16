<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
                <!--  -->
                <div class="col-md-3 col-sm-12">
                    <!-- Sidebar -->
                    <?php $this->load->view('2022/sidebar'); ?>
                </div>
                <!--  -->
                <div class="col-md-9 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Survey</a>
                        </div>
                    </div>
                    <!-- Running surveys -->
                    <div class="panel panel-default _csMt10">
                        <div class="panel-heading _csP0">
                            <!-- Nav tabs -->
                            <ul class="_csPageTabs">
                                <li>
                                    <a href="#jsActive" class="active" data-toggle="tab">Active (2)</a>
                                </li>
                                <li>
                                    <a href="#jsDraft" data-toggle="tab">Draft (5)</a>
                                </li>
                                <li>
                                    <a href="#jsFinished" data-toggle="tab">Finished (5)</a>
                                </li>
                            </ul>

                        </div>
                        <div class="panel-body">
                            <!-- Tab panes -->
                            <div class="tab-pane" id="jsActive">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <caption></caption>
                                        <thead>
                                            <tr>
                                                <th scope="col" class="_csF7 col-md-3">Title</th>
                                                <th scope="col" class="_csF7 col-md-2">Period</th>
                                                <th scope="col" class="_csF7 col-md-2">Response Progress</th>
                                                <th scope="col" class="_csF7 col-md-5 text-right">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <!-- <tr>
                                                <td class="_csVm">
                                                    <p class="_csMb0"><strong>Pulse Check</strong></p>
                                                    <span class="label _csB4">Scheduled</span>
                                                </td>
                                                <td class="_csVm">
                                                    <p>Oct 5, 2022 - Oct 12, 2022</p>
                                                </td>
                                                <td class="_csVm">
                                                    <div class="progress _csMb0 _csMt10">
                                                        <div class="progress-bar _csB4" role="progressbar" aria-valuenow="" aria-valuemin="" aria-valuemax="" style="width: 80%;">
                                                            <span class="sr-only"> 80 % Complete</span>
                                                        </div>
                                                    </div>
                                                    <p class="_csF14">80 % Completed</p>
                                                </td>
                                                <td class="text-right _csVm">
                                                    <button class="btn _csF14 _csB4 _csF2 _csR5" title="Edit Survey" placement="top"><i class="fa _csF14 fa-edit" aria-hidden="true"></i>&nbsp;Edit</button>
                                                    <button class="btn _csF14 _csB3 _csF2 _csR5" title="Notifications" placement="top"><i class="fa _csF14 fa-bell-o" aria-hidden="true"></i>&nbsp;Notifications</button>
                                                    <button class="btn _csF14 _csB3 _csF2 _csR5" title="Share Results With Managers" placement="top"><i class="fa _csF14 fa-bullhorn" aria-hidden="true"></i>&nbsp;Share Results</button>
                                                    <button class="btn _csF14 btn-danger _csF2 _csR5" title="End Survey" placement="top"><i class="fa _csF14 fa-times" aria-hidden="true"></i>&nbsp;End Survey</button>
                                                    <button class="btn _csF14 btn-danger _csF2 _csR5" title="Trash" placement="top"><i class="fa _csF14 fa-trash" aria-hidden="true"></i>&nbsp;Trash</button>
                                                    <button class="btn _csF14 _csB4 _csF2 _csR5" title="Finalize" placement="top"><i class="fa _csF14 fa-edit" aria-hidden="true"></i>&nbsp;Finalize</button>
                                                    <button class="btn _csF14 _csB1 _csF2 _csR5" title="Results" placement="top"><i class="fa _csF14 fa-eye" aria-hidden="true"></i>&nbsp;Results</button>
                                                </td>
                                            </tr> -->

                                            <tr>
                                                <td colspan="4">
                                                    <p class="text-center _csP20">
                                                        <i class="fa fa-info-circle _csF4 _csF40" aria-hidden="true"></i> <br> <br>
                                                        <span class="_csF20">No surveys yet!</span>
                                                    </p>
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