// Create router
const router = require('express').Router();
// Include a moment timezone instance
const moment = require('moment-timezone');
// Change the default timezone to PST
moment.tz.setDefault(process.env.DEFAULT_TIMEZONE);
//
// Include Company Modal
const {
    getDefaultTemplates,
    insertCompanySurvey,
    updateCompanySurvey,
    getDefaultTemplateInfo,
    insertCompanySurveyQuestion,
    updateCompanySurveyQuestion
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
 * Create Company Survey
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
            console.log("/survey ,"+ records);
            //
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
            console.log("/survey ,"+ records);
            //
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
        const response = await insertCompanySurvey(insertObj);
        //
        if (typeof response == "string") {
            Response.statusCode = 400;
            return Response.send(response);
        }
        //
        if (Request.body.template_code != 0) {
           moveQuestion(Request.body.template_code, response);
        }
        //
        return Response.send({
            "id": response
        });
    }
);

/**
 * Create Company Survey
 * 
 * @returns JSON
 */
router.post(
    '/:num/question',
    async(Request, Response) => {
        //
        let errorsArray = [];
        // Validate incoming data
        if (!Request.body) {
            console.log("/question ,"+ records + " insert");
            //
            Response.statusCode = 400;
            return Response.send(resp);
        }
        // Template Code validate
        if (!Request.body.text) {
            errorsArray.push("Question text is required.");
        }

        // Title validate
        if (!Request.body.type) {
            errorsArray.push("Question type is required.");
        }
        // Start date validate
        if (Request.body.type == "rating") {
            if (Request.body.limit === undefined || Request.body.limit === null) {
                errorsArray.push("Question rating is required.");
            }
            //
            if (Request.body.limit < 5 || Request.body.limit > 10) {
                errorsArray.push("Rating must be between 5 to 10.");
            }
        }
        
        //
        if (errorsArray.length > 0) {
            console.log("/question ,"+ records + " insert");
            //
            Response.statusCode = 400;
            return Response.send(errorsArray);
        }
        //
        const insertObj = {};
        insertObj.survey_sid = Request.params.num;
        insertObj.question_text = Request.body.text;
        insertObj.question_type = Request.body.type;
        insertObj.rating_limit = Request.body.type == "rating" ? Request.body.limit : 0;
        insertObj.question_tag = Request.body.tag != undefined ? Request.body.tag : '';
        //
        const response = await insertCompanySurveyQuestion(insertObj);
        //
        if (typeof response == "string") {
            console.log("/question ,"+ records + " insert");
            //
            Response.statusCode = 400;
            return Response.send(response);
        }
        //
        return Response.send({
            "id": response,
            question: insertObj
        });
    }
);

/**
 * Create Company Survey
 * 
 * @returns JSON
 */
router.put(
    '/:num/question',
    async(Request, Response) => {
        //
        let errorsArray = [];
        // Validate incoming data
        if (!Request.body) {
            console.log("/question ,"+ records + " update");
            //
            Response.statusCode = 400;
            return Response.send(resp);
        }
        // Template Code validate
        if (!Request.body.text) {
            errorsArray.push("Question text is required.");
        }

        // Title validate
        if (!Request.body.type) {
            errorsArray.push("Question type is required.");
        }
        // Start date validate
        if (Request.body.type == "rating") {
            if (Request.body.limit === undefined || Request.body.limit === null) {
                errorsArray.push("Question rating is required.");
            }
            //
            if (Request.body.limit < 5 || Request.body.limit > 10) {
                errorsArray.push("Rating must be between 5 to 10.");
            }
        }
        
        //
        if (errorsArray.length > 0) {
            console.log("/question ,"+ records + " update");
            //
            Response.statusCode = 400;
            return Response.send(errorsArray);
        }
        //
        const UpdateObj = {};
        UpdateObj.question_text = Request.body.text;
        UpdateObj.question_type = Request.body.type;
        UpdateObj.rating_limit = Request.body.type == "rating" ? Request.body.limit : 0;
        UpdateObj.question_tag = Request.body.tag != undefined ? Request.body.tag : '';
        UpdateObj.updated_at = moment().format(process.env.DB_DATETIME_FORMAT);
        //
        const response = await updateCompanySurveyQuestion(
            UpdateObj, 
            [
                `sid = ${Request.params.num}`
            ]
        );
        //
        if (typeof response == "string") {
            console.log("/question ,"+ records + " update");
            //
            Response.statusCode = 400;
            return Response.send(response);
        }
        //
        return Response.send({
            "id": response,
            question: UpdateObj
        });
    }
);


async function moveQuestion (templateSid, surveySid) {
    const templateInfo = await getDefaultTemplateInfo(
        templateSid,
        [
            'questions'
        ]  
    ) || {};
    //
    if (typeof templateInfo === "string") {
        return false;
    }
    //
    const templateQuestion = JSON.parse(templateInfo.questions);
    //
    templateQuestion.forEach(item => {
        //
        if (item.tag !== undefined) {
            item.questions.forEach(question => {
                const insertObj = {};
                insertObj.survey_sid = surveySid;
                insertObj.question_text = question.text;
                insertObj.question_type = question.type;
                insertObj.rating_limit = question.type == "rating" ? question.limit : 0;
                insertObj.question_tag = item.tag;

                insertCompanySurveyQuestion(insertObj);
            });
        } else {
            const insertObj = {};
            insertObj.survey_sid = surveySid;
            insertObj.question_text = item.text;
            insertObj.question_type = item.type;
            insertObj.rating_limit = item.type == "rating" ? item.limit : 0;
            insertObj.question_tag = "";
            
            insertCompanySurveyQuestion(insertObj);
        }
    });
    //
    const UpdateObj = {};
    UpdateObj.is_draft = 0;
    UpdateObj.is_completed = 1;
    UpdateObj.updated_at = moment().format(process.env.DB_DATETIME_FORMAT);
    //
    updateCompanySurvey(
        UpdateObj, 
        [
            `sid = ${surveySid}`
        ]
    );
    //
    return true;
}

// Export the Router
module.exports = router;