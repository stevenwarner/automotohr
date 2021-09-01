<div class="csPageWrap">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('payroll/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="container-fluid">
        <div class="row">
            <!-- Side Bar -->
            <?php $this->load->view('employee_info_sidebar_ems'); ?>
            <!-- Main Content Area -->
            <div class="col-md-9">
                <!-- Main Content Area -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB9">
                            Run Payroll
                        </h1>
                        <hr>
                    </div>
                </div>
                <?php $this->load->view('payroll/partials/create_step_1');?>
            </div>
        </div>
    </div>
</div>

<!-- Add Models -->
<link rel="stylesheet" href="<?=base_url('assets/css/SystemModel.css');?>">
<script src="<?=base_url("assets/js/SystemModal.js");?>"></script>

<script>
    $(function(){
        //
        var step = "<?=$step;?>";
        //
        const MAIN_LOADER = 'main_loader';
        //
        var isAJAX = null;
        //
        function fetchPayrollView(){
            //
            if(isAJAX !== null){
                return;
            }
            //
            ml(true, MAIN_LOADER);
            //
            isAJAX = $.get(
                "<?=base_url("get_payroll_step");?>/"+step
            ).done(function(resp){
                //
                isAJAX = null;
                //
                console.log(resp);
            });
        }
        // 
        // fetchPayrollView();
    });
</script>