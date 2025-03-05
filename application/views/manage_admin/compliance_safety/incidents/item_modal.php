<div class="row" data-key="">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <div class="">
            <label for="item_description">Item Description</label>
            <textarea name="item_description[]" id="textarea_edit" class="hr-form-fileds" rows="4" cols="50"><?= $record["description"]; ?></textarea>
            <input type="hidden" id="itemId" value="<?= $record["sid"]; ?>" />
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <div style="background-color: lightyellow; padding: 10px; margin-top: 10px;">
            <p>
                <strong>{{input}}</strong>
            </p>
            <p>
                <strong>{{checkbox}}</strong>
            </p>
        </div>
    </div>
</div>