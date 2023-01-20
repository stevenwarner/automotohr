<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?><!-- profile_left_menu_company -->
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                            <?php $this->load->view('manage_employer/company_logo_name'); ?>

                            Government Agents</span>
                            <a href="<?= base_url('govt_user/view') ?>" class="dashboard-link-btn">View Agents</a>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <form id="js-form" action="javascript:void(0)" method="post">
                            <div class="form-group">
                                <label for="">Agency Name <span class="cs-required">*</span> </label>
                                <input type="text" class="form-control" id="js-agency-name" placeholder="Agency XYZ" />
                            </div>

                            <div class="form-group">
                                <label for="">Agent Name <span class="cs-required">*</span> </label>
                                <input type="text" class="form-control" id="js-agent-name" placeholder="John Doe" />
                            </div>

                            <div class="form-group">
                                <label for="">Username <span class="cs-required">*</span> </label>
                                <input type="text" class="form-control" id="js-username" placeholder="John Doe" />
                            </div>

                            <div class="form-group">
                                <label for="">Email  <span class="cs-required">*</span> </label>
                                <input type="text" class="form-control" id="js-email" placeholder="johndoe@example.com" />
                            </div>

                            <div class="form-group">
                                <label for="">Password  <span class="cs-required">*</span> </label>
                                <input type="password" class="form-control" id="js-password" placeholder="johndoe" />
                            </div>

                            <div class="form-group">
                                <label for="">Employees  <span class="cs-required">*</span> </label>
                                <div style="padding: 10px 0;">
                                    <label class="control control--radio">
                                        All &nbsp;&nbsp;
                                        <input class="select-domain js-employee-type" type="radio" checked="true" value="all"  name="employee-type" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        Active &nbsp;&nbsp;
                                        <input class="select-domain js-employee-type" type="radio" value="active" name="employee-type"  />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <label class="control control--radio">
                                        In-active &nbsp;&nbsp;
                                        <input class="select-domain js-employee-type" type="radio" value="inactive" name="employee-type"  />
                                        <div class="control__indicator"></div>
                                    </label>
                                </div>
                                <select class="form-control" multiple id="js-employees"></select>
                                <small><strong id="js-selected-employees">0</strong> employees selected </small>
                            </div>

                            <div class="form-group">
                                <label for="">Expiration Date  <span class="cs-required">*</span> </label>
                                <input type="text" readonly="true" class="form-control"  id="js-expiry-date" />
                            </div>

                            <div class="form-group">
                                <label>Image of Agents identification or Credentials</label> <br />
                                <button type="button" name="button" class="btn btn-success" id="js-upload-btn">Upload Image</button>
                                <input type="file" id="js-company-logo" style="display: none;"/>
                                <div class="js-image-box" style="max-width: 300px; margin-top: 10px;"></div>
                            </div>

                            <div class="form-group">
                                <label>Note</label>
                                <textarea class="form-control" id="js-note" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="control control--checkbox">
                                    Send Credential Email
                                    <input class="select-domain" type="checkbox" id="js-send-credential-email" />
                                    <div class="control__indicator"></div>
                                </label>
                            </div>

                            <hr />

                            <div class="form-group">
                                <input type="submit" value="Save" class="btn btn-success"/>
                                <a href="<?=base_url('govt_user/view')?>" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loader -->
<div id="my_loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" style="display:block; margin-top: 35px;">Please wait while we are generating a preview
        </div>
    </div>
</div>


