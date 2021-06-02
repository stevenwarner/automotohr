<div class="csPageSection jsPageSection" data-key="templates">
    <!-- Box Header -->
    <div class="csPageBoxHeader p10">
        <h2 class="csF14">
            <em>Craft a new review from the ground up or pick a template with insightful
                questions.</em>
        </h2>
    </div>
    <!-- Box Body -->
    <div class="csPageBoxBody p10">
        <br>
        <br>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12">
                <button class="btn btn-lg btn-orange form-control csF14">
                    <i class="fa fa-eye csF14" aria-hidden="true"></i> I want to create a review
                    from scratch
                </button>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12">
                <p class="text-center csF18 csB9">----------------------- OR
                    -----------------------</p>
            </div>
        </div>
    </div>
    <!-- csPageBoxBody -->
    <div class="csPageBoxBody p10">
        <?php $this->load->view($pp.'reviews/partials/company_templates');?>
    </div>
    <!-- csPageBoxBody -->
    <div class="csPageBoxBody p10">
        <?php $this->load->view($pp.'reviews/partials/personal_templates');?>
    </div>
</div>