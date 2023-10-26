<div class="modal fade" id="jsScheduleDemoModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Schedule your free demo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <?php $this->load->view("v1/app/partials/demo_form", [
                    "buttonClass"  => "w-100"
                ]); ?>
            </div>
        </div>
    </div>
</div>