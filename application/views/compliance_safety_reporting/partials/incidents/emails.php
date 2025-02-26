   
   <div class="panel panel-default">
        <div class="panel-heading incident-panal-heading">
            <strong>Compose Message</strong>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div class="dashboard-conetnt-wrp">
                        <div class="table-responsive table-outer">
                            <div class="table-wrp data-table">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <form id="form_new_email" enctype="multipart/form-data" method="post" action="" autocomplete="off">
                                        <input type="hidden" id="perform_action" name="perform_action" value="send_email" />
                                        <table class="table table-bordered table-hover table-stripped">
                                            <tbody>
                                                <tr>
                                                    <td><b>Select Email Type</b></td>
                                                    <td>
                                                        <div class="form-group edit_filter autoheight">
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                Internal System Email
                                                                <input <?php echo !empty($incident_assigned_managers) ? 'checked="checked"' : ''; ?> name="send_type" class="email_type" type="radio" value="system" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                            <label class="control control--radio" style="margin-left:10px; margin-top:10px;">
                                                                Outside Email
                                                                <input <?php echo empty($incident_assigned_managers) ? 'checked="checked"' : ''; ?> class="email_type" name="send_type" type="radio" value="manual" />
                                                                <div class="control__indicator"></div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Message To</b> ;</td>
                                                    <td id="system_email">
                                                        <select multiple class="chosen-select" tabindex="8" name='receivers[]' id="receivers">
                                                            <?php if (!empty($incident_assigned_managers)) { ?>
                                                                <?php foreach ($incident_assigned_managers as $manager) { ?>
                                                                    <option value="<?php echo $manager['employee_id']; ?>">
                                                                        <?php echo $manager['employee_name']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option value="">No User Found</option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td id="manual_email">
                                                        <input type="text" name="manual_email" id="manual_address" value="" class="form-control invoice-fields">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Subject</b> <span class="required">*</span></td>
                                                    <td>
                                                        <input type="text" id="subject" name="subject" value="" class="form-control invoice-fields">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Attachment</b></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <a href="javascript:;" class="btn btn-info btn-block show_media_library">Add Library Attachment</a>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                <a href="javascript:;" class="btn btn-info btn-block show_manual_attachment">Add Manual Attachment</a>
                                                            </div>
                                                        </div>

                                                        <div class="table-responsive table-outer full-width" style="margin-top: 20px; display: none;" id="email_attachment_list">
                                                            <div class="table-wrp data-table">
                                                                <table class="table table-bordered table-hover table-stripped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="text-center">Attachment Title</th>
                                                                            <th class="text-center">Attachment Type</th>
                                                                            <th class="text-center">Attachment Source</th>
                                                                            <th class="text-center">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="attachment_listing_data">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div style="display: none;" id="email_attachment_files">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Message</b> <span class="required">*</span></td>
                                                    <td>
                                                        <textarea class="ckeditor" style="padding:5px; height:200px; width:100%;" class="invoice-fields" name="message" id="message"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="btn-wrp full-width text-right">
                                                            <button type="button" class="btn btn-info incident-panal-button" name="submit" value="submit" id="send_normal_email">Send Email</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>