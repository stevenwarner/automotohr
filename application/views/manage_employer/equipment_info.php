<?php if(!$load_view){ ?>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                <?php echo form_open('', array('id' => 'equipmentinfo')); ?>
                                <div class="universal-form-style-v2 equipment-types" id="types" style="display: none">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                            <label>Equipment Type</label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            <div class="hr-select-dropdown">
                                                <select id="equipments_type" name="equipment_type" class="invoice-fields">
                                                    <option>Please Select</option>    
                                                    <option <?php if (isset($equipment_info['equipment_type']) && $equipment_info['equipment_type'] == "cellphone_details") { ?> selected <?php } ?> value="cellphone_details">Cell Phones</option>    
                                                    <option <?php if (isset($equipment_info['equipment_type']) && $equipment_info['equipment_type'] == "laptops_details") { ?> selected <?php } ?> value="laptops_details">Laptops</option>
                                                    <option <?php if (isset($equipment_info['equipment_type']) && $equipment_info['equipment_type'] == "company_vehicles_details") { ?> selected <?php } ?> value="company_vehicles_details">Company vehicles</option>    
                                                    <option <?php if (isset($equipment_info['equipment_type']) && $equipment_info['equipment_type'] == "tablets_details") { ?> selected <?php } ?> value="tablets_details">Tablets</option>     
                                                    <option <?php if (isset($equipment_info['equipment_type']) && $equipment_info['equipment_type'] == "other1_details") { ?> selected <?php } ?> value="other1_details">Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <a id="types-back" href="javascript:;" class="btn btn-success text-right"><i class="fa fa-chevron-left"></i> Back</a><!--<input type="button" id="types-back" class="btn btn-success text-right" value="Back">-->
                                        </div>
                                    </div>
                                </div>
                                <div class="universal-form-style-v2 equipment-types" id="new-equip">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <input type="button" class="btn btn-success text-right" value="+ Add New Equipment">
                                        </div>
                                    </div>
                                </div>

                                <div class="universal-form-style-v2 equipment-types" id="equipment-table">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="hr-box">
                                                <div class="hr-box-header">
                                                    Assigned Equipments
                                                </div>
                                                <div class="hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <table class="table table-condensed table-bordered table-hover table-striped">
                                                            <thead>
                                                            <tr>
                                                                <th class="col-xs-3">Name</th>
                                                                <th class="col-xs-3">Type</th>
                                                                <th class="col-xs-3">Issue Date</th>
                                                                <th class="col-xs-3 text-center" colspan="2">Actions</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(!empty($equipment_info)) { ?>
                                                                <?php foreach($equipment_info as $equipment) {
                                                                    $unserialized = @unserialize($equipment['equipment_details']);
                                                                    $default_type = sizeof($unserialized) > 0 ? $unserialized['equipment_type'] : ''; ?>
                                                                    <tr>
                                                                        <td> <?php echo $equipment['brand_name']; ?> </td>
                                                                        <td> <?php echo !empty($equipment['equipment_type']) ? $equipment['equipment_type'] : 'N/A' ; ?> </td>
                                                                        <td> <?php echo !empty($equipment['issue_date']) && $equipment['issue_date'] != NULL && $equipment['issue_date'] != '0000-00-00 00:00:00' ? reset_datetime(array( 'datetime' => $equipment['issue_date'], '_this' => $this)) : 'N/A'; ?> </td>
                                                                        <td>
                                                                            <input type="button" class="btn btn-success btn-sm btn-block" value="View/Edit" onclick="showViewEdit(this);" data-equ-type="<?php echo $equipment['equipment_type']; ?>"
                                                                                    data-sid="<?php echo $equipment['sid']; ?>"
                                                                                    data-brand-name="<?php echo $equipment['brand_name']; ?>"
                                                                                    data-imei-no="<?php echo $equipment['imei_no']; ?>"
                                                                                    data-model="<?php echo $equipment['model']; ?>"
                                                                                    data-issue-date="<?php echo date('m-d-Y',strtotime($equipment['issue_date'])); ?>"
                                                                                    data-color="<?php echo $equipment['color']; ?>"
                                                                                    data-notes="<?php echo $equipment['notes']; ?>"
                                                                                    data-product-id="<?php echo $equipment['product_id']; ?>"
                                                                                    data-specification="<?php echo $equipment['specification']; ?>"
                                                                                    data-vin-number="<?php echo $equipment['vin_number']; ?>"
                                                                                    data-transmission-type="<?php echo $equipment['transmission_type']; ?>"
                                                                                    data-fuel-type="<?php echo $equipment['fuel_type']; ?>"
                                                                                    data-serial-number="<?php echo $equipment['serial_number']; ?>"
                                                                                    data-serialized='<?php print_r(json_encode($unserialized)); ?>'>
                                                                        </td>
                                                                        <td>
                                                                            <input type="button" class="btn btn-danger btn-sm btn-block"
                                                                                    onclick="deleteEquip(this);"
                                                                                    data-sid="<?php echo $equipment['sid']; ?>" value="Delete">
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td colspan="4" class="text-center">
                                                                        <span class="no-data">No Equipment Assigned</span>
                                                                    </td>
                                                                </tr>
                                                            <?php }?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="cellphone_details" class="universal-form-style-v2 equipment-details">
                                    <div class="tagline-heading"><h4>Cellphone Details</h4></div>
                                    <ul>
                                        <li class="form-col-50-left">
                                            <label>Company/Brand Name</label>
                                            <input type="text" class="invoice-fields" value="" name="cellphone_brand_name" id="cellphone_brand_name" >
                                            <?php echo form_error('cellphone_brand_name'); ?>
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>IMEI No</label>
                                            <input type="text" class="invoice-fields" value="" name="cellphone_imei_no" id="cellphone_imei_no">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Model</label>
                                            <input type="text" class="invoice-fields" value="" name="cellphone_model" id="cellphone_model">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Issue Date</label>
                                            <input type="text" readonly class="eventdate invoice-fields" value="" name="cellphone_issue_date" id="cellphone_issue_date">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Color</label>
                                            <div class='input-group colorcustompicker'>
                                                <input type='text' class="invoice-fields" value="#ffffff" name="cellphone_color" id="cellphone_color" data-rule-required='true'>
                                                <span class='input-group-addon'><i></i></span>
                                            </div>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <label>Notes</label>
                                            <textarea class="invoice-fields" name="cellphone_notes" id="cellphone_notes" style="height:200px; padding:10px;"></textarea>
                                        </li>
                                        <div class="btn-panel">
                                            <input type="submit" id="cell-submit" class="submit-btn" value="Save">
                                        </div>
                                    </ul>
                                </div>
                                <div id="laptops_details" class="universal-form-style-v2 equipment-details">
                                    <div class="tagline-heading"><h4>Laptop Details</h4></div>
                                    <ul>
                                        <li class="form-col-50-left">
                                            <label>Company/Brand Name</label>
                                            <input type="text" class="invoice-fields" value="" name="laptop_brand_name" id="laptop_brand_name">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Product ID</label>
                                            <input type="text" class="invoice-fields" value="" name="laptop_product_id" id="laptop_product_id">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Model</label>
                                            <input type="text" class="invoice-fields" value="" name="laptop_model" id="laptop_model">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Issue Date</label>
                                            <input type="text" readonly class="eventdate invoice-fields" value="" name="laptop_issue_date" id="laptop_issue_date">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Color</label>
                                            <div class='input-group colorcustompicker'>
                                                <input type='text' class="invoice-fields" value="#ffffff" name="laptop_color" id="laptop_color" data-rule-required='true'>
                                                <span class='input-group-addon'><i></i></span>
                                            </div>
<!--                                            <input type="text" class="invoice-fields" value="" name="laptop_color" id="laptop_color">-->
                                        </li>                                        
                                        <li class="form-col-50-right">
                                            <label>Specification</label>
                                            <input type="text" class="invoice-fields" value="" name="laptop_specification" id="laptop_specification">
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <label>Notes</label>
                                            <textarea class="invoice-fields" name="laptop_notes" id="laptop_notes" style="height:200px; padding:10px;"></textarea>
                                        </li>
                                        <div class="btn-panel">
                                            <input type="submit" id="laptop-submit" class="submit-btn" value="Save">
                                        </div>
                                    </ul>
                                </div>
                                <div id="company_vehicles_details" class="universal-form-style-v2 equipment-details">
                                    <div class="tagline-heading"><h4>Company vehicles Details</h4></div>
                                    <ul>
                                        <li class="form-col-50-left">
                                            <label>Company/Brand Name</label>
                                            <input type="text" class="invoice-fields" value="" name="vehicles_brand_name" id="vehicles_brand_name">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Vin number</label>
                                            <input type="text" class="invoice-fields" value="" name="vehicles_engine_no" id="vehicles_engine_no">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Model</label>
                                            <input type="text" class="invoice-fields" value="" name="vehicles_model" id="vehicles_model">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Issue Date</label>
                                            <input type="text" readonly class="eventdate invoice-fields" value="" name="vehicles_issue_date" id="vehicles_issue_date">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Color</label>
                                            <div class='input-group colorcustompicker'>
                                                <input type='text' class="invoice-fields" value="#ffffff" name="vehicles_color" id="vehicles_color" data-rule-required='true'>
                                                <span class='input-group-addon'><i></i></span>
                                            </div>
                                        </li>                                        
                                        <li class="form-col-50-right">
                                            <label>Transmission Type</label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="vehicles_transmisssion_type" id="vehicles_transmisssion_type">
                                                    <option value="Automatic">Automatic</option>
                                                    <option value="Manual">Manual</option>
                                                </select>
                                            </div>
                                        </li>                                                                                
                                        <li class="form-col-50-left">
                                            <label>Fuel Type</label>
                                            <div class="hr-select-dropdown">
                                                <select class="invoice-fields" name="fuel_type" id="fuel_type">
                                                    <option value="Patrol" >Gas</option>
                                                    <option value="Diesel" >Diesel</option>
                                                </select>
                                            </div>
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <label>Notes</label>
                                            <textarea class="invoice-fields" name="vehicles_notes" id="vehicles_notes" style="height:200px; padding:10px;"></textarea>
                                        </li>
                                        <div class="btn-panel">
                                            <input type="submit" id="company_submit" class="submit-btn" value="Save">
                                        </div>
                                    </ul>
                                </div>
                                <div id="tablets_details" class="universal-form-style-v2 equipment-details">
                                    <div class="tagline-heading"><h4>Tablets Details</h4></div>
                                    <ul>
                                        <li class="form-col-50-left">
                                            <label>Company/Brand Name</label>
                                            <input type="text" class="invoice-fields" value="" name="tablets_brand_name" id="tablets_brand_name">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Serial No</label>
                                            <input type="text" class="invoice-fields" value="" name="tablets_serial_no" id="tablets_serial_no">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Model</label>
                                            <input type="text" class="invoice-fields" value="" name="tablets_model" id="tablets_model">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Issue Date</label>
                                            <input type="text" readonly class="eventdate invoice-fields" value="" name="tablets_issue_date" id="tablets_issue_date">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Color</label>
                                            <div class='input-group colorcustompicker'>
                                                <input type='text' class="invoice-fields" value="#ffffff" name="tablets_color" id="tablets_color" data-rule-required='true'>
                                                <span class='input-group-addon'><i></i></span>
                                            </div>
                                        </li>                                        
                                        <li class="form-col-50-right">
                                            <label>Specification</label>
                                            <input type="text" class="invoice-fields" value="" name="tablets_specification" id="tablets_specification">
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <label>Notes</label>
                                            <textarea class="invoice-fields" name="tablets_notes" id="tablets_notes" style="height:200px; padding:10px;"></textarea>
                                        </li>
                                        <div class="btn-panel">
                                            <input type="submit" id="tablets-submit" class="submit-btn" value="Save">
                                        </div>
                                    </ul>
                                </div>
                                <div id="other1_details" class="universal-form-style-v2 equipment-details">
                                    <div class="tagline-heading"><h4>Other Details</h4></div>
                                    <ul>
                                        <li class="form-col-50-left">
                                            <label>Company/Brand Name</label>
                                            <input type="text" class="invoice-fields" value="" name="other1_brand_name" id="other1_brand_name">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Serial No</label>
                                            <input type="text" class="invoice-fields" value="" name="other1_serial_no" id="other1_serial_no">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Model</label>
                                            <input type="text" class="invoice-fields" value="" name="other1_model" id="other1_model">
                                        </li>
                                        <li class="form-col-50-right">
                                            <label>Issue Date</label>
                                            <input type="text" readonly class="eventdate invoice-fields" value="" name="other1_issue_date" id="other1_issue_date">
                                        </li>
                                        <li class="form-col-50-left">
                                            <label>Color</label>
                                            <div class='input-group colorcustompicker'>
                                                <input type='text' class="invoice-fields" value="#ffffff" name="other1_color" id="other1_color" data-rule-required='true'>
                                                <span class='input-group-addon'><i></i></span>
                                            </div>
                                        </li>                                        
                                        <li class="form-col-50-right">
                                            <label>Specification</label>
                                            <input type="text" class="invoice-fields" value="" name="other1_specification" id="other1_specification">
                                        </li>
                                        <li class="form-col-100 autoheight">
                                            <label>Notes</label>
                                            <textarea class="invoice-fields" name="other1_notes" id="other1_notes" style="height:200px; padding:10px;"></textarea>
                                        </li>
                                        <div class="btn-panel">
                                            <input type="submit" id="other-submit" class="submit-btn" value="Save">
                                        </div>
                                    </ul>
                                </div>
                                <input type="hidden" id="action_flag" name="action_flag" value="add">
                                <input type="hidden" id="action_sid" name="action_sid" value="0">
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<?php } else {
    $this->load->view('onboarding/required_equipment');
} ?>

<script type="text/javascript" src="<?= base_url('assets/js/bootstrap-colorpicker.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-colorpicker.min.css') ?>" />

<script type="text/javascript">
    $(document).ready(function () {
        $('.colorcustompicker').colorpicker();
        $('.eventdate').datepicker({dateFormat: 'mm-dd-yy'}).val();
        $("#equipments_type").change(function () {
            $(this).find("option:selected").each(function () {

                if ($(this).attr("value") == "cellphone_details") {
                    $(".equipment-details").not("#cellphone_details").hide();
                    $("#cellphone_details").show();
                    $(".no-equipment-select").hide();
                } else if ($(this).attr("value") == "laptops_details") {
                    $(".equipment-details").not("#laptops_details").hide();
                    $("#laptops_details").show();
                    $(".no-equipment-select").hide();
                } else if ($(this).attr("value") == "company_vehicles_details") {
                    $(".equipment-details").not("#company_vehicles_details").hide();
                    $("#company_vehicles_details").show();
                    $(".no-equipment-select").hide();
                } else if ($(this).attr("value") == "tablets_details") {
                    $(".equipment-details").not("#tablets_details").hide();
                    $("#tablets_details").show();
                    $(".no-equipment-select").hide();
                } else if ($(this).attr("value") == "other1_details") {
                    $(".equipment-details").not("#other1_details").hide();
                    $("#other1_details").show();
                    $(".no-equipment-select").hide();
                } else {
                    $(".equipment-details").hide();
                    $(".no-equipment-select").show();
                }
            });
        }).change();

        $('#new-equip').click(function(){
            $('#types').show();
            $('#new-equip').hide();
            $('#equipment-table').hide();
            $('#action_flag').val('add');
            $('#equipmentinfo').trigger('reset');
        });
        
        $('#types-back').click(function(){
            $('#types').toggle();
            $('#new-equip').toggle();
            $('#equipment-table').toggle();
            $('.equipment-details').hide();
        });

<?php if (isset($equipment_info['equipment_type'])) { ?>
            $(".no-equipment-select").hide();
            lastOn = "<?php echo $equipment_info['equipment_type']; ?>";
            $(".equipment-details").not("#" + lastOn).hide();
            $("#" + lastOn).show();
<?php } ?>
    });
    
    function showViewEdit(event){
        var serialized = JSON.parse($(event).attr('data-serialized'));
        var equ_type = $(event).attr('data-equ-type');
        var sid = $(event).attr('data-sid');
        $('#action_flag').val('edit');
        $('#action_sid').val(sid);
        $('#new-equip').hide();
        $('#equipment-table').hide();
        $('#types').show();

        if(equ_type == 'cellphone') {
            $('#equipments_type').val('cellphone_details');
            $('#cellphone_details').show();
            $('#cellphone_brand_name').val($(event).attr('data-brand-name'));
            $('#cellphone_imei_no').val($(event).attr('data-imei-no'));
            $('#cellphone_model').val($(event).attr('data-model'));
            $('#cellphone_issue_date').val($(event).attr('data-issue-date'));
            $('#cellphone_color').val($(event).attr('data-color'));
            $('#cellphone_notes').html($(event).attr('data-notes'));
        } else if(equ_type == 'laptop') {
            $('#equipments_type').val('laptops_details');
            $('#laptops_details').show();
            $('#laptop_brand_name').val($(event).attr('data-brand-name'));
            $('#laptop_product_id').val($(event).attr('data-product-id'));
            $('#laptop_model').val($(event).attr('data-model'));
            $('#laptop_issue_date').val($(event).attr('data-issue-date'));
            $('#laptop_color').val($(event).attr('data-color'));
            $('#laptop_specification').val($(event).attr('data-specification'));
            $('#laptop_notes').html($(event).attr('data-notes'));
        } else if(equ_type == 'vehicle') {
            $('#equipments_type').val('company_vehicles_details');
            $('#company_vehicles_details').show();
            $('#vehicles_brand_name').val($(event).attr('data-brand-name'));
            $('#vehicles_engine_no').val($(event).attr('data-vin-number'));
            $('#vehicles_model').val($(event).attr['data-model']);
            $('#vehicles_issue_date').val($(event).attr('data-issue-date'));
            $('#vehicles_color').val($(event).attr('data-color'));
            $('#vehicles_transmisssion_type').val($(event).attr('data-transmission-type'));
            $('#fuel_type').val($(event).attr('data-fuel-type'));
            $('#vehicles_notes').html($(event).attr('data-notes'));
        } else if(equ_type == 'tablet') {
            $('#equipments_type').val('tablets_details');
            $('#tablets_details').show();
            $('#tablets_brand_name').val($(event).attr('data-brand-name'));
            $('#tablets_serial_no').val($(event).attr('data-serial-number'));
            $('#tablets_model').val($(event).attr('data-model'));
            $('#tablets_issue_date').val($(event).attr('data-issue-date'));
            $('#tablets_color').val($(event).attr('data-color'));
            $('#tablets_specification').val($(event).attr('data-specification'));
            $('#tablets_notes').html($(event).attr('data-notes'));
        } else if(equ_type == 'other') {
            $('#equipments_type').val('other1_details');
            $('#other1_details').show();
            $('#other1_brand_name').val($(event).attr('data-brand-name'));
            $('#other1_serial_no').val($(event).attr('data-serial-number'));
            $('#other1_model').val($(event).attr('data-model'));
            $('#other1_issue_date').val($(event).attr('data-issue-date'));
            $('#other1_color').val($(event).attr('data-color'));
            $('#other1_specification').val($(event).attr('data-specification'));
            $('#other1_notes').html($(event).attr('data-notes'));
        }
        
        $('.colorcustompicker').colorpicker();
    }

    function deleteEquip(event){
        var sid = $(event).attr('data-sid');
        alertify.confirm('Confirmation', "Are you sure you want to Delete this Record?",
            function () {
                $.ajax({
                    type: 'POST',
                    data:{
                        sid: sid
                    },
                    url: '<?= base_url('settings/delete_equipment')?>',
                    success: function(data){
                        window.location.href = '<?php echo current_url()?>';
                    },
                    error: function(){

                    }
                });
            },
            function () {
                alertify.error('Canceled');
            });
    }
</script>