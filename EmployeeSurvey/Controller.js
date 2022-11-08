// Create router
const router = require('express').Router();
//
// Include Company Modal
const {
    getDefaultTemplates
} = require('./Modal.js');

/**
 * Get AutomotHR Default Survey Templates
 * 
 * @returns JSON
 */
router.get(
    '/:num/templates',
    async(Request, Response) => {
        //
        console.log(Request.params.num);
        //


        //
        Response.send({
            status: true,
            response: "please Enjoy"
        });
    }
);


/**
 * Get AutomotHR Default Survey Templates
 * 
 * @returns JSON
 */
router.get(
    '/:num/templates',
    async(Request, Response) => {
        //
        console.log(Request.params.num);
        //


        //
        Response.send({
            status: true,
            response: "please Enjoy"
        });
    }
);

// Export the Router
module.exports = router;