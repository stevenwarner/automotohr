// Create router
const TimeslotRouter = require('express').Router();
// Include Model
const {
    GetTimeslots,
    InsertTimeslot,
    GetSingleTimeslot,
    UpdateTimeslot
} = require('./Model');
//
const moment = require('moment-timezone');
// Change timezone
moment.tz.setDefault(process.env.DEFAULT_TIMEZONE);

//
TimeslotRouter.get(
    '/',
    async(req, res) => {
        //
        const records = await GetTimeslots(
            req.companyId, [
                'sid as timeslotId',
                'title',
                'shift_start_time as checkIn',
                'shift_end_time as checkOut',
                'late_check_in as lateCheckIn',
                'early_check_out as earlyCheckOut',
                'days_off as daysOff'
            ]
        );
        //
        res.send({
            Status: true,
            Data: records
        });
    }
);

//
TimeslotRouter.get(
    '/:timeslotId',
    async(req, res) => {
        //
        const record = await GetSingleTimeslot(
            req.companyId,
            req.params.timeslotId, [
                'sid as timeslotId',
                'title',
                'shift_start_time as checkIn',
                'shift_end_time as checkOut',
                'late_check_in as lateCheckIn',
                'early_check_out as earlyCheckOut',
                'days_off as daysOff'
            ]
        );
        //
        res.send({
            Status: true,
            Data: record
        });
    }
);

//
TimeslotRouter.post(
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
        if (!req.body.title || req.body.title === undefined) {
            response.Response = 'Title is required.';
        }
        //
        else if (!req.body.checkIn || req.body.checkIn === undefined) {
            response.Response = 'Check In time is required.';
        }
        //
        else if (!req.body.checkOut || req.body.checkOut === undefined) {
            response.Response = 'Check Out time is required.';
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
            insertObject.title = req.body.title;
            insertObject.shift_start_time = req.body.checkIn;
            insertObject.shift_end_time = req.body.checkOut;
            insertObject.late_check_in = req.body.lateCheckIn;
            insertObject.early_check_out = req.body.earlyCheckOut;
            insertObject.days_off = JSON.stringify(req.body.daysOff).replace(/"/g, '\\"');
            insertObject.number_of_employees = 0;
            insertObject.history = '{}';
            insertObject.last_updated_by = req.employeeId;
            insertObject.status = 1;
            insertObject.created_at = moment().format(process.env.DB_DATETIME_FORMAT);
            insertObject.updated_at = moment().format(process.env.DB_DATETIME_FORMAT);
            // Insert the record
            const Id = await InsertTimeslot(
                insertObject
            );
            //
            if (!Id) {
                response.Status = false;
                response.Response = 'Something went wrong while adding the timeslot. Please try again in a few moments.';
            } else {
                response.Response = 'You have successfully added a new timeslot.';
            }
        }
        //
        res.send(response);
    }
);

//
TimeslotRouter.put(
    '/update',
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
        if (!req.body.title || req.body.title === undefined) {
            response.Response = 'Title is required.';
        }
        //
        else if (!req.body.checkIn || req.body.checkIn === undefined) {
            response.Response = 'Check In time is required.';
        }
        //
        else if (!req.body.checkOut || req.body.checkOut === undefined) {
            response.Response = 'Check Out time is required.';
        }
        //
        else {
            response.Status = true;
        }
        //
        if (response.Status) {
            //
            const insertObject = {};
            insertObject.title = req.body.title;
            insertObject.shift_start_time = req.body.checkIn;
            insertObject.shift_end_time = req.body.checkOut;
            insertObject.late_check_in = req.body.lateCheckIn;
            insertObject.early_check_out = req.body.earlyCheckOut;
            insertObject.days_off = JSON.stringify(req.body.daysOff).replace(/"/g, '\\"');
            insertObject.last_updated_by = req.employeeId;
            insertObject.updated_at = moment().format(process.env.DB_DATETIME_FORMAT);
            // Insert the record
            const Id = await UpdateTimeslot(
                insertObject,
                req.body.timeslotId
            );
            //
            if (!Id) {
                response.Status = false;
                response.Response = 'Something went wrong while updating the timeslot. Please try again in a few moments.';
            } else {
                response.Response = 'You have successfully updated the timeslot.';
            }
        }
        //
        res.send(response);
    }
);


module.exports = TimeslotRouter;