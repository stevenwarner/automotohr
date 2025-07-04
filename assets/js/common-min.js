function loadTitles() { $('[title][placement="left"]').tooltip({ placement: "left auto", trigger: "hover" }), $('[title][placement="right"]').tooltip({ placement: "right auto", trigger: "hover" }), $('[title][placement="top"]').tooltip({ placement: "top auto", trigger: "hover" }), $("[title]").tooltip({ placement: "bottom auto", trigger: "hover" }) }

function get_document_extension(e) { var o = ""; return $.each({ ".aac": "audio/aac", ".abw": "application/x-abiword", ".arc": "application/x-freearc", ".avi": "video/x-msvideo", ".azw": "application/vnd.amazon.ebook", ".bin": "application/octet-stream", ".bmp": "image/bmp", ".bz": "application/x-bzip", ".bz2": "application", ".csh": "application/x-csh", ".css": "text/css", ".csv": "text/csv", ".doc": "application/msword", ".docx": "application/vnd.openxmlformats-officedocument.wordprocessingml.document", ".eot": "application/vnd.ms-fontobject", ".epub": "application/epub+zip", ".gz": "application/gzip", ".gif": "image/gif", ".html": "text/html", ".ico": "image/vnd.microsoft.icon", ".ics": "text/calendar", ".jar": "application/java-archive", ".jpeg": "image/jpeg", ".js": "text/javascript", ".json": "application/json", ".jsonld": "application/ld+json", ".mid": "audio/midi", ".mid": "audio/x-midi", ".mjs": "text/javascript", ".mp3": "audio/mpeg", ".mpeg": "video/mpeg", ".mpkg": "application/vnd.apple.installer+xml", ".odp": "application/vnd.oasis.opendocument.presentation", ".ods": "application/vnd.oasis.opendocument.spreadsheet", ".odt": "application/vnd.oasis.opendocument.text", ".oga": "audio/ogg", ".ogv": "video/ogg", ".ogx": "application/ogg", ".opus": "audio/opus", ".otf": "font/otf", ".png": "image/png", ".pdf": "application/pdf", ".php": "application/x-httpd-php", ".ppt": "application/vnd.ms-powerpoint", ".pptx": "application/vnd.openxmlformats-officedocument.presentationml.presentation", ".rar": "application/vnd.rar", ".rtf": "application/rtf", ".sh": "application/x-sh", ".svg": "image/svg+xml", ".swf": "application/x-shockwave-flash", ".tar": "application/x-tar", ".tif": "image/tiff", ".ts": "video/mp2t", ".ttf": "font/ttf", ".txt": "text/plain", ".vsd": "application/vnd.visio", ".wav": "audio/wav", ".weba": "audio/webm", ".webm": "video/webm", ".webp": "image/webp", ".woff": "font/woff", ".woff2": "font/woff2", ".xhtml": "application/xhtml+xml", ".xls": "application/vnd.ms-excel", ".xlsx": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", ".xml": "application/xml", ".xul": "application/vnd.mozilla.xul+xml", ".zip": "application/zip", ".3gp": "audio/3gpp", ".3g2": "audio/3gpp2", ".7z": "application/x-7z-compressed" }, (function(a, t) { t == e && (o = a) })), "" == o || null == o ? e : o }

function footer_fixer() {
    if (0 !== $(".csPageWrap").length) {
        var e = $(document).height() - $(".csPageWrap").height(),
            o = $("footer").height();
        $("footer").css("margin-top", e - o + "px")
    }
}
$(document).ready(loadTitles), $(document).on("click", ".jsHintBtn", (function(e) { e.preventDefault(), $('.jsHintBody[data-hint="' + $(this).data("target") + '"]').toggle() })), $(document).on("click", ".jsPageBTN", (function(e) { e.preventDefault(), $(this).toggleClass("fa-minus-circle"), $(this).toggleClass("fa-plus-circle"), $('.jsPageBody[data-page="' + $(this).data("target") + '"]').toggle() })), footer_fixer(), $(document).on("click", ".jsEmployeeQuickProfile", (function(e) {
    e.preventDefault();
    var o = $(this).data("id") || null;
    Model({ Id: "jsEmployeeQuickProfileModal", Loader: "jsEmployeeQuickProfileModalLoader", Title: "Employee Quick Profile View", Body: '<div class="container"><div id="jsEmployeeQuickProfileModalBody"></div></div>' }, (function() { GetAllEmployee(o, "jsEmployeeQuickProfileModal") }))
}));
var isXHRInProgress = null;

function GetAllEmployee(e, o) {
    null != isXHRInProgress && isXHRInProgress.abort(), isXHRInProgress = $.get(window.location.origin + "/get_all_company_employees").done((function(a) {
        if (isXHRInProgress = null, !1 === a.Status) return $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0), void $("#" + o + "Body").html(a.Msg);
        $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0);
        var t = "";
        t += '<div class="row">', t += '    <div class="col-sm-12">', t += "    <label><strong>Select Employee</strong></label>", t += '        <select id="' + o + 'Select">', t += '<option value="0">[Please Select An Employee]</option>', a.Data.length && a.Data.map((function(e) { t += '<option value="' + e.Id + '">' + e.Name + " " + e.Role + "</option>" })), t += "        </select>", t += "    </div>", t += "</div>", t += '<div id="' + o + 'MainBody"></div>', $("#" + o + "Body").html(t), $("#" + o + "Select").select2(), e && ($("#" + o + "Select").select2("val", e), $("#" + o + "Select").trigger("change"), GetEmployeeDetails(o)), $("#" + o + "Select").change((function() { GetEmployeeDetails(o) }))
    })).error((function(e) { $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0), isXHRInProgress = null, $("#" + o + "Body").html("Something went wrong while accessing the employee profile.") }))
}

