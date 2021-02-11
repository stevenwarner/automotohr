<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">Select Your Theme</span>
                        </div>
                        <div class="carousel">
                            <div class="mask">
                                <div class="slideset">
                                    <?php foreach ($theme as $themes) { ?>
                                        <div class="slide <?php if ($themes['theme_status'] == 1) { ?> active_theme <?php } ?>">
                                            <div class="theme_box">
                                                <div class="theme_img">
                                                    <img src="<?= base_url() ?>assets/images/<?= $themes['theme_image'] ?>" alt="">
                                                </div>
                                                <div class="theme_info">
                                                    <ul>
                                                        <li><a id="<?= $themes['sid'] ?>" href="javascript:void(0);" onclick="activeTheme(this.id)" ><?php if ($themes['theme_status'] == 1) { ?>Activated <?php } else { ?>Activate<?php } ?></a></li>
                                                        <li class="active_theme_btn"><a href="<?= base_url() ?>customize_appearance/<?= $themes['sid'] ?>" class="">Customize</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="theme_options">
                                                <div class="theme_name">
                                                    <h2><?= $themes['theme_name'] ?></h2>
                                                    <span>Select Your Theme</span>
                                                </div>
                                                <div class="theme_customize">
                                                    <a href="<?= base_url() ?>customize_appearance/<?= $themes['sid'] ?>">customize</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function activeTheme(id) {
        console.log('I am In '+id)
        url = "<?= base_url() ?>appearance/theme_status";
        alertify.confirm("Confirmation", "Are you sure to Activate selected Theme?",
                function () {
                    $.post(url, {
                        action: "update", id: id
                    })
                            .done(function (data) {
                                location.reload();
                            });
                    alertify.success('Activated');

                },
                function () {
                });
    }
</script>