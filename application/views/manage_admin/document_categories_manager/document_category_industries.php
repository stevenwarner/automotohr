<style>
    .editdocumentcategory {}
    .red {
  float: none !important;
    }
</style>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-tags"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/document_categories_manager') ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Document Categories Manager</a>
                                    </div>
                                    <div class="add-new-promotions">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <!--  <a href="<?php //echo base_url('manage_admin/job_categories_manager/add_job_category_industry'); 
                                                                ?>" class="btn btn-success" >Add New Category Industry</a>-->
                                                <button type="button" onclick="open_uploaded_model();" class="btn btn-success">Add New Industry</button>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-box">
                                                <div class="hr-box-body hr-innerpadding">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-stripped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-4">Industry Name</th>
                                                                    <th class="col-xs-6">Description</th>
                                                                    <th class="col-xs-2 text-center" colspan="3">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($job_category_industries)) { ?>
                                                                    <?php foreach ($job_category_industries as $industry) { ?>
                                                                        <tr>
                                                                            <td><?php echo ucwords($industry['industry_name']); ?></td>
                                                                            <td><?php echo $industry['short_description']; ?></td>
                                                                            <td class="text-center">
                                                                            <button class="btn btn-success btn-sm editdocumentcategory" data-industrysid="<?php echo $industry['sid']; ?>" data-industryname="<?php echo $industry['industry_name'] ?>" data-industrydesc="<?php echo $industry['short_description'] ?>"><i class="fa fa-pencil"></i></button>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a class="btn btn-success btn-sm" href="<?php echo base_url('manage_admin/document_categories_manager/assign_categories/' . $industry['sid']); ?>">Assign Categories</a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a class="btn btn-danger btn-sm jsDeleteIndustry" title="Delete this job category industry" href="javascript:void(0)" data-id="<?= $industry['sid']; ?>" data-name="<?php echo ucwords($industry['industry_name']); ?>">
                                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
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
    </div>
</div>





<div id="document_listing_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Industry</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">
                    <div class="universal-form-style-v2">
                        <ul>
                            <li class="form-col-100 autoheight">
                                <div class="row">
                                    <div class="col-md-3"><b>Industry Name </b><span class="hr-required red"> * </span></div>
                                    <div class="col-md-8">
                                        <input type='text' class="hr-form-fileds invoice-fields" name='industry_name' id="industry_name" />
                                        <input type="hidden" value="" id="categoryaction" />
                                        <input type="hidden" value="" id="categorysid" />
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-3"><b>Description </b></div>
                                    <div class="col-md-8">
                                        <textarea id="short_description" name="short_description" class="hr-form-fileds field-row-autoheight" rows="7"></textarea>

                                    </div>
                                </div>

                            </li>

                            <li class="form-col-100 autoheight">
                                <div class="row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-8">
                                        <div class="message-action-btn">
                                            <input type="submit" value="Save" class="btn btn-success" onclick="document_category_form_validate()" id="btnsave">
                                        </div>
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>


<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>



<script>
    $(function() {
        //
        $('.jsDeleteIndustry').click(function(event) {
            //
            event.preventDefault();
            //
            var industryId = $(this).data('id');
            var industryName = $(this).data('name');
            //
            alertify.confirm(
                'Do you really want to delete <strong>"' + (industryName) + '"</strong> industry?',
                function() {
                    DeleteIndustry(industryId, industryName);
                }
            ).setHeader('Confirm!');
        });

        //
        function DeleteIndustry(id, name) {
            $('body').append('<style id="jsAlertify">.ajs-ok{display:none}</style>')
            //
            var ref = alertify.alert('Please wait while we are deleting "<strong>' + (name) + '</strong>" industry....').set('closable', false);
            //
            $.ajax({
                method: "delete",
                url: "<?= base_url('manage_admin/document_categories_manager/delete_industry'); ?>/" + id,
            }).done(function() {
                //
                $('#jsAlertify').remove();
                ref.close();
                //
                alertify.alert('You have successfully deleted the industry.', function() {
                    window.location.reload();
                })
            });
        }
    });



$(".editdocumentcategory").click(function() {

var industrysid = $(this).attr('data-industrysid');
var industryname = $(this).attr('data-industryname');
var industrydesc = $(this).attr('data-industrydesc');

$('#categoryaction').val('edit_industry');
$('#industry_name').val(industryname);
$('#categorysid').val(industrysid);
$('#short_description').val(industrydesc);
$('#btnsave').val('Update');
$('.modal-title').text('Edit Industry');
$('#document_listing_modal').modal("show");
$('.modal-backdrop').hide();

});



    function open_uploaded_model() {

        $('#industry_name').val('');
        $('#short_description').val('');
        $('#document_listing_modal').modal("show");
        $('.modal-backdrop').hide();
        $('#btnsave').val('Save');
        $('#categoryaction').val('add_industry');
        $('.modal-title').text('Add New Industry');

    }


    function document_category_form_validate() {

        var industryname = $('#industry_name').val();
        var shortdescription = $('#short_description').val();
        if (industryname.trim() == "" || industryname == undefined) {
            alertify.alert("Notice", "Please enter industry name.");
            return;
        } else {

            //
            var obj = {};
            //
            var category_action = $('#categoryaction').val();
            var sid = $('#categorysid').val();
            obj.industryname = industryname;
            obj.shortdescription = shortdescription;
            obj.action = category_action;
            obj.sid = sid;
            $.ajax({
                url: "<?= base_url('manage_admin/document_categories_manager/handler'); ?>",
                type: 'POST',
                data: obj,
                beforeSend: function() {
               $('#document_loader').show();
            }
            }).done(function(resp) {
                //
                $('#document_loader').hide();
               // $('.jsModifyModalLoader').fadeOut(300);
                alertify.alert('SUCCESS!', resp.Message, function() {
                    if(resp.Status==true){
                    window.location.reload();
                    }
                });
            });

        }

    }
</script>