function GetEmployeeDetails(e) {
    var o = $("#" + e + "Select").val();
    if (0 !== o) return null != isXHRInProgress && isXHRInProgress.abort(), $('.jsIPLoader[data-page="' + e + 'Loader"]').show(0), isXHRInProgress = $.get(window.location.origin + "/get_employee_profile/" + o).done((function(o) {
        if (isXHRInProgress = null, !1 === o.Status) return $('.jsIPLoader[data-page="' + e + 'Loader"]').hide(0), void $("#" + e + "MainBody").html(o.Msg);
        $('.jsIPLoader[data-page="' + e + 'Loader"]').hide(0), $("#" + e + "MainBody").html(o.Data)
    })).error((function(o) { isXHRInProgress = null, $("#" + e).html("Something went wrong while accessing the employee profile.") })), '<div id="' + e + '"><p class="text-center"><i class="fa fa-spinner fa-spin csF18 csB7" aria-hidden="true"></i></p></div>';
    $("#" + e + "MainBody").html("")
}

function Model(e, o) {
    var a = "";
    a += "\x3c!-- Custom Modal --\x3e", a += '<div class="csModal" id="' + e.Id + '">', a += '    <div class="container">', a += '        <div class="csModalHeader">', a += '            <h3 class="csModalHeaderTitle csF20 csB7">', a += e.Title, a += '                <span class="csModalButtonWrap">', a += void 0 !== e.Buttons && 0 !== e.Buttons.length ? e.Buttons.join("") : "", a += '                    <button class="btn btn-black btn-lg jsModalCancel csF16"><em class="fa fa-times-circle csF16"></em> ' + (e.Cancel ? e.Cancel : "Cancel") + "</button>", a += "                </span>", a += '                <div class="clearfix"></div>', a += "            </h3>", a += "        </div>", a += '        <div class="csModalBody">', a += '            <div class="csIPLoader jsIPLoader" data-page="' + e.Loader + '"><i class="fa fa-circle-o-notch fa-spin"></i></div>', a += e.Body, a += "        </div>", a += '        <div class="clearfix"></div>', a += "    </div>", a += "</div>", $(".csModal").remove(), $("body").append(a), $("#" + e.Id).fadeIn(300), $("body").css("overflow-y", "hidden"), $("#" + e.Id + " .csModalBody").css("top", $("#" + e.Id + " .csModalHeader").height() + 50), "function" == typeof o && o()
}
$(document).on("click", ".jsModalCancel", (e => { e.preventDefault(), null != $(e.target).data("ask") ? alertify.confirm("Any unsaved changes will be lost.", (() => { $(e.target).closest(".csModal").fadeOut(300), $("body").css("overflow-y", "auto") })).set("labels", { ok: "LEAVE", cancel: "NO, i WILL STAY" }).set("title", "Notice!") : ($(e.target).closest(".csModal").fadeOut(300), $(".csModal").remove(), $("body").css("overflow-y", "auto")) }));

