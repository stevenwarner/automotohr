<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('Performance_management/theme2//left_menu'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?></span>
                    </div>
                    <div style="position: relative;">
                        <div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                            <form id="compareteam" action="<?php echo base_url('performance-management/teamcomparison'); ?>" method="post">
                                <div class="panel panel-theme">
                                    <div class="panel-heading" style="background-color: #81b431;">
                                        <p class="csF16 csB7 csW mb0">Team(s)</p>
                                    </div>
                                    <div class="panel-body jsPageBody" data-page="visibility" style="min-height:0px;">
                                        <!-- Teams -->
                                        <div class="row">
                                            <br />
                                            <div class="col-sm-4 col-xs-12">
                                                <label class="csF16 csB7">Select teams for comparison</label>
                                            </div>
                                            <div class="col-sm-8 col-xs-12">
                                                <select id="js-teams" name="teams[]" multiple>
                                                    <?php if (!empty($company_dt['Teams'])): ?>
                                                        <?php foreach ($company_dt['Teams'] as $team): ?>
                                                            <option value="<?= $team['Id']; ?>" <?= !empty($teams) && in_array($team['Id'], $teams) ? 'selected' : ''; ?>><?= $team['Name']; ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <span class="pull-right">
                                            <button class="btn btn-success jsUpdateSettings" type="submit"><i class="fa" aria-hidden="true"></i>&nbsp;Compare</button>
                                        </span>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php   if (!empty($teamsgoalsdata)) {?>
                        <div class="col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                            <div class="panel panel-theme">
                                <div class="panel-heading" style="background-color: #81b431;">
                                    <p class="csF16 csB7 csW mb0">Details</p>
                                </div>
                                <div class="panel-body jsPageBody" data-page="visibility">
                                    <!-- Teams -->
                                    <div class="row">
                                        <?php                                      
                                            foreach ($teamsgoalsdata as $goal) {
                                        ?>
                                                <div class="col-sm-6 col-xs-12">

                                                    <table class="table table-striped">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" style="background-color: #81b431;color:#fff;font-size:16px; padding-top: 10px;padding-bottom: 10px;">
                                                                    <string><?php echo $goal['team_title']; ?></string>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="background-color: #000;color:#fff"><strong>Total Number Of Goals: (<?php echo $goal['total_number_of_goals']; ?>)</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background-color: #81b431;color:#fff"><strong>Completed: (<?php echo $goal['completed']; ?>)</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background-color: #cc1100cc; color:#fff"><strong>Not Completed: <?php echo $goal['not_completed']; ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background-color:#5cb85c;color:#fff"><strong>Closed: (<?php echo $goal['closed']; ?>)</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background-color:#000fffcc;color:#fff"><strong>Opened: (<?php echo $goal['opened']; ?>)</strong></td>
                                                            </tr>

                                                            <tr>
                                                                <td style="background-color: #ffb72b;color:#fff"><strong>On Track: <?php echo $goal['on_track']; ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="background-color: #fd7a2acc;color:#fff"><strong>Off Track: <?php echo $goal['off_track']; ?></strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>   
    $('#js-teams').select2({ minimumResultsForSearch: -1 });
    <?php if ($team1) { ?>
        $('#js-teams').select2('val', '<?php echo $teams ?>');
    <?php } ?>
</script>