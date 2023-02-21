<!-- Edit Page--->
<div class="js-page" id="js-page-edit" <?=$page != 'edit' ? 'style="display: none;"' : '';?>>
    <div class="csPageHeader">
        <div class="row">
            <div class="col-sm-12">
                <h4> Edit type
                    <span class="pull-right">
                        <a href="javascript:void(0)" class="btn btn-orange js-view-type-btn">
                            <span><i class="fa fa-arrow-circle-left"></i></span>&nbsp; VIEW TYPES
                        </a>
                    </span>
                </h4>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="csPageBody">
        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <label>Title <span class="cs-required">*</span></label>
                    <input type="text" value="" class="form-control" id="js-type-edit" placeholder="Vacation" />
                </div>
            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                <hr />
                    <label>Type</label>
                    <select class="invoice-fields" name="type_new" id="js-type-edit-new" >
                    <option value="1">Paid</option>
                    <option value="0">Unpaid</option>
                 </select>                
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <hr />
                    <label>Policies</label>
                    <select id="js-policies-edit" multiple="true"></select>
                </div>
            </div>
        </div>

        <!--  -->
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <hr />
                    <label class="control control--checkbox">
                        <input type="checkbox" class="checkbox-sizing" id="js-archived-edit" />
                        Deactivate
                        <span class="control__indicator"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <div class="csPageFooter">
        <br />
        <div class="form-group">
            <div class="row">
                <div class="col-sm-6">
                    <!--  -->
                    <button type="button" class="btn btn-black btn-theme js-view-type-btn">Cancel</button>
                    <button id="js-save-edit-btn" type="button" class="btn btn-orange">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>