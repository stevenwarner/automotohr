<?php $pageHeader = getPageContent('header', true)["page"]["sections"];
$activePages = getAllActivePages();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= image_url('favicon_io'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= image_url('favicon_io'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= image_url('favicon_io'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= image_url('favicon_io'); ?>/site.webmanifest">
    <title><?= $meta['title']; ?></title>
    <meta name="description" content="<?= $meta['description']; ?>">
    <meta name="keywords" content="<?= $meta['keywords']; ?>">
    <?= $pageCSS ? GetCss($pageCSS) : ""; ?>
    <?= $appCSS ?? ""; ?>
    <?= getGoogleScripts(); ?>
</head>

<body>
    <?= getGoogleBody(); ?>
    <!-- header -->
    <header class="<?= $headerFixed ? 'csAbsolute' : ''; ?>">

        <nav class="navbar navbar-expand-xl navbar-light nav-bar-margin">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= main_url(""); ?>">
                    <?= getSourceByType(
                        $pageHeader["section_0"]["sourceType"],
                        $pageHeader["section_0"]["sourceFile"],
                        '',
                        false
                    ); ?>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav mx-auto<?= $limited_menu ? '-right' : ''; ?> my-2 my-lg-0 navbar-nav-scroll">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= main_url(""); ?>"><?= $pageHeader["section_1"]["menu1Text"] ?></a>
                        </li>
                        <?php if (!$limited_menu) { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="<?= ($pageHeader["section_1"]["menu2Link"]); ?>" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= ($pageHeader["section_1"]["menu2Text"]); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-modal" aria-labelledby="navbarScrollingDropdown">
                                    <div class="display-flex">
                                        <?php
                                        $subMenu1LinkArray = explode("/", $pageHeader["section_1"]["subMenu1Link"]);
                                        if (in_array($subMenu1LinkArray[1], $activePages)) {
                                        ?>
                                            <li>
                                                <a class="dropdown-item-custom modal-anchor" href="<?= main_url($pageHeader["section_1"]["subMenu1Link"]); ?>"><?= ($pageHeader["section_1"]["subMenu1Text"]); ?></a>
                                                <p class="dropdown-item-custom">
                                                    <?= ($pageHeader["section_1"]["subMenu1Details"]); ?>
                                                </p>
                                                <hr class="dropdown-hr" />
                                            </li>
                                        <?php } ?>

                                        <hr />
                                        <?php
                                        $subMenu2LinkArray = explode("/", $pageHeader["section_1"]["subMenu2Link"]);
                                        if (in_array($subMenu2LinkArray[1], $activePages)) {
                                        ?>
                                            <li>
                                                <a class="dropdown-item-custom modal-anchor" href="<?= main_url($pageHeader["section_1"]["subMenu2Link"]); ?>"><?= ($pageHeader["section_1"]["subMenu2Text"]); ?></a>
                                                <p class="dropdown-item-custom">
                                                    <?= ($pageHeader["section_1"]["subMenu2Details"]); ?>
                                                </p>
                                                <hr class="dropdown-hr" />
                                            </li>

                                        <?php } ?>

                                        <?php
                                        $subMenu3LinkArray = explode("/", $pageHeader["section_1"]["subMenu3Link"]);
                                        if (in_array($subMenu3LinkArray[1], $activePages)) {
                                        ?>
                                            <hr class="dropdown-divider" />
                                            <li>
                                                <a class="dropdown-item-custom modal-anchor" href="<?= main_url($pageHeader["section_1"]["subMenu3Link"]); ?>"><?= ($pageHeader["section_1"]["subMenu3Text"]); ?></a>
                                                <p class="dropdown-item-custom">
                                                    <?= ($pageHeader["section_1"]["subMenu3Details"]); ?>
                                                </p>
                                                <hr class="dropdown-hr" />
                                            </li>
                                            <hr class="dropdown-divider" />
                                        <?php } ?>

                                    </div>


                                    <div class="display-flex margin-top-twenty">
                                        <?php
                                        $subMenu4LinkArray = explode("/", $pageHeader["section_1"]["subMenu4Link"]);
                                        if (in_array($subMenu4LinkArray[1], $activePages)) {
                                        ?>
                                            <li>
                                                <a class="dropdown-item-custom modal-anchor" href="<?= main_url($pageHeader["section_1"]["subMenu4Link"]); ?>"><?= ($pageHeader["section_1"]["subMenu4Text"]); ?></a>
                                                <p class="dropdown-item-custom">
                                                    <?= ($pageHeader["section_1"]["subMenu4Details"]); ?>
                                                </p>
                                                <hr class="dropdown-divider" />
                                            </li>
                                        <?php } ?>
                                        <?php
                                        $subMenu5LinkArray = explode("/", $pageHeader["section_1"]["subMenu5Link"]);
                                        if (in_array($subMenu5LinkArray[1], $activePages)) {
                                        ?>
                                            <li>
                                                <a class="dropdown-item-custom modal-anchor" href="<?= main_url($pageHeader["section_1"]["subMenu5Link"]); ?>"><?= ($pageHeader["section_1"]["subMenu5Text"]); ?></a>
                                                <p class="dropdown-item-custom">
                                                    <?= ($pageHeader["section_1"]["subMenu5Details"]); ?>
                                                </p>
                                                <hr class="dropdown-divider" />
                                            </li>
                                        <?php } ?>
                                        <?php
                                        $subMenu6LinkArray = explode("/", $pageHeader["section_1"]["subMenu6Link"]);
                                        if (in_array($subMenu6LinkArray[1], $activePages)) {
                                        ?>
                                            <li>
                                                <a class="dropdown-item-custom modal-anchor" href="<?= main_url($pageHeader["section_1"]["subMenu6Link"]); ?>"><?= ($pageHeader["section_1"]["subMenu6Text"]); ?></a>
                                                <p class="dropdown-item-custom">
                                                    <?= ($pageHeader["section_1"]["subMenu6Details"]); ?>
                                                </p>
                                                <hr class="dropdown-divider" />
                                            </li>
                                        <?php } ?>

                                    </div>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= main_url($pageHeader["section_1"]["menu3Link"]); ?>"><?= ($pageHeader["section_1"]["menu3Text"]); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= main_url($pageHeader["section_1"]["menu4Link"]); ?>"><?= ($pageHeader["section_1"]["menu4Text"]); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= main_url($pageHeader["section_1"]["menu5Link"]); ?>"><?= ($pageHeader["section_1"]["menu5Text"]); ?></a>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="d-flex flex-direction-coloumn-on-mobile">
                        <?php if (!$limited_menu) { ?>
                            <a class="btn schedule-btn" href="<?= main_url($pageHeader["section_2"]["buttonLinkSchedule"]); ?>">
                                <?= ($pageHeader["section_2"]["buttonTextSchedule"]); ?>
                            </a>
                        <?php } ?>
                        <a class="btn login-btn" href="<?= main_url($pageHeader["section_2"]["buttonLinkLogin"]); ?>">
                            <?= ($pageHeader["section_2"]["buttonTextLogin"]); ?>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>