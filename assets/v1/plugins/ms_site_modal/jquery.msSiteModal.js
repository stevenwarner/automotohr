(function ($) {
    let modalCounter = 0;

    $.msSiteModal = function (options) {
        const settings = $.extend({
            title: 'Modal Title',
            onOpen: function () { },
            onClose: function () { }
        }, options);

        modalCounter++;
        const modalId = 'msSiteModal-' + modalCounter;
        const backdropId = modalId + '-backdrop';

        const modalHTML = `
            <div id="${backdropId}" class="msSiteModalBackdrop"></div>
                <div id="${modalId}" class="msSiteModal">
                    <div class="loader">
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-12 col-xs-12 text-right">
                                <button type="button" class="btn btn-black pull-left" id="${modalId}-closeBtn">
                                    <i class="fa fa-times-circle"></i>    
                                    Close
                                </button>
                                <div class="jsButtonsArea"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('body').append(modalHTML);

        const $modal = $('#' + modalId);
        const $backdrop = $('#' + backdropId);

        // ✅ ONLY close on button press now
        $('#' + modalId + '-closeBtn').on('click', () => instance.close());

        const instance = {
            id: modalId,
            open: function () {
                $modal.addClass('open');
                $backdrop.fadeIn(200);
                settings.onOpen.call($modal[0]);
                $("body").css("overflow-y", "hidden");
                return this;
            },
            close: function () {
                $modal.removeClass('open');
                $backdrop.fadeOut(200);
                settings.onClose.call($modal[0]);
                $("body").css("overflow-y", "auto");
                $backdrop.remove();
                $modal.remove();
                return this;
            },
            closeModal: function () {
                this.close();
                return this;
            },
            setContent: function (html) {
                $modal.find('.modal-body').html(html).show();
                const contentWidth = $modal.find('.modal-body')[0].scrollWidth;
                $modal.css('width', contentWidth + 30); // padding
                return this;
            },
            loader: function (show) {
                if (show) {
                    $modal.find('.loader').show();
                    $modal.find('.modal-body').hide();
                } else {
                    $modal.find('.loader').hide();
                    $modal.find('.modal-body').show();
                }
                return this;
            },
            destroy: function () {
                $modal.remove();
                $backdrop.remove();
                return null;
            },
            setButtons: function (buttons) {
                const $footer = $modal.find('.modal-footer').find('.jsButtonsArea');
                $footer.html(buttons);
                return this;
            }
        };

        return instance;
    };
})(jQuery);
