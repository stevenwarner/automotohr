<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <div class="row">
            <!-- left sidebar -->
            <?php $this->load->view('payroll/pages/sidebar', ['mainIndex'=> "tax_details", "subIndex" =>""]);?>
            <!-- Main Content -->
            <div class="col-md-9 col-sm-12">
                <!-- Heading -->
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            Tax details
                        </h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="csF18 csB7">
                            We'll continue your tax setup on gusto.com
                        </h1>
                        <p class="csF16">
                            We're taking you into Gusto for a few minutes. This will open a new tab to complete these details.
                        </p>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <a class="btn btn-orange csF16 csB7" href="https://gusto.com/" target="_blank">
                            Enter tax details on gusto.com
                        </a>
                    </div>
                </div>
                <br>
            </div>
        </div>

    </div>
</div>
