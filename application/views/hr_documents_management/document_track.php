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
                    <table class="table table-plane">
                        <thead>
                            <tr>
                                <th class="col-lg-4">Document Name</th>
                                <th class="col-lg-4 text-center" colspan="4">Employee Name</th>
                                <th class="col-lg-4 text-center" colspan="4">Action Date</th>
                                <th class="col-lg-4 text-center" colspan="4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($track_history as $track) { ?>
                                <tr>
                                    <td class="col-lg-4">
                                        <?php 
                                            if ($track['document_type'] == "eeoc") {
                                                echo "EEOC Fillable";
                                            } else if ($track['document_type'] == "w4") {
                                                echo "W4 Fillable";
                                            } else if ($track['document_type'] == "w9") {
                                                echo "W9 Fillable";
                                            } else if ($track['document_type'] == "i9") {
                                                echo "I9 Fillable";
                                            }
                                        ?>
                                    </td>
                                    <td class="col-lg-4 text-center" colspan="4">
                                        <?php 
                                            echo getUserNameBySID($track['user_sid']);
                                        ?>
                                    </td>
                                    <td class="col-lg-4 text-center" colspan="4">
                                        <?php 
                                            echo reset_datetime(array( 'datetime' => $track['created_at'], '_this' => $this));
                                        ?>
                                    </td>
                                    <td class="col-lg-4 text-center" colspan="4">
                                        <?php
                                            if ($track['action'] == "revoke") {
                                                echo "Revoke Document";
                                            } else if ($track['action'] == "assign") {
                                                echo "Assign Document";
                                            } else if ($track['action'] == "completed") {
                                                echo "Competed Document";
                                            }
                                        ?>    
                                    </td>
                                </tr>
                            <?php } ?>  
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
<!-- Preview Latest Document Modal Modal End -->

<script type="text/javascript">
    function show_document_track (source) {
        $('#fillable_history_track_modal').modal('show');
    }
</script>
