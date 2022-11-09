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
const InsertCompanySurvey = (dataObj) => {
    //
    return DB_DRIVER.Insert(Surveys, dataObj);
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
const InsertCompanySurveyQuestion = (dataObj) => {
    //
    return DB_DRIVER.Insert(SurveyQuestion, dataObj);
};


// Export the Modal Methods
module.exports = {
    getDefaultTemplates,
    InsertCompanySurvey,
    getDefaultTemplateInfo,
    InsertCompanySurveyQuestion
};