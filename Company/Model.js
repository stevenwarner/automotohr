//
const DB = require('../Database/Driver');
//
const TABLES = require('../Tables');

//
const GetStates = (columns) => {
    //
    let query = `
        SELECT ${columns === undefined ? '*' : columns.join(',')} 
        FROM ${TABLES.States}
        ORDER BY state_name ASC;
    `;
    //
    return DB.ResultArray(query);
};

//
module.exports = {
    GetStates
}