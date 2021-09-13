//
function loadTitles() {
    $('[title][placement="left"]').tooltip({ placement: 'left auto', trigger: "hover" });
    $('[title][placement="right"]').tooltip({ placement: 'right auto', trigger: "hover" });
    $('[title][placement="top"]').tooltip({ placement: 'top auto', trigger: "hover" });
    $('[title]').tooltip({ placement: 'bottom auto', trigger: "hover" });
}


$(document).ready(loadTitles);


function get_document_extension(type) {
    var listFormats = { ".aac": "audio/aac", ".abw": "application/x-abiword", ".arc": "application/x-freearc", ".avi": "video/x-msvideo", ".azw": "application/vnd.amazon.ebook", ".bin": "application/octet-stream", ".bmp": "image/bmp", ".bz": "application/x-bzip", ".bz2": "application", ".csh": "application/x-csh", ".css": "text/css", ".csv": "text/csv", ".doc": "application/msword", ".docx": "application/vnd.openxmlformats-officedocument.wordprocessingml.document", ".eot": "application/vnd.ms-fontobject", ".epub": "application/epub+zip", ".gz": "application/gzip", ".gif": "image/gif", ".html": "text/html", ".ico": "image/vnd.microsoft.icon", ".ics": "text/calendar", ".jar": "application/java-archive", ".jpeg": "image/jpeg", ".js": "text/javascript", ".json": "application/json", ".jsonld": "application/ld+json", ".mid": "audio/midi", ".mid": "audio/x-midi", ".mjs": "text/javascript", ".mp3": "audio/mpeg", ".mpeg": "video/mpeg", ".mpkg": "application/vnd.apple.installer+xml", ".odp": "application/vnd.oasis.opendocument.presentation", ".ods": "application/vnd.oasis.opendocument.spreadsheet", ".odt": "application/vnd.oasis.opendocument.text", ".oga": "audio/ogg", ".ogv": "video/ogg", ".ogx": "application/ogg", ".opus": "audio/opus", ".otf": "font/otf", ".png": "image/png", ".pdf": "application/pdf", ".php": "application/x-httpd-php", ".ppt": "application/vnd.ms-powerpoint", ".pptx": ">application/vnd.openxmlformats-officedocument.presentationml.presentation", ".rar": "application/vnd.rar", ".rtf": "application/rtf", ".sh": "application/x-sh", ".svg": "image/svg+xml", ".swf": "application/x-shockwave-flash", ".tar": "application/x-tar", ".tif": "image/tiff", ".ts": "video/mp2t", ".ttf": "font/ttf", ".txt": "text/plain", ".vsd": "application/vnd.visio", ".wav": "audio/wav", ".weba": "audio/webm", ".webm": "video/webm", ".webp": "image/webp", ".woff": "font/woff", ".woff2": "font/woff2", ".xhtml": "application/xhtml+xml", ".xls": "application/vnd.ms-excel", ".xlsx": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", ".xml": "application/xml", ".xul": "application/vnd.mozilla.xul+xml", ".zip": "application/zip", ".3gp": "audio/3gpp", ".3g2": "audio/3gpp2", ".7z": "application/x-7z-compressed" };
    var extension = '';

    $.each(listFormats, function(i, v) {
        if (v == type) {
            extension = i;
        }
    });

    if (extension == '' || extension == undefined) {
        return type;
    } else {
        return extension;
    }
}

//
$(document).on('click', '.jsHintBtn', function(event) {
    //
    event.preventDefault();
    //
    $('.jsHintBody[data-hint="' + ($(this).data('target')) + '"]').toggle();

});
// 
$(document).on('click', '.jsPageBTN', function(event) {

    //
    event.preventDefault();
    //
    $(this).toggleClass('fa-minus-circle');
    $(this).toggleClass('fa-plus-circle');
    //
    $('.jsPageBody[data-page="' + ($(this).data('target')) + '"]').toggle();

});

footer_fixer();
//
function footer_fixer() {
    //
    if ($('.csPageWrap').length === 0) {
        return;
    }
    var wh = $(document).height() - $('.csPageWrap').height();
    var fh = $('footer').height();
    $('footer').css('margin-top', (wh - fh) + 'px')
}

// 
$(document).on('click', '.jsEmployeeQuickProfile', function(event) {
    //
    event.preventDefault();
    //
    var employeeId = $(this).data('id') || null;
    //
    Model({
        Id: "jsEmployeeQuickProfileModal",
        Loader: 'jsEmployeeQuickProfileModalLoader',
        Title: 'Employee Quick Profile View',
        Body: '<div class="container"><div id="jsEmployeeQuickProfileModalBody"></div></div>'
    }, function() {
        GetAllEmployee(employeeId, 'jsEmployeeQuickProfileModal');
    });
});

var isXHRInProgress = null;


function GetAllEmployee(
    employeeId,
    id
) {
    //
    if (isXHRInProgress != null) {
        isXHRInProgress.abort();
    }
    //
    isXHRInProgress =
        $.get(window.location.origin + '/get_all_company_employees')
        .done(function(resp) {
            //
            isXHRInProgress = null;
            //
            if (resp.Status === false) {
                $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
                $('#' + id + 'Body').html(resp.Msg);
                return;
            }
            $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
            //
            var html = '';
            //
            html += '<div class="row">';
            html += '    <div class="col-sm-12">';
            html += '    <label><strong>Select Employee</strong></label>';
            html += '        <select id="' + (id) + 'Select">';
            html += '<option value="0">[Please Select An Employee]</option>';
            //
            if (resp.Data.length) {
                resp.Data.map(function(emp) {
                    html += '<option value="' + (emp.Id) + '">' + (emp.Name) + ' ' + (emp.Role) + '</option>';
                });
            }
            html += '        </select>';
            html += '    </div>';
            html += '</div>';
            html += '<div id="' + (id) + 'MainBody"></div>';
            //
            $('#' + id + 'Body').html(html);
            $('#' + id + 'Select').select2();
            //
            if (employeeId) {
                $('#' + id + 'Select').select2('val', employeeId);
                $('#' + id + 'Select').trigger('change');
                //
                GetEmployeeDetails(id);
            }
            //
            $('#' + id + 'Select').change(function() {
                GetEmployeeDetails(id);
            });
        })
        .error(function(err) {
            $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
            //
            isXHRInProgress = null;
            $('#' + id + 'Body').html('Something went wrong while accessing the employee profile.');
        });
}

function GetEmployeeDetails(
    id
) {
    //
    var employeeId = $('#' + id + 'Select').val();
    //
    if (employeeId === 0) {
        // flush view
        $('#' + id + 'MainBody').html('');
        return;
    }
    //
    if (isXHRInProgress != null) {
        isXHRInProgress.abort();
    }
    $('.jsIPLoader[data-page="' + (id) + 'Loader"]').show(0);
    //
    isXHRInProgress =
        $.get(window.location.origin + '/get_employee_profile/' + employeeId)
        .done(function(resp) {
            //
            isXHRInProgress = null;
            //
            if (resp.Status === false) {
                $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
                $('#' + id + 'MainBody').html(resp.Msg);
                return;
            }
            $('.jsIPLoader[data-page="' + (id) + 'Loader"]').hide(0);
            //
            $('#' + id + 'MainBody').html(resp.Data);
        })
        .error(function(err) {
            //
            isXHRInProgress = null;
            $('#' + id).html('Something went wrong while accessing the employee profile.');
        });
    //
    return '<div id="' + (id) + '"><p class="text-center"><i class="fa fa-spinner fa-spin csF18 csB7" aria-hidden="true"></i></p></div>';
}


/**
 * Click
 * 
 * Triggers when page modal closes
 * 
 * @param  {Object} e
 * @return {Void}
 */
$(document).on('click', '.jsModalCancel', (e) => {
    //
    e.preventDefault();
    //
    if ($(e.target).data('ask') != undefined) {
        //
        alertify.confirm(
            'Any unsaved changes will be lost.',
            () => {
                //
                $(e.target).closest('.csModal').fadeOut(300);
                //
                $('body').css('overflow-y', 'auto');
            }
        ).set('labels', {
            ok: 'LEAVE',
            cancel: 'NO, i WILL STAY'
        }).set(
            'title', 'Notice!'
        );
    } else {
        //
        $(e.target).closest('.csModal').fadeOut(300);
        //
        $('.csModal').remove();
        //
        $('body').css('overflow-y', 'auto');
    }
});

/**
 * Modal page
 * 
 * @param   {Object}   options 
 * @param   {Function} cb 
 * @returns {Void}
 */
function Model(options, cb) {
    //
    var html = '';
    html += '<!-- Custom Modal -->';
    html += '<div class="csModal" id="' + (options.Id) + '">';
    html += '    <div class="container">';
    html += '        <div class="csModalHeader">';
    html += '            <h3 class="csModalHeaderTitle csF20 csB7">';
    html += options.Title;
    html += '                <span class="csModalButtonWrap">';
    html += options.Buttons !== undefined && options.Buttons.length !== 0 ? options.Buttons.join('') : '';
    html += '                    <button class="btn btn-black btn-lg jsModalCancel csF16"><em class="fa fa-times-circle csF16"></em> ' + (options.Cancel ? options.Cancel : 'Cancel') + '</button>';
    html += '                </span>';
    html += '                <div class="clearfix"></div>';
    html += '            </h3>';
    html += '        </div>';
    html += '        <div class="csModalBody">';
    html += '            <div class="csIPLoader jsIPLoader" data-page="' + (options.Loader) + '"><i class="fa fa-circle-o-notch fa-spin"></i></div>';
    html += options.Body;
    html += '        </div>';
    html += '        <div class="clearfix"></div>';
    html += '    </div>';
    html += '</div>';
    //
    $('.csModal').remove();
    $('body').append(html);
    $("#" + (options.Id) + "").fadeIn(300);
    //
    $('body').css('overflow-y', 'hidden');
    $("#" + (options.Id) + " .csModalBody").css('top', $("#" + (options.Id) + " .csModalHeader").height() + 50);
    if (typeof(cb) === 'function') cb();
}

// http://davidwalsh.name/javascript-debounce-function
function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this,
            args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

//
function setCaretPosition(elem, caretPos) {
    if (elem != null) {
        if (elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', caretPos);
            range.select();
        } else {
            if (elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            } else
                elem.focus();
        }
    }
}

function numberFormat(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

//
$('.jsSectionTrigger').click(function(event) {
    //
    event.preventDefault();
    //
    $(this).toggleClass('fa-minus-circle');
    $(this).toggleClass('fa-plus-circle');
    //
    $('.jsSectionBody[data-id="' + ($(this).data('target')) + '"]').toggleClass('dn');
});