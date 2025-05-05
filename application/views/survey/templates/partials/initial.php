<div class="container">

    <div class="row">
        <div class="col-sm-6 text-center">

            <div class="option-card jsSurveyType" data-key="new">
                <img src="<?= base_url("assets/survey/images/asset-creation-start-new.svg") ?>" alt="Start from Scratch"
                    class="img-fluid">
                <h6 class="text-medium">Start from scratch</h6>
            </div>
        </div>
        <div class="col-sm-6 text-center">
            <div class="option-card jsSurveyType" data-key="template">
                <img src="<?= base_url("assets/survey/images/asset-creation-template.svg") ?>" alt="Start from Scratch"
                    class="img-fluid">
                <h6>Use a template</h6>
            </div>
        </div>
    </div>
</div>

<style>
    .option-card {
        border: 1px solid #ddd;
        border-radius: 18px;
        cursor: pointer;
        /* width: 200px; */
        height: 200px;
        transition: background-color .3s, border-color .3s;
        display: block;
        /* flex-direction: column; */
        align-items: center;
        padding-top: 16px;
    }

    .option-card:hover {
        background-color: #f9f9f9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .option-card img {
        max-width: 184px;
        max-height: 132px;
        min-height: 132px;
        margin-bottom: 10px;
    }

    .option-card h6 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
</style>