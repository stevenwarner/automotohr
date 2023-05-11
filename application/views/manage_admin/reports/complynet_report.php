<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css">
    .caption h3 {
        margin-top: 0px !important;
        color: #fff !important;
    }

    .caption h4 {
        margin-bottom: 0px !important;
        color: #fff !important;
    }

    .success-block {
        background: #28a745 !important;
    }

    .error-block {
        background: #dc3545 !important;
    }

    .post-block {
        background: #007bff !important;
    }

    .put-block {
        background: #674ead !important;
    }

    pre {
        background: #000 !important;
        color: #fff !important;
    }
</style>
<?php 
    $successCalls = 0;
    $errorCalls = 0;
    $getCalls = 0;
    $postCalls = 0;
    $deleteCalls = 0;
    $putCalls = 0;
?>
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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-search-criteria opened">
                                                <strong>Click to modify search criteria</strong>
                                            </div>
                                            <div class="hr-search-main search-collapse-area" style="display: block;" >
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                        <div class="field-row">
                                                            <label>Keyword:</label>
                                                            <input class="invoice-fields" id="jsKeyword" name="keyword" value="<?php echo $keyword; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-4">
                                                        <?php $status = $this->uri->segment(5); ?>
                                                        <div class="field-row">
                                                            <label>Status</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="jsRequestStatus" name="request_status">
                                                                    <option value="all">All</option>
                                                                    <option <?php echo $status == 'success' ? 'selected="selected"' : ''; ?> value="success">Success</option>
                                                                    <option <?php echo $status == 'error' ? 'selected="selected"' : ''; ?> value="error">Error</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <?php $start = $this->uri->segment(6); ?>
                                                        <?php $start = empty($start) || is_null($start) ?  date('m-d-Y') : $start; ?>
                                                        <div class="field-row">
                                                            <label>Date From:</label>
                                                            <input class="invoice-fields datepicker" id="date_start" name="date_start" value="<?php echo $start; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <?php $end = $this->uri->segment(7); ?>
                                                        <?php $end = empty($end) || is_null($end) ?  date('m-d-Y') : $end; ?>
                                                        <div class="field-row">
                                                            <label>Date To:</label>
                                                            <input class="invoice-fields datepicker" id="date_end" name="date_end" value="<?php echo $end; ?>" />
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-xs-2">
                                                        <div class="field-row">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-success btn-block btn-equalizer" onclick="func_apply_filters();">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-xs-2">
                                            <div class="thumbnail success-block">
                                                <div class="caption">
                                                    <h3 id="jsSuccessCalls">0</h3>
                                                    <h4>Success</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail error-block">
                                                <div class="caption">
                                                    <h3 id="jsErrorCalls">0</h3>
                                                    <h4>Error</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail success-block">
                                                <div class="caption">
                                                    <h3 id="jsGetCalls">0</h3>
                                                    <h4>GET</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail post-block">
                                                <div class="caption">
                                                    <h3 id="jsPostCalls">0</h3>
                                                    <h4>POST</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail put-block">
                                                <div class="caption">
                                                    <h3 id="jsPUTCalls">0</h3>
                                                    <h4>PUT</h4>
                                                </div>
                                            </div>
                                        </div>         
                                        <div class="col-xs-2">
                                            <div class="thumbnail error-block">
                                                <div class="caption">
                                                    <h3 id="jsDeleteCalls">0</h3>
                                                    <h4>DELETE</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <?php if(!empty($links)) { ?>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php echo $links; ?>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                    <?php } ?>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover table-striped table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-3">Job Reference</th>
                                                                            <th class="col-xs-3">URL</th>
                                                                            <th class="col-xs-1">Request Type</th>
                                                                            <th class="col-xs-1">Response Code</th>
                                                                            <th class="col-xs-1">Status</th>
                                                                            <th class="col-xs-1">Date Time</th>
                                                                            <th class="col-xs-2">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <thead>

                                                                    <?php if(!empty($calls)) { ?>
                                                                        <?php foreach($calls as $call) { ?>
                                                                            <?php 
                                                                                $methodColor =  '';
                                                                                //
                                                                                if ($call['response_code'] == 200) {
                                                                                    $successCalls = $successCalls + 1;
                                                                                } else {
                                                                                    $errorCalls = $errorCalls + 1;
                                                                                }
                                                                                //
                                                                                if ($call['request_method'] == 'GET') {
                                                                                    $methodColor =  'text-success';
                                                                                    $getCalls = $getCalls + 1;
                                                                                }
                                                                                //
                                                                                if ($call['request_method'] == 'POST') {
                                                                                    $postCalls = $postCalls + 1;
                                                                                    $methodColor =  'text-primary';
                                                                                }
                                                                                //
                                                                                if ($call['request_method'] == 'PUT') {
                                                                                    $putCalls = $putCalls + 1;
                                                                                    $methodColor =  'text-warning';
                                                                                }
                                                                                //
                                                                                if ($call['request_method'] == 'DELETE') {
                                                                                    $deleteCalls = $deleteCalls + 1;
                                                                                    $methodColor =  'text-danger';
                                                                                }
                                                                            ?>
                                                                            <tr class="<?php echo $call['response_code'] != 200 ? 'bg-danger' : '';  ?>" style="vertical-align: middle;">
                                                                                <td><?php echo $call['uuid_field']; ?></td>
                                                                                <td><?php echo $call['request_url']; ?></td>
                                                                                <td>
                                                                                    <strong class="<?php echo $methodColor;?>">
                                                                                        <?php echo $call['request_method']; ?>
                                                                                    </strong>    
                                                                                </td>
                                                                                <td>
                                                                                    <strong>
                                                                                        <?php echo $call['response_code']; ?></td>
                                                                                    </strong>    
                                                                                <td>
                                                                                    <?php 
                                                                                        if ($call['response_code'] == 200) {
                                                                                            echo '<strong class="text-success">SUCCESS</strong>';
                                                                                        } else {
                                                                                            echo '<strong class="text-danger">ERROR</strong>';
                                                                                        }
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <div><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $call['created_at'])->format('m-d-Y  h:i A'); ?></div>
                                                                                </td>
                                                                                <td>
                                                                                    <button class="btn btn-success" onclick="showCallDetail(<?php echo $call['sid']; ?>)">Show Detail</button>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td colspan="7" class="text-center">
                                                                                <span class="no-data">No Records</span>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if(!empty($links)) { ?>
                                                        <hr />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php echo $links; ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
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

<div id="call_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">View Detail</h4>
            </div>
            <div class="modal-body">
                <div class="loader" id="show_detail_loader" style="display: none;">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>

                <div class="row margin-top">
                    <div class="col-sm-12">
                        <label>Request URL</label>
                        <pre class="jsRequestURL">
                            
                        </pre>
                        <br>
                        <label>Response Code</label>
                        <pre class="jsResponseCode">
                            
                        </pre>
                    </div>
                </div>

                <div class="row margin-top">
                    <div class="col-sm-12">
                        <label>Request Header</label>
                        <pre class="jsRequestHeader">
                            
                        </pre>
                        <br>
                        <label>Request Body</label>
                        <pre class="jsRequestBody">
                            
                        </pre>
                    </div>
                </div>

                <div class="row margin-top">
                    <div class="col-sm-12">
                        <label>Response Header</label>
                        <pre class="jsResponseHeader">
                            
                        </pre>
                        <br>
                        <label>Response Body</label>
                        <pre class="jsResponseBody">
                            
                        </pre>
                    </div>
                </div>
            </div>
            <div id="approver_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script>
    $('#jsSuccessCalls').html(<?php echo $successCalls; ?>);
    $('#jsErrorCalls').html(<?php echo $errorCalls; ?>);
    $('#jsGetCalls').html(<?php echo $getCalls; ?>);
    $('#jsPostCalls').html(<?php echo $postCalls; ?>);
    $('#jsPUTCalls').html(<?php echo $putCalls; ?>);
    $('#jsDeleteCalls').html(<?php echo $deleteCalls; ?>);

    $(document).ready(function(){
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });

    function func_apply_filters()
    {
        var status = $('#jsRequestStatus').val();
        var keyword = $('#jsKeyword').val();
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var base_url = '<?php echo base_url('manage_admin/reports/complynet_report'); ?>';

        status = status == '' || status == undefined || status == null ? 'all' : encodeURIComponent(status);
        keyword = keyword == '' || keyword == undefined || keyword == null ? 'all' : encodeURIComponent(keyword);
        date_start = date_start == '' || date_start == undefined || date_start == null ? 'all' : encodeURIComponent(date_start);
        date_end = date_end == '' || date_end == undefined || date_end == null ? 'all' : encodeURIComponent(date_end);

        var url = base_url + '/'+ keyword + '/' + status + '/' + date_start + '/' + date_end ;
        window.location = url;
    }

    function showCallDetail (sid) {
        $('#call_modal').appendTo("body").modal('show');
        $('#show_detail_loader').show();
        //
        $.ajax({
            type: 'GET',
            url: '<?= base_url('manage_admin/reports/complynet_report/getDetail') ?>'+'/'+sid,
            success: function(data) {
                
                $('.jsRequestURL').html(data.request_url);
                $('.jsResponseCode').html(data.response_code);
                $('.jsRequestHeader').html(data.request_body.headers);
                $('.jsRequestBody').html(data.request_body.body != undefined ? data.request_body.body : 'NULL');
                $('.jsResponseHeader').html(JSON.stringify(JSON.parse(data.response_headers),undefined,4));
                $('.jsResponseBody').html(JSON.stringify(JSON.parse(data.response_body),undefined,4));
                $('#show_detail_loader').hide();
            }
        });
    }
</script>