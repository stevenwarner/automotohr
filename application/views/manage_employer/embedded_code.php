<div class="main-content">
    <div class="container-fluid">
        <div class="row">					
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <header class="hr-header-sec">
                    <h2><?php echo $title; ?></h2>
                </header>
            </div>
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">				
                <?php $this->load->view('manage_employer/profile_left_menu'); ?>
            </div>
            <?php echo form_open_multipart('', array('id' => 'embeddedcode')); ?>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <div class="universal-form-style">
                    <ul>
                        <li class="form-col-100 autoheight">
                            <div class="form-col-50-left">
                            <?php echo form_label('Embedded Code','embedded_code'); ?>
                            <?php $data_embedded_code = array(  'name'        => 'embedded_code',
                                                                'id'          => 'embedded_code',
                                                                'value'       => $company['embedded_code'],
                                                                'rows'        => '5',
                                                                'cols'        => '10',
                                                                'class'       => 'invoice-fields-textarea',
                                                            );
                                    echo form_textarea($data_embedded_code); ?>
                            <?php   echo form_error('embedded_code'); ?>
                            </div>
                            <div class="form-col-50-right">
                                <span class="help_text">
                                    <p>You can sign up for Google Analytics to track the activity on your Company Career Pages.</p>
                                    <p>For more details: <a class="more-detail-link" href="https://support.google.com/analytics/?hl=en#topic=3544906" target="_blank">Click Here</a></p>
                                    <p>Simply Copy and Paste the script tag from Google Analytics into the embedded code area and press Apply.</p>
                                </span>
                            </div>
                        </li>
                        <li class="form-col-100">
                            <div class="btn-wrp">
                                <input type="hidden" name="id" value="<?php echo $company['user_sid'];?>">
                                <input class="reg-btn" type="submit" onclick="validate_form()" value="save">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
</div>