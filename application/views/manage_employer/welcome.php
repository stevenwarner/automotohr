<!-- Main Start -->
<div class="main-content">
    <div class="container">
        <div class="row">					
            <div class="col-md-12">
                <div class="welcome-page">
                    <h1>Welcome to <?php echo STORE_NAME; ?>!</h1>
                    <p>We are excited to have you as a part of community and can’t wait to help you find your next employee!</p>
                    <p>We have created a free Sub-Domain for you, which will be the URL for your New Company Career Page. Now you can post Unlimited jobs and perform all of your company HR and management functionalities.</p>
                    <p>To visit your Company Career Page please go to:  <a href="<?php STORE_PROTOCOL . $domain_name; ?>" target="_blank"><?php STORE_PROTOCOL . $domain_name; ?></a></p>
                    <p>Now that your new Company Career Page is set up, you can start using it right away. Make sure to:</p>
                    <div class="hint-box-wrp">
                        <article class="hint-box">
                            <a href="<?= base_url('add_listing') ?>"> 
                                <figure><img src="<?= base_url() ?>assets/images/feature-img-3.png"></figure>
                                <h2>Post the first Job to your new Company Career Page</h2>
                            </a>
                            <div class="text">
                                <p>Most jobseekers are actively searching for jobs posted in their area of expertise. Take advantage of this and find them now!</p>
                            </div>
                        </article>
                        <article class="hint-box">
                            <a href="<?= base_url('appearance') ?>"> 
                                <figure><img src="<?= base_url() ?>assets/images/feature-img-2.png"></figure>
                                <h2>Customize your Career Page Theme</h2>
                            </a>
                            <div class="text">
                                <p>Now you can customize your Company Career Page theme and create a unique branded experience for your users.</p>
                            </div>
                        </article>
                        <article class="hint-box">
                            <a href="<?= base_url('my_events') ?>"> 
                                <figure><img src="<?= base_url() ?>assets/images/feature-img-1.png"></figure>
                                <h2>Manage all of your Applications</h2>
                            </a>
                            <div class="text">
                                <p>You can now manage all of your job applicants in one place. Add Applicants manually. Schedule meetings and keep in contact with future Candidates using your Talent Network all with your full Application Tracking System.</p>
                            </div>
                        </article>

                        <article class="hint-box">
                            <a href="<?= base_url('screening_questionnaires') ?>"> 
                                <figure><img src="<?= base_url() ?>assets/images/screening.png"></figure>
                                <h2>Create a Screening Questionnaire</h2>
                            </a>
                            <div class="text"								
                                 <p>Now you can create questions and filter job applicants on the bases of the answers they give to your Screening Questionnaires. Create a Questionnaire now.</p>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main End -->