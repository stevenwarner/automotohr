// Create router
const router = require('express').Router();
//
// Include Company Modal
const {
    getDefaultTemplates,
    InsertCompanySurvey,
    getDefaultTemplateInfo,
    InsertCompanySurveyQuestion
} = require('./Modal.js');

/**
 * Get AutomotHR Default Employee Survey Templates
 * 
 * @returns JSON
 */
router.get(
    '/templates',
    async(Request, Response) => {
        // Get default survey template account
        const records = await getDefaultTemplates(
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

/**
 * Create Company Specific Survey Templates
 * 
 * @returns JSON
 */
router.post(
    '/:num/survey',
    async(Request, Response) => {
        //
        let errorsArray = [];
        // Validate incoming data
        if (!Request.body) {
            Response.statusCode = 400;
            return Response.send(resp);
        }
        // Template Code validate
        if (Request.body.template_code === undefined || Request.body.template_code === null) {
            errorsArray.push("Template code is required.");
        }

        // Title validate
        if (!Request.body.title) {
            errorsArray.push("Survey Title is required.");
        }
        // Start date validate
        if (!Request.body.start_date) {
            errorsArray.push("Start date is required.");
        }
        //
        // End date validate
        if (!Request.body.end_date) {
            errorsArray.push("End date is required.");
        }
        //
        // End date validate
        if (!Request.body.employee_code) {
            errorsArray.push("Employee code is required.");
        }
        //
        if (errorsArray.length > 0) {
            Response.statusCode = 400;
            return Response.send(errorsArray);
        }
        //
        const insertObj = {};
        insertObj.company_sid = Request.params.num;
        insertObj.creator_sid = Request.body.employee_code;
        insertObj.title = Request.body.title;
        insertObj.description = Request.body.description != undefined ? Request.body.description : '';
        insertObj.start_date = Request.body.start_date;
        insertObj.end_date = Request.body.end_date;
        //
        const response = await InsertCompanySurvey(insertObj);
        //
        if (typeof response == "string") {
            Response.statusCode = 400;
            return Response.send(response);
        }
        //
        if (Request.body.template_code != 0) {
           moveQuestion (Request.body.template_code, response);
        }
        //
        return Response.send({
            "id": response
        });
        // message = 'You have successfully added the company survey.';
    }
);


async function moveQuestion (templateSid, surveySid) {
    const templateInfo = await getDefaultTemplateInfo(
        templateSid,
        [
            'questions',
            'questions_count'
        ]  
    ) || {};
    //
    if (typeof templateInfo === "object") {
        //
        const insertObj = {};
        insertObj.survey_id = surveySid;
        insertObj.questions_count = templateInfo.questions_count;
        insertObj.questions = templateInfo.questions;
        //
        const response = await InsertCompanySurveyQuestion(insertObj);
        //
        console.log()
        if (typeof response == "string") {
            return "";
        }
        //


    }
}

// Export the Router
module.exports = router;