
<!-- Main -->
<div class="mainContent">
    <div class="csPR">
        <?php $this->load->view('loader_new', ['id' => 'company_locations']); ?>
        <!--  -->
        <div class="row">
            <div class="col-md-12 col-xs-12 text-right">
                <button class="btn btn-success csF16 csB7 jsLocationAdd"><i class="fa fa-eye csF16" aria-hidden="true"></i>&nbsp;Add A Location</button>
            </div>
        </div>
        <!-- -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th class="csBG1 csF16 csB7" scope="col">Id</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Country</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">State</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">City</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Zipcode</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Street 1</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Street 2</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Phone Number</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Last Modified</th>
                            <th class="csBG1 csF16 csB7 text-right" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="jsLocationBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
           

<script>
    window.API_URL = "<?=getAPIUrl('locations');?>"; 
</script>