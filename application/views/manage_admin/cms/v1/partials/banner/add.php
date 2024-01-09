<div class="container">
    <br />
    <!-- Banner  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <strong>
                    Add a banner
                </strong>
            </h4>
        </div>
        <form id="jsAddBannerForm">
            <div class="panel-body">
                <p class="text-danger">
                    <strong>
                        <em>
                            To make word prominent use "##" before and after the word.
                        </em>
                    </strong>
                    <hr />
                </p>
                <div class="form-group">
                    <label>
                        Heading
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="heading" name="heading" />
                </div>

                <div class="form-group">
                    <label>
                        Details
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <textarea id="details" name="details" rows="5" class="form-control"></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Button text
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="buttonText" name="button_text" />
                </div>
                <div class="form-group">
                    <label>
                        Button link
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="buttonLink" name="button_link" />
                </div>

                <div class="form-group">
                    <label>
                        Banner Image
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="file" class="hidden" id="bannerImage" accept="image/*" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsSaveBanner" type="submit">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save
                </button>
            </div>
        </form>
    </div>
</div>