<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
    rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/interview-call.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/loader.css" />
<style>
    body {
        margin: 0px;
    }
    
    .interview-container {
        font-family: "Open Sans", sans-serif;
        min-height: 100vh;
        background-color: #008eab;
        color: #fff;
    }
    
    .main {
        min-height: calc(100vh - 79px);
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 50px;
        padding: 0 20px;
    }

    .expired-content {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        max-width: 600px;
        text-align: center;
    }

    .expired-icon {
        position: relative;
        width: 250px;
        height: 250px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
    }

    .expired-icon-wrapper {
        border: 1px solid #fff;
        background-color: #0D9FBD;
        border-radius: 10px;
        width: 250px;
        height: 250px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .expired-icon svg {
        width: 120px;
        height: 120px;
        color: #fff;
    }

    .expired-title {
        font-weight: 600;
        font-size: 48px;
        line-height: 50px;
        margin-bottom: 20px;
        color: #fff;
    }

    .expired-message {
        font-weight: 400;
        font-size: 20px;
        line-height: 28px;
        margin-bottom: 40px;
        color: #f1f1f1;
        max-width: 500px;
    }

    .action-buttons {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn-primary, .btn-secondary {
        padding: 15px 30px;
        border: none;
        border-radius: 100px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        min-width: 180px;
    }

    .btn-primary {
        background-color: #0DCAF0;
        color: #fff;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-primary:hover {
        background-color: #0bb5d1;
        transform: translateY(-2px);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    }

    .btn-secondary {
        background-color: transparent;
        color: #fff;
        border: 2px solid #FFFFFF4D;
    }

    .btn-secondary:hover {
        background-color: #FFFFFF1A;
        border-color: #FFFFFF80;
    }

    .footer {
        margin: auto;
        margin-bottom: 20px;
    }

    @media screen and (max-width: 1025px) {

        .expired-title {
            font-size: 36px;
            line-height: 40px;
        }

        .expired-message {
            font-size: 18px;
            line-height: 24px;
        }
    }

    @media screen and (max-width: 769px) {

        .expired-title {
            font-size: 28px;
            line-height: 32px;
        }

        .expired-message {
            font-size: 16px;
            line-height: 22px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            max-width: 280px;
        }
    }
</style>
<div class="interview-container">

    <div class="main">
        <div class="expired-content">
            <div class="expired-icon">
                <div class="expired-icon-wrapper">
                    <!-- Clock/Timer Expired Icon -->
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12,6 12,12 16,14"/>
                        <path d="M16.24 7.76a6 6 0 0 1 0 8.49m-8.48-.01a6 6 0 0 1 0-8.49"/>
                    </svg>
                </div>
            </div>

            <h1 class="expired-title">Interview Session Expired</h1>
            
            <p class="expired-message">
                We're sorry, but this interview session has expired. The interview link is no longer valid and cannot be accessed. Please contact the hiring team for further assistance or to reschedule your interview.
            </p>

            <div class="action-buttons">
                <!-- <a href="http://<?php echo $portal_employeer['sub_domain']; ?>" class="btn-primary">
                    Return to <?php echo $company['name']; ?>
                </a> -->
                <a href="http://<?php echo $portal_employeer['sub_domain']; ?>" class="btn-secondary">
                    Return to <?php echo $company['name']; ?>
                </a>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Powered by</p> <img src="/assets/images/automotoHr-logo.png" alt="automoto Logo" />
    </div>
</div>

<script>

    function contactSupport() {
        // Replace with actual support contact method
        window.location.href = 'mailto:support@company.com';
        // Or redirect to support page
        // window.location.href = '/support';
    }
</script>