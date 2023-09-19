<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <?php if(check_access_permissions_for_view($security_details, 'add_listing')) { ?>
                                <a href="<?= base_url('add_listing') ?>" class="dashboard-link-btn">Create A New Job</a>
                            <?php } ?>
                            <?php if(!empty($session['company_detail']['Logo'])) { ?>
                                <img src="<?php echo 'https://automotohrattachments.s3.amazonaws.com/'.$session['company_detail']['Logo'] ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
                            <?php } ?>
                            <?php if(!empty($session['company_detail']['CompanyName'])) { ?>
                                <br><?php echo $session['company_detail']['CompanyName']; ?>
                                <?php if($session['company_detail']['company_status']==0 && $session["employer_detail"]["access_level_plus"]==1){?>&nbsp;<span class="btn-danger" style="padding-left: 10px;padding-right: 10px;padding-bottom: 1px; margin-top: 10px; pointer-events: none;" >Closed</span> <br> <?php }?>

                            <?php } ?>
                            My Jobs
                        </span>
                    </div>
                    <div class="applicant-filter search-job-wrp">
                        <div class="row">                            
                            <div class="col-lg-8 col-md-12 col-xs-12 col-sm-12">
                                <div class="filter-form-wrp">
                                    <span>Search Jobs:</span>
                                    <div class="tracking-filter">
                                        <form method="GET" id="jobs_filter" name="jobs_filter">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 custom-col">
                                                    <div class="hr-select-dropdown no-aarow">
                                                        <input type="hidden" name="action" id="action" value="<?php echo base_url('my_listings'); ?>" />
                                                        <input type="text" name="search" id="search" value="<?php echo $searchValue ?>" class="invoice-fields search-job" placeholder="Search job">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <a class="form-btn" href="#" id="search-btn">Search</a>
                                <script>
                                    $('#search').trigger('keyup');
                                    $(document).ready(function () {
                                        $('#search').on('keyup', function () {
                                            var searchString = $('#search').val();
                                            var actionUrl = $('#action').val()+ '/' + $('#segment-2').val();
                                            $('#search-btn').attr('href', actionUrl + '/' + encodeURI(searchString));
                                        });
                                    });
                                </script>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                <a class="form-btn" href="<?= base_url('my_listings/active')?>" >Clear</a>
                            </div>
                        </div>
                    </div>
                    <div class="pagination-container" >
                        <div class="col-xs-12 col-sm-12">
                            <?php echo $links; ?>
                        </div>
                    </div>
                    <div class="panel panel-default full-width active-inactive-jobs-tab">
                        <ul class="custom-tab-nav">
                            <li class="active"><a href="<?= base_url('my_listings/active'.'/'.$searchValue); ?>">Active Job Listings</a></li>
                            <li class="inactive"><a href="<?= base_url('my_listings/inactive'.'/'.$searchValue); ?>"><!--href="#inactive"--> Inactive Job Listings</a></li>
                        </ul>
                        <div class="resp-tabs-container hor_1">
                            <div id="active" class="tabs-content panel-body">                                
                                <?php $my_data['listings'] = $listings_active; ?>
                                <?php $this->load->view('manage_employer/my_listings_partial', $my_data); ?>

                            </div>
                        </div>
                    </div>

                    <div class="pagination-container" >
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php echo $links; ?>
                        </div>
                    </div>
                    <input type="hidden" id="segment-2" value="<?php echo $this->uri->segment(2);?>">

                    <div class="btn-panel">
                        <?php if(check_access_permissions_for_view($security_details, 'delete_archive_job')) { ?>
                            <a class="delete-all-btn" id="ej_delete" href="javascript:;">Delete Selected</a>
                        <?php } ?>

                        <?php if(check_access_permissions_for_view($security_details, 'activate_deactivate_job')) { ?>
                        <a class="delete-all-btn active-btn" id="ej_active" href="javascript:;">Activate</a>
                        <a class="delete-all-btn deactive-btn" id="ej_deactive" href="javascript:;">Deactivate</a>   
                        <?php } ?>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<!--file delete opener modal starts-->
