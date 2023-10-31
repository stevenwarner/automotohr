<div class="modal fade" id="jsScheduleDemoModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Schedule your free demo</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $this->load->view("v1/app/partials/demo_form", [
                    "buttonClass"  => "w-100"
                ]); ?>
            </div>
        </div>
    </div>
</div>