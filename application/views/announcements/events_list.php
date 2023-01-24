<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/manage_ems_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <a href="<?php echo base_url('manage_ems'); ?>" class="dashboard-link-btn">
                                <i class="fa fa-chevron-left"></i>Employee Management System
                            </a>
                            <?php echo $title; ?>
                        </span>
                </div>
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 pull-left search-job-btn">
                            <b><?php echo $events_count; ?></b> announcements found.
                        </div>
                        <?php if (check_access_permissions_for_view($security_details, 'make_announcements')) { ?>
                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 pull-right search-job-btn">
                                <a style="margin:0 0 10px;" href="<?php echo base_url('announcements/add'); ?>" class="form-btn">Make Announcement</a>
                            </div>
                        <?php  } ?>
                    </div>
                    <div class="pagination-container" >
                        <div class="col-xs-12 col-sm-12">
                            <?php // echo $links; ?>
                        </div>
                    </div>
                     <div class="table-responsive table-outer">
                        <?php if ($events) { ?>
                            <form action="" method="POST" id="ticket_form">
                                <div class="table-wrp mylistings-wrp border-none">
                                    <table class="table table-bordered table-stripped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-xs-4 text-center">Title</th>
                                                <th class="col-xs-2 text-center">Type</th>
                                                <th class="col-xs-2 text-center">Status</th>
                                                <th class="col-xs-2 text-center">Actions</th>
                                            </tr> 
                                        </thead>
                                        <tbody>
                                            <?php foreach ($events as $event) { ?>
                                                <tr>
                                                    <td class="text-center"><?php echo ucwords($event['title']); ?></td>
                                                    <td class="text-center"><?php echo $event['type']; ?></td>
                                                    <td class="text-center" id="<?=$event['sid']?>"><?php echo $event['status'] ? 'Enabled' : 'Disabled'; ?></td>
                                                    <td class="text-center">
                                                        <?php if (check_access_permissions_for_view($security_details, 'delete_announcements')) { ?>
                                                            <a id="event-<?=$event['sid']?>" data-attr="<?=$event['sid']?>" class="btn <?= $event['status'] ? 'btn-danger disable' : 'btn-primary enable'?> active-btn" href="javascript:;">
                                                                <?php echo $event['status'] ? 'Disable' : 'Enable';?>
                                                            </a>
                                                        <?php } ?>
                                                        <?php if (check_access_permissions_for_view($security_details, 'edit_announcements')) { ?>
                                                        <a class="btn btn-success active-btn" href="<?php echo base_url('announcements/edit').'/'.$event['sid']; ?>">Edit</a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>                                            
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        <?php } else { ?>
                            <div class="no-job-found">
                                <ul>
                                    <li>
                                        <h3 style="text-align: center;">No Announcements Found! </h3>
                                    </li>
                                </ul>
                            </div>
                        <?php } ?>                        
                    </div>
                    <div class="pagination-container" >
                        <div class="col-xs-12 col-sm-12">
                            <?php // echo $links; ?>
                        </div>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click','.disable',function(){
        var id = $(this).attr('data-attr');
        $.ajax({
            type:'POST',
            url:'<?php echo base_url('announcements/change_status')?>',
            data:{
                id:id,
                action:'disable'
            },
            success: function(){
                $('#event-'+id).removeClass('btn-danger');
                $('#event-'+id).removeClass('disable');
                $('#event-'+id).addClass('btn-primary');
                $('#event-'+id).addClass('enable');
                $('#event-'+id).html('Enable');
                $('#'+id).html('Disabled');
            },
            error: function(){

            }
        })
    });

    $(document).on('click','.enable',function(){
        var id = $(this).attr('data-attr');
        $.ajax({
            type:'POST',
            url:'<?php echo base_url('announcements/change_status')?>',
            data:{
                id:id,
                action:'enable'
            },
            success: function(){
                $('#event-'+id).removeClass('btn-primary');
                $('#event-'+id).removeClass('enable');
                $('#event-'+id).addClass('btn-danger');
                $('#event-'+id).addClass('disable');
                $('#event-'+id).html('Disable');
                $('#'+id).html('Enabled');
            },
            error: function(){

            }
        })
    });
    $(document).ready(function () {    
        $('#ticket_form').submit(function () {
            if ($(".checkbox1:checked").size() == 0) {
                alertify.alert('Ticket Error', 'Please select support Ticket(s).');
                return false;
            }
        });

        //multiple delete
        $('#ej_delete').click(function () {
            if ($(".checkbox1:checked").size() > 0) {
                alertify.confirm('Confirmation', "Are you sure you want to delete selected Support Ticket(s)?",
                        function () {
                            var ticketIDs = $(".checkbox1:checked").map(function () {
                                return $(this).val();
                            }).get();
                            dothis('delete', ticketIDs);
                            alertify.success('Selected tickets have been Deleted.');
                        },
                        function () {
                        });
            } else {
                alertify.alert('Ticket Error', "Please select Ticket(s) to Delete.");
            }
        });

        //multiple deactive
        $('#ej_deactive').click(function () {
            if ($(".checkbox1:checked").size() > 0) {
                alertify.confirm('Confirmation', "Are you sure you want to deactivate selected Ticket(s)?",
                        function () {
                            var ticketIDs = $(".checkbox1:checked").map(function () {
                                return $(this).val();
                            }).get();
                            dothis('deactive', ticketIDs);
                            alertify.success('Selected tickets have been deactivated.');
                        },
                        function () {
                        });
            } else {
                alertify.alert('Ticket Deactivation Error', 'Please select Ticket(s) to Deactivate.');
            }
        }); 
        
        $('#ej_active').click(function () {
            if ($(".checkbox1:checked").size() > 0) {
                alertify.confirm("Confirmation", "Are you sure you want to activate selected Ticket(s)?",
                        function () {
                            var ticketIDs = $(".checkbox1:checked").map(function () {
                                return $(this).val();
                            }).get();
                            dothis('active', ticketIDs);
                            alertify.success('Selected tickets have been Activated.');
                        },
                        function () {
                        });
            } else {
                alertify.alert('Ticket Activation Error', 'Please select Ticket(s) to Activate.');
            }
        });

        $('.dropdown-btn').click(function () {
            if ($(this).is(':visible')) {
                $(this).closest("tr").css({'height': '235px'});
            } 
            
            $(this).next().slideToggle('slow', function () {
                if ($(this).is(':visible')) {
                    $(this).parent().removeClass("arrow-down").addClass("arrow-up");
                } else {
                    $(this).closest("tr").css({'height': 'auto'});
                    $(this).parent().removeClass("arrow-up").addClass("arrow-down");
                }
            });
        });

        //select all checkboxes on one click
        $('#selectall').click(function (event) {  //on click
            if (this.checked) { // check select status
                $('.checkbox1').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"              
                });
            } else {
                $('.checkbox1').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"                      
                });
            }
        });
    });

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    function callFunction(action, id) {
        newAction = action;
        if (action == 'active')
            newAction = 'activate';
        if (action == 'deactive')
            newAction = 'deactivate';
        alertify.confirm(capitalizeFirstLetter(action) + ' Confirmation', "Are you sure you want to " + newAction + " this Ticket?",
                function () {
                    dothis(action, id);
                    alertify.success(action + 'd');
                },
                function () {
                });
    }

    function notify() {
        alertify.success("Updated");
    }

    function dothis(act, id) {    
        url = "<?= base_url() ?>tickets/process_ticket";
        
        $.post(url, {action: act, sid: id})
            .done(function (data) {
                location.reload();
        });
     
//        if (act == 'delete') {
//            $.post(url, {action: act, sid: id})
//                    .done(function (data) {
//                        location.reload();
//                    });
//        } else if (act == 'deactive') {
//            $.post(url, {action: act, sid: id})
//                    .done(function (data) {
//                        location.reload();
//                    });
//        } else if (act == 'active') {
//            $.post(url, {action: act, sid: id})
//                    .done(function (data) {
//                        location.reload();
//                    });
//        }
    }
</script>