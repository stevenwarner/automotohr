<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }

  

    public function fix_merge()
    {
        $this->load->model('2022/complynet_model', 'complynet_model');
        echo $this->complynet_model->syncJobRoles(
            '90AE8942-8150-4423-90A1-9FF8160A1376',
            'Body Shop Manager'
        );
        die('END');
        $this->tm->get_merge_employee();
    }



    // Enable Rehired Employees


    // public function enableRehiredemployees()
    // {

    //     $employeesData = $this->tm->getRehiredemployees();

    //     if (!empty($employeesData)) {
    //         foreach ($employeesData as $employeeRow) {
    //             $this->tm->updateEmployee($employeeRow['sid']);
    //         }
    //     }
    //     echo "Done";
    // }



public function benefits(){


    $benefits='[
        {
          "id": 1,
          "benefit_type": 1,
          "name": "Medical Insurance",
          "description": "Health-related insurance under IRS section 125 includes medical, dental and vision insurance, and is the most common benefit provided by employers today. It allows paying for certain health benefits using pre-tax dollars. This lowers the employee taxable income and the overall tax payments for both the employee and the employer.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": true,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 2,
          "benefit_type": 2,
          "name": "Dental Insurance",
          "description": "Health-related insurance under IRS section 125 includes medical, dental and vision insurance, and is the most common benefit provided by employers today. It allows paying for certain health benefits using pre-tax dollars. This lowers the employee taxable income and the overall tax payments for both the employee and the employer.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": true,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 3,
          "benefit_type": 3,
          "name": "Vision Insurance",
          "description": "Health-related insurance under IRS section 125 includes medical, dental and vision insurance, and is the most common benefit provided by employers today. It allows paying for certain health benefits using pre-tax dollars. This lowers the employee taxable income and the overall tax payments for both the employee and the employer.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": true,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 6,
          "benefit_type": 6,
          "name": "Health Savings Account",
          "description": "HSA allows employees to be reimbursed for qualified medical expenses. In most cases, deductions are pre-tax and lower the total amount of tax paid by employees and the employer. Employers may also make tax-free contributions to employee HSA. Remaining balances are carried over to the next year.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": true,
          "category": "Health"
        },
        {
          "id": 7,
          "benefit_type": 7,
          "name": "Health FSA",
          "description": "FSA allows employees to be reimbursed for qualified medical expenses. Contributions are pre-tax and lower the total amount of tax paid by employees and the employer.Employers may also make tax-free contributions to employee FSA. Remaining balances are not carried over to the next year.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": true,
          "category": "Health"
        },
        {
          "id": 11,
          "benefit_type": 11,
          "name": "Dependent Care FSA",
          "description": "Dependent Care FSA reimburses employees for expenses to care for dependents while the employee is at work (e.g. Daycares). Contributions are pre-tax and lower the total amount of tax paid by employees and the employer. Employers may also make tax-free contributions to employee FSA. Remaining balances are not carried over to the next year. Single parents or Married couples filing a joint return can elect up to $5000 per year. Married couples filing separate returns are limited to $2500 elections each.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": true,
          "category": "Health"
        },
        {
          "id": 8,
          "benefit_type": 8,
          "name": "SIMPLE IRA",
          "description": "Simple IRA is a tax-deferred retirement savings plan for employees. It is often use by small businesses as an alternative to 401(k) due to its relatively low operating cost. Employers are required to contribute a specific percentage to an employee’s SIMPLE IRA.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 14,
          "benefit_type": 14,
          "name": "SIMPLE IRA (Non-elective)",
          "description": "Simple IRA is a tax-deferred retirement savings plan for employees. It is often use by small businesses as an alternative to 401(k) due to its relatively low operating cost. Employers are required to contribute a specific percentage to an employee’s SIMPLE IRA.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 105,
          "benefit_type": 105,
          "name": "Roth 401(k)",
          "description": "Roth 401(k) is an after-tax savings plan for employees. Contributions made by employees are taxable for federal and state withholding. Often, employers contribute additional pre-tax dollars to the employee’s Roth account to encourage saving for retirement.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 110,
          "benefit_type": 110,
          "name": "Roth 403(b)",
          "description": "Roth 403(b) is an after-tax savings plan for certain clerics, employees of public schools, and employees of other types of tax-exempt organizations. Contributions made by employees are taxable for federal and state withholding. Often, employers contribute additional pre-tax dollars to the employee’s Roth account to encourage saving for retirement.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 5,
          "benefit_type": 5,
          "name": "401(k)",
          "description": "401(k) is tax-deferred retirement savings plan for employees. It is the most common retirement plan benefit offered by employers in all sizes. Often, employers contribute to the employee savings plan additional pre-tax dollars as an encouragement for retirement saving.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 9,
          "benefit_type": 9,
          "name": "403(b)",
          "description": "403(b) is tax-deferred retirement savings plan for certain clerics, employees of public schools, and employees of other types of tax-exempt organizations. Often, employers contribute to the employee savings plan additional pre-tax dollars as an encouragement for retirement saving.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 108,
          "benefit_type": 108,
          "name": "SEP-IRA",
          "description": "A SEP-IRA is a pre-tax retirement savings plan where only the employer contributes. It is often used by small businesses as an alternative to 401(k) due to its relatively low operating cost. Employers are required to contribute the same percentage to all enrolled employees, with a maximum contribution of 25% of the employee’s compensation.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 109,
          "benefit_type": 109,
          "name": "SARSEP",
          "description": "A SARSEP is a pre-tax retirement savings plan used by small businesses as an alternative to 401(k) due to its relatively low operating cost. While new SARSEP plans are not available, there are still some companies that are grandfathered into the plan. Employers are required to contribute the same percentage to all enrolled employees, with a maximum contribution of 25% of the employee’s compensation.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": true,
          "yearly_limit": true,
          "category": "Savings and Retirement"
        },
        {
          "id": 107,
          "benefit_type": 107,
          "name": "Group-Term Life Insurance",
          "description": "Group-Term Life Insurance is for coverage in excess of $50,000 per employee and is a taxable fringe benefit. Add this benefit only if you have employees with a coverage that is larger than $50,000. See IRS Publication 15-B to determine the dollar value of the excess coverage. Learn more about the taxability of this benefit on the IRS website",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 10,
          "benefit_type": 10,
          "name": "Commuter Benefits (pre-tax)",
          "description": "Tax-free commuter benefits allow employees to reduce their monthly commuting expenses for transit, carpooling, bicycling, and work-related parking costs. Please note that there is an annual maximum for this pre-tax benefit. The maximum dollar amount is found in IRS Publication 15-B",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Transportation"
        },
        {
          "id": 106,
          "benefit_type": 106,
          "name": "Personal Use of Company Car",
          "description": "Personal use of a company car is a non-cash, taxable fringe benefit. A portion of the car’s value is considered part of the employee’s total compensation for tax purposes, even though the employer owns or leases the car.",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Transportation"
        },
        {
          "id": 111,
          "benefit_type": 111,
          "name": "529 College Savings",
          "description": "529 College Savings is an after-tax savings plan for employees designed to encourage saving for future college costs. This benefit should be reported as a taxable benefit and will therefore be taxed.",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Other"
        },
        {
          "id": 998,
          "benefit_type": 998,
          "name": "Short Term Disability (post-tax)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 1000,
          "benefit_type": 1000,
          "name": "Short Term Disability (post-tax imputed)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 999,
          "benefit_type": 999,
          "name": "Long Term Disability (post-tax)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 1001,
          "benefit_type": 1001,
          "name": "Long Term Disability (post-tax imputed)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 996,
          "benefit_type": 996,
          "name": "Short Term Disability (pre-tax)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 997,
          "benefit_type": 997,
          "name": "Long Term Disability (pre-tax)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 991,
          "benefit_type": 991,
          "name": "Voluntary Short Term Disability (post-tax)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 992,
          "benefit_type": 992,
          "name": "Voluntary Long Term Disability (post-tax)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 993,
          "benefit_type": 993,
          "name": "Voluntary Life (post-tax)",
          "description": "Third Party Disability or Third Party Leave are policies offered by employers that pay an employee for a specific life event (maternity leave, injury). All payments made to employees come from a third-party, such as an insurer. For more information on the taxation of these plans, please refer to publication 15-A for more details.",
          "pretax": false,
          "posttax": true,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Health"
        },
        {
          "id": 113,
          "benefit_type": 113,
          "name": "Commuter Parking",
          "description": "Tax-free commuter benefits allow employees to reduce their monthly commuting expenses for transit, carpooling, bicycling, and work-related parking costs. Please note that there is an annual maximum for this pre-tax benefit. The maximum dollar amount is found in IRS Publication 15-B",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Transportation"
        },
        {
          "id": 114,
          "benefit_type": 114,
          "name": "Commuter Transit",
          "description": "Tax-free commuter benefits allow employees to reduce their monthly commuting expenses for transit, carpooling, bicycling, and work-related parking costs. Please note that there is an annual maximum for this pre-tax benefit. The maximum dollar amount is found in IRS Publication 15-B",
          "pretax": true,
          "posttax": false,
          "imputed": false,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Transportation"
        },
        {
          "id": 100,
          "benefit_type": 100,
          "name": "Other (taxable)",
          "description": "Employer-sponsored benefits like this are called fringe benefits, and they don’t get special tax treatment—they’ll be reported as taxable wages on your employees’ paystubs.",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Other"
        },
        {
          "id": 201,
          "benefit_type": 201,
          "name": "Cell Phone (taxable)",
          "description": "Employer-sponsored benefits like this are called fringe benefits, and they don’t get special tax treatment—they’ll be reported as taxable wages on your employees’ paystubs.",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Other"
        },
        {
          "id": 202,
          "benefit_type": 202,
          "name": "Gym & Fitness (taxable)",
          "description": "Employer-sponsored benefits like this are called fringe benefits, and they don’t get special tax treatment—they’ll be reported as taxable wages on your employees’ paystubs.",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Other"
        },
        {
          "id": 203,
          "benefit_type": 203,
          "name": "Housing (taxable)",
          "description": "Employer-sponsored benefits like this are called fringe benefits, and they don’t get special tax treatment—they’ll be reported as taxable wages on your employees’ paystubs.",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Other"
        },
        {
          "id": 204,
          "benefit_type": 204,
          "name": "Wellness (taxable)",
          "description": "Employer-sponsored benefits like this are called fringe benefits, and they don’t get special tax treatment—they’ll be reported as taxable wages on your employees’ paystubs.",
          "pretax": false,
          "posttax": true,
          "imputed": true,
          "healthcare": false,
          "retirement": false,
          "yearly_limit": false,
          "category": "Other"
        }
      ]';

      $benefitsArray=json_decode($benefits,true);
echo "<pre>";
      foreach ($benefitsArray as $row){
        print_r($row);
        echo $row['id'].$row['name']."<br>";

        $inserData['benefit_type']=$row['benefit_type'];
        $inserData['name ']=$row['name'];
        $inserData['description']=$row['description'];
        $inserData['pretax ']=$row['pretax'];
        $inserData['posttax']=$row['posttax'];
        $inserData['imputed']=$row['imputed'];
        $inserData['healthcare']=$row['healthcare'];
        $inserData['retirement']=$row['retirement'];
        $inserData['yearly_limit']=$row['yearly_limit'];
        $inserData['category']=$row['category'];
        $inserData['status']=1;


        $this->db->insert('default_benifits', $inserData);


      }



}



}