function loadTitles() { $('[title][placement="left"]').tooltip({ placement: "left auto", trigger: "hover" }), $('[title][placement="right"]').tooltip({ placement: "right auto", trigger: "hover" }), $('[title][placement="top"]').tooltip({ placement: "top auto", trigger: "hover" }), $("[title]").tooltip({ placement: "bottom auto", trigger: "hover" }) }

function get_document_extension(e) { var o = ""; return $.each({ ".aac": "audio/aac", ".abw": "application/x-abiword", ".arc": "application/x-freearc", ".avi": "video/x-msvideo", ".azw": "application/vnd.amazon.ebook", ".bin": "application/octet-stream", ".bmp": "image/bmp", ".bz": "application/x-bzip", ".bz2": "application", ".csh": "application/x-csh", ".css": "text/css", ".csv": "text/csv", ".doc": "application/msword", ".docx": "application/vnd.openxmlformats-officedocument.wordprocessingml.document", ".eot": "application/vnd.ms-fontobject", ".epub": "application/epub+zip", ".gz": "application/gzip", ".gif": "image/gif", ".html": "text/html", ".ico": "image/vnd.microsoft.icon", ".ics": "text/calendar", ".jar": "application/java-archive", ".jpeg": "image/jpeg", ".js": "text/javascript", ".json": "application/json", ".jsonld": "application/ld+json", ".mid": "audio/midi", ".mid": "audio/x-midi", ".mjs": "text/javascript", ".mp3": "audio/mpeg", ".mpeg": "video/mpeg", ".mpkg": "application/vnd.apple.installer+xml", ".odp": "application/vnd.oasis.opendocument.presentation", ".ods": "application/vnd.oasis.opendocument.spreadsheet", ".odt": "application/vnd.oasis.opendocument.text", ".oga": "audio/ogg", ".ogv": "video/ogg", ".ogx": "application/ogg", ".opus": "audio/opus", ".otf": "font/otf", ".png": "image/png", ".pdf": "application/pdf", ".php": "application/x-httpd-php", ".ppt": "application/vnd.ms-powerpoint", ".pptx": ">application/vnd.openxmlformats-officedocument.presentationml.presentation", ".rar": "application/vnd.rar", ".rtf": "application/rtf", ".sh": "application/x-sh", ".svg": "image/svg+xml", ".swf": "application/x-shockwave-flash", ".tar": "application/x-tar", ".tif": "image/tiff", ".ts": "video/mp2t", ".ttf": "font/ttf", ".txt": "text/plain", ".vsd": "application/vnd.visio", ".wav": "audio/wav", ".weba": "audio/webm", ".webm": "video/webm", ".webp": "image/webp", ".woff": "font/woff", ".woff2": "font/woff2", ".xhtml": "application/xhtml+xml", ".xls": "application/vnd.ms-excel", ".xlsx": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", ".xml": "application/xml", ".xul": "application/vnd.mozilla.xul+xml", ".zip": "application/zip", ".3gp": "audio/3gpp", ".3g2": "audio/3gpp2", ".7z": "application/x-7z-compressed" }, (function(t, a) { a == e && (o = t) })), "" == o || null == o ? e : o }

function footer_fixer() {
    if (0 !== $(".csPageWrap").length) {
        var e = $(document).height() - $(".csPageWrap").height(),
            o = $("footer").height();
        $("footer").css("margin-top", e - o + "px")
    }
}
$(document).ready(loadTitles), $(document).on("click", ".jsHintBtn", (function(e) { e.preventDefault(), $('.jsHintBody[data-hint="' + $(this).data("target") + '"]').toggle() })), $(document).on("click", ".jsPageBTN", (function(e) { e.preventDefault(), $(this).toggleClass("fa-minus-circle"), $(this).toggleClass("fa-plus-circle"), $('.jsPageBody[data-page="' + $(this).data("target") + '"]').toggle() })), footer_fixer(), $(document).on("click", ".jsEmployeeQuickProfile", (function(e) {
    e.preventDefault();
    var o = $(this).data("id") || null;
    Model({ Id: "jsEmployeeQuickProfileModal", Loader: "jsEmployeeQuickProfileModalLoader", Title: "Employee Quick Profile View", Body: '<div class="container"><div id="jsEmployeeQuickProfileModalBody"></div></div>' }, (function() { GetAllEmployee(o, "jsEmployeeQuickProfileModal") }))
}));
var isXHRInProgress = null;

function GetAllEmployee(e, o) {
    null != isXHRInProgress && isXHRInProgress.abort(), isXHRInProgress = $.get(window.location.origin + "/get_all_company_employees").done((function(t) {
        if (isXHRInProgress = null, !1 === t.Status) return $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0), void $("#" + o + "Body").html(t.Msg);
        $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0);
        var a = "";
        a += '<div class="row">', a += '    <div class="col-sm-12">', a += "    <label><strong>Select Employee</strong></label>", a += '        <select id="' + o + 'Select">', a += '<option value="0">[Please Select An Employee]</option>', t.Data.length && t.Data.map((function(e) { a += '<option value="' + e.Id + '">' + e.Name + " " + e.Role + "</option>" })), a += "        </select>", a += "    </div>", a += "</div>", a += '<div id="' + o + 'MainBody"></div>', $("#" + o + "Body").html(a), $("#" + o + "Select").select2(), e && ($("#" + o + "Select").select2("val", e), $("#" + o + "Select").trigger("change"), GetEmployeeDetails(o)), $("#" + o + "Select").change((function() { GetEmployeeDetails(o) }))
    })).error((function(e) { $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0), isXHRInProgress = null, $("#" + o + "Body").html("Something went wrong while accessing the employee profile.") }))
}

