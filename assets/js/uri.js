/**
 * Get the base url for the current page
 * @param {string} to 
 * @returns
 */
function baseURL(to) {
    //
    return window.location.origin + '/' + (to === undefined ? '' : to);
}

/**
 * Get the segment from the URL
 * @param {number} segment
 * @returns 
 */
function getSegment(segment) {
    //
    let segmentArray = window.location.pathname.split('/');
    //
    segmentArray = segmentArray && segmentArray.filter(function(single_segment) {
        //
        return single_segment === '' ? false : single_segment.trim();
    });
    //
    return segmentArray[segment] !== undefined ? segmentArray[segment] : segmentArray;
}