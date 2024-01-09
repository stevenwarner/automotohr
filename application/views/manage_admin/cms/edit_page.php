<div class="main">
    <div class="container-fluid">
        <div class="row">		
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <?php $this->load->view('manage_admin/cms/pages/'.$page_data['slug']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(".js-from").select2();
    function resend(id) {
        alertify.dialog('confirm')
            .set({
                'title ': 'Confirmation',
                'labels': {ok: 'Yes', cancel: 'No'},
                'message': 'Are you sure you want to Resend this Email?',
                'onok': function () {
                    url = "<?= base_url('manage_admin/resend_email') ?>" + '/' + id;
                    window.location.href = url;
                },
                'oncancel': function () {
                    alertify.error('Cancelled!');
                }
            }).show();
    }


</script>
<style>
.full_width{
    width:100%;
}
</style>