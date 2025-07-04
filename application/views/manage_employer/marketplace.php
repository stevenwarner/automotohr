<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="mkp-page-wrp">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <div class="category-nav-wrp">
                        <div class="cat-search hidden-xs">
                            <div class="admin-info text-center">
                                <strong style="color: white; font-size: 16px;">Need help?</strong>
                            </div>
                            <!--<form action="<?php /* echo current_url(); */ ?>" method="get">
                                <input id="search_keywords" name="search_keywords" type="text" Placeholder="Search">
                                <button type="button" onclick="fPerformSearch();"><i class="fa fa-search"></i></button>
                            </form>-->
                        </div>
                        <div class="dash-box service-contacts hidden-xs">
                            <div class="admin-info">
                                <h2>Contact one of our Talent Network Partners at</h2>
                                <div class="profile-pic-area">
                                    <div class="form-col-100">
                                        <ul class="admin-contact-info">
                                            <li>
                                                <label>Sales Support</label>
                                                <?php $company_info = get_contact_info($company_id); ?>
                                                <span><i class="fa fa-phone"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_phone_no'] : TALENT_NETWORK_SALE_CONTACTNO; ?></span>
                                                <span><a href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope-o"></i><?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?></a></span>
                                            </li>
                                            <li>
                                                <label>Technical Support</label>
                                                <span><i class="fa fa-phone"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_phone_no'] : TALENT_NETWORK_SUPPORT_CONTACTNO; ?></span>
                                                <span><a href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope-o"></i><?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mkp-categories">
                            <ul>
                                <?php if ($career_site_listings_only == 0) { ?>
                                    <li <?php if ($productType == "") { ?> class="cat-active" <?php } ?>><a href="<?php echo base_url('market_place') ?>">Featured</a></li>
                                    <li <?php if ($productType == "all") { ?> class="cat-active" <?php } ?>><a href="<?php echo base_url('market_place/all') ?>">Browse All Categories</a></li>
                                    <li <?php if ($productType == "job-board") { ?> class="cat-active" <?php } ?>><a href="<?php echo base_url('market_place/job-board') ?>">Job Boards</a></li>
                                <?php   } ?>

                                <li <?php if ($productType == "background-checks") { ?> class="cat-active" <?php } ?>><a href="<?php echo base_url('market_place/background-checks') ?>">Background Checks</a></li>
                                <li <?php if ($productType == "drug-testing") { ?> class="cat-active" <?php } ?>><a href="<?php echo base_url('market_place/drug-testing') ?>">Drug Testing</a></li>

                                <?php if ($per_job_listing_charge == 1) { ?>
                                    <li <?php if ($productType == "pay-per-job") { ?> class="cat-active" <?php } ?>><a href="<?php echo base_url('market_place/pay-per-job') ?>">Pay Per Job</a></li>
                                <?php   } ?>

                                <?php   /*if($career_site_listings_only == 0) { ?>
                                    <!--<li <?php if ($productType == "reference-checks") { ?> class="cat-active" <?php } ?>><a href="<?php echo base_url('market_place/reference-checks') ?>">Reference Checks</a></li>-->      
                        <?php   } */ ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="cat-content-wrp">
                        <div class="category-section">
                            <header class="category-sec-header pull-left">
                                <h2>
                                    <?php if ($productType == "") { ?>
                                        Featured Products
                                    <?php   } elseif ($productType == "all") { ?>
                                        All Products
                                    <?php   } elseif ($productType == "job-board") { ?>
                                        Job Boards
                                    <?php   } elseif ($productType == "background-checks") { ?>
                                        Background Check
                                        <?php /* } elseif ($productType == "behavioral-assessment") { */ ?>
                                        <!--
                                Behavioral Assessment
                                <?php /* } elseif ($productType == "reference-checks") { */ ?>
                                Reference Checks-->
                                    <?php   } elseif ($productType == "drug-testing") { ?>
                                        Drug Testing
                                    <?php   } elseif ($productType == "pay-per-job") { ?>
                                        Pay Per Job
                                    <?php   } elseif ($productType == "video-interview") { ?>
                                        Video Interview
                                    <?php   } elseif ($productType == "facebook-api") { ?>
                                        Facebook Job Feed
                                    <?php   } else {
                                        echo 'Invalid product';
                                    } ?>
                                </h2>
                                <?php if ($productType == "pay-per-job") { ?>
                                    <a class="btn btn-success pull-right" href="<?= base_url('add_listing') ?>">Create a New Job</a>
                                <?php   }

                                $accurate_background_referer = $this->session->userdata('accurate_background');

                                if (!empty($accurate_background_referer)) { ?>
                                    <span class="pull-right">
                                        <button class="btn btn-success" onclick="window.location.href = '<?php echo $accurate_background_referer['btn_url']; ?>';"><i class="fa fa-arrow-left"></i>&nbsp;<?php echo $accurate_background_referer['btn_text']; ?></button>
                                    </span>
                                <?php } ?>
                                <!--<a href="javascript:;">view all</a>-->
                            </header>

                            <?php if (!empty($products)) { ?>
                                <div class="category-box-wrp">
                                    <div class="products-main">
                                        <?php
                                        $hasAccess = checkIfAppIsEnabled(ASSUREHIRE_SLUG, false);
                                        foreach ($products as $product) {?>
                                            <?php if (!$hasAccess && $product['product_brand'] == 'assurehire') continue; ?>
                                            <div class="cat-box">
                                                <figure><img src="<?php
                                                                    echo AWS_S3_BUCKET_URL;
                                                                    if ($product['product_image'] != NULL) {
                                                                        echo $product['product_image'];
                                                                    } else { ?>default_pic-ySWxT.jpg<?php } ?>" alt="Category images"></figure>
                                                <div class="text">
                                                    <h2><a href="marketplace_details/<?php echo $product['sid']; ?>"><?php echo $product['name']; ?></a></h2>
                                                    <p style="min-height: 45px;"><?php
                                                                                    if ($product['short_description'] != NULL) {
                                                                                        echo substr($product['short_description'], 0, 70);
                                                                                    } elseif ($product['detailed_description'] != NULL) {
                                                                                        echo substr($product['short_description'], 0, 70);
                                                                                    } else { ?>
                                                            No Description Avaliable.
                                                        <?php                                           } ?>
                                                    </p>
                                                </div>
                                                <?php $serialized_extra_info = unserialize($product['serialized_extra_info']); ?>
                                                <div class="form-col-100 add-cart-area">
                                                    <?php if ($productType != "enterprise-theme") { ?>
                                                        <div class="product-qty">Quantity: <?php echo $product['number_of_postings']; ?> Job(s)</div>
                                                    <?php                                       } ?>
                                                    <div class="product-qty"><strong>$<?php echo $product['price']; ?></strong><?= $product['daily'] == 1 ? '<small>/day</small>' : ''; ?></div>
                                                    <div class="products-btns">
                                                        <ul>
                                                            <?php echo form_open('', array('name' => 'addtocart')); ?>
                                                            <?php if ($product['daily'] == 1) { ?>
                                                                <input type="hidden" name="qty" value="1">
                                                                <div class="produnct-count">
                                                                    <label>No of Days:</label>
                                                                    <input class="buy-btn small-qty" type="number" name="no_of_days" min="1" max="100" value="1">
                                                                </div>
                                                            <?php                                               } else { ?>
                                                                <input type="hidden" name="no_of_days" value="0">
                                                                <div class="produnct-count">
                                                                    <label>Product Quantity:</label>
                                                                    <input class="buy-btn small-qty" type="number" name="qty" min="1" max="100" value="1">
                                                                </div>
                                                            <?php                                               } ?>
                                                            <li>
                                                                <input type="hidden" name="employer_sid" value="<?php echo $employer_id; ?>">
                                                                <input type="hidden" name="company_sid" value="<?php echo $company_id; ?>">
                                                                <input type="hidden" name="product_sid" value="<?php echo $product['sid']; ?>">
                                                                <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                                                                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                                                <input type="hidden" name="redirecturl" value="market_place/<?php echo $this->uri->segment(2); ?>">
                                                                <input type="hidden" name="action" value="addtocart">

                                                                <?php if ($product['product_type'] != 'account-package') { ?>
                                                                    <input type="submit" class="buy-btn" value="Buy Now">
                                                                    <i class="fa fa-shopping-cart"></i>
                                                                <?php                                                   } else { ?>
                                                                    <input type="button" class="buy-btn " value="Buy Now" onclick="ShowInfoAlert();">
                                                                    <i class="fa fa-shopping-cart"></i>
                                                                <?php                                   } ?>
                                                            </li>
                                                            <?php if ($product['product_type'] != 'account-package') { ?>
                                                                <li><a class="buy-btn" href="marketplace_details/<?php echo $product['sid']; ?>"><i class="fa fa-info-circle"></i> Details</a></li>
                                                            <?php } else { ?>
                                                                <li><a class="buy-btn" href="marketplace_details/<?php echo $product['sid']; ?>/0"><i class="fa fa-info-circle"></i> Details</a></li>
                                                            <?php } ?>
                                                            <?php echo form_close(); ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php                       } ?>
                                    </div>
                                </div>
                            <?php                   } else { ?>
                                <div class="category-box-wrp">
                                    No product found!
                                </div>
                            <?php                   } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function fPerformSearch() {
        var search_keywords = $('#search_keywords').val();
        var request_url = '<?php echo base_url('market_place/all') ?>';

        if (search_keywords != '') {
            request_url += '/' + encodeURI(search_keywords);
        } else {
            request_url += '/' + 'all';
        }

        window.location = request_url;
    }

    $(document).ready(function() {
        $(".mkp-categories ul li").click(function() {
            $(".mkp-categories ul li").removeClass("cat-active");
            $(this).addClass("cat-active");
        });
    });

    function ShowInfoAlert() {
        alertify.alert('Already Subscribed!', '<?php echo ACCOUNT_PACKAGE_BUY_NOW_MESSAGE; ?>');
    }
</script>