<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <div class="content-inner page-dashboard">
        <div class="row">
            <div class="col-xl-12">
                <div class="page-header full-width">
                    <h1 class="float-left"><b>Private Messages: <?php echo $page; ?></b></h1>
                    <div class="btn-panel float-right">
                        <a href="<?php echo base_url('/dashboard'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-long-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('inbox'); ?>"><i class="fa fa-envelope-o"></i> Inbox </a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('outbox'); ?>"><i class="fa fa-inbox"></i> Outbox</a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                <a class="btn btn-info btn-block mb-2" href="<?php echo base_url('compose-messages'); ?>"><i class="fa fa-pencil-square-o"></i> Compose new Message </a>
            </div>
        </div>


        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h5><strong>Search Messages</strong></h5>
                    </div>
                    <div class="form-wrp">
                        <form method="GET" action="">
                            <div class="card-body">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-xs-6 col-sm-6">
                                            <div class="form-group">
                                                <label>From Name</label>
                                                <input type="text" id="from_username" name="from_username" class="form-control" autocomplete="off" value="<?php echo $from_username; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-xs-6 col-sm-6">
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <input type="email" id="email" name="email" class="form-control" autocomplete="off" value="<?php echo $email; ?>"  />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12 col-sm-12 field-row">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <label>Subject:</label>
                                                <input type="text" id="subject" name="subject" class="form-control" autocomplete="off" value="<?php echo $subject; ?>"  />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <label>Date From</label>
                                                <input id="start_date" type="text" name="start_date" class="form-control" value="<?php echo $start_date; ?>"  readonly/>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <label>Date To</label>
                                                <input id="end_date" type="text" name="end_date" class="form-control" value="<?php echo $end_date; ?>"  readonly/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="form-group text-right">
                                        <input type="button" id="btn_apply_filters" name="filter" value="Search" class="btn btn-primary btn-sm" />
                                        <?php if ($view_type == 'inbox'){ ?>
                                            <a href="<?php echo base_url('inbox'); ?>" class="btn btn-primary btn-sm">Clear</a>
                                        <?php } else if ($view_type == 'outbox'){ ?>
                                            <a href="<?php echo base_url('outbox'); ?>" class="btn btn-primary btn-sm">Clear</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($messages)) { ?>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5><strong>Inbox</strong></h5>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="col-xl-4 col-lg-4 col-md-4 col-xs-4 col-sm-4">from</th>
                                            <th class="col-xl-4 col-lg-4 col-md-4 col-xs-4 col-sm-4">Subject</th>
                                            <th class="col-xl-3 col-lg-3 col-md-3 col-xs-3 col-sm-3">Received on</th>
                                            <th class="col-xl-1 col-lg-1 col-md-1 col-xs-1 col-sm-1 text-center" colspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($messages as $key => $message) {
                                                $user_data = get_affiliate_name_and_email($message['to_id']);
                                                $user_name = $user_data['full_name'];
                                                $user_email = $user_data['email']; ?>
                                            <tr id="parent_<?php echo $message['sid']; ?>">
                                                <td>
                                                    <?php echo $user_name; ?>
                                                    <br>
                                                    <?php echo $user_email; ?>
                                                </td>
                                                <td>
                                                    <?php echo $message['subject']; ?>
                                                </td>
                                                <td>
                                                    <?php echo
                                                    reset_datetime(array(
                                                        'datetime' => $message['message_date'],
                                                        // 'from_format' => 'h:iA', // Y-m-d H:i:s
                                                        // 'format' => 'h:iA', //
                                                        'from_zone' => STORE_DEFAULT_TIMEZONE_ABBR, // PST
                                                        'from_timezone' => $session['affiliate_users']['timezone'], //
                                                        '_this' => $this
                                                    ));?>
<!--                                                    --><?php //echo my_date_format($message['message_date']); ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('messages/view_message/'.$view_type.'/'.$message['sid'])?>" class="btn btn-primary btn-block btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                                <td>
<!--                                                    <a href="--><?//= base_url('messages/delete_inbox_message/'.$message['sid'])?><!--" class="btn btn-danger btn-block btn-sm">-->
<!--                                                        <i class="fa fa-trash"></i>-->
<!--                                                    </a>-->
                                                    <a class="btn btn-danger btn-block btn-sm remove" href="javascript:;" id="<?= $message["msg_id"] ?>" onclick="todo('delete', this.id);">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
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
        <?php } else { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <tbody>
                            <tr class="d-flex">
                                <div class="col-sm-12 text-center"><b>You do not have any private messages at this time.</b></div>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $(document).keypress(function(e) {
        if(e.which == 13) { // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    $(document).ready(function (){
        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function (value) {
                $('#end_date').datepicker('option', 'minDate', value);
            }
        }).datepicker('option', 'maxDate', $('#end_date').val());

        $('#end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
            onSelect: function (value) {
                $('#start_date').datepicker('option', 'maxDate', value);
            }
        }).datepicker('option', 'minDate', $('#start_date').val());

    });

    function generate_search_url(){
        var url_type = '<?php echo $view_type; ?>';
        var from_username = $('#from_username').val();
        var email = $('#email').val();
        var subject = $('#subject').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        from_username = from_username != '' && from_username != null && from_username != undefined && from_username != 0 ? encodeURIComponent(from_username) : 'all';
        email = email != '' && email != null && email != undefined && email != 0 ? encodeURIComponent(email) : 'all';
        subject = subject != '' && subject != null && subject != undefined && subject != 0 ? encodeURIComponent(subject) : 'all';
        start_date = start_date != '' && start_date != null && start_date != undefined && start_date != 0 ? encodeURIComponent(start_date) : 'all';
        end_date = end_date != '' && end_date != null && end_date != undefined && end_date != 0 ? encodeURIComponent(end_date) : 'all';
        
        if (url_type == 'inbox') {
            var url = '<?php echo base_url('inbox'); ?>' + '/' + from_username + '/' + email + '/' + subject + '/' + start_date + '/' + end_date;
        } else if (url_type == 'outbox') {
            var url = '<?php echo base_url('outbox'); ?>' + '/' + from_username + '/' + email + '/' + subject + '/' + start_date + '/' + end_date;
        }
        

        $('#btn_apply_filters').attr('href', url);
    }

    $('#btn_apply_filters').on('click', function(e){
        e.preventDefault();
        generate_search_url();
        window.location = $(this).attr('href').toString();
    });

    function todo(action, id) {
        url = "<?= base_url() ?>messages/message_task";
        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Message?",
            function () {
                $.post(url, {action: action, sid: id})
                    .done(function (data) {
                        alertify.success('Selected message have been ' + action + 'd.');
                        $("#parent_" + id).remove();
                    });
            },
            function () {
                alertify.error('Canceled');
            });
    }
</script>
