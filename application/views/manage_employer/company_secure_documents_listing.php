<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">
                            <a class="dashboard-link-btn" href="<?php echo base_url('my_settings') ?>"><i class="fa fa-chevron-left"></i>Settings</a>
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>
                            <?= $title; ?>
                        </span>
                    </div>

                    <div class="message-action">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="hr-box">
                                    <div class="hr-innerpadding">
                                        <form enctype="multipart/form-data" id="document_search" method="get" novalidate="novalidate">
                                            <div class="row" style="margin-top: 12px;">
                                                <div class="col-lg-8 col-md-4 col-xs-12 col-sm-4">
                                                    <label>Document title </label>
                                                    <input type="text" name="title" id="document_title" value="<?php echo $documentTitle; ?>" class="invoice-fields">
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-block btn-success">Apply Filter</button>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                    <label>&nbsp;</label>
                                                    <button type="button" class="btn btn-block btn-success" id="clear_filter">Clear Filter</button>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="message-action-btn">
                                    <a class="submit-btn" href="<?php echo base_url('company/documents/secure/add'); ?>">Upload Document(s)</a>
                                    <?php if (!empty($secure_documents)) { ?>
                                        <button class="submit-btn jsCopySecureDocuments">
                                            Copy Document(s)
                                        </button>
                                        <button class="submit-btn jsDownloadSecureDocumentsZip">
                                            Download Document(s)
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($secure_documents)) { ?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                                <div class="table-responsive table-outer">
                                    <div class="data-table">
                                        <table id="categories_table" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="vertical-align: middle;">
                                                        <label class="control control--checkbox">
                                                            <input type="checkbox" id="jsSelectAll" />
                                                            <div class="control__indicator" style="top: -7px;"></div>
                                                        </label>
                                                    </th>
                                                    <th class="col-xs-4">Document Title</th>
                                                    <th class="col-xs-3">Created By</th>
                                                    <th class="col-xs-2">Created At</th>
                                                    <th class="col-xs-3">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($secure_documents as $key => $documents) { ?>
                                                    <th colspan="5" style="background: #ccc;"><?php echo $key;?></th>
                                                    <?php foreach ($documents as $key => $document) { ?>
                                                        <tr>
                                                            <td scope="col" style="vertical-align: middle;">
                                                                <label class="control control--checkbox">
                                                                    <input type="checkbox" name="documents_ids[]" value="<?= $document['sid']; ?>" class="jsSelectSingle" />
                                                                    <div class="control__indicator" style="top: -7px;"></div>
                                                                </label>
                                                            </td>
                                                            <td style="vertical-align: middle;" class="jsDocumentName"><?php echo $document['document_title']; ?></td>
                                                            <td style="vertical-align: middle;"><?php echo getUserNameBySID($document['created_by']) ?></td>
                                                            <td style="vertical-align: middle;"><?php echo formatDateToDB($document['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></td>
                                                            <td style="vertical-align: middle;">
                                                                <button class="btn btn-info jsViewDocument" data-key=<?= $document['document_s3_name']; ?>>
                                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                                    &nbsp;View
                                                                </button>
                                                                <a class="btn btn-success csF16" href="<?php echo base_url('download/file/' . ($document['document_s3_name']) . ''); ?>">
                                                                    <i class="fa fa-download csF16" aria-hidden="true"></i>
                                                                    &nbsp;Download
                                                                </a>
                                                                <a class="btn btn-danger csF16 jsDeleteSecureDocument" data-id="<?= $document['sid']; ?>" href="javascript:;">
                                                                    <i class="fa fa-trash csF16" aria-hidden="true"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div id="show_no_jobs">
                                                    <span class="applicant-not-found">No Documents Found!</span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="jsSelectEmployeeModal" class="modal fade" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Select Employee</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <label>Select Employee</label>
                        <select name="employeeId" id="jsSelectedEmployeeId">
                            <?php foreach ($employees as $emp) { ?>
                                <option value="<?= $emp['id']; ?>"><?= remakeEmployeeName($emp); ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-12" style="margin-top: 12px;">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong>Selected Document(s)</strong>
                                <span id="jsSelectedCount" class="pull-right">(0) selected</span>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="col-xs-1">#</th>
                                                <th class="col-xs-5">Document Title</th>
                                            </tr>
                                        </thead>
                                        <tbody id="jsPrefillDocument">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="submit-btn jsStartCopy">
                    Start Copy
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('loader', ['props' => 'id="jsCopyDocumentLoader"']); ?>

<script>
    //
    var current = 1;
    var total = 0;
    var selectedDocuments = {};
    //
    $(document).ready(function() {
        $('#jsSelectedEmployeeId').select2({
            closeOnSelect: false
        });
    });
    //
    $("#clear_filter").click(function() {
        $("form").submit();
    });
    //
    $('#jsSelectAll').click(function() {
        //
        $('.jsSelectSingle').prop('checked', false);
        //
        if ($('#jsSelectAll').prop('checked')) {
            $('.jsSelectSingle').prop('checked', true);
        }
    });
    //
    $('.jsSelectSingle').click(function() {
        useSelect();
    });
    //
    function useSelect() {
        //
        if ($('.jsSelectSingle:checked').length != $('.jsSelectSingle').length) {
            $('#jsSelectAll').prop('checked', false);
        } else {
            $('#jsSelectAll').prop('checked', true);
        }
    }
    //
    $('.jsCopySecureDocuments').click(function(event) {
        //
        event.preventDefault();
        //
        selectedDocuments = get_all_selected_documents();
        //
        if (selectedDocuments.length === 0) {
            alertify.alert('Error!', 'Please select at least one document to copy.', function() {
                return;
            });
            //
            return;
        }
        //
        $('#jsSelectedCount').html('(' + selectedDocuments.length + ') Selected');
        $('#jsSelectEmployeeModal').modal('show');
        //
    });
    //
    $('.jsStartCopy').click(function(event) {
        //
        event.preventDefault();
        //
        var data = $('#jsSelectedEmployeeId').select2('data');
        var employeeName = data[0].text;
        var employeeId = data[0].id;
        //
        //
        alertify.confirm('Do you really want to copy documents into <strong>' + employeeName + '</strong> account?', function() {
            //
            current = 1;
            //
            total = selectedDocuments.length;
            //
            startCopyDocumentProcess(employeeId);
            //
            $('#jsSelectEmployeeModal').modal('hide');
        });
    });

    function startCopyDocumentProcess(employeeId) {
        //
        var index = current;
        //
        var document = selectedDocuments[--index];
        //
        if (document === undefined) {
            //
            loader(false);
            //
            alertify.alert('Success!', 'You have successfully copy ' + total + ' documents', function() {
                window.location.reload();
                return;
            });
            //
            return;
        }
        //
        var text = '<p>Please wait, while we are copy document <b>' + (document.document_name) + '</b></p><p>' + (current) + ' of ' + (total) + '</p>';
        //
        loader(true, text);
        //
        $.post("<?= base_url('copy_manual_secure_document'); ?>", {
            employee_sid: employeeId,
            document_sid: document.document_sid
        }).done(function() {
            //
            current++;
            //
            startCopyDocumentProcess(employeeId);
        });
    }

    function get_all_selected_documents() {
        var tmp = [];
        var html = '';
        //
        $.each($('.jsSelectSingle:checked'), function(i) {
            var obj = {};
            //
            obj.document_sid = parseInt($(this).val());
            obj.document_name = $(this).closest('tr').find('td.jsDocumentName').text();
            //
            html += `<tr>`;
            html += `   <td>${i+1}</td>`;
            html += `   <td>${obj.document_name}</td>`;
            html += `</tr>`;
            //
            tmp.push(obj);
        });
        //
        $("#jsPrefillDocument").html(html);
        //
        return tmp;
    }

    //
    function loader(doShow, text) {
        //
        if (doShow) {
            $('#jsCopyDocumentLoader').show(0);
            $('#jsCopyDocumentLoader .jsLoaderText').html(text);
        } else {
            $('#jsCopyDocumentLoader').hide(0);
            $('#jsCopyDocumentLoader .jsLoaderText').html('Please wait, while we are processing your request.');
        }
    }

    //
    $('.jsDeleteSecureDocument').click(function(event) {
        //
        event.preventDefault();
        //
        var documentId = $(this).data("id");
        //
        alertify.confirm('Do you really want to delete this document?', function() {
            //
            var text = '<p>Please wait, while we are delete the document</p>';
            //
            loader(true, text);
            //
            $.post("<?= base_url('delete_manual_secure_document'); ?>", {
                document_sid: documentId
            }).done(function() {
                //
                loader(false);
                //
                alertify.alert('Success!', 'You have successfully delete the document.', function() {
                    window.location.reload();
                    return;
                });
            });
        });
        
    });

    $('.jsDownloadSecureDocumentsZip').click(function(event) {
        //
        event.preventDefault();
        //
        selectedDocuments = get_all_selected_documents();
        //
        if (selectedDocuments.length === 0) {
            alertify.alert('Error!', 'Please select at least one document to download.', function() {
                return;
            });
            //
            return;
        }
        //
        // var url = '<?php echo base_url('company/documents/secure/download'); ?>';
        // window.open(url, '_blank');
        //
        //
        var text = '<p>Please wait, while we are downloading documents. </p>';
        //
        loader(true, text);
        //
        var ids = [];
        //
        selectedDocuments.map(function (document) {
            ids.push(document.document_sid)
        })
        //
        $.post("<?= base_url('company/documents/secure/download'); ?>", {
            documents: ids
        }).done(function(resp) {
            //
            loader(false);
            //
            // var url = '<?php echo base_url('company/documents/secure/download'); ?>';
            window.open(resp.url, '_blank');
            //
            alertify.alert('Success!', 'You have successfully downloaded documents.', function() {
                return;
            });
        });
    });
</script>