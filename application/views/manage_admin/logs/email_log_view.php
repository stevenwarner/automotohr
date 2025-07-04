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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>email inquiry log</h1>
                                    </div>
                                    <div class="hr-search-criteria opened">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <!-- Search Table Start -->
                                    <div class="hr-search-main" style="display: block;" >
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $user_name = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : '';?>
                                                    <label>From</label>
                                                    <input type="text" class="invoice-fields" name="user_name" id="user_name" value="<?php echo set_value('user_name', $user_name); ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $email = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : '';?>
                                                    <label>Email</label>
                                                    <input type="text" class="invoice-fields" name="email" id="email" value="<?php echo set_value('keyword', $email); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $name_search = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : '';?>
                                                    <label>Name</label>
                                                    <input type="text" class="invoice-fields" name="name_search" id="name_search" value="<?php echo set_value('name_search', $name_search); ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label class="">Date From</label>
                                                    <?php $start_date = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : '';?>
                                                    <input class="invoice-fields"
                                                           placeholder=""
                                                           type="text"
                                                           name="start_date"
                                                           id="start_date"
                                                           value="<?php echo set_value('start_date', $start_date); ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label class="">Date To</label>
                                                    <?php $end_date = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : '';?>
                                                    <input class="invoice-fields"
                                                           placeholder=""
                                                           type="text"
                                                           name="end_date"
                                                           id="end_date"
                                                           value="<?php echo set_value('end_dat', $end_date); ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <a id="btn_apply_filters" class="btn btn-success" href="#" >Apply Filters</a>
                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/email_enquiries'); ?>">Reset Filters</a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Search Table End -->
                                    <!-- Email Logs Start -->
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Email Enquiries Logs</h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found : <?php echo $total_records;?></h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center">Date</th>
                                                                    <th class="col-xs-2 text-center">From</th>
                                                                    <th class="col-xs-3 text-center">Name / Email</th>
                                                                    <th class="col-xs-4 text-center">Subject</th>
                                                                    <th class="col-xs-2 text-center" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php if(!empty($logs)) { ?>
                                                                <?php foreach ($logs as $log) { ?>
                                                                    <tr>
                                                                        <td><?= date_with_time($log['date']); ?></td>
                                                                        <td>
                                                                            <?= $log['username'] ?>
                                                                            <?php if($log['resend_flag']){
                                                                                echo '<br> <b>Resent On: </b>' . date_with_time($log['resend_date']);
                                                                            }?>
                                                                        </td>
                                                                        <td>
                                                                        <?php   $length_start = strpos($log['message'],'Dear');
                                                                                $add_start_length = 4;
                                                                                
                                                                                if(!$length_start){
                                                                                    $length_start = strpos($log['message'],'Hi');
                                                                                    $add_start_length = 2;
                                                                                }
                                                                                
                                                                                if($length_start) {
                                                                                    $message_start = substr($log['message'], $length_start+$add_start_length);

                                                                                    if($add_start_length == 4 || $add_start_length == 2) {
                                                                                        $length_end = strpos($message_start,',');
                                                                                    }

                                                                                    if(!$length_end){
                                                                                       $length_end = strpos($message_start,'<'); 
                                                                                    }

                                                                                    $message_name = substr($message_start, 0, $length_end);
                                                                                    echo '<b>'.strip_tags($message_name).'</b><br>';
                                                                                } ?>
                                                                            <?php echo $log['email']; ?>
                                                                        </td>
                                                                        <td><?= $log['subject'] ?></td>
                                                                        <td class="col-xs-1"><a class="btn btn-success btn-sm btn-block" title="View" href="<?= base_url('manage_admin/email_log') ?>/<?= $log["sid"] ?>">View</a></td>
                                                                        <td class="col-xs-1"><a  class="btn btn-success btn-sm btn-block" title="Resend" id="<?= $log["sid"] ?>" onclick="return resend(this.id);">Resend</a></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php }  else { ?>
                                                                <tr>
                                                                    <td class="text-center" colspan="5">
                                                                        <span class="no-data">No Email Found</span>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Email Logs End -->
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
    function resend(id) {
        alertify.dialog('confirm')
            .set({
                'title ': 'Confirmation',
                'labels': {ok: 'Yes', cancel: 'No'},
                'message': 'Are you sure you want to Resend this Email?',
                'onok': function () {
                    url = "<?= base_url('manage_admin/resend_email') ?>" + '/' + id;
                    window.location.href = url;
                },
                'oncancel': function () {
                    alertify.error('Cancelled!');
                }
            }).show();
    }

    function generate_url(){
        var user_name = $('#user_name').val();
        var email = $('#email').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var name_search = $('#name_search').val();
        
        user_name   = user_name != '' && user_name != null && user_name != undefined && user_name != 0 ? encodeURIComponent(user_name) : 'all';
        email       = email != '' && email != null && email != undefined && email != 0 ? encodeURIComponent(email) : 'all';
        start_date  = start_date != '' && start_date != null && start_date != undefined && start_date != 0 ? encodeURIComponent(start_date) : 'all';
        end_date    = end_date != '' && end_date != null && end_date != undefined && end_date != 0 ? encodeURIComponent(end_date) : 'all';
        name_search   = name_search != '' && name_search != null && name_search != undefined && name_search != 0 ? encodeURIComponent(name_search) : 'all';
        
        var url = '<?php echo base_url('manage_admin/email_enquiries'); ?>' + '/' + user_name + '/' + email + '/' + start_date + '/' + end_date+ '/' + name_search;
        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function(){
        $('#btn_apply_filters').click(function(){
            generate_url();
        });


        $('#start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                $('#end_date').datepicker('option', 'minDate', value);
                generate_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date').val());

        $('#end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                $('#start_date').datepicker('option', 'maxDate', value);
                generate_url();
            }
        }).datepicker('option', 'minDate', $('#start_date').val());

//        $('#user_name').on('keyup', function(){
//            generate_url();
//        });

//        $('#email').on('keyup', function(){
//            generate_url();
//        });

        $('#keyword').trigger('keyup');
    });

</script>