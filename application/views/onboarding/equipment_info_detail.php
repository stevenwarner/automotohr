<?php
    $company_sid = 0;
    $users_type = '';
    $users_sid = 0;
   ?>
<div class="main">
    <div class="container">
        <div class="row">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    </div>
                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <div class="page-header">
                    <h2 class="section-ttile">Equipment Detail</h2>
                </div>
                <div id="cellphone_details" class="panel panel-primary">
                    <div class="panel-heading stev_blue">
                        <strong>Assign Equipment Type: Cellphone</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Company/Brand Name</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['brand_name']) && !empty($equipment_info['brand_name']) ? $equipment_info['brand_name'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">IMEI No</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['imei_no']) && !empty($equipment_info['imei_no']) ? $equipment_info['imei_no'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Model</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['model']) && !empty($equipment_info['model']) ? $equipment_info['model'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Issue Date</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['issue_date']) && !empty($equipment_info['issue_date']) ? date("m-d-Y", strtotime($equipment_info['issue_date'])) : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Color</td>
                                    <?php
                                        $color = isset($equipment_info['color']) && !empty($equipment_info['color']) ? $equipment_info['color'] : '';   
                                    ?>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8" style="background-color: <?php echo $color; ?>">
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Notes</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['notes']) && !empty($equipment_info['notes']) ? $equipment_info['notes'] : 'NIL'; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="laptops_details" class="panel panel-primary">
                    <div class="panel-heading stev_blue">
                        <strong>Assign Equipment Type: Laptop</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Company/Brand Name</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['brand_name']) && !empty($equipment_info['brand_name']) ? $equipment_info['brand_name'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Product ID</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['product_id']) && !empty($equipment_info['product_id']) ? $equipment_info['product_id'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Model</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['model']) && !empty($equipment_info['model']) ? $equipment_info['model'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Issue Date</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['issue_date']) && !empty($equipment_info['issue_date']) ? date("m-d-Y", strtotime($equipment_info['issue_date'])) : 'NIL'; ?>
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Specification</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['specification']) && !empty($equipment_info['specification']) ?$equipment_info['specification'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Color</td>
                                    <?php
                                        $color = isset($equipment_info['color']) && !empty($equipment_info['color']) ? $equipment_info['color'] : '';   
                                    ?>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8" style="background-color: <?php echo $color; ?>">
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Notes</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['notes']) && !empty($equipment_info['notes']) ? $equipment_info['notes'] : 'NIL'; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="company_vehicles_details" class="panel panel-primary">
                    <div class="panel-heading stev_blue">
                        <strong>Assign Equipment Type: Company vehicle</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Company/Brand Name</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['brand_name']) && !empty($equipment_info['brand_name']) ? $equipment_info['brand_name'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Vin number</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['vin_number']) && !empty($equipment_info['vin_number']) ? $equipment_info['vin_number'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Model</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['model']) && !empty($equipment_info['model']) ? $equipment_info['model'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Issue Date</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['issue_date']) && !empty($equipment_info['issue_date']) ? date("m-d-Y", strtotime($equipment_info['issue_date'])) : 'NIL'; ?>
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Transmission Type</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['transmission_type']) && !empty($equipment_info['transmission_type']) ? $equipment_info['transmission_type'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Fuel Type</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['fuel_type']) && !empty($equipment_info['fuel_type']) ? $equipment_info['fuel_type'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Color</td>
                                    <?php
                                        $color = isset($equipment_info['color']) && !empty($equipment_info['color']) ? $equipment_info['color'] : '';   
                                    ?>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8" style="background-color: <?php echo $color; ?>">
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Notes</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['notes']) && !empty($equipment_info['notes']) ? $equipment_info['notes'] : 'NIL'; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="tablets_details" class="panel panel-primaryNo Job Fit Category Set">
                    <div class="panel-heading stev_blue">
                        <strong>Assign Equipment Type: Tablet</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Company/Brand Name</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['brand_name']) && !empty($equipment_info['brand_name']) ? $equipment_info['brand_name'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Serial No</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['serial_number']) && !empty($equipment_info['serial_number']) ? $equipment_info['serial_number'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Model</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['model']) && !empty($equipment_info['model']) ? $equipment_info['model'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Issue Date</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['issue_date']) && !empty($equipment_info['issue_date']) ? date("m-d-Y", strtotime($equipment_info['issue_date'])) : 'NIL'; ?>
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Specification</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['specification']) && !empty($equipment_info['specification']) ? $equipment_info['specification'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Color</td>
                                    <?php
                                        $color = isset($equipment_info['color']) && !empty($equipment_info['color']) ? $equipment_info['color'] : '';   
                                    ?>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8" style="background-color: <?php echo $color; ?>">
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Notes</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['notes']) && !empty($equipment_info['notes']) ? $equipment_info['notes'] : 'NIL'; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="other1_details" class="panel panel-primary">
                    <div class="panel-heading stev_blue">
                        <strong>Assign Equipment Type: Other</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Company/Brand Name</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['brand_name']) && !empty($equipment_info['brand_name']) ? $equipment_info['brand_name'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Serial No</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['serial_number']) && !empty($equipment_info['serial_number']) ? $equipment_info['serial_number'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Model</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['model']) && !empty($equipment_info['model']) ? $equipment_info['model'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Issue Date</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['issue_date']) && !empty($equipment_info['issue_date']) ? date("m-d-Y", strtotime($equipment_info['issue_date'])) : 'NIL'; ?>
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Specification</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['specification']) && !empty($equipment_info['specification']) ? $equipment_info['specification'] : 'NIL'; ?>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Color</td>
                                    <?php
                                        $color = isset($equipment_info['color']) && !empty($equipment_info['color']) ? $equipment_info['color'] : '';   
                                    ?>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8" style="background-color: <?php echo $color; ?>">
                                        
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">Notes</td>
                                    <td class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                        <?php echo isset($equipment_info['notes']) && !empty($equipment_info['notes'])  ? $equipment_info['notes'] : 'NIL'; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel panel-primary">
                    <div class="panel-heading stev_blue">
                        <?php echo $acknowledgment_action_title; ?>
                    </div>
                    <div class="panel-body">
                        <div class="document-action-required">
                            <b><?php echo $acknowledgment_action_desc; ?></b>
                        </div>
                        <div class="document-action-required">
                            <?php echo $acknowledgement_status; ?>   
                        </div>
                        <?php if ($equipment_info['acknowledgement_flag'] == 1) { ?>
                            <div class="document-action-required">
                                <strong class="text-success">Acknowledgment Date:</strong> <?php echo date("m-d-Y", strtotime($equipment_info['acknowledgement_datetime'])) ; ?>   
                            </div>
                        <?php } ?>
                        <div class="document-action-required">
                            <form id="form_acknowledge_document" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="perform_action" value="<?php echo $perform_action; ?>">
                                <input type="hidden" name="user_type" value="<?php echo $equipment_info['users_type']?>">
                                <input type="hidden" name="user_sid" value="<?php echo $equipment_info['users_sid']?>">
                                <input type="hidden" name="equipment_sid" value="<?php echo $equipment_info['sid']?>">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="form-group">
                                            <?php if ($equipment_info['acknowledgement_flag'] != 1) { ?>
                                                <label>Acknowledgment Note</label>
                                                <textarea cols="40" rows="10" class="form-control" name="acknowledgement_notes"></textarea>
                                            <?php } else { ?>
                                                <strong class="text-success">Acknowledgment Note:</strong><p><?php echo $equipment_info['acknowledgement_notes']; ?></p>
                                            <?php } ?> 
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn <?php echo $acknowledgement_button_css; ?> pull-right" <?php echo $equipment_info['acknowledgement_flag'] == 1? "disabled='disabled'": ''; ?>><?php echo $acknowledgement_button_txt; ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var equipment_type = '<?php echo $equipment_info["equipment_type"]; ?>';
        if (equipment_type == 'cellphone') {
            $('#laptops_details').hide();
            $('#company_vehicles_details').hide();
            $('#tablets_details').hide();
            $('#other1_details').hide();
        } else if (equipment_type == 'laptop') {
            $('#cellphone_details').hide();
            $('#company_vehicles_details').hide();
            $('#tablets_details').hide();
            $('#other1_details').hide();
        } else if (equipment_type == 'vehicle') {
            $('#cellphone_details').hide();
            $('#laptops_details').hide();
            $('#tablets_details').hide();
            $('#other1_details').hide();
        } else if (equipment_type == 'tablet') {
            $('#cellphone_details').hide();
            $('#laptops_details').hide();
            $('#company_vehicles_details').hide();
            $('#other1_details').hide();
        }else if (equipment_type == 'other') {
            $('#cellphone_details').hide();
            $('#laptops_details').hide();
            $('#company_vehicles_details').hide();
            $('#tablets_details').hide();
        }
    });
</script> 