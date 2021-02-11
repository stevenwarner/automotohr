<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <header class="heading-title">
                    <h2 class="page-title"><?php echo STORE_NAME; ?> - Sitemap</h2>
                </header>
                <div class="amr-universal-section"> 
                    <div class="footer-widgets">
                        <div class="container-fluid">
                            <div class="row">          
                                <ul class="sitemap_listing">
                                        <li><a href="/">Home Page</a></li>
                                        <li><a href="<?= base_url() ?>schedule_your_free_demo/">SCHEDULE YOUR FREE DEMO</a></li>
                                        <li><a href="<?= base_url() ?>login/">Employee Login</a></li>
                                        <li><a href="<?= base_url() ?>services/job-distribution/">Job Distribution</a></li>
                                        <li><a href="<?= base_url() ?>services/career-website/">Career Website</a></li>
                                        <li><a href="<?= base_url() ?>services/facebook-hiring/">Facebook Hiring</a></li>
                                        <li><a href="<?= base_url() ?>services/social-recruiting/">Social Recruiting</a></li>
                                        <li><a href="<?= base_url() ?>services/mobile-recruiting/">Mobile Recruiting</a></li>
                                        <li><a href="<?= base_url() ?>services/candidate-experience/">Candidate Experience</a></li>
                                        <li><a href="<?= base_url() ?>services/collaborative-hiring/">Collaborative Hiring</a></li>
                                        <li><a href="<?= base_url() ?>services/candidate-management/">Candidate Management</a></li>
                                        <li><a href="<?= base_url() ?>services/interview-management/">Interview Management</a></li>
                                        <li><a href="<?= base_url() ?>services/onboarding-employee-management/">Onboarding / Employee Management</a></li>
                                        <li><a href="<?= base_url() ?>services/recruiting-analytics/">Recruiting Analytics</a></li>
                                        <li><a href="<?= base_url() ?>services/api-integrations/">API & Integrations</a></li>
                                        <li><a href="<?= base_url() ?>services/partners/">Partners</a></li>
                                        <li><a href="<?= base_url() ?>services/eeoc-compliant/">EEOC Compliant</a></li>
                                        <li><a href="<?= base_url() ?>services/developers/">Developers</a></li>
                                        <li><a href="<?= base_url() ?>services/privacy-policy/">Privacy Policy</a></li>
                                        <li><a href="<?= base_url() ?>services/about-us/">Company</a></li>
                                        <li><a href="<?= base_url() ?>services/about-us/">About us</a></li>
                                        <li><a href="<?= base_url() ?>contact_us">Contact Us</a></li>
                                    </ul>
                            </div>
                        </div>
                    </div>
                </div>               
            </div>
        </div>
    </div>
    <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
        <?php $this->load->view('main/demobuttons'); ?>
    <?php } ?> 
</div>
<style>
    ul.sitemap_listing {
        list-style-type: square;
        margin: 0 0 20px 15px;
    }
    .sitemap_listing li,.sitemap_listing li a {
        color:#81B431;
    }
    .sitemap_listing li{
        color: #81b431;
        float: left;
        font-size: 16px;
        font-weight: 600;
        margin: 5px 0;
        text-transform: uppercase;
        width: 33%;
    }
    .sitemap_listing li a {
        color: #81b431;
        font-size: 16px;
        font-weight: 600;
        margin: 5px 0;
        text-transform: uppercase;
    }
    .sitemap_listing li a:hover{
        color:#000;
    }
</style>