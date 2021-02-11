/**
 * Created by Adee on 9/13/2017.
 */
$(document).ready(function() {

    $('.choose-file').filestyle({
        text: ' Choose File',
        btnClass: 'btn-success',
        placeholder: "No file selected"
    });

    // Basic Table Responsive Script
    $('.basic-table').basictable({
        breakpoint: 767
    });

    // Accordion with plus/minus icon
    function toggleIcon(e) {
        $(e.target)
            .prev('.panel-heading')
            .find(".more-less")
            .toggleClass('glyphicon-plus glyphicon-minus');
    }
    $('.panel-group').on('hidden.bs.collapse', toggleIcon);
    $('.panel-group').on('shown.bs.collapse', toggleIcon);

});