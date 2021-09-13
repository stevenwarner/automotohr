// Create router
const LocationRouter = require('express').Router();
// Include Model
const {
    GetCompanyLocations,
    GetSingleCompanyLocations,
    UpdateCompanyLocation,
    InsertCompanyLocation,
} = require('./Model');
//
const moment = require('moment-timezone');
// Change timezone
moment.tz.setDefault(process.env.DEFAULT_TIMEZONE);
// Get all company locations
LocationRouter.get(
    '/',
    async(req, res) => {
        //
        const records = await GetCompanyLocations(
            req.companyId, [
                'sid as locationId',
                'country',
                'state',
                'city',
                'zip as zipcode',
                'phone_number as phoneNumber',
                'street_1 as street1',
                'street_2 as street2'
            ]
        );
        //
        res.send({
            Status: true,
            Data: records
        });
    }
);

// Get single company locations
LocationRouter.get(
    '/:locationId',
    async(req, res) => {
        //
        const record = await GetSingleCompanyLocations(
            req.companyId,
            req.params.locationId, [
                'sid as locationId',
                'country',
                'state',
                'city',
                'zip as zipcode',
                'phone_number as phoneNumber',
                'street_1 as street1',
                'street_2 as street2'
            ]
        );
        //
        res.send({
            Status: true,
            Data: record
        });
    }
);

// Add a new company location
LocationRouter.post(
    '/create',
    async(req, res) => {
        //
        let response = {};
        //
        response.Status = false;
        response.Response = 'Invalid Request';
        // Validate incoming data
        //
        if (req.body === undefined) {
            return res.send(response);
        }
        //
        if (!req.body.country || req.body.country === undefined) {
            response.Response = 'Company is required.';
        }
        //
        else if (!req.body.state || req.body.state === undefined) {
            response.Response = 'State is required.';
        }
        //
        else if (!req.body.city || req.body.city === undefined) {
            response.Response = 'City is required.';
        }
        //
        else if (!req.body.street1 || req.body.street1 === undefined) {
            response.Response = 'Street 1 is required.';
        }
        //
        else if (!req.body.phoneNumber || req.body.phoneNumber === undefined) {
            response.Response = 'Phone number is required.';
        }
        //
        else {
            response.Status = true;
        }
        //
        if (response.Status) {
            //
            const insertObject = {};
            insertObject.company_sid = req.companyId;
            insertObject.last_updated_by = req.employeeId;
            insertObject.country = req.body.country.toUpperCase();
            insertObject.state = req.body.state.toUpperCase();
            insertObject.city = req.body.city;
            insertObject.street_1 = req.body.street1;
            insertObject.street_2 = req.body.street2;
            insertObject.zip = req.body.zipcode;
            insertObject.phone_number = req.body.phoneNumber;
            insertObject.filing_address = '';
            insertObject.mailing_address = '';
            insertObject.is_active_on_gusto = 0;
            insertObject.gusto_location_id = 0;
            insertObject.history = '{}';
            insertObject.created_at = moment().format(process.env.DB_DATETIME_FORMAT);
            insertObject.updated_at = moment().format(process.env.DB_DATETIME_FORMAT);

            // Insert the record
            const Id = await InsertCompanyLocation(
                insertObject
            );
            //
            if (!Id) {
                response.Status = false;
                response.Response = 'Something went wrong while adding the location. Please try again in a few moments.';
            } else {
                response.Response = 'You have successfully added a new location.';
            }
        }
        //
        res.send(response);
    }
);

// Update company location
LocationRouter.put(
    '/:locationId',
    async(req, res) => {
        //
        let response = {};
        //
        response.Status = false;
        response.Response = 'Invalid Request';
        // Validate incoming data
        //
        if (req.body === undefined) {
            return res.send(response);
        }
        //
        if (!req.body.country || req.body.country === undefined) {
            response.Response = 'Company is required.';
        }
        //
        else if (!req.body.state || req.body.state === undefined) {
            response.Response = 'State is required.';
        }
        //
        else if (!req.body.city || req.body.city === undefined) {
            response.Response = 'City is required.';
        }
        //
        else if (!req.body.street1 || req.body.street1 === undefined) {
            response.Response = 'Street 1 is required.';
        }
        //
        else if (!req.body.phoneNumber || req.body.phoneNumber === undefined) {
            response.Response = 'Phone number is required.';
        }
        //
        else {
            response.Status = true;
        }
        //
        if (response.Status) {
            //
            const insertObject = {};
            insertObject.last_updated_by = req.employeeId;
            insertObject.country = req.body.country.toUpperCase();
            insertObject.state = req.body.state.toUpperCase();
            insertObject.city = req.body.city;
            insertObject.street_1 = req.body.street1;
            insertObject.street_2 = req.body.street2;
            insertObject.zip = req.body.zipcode;
            insertObject.phone_number = req.body.phoneNumber;
            insertObject.history = '{}';
            insertObject.updated_at = moment().format(process.env.DB_DATETIME_FORMAT);

            // Insert the record
            const Id = await UpdateCompanyLocation(
                insertObject,
                req.body.locationId
            );
            //
            if (!Id) {
                response.Status = false;
                response.Response = 'Something went wrong while updating the location. Please try again in a few moments.';
            } else {
                response.Response = 'You have successfully updated the location.';
            }
        }
        //
        res.send(response);
    }
);

module.exports = LocationRouter;