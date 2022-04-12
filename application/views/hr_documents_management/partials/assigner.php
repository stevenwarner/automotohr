<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>
                    <strong>Select employees to approve the document before assign.</strong>
                </h5>
            </div>
            <div class="panel-body">
                <a href="javascript:;" id="jsAddDocumentAssigner" class="btn btn-success"><i class="fa fa-plus-circle" aria-hidden="true"></i>Add Employee To Flow</a>
                <!-- Note -->
                <div class="jsEmployeesadditionalBox"></div>
                <div class="row jsAssignerEmployeesNote csMT" style="display: none;">
                    <div class="col-xs-12">
                        <label for="footer_content">Employee(s) Note</label>
                        <textarea class="form-control" id="assigner_note" name="assigner_note" rows="8" cols="60"></textarea>
                    </div>                                            
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #jsAddDocumentAssigner{
        float:  right;
    }

    .csMT{
        margin-top: 8px;
    }
</style>