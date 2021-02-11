<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <div class="dash-box turnover-box">
                    <div class="admin-info">
                        <h2>Auto Dealers: Have you ever wondered how much employee turnover costs your dealership every year?</h2>
                        <p>Use this easy to use calculator to Calculate Your Employee Turnover Costs. The numbers will shock you, but small changes in your process can return huge savings to your bottom line $$</p>
                    </div>
                </div>
                <div class="dash-box turnover-box">
                    <div class="admin-info">
                        <h2>What could your business do with an extra: <br> <strong>$1.6 million</strong></h2>
                        <p>That's what an average dealer with 64 employees will spend each year in personnel replacement costs for employee turnover.</p>
                        <p>The average dealership can expect to lose approx 26 people a year. How many additional vehicles will you need to sell to cover the $1.6 million annual expense?</p>
                        <p>By instituting a hiring and on-boarding process, and a platform that will help you to hire right the first time, with just a 10% total reduction in employee turn over, you could add back an additional net of $500,000 to your dealerships bottom line. </p>
                        <h2>How does your dealership compare?<br> How can you drastically increase your R.O.I just by improving a process?</h2>
                        <p>Try our Employee Turnover Cost Calculator and get a customized view into the cost of turnover at your dealership, and learn the steps that you can take to mitigate these costs and drastically improve your workplace.</p>
                    </div>
                </div>
                <div class="dash-box turnover-box">
                    <div class="admin-info">
                        <p>Car dealership employee turnover was virtually unchanged at 39.6 percent, compared to 46 percent total turnover in the private sector, as estimated by the U.S. Bureau of Labor Statistics.</p>
                        <p>Car sales consultant, the only key position to exceed the national private-sector average, was the highest turnover position at 67 percent annually, a decrease of five points from 2014. </p>
                        <p>Non-luxury sales consultant turnover was 72 percent, luxury was 48 percent. </p>
                        <p><strong class="font-15">Female sales consultant turnover was 88 percent.</strong></p>
                        <p>Truck sales consultant turnover was 13 percent</p>
                        <p>Millennials were 60 percent of all dealership new hires and 42 percent of the total dealership workforce; turnover among Millennials was 52 percent.</p>
                        <p>Women were 18.6 percent of dealership employees and 20 percent of all new hires; 7.8 percent of women were employed in key positions, 89.3 percent in office and admin support.</p>
                        <p>Service advisor turnover dropped two points to 39%</p>
                        <p>As more and more dealerships add flexibility to work schedules and move away from 100% commission pay plans to attract and retain Millennials, non-luxury brand dealerships reduced sales consultant turnover by eight points</p>
                    </div>
                </div>
            </div>					
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="dash-box">
                    <div class="dual-color-heading">
                        <h2>Employee <span>Turnover Calculator</span></h2>
                    </div>
                    <hr class="form-col-100 autoheight"  />
                    <form id="turnover_calculator_form" action="<?php current_url(); ?>" method="post" enctype="multipart/form-data">
                    	<div class="universal-form-style-v2">
                            <ul>
                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label class="valign-middle">Dealership Name</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <input data-rule-required="true" value="<?php echo set_value('dealership_name', STORE_NAME); ?>" id="dealership_name" name="dealership_name" type="text" class="invoice-fields" />
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label class="valign-middle">First Name</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <input data-rule-required="true" value="<?php echo set_value('first_name'); ?>" id="first_name" name="first_name" type="text" class="invoice-fields" />
                                        </div>
                                    </div>
                                </li>

                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label class="valign-middle">Last Name</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <input data-rule-required="true" value="<?php echo set_value('last_name'); ?>" id="last_name" name="last_name" type="text" class="invoice-fields" />
                                        </div>
                                    </div>
                                </li>

                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label class="valign-middle">Email</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <input data-rule-email=”true” data-rule-required="true"  value="<?php echo set_value('email'); ?>" id="email" name="email" type="email" class="invoice-fields" />
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
                                            <input data-rule-required="true" value="<?php echo set_value('number_of_employees', 64); ?>" id="number_of_employees" name="number_of_employees" min="0" type="number" class="invoice-fields" />
                                            <p class="help-block">National Average is 64</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label class="valign-middle">Number of Sales Rep.</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <input data-rule-required="true"  value="<?php echo set_value('number_of_sales_rep', 13); ?>" id="number_of_sales_rep" name="number_of_sales_rep" min="0" type="number" class="invoice-fields" />
                                            <p class="help-block">National Average is 13</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label>Employees Annual Turnover %</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <input data-rule-required="true"  data-rangeslider type="range" id="emp_annual_turnover" name="emp_annual_turnover" value="40" min="0" max="100"/>
                                        <p class="help-block">National Average is 40%</p>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label>Sales Rep. Annual Turnover %</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <input data-rule-required="true"  data-rangeslider type="range" id="sales_rep_annual_turnover" name="sales_rep_annual_turnover" value="67" min="0" max="100"/>
                                        <p class="help-block">National Average is 67%</p>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label class="valign-middle">Employee Average Annual Salary $</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input data-rule-required="true"  value="69718" id="emp_average_annual_salary" name="emp_average_annual_salary" type="number" min="0" class="invoice-fields" />
                                            </div>
                                            <p class="help-block">National Average is $ 69,718.00</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label class="valign-middle">Sales Rep. Average Annual Salary $</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input data-rule-required="true"  value="69718" id="sales_rep_average_annual_salary" name="sales_rep_average_annual_salary" type="number" min="0" class="invoice-fields" />
                                            </div>
                                        </div>
                                        <p class="help-block">National Average is $ 69,718.00</p>
                                    </div>
                                </li>
                                <!--<li class="form-col-100 autoheight">
                                    <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                        <label>Additional Costs Percentage %</label>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                        <input data-rule-required="true"  data-rangeslider type="range" id="additional_costs_percentage" name="additional_costs_percentage" value="10" min="0" max="100"/>
                                        <p class="help-block">National Average is 10%</p>
                                    </div>
                                </li>-->
                                <input type="hidden" id="additional_costs_percentage" name="additional_costs_percentage" value="10" />
                                <hr class="form-col-100 autoheight" />

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
                    </form>
                    <div class="clear"></div>
                </div>
                <div class="dash-box turnover-box">
                    <div class="admin-info">
                        <h2 style="text-align: left;">Where do we start?</h2>
                        <p>Develop a systematic approach to onboarding, communication and training to cut these rising turnover costs.<br>Like anything worth doing, you must create a process and a plan and stick with it.</p>
                        <p>Be transparent and give candidates as much information as possible, so that they can make an informed employment decision. Be very clear and up front regarding your company culture and expectations.<br>Otherwise on Day 1 when your new hire starts and he comes in with so many misconceptions about your business and realizes that he/she has made a mistake and is now searching for his next job on your dime and increasing your turn over.</p>
                        <p>Non-luxury sales consultant turnover was 72 percent, luxury was 48 percent. Female sales consultant turnover was 88 percent.<br>How much of this annual turnover was caused by candidates knowing absolutely nothing about your business, culture and expectations?</p>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <h2 style="text-align: left;">On-boarding</h2>
                                <p>Create an On-Boarding process so that your new team members have all of their hiring documents completed before their first day, have a roadmap to success with your company and an understanding of what success looks like and the expectations that you have for them.</p>
                                <p>Welcome and treat your new hires like a VIP and vital addition to the team with all of their tools, passwords, uniforms etc ready for them before they start their first day. </p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <h2 style="text-align: left;">Communication</h2>
                                <p>Set clear and concise expectations from the very beginning. Don't just assume that people should know what and how to do their job to your standards.</p>
                                <p>Be transparent as to what a day in the life at your business looks like. By showing candidates your company culture you will have candidates that can identify and see themselves working for you and others that will see that they would not be a fit and opt out on their own before even applying to the position in the first place.</p>
                                <p>Don't try and hide the company flaws either, it will just end up costing your business when candidates end up leaving.</p>
                            </div>
                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                <h2 style="text-align: left;">Training</h2>
                                <p>Provide ongoing training and an opportunity for growth. Create a Career Path Success Roadmap so that employees have a clear understanding of what is expected from them to progress and get promoted.</p>
                                <p>We all know the adage " What if I spend the money to train my employees and give them the tools to succeed and they leave? Well what if you don't train them and they decide to stay? </p>
                            </div>
                        </div>
                        <p>Organizations with a strong on-boarding process <strong>improve new hire retention by 82% and productivity by over 70%.</strong> Companies with weak on boarding programs lose the confidence of their candidates and are more likely to lose these individuals in the 90 days.</p>
                        <p>As more and more dealerships add flexibility to work schedules and move away from 100% commission pay plans to attract and retain Millennials, non-luxury brand dealerships reduced sales consultant turnover by 8 percent.</p>
                        <h2 style="text-align: left;">Additional Benefit:</h2>
                        <p>This systematic approach protects you from lawsuits and penalties as well.</p>
                        <h2 style="text-align: left;">How we calculate this data:</h2>
                        <p>Our national average and profit data for dealerships is based on the  <a class="text-color" href="https://www.nada.org/workforcestudy/"><strong>2016 NADA Dealership Workforce Study</strong></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function calculate_turnover_cost(){
        $('#turnover_calculator_form').validate();

        if($('#turnover_calculator_form').valid()) {


            /*var number_of_employees = $('#number_of_employees').val();
            var number_of_sales_rep = $('#number_of_sales_rep').val();
            var emp_annual_turnover = $('#emp_annual_turnover').val();
            var sales_rep_annual_turnover = $('#sales_rep_annual_turnover').val();
            var emp_average_annual_salary = $('#emp_average_annual_salary').val();
            var sales_rep_average_annual_salary = $('#sales_rep_average_annual_salary').val();
            var additional_costs_percentage = $('#additional_costs_percentage').val();

            var employees_left = ( number_of_employees * emp_annual_turnover ) / 100;
            var employees_left_salary = employees_left * emp_average_annual_salary;

            var sales_rep_left = ( number_of_sales_rep * sales_rep_annual_turnover ) / 100;
            var sales_rep_left_salary = sales_rep_left * sales_rep_average_annual_salary;

            var employees_turnover_cost = Math.round(employees_left_salary * ( additional_costs_percentage / 100 ));
            var sales_rep_turnover_cost = Math.round(sales_rep_left_salary * ( additional_costs_percentage / 100 ));

            $('#calculated_employee_turnover_cost').val(employees_turnover_cost);
            $('#calculated_sales_rep_turnover_cost').val(sales_rep_turnover_cost);*/

            $('#turnover_calculator_form').submit();
        }

    }




    $(document).ready(function () {
        $('input[type="range"]').rangeslider({
            polyfill : false,
            // Callback function
            onInit: function() {
                this.output = $( '<div class="range-output" />' ).insertAfter( this.$range ).html( this.$element.val() + ' %' );
            },

            // Callback function
            onSlide: function(position, value) {
                this.output.html( value + ' %');
            },

            // Callback function
            onSlideEnd: function(position, value) {

            }
        });
    });
    
</script>