<style>   
    .question-section:nth-child(even) {
        background: #eee;
    }

    textarea {
        resize: none;
    }
</style>  
<?php 
    $readonly = $section_2 ? 'readonly' : '';
    $disabled = $section_2 ? 'disabled' : ''; 
?> 
<br />
<div class="container">
    <form action="" id="jsSectionOneForm">
        <?php if ($section_2_status == "uncompleted") { ?>
            <div class="panel panel-default">
                <div class="panel-footer text-right">
                    <button class="btn btn-orange jsSaveSectionTwo">Save</button>
                </div>
            </div>
        <?php } ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="m0">
                    <strong>
                        Employee Section 2: The Year in Review
                    </strong>
                </h3>
            </div>
            <div class="panel-body">
                <!-- Question Start -->
                <div class="row question-section">
                    <div class="col-md-12 col-sm-12">
                        <p>
                            List 3-4 top accomplishments & add a reflection on overall performance for the year.
                        </p>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Accomplishment</th>
                                    <th scope="col">Employee Comments/Reflection</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>
                                        <input type="text" name="accomplishment_1" <?=$readonly?> class="form-control" value="<?= $section_2['accomplishment_1'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="accomplishment_comment_1" rows="4" <?=$readonly?> class="form-control"><?= $section_2['accomplishment_comment_1'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>
                                        <input type="text" name="accomplishment_2" <?=$readonly?> class="form-control jsAccomplishment" data-key="jsAccomplishment2" value="<?= $section_2['accomplishment_2'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="accomplishment_comment_2" <?=$readonly?> rows="4" class="form-control jsAccomplishment2"><?= $section_2['accomplishment_comment_2'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>
                                        <input type="text" name="accomplishment_3" <?=$readonly?> class="form-control jsAccomplishment" data-key="jsAccomplishment3" value="<?= $section_2['accomplishment_3'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="accomplishment_comment_3" <?=$readonly?> rows="4" class="form-control jsAccomplishment3"><?= $section_2['accomplishment_comment_3'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>
                                        <input type="text" name="accomplishment_4" <?=$readonly?> class="form-control jsAccomplishment" data-key="jsAccomplishment4" value="<?= $section_2['accomplishment_4'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="accomplishment_comment_4" <?=$readonly?> rows="4" class="form-control jsAccomplishment4"><?= $section_2['accomplishment_comment_4'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Question End -->
                <!-- <hr> -->
                <!-- Question Start -->
                <div class="row question-section">
                    <div class="col-md-12 col-sm-12">
                        <p>
                            Opportunities for Improved Performance: List 2-4 areas of improvement & how you will work on these improvements over the coming year.
                        </p>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Opportunity for Improvement</th>
                                    <th scope="col">Employee Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>
                                        <input type="text" name="opportunities_1" <?=$readonly?> class="form-control" value="<?= $section_2['opportunities_1'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="opportunities_comment_1" rows="4" <?=$readonly?> class="form-control"><?= $section_2['opportunities_comment_1'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>
                                        <input type="text" name="opportunities_2" <?=$readonly?> class="form-control jsOpportunities" data-key="jsOpportunities2" value="<?= $section_2['opportunities_2'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="opportunities_comment_2" <?=$readonly?> rows="4" class="form-control jsOpportunities2"><?= $section_2['opportunities_comment_2'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>
                                        <input type="text" name="opportunities_3" <?=$readonly?> class="form-control jsOpportunities" data-key="jsOpportunities3" value="<?= $section_2['opportunities_3'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="opportunities_comment_3" <?=$readonly?> rows="4" class="form-control jsOpportunities3"><?= $section_2['opportunities_comment_3'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">4</th>
                                    <td>
                                        <input type="text" name="opportunities_4" <?=$readonly?> class="form-control jsOpportunities" data-key="jsOpportunities4" value="<?= $section_2['opportunities_4'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="opportunities_comment_4" <?=$readonly?> rows="4" class="form-control jsOpportunities4"><?= $section_2['opportunities_comment_4'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Question End -->
                <!-- <hr> -->
                <!-- Question Start -->
                <div class="row question-section">
                    <div class="col-md-12 col-sm-12">
                        <p>
                            List 2-3 goals for the upcoming year. One must reflect a personal development goal.
                        </p>
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Goal</th>
                                    <th scope="col">General comments and summary relating to the status of the goal, attainment, difficulty of goal, and impacting factors.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>
                                        <input type="text" name="goal_1" <?=$readonly?> <?=$readonly?> class="form-control" value="<?= $section_2['goal_1'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="goal_comment_1" <?=$readonly?> rows="4" <?=$readonly?> class="form-control"><?= $section_2['goal_comment_1'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>
                                        <input type="text" name="goal_2" <?=$readonly?> class="form-control jsGoal" data-key="jsGoal2" value="<?= $section_2['goal_2'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="goal_comment_2" <?=$readonly?> rows="4" class="form-control jsGoal2"><?= $section_2['goal_comment_2'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>
                                        <input type="text" name="goal_3" <?=$readonly?> class="form-control jsGoal" data-key="jsGoal3" value="<?= $section_2['goal_3'] ?? '' ?>">
                                    </td>
                                    <td>
                                        <textarea name="goal_comment_3" <?=$readonly?> rows="4" class="form-control jsGoal3"><?= $section_2['goal_comment_3'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Question End -->
                <!-- <hr> -->
                <!-- Question Start -->
                <div class="row question-section">
                    <div class="col-md-12 col-sm-12">
                        <h4>
                            1. Have you and your manager reviewed your job description for this review period?
                        </h4>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <label class="control control--radio">
                                <span class="text-large">
                                    Yes
                                </span>
                                <input type="radio" <?=$disabled?> name="review_period_radio" value="1" <?php echo $section_2['review_period_radio'] == 1 ? 'checked' : '' ?> />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <label class="control control--radio">
                                <span class="text-large">
                                    No
                                </span>
                                <input type="radio" <?=$disabled?> name="review_period_radio" value="2" <?php echo $section_2['review_period_radio'] == 2 ? 'checked' : '' ?> />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <p>
                            (Please attach a copy of your job description for review with your manager)
                        </p>
                    </div>
                </div>
                <!-- Question End -->
                <!-- <hr> -->
                <!-- Question Start -->
                <div class="row question-section">
                    <div class="col-md-12 col-sm-12">
                        <h4>
                            2. Do you have access to equipment and resources necessary to perform your job function?
                        </h4>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <label class="control control--radio">
                                <span class="text-large">
                                    Yes
                                </span>
                                <input type="radio" <?=$disabled?> name="equipment_resources_radio" class="jsEquipmentResourcesRadio" value="1" <?php echo $section_2['equipment_resources_radio'] == 1 ? 'checked' : '' ?> />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <label class="control control--radio">
                                <span class="text-large">
                                    No
                                </span>
                                <input type="radio" <?=$disabled?> name="equipment_resources_radio" class="jsEquipmentResourcesRadio" value="2" <?php echo $section_2['equipment_resources_radio'] == 2 ? 'checked' : '' ?> />
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <p>
                            (If No, please list the equipment you deem necessary subject to Managers approval and budgeting)
                        </p>

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label for="license_notes">Necessary Equipment or Resources Needed</label>
                                <input type="text" name="equipment_resources_needed" class="form-control jsEquipmentResourcesNeeded" value="<?= $section_2['equipment_resources_needed'] ?? '' ?>">
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- Question End -->
                <!-- <hr> -->
                <!-- Question Start -->
                <div class="row question-section">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group autoheight">
                            <label for="license_notes">3. Is there any additional support or training you feel would be helpful for <?= $companyName ?> to provide for you to help you succeed in your current role?</label>
                            <textarea name="additional_support" rows="10" <?=$readonly?> class="form-control"><?= $section_2['additional_support'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- Question End -->
                <!-- <hr> -->
                <!-- Question Start -->
                <div class="row question-section">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="form-group autoheight">
                            <label for="license_notes">4. Employee Additional Comments:</label>
                            <textarea name="additional_comment" rows="10" <?=$readonly?> class="form-control"><?= $section_2['additional_comment'] ?? '' ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- Question End -->
            </div>

            <?php if ($section_2_status == "uncompleted") { ?>
                <div class="panel panel-default">
                    <div class="panel-footer text-right">
                        <button class="btn btn-orange jsSaveSectionTwo">Save</button>
                    </div>
                </div>
            <?php } ?>

    </form>
</div>