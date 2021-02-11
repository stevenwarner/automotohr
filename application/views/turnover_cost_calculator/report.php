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
                    <?php if(!empty($cost_record)) { ?>
                        <div class="dual-color-heading">
                            <h2>EMPLOYEE TURNOVER COST <span>REPORT</span></h2>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <p>Here is your customized report</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <p>Employee Turnover takes a huge toll on your dealerships bottom line, most likely in ways that you weren't even aware of.</p>
                                <p>This customized report can help your dealership to focus on overlooked areas that cost you time and money... as well as exposing you to financial and legal risks.</p>
                            </div>
                        </div>

                        <br />
                        <br />
                        <br />

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="dual-color-heading">
                                    <h2>Across Your <span>Dealership</span></h2>
                                </div>
                            </div>
                        </div>
                        <div class="row font-22">
                            <div class="col-xs-6 text-right">
                                <p>Each Year turnover will cost your company</p>
                            </div>
                            <div class="col-xs-6 text-left text-danger">
                                <p>$ <?php echo number_format($cost_record['calculated_employee_turnover_cost'],2); ?></p>
                            </div>
                        </div>

                        <div class="row font-22">
                            <div class="col-xs-6 text-right">
                                <p>Your dealership will lose</p>
                            </div>
                            <div class="col-xs-6 text-left text-danger">
                                <p><?php echo $cost_record['number_of_employees_left']; ?> Employees</p>
                            </div>
                        </div>

                        <br />
                        <br />
                        <br />

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="dual-color-heading">
                                    <h2>Focusing On Your <span>Sales Team</span></h2>
                                </div>
                            </div>
                        </div>

                        <div class="row font-22">
                            <div class="col-xs-6 text-right">
                                <p>Turnover within your sales team will cost</p>
                            </div>
                            <div class="col-xs-6 text-left text-danger">
                                <p>$ <?php echo number_format($cost_record['calculated_sales_rep_turnover_cost'], 2); ?></p>
                            </div>
                        </div>

                        <div class="row font-22">
                            <div class="col-xs-6 text-right">
                                <p>Your dealership will lose</p>
                            </div>
                            <div class="col-xs-6 text-left text-danger">
                                <p><?php echo $cost_record['number_of_sales_reps_left']; ?> Sales Representatives</p>
                            </div>
                        </div>

                        <br />
                        <br />
                        <br />


                        <div class="row font-22">
                            <div class="col-xs-12">
                                <div class="dual-color-heading">
                                    <h2>Potential <span>Savings</span></h2>
                                </div>
                            </div>
                        </div>

                        <?php if($cost_record['employee_annual_turnover_percentage'] > 10) { ?>
                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>If You Reduce your Dealership turnover by</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p>10 %</p>
                                </div>
                            </div>
                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>From</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p><?php echo $cost_record['employee_annual_turnover_percentage']; ?> %</p>
                                </div>
                            </div>

                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>To</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p><?php echo $cost_record['employee_annual_turnover_percentage'] - 10; ?> %</p>
                                </div>
                            </div>

                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>Your could save</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p>$ <?php echo number_format($cost_record['calculated_employee_turnover_cost'] - $cost_record['calculated_employee_turnover_cost_reduced'], 2); ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($cost_record['sales_reps_annual_turnover_percentage'] > 10) { ?>

                            <br />

                            <div class="row font-22">
                                <div class="col-xs-12">
                                    <div class="dual-color-heading">
                                        <h2><span>AND</span></h2>
                                    </div>
                                </div>
                            </div>

                            <br />

                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>If You Reduce your Sales team turnover by</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p>10 %</p>
                                </div>
                            </div>
                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>From</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p><?php echo $cost_record['sales_reps_annual_turnover_percentage']; ?> %</p>
                                </div>
                            </div>

                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>To</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p><?php echo $cost_record['sales_reps_annual_turnover_percentage'] - 10; ?> %</p>
                                </div>
                            </div>

                            <div class="row font-22">
                                <div class="col-xs-6 text-right">
                                    <p>You could save</p>
                                </div>
                                <div class="col-xs-6 text-left text-color">
                                    <p>$ <?php echo number_format($cost_record['calculated_sales_rep_turnover_cost'] - $cost_record['calculated_sales_rep_turnover_cost_reduced'], 2); ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <br/>
                        <br/>
                        <br/>

                        <div class="row font-22">
                            <div class="col-xs-6 col-xs-offset-3 text-center">
                                <a class="btn btn-success btn-lg" href="<?php echo base_url('turnover_cost_calculator');?>">Back To Calculator</a>
                            </div>

                        </div>

                        <div class="clear"></div>
                    <?php } ?>
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

