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

    <?php
    if (isset($pageJs)) :
        echo GetScripts($pageJs);
    endif;
    ?>

</head>

<body>
    <!-- header -->
    <header>
        <nav class="navbar navbar-expand-xl navbar-light nav-bar-margin">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= base_url('/'); ?>"><img src="<?= base_url('assets/v1/app/images/Mask group.png'); ?>" alt="logo" /></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav mx-auto my-2 my-lg-0 navbar-nav-scroll">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= base_url('/'); ?>">Home</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Product
                            </a>
                            <ul class="dropdown-menu dropdown-modal" aria-labelledby="navbarScrollingDropdown">
                                <div class="display-flex">
                                    <li>
                                        <a class="dropdown-item-custom modal-anchor" href="product/people-operations">People Operations </a>
                                        <p class="dropdown-item-custom">
                                            Performance reviews, surveys,<br />
                                            employment info, and more.
                                        </p>
                                        <hr class="dropdown-hr" />
                                    </li>
                                    <hr />
                                    <li>
                                        <a class="dropdown-item-custom modal-anchor" href="product/hr-electronic-onboarding">HR Electronic Onboarding</a>
                                        <p class="dropdown-item-custom">
                                            Onboarding, hiring paperwork,<br />
                                            orientation, and compliance.
                                        </p>
                                        <hr class="dropdown-hr" />
                                    </li>
                                    <hr class="dropdown-divider" />
                                    <li>
                                        <a class="dropdown-item-custom modal-anchor" href="product-payroll">Payroll</a>
                                        <p class="dropdown-item-custom">
                                            Automate payroll deductions, <br />
                                            direct deposits, and tax filing.
                                        </p>
                                        <hr class="dropdown-hr" />
                                    </li>
                                    <hr class="dropdown-divider" />
                                </div>
                                <div class="display-flex margin-top-twenty">
                                    <li>
                                        <a class="dropdown-item-custom modal-anchor" href="product-recruitment">Recruitment
                                        </a>
                                        <p class="dropdown-item-custom">
                                            Career website, mobile recruiting,<br />
                                            collaborative hiring & more.
                                        </p>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <a class="dropdown-item-custom modal-anchor" href="product-employee-management">Employee Management</a>
                                        <p class="dropdown-item-custom">
                                            Attendance, scheduling, PTO,<br />
                                            leave, and overtime.
                                        </p>
                                        <hr class="dropdown-divider" />
                                    </li>
                                    <li>
                                        <a class="dropdown-item-custom modal-anchor" href="product-compliance">Compliance</a>
                                        <p class="dropdown-item-custom">
                                            Add compliance videos,<br />
                                            employee handbook, and policies.
                                        </p>
                                        <hr class="dropdown-divider" />
                                    </li>
                                </div>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/services/why-us'); ?>">Why Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/services/about-us'); ?>" tabindex="-1">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/contact_us'); ?>" tabindex="-1">Contact</a>
                        </li>
                    </ul>

                    <div class="d-flex flex-direction-coloumn-on-mobile">
                        <a class="btn schedule-btn" href="#freedemo">

                            Schedule Your No Obligation Consultation</a>
                        <a class="btn login-btn" href="<?= base_url('login') ?>"> Login </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>