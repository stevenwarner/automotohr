<!--  -->
<div class="container">
    <div class="">
        <!-- Heading -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-large">
                    <strong>
                        Payroll Admin
                    </strong>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-medium">
                    The person who will manage and run the payroll for this store.
                </h1>
            </div>
        </div>
        <!-- Body -->
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="text-medium">
                    First name
                </label>
                <p class="text-medium"><?= $admin['first_name']; ?></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="text-medium">
                    Last name
                </label>
                <p class="text-medium"><?= $admin['last_name']; ?></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="text-medium">
                    Email
                </label>
                <p class="text-medium"><?= $admin['email_address']; ?></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-black text-medium jsBackToStep3">
                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;
                    Back
                </button>
            </div>
        </div>
    </div>
</div>