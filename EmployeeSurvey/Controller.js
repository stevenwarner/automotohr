// Create router
const esrouter = require('express').Router();
//
// Include Company Modal
const {
    GetCompanyBankAccounts,
    GetCompanyBankAccountsRow,
    GetCompanyBankAccountsHistory,
    InsertCompanyBankAccounts,
    InsertCompanyBankAccountsHistory,
    UpdateCompanyBankAccounts,
} = require('./Modal.js');

/**
 * Get companys' bank account history
 * 
 * @returns JSON
 */
esrouter.get(
    '/:num/getTemplate',
    async(Request, Response) => {
        //
        console.log(Request.params.num);
        //


        //
        Response.send({
            status: true,
            response: "please Enjoy"
        });
    }
);

// Export the Router
module.exports = esrouter;