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

// Employee Login Route
const EmployeeRouter = require('./Employee/Controller');
const EmployeeSurvey = require('./EmployeeSurvey/Controller');

//
app.use(cors());
// Parser
app.use(express.json());
// Use Routes
//
app.use('/employee', EmployeeRouter);
// 
app.use('/employee_survey', EmployeeSurvey);
// Hello URL
app.get('/ping', (req, res) => { res.send("All systems ready to go :)"); });
//



// For unauthorised routes 
app.get('*', (req, res) => { res.sendStatus(404); });
//
app.listen(3000, () => {
    console.log(`Server running on: http://127.0.0.1:3000`);
});

