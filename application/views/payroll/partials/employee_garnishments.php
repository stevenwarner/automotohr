<br>
<div class="panel panel-success">
    <div class="csPR">
        <?php $this->load->view('loader_new', ['id' => 'employee_garnishment']); ?>
        <div class="panel-heading">
            <h1 class="csF16 csB7 csW m0">
                Garnishments
                <span class="pull-right">
                    Garnishments Found: <span class="jsGarnishmentCount">0</span>
                </span>
            </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-xs-12 text-right">
                    <button class="btn btn-success csF16 csB7 jsGarnishentAdd">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Garnishment
                    </button>
                </div>
            </div>
            <!--  -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col" class="csBG1 csF16 csB7 csW">Description</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Amount</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Court Ordered</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Times</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Recurring</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Deduct As Percentage</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Status</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Last Modified</th>
                            <th scope="col" class="csBG1 csF16 csB7 csW text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="jsGarnishmentDataBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>