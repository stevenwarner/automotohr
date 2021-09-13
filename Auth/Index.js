//
const JSONWEBTOKEN = require('jsonwebtoken');
//
module.exports = async(req, res, next) => {
    //
    const response = {
        Status: false,
        Response: "Invalid Request"
    };
    //
    if (req.headers['key'] === undefined) {
        res.send(response);
        return;
    }
    // Let's verify the key
    try {
        const jwt = JSONWEBTOKEN.verify(req.headers['key'], process.env.SECRET_KEY);
        //
        req.companyId = jwt.companyId;
        req.employeeId = jwt.employeeId;
        //
    } catch (err) {
        return res.status(401).send("Invalid Token");
    }
    //
    return next();
}