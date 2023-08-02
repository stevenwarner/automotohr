<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!--  -->
            <?php $this->load->view('loader', ['props' => 'id="jsPayrollLoader"']); ?>
            <!--  -->
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('v1/payrolls/left_sidebar'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <!--  -->
                <?php $this->load->view('v1/payrolls/off_cycle/' . $loadPage); ?>
            </div>
        </div>
    </div>
</div>