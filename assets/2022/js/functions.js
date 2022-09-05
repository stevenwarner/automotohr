/*
 SelectNav.js (v. 0.1)
 Converts your <ul>/<ol> navigation into a dropdown list for small screens
 https://github.com/lukaszfiszer/selectnav.js
*/
window.selectnav=function(){return function(p,q){var a,h=function(b){var c;b||(b=window.event);b.target?c=b.target:b.srcElement&&(c=b.srcElement);3===c.nodeType&&(c=c.parentNode);c.value&&(window.location.href=c.value)},k=function(b){b=b.nodeName.toLowerCase();return"ul"===b||"ol"===b},l=function(b){for(var c=1;document.getElementById("selectnav"+c);c++);return b?"selectnav"+c:"selectnav"+(c-1)},n=function(b){g++;var c=b.children.length,a="",d="",f=g-1;if(c){if(f){for(;f--;)d+=r;d+=" "}for(f=0;f<
c;f++){var e=b.children[f].children[0];if("undefined"!==typeof e){var h=e.innerText||e.textContent,i="";j&&(i=-1!==e.className.search(j)||-1!==e.parentElement.className.search(j)?m:"");s&&!i&&(i=e.href===document.URL?m:"");a+='<option value="'+e.href+'" '+i+">"+d+h+"</option>";t&&(e=b.children[f].children[1])&&k(e)&&(a+=n(e))}}1===g&&o&&(a='<option value="">'+o+"</option>"+a);1===g&&(a='<select class="selectnav" id="'+l(!0)+'">'+a+"</select>");g--;return a}};if((a=document.getElementById(p))&&k(a)){document.documentElement.className+=
" js";var d=q||{},j=d.activeclass||"active",s="boolean"===typeof d.autoselect?d.autoselect:!0,t="boolean"===typeof d.nested?d.nested:!0,r=d.indent||"\u2192",o=d.label||"- Navigation -",g=0,m=" selected ";a.insertAdjacentHTML("afterend",n(a));a=document.getElementById(l());a.addEventListener&&a.addEventListener("change",h);a.attachEvent&&a.attachEvent("onchange",h)}}}();

$(document).ready(function(){
    /*//Disable Autocomplete
    $('input,select,textarea').each(function () {
        $(this).attr('autocomplete', 'off');
    });*/

    selectnav('menus', {
      label: 'Navigation',
      nested: true,
      indent: '-'
    }); 


    $(".cart-button").click(function(){
      $(".outter-view-cart").slideToggle();
      $(".outer-cart-overlay").slideToggle();
    }); 

    $(".outer-cart-overlay").click(function(){
      $(this).slideToggle();
      $(".outter-view-cart").slideToggle();
    }); 
	
        
    $(".inner-cart").click(function(){
      $(".inner-cart-view").slideToggle();
      $(".inner-cart-overlay").slideToggle();
    }); 
    $(".inner-cart-overlay").click(function(){
      $(this).slideToggle();
      $(".inner-cart-view").slideToggle();
    });


    $("img.info").click(function(){  
      $(".info-detail").toggle();
    });

    $("#close").click(function(){  
      $(".info-detail").hide();
    });

    $('.notifications-btn, .bg-overlay').click(function(){
      $('.notification-wrp').animate('slow', function(){
        if ($(this).is(':visible')) {
          $('.notification-wrp').toggleClass('closed opened');
        }
      });
    });

    // Keep Active tab open
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('#tabs a[href="' + activeTab + '"]').tab('show');
    }

    var url = window.location;
    $('ul.nav-tabs a[href="'+ url +'"]').parent().addClass('active');

    $('ul.nav-tabs a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');


    // Equal height Grid
    ResetGridColumns();

    $(window).resize(function() {
        ResetGridColumns();
    });

   
    $(window).keydown(function(event){
        //console.log(event.target.tagName);
        var tagName = event.target.tagName;

        if(event.keyCode == 13 && tagName != 'TEXTAREA') {
            event.preventDefault();
            return false;
        }
    });
    
    // Tooltip Function
    $('[data-toggle="tooltip"]').tooltip();

    $('[data-toggle=popover]').popover({
        trigger:"click"
    });

    $('[data-toggle=popover]').on('click', function (e) {
        $('[data-toggle=popover]').not(this).popover('hide');
    });
    
    $('.choose-file').filestyle({
       text: ' Choose File',
       btnClass: 'btn-success',
       placeholder: "No file selected"
   });

}).trigger('resize');

window.onload = function(){
    ResetGridColumns();
};

function ResetGridColumns() {
    $('.grid-columns').each(function() {

        // find all columns
        var $cs = $(this).children('[class*="col-"]');

        // reset the height
        $cs.css('height', 'auto');

        // set the heights per row
        var rowWidth = $(this).width();
        var $curCols = $();
        var curMax = 0;
        var curWidth = 0;
        $cs.each(function() {
            var w = $(this).width();
            var h = $(this).height();
            if(curWidth+w <= rowWidth) {
                $curCols = $curCols.add(this);
                curWidth+= w;
                if(h>curMax) curMax = h;
            } else {
                if($curCols.length>1) $curCols.css('height', curMax+'px');
                $curCols = $(this);
                curWidth = w;
                curMax = h;
            }
        });
        if($curCols.length>1) $curCols.css('height', curMax+'px');

    });
}

function func_convert_form_to_json_object(form_id) {
    var form_data = $('#' + form_id).serializeArray();

    var my_return = {};

    $.each(form_data, function () {
        if (my_return[this.name] !== undefined) {
            if (!my_return[this.name].push) {
                my_return[this.name] = [my_return[this.name]];
            }
            my_return[this.name].push(this.value || '');
        } else {
            my_return[this.name] = this.value || '';
        }
    });

    return my_return;
}

Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};