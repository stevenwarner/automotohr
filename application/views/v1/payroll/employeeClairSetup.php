<br>
<div class="container">
    <!--  -->
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="<?= base_url("dashboard"); ?>" class="btn btn-black">
                <i class="fa fa-arrow-left"></i>
                Dashboard
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <br>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-large">
                        <strong>
                            <i class="fa fa-cogs text-orange" aria-hidden="true"></i>
                            Set up Clair
                        </strong>
                    </h2>
                </div>
                <div class="panel-body">
                    <iframe src="<?= $flow; ?>" style="border: 0; width: 100%; height: 800px;" title="Clair Integration"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>