function GetEmployeeDetails(e) {
    var o = $("#" + e + "Select").val();
    if (0 !== o) return null != isXHRInProgress && isXHRInProgress.abort(), $('.jsIPLoader[data-page="' + e + 'Loader"]').show(0), isXHRInProgress = $.get(window.location.origin + "/get_employee_profile/" + o).done((function(o) {
        if (isXHRInProgress = null, !1 === o.Status) return $('.jsIPLoader[data-page="' + e + 'Loader"]').hide(0), void $("#" + e + "MainBody").html(o.Msg);
        $('.jsIPLoader[data-page="' + e + 'Loader"]').hide(0), $("#" + e + "MainBody").html(o.Data)
    })).error((function(o) { isXHRInProgress = null, $("#" + e).html("Something went wrong while accessing the employee profile.") })), '<div id="' + e + '"><p class="text-center"><i class="fa fa-spinner fa-spin csF18 csB7" aria-hidden="true"></i></p></div>';
    $("#" + e + "MainBody").html("")
}

function Model(e, o) {
    var t = "";
    t += "\x3c!-- Custom Modal --\x3e", t += '<div class="csModal" id="' + e.Id + '">', t += '    <div class="container">', t += '        <div class="csModalHeader">', t += '            <h3 class="csModalHeaderTitle csF20 csB7">', t += e.Title, t += '                <span class="csModalButtonWrap">', t += void 0 !== e.Buttons && 0 !== e.Buttons.length ? e.Buttons.join("") : "", t += '                    <button class="btn btn-black btn-lg jsModalCancel csF16"><em class="fa fa-times-circle csF16"></em> ' + (e.Cancel ? e.Cancel : "Cancel") + "</button>", t += "                </span>", t += '                <div class="clearfix"></div>', t += "            </h3>", t += "        </div>", t += '        <div class="csModalBody">', t += '            <div class="csIPLoader jsIPLoader" data-page="' + e.Loader + '"><i class="fa fa-circle-o-notch fa-spin"></i></div>', t += e.Body, t += "        </div>", t += '        <div class="clearfix"></div>', t += "    </div>", t += "</div>", $(".csModal").remove(), $("body").append(t), $("#" + e.Id).fadeIn(300), $("body").css("overflow-y", "hidden"), $("#" + e.Id + " .csModalBody").css("top", $("#" + e.Id + " .csModalHeader").height() + 50), "function" == typeof o && o()
}

