<?php $method =  $this->router->fetch_method(); ?>
    <?php if(
        $method == 'login' ||
        $method == 'generate_password' ||
        $method == 'forgot_password' ||
        $method == 'change_password' ||
        $method == 'thankyou' ||
        $method == 'linked_expired'
    ) {

    } else { ?>
        <footer class="main-footer text-center <?php echo $this->uri->segment(2) == 'form_w9' ? 'ml-0' : ''; ?>">
            Powered by <a href="<?php echo base_url();?>"><img src="<?php echo base_url('assets/images/140X29-bottom-black.png');?>"></a>
        </footer>
    <?php } ?>

    <?php if(
            $method == 'login' ||
            $method == 'generate_password' ||
            $method == 'forgot_password' ||
            $method == 'change_password' ||
            $method == 'thankyou' ||
            $method == 'linked_expired'
    ) {
        $this->load->view('main/partials/scripts_login');
    } else {
        $this->load->view('main/partials/scripts_footer');
    } ?>
</div>
</body>
</html>