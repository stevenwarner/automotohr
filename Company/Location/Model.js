//
const DB = require('../../Database/Driver');
//
const TABLES = require('../../Tables');
//
const GetCompanyLocations = (companyId, columns) => {
    //
    let query = `
        SELECT ${columns === undefined ? '*' : columns.join(',')} 
        FROM ${TABLES.CompanyLocations} 
        WHERE company_sid = ${companyId}
        ORDER BY sid DESC;
    `;
    //
    return DB.ResultArray(query);
};
//
const GetSingleCompanyLocations = (companyId, locationId, columns) => {
    //
    let query = `
        SELECT ${columns === undefined ? '*' : columns.join(',')} 
        FROM ${TABLES.CompanyLocations} 
        WHERE company_sid = ${companyId} 
        AND sid = ${locationId};
    `;
    //
    return DB.RowArray(query);
};
//
const InsertCompanyLocation = (data) => {
    //
    return DB.Insert(
        TABLES.CompanyLocations,
        data
    );
};
//
const UpdateCompanyLocation = (data, locationId) => {
    //
    return DB.Update(
        TABLES.CompanyLocations,
        data, [`sid = ${locationId}`]
    );
};


//
module.exports = {
    GetCompanyLocations,
    GetSingleCompanyLocations,
    InsertCompanyLocation,
    UpdateCompanyLocation
}