
<script src="<?= base_url('assets/employee_panel/js/jquery-1.11.3.min.js')?>"></script>
<script src="<?= base_url('assets/js/select2.js');?>"></script>
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
                    <!--  Settings -->

                    <div class="panel panel-default _csMt10">
                        <div class="panel-heading">
                            <p class="_csF16 "><b>Visibility</b> <small>(Who can manage reviews)</small></p>
                        </div>
                        <div class="panel-body jsPageBody" data-page="visibility">
                            <!-- Roles -->
                            <div class="row">
                                <div class="col-sm-4 col-xs-12">
                                    <label class="_csF16">Role(s) <i class="fa fa-question-circle-o jsHintBtn" data-target="title" aria-hidden="true"></i></label>
                                    <p class="_csF14 jsHintBody" data-hint="title">The selected Role(s) can manage this review.</p>
                                </div>
                                <div class="col-sm-8 col-xs-12">
                                    <select id="jsReviewRolesInp" multiple>
                                        <?php foreach (getRoles() as $index => $role) : ?>
                                            <option value="<?= $index; ?>" <?= !empty($roles) && in_array($index, $roles) ? 'selected' : ''; ?>><?= $role; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Departments -->
                            <div class="row">
                                <br />
                                <div class="col-sm-4 col-xs-12">
                                    <label class="_csF16">Department(s) <i class="fa fa-question-circle-o jsHintBtn" data-target="title" aria-hidden="true"></i></label>
                                    <p class="_csF14 jsHintBody" data-hint="title">The selected Department(s) supervisors can manage this
                                        review.</p>
                                </div>
                                <div class="col-sm-8 col-xs-12">
                                    <select id="jsReviewDepartmentsInp" multiple>
                                        <option value="1">Test1 </option>
                                        <option value="2">Test2 </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Teams -->
                            <div class="row">
                                <br />
                                <div class="col-sm-4 col-xs-12">
                                    <label class="_csF16">Team(s) <i class="fa fa-question-circle-o jsHintBtn" data-target="title" aria-hidden="true"></i></label>
                                    <p class="_csF14 jsHintBody" data-hint="title">The selected Team(s) team leads can manage this
                                        review.</p>
                                </div>
                                <div class="col-sm-8 col-xs-12">
                                    <select id="jsReviewTeamsInp" multiple>
                                        <option value="1">Team1</option>
                                        <option value="2">Team2</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Employees -->
                            <div class="row">
                                <br />
                                <div class="col-sm-4 col-xs-12">
                                    <label class="_csF16">Employee(s) <i class="fa fa-question-circle-o jsHintBtn" data-target="title" aria-hidden="true"></i></label>
                                    <p class="_csF14 jsHintBody" data-hint="title">The selected Employee(s) can manage this review.</p>
                                </div>
                                <div class="col-sm-8 col-xs-12">
                                    <select id="jsReviewEmployeesInp" multiple>
                                        <option value="1">Employee1</option>
                                        <option value="2">Employee2</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <span class="pull-right">
                                <button class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Update</button>
                            </span>
                            <div class="clearfix"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script>
    //
    $('#jsReviewRolesInp').select2({
        closeOnSelect: false
    });
    $('#jsReviewDepartmentsInp').select2({
        closeOnSelect: false
    });
    $('#jsReviewTeamsInp').select2({
        closeOnSelect: false
    });
    $('#jsReviewEmployeesInp').select2({
        closeOnSelect: false
    });
</script>