<div class="main-content">
    <div class="hr-heading-strip">
        <div class="dual-color-heading">
            <h2>Why <span>US</span></h2>
        </div>
        <h1>Recruit, Onboard, and Manage all of your Employees in one place with a single login. Hire to Retire Talent Management platform.</h1>
    </div>
    <!-- Info Boxes Start -->
    <div class="info-box-wrp">
        <article class="v1">
            <figure>
                    <?php if($home_page['banner_1_type'] == 'youtube'){ ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?= $home_page['why_us_banner_1'] ?>"></iframe>
                        </div>
                    <?php }else if($home_page['banner_1_type'] == 'vimeo'){ ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="//player.vimeo.com/video/<?= $home_page['why_us_banner_1'] ?>"></iframe>
                        </div>
                    <?php }else if($home_page['banner_1_type'] == 'upload_video'){ ?>
                        <video style="vertical-align: middle; max-height: 800px; max-width: 100%;" controls src="<?= base_url($home_page['why_us_banner_1']) ?>">
                        </video>
                    <?php }else if($home_page['banner_1_type'] == 'upload_image'){ ?>
                        <img style="vertical-align: middle; max-height: 800px; max-width: 100%;" src="<?php echo AWS_S3_BUCKET_URL . $home_page['why_us_banner_1']; ?>">
                    <?php }?>
                
            </figure>
            <div class="text">
                <div class="info-box">
                    <h2>So why should you use or switch to <?php echo STORE_NAME; ?>?</h2>
                    <ul class="points-list">
                        <li>Build your Company Career pages in minutes</li>
                        <li>Post to all of your preferred job boards</li>
                        <li>Hire on the go with your mobile optimized hiring system</li>
                        <li>Easily evaluate candidates and collaborate with teams to make the best hire</li>
                        <li>Maintain all of your Companies pertinent and legally required hiring information, government compliance forms and data all in one place, with a single secure log in.</li>
                        <li>Complete easily managed Onboarding process.</li>
                    </ul>
                </div>
            </div>
        </article>
        <article class="v1 v2">
            <div class="text">
                <div class="info-box">
                    <h2>Transform Your HR and Recruiting Into a High-Powered Employment Brand Marketing and Sales Machine</h2>
                    <p>Build amazingly beautiful branded career websites and micro-sites, Delight candidates with an amazing candidate experience, Distribute your jobs to thousands of job boards, Build a kick butt employee referral engine and Talent Network, Seamlessly communicate with candidates, Schedule and manage interviews with ease, Integrate skills assessments to build a pipeline of top talent, Complete easily managed On-boarding and hiring process, Hire on the go with a mobile hiring platform, EEO & OFCCP: ensure the compliance of your processes.</p>
                </div>
            </div>
        </article>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                <div style="height: 800px; width: 100%; white-space: nowrap; text-align: center; margin: 1em 0;">
                    <span style="display: inline-block; height: 100%; vertical-align: middle;"></span>
                    <img style="vertical-align: middle; max-height: 500px; max-width: 100%;" src="<?php echo base_url('assets/images/ahr_award_01.png'); ?>">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="text" style="font-weight: 600; line-height: 28px; font-size: 18px; padding-top: 70px;">
                    <div class="info-box hr-targets">
                        <h2>AutomotoHR Wins 2018 AWA Award For "Dealership Management Tool"</h2>
                        <p>
                            PCG is pleased to announce that AutomotoHR has won a 2018 AWA Award in the Dealership Management Tools category.
                        </p>
                        <p>
                            AutomotoHR is a complete HR management system, for both large and small dealerships. It targets and tracks top automotive talent through visibility on 100s of job boards, and includes the building of a professional, branded career website and micro-sites to enhance the candidate experience and share quality information about the dealership. AutomotoHR allows managers to schedule, interview, and hire with ease; as well as document all communications. The platform ensures EEO and OFCCP compliance with a full audit trail and can be used to ensure the onboarding and training process are followed.
                        </p>
                        <p>
                            Inter-office communications and day-to-day activities in the service drive, showroom, accounting and HR departments can often verge on chaos. Winners of the Dealership Management Tools category help to automate and track processes to help the dealership run more smoothly and efficiently.
                        </p>
                        <p>
                            We congratulate AutomotoHR for being named a 2018 AWA winner in the Dealership Management Tools category, and thank them for participating in this year’s review process.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="text" style="font-weight: 600; line-height: 28px; font-size: 18px; padding-top: 200px;">
                    <div class="info-box hr-targets">
                        <h2>Get everything you need to target</h2>
                        <p>Get everything you need to target, attract and hire the top talent with a best-in-class candidate experience. Includes all the recruitment, HR and hiring marketing capabilities you'll need. Provide a modern, mobile user experience that your hiring managers will embrace to easily collaborate and close the best candidates. Run the entire HR platform your way. Manage your Talent acquisition through an open and configurable secure cloud based platform for all of your processes, partners and integrations.</p>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center">
                    <?php if($home_page['banner_2_type'] == 'youtube'){ ?>
                        <div class="embed-responsive embed-responsive-16by9" style="margin-top: 250px;margin-bottom:50px;">
                            <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?= $home_page['why_us_banner_2'] ?>"></iframe>
                        </div>
                    <?php }else if($home_page['banner_2_type'] == 'vimeo'){ ?>
                        <div class="embed-responsive embed-responsive-16by9" style="margin-top: 250px;margin-bottom:50px;">
                            <iframe class="embed-responsive-item" src="//player.vimeo.com/video/<?= $home_page['why_us_banner_2'] ?>"></iframe>
                        </div>
                    <?php }else if($home_page['banner_2_type'] == 'upload_video'){ ?>
                        <div style="height: 800px; width: 100%; white-space: nowrap; text-align: center; margin: 1em 0;">
                            <span style="display: inline-block; height: 100%; vertical-align: middle;"></span>
                            <video style="vertical-align: middle; max-height: 800px; max-width: 100%;" controls src="<?= base_url($home_page['why_us_banner_2']) ?>">
                            </video>
                        </div>
                    <?php }else if($home_page['banner_2_type'] == 'upload_image'){ ?>
                        <div style="height: 800px; width: 100%; white-space: nowrap; text-align: center; margin: 1em 0;">
                            <span style="display: inline-block; height: 100%; vertical-align: middle;"></span>
                            <img style="vertical-align: middle; max-height: 800px; max-width: 100%;" src="<?php echo AWS_S3_BUCKET_URL . $home_page['why_us_banner_2']; ?>">
                        </div>
                    <?php }?>
            </div>
        </div>



        <article id="one-stop-shop-area" class="v1 v2">
            <div class="text">
                <div class="info-box">
                    <h2><?php echo STORE_NAME; ?> is designed to be your one-stop shop, for all your HR and hiring needs.</h2>
                    <div class="col-lg-6 col-md-offset-3">
                        <ul class="points-list align-left">
                            <p>Easily manage your job postings and advertise on the places that matter. </p>
                            <li>Your career website.</li>
                            <li>Your company Facebook page.</li>
                            <li>Any job board.</li>
                            <li>Then manage all your candidates in one place.</li>
                            <li>Run and manage Assessment and Background checks without leaving the system.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

    </div>
    <!-- Info Boxes End -->
    <div class="hr-heading-strip">
        <div class="dual-color-heading">
            <h2>HR and <span>Recruiting</span></h2> 
        </div>
        <h1>Everyone is incredibly frustrated by how hard it is to find & manage amazing talent.</h1>
    </div>
    <div class="hr-video-area">
        <div class="row">
            <div class="col-lg-6" style="float:none;margin:0 auto;">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="//www.youtube.com/embed/<?php echo $home_page['hr_video']; ?>"></iframe>
                </div>
            </div>
