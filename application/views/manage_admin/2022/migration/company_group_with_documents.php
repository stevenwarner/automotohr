<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php

$companyOptions = '<option value="0">Please Select</option>';

if (!empty($companies) && is_array($companies)) {
    foreach ($companies as $company) {
        $companyOptions .= '<option value="' . ($company['sid']) . '">' . (ucwords($company['CompanyName'])) . '</option>';
    }
}

?>
<!-- Loader -->
<?php $this->load->view("loader", [
    "props" => 'id = jsGroupsWithDocumentsIframe'
]); ?>

<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                     <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;">
                                            <i class="fa fa-file" aria-hidden="true"></i>Copy Groups with Documents
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <!-- Main Page -->
                                    <div id="js-main-page" class="js-block">
                                        <div class="hr-setting-page">
                                            <?php echo form_open('javascript:void(0)', array('id' => 'jsMigrationOfGroupsWithDocuments')); ?>
                                            <ul>
                                                <li>
                                                    <label>Copy From <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="jsFromCompany"><?= $companyOptions; ?></select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Copy To <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="jsToCompany"><?= $companyOptions; ?></select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <a class="site-btn" id="jsFetchSelectedGroups" href="#">Fetch Selected Groups</a>
                                                </li>
                                            </ul>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row jsGroupWithDocumentsWrap">
                                <div class="col-sm-6">
                                    <h4><strong>Total: <span id="jsGroupWithDocumentsCount">0</span></strong></h4>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <h4><strong>Selected: <span id="jsGroupWithDocumentsSelectedCount">0</span></strong></h4>
                                </div>
                            </div>
                            <!--  -->
                            <div class="panel panel-default jsGroupWithDocumentsWrap">
                                <div class="panel-heading"><h4>Copy Groups With Documents</h4></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12 text-right">
                                            <button class="btn btn-success jsStartMigrationProcess">
                                                Copy Selected Groups With Documents
                                            </button>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="row">
                                        <div class="col-sm-12 table-responsive">
                                            <table class="table table-striped">
                                                <caption></caption>
                                                <tr>
                                                    <th scope="col" class="col-sm-1 text-justify" style="background: #81b431">
                                                        <label class="control control--checkbox vam">
                                                            <input type="checkbox" id="jsSelectAll" />
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </th>
                                                    <th scope="col" class="vam" style="background: #81b431">Name</th>
                                                    <th scope="col" class="vam col-sm-2 text-justify" style="background: #81b431"># of Documents</th>
                                                </tr>
                                                <tbody id="jsGroupWithDocumentsTBody"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    #js-job-list-block {
        display: none;
    }

    .cs-required {
        font-weight: bolder;
        color: #cc0000;
    }

    /* Alertify CSS */
    .ajs-header {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }

    .ajs-ok {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }

    .ajs-cancel {
        background-color: #81b431 !important;
        color: #ffffff !important;
    }

    .vam{
        vertical-align: middle !important;
    }
</style>

<!--  -->
<script src="<?= _m(base_url('assets/2022/js/migration/groups/main'), 'js', time()); ?>"></script>