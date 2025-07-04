<div class="hr-box candidate-interview-evaluation">
    <div class="hr-box-header bg-header-orange">        
        <strong>Applicant Interview Evaluation Form</strong>        
    </div>
    <form action="" method="POST">
        <div class="table-responsive hr-innerpadding">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="col-lg-6">Competency</th>
                    <th class="col-lg-6">Candidate Rating <span class="hr-required">*</span></th>
<!--                    <th class="col-lg-3">Job relevancy <span class="hr-required">*</span></th>-->
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <strong>Communication:</strong> Candidate expresses thoughts clearly in writing and verbally; projects positive manner in all forms of communication; responds diplomatically.
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['comm_cand']) ? $evaluation_form['comm_cand'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('comm_cand', 10, $default_selected); ?> name="comm_cand" id="comm_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('comm_cand', 7.5, $default_selected); ?> name="comm_cand" id="comm_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('comm_cand', 5,  $default_selected); ?> name="comm_cand" id="comm_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('comm_cand', 2.5, $default_selected); ?> name="comm_cand" id="comm_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('comm_cand', 0, $default_selected); ?> name="comm_cand" id="comm_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['comm_job']) ? $evaluation_form['comm_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('comm_job', 10, $default_selected); ?><!-- name="comm_job" id="comm_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('comm_job', 7.5, $default_selected); ?><!-- name="comm_job" id="comm_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('comm_job', 5, $default_selected); ?><!-- name="comm_job" id="comm_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('comm_job', 2.5, $default_selected); ?><!-- name="comm_job" id="comm_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('comm_job', 0, $default_selected); ?><!-- name="comm_job" id="comm_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td>
                        <strong>Problem Solving/ Decision Making:</strong> Candidate demonstrates ability to make decisions; involves others as appropriate; demonstrates ability to resolve issues.
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['pros_decm_cand']) ? $evaluation_form['pros_decm_cand'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('pros_decm_cand', 10, $default_selected); ?> name="pros_decm_cand" id="pros_decm_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('pros_decm_cand', 7.5, $default_selected); ?> name="pros_decm_cand" id="pros_decm_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('pros_decm_cand', 5, $default_selected); ?> name="pros_decm_cand" id="pros_decm_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('pros_decm_cand', 2.5, $default_selected); ?> name="pros_decm_cand" id="pros_decm_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('pros_decm_cand', 0, $default_selected); ?> name="pros_decm_cand" id="pros_decm_cand_05" value="0" type="radio">
                                <div  class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['pros_decm_job']) ? $evaluation_form['pros_decm_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('pros_decm_job', 10, $default_selected); ?><!-- name="pros_decm_job" id="pros_decm_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('pros_decm_job', 7.5, $default_selected); ?><!-- name="pros_decm_job" id="pros_decm_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('pros_decm_job', 5, $default_selected); ?><!-- name="pros_decm_job" id="pros_decm_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('pros_decm_job', 2.5, $default_selected); ?><!-- name="pros_decm_job" id="pros_decm_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('pros_decm_job', 0, $default_selected); ?><!-- name="pros_decm_job" id="pros_decm_job_05" value="0" type="radio">-->
<!--                                <div  class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td>
                        <strong>Building Trust:</strong> Candidate demonstrates ability to keep commitments and meet deadlines; exhibits integrity and honesty with colleagues and customers; demonstrates ability to be open to views of others; takes responsibility for own actions in a conflict resolution.
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['build_trst_cand']) ? $evaluation_form['build_trst_cand'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('build_trst_cand', 10, $default_selected); ?> name="build_trst_cand" id="build_trst_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('build_trst_cand', 7.5, $default_selected); ?> name="build_trst_cand" id="build_trst_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('build_trst_cand', 5, $default_selected); ?> name="build_trst_cand" id="build_trst_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('build_trst_cand', 2.5, $default_selected); ?> name="build_trst_cand" id="build_trst_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('build_trst_cand', 0, $default_selected); ?> name="build_trst_cand" id="build_trst_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['build_trst_job']) ? $evaluation_form['build_trst_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('build_trst_job', 10, $default_selected); ?><!-- name="build_trst_job" id="build_trst_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('build_trst_job', 7.5, $default_selected); ?><!-- name="build_trst_job" id="build_trst_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('build_trst_job', 5, $default_selected); ?><!-- name="build_trst_job" id="build_trst_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('build_trst_job', 2.5, $default_selected); ?><!-- name="build_trst_job" id="build_trst_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('build_trst_job', 0, $default_selected); ?><!-- name="build_trst_job" id="build_trst_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td>
                        <strong>Conflict Resolution:</strong> Candidate demonstrates ability to resolve conflict with person directly involved; demonstrates active listening skills; focuses on conflict resolution, not blame.
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['conf_res_cand']) ? $evaluation_form['conf_res_cand'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('conf_res_cand', 10, $default_selected); ?> name="conf_res_cand" id="conf_res_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('conf_res_cand', 7.5, $default_selected); ?> name="conf_res_cand" id="conf_res_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('conf_res_cand', 5, $default_selected); ?> name="conf_res_cand" id="conf_res_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('conf_res_cand', 2.5, $default_selected); ?> name="conf_res_cand" id="conf_res_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('conf_res_cand', 0, $default_selected); ?> name="conf_res_cand" id="conf_res_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['conf_res_job']) ? $evaluation_form['conf_res_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('conf_res_job', 10, $default_selected); ?><!-- name="conf_res_job" id="conf_res_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('conf_res_job', 7.5, $default_selected); ?><!-- name="conf_res_job" id="conf_res_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('conf_res_job', 5, $default_selected); ?><!-- name="conf_res_job" id="conf_res_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('conf_res_job', 2.5, $default_selected); ?><!-- name="conf_res_job" id="conf_res_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('conf_res_job', 0, $default_selected); ?><!-- name="conf_res_job" id="conf_res_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td>
                        <strong>Teamwork:</strong> Candidate demonstrates ability to work as part of a team; seeks the perspective and expertise of others; looks for opportunities to support others on team.
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['team_wrk_cand']) ? $evaluation_form['team_wrk_cand'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('team_wrk_cand', 10, $default_selected); ?> name="team_wrk_cand" id="team_wrk_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('team_wrk_cand', 7.5, $default_selected); ?> name="team_wrk_cand" id="team_wrk_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('team_wrk_cand', 5, $default_selected); ?> name="team_wrk_cand" id="team_wrk_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('team_wrk_cand', 2.5, $default_selected); ?> name="team_wrk_cand" id="team_wrk_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('team_wrk_cand', 0, $default_selected); ?> name="team_wrk_cand" id="team_wrk_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['team_wrk_job']) ? $evaluation_form['team_wrk_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('team_wrk_job', 10, $default_selected); ?><!-- name="team_wrk_job" id="team_wrk_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('team_wrk_job', 7.5, $default_selected); ?><!-- name="team_wrk_job" id="team_wrk_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('team_wrk_job', 5, $default_selected); ?><!-- name="team_wrk_job" id="team_wrk_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('team_wrk_job', 2.5, $default_selected); ?><!-- name="team_wrk_job" id="team_wrk_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('team_wrk_job', 0, $default_selected); ?><!-- name="team_wrk_job" id="team_wrk_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td>
                        <strong>Customer Service Oriented:</strong> Candidate demonstrates strong customer service orientation with the ability to provide clear consistent information and service; demonstrates ability to handle difficult customers; delivers service in a timely and professional way.
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['cs_orient_cand']) ? $evaluation_form['cs_orient_cand'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('cs_orient_cand', 10, $default_selected); ?> name="cs_orient_cand" id="cs_orient_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('cs_orient_cand', 7.5, $default_selected); ?> name="cs_orient_cand" id="cs_orient_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('cs_orient_cand', 5, $default_selected); ?> name="cs_orient_cand" id="cs_orient_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('cs_orient_cand', 2.5, $default_selected); ?> name="cs_orient_cand" id="cs_orient_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('cs_orient_cand', 0, $default_selected); ?> name="cs_orient_cand" id="cs_orient_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['cs_orient_job']) ? $evaluation_form['cs_orient_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('cs_orient_job', 10, $default_selected); ?><!-- name="cs_orient_job" id="cs_orient_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('cs_orient_job', 7.5, $default_selected); ?><!-- name="cs_orient_job" id="cs_orient_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('cs_orient_job', 5, $default_selected); ?><!-- name="cs_orient_job" id="cs_orient_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('cs_orient_job', 2.5, $default_selected); ?><!-- name="cs_orient_job" id="cs_orient_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('cs_orient_job', 0, $default_selected); ?><!-- name="cs_orient_job" id="cs_orient_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td>
                        <strong>Work Experience Rating:</strong> Does candidate possess experience directly related to the position?
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['work_exp_cand']) ? $evaluation_form['work_exp_cand'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('work_exp_cand', 10, $default_selected); ?> name="work_exp_cand" id="work_exp_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('work_exp_cand', 7.5, $default_selected); ?> name="work_exp_cand" id="work_exp_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('work_exp_cand', 5, $default_selected); ?> name="work_exp_cand" id="work_exp_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('work_exp_cand', 2.5, $default_selected); ?> name="work_exp_cand" id="work_exp_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('work_exp_cand', 0, $default_selected); ?> name="work_exp_cand" id="work_exp_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['work_exp_job']) ? $evaluation_form['work_exp_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('work_exp_job', 10, $default_selected); ?><!-- name="work_exp_job" id="work_exp_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('work_exp_job', 7.5, $default_selected); ?><!-- name="work_exp_job" id="work_exp_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('work_exp_job', 5, $default_selected); ?><!-- name="work_exp_job" id="work_exp_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('work_exp_job', 2.5, $default_selected); ?><!-- name="work_exp_job" id="work_exp_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('work_exp_job', 0, $default_selected); ?><!-- name="work_exp_job" id="work_exp_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>

                <tr>

                    <td >
                        <strong>Educational Background:</strong> Does the candidate have the appropriate educational qualifications
                        or training for this position?
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['edu_back_cand']) ? $evaluation_form['edu_back_cand'] : 0 ); ?>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('edu_back_cand', 10, $default_selected); ?> name="edu_back_cand" id="edu_back_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('edu_back_cand', 7.5, $default_selected); ?> name="edu_back_cand" id="edu_back_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('edu_back_cand', 5, $default_selected); ?> name="edu_back_cand" id="edu_back_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('edu_back_cand', 2.5, $default_selected); ?> name="edu_back_cand" id="edu_back_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('edu_back_cand', 0, $default_selected); ?> name="edu_back_cand" id="edu_back_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['edu_back_job']) ? $evaluation_form['edu_back_job'] : 0 ); ?>
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('edu_back_job', 10, $default_selected); ?><!-- name="edu_back_job" id="edu_back_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('edu_back_job', 7.5, $default_selected); ?><!-- name="edu_back_job" id="edu_back_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('edu_back_job', 5, $default_selected); ?><!-- name="edu_back_job" id="edu_back_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('edu_back_job', 2.5, $default_selected); ?><!-- name="edu_back_job" id="edu_back_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('edu_back_job', 0, $default_selected); ?><!-- name="edu_back_job" id="edu_back_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>

                <tr>

                    <td>
                        <strong>Technical Qualifications/Experience:</strong> Does the candidate have the technical skills necessary for this position?
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['tech_qual_cand']) ? $evaluation_form['tech_qual_cand'] : 0 ); ?>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('tech_qual_cand', 10, $default_selected); ?> name="tech_qual_cand" id="tech_qual_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('tech_qual_cand', 7.5, $default_selected); ?> name="tech_qual_cand" id="tech_qual_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('tech_qual_cand', 5, $default_selected); ?> name="tech_qual_cand" id="tech_qual_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('tech_qual_cand', 2.5, $default_selected); ?> name="tech_qual_cand" id="tech_qual_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('tech_qual_cand', 0, $default_selected); ?> name="tech_qual_cand" id="tech_qual_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['tech_qual_job']) ? $evaluation_form['tech_qual_job'] : 0 ); ?>
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('tech_qual_job', 10, $default_selected); ?><!-- name="tech_qual_job" id="tech_qual_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('tech_qual_job', 7.5, $default_selected); ?><!-- name="tech_qual_job" id="tech_qual_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('tech_qual_job', 5, $default_selected); ?><!-- name="tech_qual_job" id="tech_qual_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('tech_qual_job', 2.5, $default_selected); ?><!-- name="tech_qual_job" id="tech_qual_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('tech_qual_job', 0, $default_selected); ?><!-- name="tech_qual_job" id="tech_qual_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>

                <tr>
                    <td>
                        <strong>Commitment:</strong> In terms of career focus, client/service orientation, passion, work ethic, interest in the company and position, how would you rate the candidate?
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['commitment_cand']) ? $evaluation_form['commitment_cand'] : 0 ); ?>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('commitment_cand', 10, $default_selected); ?> name="commitment_cand" id="commitment_cand_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('commitment_cand', 7.5, $default_selected); ?> name="commitment_cand" id="commitment_cand_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('commitment_cand', 5, $default_selected); ?> name="commitment_cand" id="commitment_cand_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('commitment_cand', 2.5, $default_selected); ?> name="commitment_cand" id="commitment_cand_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('commitment_cand', 0, $default_selected); ?> name="commitment_cand" id="commitment_cand_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['commitment_job']) ? $evaluation_form['commitment_job'] : 0 ); ?>
<!--                        <div class="radio-row">-->
<!--                            <label class="control control--radio">-->
<!--                                --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('commitment_job', 10, $default_selected); ?><!-- name="commitment_job" id="commitment_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            <label class="control control--radio">-->
<!--                                --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('commitment_job', 7.5, $default_selected); ?><!-- name="commitment_job" id="commitment_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('commitment_job', 5, $default_selected); ?><!-- name="commitment_job" id="commitment_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('commitment_job', 2.5, $default_selected); ?><!-- name="commitment_job" id="commitment_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('commitment_job', 0, $default_selected); ?><!-- name="commitment_job" id="commitment_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td colspan="3">
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['cand_know_skill_abill_general']) ? $evaluation_form['cand_know_skill_abill_general'] : '' ); ?>

                        <label>Describe candidate's job knowledge, skills, and abilities (KSA's) as it relates to the position.</label>
                        <textarea class="invoice-fields-textarea" style="height: 100px;" name="cand_know_skill_abill_general" id="cand_know_skill_abill_general"><?php echo set_value('cand_know_skill_abill_general', $temp); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['cand_know_skill_abill_unique']) ? $evaluation_form['cand_know_skill_abill_unique'] : '' ); ?>

                        <label>Describe candidate's unique skills important for the position/department.</label>
                        <textarea class="invoice-fields-textarea" style="height: 100px;" name="cand_know_skill_abill_unique" id="cand_know_skill_abill_unique"><?php echo set_value('cand_know_skill_abill_unique', $temp); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Overall Assessment:</strong>
                    </td>
                    <td>
                        <?php $temp = ( isset($evaluation_form) && isset($evaluation_form['overall_assessment_candidate']) ? $evaluation_form['overall_assessment_candidate'] : 0 ); ?>

                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 10 ? true : false ); ?>
                            <label class="control control--radio">
                                Highly Recommended
                                <input <?php echo set_radio('overall_assessment_candidate', 10, $default_selected); ?> name="overall_assessment_candidate" id="overall_assessment_candidate_01" value="10" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 7.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Strong
                                <input <?php echo set_radio('overall_assessment_candidate', 7.5, $default_selected); ?> name="overall_assessment_candidate" id="overall_assessment_candidate_02" value="7.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 5 ? true : false ); ?>
                            <label class="control control--radio">
                                Average
                                <input <?php echo set_radio('overall_assessment_candidate', 5, $default_selected); ?> name="overall_assessment_candidate" id="overall_assessment_candidate_03" value="5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 2.5 ? true : false ); ?>
                            <label class="control control--radio">
                                Weak
                                <input <?php echo set_radio('overall_assessment_candidate', 2.5, $default_selected); ?> name="overall_assessment_candidate" id="overall_assessment_candidate_04" value="2.5" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                        <div class="radio-row">
                            <?php $default_selected = ( $temp == 0 ? true : false ); ?>
                            <label class="control control--radio">
                                Not Recommended
                                <input <?php echo set_radio('overall_assessment_candidate', 0, $default_selected); ?> name="overall_assessment_candidate" id="overall_assessment_candidate_05" value="0" type="radio">
                                <div class="control__indicator"></div>
                            </label>
                        </div>
                    </td>
