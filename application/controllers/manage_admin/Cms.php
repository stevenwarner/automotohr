<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cms extends Admin_Controller
{
    //
    private $resp = [];

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/cms_model');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        //
        $this->resp = [
            'Status' => false,
            'Response' => 'Request Not Authorized'
        ];
    }


    public function index()
    {
        $this->load->library('pagination');
        $redirect_url = 'manage_admin';
        $admin_id = $this->ion_auth->user()->row()->id;
        $this->data['page_title'] = 'CMS';
        $page_number = ($this->uri->segment(7)) ? $this->uri->segment(7) : 1;
        $offset           = 0;
        $records_per_page = 50;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }
        $pagination_base = base_url('manage_admin/cms');
        $pages_data = $this->cms_model->get_pages_data();
        $total_records = $this->cms_model->get_pages_data(null, null, true);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $this->uri->total_segments();
        $config["num_links"] = 8;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        $this->data['groups'] = $this->ion_auth->groups()->result();

        $this->data['pages_data'] = $pages_data;
        $this->render('manage_admin/cms/index');
    }

    /**
     * dynamic page add
     */
    public function addDynamicPage()
    {
        //
        $this->data["page_title"] = "Add dynamic page :: AutomotoHR.com";
        //
        $commonFileBundle = bundleJs(
            [
                "js/app_helper"
            ],
            "public/v1/app/",
            "app_common",
            true
        );
        //
        $pageBundle = bundleJs(
            [
                "v1/cms/page/create_dynamic_page"
            ],
            "public/v1/app/",
            "app_page",
            true
        );
        $this->data["appJs"] = combineBundle([
            $commonFileBundle,
            $pageBundle
        ]);
        //
        $this->render('manage_admin/cms/v1/partials/dynamic_pages/add_dynamic_page');
    }

    /**
     * dynamic page edit
     */
    public function editDynamicPage(int $pageId)
    {
        //
        $this->data["page_title"] = "Edit a dynamic page :: AutomotoHR.com";
        // get the page
        $this->data["page"] = $this->cms_model->get_page_data($pageId);
        $this->data["pageContent"] = json_decode($this->data["page"]["content"], true);
        $this->data["PageScripts"] = [
            "ckeditor/ckeditor"
        ];
        //
        $commonFileBundleCSS = bundleCSS(
            [
                "v1/plugins/ms_modal/main",
                "v1/plugins/ms_uploader/main",
            ],
            "public/v1/app/",
            "app_common_edit",
            true
        );
        //
        $commonFileBundle = bundleJs(
            [
                "v1/plugins/ms_modal/main",
                "v1/plugins/ms_uploader/main",
                "js/app_helper"
            ],
            "public/v1/app/",
            "app_common_edit",
            true
        );
        //
        $pageBundle = bundleJs(
            [
                "v1/cms/page/edit_dynamic_page"
            ],
            "public/v1/app/",
            "app_edit_page",
            true
        );
        $this->data["appJs"] = combineBundle([
            $commonFileBundle,
            $pageBundle
        ]);
        $this->data["appCSS"] = combineBundle([
            $commonFileBundleCSS,
        ]);
        //
        $this->render('manage_admin/cms/v1/partials/dynamic_pages/edit_dynamic_page');
    }

    //
    public function edit_page($sid)
    {
        $this->load->helper('url');
        $this->data['page_title'] = 'Page';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $page_data = $this->cms_model->get_page_data($sid);

        $this->data['page_data'] = $page_data;
        $this->data['page_title'] = "Modify " . ucwords($page_data["page"]) . " :: " . STORE_NAME;
        //
        $this->data['pageContent'] = json_decode($page_data["content"], true);


        $this->data['page'] = $page_data;

        $this->data["appCSS"] = bundleCSS([
            "v1/plugins/ms_modal/main",
            "v1/plugins/ms_uploader/main",
        ], "public/v1/app/", "app_" . $page_data["page"], false);
        //
        $files = [];
        // for home page
        if ($page_data["page"] == "home") {
            //
            $files = [
                "v1/cms/slider",
                "v1/cms/home_section_1",
                "v1/cms/home_section_2",
                "v1/cms/process",
                "v1/cms/about_section",
            ];
        }
        // for products
        elseif (in_array($page_data["slug"], ["payroll", "compliance", "employee-management", "hr-electronic-onboarding", "people-operations", "recruitment"])) {
            //
            $files = [
                "v1/cms/product",
            ];
        }
        // for each page
        else {

            $files = [
                "v1/cms/page/" . $page_data["page"]
            ];
        }

        $this->data["PageScripts"] = [
            "ckeditor/ckeditor"
        ];

        //
        $this->data["appJs"] = bundleJs(array_merge([
            "v1/plugins/ms_modal/main",
            "v1/plugins/ms_uploader/main",
            "js/app_helper",
            "v1/cms/meta",
            "v1/cms/page",
        ], $files), "public/v1/app/", "app_" . $page_data["page"], false);

        $this->render('manage_admin/cms/v1/' . $page_data['page']);
    }

    //
    public function update_page()
    {
        $pageId = $this->input->post('pageId');
        $content = $this->input->post('content');
        $dataUpdate['content'] = json_encode($content);

        $dataUpdate['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        $this->cms_model->update_page_data($pageId, $dataUpdate);
    }


    // API calls

    /**
     * 
     */
    public function updateMeta(int $pageId)
    {
        $this->form_validation->set_rules("meta_title", "Meta title", "xss_clean|required|trim");
        $this->form_validation->set_rules("meta_description", "Meta description", "xss_clean|required|trim");
        $this->form_validation->set_rules("meta_keywords", "Meta keywords", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        //
        $pageContent = json_decode($pageContent, true);
        $pageContent["page"]["meta"] = [
            "title" => $post["meta_title"],
            "description" => $post["meta_description"],
            "keyword" => $post["meta_keywords"],
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated the meta."]);
    }

    /**
     * 
     */
    public function updateSlider(int $pageId)
    {
        $this->form_validation->set_rules("heading", "Banner heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Banner details", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_text", "Button text", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_link", "Button link", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // check and run for image
        $errors = hasFileErrors($_FILES, "banner_image", 'imag2');
        //
        if ($errors) {
            return SendResponse(
                400,
                ["errors" => $errors]
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        $pageContent["page"]["slider"][] = [
            "heading" => $post["heading"],
            "headingDetail" => $post["details"],
            "btnText" => $post["button_text"],
            "btnSlug" => $post["button_link"],
            "image" => upload_file_to_aws(
                "banner_image",
                0,
                "slider_banner_",
            ),
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully added a banner."]);
    }

    /**
     * 
     */
    public function deleteSlider(int $pageId, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        unset($pageContent["page"]["slider"][$index]);
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully deleted a banner."]);
    }

    /**
     * 
     */
    public function getBannerAddPage()
    {
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/banner/add",
                [],
                true
            )
        ]);
    }

    /**
     * 
     */
    public function getBannerEditPage(int $pageId, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/banner/edit",
                [
                    "pageContent" => $pageContent["page"]["slider"][$index],
                    "index" => $index
                ],
                true
            ),
            "data" => $pageContent["page"]["slider"][$index]
        ]);
    }

    /**
     * 
     */
    public function updateSliderIndex(int $pageId, int $index)
    {
        $this->form_validation->set_rules("heading", "Banner heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Banner details", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_text", "Button text", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_link", "Button link", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            if (!$_FILES['file'] && $post['source_link']) {
                $fileLink = $post["source_link"];
            } else {
                // check and run for image
                $errors = hasFileErrors($_FILES, "file", 'image', 20);
                //
                if ($errors) {
                    return SendResponse(
                        400,
                        ["errors" => $errors]
                    );
                }
                $fileLink = upload_file_to_aws(
                    "file",
                    0,
                    "slider",
                );
            }
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["slider"][$index] = [
            "heading" => $post["heading"],
            "headingDetail" => $post["details"],
            "btnText" => $post["button_text"],
            "btnSlug" => $post["button_link"],
            "image" => $fileLink
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated a banner."]);
    }

    /**
     * 
     */
    public function addSliderIndex(int $pageId)
    {
        $this->form_validation->set_rules("heading", "Banner heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Banner details", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_text", "Button text", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_link", "Button link", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // check and run for image
        $errors = hasFileErrors($_FILES, "banner_image", 'imag2');
        //
        if ($errors) {
            return SendResponse(
                400,
                ["errors" => $errors]
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["slider"][] = [
            "heading" => $post["heading"],
            "headingDetail" => $post["details"],
            "btnText" => $post["button_text"],
            "btnSlug" => $post["button_link"],
            "image" => upload_file_to_aws(
                "banner_image",
                0,
                "slider_banner_",
            ),
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully added a banner."]);
    }

    /**
     * Home section 1
     */
    public function updateHomePageSection1(int $pageId)
    {
        $this->form_validation->set_rules("jsMainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("jsSubHeading", "Sub heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("jsDetails", "Details", "xss_clean|required|trim");
        $this->form_validation->set_rules("bullet1", "Point 1", "xss_clean|required|trim");
        $this->form_validation->set_rules("bullet2", "Point 2", "xss_clean|required|trim");
        $this->form_validation->set_rules("bullet3", "Point 3", "xss_clean|required|trim");
        $this->form_validation->set_rules("bullet4", "Point 4", "xss_clean|required|trim");
        $this->form_validation->set_rules("bullet5", "Point 5", "xss_clean|required|trim");
        $this->form_validation->set_rules("bullet6", "Point 6", "xss_clean|required|trim");
        $this->form_validation->set_rules("jsButtonText", "Button text", "xss_clean|required|trim");
        $this->form_validation->set_rules("jsButtonSlug", "Button link", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            if (!$_FILES['file'] && $post['source_link']) {
                $fileLink = $post["source_link"];
            } else {
                // check and run for image
                $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
                //
                if ($errors) {
                    return SendResponse(
                        400,
                        ["errors" => $errors]
                    );
                }
                $fileLink = upload_file_to_aws(
                    "file",
                    0,
                    "home_section_1_",
                );
            }
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"]["section1"] = [
            "mainheading" => $post["jsMainHeading"],
            "heading" => $post["jsSubHeading"],
            "headingDetail" => $post["jsDetails"],
            "btnText" => $post["jsButtonText"],
            "btnSlug" => $post["jsButtonSlug"],
            "sourceType" => $post["source_type"],
            "sourceFile" => $fileLink,
            "bullet1" => $post["bullet1"],
            "bullet2" => $post["bullet2"],
            "bullet3" => $post["bullet3"],
            "bullet4" => $post["bullet4"],
            "bullet5" => $post["bullet5"],
            "bullet6" => $post["bullet6"],
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated \"What we offer?\"."]);
    }

    /**
     * Home section 2
     */
    public function updateHomePageSection2(int $pageId)
    {
        $this->form_validation->set_rules("jsMainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("jsSubHeading", "Sub heading", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"]["section2"] = [
            "mainheading" => $post["jsMainHeading"],
            "heading" => $post["jsSubHeading"],
            "products" =>  $pageContent["page"]["sections"]["section2"]["products"],
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated the data."]);
    }

    /**
     * Home page add product
     */
    public function addProductToHomePage(int $pageId)
    {
        $this->form_validation->set_rules("mainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("subHeading", "Sub heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Details", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_text", "Button text", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_link", "Button link", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            // check and run for image
            $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
            //
            if ($errors) {
                return SendResponse(
                    400,
                    ["errors" => $errors]
                );
            }
            $fileLink = upload_file_to_aws(
                "file",
                0,
                "home_page_product_",
            );
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"]["section2"]["products"][] = [
            "mainHeading" => $post["mainHeading"],
            "subHeading" => $post["subHeading"],
            "details" => $post["details"],
            "buttonText" => $post["button_text"],
            "buttonLink" => $post["button_link"],
            "layout" => $post["theme"],
            "direction" => $post["direction"],
            "sourceType" => $post["source_type"],
            "sourceFile" => $fileLink,
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully added a new product section."]);
    }

    /**
     * Home page update product
     */
    public function updateProductToHomePage(int $pageId, int $index)
    {
        $this->form_validation->set_rules("mainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("subHeading", "Sub heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Details", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_text", "Button text", "xss_clean|required|trim");
        $this->form_validation->set_rules("button_link", "Button link", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            if (!$_FILES['file'] && $post['source_link']) {
                $fileLink = $post["source_link"];
            } else {
                // check and run for image
                $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
                //
                if ($errors) {
                    return SendResponse(
                        400,
                        ["errors" => $errors]
                    );
                }
                $fileLink = upload_file_to_aws(
                    "file",
                    0,
                    "home_page_product",
                );
            }
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"]["section2"]["products"][$index] = [
            "mainHeading" => $post["mainHeading"],
            "subHeading" => $post["subHeading"],
            "details" => $post["details"],
            "buttonText" => $post["button_text"],
            "buttonLink" => $post["button_link"],
            "layout" => $post["theme"],
            "direction" => $post["direction"],
            "sourceType" => $post["source_type"],
            "sourceFile" => $fileLink,
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated the product section."]);
    }

    /**
     * Home page delete product
     */
    public function deleteProductToHomePage(int $pageId, int $index)
    {

        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);

        unset($pageContent["page"]["sections"]["section2"]["products"][$index]);
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully deleted the selected product section."]);
    }

    /**
     * Update page content
     */
    public function updatePageSection(int $pageId)
    {
        $post = $this->input->post(null, true);
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);

        if ($post["source_type"]) {
            //
            $fileLink = $post["source_link"];
            //
            if ($post["source_type"] === "upload") {
                //
                if (!$_FILES['file'] && $post['source_link']) {
                    $fileLink = $post["source_link"];
                } else {
                    // check and run for image
                    $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
                    //
                    if ($errors) {
                        return SendResponse(
                            400,
                            ["errors" => $errors]
                        );
                    }
                    $fileLink = upload_file_to_aws(
                        "file",
                        0,
                        "innovating_hr",
                    );
                }
            }
            $post["sourceFile"] = $fileLink;
            $post["sourceType"] = $post["source_type"];

            unset($post["source_type"]);
        }

        // for extra points
        if ($post["source_type_point_1"]) {
            //
            $fileLink = $post["source_link_point_1"];
            //
            if ($post["source_type_point_1"] === "upload") {
                //
                if (!$_FILES['file_point_1'] && $post['source_link_point_1']) {
                    $fileLink = $post["source_link_point_1"];
                } else {
                    // check and run for image
                    $errors = hasFileErrors($_FILES, "file_point_1", 'image|video', 20);
                    //
                    if ($errors) {
                        return SendResponse(
                            400,
                            ["errors" => $errors]
                        );
                    }
                    $fileLink = upload_file_to_aws(
                        "file_point_1",
                        0,
                        "point_1",
                    );
                }
            }
            $post["headingPoint1File"] = $fileLink;
            $post["headingPoint1Type"] = $post["source_type_point_1"];

            unset($post["source_type_point_1"]);
        }

        if ($post["source_type_point_2"]) {
            //
            $fileLink = $post["source_link_point_2"];
            //
            if ($post["source_type_point_2"] === "upload") {
                //
                if (!$_FILES['file_point_2'] && $post['source_link_point_2']) {
                    $fileLink = $post["source_link_point_2"];
                } else {
                    // check and run for image
                    $errors = hasFileErrors($_FILES, "file_point_2", 'image|video', 20);
                    //
                    if ($errors) {
                        return SendResponse(
                            400,
                            ["errors" => $errors]
                        );
                    }
                    $fileLink = upload_file_to_aws(
                        "file_point_2",
                        0,
                        "point_2",
                    );
                }
            }
            $post["headingPoint2File"] = $fileLink;
            $post["headingPoint2Type"] = $post["source_type_point_2"];

            unset($post["source_type_point_2"]);
        }

        if ($post["source_type_point_3"]) {
            //
            $fileLink = $post["source_link_point_3"];
            //
            if ($post["source_type_point_3"] === "upload") {
                //
                if (!$_FILES['file_point_3'] && $post['source_link_point_3']) {
                    $fileLink = $post["source_link_point_3"];
                } else {
                    // check and run for image
                    $errors = hasFileErrors($_FILES, "file_point_3", 'image|video', 20);
                    //
                    if ($errors) {
                        return SendResponse(
                            400,
                            ["errors" => $errors]
                        );
                    }
                    $fileLink = upload_file_to_aws(
                        "file_point_3",
                        0,
                        "point_3",
                    );
                }
            }
            $post["headingPoint3File"] = $fileLink;
            $post["headingPoint3Type"] = $post["source_type_point_3"];

            unset($post["source_type_point_3"]);
        }

        if ($post["source_type_logo"]) {
            //
            $fileLink = $post["source_link_logo"];
            //
            if ($post["source_type_logo"] === "upload") {
                //
                if (!$_FILES['file_logo'] && $post['source_link_logo']) {
                    $fileLink = $post["source_link_logo"];
                } else {
                    // check and run for image
                    $errors = hasFileErrors($_FILES, "file_logo", 'image|video', 20);
                    //
                    if ($errors) {
                        return SendResponse(
                            400,
                            ["errors" => $errors]
                        );
                    }
                    $fileLink = upload_file_to_aws(
                        "file_logo",
                        0,
                        "banner_logo",
                    );
                }
            }
            $post["logoFile"] = $fileLink;
            $post["logoType"] = $post["source_type_logo"];

            unset($post["source_type_logo"]);
        }

        //
        if ($pageContent["page"]["sections"][$post["section"]]["tags"]) {
            $tags = $pageContent["page"]["sections"][$post["section"]]["tags"];
            $pageContent["page"]["sections"][$post["section"]] = $post;
            $pageContent["page"]["sections"][$post["section"]]["tags"] = $tags;
        } else {
            $pageContent["page"]["sections"][$post["section"]] = $post;
        }

        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated the section."]);
    }


    /**
     * 
     */
    public function getHomeProductAddPage()
    {
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/home/add",
                [],
                true
            )
        ]);
    }

    /**
     * 
     */
    public function getHomeProductEditPage(int $pageId, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/home/edit",
                [
                    "data" => $pageContent["page"]["sections"]["section2"]["products"][$index]
                ],
                true
            ),
            "sourceType" => $pageContent["page"]["sections"]["section2"]["products"][$index]["sourceType"],
            "sourceFile" => $pageContent["page"]["sections"]["section2"]["products"][$index]["sourceFile"],
            "index" => $index
        ]);
    }

    /**
     * 
     */
    public function getProductAddPage()
    {
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/product/add",
                [],
                true
            )
        ]);
    }
    /**
     * Home page add product
     */
    public function addProductSection(int $pageId)
    {
        $this->form_validation->set_rules("mainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Details", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            // check and run for image
            $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
            //
            if ($errors) {
                return SendResponse(
                    400,
                    ["errors" => $errors]
                );
            }
            $fileLink = upload_file_to_aws(
                "file",
                0,
                "home_page_product_",
            );
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"]["products"][] = [
            "mainHeading" => $post["mainHeading"],
            "details" => $post["details"],
            "direction" => $post["direction"],
            "sourceType" => $post["source_type"],
            "sourceFile" => $fileLink,
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully added a new product section."]);
    }

    /**
     * 
     */
    public function geProductEditPage(int $pageId, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/product/edit",
                [
                    "data" => $pageContent["page"]["sections"]["products"][$index]
                ],
                true
            ),
            "sourceType" => $pageContent["page"]["sections"]["products"][$index]["sourceType"],
            "sourceFile" => $pageContent["page"]["sections"]["products"][$index]["sourceFile"],
            "index" => $index
        ]);
    }

    /**
     * Home page update product
     */
    public function updateProduct(int $pageId, int $index)
    {
        $this->form_validation->set_rules("mainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Details", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            if (!$_FILES['file'] && $post['source_link']) {
                $fileLink = $post["source_link"];
            } else {
                // check and run for image
                $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
                //
                if ($errors) {
                    return SendResponse(
                        400,
                        ["errors" => $errors]
                    );
                }
                $fileLink = upload_file_to_aws(
                    "file",
                    0,
                    "product_page_",
                );
            }
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"]["products"][$index] = [
            "mainHeading" => $post["mainHeading"],
            "details" => $post["details"],
            "direction" => $post["direction"],
            "sourceType" => $post["source_type"],
            "sourceFile" => $fileLink,
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated the product section."]);
    }

    /**
     * Home page delete product
     */
    public function deleteProduct(int $pageId, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        $statusMsg = '';
        // unset($pageContent["page"]["sections"]["products"][$index]);
        if ($pageContent["page"]["sections"]["products"][$index]['status'] == "1") {
            $pageContent["page"]["sections"]["products"][$index]['status'] = "0";
            $statusMsg = 'De-Activate';
        } else if ($pageContent["page"]["sections"]["products"][$index]['status'] == "0") {
            $pageContent["page"]["sections"]["products"][$index]['status'] = "1";
            $statusMsg = 'Activate';
        } else {
            $pageContent["page"]["sections"]["products"][$index]['status'] = "0";
            $statusMsg = 'De-Activate';
        }

        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully " . $statusMsg . " the selected product section."]);
    }


    /**
     * get add page by page name
     *
     * @param string $page
     */
    public function getAddPage(string $page)
    {
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/page/" . $page,
                [],
                true
            )
        ]);
    }

    /**
     * Home page add product
     */
    public function processAddPage(int $pageId)
    {
        $this->form_validation->set_rules("mainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Details", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            // check and run for image
            $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
            //
            if ($errors) {
                return SendResponse(
                    400,
                    ["errors" => $errors]
                );
            }
            $fileLink = upload_file_to_aws(
                "file",
                0,
                "home_page_product_",
            );
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"][$post["section"]][] = [
            "mainHeading" => $post["mainHeading"],
            "details" => $post["details"],
            "sourceType" => $post["source_type"],
            "sourceFile" => $fileLink,
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully added a new section."]);
    }

    /**
     * get the edit page
     */
    public function getEditPage(int $pageId, string $page, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/page/" . $page,
                [
                    "data" => $pageContent["page"]["sections"][$page][$index]
                ],
                true
            ),
            "sourceType" => $pageContent["page"]["sections"][$page][$index]["sourceType"],
            "sourceFile" => $pageContent["page"]["sections"][$page][$index]["sourceFile"],
            "index" => $index
        ]);
    }

    /**
     * Home page add product
     */
    public function processEditPage(int $pageId, int $index)
    {
        $this->form_validation->set_rules("mainHeading", "Main heading", "xss_clean|required|trim");
        $this->form_validation->set_rules("details", "Details", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        //
        $fileLink = $post["source_link"];
        //
        if ($post["source_type"] === "upload") {
            //
            if (!$_FILES['file'] && $post['source_link']) {
                $fileLink = $post["source_link"];
            } else {
                // check and run for image
                $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
                //
                if ($errors) {
                    return SendResponse(
                        400,
                        ["errors" => $errors]
                    );
                }
                $fileLink = upload_file_to_aws(
                    "file",
                    0,
                    "product_page_",
                );
            }
        }
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);
        //
        $pageContent["page"]["sections"][$post["section"]][$index] = [
            "mainHeading" => $post["mainHeading"],
            "details" => $post["details"],
            "sourceType" => $post["source_type"],
            "sourceFile" => $fileLink,
        ];
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated a section."]);
    }

    /**
     * Home page delete product
     */
    public function processDeletePage(int $pageId, string $page, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model->get_page_data($pageId)["content"];
        // //
        $pageContent = json_decode($pageContent, true);

        unset($pageContent["page"]["sections"][$page][$index]);
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully deleted the selected section."]);
    }


    /**
     * get add page by page name
     *
     * @param string $page
     */
    public function getPageUI(string $page)
    {
        $data = [];
        //
        $post = $this->input->post(null, true);
        //
        if ($post) {
            // get the page record
            $pageContent = $this->cms_model->get_page_data(
                $post["pageId"]
            )["content"];
            // 
            $pageContent = json_decode($pageContent, true);
            $paths = explode("|", $post["section"]);
            //
            $data = $pageContent["page"]["sections"];
            foreach ($paths as $path) {
                $data = $data[$path];
            }
        }
        //
        return SendResponse(200, [
            "view" => $this->load->view(
                "manage_admin/cms/v1/partials/dynamic/" . (strtolower($page)),
                [
                    "data" => $data
                ],
                true
            )
        ]);
    }

    /**
     * updates page section
     *
     * @param int $pageId
     */
    public function updatePageSections(int $pageId)
    {
        // get the page record
        $pageContent = $this->cms_model
            ->get_page_data(
                $pageId
            )["content"];
        //
        $post = $this->input->post(null, true);
        //
        $pageContent = json_decode($pageContent, true);

        $pageContent["page"]["sections"][$post["section"]][$post["index"]][] = $post;

        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully updated the selected section."]);
    }

    /**
     * deletes page section
     *
     * @param int $pageId
     * @param int $index
     */
    public function deletePageTag(int $pageId, int $index)
    {
        // get the page record
        $pageContent = $this->cms_model
            ->get_page_data(
                $pageId
            )["content"];
        //
        $pageContent = json_decode($pageContent, true);

        unset(
            $pageContent["page"]["sections"]["section0"]["tags"][$index]
        );

        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully deleted the selected section."]);
    }

    /**
     * add page section card
     *
     * @param int $pageId
     */
    public function addPageTag(int $pageId)
    {
        // get the page record
        $pageContent = $this->cms_model
            ->get_page_data(
                $pageId
            )["content"];
        //
        $post = $this->input->post(null, true);
        //
        $pageContent = json_decode($pageContent, true);

        if (isset($post["index"])) {
            $pageContent["page"]["sections"]["section0"]["tags"][$post["tagIndex"]]["cards"][$post["index"]] = $post;
            $msg = "You have successfully updated a card section.";
        } else {
            $pageContent["page"]["sections"]["section0"]["tags"][$post["tagIndex"]]["cards"][] = $post;
            $msg = "You have successfully added a card section.";
        }
        //
        if (isset($post["sortOrder"])) {
            $tagCards = $pageContent["page"]["sections"]["section0"]["tags"][$post["tagIndex"]]["cards"];
            $pageContent["page"]["sections"]["section0"]["tags"][$post["tagIndex"]]["cards"] = $this->sortCards($tagCards);
        }
        //
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, [
            "msg" => $msg
        ]);
    }

    public function sortCards($cards)
    {
        //
        foreach ($cards as $key => $card) {
            if (!isset($card['sortOrder'])) {
                $cards[$key]['sortOrder'] = 0;
            }
        }
        $sortOrders = array_column($cards, 'sortOrder');
        //
        array_multisort($sortOrders, SORT_ASC, $cards);
        //
        foreach ($cards as $key => $card) {
            $cards[$key]['index'] = $key;
        }
        //
        return $cards;
    }

    public function updateSortOrder(int $pageId)
    {
        // get the page record
        $pageContent = $this->cms_model
            ->get_page_data(
                $pageId
            )["content"];
        //
        $post = $this->input->post(null, true);
        //
        $pageContent = json_decode($pageContent, true);
        //
        if (isset($post["sortOrders"])) {
            $tagCards = $pageContent["page"]["sections"]["section0"]["tags"][$post["tagIndex"]]["cards"];
            //
            $newCardsOrder = [];
            //
            foreach ($post["sortOrders"] as $key => $index) {
                //
                $item = $tagCards[$index];
                //
                $item['index'] = $key;
                $item['sortOrder'] = $key + 1;
                $newCardsOrder[] = $item;
            }
            //         
            $pageContent["page"]["sections"]["section0"]["tags"][$post["tagIndex"]]["cards"] = $newCardsOrder;
            $msg = "You have successfully updated a cards sort order.";
        }
        // //
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, [
            "msg" => $msg
        ]);
    }

    /**
     * deletes tag card
     *
     * @param int $pageId
     * @param int $index
     */
    public function deleteTagCard(int $pageId, int $index, int $tagIndex)
    {
        // get the page record
        $pageContent = $this->cms_model
            ->get_page_data(
                $pageId
            )["content"];
        //
        $pageContent = json_decode($pageContent, true);

        unset(
            $pageContent["page"]["sections"]["section0"]["tags"][$tagIndex]["cards"][$index]
        );


        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, ["msg" => "You have successfully deleted the selected card."]);
    }


    /**
     * create a new page process
     */
    public function addDynamicPageProcess()
    {
        $this->form_validation->set_rules("title", "Page name", "xss_clean|required|trim");
        $this->form_validation->set_rules("slug", "Page slug", "xss_clean|required|trim");
        //
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get sanitized post
        $post = $this->input->post(null, true);
        // check if the page slug already exists
        if ($this->cms_model->isPageExists($post["slug"])) {
            return SendResponse(
                400,
                ["errors" => ["Page already exists."]]
            );
        }
        // create the page
        $pageId = $this->cms_model->createPage($post);
        //
        if (!$pageId) {
            return SendResponse(
                400,
                ["errors" => ["Failed to create page."]]
            );
        }
        return SendResponse(
            200,
            [
                "msg" => "You have successfully created the page.",
                "pageId" => $pageId
            ]
        );
    }

    /**
     * create a new page process
     *
     * @param int $pageId
     */
    public function editDynamicPageProcess(int $pageId)
    {
        // get sanitized post
        $post = $this->input->post(null, true);

        //
        if ($post["section"] === "main") {
            // check if the page slug already exists
            if ($this->cms_model->isPageExistsWithId($post["slug"], $pageId)) {
                return SendResponse(
                    400,
                    ["errors" => ["Page already exists."]]
                );
            }
            // update the page
            $this->cms_model->updateDynamicPage($post, $pageId);
        } elseif ($post["section"] === "status") {
            // update the page
            $this->cms_model->updatePageStatus($post["status"], $pageId);
        } elseif ($post["section"] === "footerlink") {
            // update the page
            $this->cms_model->updatePageIsFooterLink($post["is_footer_link"], $pageId);
        } else {
            $pageContent = [];
            // get the page record
            $content = $this->cms_model
                ->get_page_data(
                    $pageId
                )["content"];
            //
            $pageContent = $content != "0" ? json_decode($content, true) : [];

            if ($post["section"] === "meta") {
                // update the page
                $pageContent["meta"]["title"] = $post["title"];
                $pageContent["meta"]["description"] = $post["description"];
                $pageContent["meta"]["keywords"] = $post["keywords"];
            } else {
                if ($post["source_type"]) {
                    //
                    $fileLink = $post["source_link"];
                    //
                    if (
                        $post["source_type"] === "upload"
                    ) {
                        //
                        if (
                            !$_FILES['file'] && $post['source_link']
                        ) {
                            $fileLink = $post["source_link"];
                        } else {
                            // check and run for image
                            $errors = hasFileErrors($_FILES, "file", 'image|video', 20);
                            //
                            if ($errors) {
                                return SendResponse(
                                    400,
                                    ["errors" => $errors]
                                );
                            }
                            $fileLink = upload_file_to_aws(
                                "file",
                                0,
                                "product_page_",
                            );
                        }
                    }
                    //
                    $post["sourceFile"] = $fileLink;
                    $post["sourceType"] = $post["source_type"];
                }

                //
                $post["details"] = $this->input->post("details", false);
                // update the section
                $pageContent[$post["section"]] = $post;
            }


            //
            $this->cms_model->updatePage(
                $pageId,
                json_encode($pageContent)
            );
        }
        return SendResponse(
            200,
            [
                "msg" => "You have successfully updated the page.",
                "pageId" => $pageId
            ]
        );
    }


    //
    public function updateSolutionsSortOrder(int $pageId)
    {
        // get the page record
        $pageContent = $this->cms_model
            ->get_page_data(
                $pageId
            )["content"];
        //
        $post = $this->input->post(null, true);
        //
        $pageContent = json_decode($pageContent, true);

        //
        if (isset($post["sortOrders"])) {
            $tagCards = $pageContent["page"]["sections"]["products"];

            //
            foreach ($post["sortOrders"] as $key => $index) {
                //
                $item = $tagCards[$index];
                $pageContent["page"]["sections"]["products"][$key] = $item;
            }
            //        
            $msg = "You have successfully updated a cards sort order.";
        }
        //
        $this->cms_model->updatePage($pageId, json_encode($pageContent));
        //
        return SendResponse(200, [
            "msg" => $msg
        ]);
    }
}