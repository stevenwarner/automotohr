<!--  -->
<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Heading -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF28 csB7">
                    Payroll Admin
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h1 class="csF22">
                    The person who will manage and run the payroll for this store.
                </h1>
            </div>
        </div>
        <!-- Body -->
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="csF18 csB7">
                    First name
                </label>
                <p class="csF18"><?=$primaryAdmin['first_name'];?></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="csF18 csB7">
                    Last name
                </label>
                <p class="csF18"><?=$primaryAdmin['last_name'];?></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="csF18 csB7">
                    Email
                </label>
                <p class="csF18"><?=$primaryAdmin['email_address'];?></p>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="csF18 csB7">
                    Phone number
                </label>
                <p class="csF18"><?=$primaryAdmin['phone_number'];?></p>
            </div>
        </div>
        
        <br>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-black csF16 csB7 jsPayrollBackToOnboard">
                    <i class="fa fa-long-arrow-left" aria-hidden="true"></i>&nbsp;
                    Back
                </button>
            </div>
        </div>
    </div>
</div>