function debounce(e, o, t) {
    var a;
    return function() {
        var i = this,
            l = arguments,
            n = function() { a = null, t || e.apply(i, l) },
            s = t && !a;
        clearTimeout(a), a = setTimeout(n, o), s && e.apply(i, l)
    }
}

function setCaretPosition(e, o) {
    if (null != e)
        if (e.createTextRange) {
            var t = e.createTextRange();
            t.move("character", o), t.select()
        } else e.selectionStart ? (e.focus(), e.setSelectionRange(o, o)) : e.focus()
}

function numberFormat(e) { return e.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }
$(document).on("click", ".jsModalCancel", (e => { e.preventDefault(), null != $(e.target).data("ask") ? alertify.confirm("Any unsaved changes will be lost.", (() => { $(e.target).closest(".csModal").fadeOut(300), $("body").css("overflow-y", "auto") })).set("labels", { ok: "LEAVE", cancel: "NO, i WILL STAY" }).set("title", "Notice!") : ($(e.target).closest(".csModal").fadeOut(300), $(".csModal").remove(), $("body").css("overflow-y", "auto")) }));

function loadTitles() { $('[title][placement="left"]').tooltip({ placement: "left auto", trigger: "hover" }), $('[title][placement="right"]').tooltip({ placement: "right auto", trigger: "hover" }), $('[title][placement="top"]').tooltip({ placement: "top auto", trigger: "hover" }), $("[title]").tooltip({ placement: "bottom auto", trigger: "hover" }) }

