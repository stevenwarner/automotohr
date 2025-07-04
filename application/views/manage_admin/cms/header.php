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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Header</h1>
                                    </div>
                                    <div class="hr-search-main" style="display: block;">
                                        <form enctype="multipart/form-data" method="post" id="header_form">

                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <label> Main Menu</label>
                                                    <input type="hidden" class="invoice-fields" name="page_id" id="page_id" value="<?php echo $page_data['sid']; ?>" />

                                                </div>
                                            </div>


                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Home</h1>
                                                </div>
                                                <?php
                                                $pageContent = json_decode($page_data['content'], true);
                                                ?>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="home_title" id="home_title" value="<?php echo $pageContent['page']['home']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">

                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="home_slug" id="home_slug" value="<?php echo $pageContent['page']['home']['slug']; ?>" />
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row">
                                                <div class="col-xs-12">

                                                    <div class="hr-box" style="margin: 15px 0 0;">
                                                        <div class="hr-box-header bg-header-green">
                                                            <h1 class="hr-registered pull-left">Products</h1>
                                                        </div>


                                                        <div class="col-xs-6 form-group"> </br>
                                                            <label>Title</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="product_title" id="product_title" value="<?php echo $pageContent['page']['products']['title']; ?>" />
                                                        </div>

                                                        <div class="col-xs-6 form-group"> <br>

                                                            <label>Slug</label><b class="text-danger"> *</b>
                                                            <input type="text" class="invoice-fields" name="product_slug" id="product_slug" value="<?php echo $pageContent['page']['products']['slug']; ?>" />
                                                        </div>


                                                        <div class="col-xs-12 form-group">
                                                            <label>Products Submenu</label>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>People Operations Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="people_operations_title" id="people_operations_title" value="<?php echo $pageContent['page']['products']['submenu1']['title']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="people_operations_slug" id="people_operations_slug" value="<?php echo $pageContent['page']['products']['submenu1']['slug']; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Details</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="people_operations_title_detail" id="people_operations_title_detail" value="<?php echo $pageContent['page']['products']['submenu1']['titledetail']; ?>" />
                                                            </div>
                                                        </div>


                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>HR Electronic Onboarding Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="hr_electronic_onboarding_title" id="hr_electronic_onboarding_title" value="<?php echo $pageContent['page']['products']['submenu2']['title']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">

                                                            <div class="field-row">
                                                                <label>Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="hr_electronic_onboarding_slug" id="hr_electronic_onboarding_slug" value="<?php echo $pageContent['page']['products']['submenu2']['slug']; ?>" />
                                                            </div>
                                                        </div>


                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Detail</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="hr_electronic_onboarding_title_detail" id="hr_electronic_onboarding_title_detail" value="<?php echo $pageContent['page']['products']['submenu2']['titledetail']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Payroll Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="payroll_title" id="payroll_title" value="<?php echo $pageContent['page']['products']['submenu3']['title']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">

                                                            <div class="field-row">
                                                                <label>Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="payroll_slug" id="payroll_slug" value="<?php echo $pageContent['page']['products']['submenu3']['slug']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Detail</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="payroll_title_detail" id="payroll_title_detail" value="<?php echo $pageContent['page']['products']['submenu3']['titledetail']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Recruitment Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="recruitment_title" id="recruitment_title" value="<?php echo $pageContent['page']['products']['submenu4']['title']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">

                                                            <div class="field-row">
                                                                <label>Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="recruitment_slug" id="recruitment_slug" value="<?php echo $pageContent['page']['products']['submenu4']['slug']; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Detail</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="recruitment_title_detail" id="recruitment_title_detail" value="<?php echo $pageContent['page']['products']['submenu4']['titledetail']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Employee Management Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="employee_management_title" id="employee_management_title" value="<?php echo $pageContent['page']['products']['submenu5']['title']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">

                                                            <div class="field-row">
                                                                <label>Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="employee_management_slug" id="employee_management_slug" value="<?php echo $pageContent['page']['products']['submenu5']['slug']; ?>" />
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Detail</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="employee_management_title_detail" id="employee_management_title_detail" value="<?php echo $pageContent['page']['products']['submenu5']['titledetail']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">
                                                            <div class="field-row">
                                                                <label>Compliance Text</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="compliance_title" id="compliance_title" value="<?php echo $pageContent['page']['products']['submenu6']['title']; ?>" />
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6">

                                                            <div class="field-row">
                                                                <label>Slug</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="compliance_slug" id="compliance_slug" value="<?php echo $pageContent['page']['products']['submenu6']['slug']; ?>" />
                                                            </div>
                                                        </div>


                                                        <div class="col-xs-12">
                                                            <div class="field-row">
                                                                <label>Detail</label><b class="text-danger"> *</b>
                                                                <input type="text" class="invoice-fields" name="compliance_title_detail" id="compliance_title_detail" value="<?php echo $pageContent['page']['products']['submenu6']['titledetail']; ?>" />
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>




                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">Why Us</h1>
                                                </div>


                                                <div class="col-xs-6 form-group"><br>
                                                    <label>Title</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="whyus_title" id="whyus_title" value="<?php echo $pageContent['page']['whyus']['title']; ?>" />
                                                </div>

                                                <div class="col-xs-6 form-group"><br>
                                                    <label>Slug</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="whyus_slug" id="whyus_slug" value="<?php echo $pageContent['page']['whyus']['slug']; ?>" />
                                                </div>


                                            </div>

                                            <div class="hr-box" style="margin: 15px 0 0;">
                                                <div class="hr-box-header bg-header-green">
                                                    <h1 class="hr-registered pull-left">About Us</h1>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="aboutus_title" id="aboutus_title" value="<?php echo $pageContent['page']['aboutus']['title']; ?>" />
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
                                                    <h1 class="hr-registered pull-left">Buttons</h1>
                                                </div>


                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Login Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="btn_login_title" id="btn_login_title" value="<?php echo $pageContent['page']['btnlogin']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="btn_login_slug" id="btn_login_slug" value="<?php echo $pageContent['page']['btnlogin']['slug']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Obligation Consultation Title</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="btn_consultation_title" id="btn_consultation_title" value="<?php echo $pageContent['page']['btnobligation']['title']; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="field-row">
                                                        <label>Slug</label><b class="text-danger"> *</b>
                                                        <input type="text" class="invoice-fields" name="btn_consultation_slug" id="btn_consultation_slug" value="<?php echo $pageContent['page']['btnobligation']['slug']; ?>" />
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
                home_title: {
                    required: true
                },
                home_slug: {
                    required: true
                },
                product_title: {
                    required: true
                },
                product_slug: {
                    required: true
                },
                people_operations_title: {
                    required: true
                },
                people_operations_slug: {
                    required: true
                },
                people_operations_title_detail: {
                    required: true
                },
                hr_electronic_onboarding_title: {
                    required: true
                },
                hr_electronic_onboarding_title_detail: {
                    required: true
                },

                payroll_title: {
                    required: true
                },

                payroll_title_detail: {
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
                recruitment_title_detail: {
                    required: true
                },
                hr_electronic_onboarding_slug: {
                    required: true
                },
                employee_management_title: {
                    required: true
                },
                employee_management_title_detail: {
                    required: true
                },
                compliance_title: {
                    required: true
                },
                compliance_title_detail: {
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
                btn_login_title: {
                    required: true
                },
                btn_login_slug: {
                    required: true
                },
                btn_consultation_slug: {
                    required: true
                },
                btn_consultation_title: {
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
        pageData.page['home'] = {
            title: $("#home_title").val(),
            slug: $("#home_slug").val()
        };
        pageData.page['products'] = {
            title: $("#product_title").val(),
            slug: $("#product_slug").val(),
            submenu1: {
                title: $("#people_operations_title").val(),
                slug: $("#people_operations_slug").val(),
                titledetail: $("#people_operations_title_detail").val(),

            },
            submenu2: {
                title: $("#hr_electronic_onboarding_title").val(),
                slug: $("#hr_electronic_onboarding_slug").val(),
                titledetail: $("#hr_electronic_onboarding_title_detail").val(),

            },
            submenu3: {
                title: $("#payroll_title").val(),
                slug: $("#payroll_slug").val(),
                titledetail: $("#payroll_title_detail").val(),

            },
            submenu4: {
                title: $("#recruitment_title").val(),
                slug: $("#recruitment_slug").val(),
                titledetail: $("#recruitment_title_detail").val(),

            },
            submenu5: {
                title: $("#employee_management_title").val(),
                slug: $("#employee_management_slug").val(),
                titledetail: $("#employee_management_title_detail").val(),

            },
            submenu6: {
                title: $("#compliance_title").val(),
                slug: $("#compliance_slug").val(),
                titledetail: $("#compliance_title_detail").val(),

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
        pageData.page['btnlogin'] = {
            title: $("#btn_login_title").val(),
            slug: $("#btn_login_slug").val()
        };
        pageData.page['btnobligation'] = {
            title: $("#btn_consultation_title").val(),
            slug: $("#btn_consultation_slug").val()
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