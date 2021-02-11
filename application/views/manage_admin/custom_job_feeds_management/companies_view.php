<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <!--  -->
                <div class="col-sm-9 col-md-9 col-lg-9 col-xs-12 col-xl-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i>Activate/Deactivate companies for <?=$feedName;?> feed</h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/custom_job_feeds_management'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Feeds</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table class="table table-bordered border-striped">
                                                        <thead>
                                                            <tr>
                                                                <!-- <th>
                                                                    <label class="control control--checkbox">
                                                                        <input type="checkbox" id="check_all" name="check_all">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    <br />
                                                                </th> -->
                                                                <th>Company Name</th>
                                                                <th>Status</th>
                                                                <th class="col-sm-2">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <input type="hidden" value="<?php echo sizeof($company_listing) ?>" id="company_count">
                                                        <?php foreach($company_listing as $company) {?>
                                                            <tr data-id="<?php echo $company['sid'] ;?>" data-status="<?=$company['status'];?>">
                                                                <!-- <td>
                                                                    <label class="control control--checkbox">
                                                                        <input name="checkit[]" type="checkbox" class="check_it" value="<?php echo $company['sid'] ;?>">
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </td> -->
                                                                <td><?php echo $company['CompanyName'] ;?></td>
                                                                <td class="js-status <?=$company['status'] == 1 ? 'text-success' : 'text-danger';?>"><strong><?php echo $company['status'] == 0 ? 'InActive' : 'Active' ;?></strong></td>
                                                                <td><a class="btn <?=$company['status'] == 0 ? 'btn-success' : 'btn-danger';?> action_btn"><?=$company['status'] == 1 ? 'Deactivate' : 'Activate';?></a></td>
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
</div>
<script>

    $("#check_all").click(function(){ $('.check_it').prop('checked', this.checked ); });
    //
    $(".check_it").on('click', function () {
        var company_count=$("#company_count").val();
        var checked_checkboxes_count=$(".check_it:checked").length;
        $("#check_all").prop('checked', company_count==checked_checkboxes_count ? true : false);
    });

    $(".action_btn").click(function(){
        company_status(
            $(this).closest('tr').data('status'),
            $(this).closest('tr').data('id')
        );
    });

    function company_status(
        status_val,
        company_sid
    ){
        //
        loader();
        $.post("<?=base_url('manage_admin/custom_job_feeds_management/updatestatus');?>", {
            companySid: company_sid,
            status: status_val,
            feedSid: <?=$feedSid?>
        }, function() {
            var target = $('tr[data-id="'+( company_sid )+'"]');
            if(status_val==1){
                target.data("status",0);
                target.find("a.action_btn").removeClass("btn-danger").addClass("btn-success").text("Activate");
                target.find(".js-status").removeClass("text-success").addClass("text-danger").html("<strong>InActive</strong>");

            }else{
                target.data("status",1);
                target.find("a.action_btn").removeClass("btn-success").addClass("btn-danger").text("Deactivate");
                target.find(".js-status").removeClass("text-danger").addClass("text-success").html("<strong>Active</strong>");
            }
            loader('hide');
        });
    }

    function loader(show){
        if(show == true || show == undefined){
            $('#js-loader').fadeIn();
        }
        else $('#js-loader').hide();
    }
// // $("#activate_all").click(function(){
// //     $(".check_it:checked").map(
// //         function(i,v){
// //           var status_val= $(this).closest("tr").attr("status");
// //           var company_sid= $(this).closest("tr").attr("id");
// //           company_status(status_val,company_sid);
// //         }
// //     );
// // });

// // $("#deactivate_selected").click(function(){
// //    $(".check_it:checked").map(
// //        function(i,v){
// //            var status_val=$(this).closest("tr").attr("status");
// //            var company_sid=$(this).closest("tr").attr("id");
// //            company_status(status_val,company_sid);
// //        }
// //    );
// });
  

</script>
<style>
   .cs_btn_margin{
       margin-top:10px;
       margin-bottom:10px;
       margin-left:5px;
   }
   .cs_float{
       float:right;
   }
</style>

<style>
    .my_loader{ display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99; background-color: rgba(0,0,0,.7); }
    .loader-icon-box{ position: absolute; top: 50%; left: 50%; width: auto; z-index: 9999; -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -ms-transform: translate(-50%, -50%); -o-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
    .loader-icon-box i{ font-size: 14em; color: #81b431; }
    .loader-text{ display: inline-block; padding: 10px; color: #000; background-color: #fff !important; border-radius: 5px; text-align: center; font-weight: 600; }
</style>

<!-- Loader -->
<div id="js-loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
    <div class="loader-icon-box cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text cs-loader-text" id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we are proccessing your request...</div>
    </div>
</div>