<!--            <div class="col-lg-6">
                <div class="info-box hr-targets">
                    <h2>We share that frustration!</h2>
                    <p>HR and Recruiting is a fragmented, often confusing process undermined by outdated technology that turns off candidates and hiring managers.</p>
                    <p>Now imagine a world where it’s easy to find great Automotive industry candidates, it’s easy for people to show interest in jobs, it’s easy for hiring teams to collaborate, and your HR and recruiting vendors are just a click away.</p>
                    <p>We imagined it. And then we delivered it.</p>
                </div>
            </div>-->
        </div>
    </div>
    <div class="hr-heading-strip">
        <div class="dual-color-heading">
            <h2>find great&nbsp;<span>people</span></h2>
        </div>
        <h1>Get the word out about your job opportunities and be seen by Millions of interested jobseekers every day.</h1>
    </div>
    <!-- Top Job Employers Start -->
    <div class="top-job-employer">
        <figure>
            <img class="img-responsive" src="<?= base_url() ?>/assets/images/partner-banner-new.jpg">
            <figcaption>
                <div class="container">
                    <div class="col-lg-5 col-md-5 col-xs-7 col-sm-7 col-md-offset-1">
                        <ul class="points-list">
                            <p>When you list your job opportunities on <a href=""><?php echo STORE_DOMAIN; ?></a> they will also Automatically be listed on</p>
                        </ul>
                    </div>
                </div>
            </figcaption>
        </figure>
    </div>
    <!-- Top Job Employers End -->
    <div class="hr-heading-strip">
        <div class="dual-color-heading">
            <h2>Talent<span> Management</span></h2>  
        </div>
        <h1>Tune In To The Future of Hiring and Employee Management</h1>
    </div>
    <div class="people-area">
        <div class="row">
            <div class="grid-columns">
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                    <article>
                        <!--<figure><img src="<?= base_url() ?>/assets/images/img-people-1.jpg"></figure>-->
                        <div class="text">
                            <div class="info-box">
                                <h2>Easily advertise on the places that matter</h2>
                                <p>Your career website. Your company Facebook page. Any job board. Then manage all your Candidates in one place.</p>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                    <article>
                        <!--<figure><img src="<?= base_url() ?>/assets/images/img-people-2.jpg"></figure>-->
                        <div class="text">
                            <div class="info-box">
                                <h2>Turn all your employees into recruiters</h2>
                                <p>Make referrals from your social networks. Then manage social referrals in one place. Know your top referrers across all jobs.</p>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                    <article>
                        <!--<figure><img src="<?= base_url() ?>/assets/images/img-people-3.jpg"></figure>-->
                        <div class="text">
                            <div class="info-box">
                                <h2>Never leave a great candidate waiting</h2>
                                <p>Let candidates easily apply and communicate with you directly through the system. Mobile Optimized for hiring on the go. Candidates can register through your Talent Network to stay in the loop.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="btn-section">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-1-new.jpg">
                                <a href="<?/*= base_url() */?>services/job-distribution/"><figcaption><span>Job Distribution<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-2-new.jpg">
                                <a href="<?/*= base_url() */?>services/career-website/"><figcaption><span>Career Website<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-3-new.jpg">
                                <a href="<?/*= base_url() */?>services/social-recruiting/"><figcaption><span>Social Recruiting<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-4-new.jpg">
                                <a href="<?/*= base_url() */?>services/facebook-hiring/"><figcaption><span>Facebook Hiring<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-5-new.jpg">
                                <a href="<?/*= base_url() */?>services/mobile-recruiting/"><figcaption><span>Mobile Recruiting<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-6-new.jpg">
                                <a href="<?/*= base_url() */?>services/onboarding-employee-management/"><figcaption><span>Onboarding / Employee Management<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-7-new.jpg">
                                <a href="<?/*= base_url() */?>services/candidate-management/"><figcaption><span>Candidate Management System<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    <div class="hr-heading-strip">
        <div class="dual-color-heading">
            <h2>HIRE GREAT<span> PEOPLE</span></h2>  
        </div>
    </div>
    <div class="people-area">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                <article class="post-v2">
                    <figure>
                        <!--<img src="<?= base_url() ?>/assets/images/img-people-4-new.jpg">-->
                        <h2>Collaborate to make the right hire</h2>
                    </figure>
                    <div class="text">
                        <div class="info-box">
                            <p>Hiring is a team sport so hire together. Invite colleagues and recruiters to your hiring team. Perform assessments directly from our platform.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                <article class="post-v2">
                    <figure>
                        <!--<img src="<?= base_url() ?>/assets/images/img-people-5-new.jpg">-->
                        <h2>Understand the big picture</h2>
                    </figure>
                    <div class="text">
                        <div class="info-box">                            
                            <p>Get at-a-glance insights about your jobs &amp; candidates. Dive into real-time analytics &amp; compliance reporting.</p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                <article class="post-v2">
                    <figure>
                        <!--<img src="<?= base_url() ?>/assets/images/img-people-6-new.jpg">-->
                        <h2>Connect all your hiring tools</h2>
                    </figure>
                    <div class="text">
                        <div class="info-box">                            
                            <p>Tap into our pre-integrated job board partners, assessment vendors, Background and Drug Testing integrations, and leverage our open API.</p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
    <!--<div class="btn-section">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-8.jpg">
                                <a href="<?/*= base_url() */?>services/candidate-experience/"><figcaption><span>Candidate Experience<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-11.jpg">
                                <a href="<?/*= base_url() */?>services/interview-management/"><figcaption><span>Interview Management<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-12.jpg">
                                <a href="<?/*= base_url() */?>services/candidate-assessment/"><figcaption><span>Candidate Assessment<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-9.jpg">
                                <a href="<?/*= base_url() */?>services/candidate-management/"><figcaption><span>Candidate Management<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-10.jpg">
                                <a href="<?/*= base_url() */?>services/collaborative-hiring/"><figcaption><span>Collaborative Hiring<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-13.jpg">
                                <a href="<?/*= base_url() */?>services/recruiting-analytics/"><figcaption><span>Recruiting Analytics<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                        <article class="btn-box">
                            <figure>
                                <img src="<?/*= base_url() */?>/assets/images/img-btn-14.jpg">
                                <a href="<?/*= base_url() */?>services/api-integrations/"><figcaption><span>API & Integrations<div class="external-link"><i class="fa fa-external-link"></i></div></span></figcaption></a>
                            </figure>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

    <?php if (!$this->session->userdata('logged_in') && $title != 'Register') { ?> 
        <?php $this->load->view('main/demobuttons'); ?>
    <?php } ?> 
</div>
<!-- Main End -->	