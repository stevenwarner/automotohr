// Timeoff common functions

$('.js-step').fadeOut(300);
$(`.js-step[data-step="1"]`).fadeIn(300);

$(document).on('click', '.js-to-step', function(e){
    //
    e.preventDefault();
    //
    let step = $(this).closest('div').data('step'),
    proceed = true;
    //
    if( step == 9 ){
        if( !stepSaved(1) ){ step = 1; proceed = false; }
        else if( !stepSaved(2) ){ step = 2; proceed = false; }
        else if( !stepSaved(3) ){ step = 3; proceed = false; }
        else if( !stepSaved(4) ){ step = 4; proceed = false; }
        else if( !stepSaved(5) ){ step = 5; proceed = false; }
        else if( !stepSaved(6) ){ step = 6; proceed = false; }
        else if( !stepSaved(7) ){ step = 7; proceed = false; }
        else if( !stepSaved(8) ){ step = 8; proceed = false; }
        else{
            stepSaved(9);
        }
    }
    //
    if(proceed === true && step == 9){
        callFinish();
        return;
    }
    //
    $('.js-step-tab').parent('li').removeClass('active');
    $(`.js-step-tab[data-step="${ step }"]`).parent('li').addClass('active');
    //
    $('.js-step').fadeOut(0);
    $(`.js-step[data-step="${ step }"]`).fadeIn(300);
    //
});


$(document).on('click', '.js-to-step-back', function(e){
    //
    e.preventDefault();
    //
    let step = $(this).closest('div').data('step');
    //
    $('.js-step-tab').parent('li').removeClass('active');
    $(`.js-step-tab[data-step="${step - 2}"]`).parent('li').addClass('active');
    //
    $('.js-step').fadeOut(0);
    $(`.js-step[data-step="${step - 2}"]`).fadeIn(300);
});


$(document).on('click', '.js-step-tab', function(e){
    //
    e.preventDefault();
    //
    let step = $(this).data('step'),
    proceed = true;
    //
    if( step == 9 ){
        if( !stepSaved(1) ){ step = 1; proceed = false; }
        else if( !stepSaved(2) ){ step = 2; proceed = false; }
        else if( !stepSaved(3) ){ step = 3; proceed = false; }
        else if( !stepSaved(4) ){ step = 4; proceed = false; }
        else if( !stepSaved(5) ){ step = 5; proceed = false; }
        else if( !stepSaved(6) ){ step = 6; proceed = false; }
        else if( !stepSaved(7) ){ step = 7; proceed = false; }
        else if( !stepSaved(8) ){ step = 8; proceed = false; }
        else{
            stepSaved(9);
        }
    }
    //
    if(proceed === true && step == 9){
        callFinish();
        return;
    }
    //
    $('.js-step-tab').parent('li').removeClass('active');
    $(this).parent('li').addClass('active');
    //
    $('.js-step').fadeOut(0);
    $(`.js-step[data-step="${ step }"]`).fadeIn(300);
});



// Helpers

// Mobile menu
$('.csMobile span i').click(function(e){ $('.csVertical').toggle(); });


// Loader
function ml(doShow, p){
    //
    p = p === undefined ? `.jsIPLoader` : `.jsIPLoader[data-page="${p}"]`;
    //
    if(doShow === undefined || doShow === false) $(p).hide();
    else $(p).show();
}