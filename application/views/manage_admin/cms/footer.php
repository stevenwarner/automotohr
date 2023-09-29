<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Footer</h1>
                                    </div>
                                    <div class="hr-search-main" style="display: block;">
                                        <form enctype="multipart/form-data" method="post" id="header_form">

                                            <?php
                                            $pageContent = json_decode($page_data['content'], true);
                                            ?>


                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Products</h1>
                                                </div> <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />


                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Product Operations Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="product_operations_title" id="product_operations_title" value="<?php echo $pageContent['page']['products']['productoperations']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="field-row">
                                                        <label>Product Operations Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="product_operations_slug" id="product_operations_slug" value="<?php echo $pageContent['page']['products']['productoperations']['slug']; ?>" />
                                                    </div>
                                                </div>


                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Recruitment Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="recruitment_title" id="recruitment_title" value="<?php echo $pageContent['page']['products']['recruitment']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="recruitment_slug" id="recruitment_slug" value="<?php echo $pageContent['page']['products']['recruitment']['slug']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>HR Electronic Onboarding Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="hr_electronic_onboarding_title" id="hr_electronic_onboarding_title" value="<?php echo $pageContent['page']['products']['electroniconboarding']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="hr_electronic_onboarding_slug" id="hr_electronic_onboarding_slug" value="<?php echo $pageContent['page']['products']['electroniconboarding']['slug']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Payroll Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="payroll_title" id="payroll_title" value="<?php echo $pageContent['page']['products']['payroll']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="payroll_slug" id="payroll_slug" value="<?php echo $pageContent['page']['products']['payroll']['slug']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Employee Management Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="employee_management_title" id="employee_management_title" value="<?php echo $pageContent['page']['products']['employeemanagement']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="employee_management_slug" id="employee_management_slug" value="<?php echo $pageContent['page']['products']['employeemanagement']['slug']; ?>" />
                                                    </div>
                                                </div>


                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Compliance Text</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="compliance_title" id="compliance_title" value="<?php echo $pageContent['page']['products']['compliance']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="compliance_slug" id="compliance_slug" value="<?php echo $pageContent['page']['products']['compliance']['slug']; ?>" />
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Affiliate Program</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="affiliate_program_title" id="affiliate_program_title" value="<?php echo $pageContent['page']['affiliateprogram']['title']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="affiliate_program_slug" id="affiliate_program_slug" value="<?php echo $pageContent['page']['affiliateprogram']['slug']; ?>" />
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Privacy Policy</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="privacy_policy_title" id="privacy_policy_title" value="<?php echo $pageContent['page']['privacypolicy']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="privacy_policy_slug" id="privacy_policy_slug" value="<?php echo $pageContent['page']['privacypolicy']['slug']; ?>" />
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Why Us</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="whyus_title" id="whyus_title" value="<?php echo $pageContent['page']['whyus']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="whyus_slug" id="whyus_slug" value="<?php echo $pageContent['page']['whyus']['slug']; ?>" />
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">About Us</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="aboutus_title" id="aboutus_title" value="<?php echo $pageContent['page']['whyus']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="aboutus_slug" id="aboutus_slug" value="<?php echo $pageContent['page']['aboutus']['slug']; ?>" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Resources</h1>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="resources_title" id="resources_title" value="<?php echo $pageContent['page']['resources']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="resources_slug" id="resources_slug" value="<?php echo $pageContent['page']['resources']['slug']; ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Contact Us</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="contactus_title" id="contactus_title" value="<?php echo $pageContent['page']['contactus']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="contactus_slug" id="contactus_slug" value="<?php echo $pageContent['page']['contactus']['slug']; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Terms</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="terms_title" id="terms_title" value="<?php echo $pageContent['page']['terms']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="terms_slug" id="terms_slug" value="<?php echo $pageContent['page']['terms']['slug']; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Sitemap</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="sitemap_title" id="sitemap_title" value="<?php echo $pageContent['page']['sitemap']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="sitemap_slug" id="sitemap_slug" value="<?php echo $pageContent['page']['sitemap']['slug']; ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Sales Support</h1>
                                                </div>
                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Phone Number</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="sales_phone" id="sales_phone" value="<?php echo $pageContent['page']['sales']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Email</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="sales_email" id="sales_email" value="<?php echo $pageContent['page']['sales']['slug']; ?>" />
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Technical Support</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Phone Number</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="technical_phone" id="technical_phone" value="<?php echo $pageContent['page']['technical']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Email</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="technical_email" id="technical_email" value="<?php echo $pageContent['page']['technical']['slug']; ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>

                                <hr />
                                <div class="row">
                                    <div class="col-lg-12 text-right">
                                        <input type="submit" name="submit_button" class="btn btn-success" value="Save">
                                        <a class="btn btn-default" href='<?php echo base_url('manage_admin/cms') ?>'>Cancel</a>
                                    </div>
                                </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#header_form").validate({
            ignore: [],
            rules: {
                product_operations_title: {
                    required: true
                },
                product_operations_slug: {
                    required: true
                },

                hr_electronic_onboarding_title: {
                    required: true
                },

                payroll_title: {
                    required: true
                },
                payroll_slug: {
                    required: true
                },
                recruitment_slug: {
                    required: true
                },
                recruitment_title: {
                    required: true
                },
                hr_electronic_onboarding_slug: {
                    required: true
                },
                employee_management_title: {
                    required: true
                },
                compliance_title: {
                    required: true
                },
                whyus_slug: {
                    required: true
                },
                employee_management_slug: {
                    required: true
                },
                compliance_slug: {
                    required: true
                },
                aboutus_title: {
                    required: true
                },
                whyus_title: {
                    required: true
                },
                aboutus_slug: {
                    required: true
                },
                contactus_title: {
                    required: true
                },
                contactus_slug: {
                    required: true
                },

                terms_title: {
                    required: true
                },
                terms_slug: {
                    required: true
                },
                sitemap_title: {
                    required: true
                },
                sitemap_slug: {
                    required: true
                },
                sales_phone: {
                    required: true
                },
                sales_email: {
                    required: true
                },
                technical_phone: {
                    required: true
                },
                technical_email: {
                    required: true
                },
                resources_title: {
                    required: true
                },
                resources_slug: {
                    required: true
                },
                affiliate_program_title: {
                    required: true
                },
                affiliate_program_slug: {
                    required: true
                },
                privacy_policy_title: {
                    required: true
                },
                privacy_policy_slug: {
                    required: true
                }
            },
            messages: {
                home_title: {
                    required: 'Hompe Title is required!'
                }
            },
            submitHandler: function(form) {
                savepage();
                return;
            }
        });
    });


    function savepage() {

        const pageData = {
            page: {}
        };

        const pageId = $("#page_id").val();

        //

        pageData.page['products'] = {
            productoperations: {
                title: $("#product_operations_title").val(),
                slug: $("#product_operations_slug").val(),
            },
            electroniconboarding: {
                title: $("#hr_electronic_onboarding_title").val(),
                slug: $("#hr_electronic_onboarding_slug").val(),
            },
            payroll: {
                title: $("#payroll_title").val(),
                slug: $("#payroll_slug").val(),
            },
            recruitment: {
                title: $("#recruitment_title").val(),
                slug: $("#recruitment_slug").val(),
            },
            employeemanagement: {
                title: $("#employee_management_title").val(),
                slug: $("#employee_management_slug").val(),
            },
            compliance: {
                title: $("#compliance_title").val(),
                slug: $("#compliance_slug").val(),
            }
        };
        pageData.page['whyus'] = {
            title: $("#whyus_title").val(),
            slug: $("#whyus_slug").val()
        };
        pageData.page['aboutus'] = {
            title: $("#aboutus_title").val(),
            slug: $("#aboutus_slug").val()
        };
        pageData.page['contactus'] = {
            title: $("#contactus_title").val(),
            slug: $("#contactus_slug").val()
        };

        pageData.page['resources'] = {
            title: $("#resources_title").val(),
            slug: $("#resources_slug").val()
        };
        pageData.page['terms'] = {
            title: $("#terms_title").val(),
            slug: $("#terms_slug").val()
        };
        pageData.page['sitemap'] = {
            title: $("#sitemap_title").val(),
            slug: $("#sitemap_slug").val()
        };
        pageData.page['sales'] = {
            title: $("#sales_phone").val(),
            slug: $("#sales_email").val()
        };
        pageData.page['technical'] = {
            title: $("#technical_phone").val(),
            slug: $("#technical_email").val()
        };

        pageData.page['privacypolicy'] = {
            title: $("#privacy_policy_title").val(),
            slug: $("#privacy_policy_slug").val()
        };
        pageData.page['affiliateprogram'] = {
            title: $("#affiliate_program_title").val(),
            slug: $("#affiliate_program_slug").val()
        };

        //
        url_to = "<?= base_url() ?>manage_admin/cms/update_page";
        $.post(url_to, {
                pageId: pageId,
                content: pageData
            })
            .done(function() {
                alertify.success('Page Sucessfully Saved.');
            });
    }
</script>