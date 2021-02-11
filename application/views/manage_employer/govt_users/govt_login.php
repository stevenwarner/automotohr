<div class="cs-main" style="min-height: 600px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="registered-user">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <form action="<?= base_url('govt_login/'.$en_company_id) ?>" method="post" id="loginForm">
                        <div class="form-group">
                            <label for="username" class="text-left" style="width: 100%;">Username</label>                                
                            <input type="text" name="username" value="<?= set_value('username') ?>" class="form-control form-control-lg ">
                            <?php echo form_error('username', '<span class="cs-error">', '</span>'); ?>
                        </div>
                        <div class="form-group">
                            <label for="password" class="text-left" style="width: 100%;">Password</label>                                
                            <input type="password" name="password" value="" class="form-control form-control-lg">
                            <?php echo form_error('password', '<span class="cs-error">', '</span>'); ?>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" value="Login" class="form-control btn btn-success">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .registered-user{
        background-color: #f1f1f1;
        margin-top: 50px;
        padding: 5px !important;
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -o-border-radius: 3px;
    }
    .registered-user form{
        padding: 10px !important;
    }
    .error_message{
        padding: 0;
        text-align: left;
    }
    .cs-error{ font-weight: 900; color: #cc0000; }
</style>