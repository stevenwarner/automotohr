
<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('courses/partials/navbar'); ?>
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
                        <span class="col-sm-12 text-right">
                            <a class="btn _csB1 _csF2 _csR5 _csF16 _csMr10" href="<?=base_url('lms_courses/my_courses');?>">
                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back to Courses 
                            </a>
                        </span>
                    </div>

                    <div class="panel panel-default _csMt10">
                        <div class="panel-body">
                            <!-- Basic -->
                            <div class="row">
                                <div class="col-md-6 col-xs-12">
                                    <div>
                                        <p class="_csF16 _csB2">
                                            <b><?php echo $course['title']; ?></b>
                                        </p>
                                        <p class="_csF14">
                                            <?php echo $course['display_start_date'].' - '.$course['display_end_date']; ?>
                                            <br />
                                            <?php echo ' Due in '.$course['daysLeft']; ?>
                                        </p>
                                        <p class="_csF14 _csB2">
                                            <?php echo $course['description']; ?>
                                        </p>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-md-6 col-xs-12">
                                    <span class="pull-right _csF16 _csB2">
                                        <strong class="._csF1">PENDING</strong>
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <!-- Basic -->
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div>
                                        <p class="_csF16 _csB2" id="jsRemainingQuestions">
                                            <b> Completed <?php echo $chapter['completedCount']; ?> out of <?php echo $chapter['chaptersCount']; ?> Chapter(s) </b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Basic -->
                            <!-- <div class="row">
                                <br>
                                <div class="col-md-12 col-xs-12">
                                    <div>
                                        <ul class="_csPaginationMenu text-left" id="jsChaptersMenu">
                                            <li class="active">
                                                <a href="#" title="Pending" placement="top">1</a>
                                            </li>
                                            <li class="">
                                                <a href="#" title="Pending" placement="top">2</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>  -->     
                        </div>
                    </div>

                    <div class="row" >
                        <br />
                        <div class="col-xs-12">
                            <div class="panel panel-theme">
                                <div class="panel-heading _csB1">
                                    <p class="_csF14 _csF2">
                                        <b id="jsChapterTitle"><?php echo $chapter['title']; ?></b>
                                    </p>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <p class="_csF14" id="jsChapterDescription">
                                                <?php echo $chapter['description']; ?>
                                            </p>
                                        </div>
                                    </div>   
                                    <?php if ($chapter['status'] == "video_pending") { ?>  
                                        <div class="row">    
                                            <div class="col-md-12 col-xs-12">
                                                <video id="jsChapterVideo" autoplay controls style="width: 100%;" preload="metadata">
                                                    <source src="<?php echo $chapter['videoURL']; ?>" type="video/webm">
                                                    </source>
                                                    <track label="English" kind="captions" srclang="en" default />
                                                </video>
                                            </div>
                                        </div> 
                                    <?php } else if ($chapter['status'] == "quiz_pending") { ?>   
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                <?php foreach ($chapter['quiz'] as $qkey => $question) { ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h3 class="_csF14"><?php echo $question['question']; ?></h3>
                                                        </div>
                                                        <?php $questionKey = getQuizKey($question['question']); ?>
                                                        <?php if ($question['type'] == 'boolean') { ?>
                                                            <br>
                                                            <div class="col-md-12">
                                                              <label class="control control--radio _csF14">
                                                                  <input type="radio" name="jsQuestionChoice<?php echo $qkey; ?>" value="yes" placeholder="<?php echo $questionKey; ?>" checked="checked"/>
                                                                    Yes
                                                                  <span class="control__indicator"></span>
                                                              </label>
                                                              <br />
                                                              <label class="control control--radio _csF14">
                                                                  <input type="radio" name="jsQuestionChoice<?php echo $qkey; ?>" value="no" placeholder="<?php echo $questionKey; ?>"/>
                                                                      No
                                                                  <span class="control__indicator"></span>
                                                              </label>
                                                            </div>
                                                        <?php } ?>

                                                        <?php if ($question['type'] == 'text') { ?>
                                                            <br>
                                                           <div class="col-xs-12">
                                                               <input type="text" class="form-control _csTAD textbox" name="<?php echo $questionKey; ?>"  />
                                                           </div>
                                                        <?php } ?>
                                                    </div>    
                                                <?php } ?>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-12 text-right col-sm-12 _csMt10">
                                                        <button class="btn _csB4 _csF2 _csR5" id="jsChapterQuestionSaveBTN">
                                                            <i class="fa fa-floppy-o _csF14" aria-hidden="true"></i>
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>            
                                            </div>    
                                        </div>
                                    <?php } else if ($chapter['status'] == "chapter_completed") { ?>
                                        <div class="row">    
                                            <div class="col-md-12 col-xs-12"> 
                                                <table class="table table-striped table-condensed">
                                                    <caption></caption>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="_csF16 _csB1 _csF2">Chapter</th>
                                                            <th scope="col" class="_csF16 _csB1 _csF2">Status</th>
                                                            <th scope="col" class="_csF16 _csB1 _csF2">Total Question</th>
                                                            <th scope="col" class="_csF16 _csB1 _csF2">Correct Answer</th>
                                                            <th scope="col" class="_csF16 _csB1 _csF2">Chapter Result</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($courseInfo)) { ?>
                                                            <?php foreach ($courseInfo as $chapter) { ?>
                                                                <tr>
                                                                    <td style="vertical-align: middle">
                                                                        <p class="_csF14">
                                                                            <b><?php echo $chapter['chapter_title']; ?></b>
                                                                        </p>
                                                                    </td>
                                                                    <td style="vertical-align: middle">
                                                                        <?php 
                                                                            if ($chapter['chapter_completed'] == 1) {
                                                                                echo "Completed";
                                                                            } else {
                                                                                echo "Incomplete";
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td style="vertical-align: middle">
                                                                        <?php echo $chapter['quiz_total_marks']; ?>
                                                                    </td>
                                                                    <td style="vertical-align: middle">
                                                                        <?php echo $chapter['quiz_obtain_marks']; ?>
                                                                    </td>
                                                                    <td style="vertical-align: middle" class="<?php echo $chapter['quiz_status']  == 'fail' ? 'text-danger' : 'text-success';?>">
                                                                        <p class="_csF14">
                                                                            <b><?php echo strtoupper($chapter['quiz_status']); ?></b>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="5" class="text-center">
                                                                    <p>
                                                                        <b>
                                                                            Course is not completed yet!
                                                                        </b>    
                                                                    </p>        
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>        
                                    <?php } ?>     
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <span class="col-sm-12 text-right">
                            <a class="btn _csB1 _csF2 _csR5 _csF16 _csMr10 _csMb10" href="<?=base_url('lms_courses/my_courses');?>">
                                <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp; Back to Courses 
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>