<!--                    <td>-->
<!--                        --><?php //$temp = ( isset($evaluation_form) && isset($evaluation_form['overall_assessment_job']) ? $evaluation_form['overall_assessment_job'] : 0 ); ?>
<!---->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 10 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Highly Recommended-->
<!--                                <input --><?php //echo set_radio('overall_assessment_job', 10, $default_selected); ?><!-- name="overall_assessment_job" id="overall_assessment_job_01" value="10" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 7.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Strong-->
<!--                                <input --><?php //echo set_radio('overall_assessment_job', 7.5, $default_selected); ?><!-- name="overall_assessment_job" id="overall_assessment_job_02" value="7.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Average-->
<!--                                <input --><?php //echo set_radio('overall_assessment_job', 5, $default_selected); ?><!-- name="overall_assessment_job" id="overall_assessment_job_03" value="5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 2.5 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Weak-->
<!--                                <input --><?php //echo set_radio('overall_assessment_job', 2.5, $default_selected); ?><!-- name="overall_assessment_job" id="overall_assessment_job_04" value="2.5" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                        <div class="radio-row">-->
<!--                            --><?php //$default_selected = ( $temp == 0 ? true : false ); ?>
<!--                            <label class="control control--radio">-->
<!--                                Not Recommended-->
<!--                                <input --><?php //echo set_radio('overall_assessment_job', 0, $default_selected); ?><!-- name="overall_assessment_job" id="overall_assessment_job_05" value="0" type="radio">-->
<!--                                <div class="control__indicator"></div>-->
<!--                            </label>-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>


                </tbody>
            </table>
        </div>
        <hr />
        <div class="hr-innerpadding" style="padding-top: 0;">
            <div class="row">
                <div class="col-lg-6 col-md-7 col-xs-12 col-sm-12">
                    <div class="evaluation-comments">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Grade</th>
                                <th class="text-center">Score</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Highly Recommended</td>
                                <td class="text-center">10.0</td>
                            </tr>
                            <tr>
                                <td>Strong</td>
                                <td class="text-center">7.5</td>
                            </tr>
                            <tr>
                                <td>Average</td>
                                <td class="text-center">5.0</td>
                            </tr>
                            <tr>
                                <td>Weak</td>
                                <td class="text-center">2.5</td>
                            </tr>
                            <tr>
                                <td>Not Recommended</td>
                                <td class="text-center">0.0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-5 col-xs-12 col-sm-12">
                    <div class="question-row text-center">
                        <div class="start-rating">
                            <?php $score = ( isset($questionnaire_score) && isset($questionnaire_score['star_rating']) ? $questionnaire_score['star_rating'] : 0 )?>

                            <input name="star_rating" id="star_rating" value="<?php echo $score; ?>" type="number" class="rating" min="0" max="5" step="0.5" data-size="xs" readonly="readonly"/>
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-lg-8 col-md-8 col-xs-8 col-sm-8 text-left">
                                    <strong>Candidate Score</strong> <br /><small>( Out of 100 Points )</small>
                                </td>
                                <td class="col-lg-4 col-md-4 col-xs-4 col-sm-4">
                                    <span class="paid">
                                        <?php if($is_manage == 1 || $is_preview == 1) { ?>
                                            0
                                        <?php } else {?>
                                            <?php $score = ( isset($questionnaire_score) && isset($questionnaire_score['candidate_score']) ? $questionnaire_score['candidate_score'] : 0 )?>
                                            <?php echo $score; ?>
                                        <?php } ?>
                                    </span>
                                </td>
                            </tr>
