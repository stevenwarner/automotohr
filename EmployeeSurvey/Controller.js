// Create router
const esrouter = require('express').Router();

/**
 * Get companys' bank account history
 * 
 * @returns JSON
 */
esrouter.get(
    '/:num',
    async(Request, Response) => {
        //
        console.log(__filename);
        console.log(__dirname);
        console.log(Request.params);
        console.log(Request.query);
        console.log(Request.body);
        //
        var events = require('events');
        var fs = require('fs');
        var eventEmitter = new events.EventEmitter();

        var ringbell = function(){
        	console.log("ring ring ring");
        	eventEmitter.emit("bellring","welcome");
        }

        eventEmitter.on("doorOpen",ringbell);
        eventEmitter.on("bellring",function(message){
        	console.log(message)
        });
        eventEmitter.emit("doorOpen",ringbell);
        //
        fs.readFile(__dirname+"/input.txt", function (err, data) {
        	if (err) {
        		console.log(err)
        	} else {
        		console.log("Async data is "+ data.toString());
        	}
        });

        var fileData = fs.readFileSync(__dirname+"/input.txt");
        console.log("Sync data is "+ fileData.toString());
        console.log("End of file system ");
        //
        var readableStream = fs.createReadStream(__dirname+"/input.txt");
        var newData = "";
        readableStream.setEncoding("UTF8");
        readableStream.on("data",function(chunk){
        	newData+=chunk;
        })
        readableStream.on("end",function(){
        	console.log(newData)
        })
        //
        var writeData = "pakistan zindabad";
        var writeableStream = fs.createWriteStream(__dirname+"/output.txt");
        writeableStream.write(writeData,"UTF8");
        writeableStream.end();
        writeableStream.on("finish",function(){
        	console.log("wtite complete")
        });
        //
        var newreadableStream = fs.createReadStream(__dirname+"/new.txt");
        var newwriteableStream = fs.createWriteStream(__dirname+"/very_new.txt");
        newreadableStream.pipe(newwriteableStream);

        //
        Response.send({
            status: true,
            response: "please Enjoy"
        });
    }
);

// Export the Router
module.exports = esrouter;