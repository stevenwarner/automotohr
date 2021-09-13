// Create router
const EmployeeRouter = require('express').Router();
// Include Middleware
const EmployeeMiddleware = require('./Middleware');
// 
const JSONWEBTOKEN = require('jsonwebtoken');
// 
EmployeeRouter.post(
    '/login',
    EmployeeMiddleware,
    async(req, res) => {
        //
        const token = JSONWEBTOKEN.sign({
            data: req.body.Code,
            companyId: req.body.CompanyCode,
            employeeId: req.body.EmployeeCode
        }, process.env.SECRET_KEY, { expiresIn: "1 day" });
        //
        res.send({
            Status: true,
            Response: token
        });
    }
);
//
module.exports = EmployeeRouter;