<!--                            <tr>-->
<!--                                <td class="col-lg-8 col-md-8 col-xs-8 col-sm-8 text-left"><strong>Job Relevancy</strong> <br /><small>( Out of 100 Points )</small></td>-->
<!--                                <td class="col-lg-4 col-md-4 col-xs-4 col-sm-4">-->
<!--                                    <span class="paid">-->
<!--                                        --><?php //if($is_manage == 1 || $is_preview == 1) { ?>
<!--                                            0-->
<!--                                        --><?php //} else {?>
<!--                                            --><?php //$score = ( isset($questionnaire_score) && isset($questionnaire_score['job_relevancy_score']) ? $questionnaire_score['job_relevancy_score'] : 0 )?>
<!--                                            --><?php //echo $score; ?>
<!--                                        --><?php //} ?>
<!--                                    </span>-->
<!--                                </td>-->
<!--                            </tr>-->
                            <tr>
                                <td class="col-lg-8 col-md-8 col-xs-8 col-sm-8 text-left"><strong>Overall Assessment</strong> <br /><small>( Out of 100 Points )</small></td>
                                <td class="col-lg-4 col-md-4 col-xs-4 col-sm-4 paid">
                                    <span class="paid">
                                        <?php if($is_manage == 1 || $is_preview == 1) { ?>
                                            0
                                        <?php } else {?>
                                            <?php $score_candidate = ( (isset($questionnaire_score) && isset($questionnaire_score['candidate_overall_score'])) || ((isset($is_manage) && $is_manage == 1) || (isset($is_preview) && $is_preview == 1)) ? $questionnaire_score['candidate_overall_score'] : 0 )?>
                                            <?php $score_job_relevancy = ( (isset($questionnaire_score) && isset($questionnaire_score['job_relevancy_overall_score'])) || ((isset($is_manage) && $is_manage == 1) || (isset($is_preview) && $is_preview == 1)) ? $questionnaire_score['job_relevancy_overall_score'] : 0 )?>
                                            <?php echo $score_candidate * 10; ?>
<!--                                            --><?php //echo (($score_candidate + $score_job_relevancy) * 10) / 2; ?>
                                        <?php } ?>
                                    </span>
                                </td>
                            </tr>
                        </table>

                        <button id="calculate_btn" <?php echo ( isset($is_already_scored) && $is_already_scored == 1 ? 'disabled="disabled"' : '' ); ?> type="button" class="btn btn-block btn-success <?php echo ( isset($is_already_scored) && $is_already_scored == 1 ? 'disabled' : '' ); ?>" onclick="func_evaluate_and_calculate_score(<?php echo $questionnaire['sid']; ?>, <?php echo (isset($job_sid) ? $job_sid : 0);?>);">Evaluate and Calculate Score and Save</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait ...
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        func_hide_loader();
    });

    function func_hide_loader(){
        $('#file_loader').css("display", "none");
        $('.my_spinner').css("visibility", "hidden");
        $('.loader-text').css("display", "none");
        $('.my_loader').css("display", "none");
    }

    function func_show_loader(){
        $('#file_loader').css("display", "block");
        $('.my_spinner').css("visibility", "visible");
        $('.loader-text').css("display", "block");
        $('.my_loader').css("display", "block");
    }

    function func_evaluate_and_calculate_score(questionnaire_sid, job_sid){
        alertify.confirm(
            'Are you sure?',
            'Are you sure you want to Evaluate and Save Score for this Applicant, you will not be able make any changes after evaluation is done!',
            function() {
                var questionnaire_form = func_convert_form_to_json_object('form_questionnaire');
                var evaluation_form = func_convert_form_to_json_object('form_evaluation');

                questionnaire_form = JSON.stringify(questionnaire_form);
                evaluation_form = JSON.stringify(evaluation_form);

//                console.log(questionnaire_form);
//                console.log(evaluation_form);
//                return false;

                var data_to_send = {
                    'perform_action': 'calculate_and_evaluate_score',
                    'questionnaire_sid': questionnaire_sid,
                    'questionnaire_form': questionnaire_form,
                    'evaluation_form': evaluation_form,
                    'job_sid': job_sid
                };

                $('#calculate_btn').prop('disabled', true);
                $('#calculate_btn').addClass('disabled');

                func_show_loader();

                var my_request;
                my_request = $.ajax({
                    url: '<?php echo base_url("interview_questionnaire/ajax_responder"); ?>',
                    type: 'POST',
                    requestType: 'json',
                    data: data_to_send
                });

                my_request.done(function (response) {
                    func_hide_loader();
                    if(response.status == 'success'){
                        window.location.href = window.location.href;
                    } else {
                        alertify.error('Something Went Wrong!');
                    }
                });
            },
            function () {
                alertify.error('Cancelled');
            });

        /*


        */
    }

    $(document).ready(function() {
        <?php if($is_manage == 1 || $is_preview == 1 || $is_already_scored == 1) { ?>
            disable_editing();
        <?php } ?>
    });
</script>