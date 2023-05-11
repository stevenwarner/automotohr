<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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

    .vam {
        vertical-align: middle !important;
    }

    .thumbnail {
        border-radius: 5px;
        box-shadow: 0 0 5px 1px #eee;
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
                                            <div class="hr-search-main search-collapse-area" style="display: block;">
                                                <div class="row">
                                                    <div class="col-xs-12 col-md-6">
                                                        <?php $keyword = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                        <div class="field-row">
                                                            <label>Keyword:</label>
                                                            <input class="invoice-fields" id="jsKeyword" name="keyword" value="<?php echo $keyword; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3">
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
                                                    <div class="col-xs-12 col-md-3">
                                                        <?php $method = $this->uri->segment(6); ?>
                                                        <div class="field-row">
                                                            <label>Method</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="jsRequestMethod" name="request_method">
                                                                    <option value="all">All</option>
                                                                    <option <?php echo $method == 'get' ? 'selected="selected"' : ''; ?> value="get">GET</option>
                                                                    <option <?php echo $method == 'post' ? 'selected="selected"' : ''; ?> value="post">POST</option>
                                                                    <option <?php echo $method == 'put' ? 'selected="selected"' : ''; ?> value="put">PUT</option>
                                                                    <option <?php echo $method == 'delete' ? 'selected="selected"' : ''; ?> value="delete">DELETE</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3">
                                                        <?php $start = $this->uri->segment(7); ?>
                                                        <?php $start = empty($start) || is_null($start) ?  date('m-d-Y') : $start; ?>
                                                        <div class="field-row">
                                                            <label>Date From:</label>
                                                            <input class="invoice-fields datepicker" id="date_start" name="date_start" value="<?php echo $start; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3">
                                                        <?php $end = $this->uri->segment(8); ?>
                                                        <?php $end = empty($end) || is_null($end) ?  date('m-d-Y') : $end; ?>
                                                        <div class="field-row">
                                                            <label>Date To:</label>
                                                            <input class="invoice-fields datepicker" id="date_end" name="date_end" value="<?php echo $end; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3">
                                                        <div class="field-row">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-success btn-block btn-equalizer" onclick="func_apply_filters();">Search</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-3">
                                                        <div class="field-row">
                                                            <label>&nbsp;</label>
                                                            <a href="<?= base_url('manage_admin/reports/complynet_report'); ?>" class="btn btn-default btn-block btn-equalizer">Clear Filter</a>
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
                                                    <h3 id="jsSuccessCalls"><?= $boxArray['success']; ?></h3>
                                                    <h4><strong>Success</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail error-block">
                                                <div class="caption">
                                                    <h3 id="jsErrorCalls"><?= $boxArray['error']; ?></h3>
                                                    <h4><strong>Error</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail success-block">
                                                <div class="caption">
                                                    <h3 id="jsGetCalls"><?= $boxArray['get']; ?></h3>
                                                    <h4><strong>GET</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail post-block">
                                                <div class="caption">
                                                    <h3 id="jsPostCalls"><?= $boxArray['post']; ?></h3>
                                                    <h4><strong>POST</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail put-block">
                                                <div class="caption">
                                                    <h3 id="jsPUTCalls"><?= $boxArray['put']; ?></h3>
                                                    <h4><strong>PUT</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="thumbnail error-block">
                                                <div class="caption">
                                                    <h3 id="jsDeleteCalls"><?= $boxArray['delete']; ?></h3>
                                                    <h4><strong>DELETE</strong></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <?php if (!empty($links)) { ?>
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
                                                                            <th class="col-xs-3">Job<br>Reference</th>
                                                                            <th class="col-xs-3">URL</th>
                                                                            <th class="col-xs-1">Request<br>Type</th>
                                                                            <th class="col-xs-1">Response<br>Code</th>
                                                                            <th class="col-xs-1">Status</th>
                                                                            <th class="col-xs-2">Date<br>Time</th>
                                                                            <th class="col-xs-1 text-right">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <thead>

                                                                        <?php if (!empty($calls)) { ?>
                                                                            <?php foreach ($calls as $call) { ?>
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
                                                                                <tr class="<?php echo $call['response_code'] != 200 ? 'bg-danger' : '';  ?>">
                                                                                    <td class="vam"><?php echo $call['uuid_field']; ?></td>
                                                                                    <td class="vam"><?php echo $call['request_url']; ?></td>
                                                                                    <td class="vam text-center">
                                                                                        <strong class="<?php echo $methodColor; ?>">
                                                                                            <?php echo $call['request_method']; ?>
                                                                                        </strong>
                                                                                    </td>
                                                                                    <td class="vam text-center">
                                                                                        <strong>
                                                                                            <?php echo $call['response_code']; ?>
                                                                                    </td>
                                                                                    </strong>
                                                                                    <td class="vam  text-center">
                                                                                        <?php
                                                                                        if ($call['response_code'] == 200) {
                                                                                            echo '<strong class="text-success">SUCCESS</strong>';
                                                                                        } else {
                                                                                            echo '<strong class="text-danger">ERROR</strong>';
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td class="vam">
                                                                                        <?= formatDateToDB($call['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?>
                                                                                    </td>
                                                                                    <td class="vam text-right">
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
                                                    <?php if (!empty($links)) { ?>
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
    $(document).ready(function() {
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });

    function func_apply_filters() {
        var keyword = $('#jsKeyword').val();
        var status = $('#jsRequestStatus').val();
        var method = $('#jsRequestMethod').val();
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var base_url = '<?php echo base_url('manage_admin/reports/complynet_report'); ?>';

        keyword = keyword == '' || keyword == undefined || keyword == null ? 'all' : encodeURIComponent(keyword);
        status = status == '' || status == undefined || status == null ? 'all' : encodeURIComponent(status);
        method = method == '' || method == undefined || method == null ? 'all' : encodeURIComponent(method);
        date_start = date_start == '' || date_start == undefined || date_start == null ? 'all' : encodeURIComponent(date_start);
        date_end = date_end == '' || date_end == undefined || date_end == null ? 'all' : encodeURIComponent(date_end);

        var url = base_url + '/' + keyword + '/' + status + '/' + method + '/' + date_start + '/' + date_end;
        window.location = url;
    }

    function showCallDetail(sid) {
        $('#call_modal').appendTo("body").modal('show');
        $('#show_detail_loader').show();
        //
        $.ajax({
            type: 'GET',
            url: '<?= base_url('manage_admin/reports/complynet_report/getDetail') ?>' + '/' + sid,
            success: function(data) {

                // set decoded json
                let jsonToObjRequestBody = JSON.parse(data.Response.request_body);

                $('.jsRequestURL').html(data.Response.request_url);
                $('.jsResponseCode').html(data.Response.response_code);
                $('.jsRequestHeader').html(jsonToObjRequestBody.headers.join('<br/>'));
                $('.jsRequestBody').html(jsonToObjRequestBody['body'] ? JSON.stringify(JSON.parse(jsonToObjRequestBody['body']), undefined, 4) : 'NULL');

                // set response
                $('.jsResponseHeader').html(JSON.stringify(JSON.parse(data.Response.response_headers), undefined, 4));
                $('.jsResponseBody').html(JSON.stringify(JSON.parse(data.Response.response_body), undefined, 4));
                $('#show_detail_loader').hide();
            }
        });
    }
</script>