$(document).ready(function() {
    // Accordion with plus/minus icon
    function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);


    // ToolTip
    $('[data-toggle="tooltip"]').tooltip();


    // File Select
    $('.choose-file').filestyle({
        text: 'Browse...',
        btnClass: 'btn-green',
        placeholder: "No file selected"
    });

});