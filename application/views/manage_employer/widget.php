<div class="emp-main-content">
    <div class="employer-portal-container">
        <header class="hr-page-header">
            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
            <h2>Portal Widget</h2>
            <div class="back-btn">
                <a class="siteBtn redBtn" style="margin-bottom: 10px;" id="" href="<?= base_url('dashboard') ?>">&laquo; BACK</a>
            </div>
        </header>
        <div class="settings-wrap">
            <div class="settings-section">
                <div class="settings-form">
                    <div class="title-area">
                        <div class="figure widget-icon"><img src="<?= base_url() ?>assets/images/widget-small-icon.png" alt="image" class="img-resp"></div>
                        <span class="topheading">Job Portal Widget</span>
                    </div>                     
                    <div class="title-area">
                        <h4>Your Employer Portal jobs will be displayed in Widget</h4>
                        <p>To display your jobs widget on your website, please copy the text from the text area below and paste it into the area on your website, you would like your jobs to display.</p>
                        <span><textarea rows="4" cols="80" readonly="readonly"><?=htmlspecialchars($script_tag)?></textarea></span>
                    </div>         
                </div>	
                    <div class="settings-form">
                    <div class="title-area">
                        <div class="figure widget-icon"><img src="<?= base_url() ?>assets/images/web.png" alt="image" class="img-resp"></div>
                        <span class="topheading">JOB PORTAL Webservice</span>
                    </div> 
                    <br><br>
                    <div class="title-area">
                        <h4>Your Employer Portal jobs will be Exported as XML file</h4>
                        <p>To get your jobs XML file Please copy the text from textarea below and use it as Webservice. You can add/remove attributes from the link.</p>
                        <span><textarea rows="4" cols="80" readonly="readonly"><?=htmlspecialchars($api_link)?></textarea></span>
                    </div>         
                </div>	
            </div>
        </div>
    </div>
</div>