<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
    <div class="pto-category-box">
        <h4 class="js-to-heading">My Time Off</h4>
        <div class="text">
            <article>
                <figure class="cs-sidebar-timeoff-container" id="js-pending-time"></figure>
                <div class="article-text">
                    &nbsp;Hours Remaining
                    <i class="cs-jam-ul fa fa-question-circle question-custom"
                        data-html="true"
                        data-toggle="popover"
                        data-placement="top"
                        data-trigger="hover"
                        data-content="Balance available">
                    </i>
                </div>
            </article>
        </div>
        <div class="text">
            <article>
                <figure class="cs-sidebar-timeoff-container" id="js-consumed-time"></figure>
                <div class="article-text">
                    &nbsp;Hours Approved
                    <i class="cs-jam-ul fa fa-question-circle question-custom"
                        data-html="true"
                        data-toggle="popover"
                        data-placement="top"
                        data-trigger="hover"
                        data-content="Balance used">
                    </i>
                </div>
            </article>
        </div>
        <div class="text">
            <article>
                <figure class="cs-sidebar-timeoff-container" id="js-allowed-time"></figure>
                <div class="article-text">
                    &nbsp;Hours Allowed
                    <i class="cs-jam-ul fa fa-question-circle question-custom"
                        data-html="true"
                        data-toggle="popover"
                        data-placement="top"
                        data-trigger="hover"
                        data-content="Balance allowed">
                    </i>
                </div>
            </article>
        </div>
    </div>
    <!-- Policies Holidays -->
    <div class="pto-category-box">
        <h4>Company Holidays</h4>
        <div class="text">
            <span class="btn btn-success btn-5 form-control" id="js-company-holidays-btn">Show</span>
            <!-- <ul class="js-holiday-list-ul"></ul> -->
        </div>
    </div>
    <!-- Policies details -->
    <div class="pto-category-box">
        <h4>Time Off Policies</h4>
        <div class="text">
            <ul class="js-policy-list-ul"></ul>
        </div>
    </div>
</div>

<style>
    .js-holiday-list-ul li,
    .js-holiday-list-ul span{ color: #000 !important; }
</style>

<script>
    $('.cs-jam-ul').popover({ trigger: 'hover' });
</script>