<form id="deleteJobForm" style="display: none">
    <fieldset class="confirm-hireed-employee">
        <label>Note: If you delete this job any Candidate applications in your Applicant tracking system, will lose its title and simply say Job Listing removed from system.
        Please just Deactivate this job posting instead and maintain it for your records.
        </label>
        <input type="checkbox" required id="myCheckbox" onclick="check_status(this);"/>
        <input type="hidden" name="delete_job_id" id="delete_job_id" value="0"/>
        <label for="myCheckbox">Are you sure you want to proceed with deleting this job? </label>
        <div class="btn-panel">
            <ul>
                <li>
                    <input id="yes-btn" class="submit-btn" type="submit" value="Yes!"/>
                </li>
            </ul>
        </div>
    </fieldset>
    <div class="clear"></div>
</form>
<script type="text/javascript">
    function func_toggle_del_btn(source){
        if(source == 'archived'){
            $('#ej_delete').show();
        } else {
            $('#ej_delete').hide();
        }
        
        if(source == 'active'){
           $('#ej_active').hide();
        } else {
            $('#ej_active').show();
        }
        
        if(source == 'inactive'){
            $('#ej_deactive').hide();
        } else {
           $('#ej_deactive').show();
        }
    }

    function func_archive_job(job_sid) {
        alertify.confirm(
            'Are you Sure?',
            'Are you Sure you want to archive this job?',
            function () {
                var my_request;

                my_request = $.ajax({
                    url: '<?php echo current_url(); ?>',
                    type: 'POST',
                    data: { 'perform_action': 'archive_job', 'job_sid': job_sid }
                });

                my_request.done(function (response) {
                   if(response == 'success'){
                       window.location = window.location.href;
                   } else {
                       alertify.error('Something went Wrong!');
                   }
                });
            },
            function () {
                alertify.error('Cancelled');
            });
    }

    $(window).load(function(){
        if($('#segment-2').val() == 'active' || $('#segment-2').val() == ''){
            $('.active').addClass('resp-tab-active');
            $('.inactive').removeClass('resp-tab-active');
        } else{
            $('.active').removeClass('resp-tab-active');
            $('.active').addClass('resp-tab-inactive');
            $('.inactive').addClass('resp-tab-active');
        }
    });

    $(document).ready(function () {
        if($('#segment-2').val() == 'archived'){
            $('#ej_delete').show();
        } else {
            $('#ej_delete').hide();
        }

        if($('#segment-2').val() == 'active' || $('#segment-2').val() == ''){
            $('#ej_active').hide();
        } else {
            $('#ej_active').show();
        }

        if($('#segment-2').val() == 'inactive'){
            $('#ej_deactive').hide();
        } else {
            $('#ej_deactive').show();
        }
//        $('#ej_delete').hide();
//        $('#ej_active').hide();
        //multi XML feed
        $('#xml_form').submit(function () {
            if ($(".checkbox1:checked").size() == 0) {
                alertify.alert('Job Export Error', 'Please select Job(s) to get XML.');
                return false;
            }
        });

        //multiple delete
        $('#ej_delete').click(function () {
            if ($(".checkbox1:checked").size() > 0) {
                var jobIDs = $(".checkbox1:checked").map(function () {
                    return $(this).val();
                }).get();
                myPopup('delete', jobIDs);
                //console.log(jobIDs);

//                alertify.confirm('Confirmation', "Are you sure you want to delete selected Job(s)?",
//                        function () {
//                            var jobIDs = $(".checkbox1:checked").map(function () {
//                                return $(this).val();
//                            }).get();
//                            dothis('delete', jobIDs);
//                            alertify.success('Selected jobs have been Deleted.');
//
//                        },
//                        function () {
//
//                        });

            } else {
                alertify.alert('Job Delete Error', "Please select Job(s) to Delete.");
            }
        });

        //multiple deactive
        $('#ej_deactive').click(function () {
            if ($(".checkbox1:checked").size() > 0) {
                alertify.confirm('Confirmation', "Are you sure you want to deactivate selected Job(s)?",
                    function () {
                        var jobIDs = $(".checkbox1:checked").map(function () {
                            return $(this).val();
                        }).get();
                        dothis('deactive', jobIDs, 'checkbox');
                        //alertify.success('Selected jobs have been deactivated.');
                    },
                    function () {

                    });
            } else {
                alertify.alert('Job Deactivation Error', 'Please select Job(s) to Deactivate.');
            }
        });
        //multiple active
        $('#ej_active').click(function () {
            if ($(".checkbox1:checked").size() > 0) {
                alertify.confirm("Confirmation", "Are you sure you want to activate selected Job(s)?",
                    function () {
                        var jobIDs = $(".checkbox1:checked").map(function () {
                            return $(this).val();
                        }).get();
                        dothis('active', jobIDs, 'checkbox');
                        //alertify.success('Selected jobs have been Activated.');
                    },
                    function () {
                    });
            } else {
                alertify.alert('Job Activation Error', 'Please select Job(s) to Activate.');
            }
        });

        $('.job-action-btn-wrp').click(function () { 
            $(this).closest("tr").css({'height': '270px'}); 
            $(this).children(".dropdown-btn").next().slideToggle('slow', function () {
                if($(this).parent().attr('class') == 'job-action-btn-wrp arrow-down'){
                    $(this).parent().removeClass("arrow-down").addClass("arrow-up"); 
                } else {
                    $(this).closest("tr").css({'height': 'auto'});
                    $(this).parent().removeClass("arrow-up").addClass("arrow-down");
                }
            });
        });

        //toolpit for edit delete and deactive button
        $(".edit").attr('title', 'Edit Job');
        $(".delete").attr('title', 'Delete Job');
        $(".active").attr('title', 'Activate Job');
        $(".deactive").attr('title', 'Deactivate Job');
        $(".clone").attr('title', 'Clone Job');

        //select all checkboxex on one click
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

    function callFunction(action, id, place = '') {
        newAction = action;
        if (action == 'active')
            newAction = 'activate';
        if (action == 'deactive')
            newAction = 'deactivate';
            alertify.confirm(capitalizeFirstLetter(action) + ' Confirmation', "Are you sure you want to " + newAction + " this Job?",
            function () {
                dothis(action, id, place);
                //alertify.success(action + 'd');
            },
            function () {
            });
    }

    function notify() {
        alertify.success("Updated");
    }

    function dothis(act, id, place = '') {
        url = "<?= base_url() ?>job_listings/job_task";
        
        $.post(url, {action: act, sid: id, place:place})
            .done(function (data) {
                if(data == 'customerror') {
                    alertify.error('Not Authorized to perform the actiom!');
                } else {
                    alertify.success(data);
                }
                alertify.closeAll();
                window.location.href = window.location.href;
            });
    }

    function myPopup(del, id) {
        $('#delete_job_id').val(id);
        $('#deleteJobForm').css('display', 'block');
        alertify.genericDialog || alertify.dialog('genericDialog', function () {
            return {
                main: function (content) {
                    this.setContent(content);
                },
                setup: function () {
                    return {
                        focus: {
                            element: function () {
                                return this.elements.body.querySelector(this.get('selector'));
                            },
                            select: true
                        },
                        options: {
                            basic: false,
                            maximizable: false,
                            resizable: false,
                            padding: false,
                            title: "Please Confirm Delete Job"
                        }
                    };
                },
                settings: {
                    selector: undefined
                }
            };
        });
        alertify.genericDialog($('#deleteJobForm')[0]);
    }

    $('#deleteJobForm').on('submit', function (e) {
        e.preventDefault();
        
        if ($('#myCheckbox').is(":checked")) {
            var delete_job_id = $('#delete_job_id').val();
            dothis('delete', delete_job_id);
            alertify.success('Job(s) deleted successfully');
        }
    });

    function check_status(source) {
        if ($(source).is(':checked')) {
            $('#yes-btn').removeAttr('disabled');
            $('#yes-btn').removeClass('disabled-btn');
        } else {
            $('#yes-btn').attr('disabled', 'disabled');
            $('#yes-btn').addClass('disabled-btn');
        }
    }

    $(document).ready(function () {
        $('#yes-btn').attr('disabled', 'disabled');
        $('#yes-btn').addClass('disabled-btn');

        $('#HorizontalTab').easyResponsiveTabs({
            type: 'default', //Types: default, vertical, accordion
            width: 'auto', //auto or any width like 600px
            fit: true, // 100% fit in a container
            tabidentify: 'hor_1', // The tab groups identifier
            activate: function() {}
        });
    });

    
</script>