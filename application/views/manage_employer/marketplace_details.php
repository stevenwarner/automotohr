<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="mkp-page-wrp">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-9">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="cat-content-wrp">
                        <div class="category-section">
                            <div class="category-box-wrp">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="market-deatil-header">
                                        <h2><?php echo $product['name']; ?></h2>
                                        <div class="market-breadcrumbs">
                                            <ul>
                                                <li><a href="<?php echo base_url('market_place'); ?>">Marketplace</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                    <div class="form-col-100 mkt-detail-desc">
                                        <div class="text">
                                            <!--<h2 class="section-title"><?php echo $product['name']; ?></h2>-->
                                            <p>
                                                <?php
                                                if ($product['detailed_description'] != NULL) {
                                                    echo $product['detailed_description'];
                                                } else {
                                                    echo $product['short_description'];
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                    <div class="cat-box product-box">
                                        
                                <?php if($product['product_image'] != '') { ?>
                                        <figure><img src="<?php echo AWS_S3_BUCKET_URL . $product['product_image']; ?>"></figure>
                                <?php } ?>
                                        <!--                                        <div class="text">
                                                                                    <a href="javascript:;">FIND JOBS</a>
                                                                                </div>-->
                                    </div>
                                    <?php echo form_open('', array('id' => 'addtocart')); ?>
                                    <div class="cat-box product-box">
                                        <div class="product-quantity">
                                            <?php if ($product['daily'] == 1) {
                                                ?>
                                                <input type="hidden" name="qty" value="1">
                                                <div class="produnct-count">
                                                    <label>No of Days:</label>
                                                    <input class="buy-btn small-qty" type="number" name="no_of_days" min="1" max="100" value="1">
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="no_of_days" value="0">
                                                <div class="produnct-count">
                                                    <label>Product Quantity:</label>
                                                    <input class="buy-btn small-qty" type="number" name="qty" min="1" max="100" value="1">
                                                </div>
                                            <?php } ?>
                                            <?php if($product['product_type'] != 'account-package') { ?>
                                                <input type="submit" class="cart-btn" value="Add to Cart">
                                            <?php } else { ?>
                                                <input type="button" class="cart-btn" value="Add to Cart" onclick="ShowInfoAlert();">
                                            <?php } ?>
                                        </div>
                                        <!--<a class="page-heading" href="javascript:;">Get Started</a>-->
                                        <ul class="url-list">
                                            <?php if ($product['url'] != NULL) { ?>
                                                <li>
                                                    <a href="<?php echo $product['url']; ?>" target="blank"><i class="fa fa-globe"></i><?php echo $product['url']; ?></a>
                                                </li>
                                            <?php } ?>
                                            <li>
                                                <a href="javascript:;"><i class="fa fa-map-marker"></i>Worldwide</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"><i class="fa fa-money"></i>$<?php echo $product['price']; ?><?=$product['daily'] == 1 ? '<small>/day</small>' : '';?></a>
                                            </li>
                                        </ul>
                                        <div class="btn-panel">
                                            <a style="background-color:#ededed; color:#5c5c5c; width: 100%; margin: 0 auto; display: inline-block; max-width: 100%;" class="submit-btn" href="javascript:void(0);"><?php echo ucwords(str_replace('-', ' ', $product['product_type'] )); ?></a>
                                            <!--<a style="background-color:#ededed; color:#5c5c5c;" class="submit-btn" href="javascript:;">Pay-Per-Click</a>-->
                                        </div>
                                    </div>
                                    <?php $serialized_extra_info = unserialize($product['serialized_extra_info']); ?>
                                    <input type="hidden" name="employer_sid" value="<?php echo $employer_id; ?>">
                                    <input type="hidden" name="company_sid" value="<?php echo $company_id; ?>">
                                    <input type="hidden" name="product_sid" value="<?php echo $product['sid']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                                    <input type="hidden" name="redirecturl" value="marketplace_details/<?php echo $product['sid']; ?>">
                                    <input type="hidden" name="action" value="addtocart">
                                    <?php echo form_close(); ?>
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
    function ShowInfoAlert(){
        alertify.alert('Already Subscribed!', '<?php echo ACCOUNT_PACKAGE_BUY_NOW_MESSAGE; ?>');
    }
</script>
