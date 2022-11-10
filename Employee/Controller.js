// Create router
const router = require('express').Router();
//
// Include Company Modal
const {
    getCompanyEmployees
} = require('./Modal.js');


/**
 * Get company employees
 * 
 * @returns JSON
 */
router.get(
    '/:num/employees',
    async(Request, Response) => {
        console.log(Request.params.num);
        console.log(Request.query.columns);
        //
        return Response.send();
        // Get default survey template account
        const records = await getDefaultTemplates(
            Request.params.num,
            [
                'title',
                'description',
                'frequency',
                'questions_count',
                'questions',
            ]
        ) || {};
        //
        if (typeof records === "string") {
            console.log("/templates ,"+ records);
            //
            Response.statusCode = 400;
            return Response.send();
        }
        //
        return Response.send(records);
    }
);
//
module.exports = router;