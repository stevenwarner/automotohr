<div class="dashboard-menu">
    <ul>
        <li>
            <a <?php if (strpos(base_url(uri_string()), site_url('dashboard')) !== false) {
                    echo 'class="active"';
                } ?> href="<?php echo base_url('dashboard'); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
        <?php
        $PM_PERMISSION = $session['employer_detail']['access_level_plus']
            ? 1 : GetPMPermissions(
                $companyId,
                $employerId,
                $employee['access_level'],
                $this
            );
        //
        $tabs = [];

        /*
        $tabs[] =     [
            'title' => 'Overview',
            'url' => '/dashboard',
            'slug' => '',
            'icon' => 'dashboard',
            'segment' => 'dashboard'
        ];
        */

        //
        if ($PM_PERMISSION) {
            $tabs[] =     [
                'title' => 'Template(s)',
                'url' => '/templates',
                'slug' => 'templates',
                'icon' => '',
                'segment' => 'templates'
            ];
        }

        $tabs[] =     [
            'title' => 'Create a Review',
            'url' => '/review/create',
            'slug' => 'create',
            'icon' => '',
            'segment' => 'review/create'
        ];

        $tabs[] =     [
            'title' => 'All Reviews',
            'url' => '/reviews',
            'slug' => 'reviews',
            'icon' => '',
            'segment' => 'reviews'
        ];


        /*  Blue
        $tabs[] =     [
            'title' => 'Assigned Reviews - Reviewer',
            'url' => '/reviews/all',
            'slug' => 'assigned-reviews',
            'icon' => '',
            'segment' => 'reviews/all'
        ];
        */

          /*  Blue
        $tabs[] =     [
           'title' => 'Assigned Reviews - Reporting Manager',
                    'url' => '/feedbacks/all',
                    'slug' => 'assigned-reviews',
                    'icon' => '',
                    'segment' => 'feedbacks/all'
        ];

        $tabs[] =     [
            'title' => 'Reviews Completed Against Me',
                    'url' => '/my-reviews',
                    'slug' => 'my-reviews',
                    'icon' => '',
                    'segment' => 'my-reviews'
         ];

*/

        // for both blue and green
        $tabs[] =     [
            'title' => 'Create a Goal',
            'url' => 'javascript:void(0)',
            'slug' => 'goal/create',
            'icon' => '',
            'segment' => 'goal/create',
            'class' => 'jsCreateGoal'
        ];

        $tabs[] =     [
            'title' => 'All Goal(s)',
            'url' => '/goals?type=all',
            'slug' => 'goals?type=all',
            'icon' => '',
            'segment' => 'goalstype=all'
        ];

        /* blue
        $tabs[] =     [
            'title' => 'My Goal(s)',
            'url' => '/goals?type=my',
            'slug' => '',
            'icon' => '',
            'segment' => '/goalstype=my'
        ];
        */

        $tabs[] =     [
            'title' => 'Company Goal(s)',
            'url' => '/goals?type=company',
            'slug' => '',
            'icon' => '',
            'segment' => '/goalstype=company'
        ];

        $tabs[] =     [
            'title' => 'Departments Goal(s)',
            'url' => '/goals?type=department',
            'slug' => '',
            'icon' => '',
            'segment' => '/goalstype=department'
        ];

        $tabs[] =     [
            'title' => 'Teams Goal(s)',
                    'url' => '/goals?type=team',
                    'slug' => '',
                    'icon' => '',
                    'segment' => '/goalstype=team'
        ];


        $tabs[] =     [
            'title' => 'Teams comparison',
            'url' => '/teamcomparison',
            'slug' => '/teamcomparison',
            'icon' => '',
            'segment' => '/teamcomparison',
            'class' => ''
        ];


        $tabs[] =     [
            'title' => 'Calendar',
            'url' => 'javascript:void(0)',
            'slug' => 'calendar',
            'icon' => '',
            'segment' => 'calendar',
            'class' => 'jsCalendarView'
        ];
        if ($PM_PERMISSION) {

            $tabs[] =     [
                'title' => 'Report',
                'url' => '/report',
                'slug' => 'report',
                'icon' => '',
                'segment' => 'report'
            ];

            /*
            $tabs[] =     [
                'title' => 'Shared Reports',
                'url' => '/sharedrep',
                'slug' => 'sharedrep',
                'icon' => '',
                'segment' => 'sharedrep'
            ];
            */
            
            $tabs[] =     [
                'title' => 'Settings',
                'url' => '/settings',
                'slug' => 'settings',
                'icon' => '',
                'segment' => 'settings'
            ];
        }

        /*
        $tabs[] = [
            'title' => 'More',
            'submenu' => $sub
        ];
        */

        //
        $lis = '';
        //
        $baseURL = purl();
        //
        foreach ($tabs as $tab) {
            //
           // if (isset($tab['submenu'])) {
                /*
                $tmp = '';
                $lis .= '<li class="has">';
                $lis .= '   <a href="javascript:void(0)"><i class="fa fa-list"></i>&nbsp;&nbsp;' . ($tab['title']) . ' &nbsp; <i class="fa"></i></a>';
                $lis .= '   <ul>';

                foreach ($tab['submenu'] as $item) {
                    $params   = $_SERVER['QUERY_STRING'];
                    $activeURL = $this->uri->uri_string() . trim($params);
                    $tmp .= '<a href="' . ($item["url"] == "javascript:void(0)" ? $item['url'] : $baseURL . $item['url']) . '" ' . "class='" . (strpos($activeURL, $item['segment']) !== FALSE  ?  'active ' : ' ') .   $item['class'] .   "'" . '><i class="fa fa-' . ($item['icon']) . '"></i> ' . ($item['title']) . '</a>';
                }
                //          
                $lis .=       $tmp;
                $lis .= '   </ul>';
                $lis .= '</li>';
            } else {
                */
                //
                $lis .= '<li><a ' . (isset($tab['props']) ? $tab['props'] : "") . ' class="csF16 ' . (isset($tab['class']) ? $tab['class'] : '') . ' ' . ($tab['segment'] == '' || strpos($this->uri->uri_string(), $tab['segment']) !== FALSE  ?  'active' : '') . '" href="' . ($tab['url'] == 'javascript:void(0)' ? $tab['url'] : $baseURL . $tab['url']) . '" ><i class="fa fa-' . ($tab['icon']) . '"></i> ' . ($tab['title']) . '</a></li>';
          //  }
        }
        echo $lis;
        ?>
    </ul>
