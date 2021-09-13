// Let's load the env file
require('dotenv').config();
// Include the express
const express = require('express');
// Create an express server
const app = express();
//
const cors = require('cors');
// Include Middleware
const AUTH = require('./Auth/Index');
// Include Routes
const AttendanceRouter = require('./Attendance/Controller');
const EmployeeRouter = require('./Employee/Controller');
const CompanyRouter = require('./Company/Controller');
const LocationRouter = require('./Company/Location/Controller');
const TimeslotRouter = require('./Company/Timeslot/Controller');
//
app.use(cors());
// Parser
app.use(express.json());
// Use Routes
app.use('/attendance', AUTH, AttendanceRouter);
app.use('/employee', EmployeeRouter);
app.use('/company', AUTH, CompanyRouter);
app.use('/company/locations', AUTH, LocationRouter);
app.use('/company/timeslots', AUTH, TimeslotRouter);
// For unauthorised routes 
app.get('*', (req, res) => { res.sendStatus(404); })
    //
app.listen(3000, () => {
    console.log('Server running');
})