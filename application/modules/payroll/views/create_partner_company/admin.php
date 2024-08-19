<!--  -->
<div class="container">
    <div class="">
        <!-- Heading -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-large">
                    We are excited to help you pay your team.
                </h1>
            </div>
        </div>
        <!-- Body -->
        <div class="row">
            <div class="col-sm-12">
                <p class="text-medium">
                    Start with a few questions about your business. Then we'll get into the payroll details.
                    Your information will be shared as you go. Let's get started!
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <p class="text-medium">
                    By continuing you are accepting Gusto's Terms & Service and Privacy Policy.
                </p>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-orange <?= $admin ? 'jsCreatePartnerCompanyProcessBtn' : 'disabled'; ?> ">
                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i>&nbsp;
                    Continue
                </button>
                <?php if (!$admin): ?>
                    <button class="btn btn-orange jsPayrollSetAdmin">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;
                        Set Admin
                    </button>
                <?php else: ?>
                    <button class="btn btn-orange jsPayrollViewAdminDetails">
                        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;
                        View Admin
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>