function get_document_extension(e) { var o = ""; return $.each({ ".aac": "audio/aac", ".abw": "application/x-abiword", ".arc": "application/x-freearc", ".avi": "video/x-msvideo", ".azw": "application/vnd.amazon.ebook", ".bin": "application/octet-stream", ".bmp": "image/bmp", ".bz": "application/x-bzip", ".bz2": "application", ".csh": "application/x-csh", ".css": "text/css", ".csv": "text/csv", ".doc": "application/msword", ".docx": "application/vnd.openxmlformats-officedocument.wordprocessingml.document", ".eot": "application/vnd.ms-fontobject", ".epub": "application/epub+zip", ".gz": "application/gzip", ".gif": "image/gif", ".html": "text/html", ".ico": "image/vnd.microsoft.icon", ".ics": "text/calendar", ".jar": "application/java-archive", ".jpeg": "image/jpeg", ".js": "text/javascript", ".json": "application/json", ".jsonld": "application/ld+json", ".mid": "audio/midi", ".mid": "audio/x-midi", ".mjs": "text/javascript", ".mp3": "audio/mpeg", ".mpeg": "video/mpeg", ".mpkg": "application/vnd.apple.installer+xml", ".odp": "application/vnd.oasis.opendocument.presentation", ".ods": "application/vnd.oasis.opendocument.spreadsheet", ".odt": "application/vnd.oasis.opendocument.text", ".oga": "audio/ogg", ".ogv": "video/ogg", ".ogx": "application/ogg", ".opus": "audio/opus", ".otf": "font/otf", ".png": "image/png", ".pdf": "application/pdf", ".php": "application/x-httpd-php", ".ppt": "application/vnd.ms-powerpoint", ".pptx": ">application/vnd.openxmlformats-officedocument.presentationml.presentation", ".rar": "application/vnd.rar", ".rtf": "application/rtf", ".sh": "application/x-sh", ".svg": "image/svg+xml", ".swf": "application/x-shockwave-flash", ".tar": "application/x-tar", ".tif": "image/tiff", ".ts": "video/mp2t", ".ttf": "font/ttf", ".txt": "text/plain", ".vsd": "application/vnd.visio", ".wav": "audio/wav", ".weba": "audio/webm", ".webm": "video/webm", ".webp": "image/webp", ".woff": "font/woff", ".woff2": "font/woff2", ".xhtml": "application/xhtml+xml", ".xls": "application/vnd.ms-excel", ".xlsx": "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", ".xml": "application/xml", ".xul": "application/vnd.mozilla.xul+xml", ".zip": "application/zip", ".3gp": "audio/3gpp", ".3g2": "audio/3gpp2", ".7z": "application/x-7z-compressed" }, (function(t, a) { a == e && (o = t) })), "" == o || null == o ? e : o }

function footer_fixer() { if (0 !== $(".csPageWrap").length) { var e = $(document).height() - $(".csPageWrap").height(),
            o = $("footer").height();
        $("footer").css("margin-top", e - o + "px") } }
$(document).ready(loadTitles), $(document).on("click", ".jsHintBtn", (function(e) { e.preventDefault(), $('.jsHintBody[data-hint="' + $(this).data("target") + '"]').toggle() })), $(document).on("click", ".jsPageBTN", (function(e) { e.preventDefault(), $(this).toggleClass("fa-minus-circle"), $(this).toggleClass("fa-plus-circle"), $('.jsPageBody[data-page="' + $(this).data("target") + '"]').toggle() })), footer_fixer(), $(document).on("click", ".jsEmployeeQuickProfile", (function(e) { e.preventDefault(); var o = $(this).data("id") || null;
    Model({ Id: "jsEmployeeQuickProfileModal", Loader: "jsEmployeeQuickProfileModalLoader", Title: "Employee Quick Profile View", Body: '<div class="container"><div id="jsEmployeeQuickProfileModalBody"></div></div>' }, (function() { GetAllEmployee(o, "jsEmployeeQuickProfileModal") })) }));
var isXHRInProgress = null;

function GetAllEmployee(e, o) { null != isXHRInProgress && isXHRInProgress.abort(), isXHRInProgress = $.get(window.location.origin + "/get_all_company_employees").done((function(t) { if (isXHRInProgress = null, !1 === t.Status) return $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0), void $("#" + o + "Body").html(t.Msg);
        $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0); var a = "";
        a += '<div class="row">', a += '    <div class="col-sm-12">', a += "    <label><strong>Select Employee</strong></label>", a += '        <select id="' + o + 'Select">', a += '<option value="0">[Please Select An Employee]</option>', t.Data.length && t.Data.map((function(e) { a += '<option value="' + e.Id + '">' + e.Name + " " + e.Role + "</option>" })), a += "        </select>", a += "    </div>", a += "</div>", a += '<div id="' + o + 'MainBody"></div>', $("#" + o + "Body").html(a), $("#" + o + "Select").select2(), e && ($("#" + o + "Select").select2("val", e), $("#" + o + "Select").trigger("change"), GetEmployeeDetails(o)), $("#" + o + "Select").change((function() { GetEmployeeDetails(o) })) })).error((function(e) { $('.jsIPLoader[data-page="' + o + 'Loader"]').hide(0), isXHRInProgress = null, $("#" + o + "Body").html("Something went wrong while accessing the employee profile.") })) }

