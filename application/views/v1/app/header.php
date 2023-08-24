<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/v1/app/images/favicon_io'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/v1/app/images/favicon_io'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/v1/app/images/favicon_io'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= base_url('assets/v1/app/images/favicon_io'); ?>/site.webmanifest">
    <title><?= $meta['title']; ?></title>
    <meta name="description" content="<?= $meta['description']; ?>">
    <meta name="keywords" content="<?= $meta['keywords']; ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <?php
    if (isset($pageCSS)) :
        echo GetCss($pageCSS);
    endif;
    ?>
    <?php if (isset($appCSS)) {
        echo $appCSS;
    } ?>
</head>

<body>
    <!-- header -->
    <header>
        <div class="d-flex justify-content-center width_100 navbar_main">
            <nav class="navbar navbar-expand-xl navbar-light d-flex justify-content-between px-3">
                <a class="navbar-brand" href="<?= base_url('/home2'); ?>">
                    <img src="<?= base_url('assets/v1/app/images/logo.png'); ?>" alt="logo" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="<?= base_url('/home2'); ?>">Home</a>
                        </li>
                        <li class="nav-item active nav_link mx-3 mt-lg-2 my-2">
                            <div class="dropdown">
                                <a class="dropdown-toggle navbar-dropdown nav-link nav_link" id="hoverDropdown" data-bs-toggle="dropdown">
                                    Product
                                </a>
                                <ul class="dropdown-menu p-0" aria-labelledby="hoverDropdown">
                                    <li class="dropdown-li">
                                        <a class="dropdown-item dropdown-option" href="<?= base_url('/ats'); ?>">ATS</a>
                                        <hr class="m-0" />
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="<?= base_url('/why-us'); ?>">Why Us</a>
                        </li>
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="<?= base_url('/about-us'); ?>">About Us</a>
                        </li>
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="<?= base_url('/contact-us'); ?>">Contact</a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <a href="<?= base_url('/schedule'); ?>" class="my-2 px-4 py-3 my-sm-0 nav-btn nav_btn_1" type="submit">
                            Schedule Your No Obligation Consultation
                        </a>
                        <a href="<?= base_url('/login'); ?>" class="nav_btn_2 my-2 my-sm-0 px-4 py-3 ms-sm-3" type="submit">
                            Login
                        </a>
                    </form>
                </div>
            </nav>
        </div>
    </header>