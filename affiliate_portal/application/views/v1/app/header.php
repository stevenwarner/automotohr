<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= main_url('assets/v1/app/images/favicon_io'); ?>/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= main_url('assets/v1/app/images/favicon_io'); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= main_url('assets/v1/app/images/favicon_io'); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?= main_url('assets/v1/app/images/favicon_io'); ?>/site.webmanifest">
    <title><?= $meta['title']; ?></title>
    <meta name="description" content="<?= $meta['description']; ?>">
    <meta name="keywords" content="<?= $meta['keywords']; ?>">
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
        <?php $headerContent = getPageContent('header');  ?>

        <nav class="navbar navbar-expand-xl navbar-light nav-bar-margin">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= main_url('/'); ?>"><img src="<?= image_url('Mask group.png'); ?>" alt="logo" /></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav mx-auto<?= $limited_menu ? '-right' : ''; ?> my-2 my-lg-0 navbar-nav-scroll">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="<?= main_url($headerContent['page']['home']['slug']); ?>"><?php echo $headerContent['page']['home']['title']; ?></a>
                        </li>
                        <?php if (!$limited_menu) { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo $headerContent['page']['products']['title']; ?>
                                </a>
                                <ul class="dropdown-menu dropdown-modal" aria-labelledby="navbarScrollingDropdown">
                                    <div class="display-flex">
                                        <li>
                                            <a class="dropdown-item-custom modal-anchor" href="<?= main_url($headerContent['page']['products']['submenu1']['slug']); ?>"><?php echo $headerContent['page']['products']['submenu1']['title']; ?> </a>
                                            <p class="dropdown-item-custom">
                                                Performance reviews, surveys,<br />
                                                employment info, and more.
                                            </p>
                                            <hr class="dropdown-hr" />
                                        </li>
                                        <hr />
                                        <li>
                                            <a class="dropdown-item-custom modal-anchor" href="<?= main_url($headerContent['page']['products']['submenu2']['slug']); ?>"><?php echo $headerContent['page']['products']['submenu2']['title']; ?></a>
                                            <p class="dropdown-item-custom">
                                                Onboarding, hiring paperwork,<br />
                                                orientation, and compliance.
                                            </p>
                                            <hr class="dropdown-hr" />
                                        </li>
                                        <hr class="dropdown-divider" />
                                        <li>
                                            <a class="dropdown-item-custom modal-anchor" href="<?= main_url($headerContent['page']['products']['submenu3']['slug']); ?>"><?php echo $headerContent['page']['products']['submenu3']['title']; ?></a>
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
                                            <a class="dropdown-item-custom modal-anchor" href="<?= main_url($headerContent['page']['products']['submenu4']['slug']); ?>"><?php echo $headerContent['page']['products']['submenu4']['title']; ?>
                                            </a>
                                            <p class="dropdown-item-custom">
                                                Career website, mobile recruiting,<br />
                                                collaborative hiring & more.
                                            </p>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <a class="dropdown-item-custom modal-anchor" href="<?= main_url($headerContent['page']['products']['submenu5']['slug']); ?>"><?php echo $headerContent['page']['products']['submenu5']['title']; ?></a>
                                            <p class="dropdown-item-custom">
                                                Attendance, scheduling, PTO,<br />
                                                leave, and overtime.
                                            </p>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li>
                                            <a class="dropdown-item-custom modal-anchor" href="<?= main_url($headerContent['page']['products']['submenu6']['slug']); ?>"><?php echo $headerContent['page']['products']['submenu6']['title']; ?></a>
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
                                <a class="nav-link" href="<?= main_url($headerContent['page']['whyus']['slug']); ?>"><?php echo $headerContent['page']['whyus']['title']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= main_url($headerContent['page']['aboutus']['slug']); ?>" tabindex="-1"><?php echo $headerContent['page']['aboutus']['title']; ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= main_url($headerContent['page']['contactus']['slug']); ?>" tabindex="-1"><?php echo $headerContent['page']['contactus']['title']; ?></a>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="d-flex flex-direction-coloumn-on-mobile">
                        <?php if (!$limited_menu) { ?>
                            <a class="btn schedule-btn" href="<?= main_url($headerContent['page']['btnobligation']['slug']); ?>"><?php echo $headerContent['page']['btnobligation']['title']; ?></a>
                        <?php } ?>
                        <a class="btn login-btn" href="<?= main_url($headerContent['page']['btnlogin']['slug']) ?>"> <?php echo $headerContent['page']['btnlogin']['title']; ?> </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>