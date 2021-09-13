//
const { default: axios } = require('axios');
//
const qs = require('qs');

//
module.exports = async(req, res, next) => {
    //
    const response = {
        Status: false,
        Response: "Invalid Call"
    };
    //
    if (req.body === undefined || req.body.Token === undefined) {
        return res.send(response);
    }
    //
    var repos;
    // Validate the keys
    try {
        // Send a POST request
        repos = await axios({
            method: 'post',
            url: `${process.env.AHR_PATH}verify_my_token/${process.env.AHR_TOKEN}`,
            data: {
                TOKEN: req.body.Token
            }
        });
    } catch (err) {
        return res.send(response);
    }
    //
    if (repos !== undefined) {
        //
        next();
    }
}