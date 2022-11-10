// Include DB Driver
const DB_DRIVER = require('../Database/Driver.js');
// Include DB Schems
const { DefaultTemplates, Surveys, SurveyQuestion } = require('../Database/Schema.js');

/**
 * Get AutoMoto Default Templates
 * @param {Array} columns 
 * @returns
 */
const getDefaultTemplates = (columns) => {
    //
    let SQL_QUERY = `
        SELECT ${columns !== undefined ? columns.join(', ') : '*'}
        FROM ${DefaultTemplates}
        WHERE status = 1;
    `;
    //
    return DB_DRIVER.ResultArray(SQL_QUERY);
};

/**
 * 
 * @param {Object} dataObj 
 * @returns 
 */
const insertCompanySurvey = (dataObj) => {
    //
    return DB_DRIVER.Insert(Surveys, dataObj);
};

/**
 * 
 * @param {Object} dataObj 
 * @param {Object} whereObj 
 * @returns 
 */
const updateCompanySurvey = (dataObj, whereObj) => {
    //
    return DB_DRIVER.Update(Surveys, dataObj, whereObj);
};


/**
 * Get Specific Template Question
 * @param {Integer} templateId 
 * @param {Array} columns 
 * @returns
 */
const getDefaultTemplateInfo = (templateId, columns) => {
    //
    let SQL_QUERY = `
        SELECT ${columns !== undefined ? columns : '*'}
        FROM ${DefaultTemplates}
        WHERE sid = ${templateId};
    `;
    //
    return DB_DRIVER.RowArray(SQL_QUERY);
};

/**
 * 
 * @param {Object} dataObj 
 * @returns 
 */
const insertCompanySurveyQuestion = (dataObj) => {
    //
    return DB_DRIVER.Insert(SurveyQuestion, dataObj);
};


/**
 * 
 * @param {Object} dataObj 
 * @param {Object} whereObj 
 * @returns 
 */
const updateCompanySurveyQuestion = (dataObj, whereObj) => {
    //
    return DB_DRIVER.Update(SurveyQuestion, dataObj, whereObj);
};

// Export the Modal Methods
module.exports = {
    getDefaultTemplates,
    insertCompanySurvey,
    updateCompanySurvey,
    getDefaultTemplateInfo,
    insertCompanySurveyQuestion,
    updateCompanySurveyQuestion
};