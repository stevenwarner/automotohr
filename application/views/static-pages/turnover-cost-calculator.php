<div class="main-content">
    <div class="row">					
        <div class="col-md-12">
            <div class="turnover-cost-calculator">
                <div class="dual-color-heading">
                    <h2>Employee <span>Turnover Calculator</span></h2>
                </div>
            	<div class="universal-form-style-v2">
                    <ul>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Dealership Name</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input data-rule-required="true" value="<?php echo STORE_NAME; ?>" id="dealership_name" name="dealership_name" type="text" class="invoice-fields" />
                                </div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">First Name</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input data-rule-required="true" value="" id="first_name" name="first_name" type="text" class="invoice-fields" />
                                </div>
                            </div>
                        </li>

                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Last Name</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input data-rule-required="true" value="" id="last_name" name="last_name" type="text" class="invoice-fields" />
                                </div>
                            </div>
                        </li>

                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Email</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input data-rule-email=”true” value="" id="email" name="email" type="email" class="invoice-fields" />
                                </div>
                            </div>
                        </li>
                    </ul>
                    <hr class="form-col-100 autoheight"  />
                	<ul>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Number of Employees</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input value="50" id="number_of_employees" name="number_of_employees" min="0" type="number" class="invoice-fields" />
                                </div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Number of Sales Rep.</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input value="10" id="number_of_sales_rep" name="number_of_sales_rep" min="0" type="number" class="invoice-fields" />
                                </div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label>Employees Annual Turnover %</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div id="emp_annual_turnover" class="range-slider"></div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label>Sales Rep. Annual Turnover %</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div id="sales_rep_annual_turnover" class="range-slider"></div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Employee Average Annual Salary $</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input value="80000" id="emp_average_annual_salary" name="emp_average_annual_salary" type="number" min="0" class="invoice-fields" />
                                </div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Sales Rep. Average Annual Salary $</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input value="90000" id="sales_rep_average_annual_salary" name="sales_rep_average_annual_salary" type="number" min="0" class="invoice-fields" />
                                </div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label>Additional Costs Percentage %</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div id="additional_costs_percentage" class="range-slider"></div>
                            </div>
                        </li>
                        <hr class="form-col-100 autoheight" />
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Calculated Employee Turnover Cost $</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input id="calculated_employee_turnover_cost" name="calculated_employee_turnover_cost" type="number" min="0" class="invoice-fields" readonly/>
                                </div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">Calculated Sales Rep. Turnover Cost $</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group">
                                    <input id="calculated_sales_rep_turnover_cost" name="calculated_sales_rep_turnover_cost" type="number" min="0" class="invoice-fields" readonly/>
                                </div>
                            </div>
                        </li>
                        <li class="form-col-100 autoheight">
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                <label class="valign-middle">&nbsp;</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                <div class="form-group search-job-btn">
                                    <button type="button" onclick="calculate_turnover_cost();" class="form-btn btn-block">Calculate</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function calculate_turnover_cost(){
        var number_of_employees = $('#number_of_employees').val();
        var number_of_sales_rep = $('#number_of_sales_rep').val();
        var emp_annual_turnover = $('#emp_annual_turnover').slider('value');
        var sales_rep_annual_turnover = $('#sales_rep_annual_turnover').slider('value');
        var emp_average_annual_salary = $('#emp_average_annual_salary').val();
        var sales_rep_average_annual_salary = $('#sales_rep_average_annual_salary').val();
        var additional_costs_percentage = $('#additional_costs_percentage').slider('value');

        var employees_left = ( number_of_employees * emp_annual_turnover ) / 100;
        var employees_left_salary = employees_left * emp_average_annual_salary;

        var sales_rep_left = ( number_of_sales_rep * sales_rep_annual_turnover ) / 100;
        var sales_rep_left_salary = sales_rep_left * sales_rep_average_annual_salary;

        var employees_turnover_cost = Math.round(employees_left_salary * ( additional_costs_percentage / 100 ));
        var sales_rep_turnover_cost = Math.round(sales_rep_left_salary * ( additional_costs_percentage / 100 ));

        $('#calculated_employee_turnover_cost').val(employees_turnover_cost);
        $('#calculated_sales_rep_turnover_cost').val(sales_rep_turnover_cost);

    }


    $(document).ready(function(){
    	$( ".range-slider" ).slider({
            min: 0,
            max: 100,
           animate:"slow",
           orientation: "horizontal",
            slide: function( event, ui ) {
	            $( "#amount" ).val( ui.value );
	            $(this).find('.ui-slider-handle').text(ui.value);
	        },
	        create: function(event, ui) {
	            var v = $(this).slider('value');
	            $(this).find('.ui-slider-handle').text(v);
	        }
	        
        });


        /*$('#emp_annual_turnover').slider('value', 20);
        $('#sales_rep_annual_turnover').slider('value', 10);
        $('#additional_costs_percentage').slider('value', 35);*/

        $( ".range-slider").trigger('change');
    });


    
</script>