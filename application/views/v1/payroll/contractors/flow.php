<br>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <div class="sidebar-nav">
                <div class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <span class="visible-xs navbar-brand">Contractor payroll menu</span>
                    </div>
                    <div class="navbar-collapse collapse sidebar-navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li <?= $step == 'personal_details' ? 'class="active"' : ''; ?>><a href="#" class="csF16 jsMenuTrigger" data-step="personal_details">Personal details</a></li>
                            <li <?= $step == 'home_address' ? 'class="active"' : ''; ?>><a href="#" class="csF16 jsMenuTrigger" data-step="home_address">Home address</a></li>
                            <li <?= $step == 'payment_method' ? 'class="active"' : ''; ?>><a href="#" class="csF16 jsMenuTrigger" data-step="payment_method">Payment Method</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9">
            <?php $this->load->view("v1/payroll/contractors/" . $step); ?>
        </div>
    </div>
</div>

<style>
    /* make sidebar nav vertical */
    @media (min-width: 768px) {
        .sidebar-nav .navbar .navbar-collapse {
            padding: 0;
            max-height: none;
        }

        .sidebar-nav .navbar ul {
            float: none;
        }

        .sidebar-nav .navbar ul:not {
            display: block;
        }

        .sidebar-nav .navbar li {
            float: none;
            display: block;
        }

        .sidebar-nav .navbar li a {
            padding-top: 12px;
            padding-bottom: 12px;
        }
    }

    .navbar-default .navbar-nav>.active>a,
    .navbar-default .navbar-nav>.active>a:hover,
    .navbar-default .navbar-nav>.active>a:focus {
        background-color: #81b431;
        color: #fff;
        font-weight: 700;
    }
</style>