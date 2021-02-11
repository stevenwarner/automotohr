<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <?php if(isset($note_data['note_text'])){ ?>
                                            <h1 class="page-title"><i class="fa fa-users"></i>Edit Note</h1>
                                        <?php } else { ?>
                                            <h1 class="page-title"><i class="fa fa-users"></i>Add New Note</h1>
                                        <?php } ?>
                                    </div>
                                    <div class="add-new-company">
                                        <form action="" method="POST">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <div class="field-row field-row-autoheight">
                                                        <?php
                                                            $note_text = '';
                                                            if(isset($note_data['note_text'])){
                                                                $note_text = $note_data['note_text'];
                                                            }
                                                        ?>
                                                        <label for="note">Note</label>
                                                        <script type="text/javascript" src="<?php echo site_url('assets/ckeditor/ckeditor.js'); ?>"></script>
                                                        <textarea class="ckeditor" name="note" rows="8" cols="60" >
                                                            <?php echo set_value('note', $note_text); ?>
                                                        </textarea>
                                                        <?php echo form_error('note'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                    <?php

                                                    $is_default_general = false;
                                                    $is_default_billing = false;
                                                    if(isset($note_data['note_type'])){
                                                        if($note_data['note_type'] == 'billing'){
                                                            $is_default_billing = true;
                                                        }elseif($note_data['note_type'] == 'general'){
                                                            $is_default_general = true;
                                                        }
                                                    }

                                                    ?>
                                                    <div class="field-row field-row-autoheight">
                                                        <label>Note Type</label>
                                                        <br />
                                                        <input type="radio" value="general" name="note_type" id="note_type_general" <?php echo set_radio('note_type', 'general', $is_default_general); ?> />
                                                        &nbsp;<label for="note_type_general">General</label>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <input type="radio" value="billing" name="note_type" id="note_type_billing" <?php echo set_radio('note_type', 'billing', $is_default_billing); ?> />
                                                        &nbsp;<label for="note_type_billing">Billing</label>
                                                    </div>
                                                    <?php echo form_error('note_type'); ?>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                    <button type="submit" class="search-btn">Save</button>
                                                </div>
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
    </div>
</div>

<script>

</script>