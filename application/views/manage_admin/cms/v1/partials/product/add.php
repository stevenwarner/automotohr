<div class="container">
    <br />
    <!-- Banner  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <strong>
                    Add a product section
                </strong>
            </h4>
        </div>
        <form id="jsAdd">
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
                        Main Heading
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" id="mainHeading" name="mainHeading" />
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
                        Direction
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <select name="direction" id="direction" class="form-control">
                        <option value="left_to_right">Left To Right</option>
                        <option value="right_to_left">Right To Left</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Source
                        &nbsp;<strong class="text-danger">*</strong>
                    </label>
                    <input type="file" class="hidden" id="sourceFile" accept="image/*, video/mp4, video/mov" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsSaveProductBtn" type="submit">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save
                </button>
            </div>
        </form>
    </div>
</div>