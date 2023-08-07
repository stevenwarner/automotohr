<style>
    .gwb_HebOV {
    right: 1.2rem;
}

.gwb_2SlKx {
    color: #6c6c72;
    display: flex;
    align-items: center;
    pointer-events: none;
    position: absolute;
    top: 50%;
    transform: translate(0%, -50%);
    white-space: nowrap;
}
.gwb_jsuEB.gwb_23CWS {
    padding-right: calc( 1.2rem + var(--afterWidth, 0) + 0.8rem );
}

.gwb_jsuEB {
    background-color: #f4f4f3;
    border: 0;
    border-radius: 0.4rem;
    caret-color: #005961;
    color: #1c1c1c;
    font: inherit;
    padding-top: 1rem;
    padding-bottom: 1rem;
    padding-left: 1.2rem;
    padding-right: 1.2rem;
    width: 100%;
}

.gwb_2816k {
    font-size: 1.6rem;
    font-weight: 400;
    line-height: 2.4rem;
    letter-spacing: 0.02rem;
    border-radius: 0.4rem;
    display: block;
    overflow: hidden;
    position: relative;
    isolation: isolate;
}

.gwb_18B7c {
    height: 100%;
    left: 0;
    pointer-events: none;
    position: absolute;
    top: 0;
    width: 100%;
}

.csPageWrap tr th {
    font-size: 15px !important;
    text-transform: capitalize !important;
}

</style>


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


<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>



<script>
    var loaderTarget = $('#my_loader')

    loader(false);

    $('#jsSelectAll').click(function() {
        //
        $('.jsSelectSingle').prop('checked', false);
        //
        if ($('#jsSelectAll').prop('checked')) {
            $('.jsSelectSingle').prop('checked', true);
        }
    });

    $('.js-action-btn').click(function() {
          var step = $(this).data('step');
        //
        if(step!='step_0'){
        window.location.href = "<?= base_url('payrolls/off_cycle'); ?>/" + step;
        }
        
    });


    function loader(is_show) {
        if (is_show == true) loaderTarget.fadeIn(500);
        else loaderTarget.fadeOut(500);
    }
</script>