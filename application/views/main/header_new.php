<?php $load_view = isset($load_view) ? $load_view : false; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php $class = strtolower($this->router->fetch_class()); ?>
    <?php $method = $this->router->fetch_method(); ?>

    <meta charset="UTF-8" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo STORE_NAME; ?>: <?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/assets_new/css/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link href="<?= base_url() ?>assets/assets_new/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" href="<?= base_url() ?>assets/images/favi-icon.png?v=<?= time(); ?>" />
    <link rel="shortcut icon" href="<?= base_url() ?>assets/images/favi-icon.png" type="image/x-icon" />
</head>

<body>
    <main>
        <div class="d-flex justify-content-center width_100 navbar_main">
            <nav class="navbar navbar-expand-xl navbar-light d-flex justify-content-between px-3">
                <a class="navbar-brand" href="<?= base_url() ?>"">
                    <img src="<?= base_url() ?>/assets/assets_new/images/logo.png" alt="" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0">
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="<?= base_url() ?>">Home</a>
                        </li>
                        <li class="nav-item active nav_link mx-3 mt-2">
                            <div class="dropdown">
                                <a class="dropdown-toggle navbar-dropdown nav-link nav_link" id="hoverDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Product
                                </a>
                                <ul class="dropdown-menu p-0" aria-labelledby="hoverDropdown">


                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/job-distribution/">Job Distribution</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/career-website/">Career Website</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/facebook-hiring/">Facebook Hiring</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/social-recruiting/">Social Recruiting</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/mobile-recruiting/">Mobile Recruiting</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/candidate-experience/">Candidate Experience</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/collaborative-hiring/">Collaborative Hiring</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/candidate-management/">Candidate Management</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/interview-management/">Interview Management</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/onboarding-employee-management/">Onboarding / Employee Management</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/recruiting-analytics/">Recruiting Analytics</a></li>
                                    <hr class="m-0" />
                                    <li class="dropdown-li"><a class="dropdown-item dropdown-option" href="<?= base_url() ?>services/api-integrations/">API & Integrations</a></li>
                                    <hr class="m-0" />
                                    <hr class="m-0" />
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="#">Why Us</a>
                        </li>
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="<?= base_url('services/about-us') ?>">About Us</a>
                        </li>
                        <li class="nav-item active nav_link mx-3">
                            <a class="nav-link" href="<?= base_url('contact_us') ?>">Contact</a>
                        </li>
                    </ul>
                    <?php if (!$this->session->userdata('logged_in')) { ?>

                        <form class="form-inline my-2 my-lg-0">

                            <a class="my-2 px-4 py-3 my-sm-0 nav-btn nav_btn_1" href="<?= base_url('schedule_your_free_demo') ?>" type="submit">
                                Schedule Your No Obligation Consultation
                            </a>

                            <a class="nav_btn_2 my-2 my-sm-0 px-4 py-3 ms-sm-3" href="<?= base_url('login') ?>" type="submit" heref>
                                Login
                            </a>
                        </form>
                    <?php } ?>

                </div>
            </nav>
        </div>