</div>

<!-- -->
<div class="dash-box service-contacts hidden-xs">
    <div class="admin-info">
        <h2>Need help with your AutomotoHR Platform? <br />Contact one of our Talent Network Partners at</h2>
        <div class="profile-pic-area">
            <div class="form-col-100">
                <ul class="admin-contact-info">
                    <li>
                        <label>Sales Support</label>
                        <?php $data['session'] = $this->session->userdata('logged_in'); ?>
                        <?php $company_sid = $data["session"]["company_detail"]["sid"]; ?>
                        <?php $company_info = get_contact_info($company_sid); ?>
                        <span><i class="fa fa-phone"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_phone_no'] : TALENT_NETWORK_SALE_CONTACTNO; ?></span>
                        <span><a href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?>"><i class="fa fa-envelope-o"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['exec_sales_email'] : TALENT_NETWORK_SALES_EMAIL; ?></a></span>
                    </li>
                    <li>
                        <label>Technical Support</label>
                        <span><i class="fa fa-phone"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_phone_no'] : TALENT_NETWORK_SUPPORT_CONTACTNO; ?></span>
                        <span><a href="mailto:<?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?>"><i class="fa fa-envelope-o"></i> <?php echo isset($company_info) && sizeof($company_info) > 0 ? $company_info[0]['tech_support_email'] : TALENT_NETOWRK_SUPPORT_EMAIL; ?></a></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- -->
<?php
$getCompanyHelpboxInfo = get_company_helpbox_info($company_sid);
if ($getCompanyHelpboxInfo[0]['box_status'] == 1) {
?>
    <div class="dash-box service-contacts hidden-xs">
        <div class="admin-info">
            <h2><?php echo $getCompanyHelpboxInfo[0]['box_title']; ?></h2>
            <div class="profile-pic-area">
                <div class="form-col-100">
                    <ul class="admin-contact-info">
                        <li>
                            <label>Support</label>
                            <?php if ($getCompanyHelpboxInfo[0]['box_support_phone_number']) { ?>
                                <span><i class="fa fa-phone"></i> <?php echo $getCompanyHelpboxInfo[0]['box_support_phone_number']; ?></span><br>
                            <?php } ?>
                            <span>
                                <button class="btn btn-orange jsCompanyHelpBoxBtn">
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>&nbsp;<?= $getCompanyHelpboxInfo[0]['button_text']; ?>
                                </button>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('company_help_box_script'); ?>

<?php } ?>