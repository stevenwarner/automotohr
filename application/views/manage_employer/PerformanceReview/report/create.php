<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/PerformanceReview/sidebar'); ?>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="right-content">
                        <!-- Header -->
                        <?php $this->load->view('manage_employer/PerformanceReview/headerBar', [
                            'Link' => [
                                base_url('performance/view'),
                                'Performance Review - View',
                            ],
                            'Text' => 'Performance Review - Add'
                        ]); ?>

                        <div class="clearfix"></div>

                        <!-- Table -->
                        <div class="cs-prpage" style="margin-top: 0;">
                            <!-- Loader -->
                            <div class="cs-loader">
                                <i class="fa fa-circle-o-notch fa-spin"></i>
                            </div>

                            <!-- Step 1  -->
                            <div class="js-template-step" data-step="1">
                                <form id="js-template-add-form">
                                    <div class="form-group">
                                        <label>Template Name <span class="cs-required">*</span> </label>
                                        <div>
                                            <input type="text" class="form-control" id="js-template-title" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description </label>
                                        <div>
                                            <textarea id="js-template-description"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <br />
                                        <label>Status </label>
                                        <div>
                                            <select id="js-template-status">
                                                <option value="0">InActive</option>
                                                <option value="1">Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div>
                                            <input type="submit" class="btn btn-success" value="Next" />
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Step 2 -->
                            <div class="js-template-step cs-hider" data-step="2">
                                <div>
                                    <button class="btn btn-success js-add-save-template" disabled>Save Template</button>
                                    <button class="btn btn-default js-view-template">Back</button>
                                    <span class="pull-right">
                                        <button class="btn btn-success js-add-question">
                                            <i class="fa fa-plus"></i>&nbsp; Add Question
                                        </button>
                                    </span>
                                </div>
                                <div class="table-responsive table-outer">
                                    <br />
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <th>Question</th>
                                            <th class="col-sm-2">Type</th>
                                            <th class="col-sm-2">Actions</th>
                                        </thead>
                                        <tbody id="js-data-area">
                                            <tr>
                                                <td colspan="3">
                                                    <p class="alert alert-info text-center">No questions Added</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="js-template-step cs-hider" data-step="3">
                                <h4>Question <span id="js-add-question-number">1</span></h4>
                                <form id="js-add-question-submit">
                                    <div class="form-group">
                                        <label>Question <span class="cs-required">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" id="js-add-question-text" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <div>
                                            <input type="text" class="form-control" id="js-add-question-description" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label>Question Type<span class="cs-required">*</span></label>
                                        <div>
                                            <select id="js-add-question-type">
                                                <option value="text">Text Box</option>
                                                <option value="rating">Rating Scale</option>
                                                <option value="text-rating">Rating Scale and Text Rating</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group js-add-rating-box">
                                        <label>Rating Scale <span class="cs-required">*</span></label>
                                        <div>
                                            <select id="js-add-rating-scale">
                                                <option value="0">[Select Rating Scale]</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label>Options</label>
                                        <div>
                                            <div class="control control--checkbox js-add-use-label-box">
                                                <label>
                                                    <input type="checkbox" name="include_ws" id="js-add-use-label" />
                                                    Use Labels
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <br />

                                            <div class="control control--checkbox">
                                                <label>
                                                    <input type="checkbox" name="include_na" id="js-add-include-na" />
                                                    Include N/A
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="form-group js-add-rating-list-box">
                                    </div>

                                    <hr />

                                    <!--  -->
                                    <div class="form-group">
                                        <input type="submit" value="Save Question" class="btn btn-success" />
                                        <input type="button" value="Cancel" class="btn btn-default js-question-cancel" />
                                    </div>
                                </form>
                            </div>

                            <!-- Step 4 -->
                            <div class="js-template-step cs-hider" data-step="4">
                                <h4>Question <span id="js-edit-question-number">1</span></h4>
                                <form id="js-edit-question-submit">
                                    <div class="form-group">
                                        <label>Question <span class="cs-required">*</span></label>
                                        <div>
                                            <input type="text" class="form-control" id="js-edit-question-text" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Description</label>
                                        <div>
                                            <input type="text" class="form-control" id="js-edit-question-description" />
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label>Question Type<span class="cs-required">*</span></label>
                                        <div>
                                            <select id="js-edit-question-type">
                                                <option value="text">Text Box</option>
                                                <option value="rating">Rating Scale</option>
                                                <option value="text-rating">Rating Scale and Text Rating</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group js-edit-rating-box">
                                        <label>Rating Scale <span class="cs-required">*</span></label>
                                        <div>
                                            <select id="js-edit-rating-scale">
                                                <option value="0">[Select Rating Scale]</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label>Options</label>
                                        <div>
                                            <div class="control control--checkbox js-edit-use-label-box">
                                                <label>
                                                    <input type="checkbox" name="include_ws" id="js-edit-use-label" />
                                                    Use Labels
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                            <br />

                                            <div class="control control--checkbox">
                                                <label>
                                                    <input type="checkbox" name="include_na" id="js-edit-include-na" />
                                                    Include N/A
                                                    <div class="control__indicator"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <div class="form-group js-edit-rating-list-box">
                                    </div>

                                    <hr />

                                    <!--  -->
                                    <div class="form-group">
                                        <input type="submit" value="Save Question" class="btn btn-success" />
                                        <input type="button" value="Cancel" class="btn btn-default js-edit-question-cancel" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    <?php $this->load->view("manage_employer/PerformanceReview/main.css"); ?>
</style>
<script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script>
    <?php $this->load->view('manage_employer/PerformanceReview/template/scripts/create'); ?>
</script>