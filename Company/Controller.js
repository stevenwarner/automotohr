// Create router
const CompanyRouter = require('express').Router();
// Include Model
const CompanyModal = require('./Model');
//
const moment = require('moment-timezone');
// Change timezone
moment.tz.setDefault(process.env.DEFAULT_TIMEZONE);

// Get all States
// Need to move to a separate module
CompanyRouter.get(
    '/states',
    async(req, res) => {
        //
        const records = await CompanyModal.GetStates([
            'state_name as name',
            'state_code as code'
        ]);
        //
        res.send({
            Status: true,
            Data: records
        });
    }
);


module.exports = CompanyRouter;