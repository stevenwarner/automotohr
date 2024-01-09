<br />
<div class="container">
    <!-- Section  -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                <strong>
                    Add a card section
                </strong>
            </h4>
        </div>
        <form action="javascript:void(0)" id="jsAddCardForm">
            <div class="panel-body">
                <div class=" form-group">
                    <label>
                        Title
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" data-target="buttonLink" class="form-control" name="title" value="" />
                </div>

                <div class=" form-group">
                    <label>
                        Description
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" class="form-control" name="details"></textarea>
                </div>

                <div class=" form-group">
                    <label>
                        Button text
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonText" value="" />
                </div>

                <div class=" form-group">
                    <label>
                        Button link
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="buttonLink" value="" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsAddCardBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>