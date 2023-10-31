<div id="carouselExampleIndicators" class="carousel slide position-relative" data-ride="carousel">
    <div class="carousel-inner">
        <?php $bullets = ''; ?>
        <?php foreach ($slider as $index => $slide) : ?>
            <?php
            $bullets .= '
                <div class="bullet" data-target="#carouselExampleIndicators" data-slide-to="' . $index . '" class="' . ($index === 0 ? 'active' : '') . '">
                    <div class="' . ($index === 0 ? 'divider_active' : 'divider_inactive') . '"></div>
                    <p class="detail mb-0 ms-2 active_slider">' . (($index + 1) ? '0' . ($index + 1) : ($index + 1)) . '</p>
                </div>';
            ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> position-relative">
                <div class="slider-one-background d-flex" style="background-image: url('<?= $slide['image'] ?>')">
                    <div class="slider d-flex flex-lg-row flex-column align-items-lg-center align-items-start justify-content-center my-lg-0 my-5">
                        <div class="d-flex mb-xl-5 mb-0 slider-content">
                            <div class="px-3 ms-lg-5 me-lg-3 mx-0 d-flex flex-column align-items-lg-start align-items-center justify-content-center text-center-onmobile">
                                <h1 class="slider_heading title w-70">
                                    <?= $slide['title']; ?>
                                </h1>
                                <p class="slider-detail mt-4 slider_detail opacity-eighty auto-mobile-slider-detail-color">
                                    <?= $slide['sub_title']; ?>
                                </p>
                                <a href="<?= base_url($slide['link']); ?>" class="button jsButtonAnimate explore_btn d-flex top-button text-white mt-5">
                                    <p class="btn-text mb-0"><?= $slide['link_text']; ?></p>
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </a>
                            </div>
                        </div>
                        <div class="d-lg-flex d-none auto-d-lg-flex-width"></div>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
        <!--  -->
        <div class="carousel-indicators desktop-bullets">
            <?= $bullets; ?>
        </div>
    </div>
</div>