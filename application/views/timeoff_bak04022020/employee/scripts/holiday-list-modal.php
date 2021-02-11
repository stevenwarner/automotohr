<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">

<!-- Modal -->
<div class="modal fade" id="js-timeoff-holiday-list-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Company Holidays</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="bg-success">
                                        <th>Holiday</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-5 js-btn-view" data-dismiss="modal">Close</button>
            </div>
        </div>
  </div>
</div>

<style>
#js-timeoff-holiday-list-modal th{
    font-size: 20px;
    font-weight: bold;
}
#js-timeoff-holiday-list-modal td{
    font-size: 20px;
}
</style>
