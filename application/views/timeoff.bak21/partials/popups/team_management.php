<style>
#team_management_section{
  position: relative;
}

.ajax-loader-container {
    top: 0;
    position: absolute;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999999999999999999;
    background: #f8f8f8;
    opacity: .8;
}

.ajax-loader-spiner {
    position: absolute;
    left: 50%;
    top: 50%;
    font-size: 2.5em;
}

.select_employee_section{
	color: #3c763d;
	font-weight: 700;
}
</style>

<!-- Modal -->
<div id="team_management_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">`zfr5z9
        <div class="modal-content">
        	<div class="ajax-loader-container" id="save_changes_loader" style="display: none;">
                <i class="fa fa-spinner fa-spin ajax-loader-spiner"></i>
            </div>
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Team Management</h4>
            </div>
            <div class="modal-body">
            	

                <div class="row">
            		<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
            			<label>Select a Team</label>
                        <div class="">
                            <select class="invoice-fields" id="js-departments"></select>
                        </div>
            		</div>
            	</div>

            	<div class="row" style="display: none; margin-top: 8px;" id="team_management_section">
            		<div class="ajax-loader-container" id="team_management_section_loader" style="display: none;">
	                    <i class="fa fa-spinner fa-spin ajax-loader-spiner"></i>
	                </div>
            		<div class="col-sm-12">
            			<div class="panel panel-success" style="position: relative;">
                            <div class="panel-heading">
                                <b>Department Supervisors :</b>
                            </div>
                            <div class="panel-body" id="all_supervisor">
                               
                            </div>
                        </div> 
                        <div class="panel panel-success" style="position: relative;">
                            <div class="panel-heading">
                                <b>Team Leads :</b>
                            </div>
                            <div class="panel-body" id="all_teamleads">
                               
                            </div>
                        </div>
        				<div class="panel panel-success" style="position: relative;">
                            <div class="panel-heading">
                                <label class="control control--checkbox pull-left">
                                    <input type="checkbox" id="selectall">
                                    <div class="control__indicator"></div>
                                </label>
                                <h4 class="hr-registered pull-left select_employee_section">
                                    Select Employees
                                </h4>
                                <div class="text-right select_employee_section">(<span id="count_employees"></span>) Assigned</div>
                            </div>
                            <div class="panel-body" id="all_company_employees">
                               
                            </div>
                        </div>  
            			<!-- <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
            				<div id="company_all_active_employees">
                        	</div>
            			</div>
            			<div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">

            			</div>
            			<div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
            				<div id="selected_team_employees">

                        	</div>
            			</div> -->
            		</div>
            	</div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="save_department_team_changes" style="display: none;">Save</button>
                <button type="button" class="btn btn-cancel" style="color: #fff !important;" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	
    //
	$(function(){
		//
		var departments = {},
		company_employees = {},
		teams = {},
		teamEmployees = {},
		activeTeamEmployees = [],
		team_department_sid,
		suprevisors = {},
		team_leads = {},
		request_url = '<?php echo base_url("department_management/ajax_responder"); ?>';

		$(".manage_my_team").on('click', function(){
			$('#save_changes_loader').show();
	        create_department_team_drop_down();
			$('#team_management_modal .modal-dialog').css('width', '75%');
	        $('#team_management_modal').modal('show');
	    });

	    $('#team_management_modal').on('hidden.bs.modal', function () {
			$('#all_company_employees').html('');
            $('#save_department_team_changes').hide();
			$('#team_management_section').hide();
			$('body').css('overflow-y', 'auto');
		});

	    $('#selectall').click(function (event) { 
	        if (this.checked) { 
	            $('.active_employee_checkbox').each(function () { 
	                this.checked = true;  
	                $('#count_employees').text(company_employees.length);
	            });
	        } else {
	            $('.active_employee_checkbox').each(function () { 
	                this.checked = false;
	                $('#count_employees').text(0);
	            });
	        }
	    });

	    async function create_department_team_drop_down () {
	    	
			let dpromise;
			let tpromise
			 
	    	if ($.isEmptyObject(departments)) {
	    		dpromise = new Promise((resolve, reject) => {
		    		var departmentsRequest;
			        var departmentData = { 'perform_action' : 'get_all_departments' };

			        departmentsRequest = $.ajax({
			           	url : request_url,
			            type: 'POST',
			            data: departmentData,
			            dataType: 'json'
			        });

			        departmentsRequest.done(function (response) {
		        		departments = response;
		        		resolve();
		        	});
	        	});	
	    	}

	    	await dpromise;

	    	if ($.isEmptyObject(teams)) {
	    		tpromise = new Promise((resolve, reject) => {
		    		var teamsRequest;
			        var teamData = { 'perform_action' : 'get_all_teams' };

			        teamsRequest = $.ajax({
			           	url : request_url,
			            type: 'POST',
			            data: teamData,
			            dataType: 'json'
			        });

			        teamsRequest.done(function (response) {
		        		teams = response;
		        		resolve();
		        	});
		        });	
	    	}

	    	await tpromise;

	    	var dd_html = new Promise((resolve, reject) => {
	    		generate_teams_drop_down()
	    		resolve()
	    	})	

	    	await dd_html;
	    	$('#save_changes_loader').hide();
	    	
	    }

	    function generate_teams_drop_down () {
	    	var rows = '';
            rows += '<option value="select_team">[Select Team]</option>';

	    	if(departments.length != 0 && teams.length != 0) {
                departments.map(function(d){
                    rows += '<optgroup label="'+( d.name )+'">';
                    //
                    if(teams.length != 0){
                        teams.map(function(t){
                            if (t.department_sid == d.department_sid) {
                                rows += '<option value="'+( t.team_sid )+','+( t.department_sid )+'">'+( t.name )+'</option>';
                            }
                        });
                    }
                    //
                    rows += '</optgroup>';
                });
            }

            $('#js-departments').html(rows);
	        $('#js-departments').select2({maximumSelectionSize: 1 });
	    }

	    function update_team_drop_down (){
	    	$('#save_changes_loader').show();
	    	$('#all_company_employees').html('');
            $('#save_department_team_changes').hide();
            $('#team_management_section').hide();
            create_department_team_drop_down();
	    };

	    $(document).on("change","#js-departments",async function(){
	    	$('#team_management_section').show();
	    	$('#team_management_section_loader').show();
	    	team_department_sid = $('#js-departments').val();
	    	if (team_department_sid == 'select_team') {
	    		update_team_drop_down();
	    	} else {
		    	teamEmployees = {};
		    	activeTeamEmployees = [];
		    	let cepromise;
		    	//
		    	if ($.isEmptyObject(company_employees)) {
			    	cepromise = new Promise((resolve, reject) => {
			    		var employeesRequest;
				        var employeeData = { 'perform_action' : 'get_all_active_company_employees'};

				        employeesRequest = $.ajax({
				           	url : request_url,
				            type: 'POST',
				            data: employeeData,
				            dataType: 'json'
				        });

				        employeesRequest.done(function (response) {
			        		company_employees = response;
			        		resolve();
			        	});
			        });
			    }

		        await cepromise;

		    	let tepromise = new Promise((resolve, reject) => {
		    		var teamEmployeesRequest;
			        var teamEmployeesData = { 'perform_action' : 'get_all_team_employees',  'team_department_sid' : team_department_sid};

			        teamEmployeesRequest = $.ajax({
			           	url : request_url,
			            type: 'POST',
			            data: teamEmployeesData,
			            dataType: 'json'
			        });

			        teamEmployeesRequest.done(function (response) {
		        		teamEmployees = response.team_employees;
		        		suprevisors = response.supervisor_names;
		        		team_leads = response.teamlead_names;
		        		resolve();
		        	});
		        });

		        await tepromise;
		        
		        var supervisor_list = '';
		        //
		        suprevisors.map(function(sup){
		        	supervisor_list += '<div class="col-xs-6">';
		            supervisor_list += '<label>';
		            supervisor_list += sup.name;  
		            supervisor_list += '</label>';
		            supervisor_list += '</div>';
		        });
		        //
		        $('#all_supervisor').html(supervisor_list);

		        var teamlead_list = '';
		        //
		        team_leads.map(function(tl){
		        	teamlead_list += '<div class="col-xs-6">';
		            teamlead_list += '<label>';
		            teamlead_list += tl.name;  
		            teamlead_list += '</label>';
		            teamlead_list += '</div>';
		        });
		        //
		        $('#all_teamleads').html(teamlead_list);

		        // var unassign_employee = '';
		        // var assign_employee = '';
		        var row = '';
		        company_employees.map(function(ce){
		         // if(jQuery.inArray(ce.employee_sid, teamEmployees) == -1) {
		         //        unassign_employee += '<div class="col-xs-12">';
		         //        unassign_employee += '<label class="control control--checkbox font-normal">';
		         //        unassign_employee += ce.employee_name;
		         //        unassign_employee += '<input class="employee_checkbox" name="employees[]" value="'+( ce.employee_sid )+'" type="checkbox">';
		         //        unassign_employee += '<div class="control__indicator"></div>';  
		         //        unassign_employee += '</label>';
		         //        unassign_employee += '</div>';
		         //    } else {
		         //    	assign_employee += '<div class="col-xs-12">';
		         //        assign_employee += '<label class="control control--checkbox font-normal">';
		         //        assign_employee += ce.employee_name;
		         //        assign_employee += '<input class="employee_checkbox" name="employees[]" value="'+( ce.employee_sid )+'" type="checkbox">';
		         //        assign_employee += '<div class="control__indicator"></div>';  
		         //        assign_employee += '</label>';
		         //        assign_employee += '</div>';
		         //    }
		         	var checked = ''; 
		         	if(jQuery.inArray(ce.employee_sid, teamEmployees) != -1) {
		         		checked = 'checked="checked"'; 
		         		activeTeamEmployees.push(ce.employee_sid);
		         	}

		         	row += '<div class="col-xs-6">';
		            row += '<label class="control control--checkbox font-normal">';
		            row += ce.employee_name;
		            row += '<input class="active_employee_checkbox" name="employees[]" value="'+( ce.employee_sid )+'" type="checkbox" '+( checked )+'>';
		            row += '<div class="control__indicator"></div>';  
		            row += '</label>';
		            row += '</div>';
	            });
	            // $('#company_all_active_employees').html(unassign_employee);
	            // $('#selected_team_employees').html(assign_employee);
	            $('#all_company_employees').html(row);
	            $('#team_management_section_loader').hide();
	            $('#save_department_team_changes').show();
	            $('#count_employees').text(activeTeamEmployees.length);
	        }    
		});  

		$('#save_department_team_changes').on('click', function(){
			$('#save_changes_loader').show();
			$('#save_department_team_changes').hide();

			var selected_objs = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'));
			var selected_ids = selected_objs.map(function(selected_obj) {return selected_obj.defaultValue});
			
			result = filter_selected_employee(selected_ids);

			if (result.add.length == 0 && result.remove.length == 0) {
				alertify.alert('NOTCIES','Please select or deselect employee to save changes.');
				$('#save_changes_loader').hide();
				$('#save_department_team_changes').show();
			} else {
				
				var form_data = new FormData();

	            form_data.append('perform_action', 'save_changes');
	            if (result.add.length > 0){
	            	form_data.append('add_employee', result.add);
	            }

	            if (result.remove.length > 0){
	            	form_data.append('remove_employee', result.remove);
	            }

	            form_data.append('team_department_sid', team_department_sid);

	            $.ajax({
	                url: request_url,
	                type: 'post',
	                data: form_data,
	                processData: false,
    				contentType: false,
	                success: function(response){
	                    if (response != "error") {
	                        $('#team_management_modal').modal('hide');
	                        alertify.alert('SUCCESS','You have successfully updated the team members.', () => {
								$('body').removeClass('ajs-no-overflow');
							});
	                    } else {
							alertify.alert('WARNING','We are unable to save the changes at this point. Please, try again in a few moments.', () => {
								$('body').removeClass('ajs-no-overflow');
							});
							
	                    }
	                },
	                error: function(){
	                	alertify.alert('WARNING','We are unable to process your request at this point. Please, try again in a few moments.');
	                }
	            });
			}
		})

		function filter_selected_employee (selected_ids) {
			var add_employee = [],
				remove_employee = [],
				result = {};

			selected_ids.map(function(si){
				if(jQuery.inArray(si, teamEmployees) == -1) {
		        	add_employee.push(si) ;
		        } 
	        });
	       

	        activeTeamEmployees.map(function(pr){
				if(jQuery.inArray(pr, selected_ids) == -1) {
		        	remove_employee.push(pr);
		        } 
	        });

	       	result = {add: add_employee, remove:remove_employee};
	       	return result;
		}

		$(document).on('click', '.active_employee_checkbox', function() {
            $('#count_employees').text($('.active_employee_checkbox:checked').length);
        });
	});	
</script>