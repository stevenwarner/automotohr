<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-12">
                    <!-- Sidebar -->
                    <?php $this->load->view('2022/sidebar'); ?>
                </div>
                <div class="col-md-9 col-sm-12">
                    <!--  -->
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <h3 class="_csF26">Let's get started</h3>
                        </div>
                        <div class="col-md-4 col-sm-12 text-right _csVm">
                            <a href="" class="btn _csB1 _csF16 _csF2 _csR5 _csMt20">
                                <i class="fa fa-times-circle _csF16" aria-hidden="true"></i>&nbsp;Cancel
                            </a>
                        </div>
                    </div>
                    <?php $this->load->view('2022/footer_scripts_2022'); ?>
                    <?php $this->load->view('es/partials/steps'); ?>
                    <?php $this->load->view('es/partials/step_' . ($step)); ?>
                </div>
            </div>
        </div>
    </div>
  


    