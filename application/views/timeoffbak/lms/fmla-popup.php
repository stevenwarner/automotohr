
<!-- FMLA model -->
<div class="modal fade js-fmla-popup" id="js-fmla-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
               <!-- FMLA forms -->
                <div class="js-page" id="js-fmla">
                    <span class="pull-right" style="margin: 5px 10px 20px;"><button class="btn btn-info btn-5 js-shift-page">Back</button></span>
                    
                    <div class="js-form-area" data-type="health" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/health'); ?>
                    </div>
                    <div class="js-form-area" data-type="medical" style="display: none;">
                        <?php $this->load->view('timeoff/fmla/employee/medical'); ?>
                    </div>
                   
                </div> 
            </div>
        </div>
    </div>
</div>