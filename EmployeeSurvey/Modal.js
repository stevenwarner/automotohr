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
const GetCompanyBankAccounts = (companyId, columns) => {
    //
    let SQL_QUERY = `
        SELECT ${columns !== undefined ? columns.join(', ') : '*'}
        FROM ${CompanyBankAccounts}
        INNER JOIN ${Employees} ON ${Employees}.sid = ${CompanyBankAccounts}.last_updated_by
        WHERE ${CompanyBankAccounts}.company_sid = ${companyId}
        ORDER BY ${CompanyBankAccounts}.sid DESC
        LIMIT 1;
    `;
    //
    return DB_DRIVER.RowArray(SQL_QUERY);
};

/**
 * Get Company Bank Accounts Row
 * @param {Integer} companyId 
 * @param {Integer} columns 
 * @returns
 */
const GetCompanyBankAccountsRow = (companyId, columns) => {
    //
    let SQL_QUERY = `
        SELECT ${columns !== undefined ? columns.join(', ') : '*'}
        FROM ${CompanyBankAccounts}
        WHERE ${CompanyBankAccounts}.company_sid = ${companyId}
        ORDER BY ${CompanyBankAccounts}.sid DESC
        LIMIT 1;
    `;
    //
    return DB_DRIVER.RowArray(SQL_QUERY);
};

/**
 * Get Company Bank Accounts
 * @param {Integer} companyId 
 * @param {Integer} columns 
 * @returns
 */
const GetCompanyBankAccountsHistory = (companyId, columns) => {
    //
    let SQL_QUERY = `
        SELECT ${columns !== undefined ? columns.join(', ') : '*'}
        FROM ${CompanyBankAccountsHistory}
        INNER JOIN ${Employees} ON ${Employees}.sid = ${CompanyBankAccountsHistory}.last_updated_by
        WHERE ${CompanyBankAccountsHistory}.company_sid = ${companyId}
        ORDER BY ${CompanyBankAccountsHistory}.sid DESC;
    `;
    //
    return DB_DRIVER.ResultArray(SQL_QUERY);
};

/**
 * 
 * @param {Object} dataObj 
 * @returns 
 */
const InsertCompanyBankAccounts = (dataObj) => {
    //
    return DB_DRIVER.Insert(CompanyBankAccounts, dataObj);
};

/**
 * 
 * @param {Object} dataObj 
 * @param {Object} whereObj 
 * @returns 
 */
const UpdateCompanyBankAccounts = (dataObj, whereObj) => {
    //
    return DB_DRIVER.Update(CompanyBankAccounts, dataObj, whereObj);
};

/**
 * 
 * @param {Object} dataObj 
 * @returns 
 */
const InsertCompanyBankAccountsHistory = (dataObj) => {
    //
    return DB_DRIVER.Insert(CompanyBankAccountsHistory, dataObj);
};





// Export the Modal Methods
module.exports = {
    GetCompanyBankAccounts,
    GetCompanyBankAccountsRow,
    GetCompanyBankAccountsHistory,
    InsertCompanyBankAccounts,
    UpdateCompanyBankAccounts,
    InsertCompanyBankAccountsHistory
};