<script>
    $(function (){
        var employeeSize = <?=sizeof($employees);?>,
        employees = <?=json_encode($employees);?>;
        //
        loader('hide');
        //
        $('#js-employees').select2({
            closeOnSelect : false,
            allowHtml: true,
            allowClear: true,
            tags: true
        });
        //
        $('#js-expiry-date').datepicker({
            minDate: 0
        });
        //
        $('#js-employees').on('select2:select', updateEmployeeCount);
        $('#js-employees').on('select2:unselect', updateEmployeeCount);
        //
        $('.js-employee-type').click(function(){
            var sv = $(this).val().toLowerCase();
            var s2v = $('#js-employees').val();
            $('#js-employees').select2('val', null);
            $('#js-employees').html('<option value="all">All</option>');
            employees.map(function(employee){
                if(sv == 'active' && employee.status != '1') return false;
                if(sv == 'inactive' && employee.status != '0') return false;
                $('#js-employees').append('<option value="'+employee['sid']+'">'+( remakeEmployeeName(employee) )+' ('+( employee['i9'] > 0 ? "I9 Completed" : "I9 Pending" )+')'+'</option>');
            });
            $('#js-employees').select2('val', s2v);
            $('#js-selected-employees').text(
                $('#js-employees').val() == 'all' ? $('#js-employees').find('option').length - 1 : ( $('#js-employees').val() == null ? 0 : $('#js-employees').val().length )
            );
        });

        //
        function remakeEmployeeName(
            o,
            d
        ){
            //
            var r = '';
            //
            if(d === undefined) r += o.first_name+' '+o.last_name;
            //
            r = r.ucwords();
            //
            if(o.job_title != '' && o.job_title != null) r += ' ('+( o.job_title )+')';
            //
            r += ' [';
            //
            if(typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
            //
            if(o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1)  r += o['access_level']+' Plus / Payroll';
            else if(o['access_level_plus'] == 1) r += o['access_level']+' Plus';
            else if(o['pay_plan_flag'] == 1) r += o['access_level']+' Payroll';
            else r += o['access_level'];
            //
            r += ']';
            //
            return r;
        }

        //
        String.prototype.ucwords = function() {
            str = this.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(s){ return s.toUpperCase(); });
        };

        $('.js-employee-type[value="all"]').trigger('click');
        function updateEmployeeCount(){
            //
            var currentList = $(this).val();
            //
            if(currentList == null || currentList.length == 0){
                $('#js-selected-employees').text(0);
                return;
            }
            // find in all exists
            if(currentList.indexOf('all') !== -1){
                $('#js-employees').select2('val', 'all');
                var sz = 0;
                var sv = $('.js-employee-type:checked').val().toLowerCase();
                employees.map(function(employee){
                    if(sv == 'active' && employee.status != '1') return false;
                    if(sv == 'inactive' && employee.status != '0') return false;
                    sz++;
                });
                $('#js-selected-employees').text(sz);
            }

            $('#js-selected-employees').text(
                $(this).val().length
            );
        }
        //
        $('#js-form').submit(function(e){
            e.preventDefault();
            var megaOBJ = {};
            megaOBJ.action = 'add';
            megaOBJ.agencyName = $('#js-agency-name').val().trim();
            megaOBJ.agentName = $('#js-agent-name').val().trim();
            megaOBJ.username = $('#js-username').val().trim();
            megaOBJ.password = $('#js-password').val().trim();
            megaOBJ.email = $('#js-email').val().trim();
            megaOBJ.expiryDate = $('#js-expiry-date').val().trim();
            megaOBJ.employees = $('#js-employees').val();
            megaOBJ.sendEmail = Number($('#js-send-credential-email').prop('checked'));
            megaOBJ.picture = $('#js-company-logo').val();
            megaOBJ.note = $('#js-note').val().trim();
            megaOBJ.employeeType = $('.js-employee-type:checked').val();
            // Validation
            if(megaOBJ.agencyName == ''){
                alertify.alert('ERROR!', 'Agency name is required.');
                return;
            }
            if(megaOBJ.agentName == ''){
                alertify.alert('ERROR!', 'Agent name is required.');
                return;
            }
            if(megaOBJ.username == ''){
                alertify.alert('ERROR!', 'Username is required.');
                return;
            }
            if(megaOBJ.username.length < 3){
                alertify.alert('ERROR!', 'Username should be greater than 3 characters.');
                return;
            }
            if(megaOBJ.email == ''){
                alertify.alert('ERROR!', 'Email is required.');
                return;
            }
            if(!validateEmail(megaOBJ.email)){
                alertify.alert('ERROR!', 'Email is invalid.');
                return;
            }
            if(megaOBJ.password == ''){
                alertify.alert('ERROR!', 'Password is required.');
                return;
            }
            if(megaOBJ.employees == null || megaOBJ.employees.length == 0){
                alertify.alert('ERROR!', 'Employees are required.');
                return;
            }
            if(megaOBJ.expiryDate == ''){
                alertify.alert('ERROR!', 'Expiration date is required.');
                return;
            }
            // if(megaOBJ.picture == ''){
            //     alertify.alert('ERROR!', 'Agent identification image is required.');
            //     return;
            // }
            //
            megaOBJ.employees = megaOBJ.employees.join(',');
            // Image
            var formData = new FormData();
            formData.append('file', $('#js-company-logo')[0].files[0]);

            $.each(megaOBJ, function(i, v){
                formData.append(i, v);
            });
            loader('show');

            $.ajax({
                url: "<?=base_url('govt_user/handler');?>",
                data: formData,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp){
                    //
                    if(resp.Status === false && resp.Expire !== undefined){
                        alertify.confirm('ERROR!', resp.Response,
                        function(){
                            formData.set('action', 'add_expire');
                            addExpire(formData);
                        }, function(){
                            loader('hide');
                        }).set('labels', { ok: "Yes", cancel: "No"});
                        return;
                    } else if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        loader('hide');
                        return;
                    }
                    alertify.alert('SUCCESS!', resp.Response, function(){
                        window.location.href = "<?=base_url('govt_user/view');?>";
                    });
                }
            });

            console.log(megaOBJ);
        });
        //
        $("#js-company-logo").change(function() { readURL(this); });
        //
        $('#js-upload-btn').click(function(){$("#js-company-logo").click(); });
        //
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if(!e.target.result.match(/image/g)) {
                        alertify.alert('ERROR!', 'You can only upload images of type JPG,JPEG,PNG.');
                        return;
                    }
                    $('.js-image-box').html('<img src="'+( e.target.result )+'" width="100%" />');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        //
        function addExpire(formData){
            $.ajax({
                url: "<?=base_url('govt_user/handler');?>",
                data: formData,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp){
                    //
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        loader('hide');
                        return;
                    }
                    alertify.alert('SUCCESS!', resp.Response, function(){
                        window.location.href = "<?=base_url('govt_user/view');?>";
                    });
                }
            });
        }
        //
        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
        //
        function loader(do_show){
            if(do_show === undefined || do_show == 'show') $('#my_loader').fadeIn();
            else $('#my_loader').fadeOut();
        }
    })
