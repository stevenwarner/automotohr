<!-- Preview Latest Document Modal Start -->
<div id="fillable_history_track_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Fillable Verification Actions History
                </h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive full-width">
                    <table class="table table-plane table-striped">
                        <caption></caption>
                        <thead>
                            <tr>
                                <th scope="col" class="col-lg-4">Document</th>
                                <th scope="col" class="col-lg-4 text-right" colspan="4">Employee</th>
                                <th scope="col" class="col-lg-4 text-right" colspan="4">Action Date</th>
                                <th scope="col" class="col-lg-4 text-right" colspan="4">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<script type="text/javascript">
    function show_document_track(type, sid) {
        //
        $('#fillable_history_track_modal tbody').html('<tr><td colspan="12"><p class="alert alert-info text-center">Please wait while we fetch the document trail.</p></td></tr>');
        //
        $.get("<?= base_url('eeoc/get_trail'); ?>/" + (sid) + "/" + (type) + "")
            .success(function(resp) {
                //
                if (type == 'assigned') {
                    $('#fillable_history_track_modal .modal-title').html("Assigned Verification Actions History");
                }

                $('#fillable_history_track_modal tbody').html(resp);

            })
            .error();
        //
        $('#fillable_history_track_modal').modal('show');
    }
</script>