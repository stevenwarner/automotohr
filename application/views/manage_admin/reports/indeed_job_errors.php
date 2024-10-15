<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2> <?php echo $jobInfo['Title']; ?></h2>

            <?php if ($errors) { ?>
                <input type="hidden" id="jsJobId" value="<?=$jobInfo['job_sid']?>">
                <input type="hidden" id="jsQueueId" value="<?=$jobInfo['sid']?>">
                <?php if (isset($errors['salary'])) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Salary</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF14 csInfo csB7 text-danger">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        &nbsp;<b><?php echo $errors['salary']; ?></b>
                                    </p>
                                </div>    
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset id="details_div">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-100 autoheight">
                                                    <label>Salary or Salary Range:</label>
                                                    <input class="invoice-fields" type="text" id="jsSalary" value="<?php echo $jobInfo['Salary']; ?>">
                                                    <div class="text-danger" style='font-style: italic;'>
                                                        <b>
                                                            Please use the following format: $20 - $30
                                                        </b>
                                                    </div>
                                                </li>

                                                <li class="form-col-50-right">
                                                    <label>Salary Type:</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" id="jsSalaryType">
                                                            <option value="0">Select Salary Type</option>
                                                            <option value="per_hour" <?= $jobInfo["SalaryType"] == "per_hour" ? "selected" : ""; ?>>
                                                                per hour
                                                            </option>
                                                            <option value="per_week" <?= $jobInfo["SalaryType"] == "per_week" ? "selected" : ""; ?>>
                                                                per week
                                                            </option>
                                                            <option value="per_month" <?= $jobInfo["SalaryType"] == "per_month" ? "selected" : ""; ?>>
                                                                per month
                                                            </option>
                                                            <option value="per_year" <?= $jobInfo["SalaryType"] == "per_year" ? "selected" : ""; ?>>
                                                                per year
                                                            </option>
                                                        </select>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </fieldset>        
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>  

                <?php if (isset($errors['JobType'])) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Job Type</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="csF14 csInfo csB7 text-danger">
                                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        &nbsp;<b><?php echo $errors['JobType']; ?></b>
                                    </p>
                                </div>    
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset id="details_div">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <li class="form-col-50-right">
                                                    <label>Job Type:</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" id="jsJobType">
                                                            <option value="0">Select Job Type</option>
                                                            <option value="Full Time" <?= $listing["JobType"] == "Full Time" ? "selecte" : "" ?>>
                                                                Full Time
                                                            </option>
                                                            <option value="Part Time" <?= $listing["JobType"] == "Part Time" ? "selecte" : "" ?>>
                                                                Part Time
                                                            </option>
                                                            <option value="Seasonal" <?= $listing["JobType"] == "Seasonal" ? "elected" : ""?>>
                                                                Seasonal
                                                            </option>

                                                        </select>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </fieldset>  
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?> 
                
                <?php if (isset($errors['description'])) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Description</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php foreach ($errors['description'] as $issue) { ?>
                                        <p class="csF14 csInfo csB7 text-danger" style="font-size: 12px !important">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            &nbsp;<b><?php echo $issue; ?></b>
                                        </p>    
                                    <?php } ?>
                                </div>    
                            </div>

                            <br>

                            <div class="row">
                                <div class="col-sm-12">
                                    <fieldset id="details_div">
                                        <div class="universal-form-style-v2">
                                            <ul>
                                                <div class="description-editor">
                                                    <label>Job Description:<span class="staric">*</span></label>
                                                    <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                                    <textarea class="ckeditor" id="jsJobDescription" cols="67" rows="6"><?php echo $jobInfo["JobDescription"]; ?></textarea>
                                                </div>
                                            </ul>
                                        </div>
                                    </fieldset>  
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?> 
                
                
                <a class="btn btn-success pull-right jsUpdateJobInfo">Update Job</a>      
            <?php } else { ?>
                <div class="alert alert-info text-center">
                    <strong>
                        Error not found.
                    </strong>
                </div>
            <?php } ?>
        </div>
    </div>        
</div>