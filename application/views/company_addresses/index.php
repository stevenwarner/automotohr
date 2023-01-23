<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <a href="<?php echo base_url('company_addresses/add_new_address')?>" class="btn btn-success"><i class="fa fa-plus"> </i> Add Company Address</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="dashboard-conetnt-wrp">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover table-condensed">
                                                <thead>
                                                <tr>
                                                    <th class="col-xs-10">Address</th>
                                                    <th colspan="2" class="col-xs-2 text-center">Actions</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(!empty($addresses)) { ?>
                                                    <?php foreach($addresses as $address) { ?>
                                                        <tr>
                                                            <td><?php echo $address['address']; ?></td>
                                                            <td class="col-xs-1 text-center">
                                                                <a class="btn btn-success btn-block btn-sm" href="<?php echo base_url('company_addresses/edit_new_address/'.$address['sid'])?>">Edit</a>
                                                            </td>
                                                            <td xml:base="col-xs-1 text-center">
                                                                <a class="btn btn-danger delete-address btn-block btn-sm" data-attr="<?php echo $address['sid']?>" href="javascript:;">Delete</a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php }  else { ?>
                                                    <tr>
                                                        <td class="text-center" colspan="2">
                                                            <span class="no-data">No Records</span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
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
</div>
<script type="text/javascript">
    $('.delete-address').click(function(){
        var add_id = $(this).attr('data-attr');
        alertify.confirm(
            'Please Confirm Delete',
            'Are you sure you want to delete!',
            function () {
                var myUrl = "<?php echo base_url('/company_addresses/delete_address') ?>";
                var myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: {
                        sid:add_id
                    }
                });

                myRequest.success(function (response) {
                    if (response == 'success') {
                        alertify.success('Address Deleted Successfully!');
                        window.location.href = '<?php echo base_url('company_addresses')?>'
                    } else {
                        alertify.error('Unknown Error Occur!');
                    }
                });
            }, function () {
                alertify.warning('Cancelled!');
        });
    });
</script>