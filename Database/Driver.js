//
const mysql = require('mysql');
//
const DB = mysql.createPool({
    connectionLimit: process.env.DB_POOL,
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE,
});

// Get Single Record
const RowArray = (query) => {
    return new Promise((res, rej) => {
        //
        DB.query(
            query,
            (err, results) => {
                //
                if (err) {
                    return res(err.sqlMessage);
                }
                return res(results[0]);
            }
        );
    });
};

// Get Multiple
const ResultArray = (query) => {
    return new Promise((res, rej) => {
        //
        DB.query(
            query,
            (err, results) => {
                //
                if (err) {
                    return res(err.sqlMessage);
                }
                return res(results);
            }
        );
    });
};

// Insert
const Insert = (table, dataOBJ) => {
    return new Promise((res, rej) => {
        //
        let query = `INSERT INTO ${table} (`
            //
        Object.keys(dataOBJ).map((column) => {
            query += "`" + (column) + "`,";
        });
        //
        query = query.replace(/,$/, '') + ') VALUES (';
        //
        for (let value in dataOBJ) {
            query += '"' + (dataOBJ[value]) + '",';
        };
        //
        query = query.replace(/,$/, '') + ');';
        //
        DB.query(
            query,
            (err, results) => {
                //
                if (err) {
                    return res(err.sqlMessage);
                }
                return res(results.insertId);
            }
        );
    });
};

// Update
const Update = (table, dataOBJ, whereOBJ) => {
    return new Promise((res, rej) => {
        //
        let query = `UPDATE ${table} SET `
            //
        for (let value in dataOBJ) {
            query += '`' + (value) + '` = "' + (dataOBJ[value]) + '",';
        };
        //
        query = query.replace(/,$/, '') + ' WHERE ';
        //
        whereOBJ.map((where) => {
            //
            query += ` ${where} `;
        });
        //
        DB.query(
            query,
            (err, results) => {
                //
                if (err) {
                    return res(err.sqlMessage);
                }
                //
                return res(results.affectedRows);
            }
        );
    });
};

// Delete
const Delete = (table, whereOBJ) => {
    return new Promise((res, rej) => {
        //
        let query = `DELETE FROM ${table} WHERE `
            //
        whereOBJ.map((where) => {
            //
            query += ` ${where} `;
        });
        //
        DB.query(
            query,
            (err, results) => {
                //
                if (err) {
                    return res(err.sqlMessage);
                }
                return res(results.affectedRows);
            }
        );
    });
};

//
module.exports = {
    RowArray,
    ResultArray,
    Insert,
    Update,
    Delete
};