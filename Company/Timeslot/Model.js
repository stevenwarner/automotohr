//
const DB = require('../../Database/Driver');
//
const TABLES = require('../../Tables');
//
const GetTimeslots = (companyId, columns) => {
    //
    let query = `
        SELECT ${columns === undefined ? '*' : columns.join(',')} 
        FROM ${TABLES.CompanyTimeslots} 
        WHERE company_sid = ${companyId}
        ORDER BY sid DESC;
    `;
    //
    return DB.ResultArray(query);
};

//
const GetSingleTimeslot = (companyId, timeslotId, columns) => {
    //
    let query = `
        SELECT ${columns === undefined ? '*' : columns.join(',')} 
        FROM ${TABLES.CompanyTimeslots} 
        WHERE company_sid = ${companyId} 
        AND sid = ${timeslotId};
    `;
    //
    return DB.RowArray(query);
};

//
const InsertTimeslot = (data) => {
    //
    return DB.Insert(
        TABLES.CompanyTimeslots,
        data
    );
};

//
const UpdateTimeslot = (data, timeslotId) => {
    //
    return DB.Update(
        TABLES.CompanyTimeslots,
        data, [`sid = ${timeslotId}`]
    );
};
//
module.exports = {
    GetTimeslots,
    GetSingleTimeslot,
    InsertTimeslot,
    UpdateTimeslot
}