<style>
    .loader{ position: absolute; top: 0; right: 0; bottom: 0; left: 0; width: 100%; background: rgba(255,255,255,.8); z-index: 9999 !important; }
    .loader i{ text-align: center; top: 50%; left: 50%; font-size: 40px; position: relative; }
</style>

<script>
	function loadIframe(
        url, 
        target, 
        doLoad,
        loader
    ){
        if(doLoad !== undefined){
            //
            $('.js-inner-loader-iframe').remove();
            //
            if(loader === undefined){
            	$(target).parent().append('<div class="loader js-inner-loader-iframe"><i class="fa fa-spinner fa-spin"></i></div>');
            }
            // 
            setTimeout(() => {
                loadIframe(
                    url,
                    target,
                    undefined,
                    loader
                );
            }, 2000);

            return;
        }

        try {
            if($(target).contents()[0].body.text == ''){
                $(target).prop('src', url);
                setTimeout(function(){
                    loadIframe(url, target, undefined, loader);
                }, 2000);
            }
        } catch(e) {
            $('.js-inner-loader-iframe').remove();
            $(loader).hide(0);
            console.log('Iframe is loaded.');
        }
    }
</script>