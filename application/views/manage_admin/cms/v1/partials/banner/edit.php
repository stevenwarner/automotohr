<div class="container">
    <br />
    <!-- Banner  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <strong>
                    Edit a banner
                </strong>
            </h4>
        </div>
        <form id="jsUpdateBannerForm">
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
                    <input type="text" class="form-control" id="heading" name="heading" value="<?= $pageContent["heading"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Details
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <textarea id="details" name="details" rows="5" class="form-control"><?= $pageContent["headingDetail"]; ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        Button text
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="buttonText" name="button_text" value="<?= $pageContent["btnText"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Button link
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="buttonLink" name="button_link" value="<?= $pageContent["btnSlug"]; ?>" />
                </div>

                <div class="form-group">
                    <label>
                        Banner Image
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="file" class="hidden" id="bannerEditImage" accept="image/*" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <input type="hidden" id="jsId" value="<?= $index; ?>" name="id" />
                <button class="btn btn-success jsUpdateBanner" type="submit">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>