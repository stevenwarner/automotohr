/**
 * @author: Mubashir Ahmed
 * @version: 1.0
 * @package: File uploader (mFileUploader)
 * @description: Upload files using drag an drop / choose from file.
 *               It depends on jQuery.
 * @example:
 */

(function($) {
    //
    var instances = {};
    //
    $.fn.mVideo = function(opt) {
        // Save the current instance
        var _this = this.length > 1 ? this[0] : this;
        //
        if (typeof opt === 'string') {
            if (opt == 'get') {
                return instances[_this.selector];
            }
            return;
        }
        //
        var
            oFile = {},
            //
            options = {};
        //
        instances[_this.selector] = oFile;
        //
        options['id'] = opt !== undefined && opt.id || Math.ceil(Math.random() * 1000);
        options['onSuccess'] = opt !== undefined && opt.onSuccess || function() {};
        options['onError'] = opt !== undefined && opt.onError || function() {};
        options['onClear'] = opt !== undefined && opt.onClear || function() {};

        //
        var setMainView = function() {
            //
            $(_this).html(generateHTML());
            //
            $('.jsVideoUploaderCommon').hide(0);
            //
            $('#jsVideoUploaderFile' + (options['id']) + '').mFileUploader({
                fileLimit: '4MB',
                allowedTypes: ['mp4'],
                text: 'Click / Drag to upload',
                onSuccess: function(file) {
                    instances[_this.selector] = oFile = file;
                },
                onError: function(errorCode) {
                    instances[_this.selector]['hasError'] =
                        oFile['hasError'] = errorCode;
                },
                onClear: function() {
                    instances[_this.selector] = oFile = {};
                }
            });
            //
            if (opt.preview !== undefined) {
                if (opt.preview.type == 'youtube' || opt.preview.type == 'vimeo') {
                    $('.jsVideoUploaderPreview div').html('<iframe src="' + (opt.preview.url) + '" style="width: 100%"></iframe>');
                }
            }
        };

        //
        var generateHTML = function() {
            var rows = '<!--  -->';
            rows += '<div class="jsVideoUploader" data-id="' + (options['id']) + '">';
            rows += '    <div class="row">';
            rows += '        <div class="jsVideoUploaderOptions">';
            rows += '            <div class="col-sm-12 col-xs-12">';
            rows += '                <label class="control control--radio">';
            rows += '                    <input type="radio" class="jsVideoOption' + (options['id']) + '" value="youtube" name="jsVideoOption' + (options['id']) + '" /> Youtube&nbsp;';
            rows += '                    <div class="control__indicator"></div>';
            rows += '                </label>';
            rows += '                <label class="control control--radio">';
            rows += '                    <input type="radio" class="jsVideoOption' + (options['id']) + '" value="vimeo" name="jsVideoOption' + (options['id']) + '" /> Vimeo&nbsp;';
            rows += '                    <div class="control__indicator"></div>';
            rows += '                </label>';
            rows += '                <label class="control control--radio">';
            rows += '                    <input type="radio" class="jsVideoOption' + (options['id']) + '" value="upload" name="jsVideoOption' + (options['id']) + '" /> Upload&nbsp;';
            rows += '                    <div class="control__indicator"></div>';
            rows += '                </label>';
            rows += '            </div>';
            rows += '        </div>';
            rows += '        <div class="col-sm-12 col-xs-12 ma10">';
            rows += '            <div class="jsVideoUploaderCommon jsVideoUploaderYoutube">';
            rows += '                <label>Youtube URL <span class="csRequired"></span></label>';
            rows += '                <input type="text" class="form-control jsVideoUploaderYoutubeInput' + (options['id']) + '" placeholder="https://www.youtube.com?watch=23131232" />';
            rows += '            </div>';
            rows += '            <div class="jsVideoUploaderCommon jsVideoUploaderVimeo">';
            rows += '                <label>Vimeo URL <span class="csRequired"></span></label>';
            rows += '                <input type="text" class="form-control jsVideoUploaderVimeoInput' + (options['id']) + '" placeholder="https://www.vimeo.com?watch=23131232" />';
            rows += '            </div>';
            rows += '            <div class="jsVideoUploaderCommon jsVideoUploaderCustom jsVideoUploaderCustom' + (options['id']) + '">';
            rows += '                <input type="file" id="jsVideoUploaderFile' + (options['id']) + '" style="display: none" />';
            rows += '            </div>';
            rows += '        </div>';
            rows += '        <div class="jsVideoUploaderPreview">';
            rows += '            <div class="col-sm-12 col-xs-12">';
            rows += '            </div>';
            rows += '        </div>';
            rows += '    </div>';
            rows += '</div>';
            //
            return rows;
        }

        //
        $(document).on('click', '.jsVideoOption' + (options['id']) + '', function() {
            //
            $('.jsVideoUploaderCommon').hide(0);
            //
            instances[_this.selector] = oFile = {};
            //
            if ($(this).val() === 'youtube') {
                //
                $('.jsVideoUploaderYoutube').show();
                $('.jsVideoUploaderYoutubeInput' + (options['id']) + '').trigger('keyup');
            } else if ($(this).val() === 'vimeo') {
                //
                $('.jsVideoUploaderVimeo').show();
                $('.jsVideoUploaderVimeoInput' + (options['id']) + '').trigger('keyup');
            } else {
                //
                $('.jsVideoUploaderCustom').show();
                var tmp = $('#jsVideoUploaderFile' + (options['id']) + '').mFileUploader('get');
                //
                if (tmp.hasError) {
                    instances[_this.selector]['hasError'] = oFile['hasError'] = tmp['errorCode'];
                } else {
                    instances[_this.selector] = oFile = tmp;
                }
            }
        });

        //
        $(document).on('keyup', '.jsVideoUploaderYoutubeInput' + (options['id']) + '', function() {
            //
            var input = $(this).val().trim();
            //
            $('.jsVideoError').remove();
            //
            if (input.match(/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/gm) === null) {
                $(this).css('border-color', 'red');
                $(this).after('<p class="jsVideoError text-danger csB7">Youtube link is not valid.</p>')
                instances[_this.selector] = oFile = {};
            } else {
                $(this).css('border-color', 'green');
                //
                oFile['video_type'] = 'youtube';
                oFile['video_url'] = input;
                //
                instances[_this.selector] = oFile;
            }
        });

        //
        $(document).on('keyup', '.jsVideoUploaderVimeoInput' + (options['id']) + '', function() {
            //
            var input = $(this).val().trim();
            //
            $('.jsVideoError').remove();
            //
            if (input.match(/(http|https)?:\/\/(www\.|player\.)?vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|video\/|)(\d+)(?:|\/\?)/gm) === null) {
                $(this).css('border-color', 'red');
                $(this).after('<p class="jsVideoError text-danger csB7">Vimeo link is not valid.</p>')
                instances[_this.selector] = oFile = {};
            } else {
                $(this).css('border-color', 'green');
                //
                oFile['video_type'] = 'vimeo';
                oFile['video_url'] = input;
                //
                instances[_this.selector] = oFile;
            }
        });

        //
        setMainView();

        return this;
    };

    //
    $.fn.mVideo.__proto__.instances = instances;
})(jQuery || $);