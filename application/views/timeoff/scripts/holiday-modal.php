<?php
    $iconList = array();
    for($i = 1; $i <= 28; $i++){
        $n = $i.'.png';
        $iconList[$n] = $n; 
    }
?>
<script src="<?=base_url("assets/js/moment.min.js")?>"></script>
<link rel="stylesheet" href="<?=base_url('assets');?>/css/timeoffstyle.css">
<!-- Modal -->
<div class="modal fade" id="js-holiday-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content model-content-custom">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="js-edit-modal-header">Holiday Icons</h4>
            </div>
            <div class="modal-body full-width modal-body-custom">
                <?php foreach ($iconList as $key => $value) { ?>
                    <!--  -->
                    <div class="col-sm-2">
                        <div class="cs-icon-box js-icon-select">
                            <img src="<?=base_url('assets/images/holidays');?>/<?=$value?>" data-id="<?=$value;?>" alt="<?=$value?>" />                        
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <input type="hidden" value="add" id="js-holiday-icon-type" />
                <button type="submit" class="btn btn-success btn-rounded" id="js-modal-save-icon">Save</button>
                <button type="button" class="btn btn-default btn-rounded" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
  </div>
</div>

<style>
    .cs-icon-box{
        background-color: #000; border-radius: 3px; height: 50px; margin-bottom: 10px; padding-top: 10px;
    }
    .cs-icon-box.active{
        background-color: #81b314;
    }
    .cs-icon-box img{
        display: block; margin: auto; max-width: 100%;
    }
</style>

<script>
    $('.js-icon-select').click(function(){
        $('.js-icon-select').removeClass('active');
        $(this).addClass('active');
    });

    $('#js-modal-save-icon').click(function(){
        if($('.js-icon-select.active').length != 0){
            let type = $('#js-holiday-icon-type').val();
            //
            $('#js-icon-plc-'+( type )+'').prop('src', $('.js-icon-select.active').find('img').prop('src'));
            $('#js-icon-plc-box-'+( type )+'').removeClass('hidden');
            $('#js-holiday-icon-'+( type )+'').val($('.js-icon-select.active').find('img').data('id'));
        }

        $('#js-holiday-modal').modal('hide');
    });
</script>