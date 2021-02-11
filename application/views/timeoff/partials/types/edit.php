<!-- Edit Page--->
<div class="js-page p10" id="js-page-edit" <?=$page != 'add' ? 'style="display: none;"' : '';?>>
    <div class="row mg-lr-0">
        <div class="border-top-section border-bottom csHeader">
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-left pl0">
                    <p>Types</p>
                </div>
            </div>
            <div class="col-xs-6 col-lg-6">
                <div class="pto-top-heading-right text-right pr0">
                    <a href="javascript:void(0)" class="dashboard-link-btn2 cs-btn-add js-view-type-btn">
                        <span><i class="fa fa-arrow-circle-left"></i></span>&nbsp; VIEW TYPES
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="p10">
        <br />
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <label>Type <span class="cs-required">*</span></label>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="text" value="" class="form-control" id="js-type-edit" placeholder="Vacation" />
                    </div>
                </div>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <label>Policies</label>
                <select id="js-policies-edit" multiple="true"></select>
            </div>
        </div>
        <br />

        <div class="row">
            <div class="col-md-6 col-xs-12">
                <label class="control control--checkbox">
                    <input type="checkbox" class="checkbox-sizing" id="js-archived-edit" />
                    Deactivate
                    <span class="control__indicator"></span>
                </label>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="">
                    <hr />
                    <button type="button" class="btn btn-black js-view-type-btn">Cancel</button>
                    <button id="js-save-edit-btn" type="button" class="btn btn-success">Edit</button>
                </div>
            </div>
        </div>
    </div>
</div>