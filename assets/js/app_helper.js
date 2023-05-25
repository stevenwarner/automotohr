/**
 * Validate email address
 * @returns 
 */
String.prototype.verifyEmail = function () {
    return this.match(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g) === null ? false : true;
}