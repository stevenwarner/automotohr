<style>
    .csLoader{
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        background: #fff;
        z-index: 999;
    }
    .csLoader i{
        position: relative;
        top: 10%;
        left: 50%;
        transform: translate(-10%, -50%);
        font-size: 70px;
    }
    .jsRow{ cursor: grab; }
</style>

<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <!-- Loader -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <?php echo $title; ?>
                                </span>
                            </div>
                            <div class="dashboard-conetnt-wrp" style="position: relative">
                                <div class="form-wrp">
                                    <div class="csLoader jsLoader"><i class="fa fa-spinner fa-spin"></i></div>
                                    <form id="edit_status" name="edit_status" action="" method="POST">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <p class="alert alert-info">
                                                    <strong>Note</strong> You can change the sort order by dragging the status.
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <h2><strong>Applicant Status</strong>
                                                <?php if($additional_status_bar == 'enabled') { ?>
                                                 <span class="pull-right">
                                                        <a href="javascript:;" onclick="add_status_block()"
                                                            class="btn btn-success"> + Add a status</a>
                                                    </span>
                                                <?php } ?> </h2>
                                                <hr />
                                            </div>
                                        </div>
                                        <div class="jsDraggable">
                                            <!-- All status -->
                                            <?php foreach ($application_status as $status) { ?>
                                            <div class="row jsRow" data-id="<?=$status['sid'];?>">
                                                <div data_id="<?php echo $status['css_class']; ?>"
                                                    data_sa="<?php echo $status['status_order']; ?>">
                                                    <div class="col-sm-8 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Applicant Status: <span
                                                                    class='staric'>*</span> </label>
                                                            <input class="form-control jsStatus" type="text"
                                                                name="<?php echo $status['css_class']; ?>"
                                                                id="<?php echo $status['css_class']; ?>"
                                                                value="<?php echo $status['name']; ?>">
                                                            <?php echo form_error($status['css_class']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 col-xs-12">
                                                        <div class="form-group">
                                                            <label>Color: <span class='staric'>*</span></label>
                                                            <div class='input-group colorcustompicker'>
                                                                <input type='text' class='form-control jsColor'
                                                                    name='color_<?=$status['css_class']?>'
                                                                    data-rule-required='true'
                                                                    value='<?php echo !empty($status['bar_bgcolor']) ? '#'.$status['bar_bgcolor'] : getStatusColor($status['css_class']) ?>'>
                                                                <span class='input-group-addon'><i></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 col-xs-12 hidden">
                                                        <div class="form-group">
                                                            <label>Sort Order: <span class="staric">*</span></label>
                                                            <input type="text"
                                                                value="<?php echo $status['status_order']; ?>"
                                                                name="order_<?php echo $status['css_class']; ?>"
                                                                id="order_<?php echo $status['css_class']; ?>" min="1"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" class="jsType" value="<?=$status['status_type'];?>">
                                                    <div class="col-sm-1 col-xs-12">
                                                        <?php  if($status['status_type']=='custom'){ ?>
                                                        <div class='delete-row-new'>
                                                            <a href='javascript:;' title="Remove this status"
                                                                placement="top" data-attr="<?=$status['sid']?>"
                                                                class="remove dlt-custom-status">
                                                                <i class='fa fa-times'></i>
                                                            </a>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <input type="submit" value="Update" onclick="return validate_form();"
                                        class="submit-btn">
                                    <a class="submit-btn btn-cancel"
                                        href="<?php echo base_url('my_settings'); ?>">Cancel</a>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-colorpicker.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-colorpicker.min.css " />
<script>
$(document).ready(function() {
    $(document).on('click', ".up,.down", function(e) {
        e.preventDefault();
        var row = $(this).parents(".jsRow:first");
        var c_row_id = row.attr('data_id');
        var c_row_order = row.attr('data_sa');

        if ($(this).is(".up")) {
            var p_row_id = row.prev().attr('data_id');
            var p_row_order = row.prev().attr('data_sa');

            $('#order_' + c_row_id).val(p_row_order);
            $('#order_' + p_row_id).val(c_row_order);

            row.attr('data_sa', p_row_order);
            row.prev().attr('data_sa', c_row_order);

            row.insertBefore(row.prev());
        } else if ($(this).is(".down")) {
            var n_row_id = row.next().attr('data_id');
            var n_row_order = row.next().attr('data_sa');

            $('#order_' + c_row_id).val(n_row_order);
            $('#order_' + n_row_id).val(c_row_order);

            row.attr('data_sa', n_row_order);
            row.next().attr('data_sa', c_row_order);

            row.insertAfter(row.next());
        }
    });

    $('.colorcustompicker').colorpicker();

    $('.dlt-custom-status').on('click', function() {
        var status_id = $(this).attr('data-attr');
        alertify.confirm('Delete Confirm', 'Are you sure you want to delete?', function() {
            $.ajax({
                url: '<?php echo base_url('application_status/delete_custom_status')?>',
                type: 'POST',
                data: {
                    id: status_id
                },
                success: function(data) {
                    if (data == 'success') {
                        alertify.success('Status Deleted Successfully', function(){
                            window.location.reload();
                        });
                    }
                },
                error: function() {

                }
            })
        }, function() {

        });
    });
});

var i = 0;

function add_status_block() {
    var id = "add_status_row" + $('.jsRow').length;
    $('.jsDraggable').append(getRow(id));
    $('.colorcustompicker').colorpicker();
    $('html, body').animate({
        scrollTop: $(document).height()
    }, 0);
    loadTitles();
    callDager();
}


function getRow(id) {
    return `
    <div class="row jsRow" data-id="00${Math.floor(Math.random() * Math.floor(50))}" id="${id}">
        <div data_id="not_contacted" data_sa="${$('.jsRow').length + 1}">
            <div class="col-sm-8 col-xs-12">
                <div class="form-group">
                    <label>Applicant Status: <span class="staric">*</span></label>
                    <input class="form-control jsStatus" type="text" data-rule-required="true" name="custom_status_name_[]" value="" />
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="form-group">
                    <label>Color: <span class="staric">*</span></label>
                    <div class="input-group colorcustompicker">
                        <input type="text" class="form-control jsColor" name="custom_color_[]" data-rule-required="true" value="#a13e07">
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-xs-12 hidden">
                <div class="form-group">
                    <label>Sort Order : <span class="staric">*</span></label>
                    <input type="number" value="${$('.jsRow').length + 1}" name="custom_sort_order_[]" id="order_" min="1" class="form-control">
                </div>
                <input type="hidden" value="custom" class="jsType">
            </div>
            <div class="col-sm-1 col-xs-12">
                <div class="form-group">
                    <div class="delete-row-new text-right" tilte="Remove this status" placement="top"><a href="javascript:;" onclick="deleteAnswerBlock(this); return false;" class="remove"><i class="fa fa-times"></i></a>
                </div>
            </div>
        </div>
    </div>
    `;
}

function validate_form() {

    $("#edit_status").validate({
        ignore: [],
        rules: {
            <?php foreach ($application_status as $status) { ?>
            <?php echo $status['css_class']; ?>: {
                required: true,
                pattern: /^[a-zA-Z0-9\s]+$/i
            },
            <?php } ?>
            <?php foreach ($application_status as $status) { ?>
            <?php echo 'order_'.$status['css_class']; ?>: {
                required: true,
                number: true,
                //                        max: 12,
                min: 1
            },
            <?php } ?>
        },
        messages: {
            <?php foreach ($application_status as $status) { ?>
            <?php echo $status['css_class']; ?>: {
                required: 'Status name is required',
                pattern: 'Status must be Alpha Numeric only.'
            },
            <?php } ?>
            <?php foreach ($application_status as $status) { ?>
            <?php echo 'order_'.$status['css_class']; ?>: {
                required: 'Order is required',
                number: 'Please enter a valid number',
                //                    max: 'Please enter a number between 1 and 12',
                min: 'Please enter order minimum 1'
            },
            <?php } ?>
        },
        submitHandler: function(form) {
            handleFormSubmission();
        }
    });
}

function deleteAnswerBlock(id) {
    $(id).closest('.jsRow').remove();
}

//
function handleFormSubmission() {
    // set the sort
    callSort();
    //
    let post = [];
    //
    $('.jsRow').map(function() {
        post.push({
            status: $(this).find('.jsStatus').val().trim(),
            color: $(this).find('.jsColor').val().trim(),
            type: $(this).find('.jsType').val().trim(),
            order: parseInt(sort[$(this).data('id')]) + 1,
            id: $(this).data('id')
        });
    });

    $('.jsLoader').show(0);
    
    //
    $.post("<?=base_url('Application_status/handler');?>", {
        data: post
    }, function(resp){
        $('.jsLoader').hide(0);
        alertify.alert('SUCCESS!', 'You have successfully updated the status.', function(){
            window.location.reload();
        });
    })
}

//
let sort = {};
//
$('#edit_status').submit(function(e) {
    e.preventDefault();
});
//
callDager();
//
function callDager() {
    $(".jsDraggable").sortable({
        placeholder: "ui-state-highlight"
    });
}
//
function callSort() {
    sort = {};
    var
        i = 0,
        l = $('.jsRow').length;
    for (i; i < l; i++) {
        sort[$('.jsRow:eq(' + (i) + ')').data('id')] = i;
    }
}

$('.jsLoader').hide(0);
</script>