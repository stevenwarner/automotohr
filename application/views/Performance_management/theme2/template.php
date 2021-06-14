<!--  -->
<!-- Choose a template -->
<div class="row">
    <div class="col-md-12">
        <br>
        <p class="csF16 csB7">Craft a new review from the ground up or pick a template with insightful questions.</p>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-12">
        <br>
        <br>
        <br>
        <button class="btn btn-orange csF16 text-center form-control"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;I want to create a review from scratch</button>
        <br>
        <br>
        <p class="text-center csF16 csB7">------------------- OR -------------------</p>
        <br>
    </div>
</div>
<!-- csPageBoxBody -->
<div class="csPageBoxBody ">
    <?php $this->load->view($pp.'partials/company_templates');?>
</div>
<!-- csPageBoxBody -->
<div class="csPageBoxBody ">
    <?php $this->load->view($pp.'partials/personal_templates');?>
</div>