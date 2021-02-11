$(document).ready(function () {

    ResetGridColumns();

    // Equal height Grid
    $(window).resize(function () {
        ResetGridColumns();
    });


    window.onload = function () {
        ResetGridColumns();
    }

    $(window).keydown(function (event) {
        //console.log(event.target.tagName);
        var tagName = event.target.tagName;

        if (event.keyCode == 13 && tagName != 'TEXTAREA') {
            event.preventDefault();
            return false;
        }
    });

    // Tooltip Function
    $('[data-toggle="tooltip"]').tooltip();

    function ResetGridColumns() {
        $('.grid-columns').each(function () {

            // find all columns
            var $cs = $(this).children('[class*="col-"]');

            // reset the height
            $cs.css('height', 'auto');

            // set the heights per row
            var rowWidth = $(this).width();
            var $curCols = $();
            var curMax = 0;
            var curWidth = 0;
            $cs.each(function () {
                var w = $(this).width();
                var h = $(this).height();
                if (curWidth + w <= rowWidth) {
                    $curCols = $curCols.add(this);
                    curWidth += w;
                    if (h > curMax) curMax = h;
                } else {
                    if ($curCols.length > 1) $curCols.css('height', curMax + 'px');
                    $curCols = $(this);
                    curWidth = w;
                    curMax = h;
                }
            });
            if ($curCols.length > 1) $curCols.css('height', curMax + 'px');

        });
    }

});

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

