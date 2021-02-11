<div class="dashboard-menu hidden-print">
    <ul>
        <li>
            <a href="<?= base_url("dashboard"); ?>">
                <figure><i class="fa fa-th"></i></figure>Dashboard
            </a>
        </li>
        <?php if($session['employer_detail']['access_level_plus'] == 1) { ?>
        <li>
            <a href="<?= base_url("performance/template/create"); ?>" <?= preg_match('/template\/create/', uri_string()) === 1 ? 'class="active"' : '' ?>>
                <figure><i class="fa fa-plus-square"></i></figure>Create A Review Template
            </a>
        </li>
        <li>
            <a href="<?= base_url("performance/template/view"); ?>" <?= preg_match('/template\/view/', uri_string()) === 1 ? 'class="active"' : '' ?>>
                <figure><i class="fa fa-th-list"></i></figure>View Review Templates
            </a>
        </li>
        <?php } ?>
        <li>
            <a href="<?= base_url("performance/review/create"); ?>" <?= preg_match('/review\/create/', uri_string()) === 1 ? 'class="active"' : '' ?>>
                <figure><i class="fa fa-plus-square"></i></figure>Create A Review
            </a>
        </li>
        <li>
            <a href="<?= base_url("performance/review/view"); ?>" <?= (preg_match('/review\/view/', uri_string()) === 1 || preg_match('/performance\/edit/', uri_string()) === 1 ) ? 'class="active"' : '' ?>>
                <figure><i class="fa fa-th-list"></i></figure>View Reviews
            </a>
        </li>
        <li>
            <a href="<?= base_url("performance/assigned/view"); ?>" <?= preg_match('/assigned\/view/', uri_string()) === 1 ? 'class="active"' : '' ?>>
                <figure><i class="fa fa-list"></i></figure>Assigned Reviews
            </a>
        </li>
        <li>
            <a href="<?= base_url("performance/feedback"); ?>" <?= (preg_match('/performance\/feedback/', uri_string()) === 1 || preg_match('/performance\/feedback\/edit/', uri_string()) === 1 ) ? 'class="active"' : '' ?>>
                <figure><i class="fa fa-comments-o"></i></figure>Feedback
            </a>
        </li>
        <?php if($session['employer_detail']['access_level_plus'] == 1) { ?>
        <li>
            <a href="<?= base_url("performance/report"); ?>" <?= preg_match('/report/', uri_string()) === 1 ? 'class="active"' : '' ?>>
                <figure><i class="fa fa-pie-chart"></i></figure>Report
            </a>
        </li>
        <?php } ?>
    </ul>
</div>