function GetEmployeeDetails(e) { var o = $("#" + e + "Select").val(); if (0 !== o) return null != isXHRInProgress && isXHRInProgress.abort(), $('.jsIPLoader[data-page="' + e + 'Loader"]').show(0), isXHRInProgress = $.get(window.location.origin + "/get_employee_profile/" + o).done((function(o) { if (isXHRInProgress = null, !1 === o.Status) return $('.jsIPLoader[data-page="' + e + 'Loader"]').hide(0), void $("#" + e + "MainBody").html(o.Msg);
        $('.jsIPLoader[data-page="' + e + 'Loader"]').hide(0), $("#" + e + "MainBody").html(o.Data) })).error((function(o) { isXHRInProgress = null, $("#" + e).html("Something went wrong while accessing the employee profile.") })), '<div id="' + e + '"><p class="text-center"><i class="fa fa-spinner fa-spin csF18 csB7" aria-hidden="true"></i></p></div>';
    $("#" + e + "MainBody").html("") }

function Model(e, o) { var t = "";
    t += "\x3c!-- Custom Modal --\x3e", t += '<div class="csModal" id="' + e.Id + '">', t += '    <div class="container">', t += '        <div class="csModalHeader">', t += '            <h3 class="csModalHeaderTitle csF20 csB7">', t += e.Title, t += '                <span class="csModalButtonWrap">', t += void 0 !== e.Buttons && 0 !== e.Buttons.length ? e.Buttons.join("") : "", t += '                    <button class="btn btn-black btn-lg jsModalCancel csF16"><em class="fa fa-times-circle csF16"></em> ' + (e.Cancel ? e.Cancel : "Cancel") + "</button>", t += "                </span>", t += '                <div class="clearfix"></div>', t += "            </h3>", t += "        </div>", t += '        <div class="csModalBody">', t += '            <div class="csIPLoader jsIPLoader" data-page="' + e.Loader + '"><i class="fa fa-circle-o-notch fa-spin"></i></div>', t += e.Body, t += "        </div>", t += '        <div class="clearfix"></div>', t += "    </div>", t += "</div>", $(".csModal").remove(), $("body").append(t), $("#" + e.Id).fadeIn(300), $("body").css("overflow-y", "hidden"), $("#" + e.Id + " .csModalBody").css("top", $("#" + e.Id + " .csModalHeader").height() + 50), "function" == typeof o && o() }

function debounce(e, o, t) { var a; return function() { var i = this,
            l = arguments,
            n = function() { a = null, t || e.apply(i, l) },
            s = t && !a;
        clearTimeout(a), a = setTimeout(n, o), s && e.apply(i, l) } }

function setCaretPosition(e, o) { if (null != e)
        if (e.createTextRange) { var t = e.createTextRange();
            t.move("character", o), t.select() } else e.selectionStart ? (e.focus(), e.setSelectionRange(o, o)) : e.focus() }

function numberFormat(e) { return e.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") }
$(document).on("click", ".jsModalCancel", (e => { e.preventDefault(), null != $(e.target).data("ask") ? alertify.confirm("Any unsaved changes will be lost.", (() => { $(e.target).closest(".csModal").fadeOut(300), $("body").css("overflow-y", "auto") })).set("labels", { ok: "LEAVE", cancel: "NO, i WILL STAY" }).set("title", "Notice!") : ($(e.target).closest(".csModal").fadeOut(300), $(".csModal").remove(), $("body").css("overflow-y", "auto")) }));