/**
 *  Fixes the footer position
 * @returns 
 */
function footerFixer() {
    //
    if ($('.csPageWrap').length === 0) {
        return;
    }
    //
    let wh = $(document).height() - $('.csPageWrap').height();
    let fh = $('footer').height();
    $('footer').css('margin-top', (wh - fh) + 'px');
}

function loadTitles(){
    $('[placement="top"]').tooltip();
}

/**
 * Google translation script
 */
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'de,es,fr,pt,it,zh-CN,zh-TW',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}


// Call the footer fixer
footerFixer();
loadTitles();


$(function App() {
    const targets = {
        count: $('#js-notification-count'),
        box: $('#js-notification-box')
    };



    loadNotifications();

    function loadNotifications() {
        $.get(window.location.origin+"/notifications/get_notifications", function(resp) {
            //
            if (resp.Status === false) {
                console.log(resp.Response);
                targets.count.parent().find('i').removeClass('faa-shake animated');
                return;
            }
            //
            var rows = '';
            resp.Data.map(function(v) {
                rows += '<li>';
                rows += '    <a href="' + (v['link']) + '">';
                rows += '        <span class="pull-left">' + (v['title']) + ' <b>(' + (v['count']) + ')</b></span>';
                rows += '        <span class="pull-right"><i class="fa fa-eye"></i></span>';
                rows += '    </a>';
                rows += '</li>';
            });
            //
            targets.count.text(resp.Data.length);
            targets.box.prepend(rows);
            targets.count.parent().find('i').addClass('faa-shake animated');
        });
    }
});



