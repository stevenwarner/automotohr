//
$(function(){
    $.ajaxSetup({
        headers: {
            Authorization: "Bearer " + apiAccessToken,
        },
    });
})
