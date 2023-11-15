<div class="container">
    <br />
    <!-- Banner  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <strong>
                    Add a Team member
                </strong>
            </h4>
        </div>
        <form id="jsAddTeamForm" action="javascript:void(0)">
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
                    <textarea id="detailsAddTeam" name="details" rows="10" class="form-control"></textarea>
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
                <button class="btn btn-success jsAddTeamBtn" type="submit">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Save
                </button>
            </div>
        </form>
    </div>
</div>