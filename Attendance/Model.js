//
const DB = require('../Database/Driver');


//
const getResult = () =>{
    //
    var query = "SELECT sid FROM users limit 2;";
    //
    return DB.ResultArray(query);
}

//

module.exports =  {
    getResult
}
