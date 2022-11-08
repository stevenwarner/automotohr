// Include DB Driver
const DB_DRIVER = require('../Database/Driver.js');
// Include DB Schems
const { CompanyBankAccounts, CompanyBankAccountsHistory, Employees } = require('../Database/Schema.js');

/**
 * Get Company Bank Accounts
 * @param {Integer} companyId 
 * @param {Integer} columns 
 * @returns
 */
const getDefaultTemplates = (columns) => {
    //
    let SQL_QUERY = `
        SELECT ${columns !== undefined ? columns.join(', ') : '*'}
        FROM ${CompanyBankAccounts}
        INNER JOIN ${Employees} ON ${Employees}.sid = ${CompanyBankAccounts}.last_updated_by
        ORDER BY ${CompanyBankAccounts}.sid DESC
        LIMIT 1;
    `;
    //
    return DB_DRIVER.RowArray(SQL_QUERY);
};





// Export the Modal Methods
module.exports = {
    getDefaultTemplates,
};