</script>

<style>
    .cs-required{ color: #cc0000; font-weight: bolder; }
    .form-control{
        /* max-width: 300px;  */
    }
</style>


<style>
.select2-container {
  min-width: 400px;
}

.select2-results__option {
  padding-right: 20px;
  vertical-align: middle;
}
.select2-results__option:before {
  content: "";
  display: inline-block;
  position: relative;
  height: 20px;
  width: 20px;
  border: 2px solid #e9e9e9;
  border-radius: 4px;
  background-color: #fff;
  margin-right: 20px;
  vertical-align: middle;
}
.select2-results__option[aria-selected=true]:before {
  font-family:fontAwesome;
  content: "\f00c";
  color: #fff;
  background-color: #81b431;
  border: 0;
  display: inline-block;
  padding-left: 3px;
}
.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #fff;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #eaeaeb;
    color: #272727;
}
.select2-container--default .select2-selection--multiple {
    margin-bottom: 10px;
}
.select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
    border-radius: 4px;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #81b431;
    border-width: 2px;
}
.select2-container--default .select2-selection--multiple {
    border-width: 2px;
}
.select2-container--open .select2-dropdown--below {

    border-radius: 6px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);

}
.select2-selection .select2-selection--multiple:after {
    content: 'hhghgh';
}
/* select with icons badges single*/
.select-icon .select2-selection__placeholder .badge {
    display: none;
}
.select-icon .placeholder {
    display: none;
}
.select-icon .select2-results__option:before,
.select-icon .select2-results__option[aria-selected=true]:before {
    display: none !important;
    /* content: "" !important; */
}
.select-icon  .select2-search--dropdown {
    display: none;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
    height: 25px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered{
    height: 30px;
}
</style>
