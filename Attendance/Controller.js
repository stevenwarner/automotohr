// Create router
const AttendanceRouter = require('express').Router();
// Include Model
const AttendanceModel = require('./Model');

// 
AttendanceRouter.get(
    '/',
    async (req, res) => {
        // console.dir(req.ahr_session, {depth: 0});
        //
        //
        result =  await AttendanceModel.getResult();
        //
        res.send({
            Status: true,
            Response: result
        });
    }
);


module.exports = AttendanceRouter;