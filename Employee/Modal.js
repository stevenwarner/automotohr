// Include DB Driver
const DB_DRIVER = require('../Database/Driver.js');
// Include DB Schems
const { Employees } = require('../Database/Schema.js');

/**
 * 
 * @param {Integer} employeeId 
 * @param {Integer} locationID 
 * @param {Array}   columns 
 * @returns 
 */
 const GetEmployeeJobByLocationID = (employeeId, locationID, columns) => {
    //
    let SQL_QUERY = `
        SELECT ${typeof columns === 'object' ? columns.join(',') : columns}
        FROM ${PayrollEmployeeJobs}
        INNER JOIN ${Employees} ON ${Employees}.sid = ${PayrollEmployeeJobs}.last_modified_by
        WHERE ${PayrollEmployeeJobs}.employee_sid = ${employeeId}
        AND ${PayrollEmployeeJobs}.payroll_location_id = ${locationID}
        AND ${PayrollEmployeeJobs}.is_primary = 1
        ORDER BY ${PayrollEmployeeJobs}.sid DESC;
    `;
    //
    return DB_DRIVER.RowArray(SQL_QUERY);
};

// Export the Modal Methods
module.exports = {
    getCompanyEmployees
};