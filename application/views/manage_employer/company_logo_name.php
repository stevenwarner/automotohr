    <?php if (!empty($session['company_detail']['Logo'])) { ?>
        <img src="<?php echo 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo']; ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
    <?php } ?>
    <?php if (!empty($session['company_detail']['CompanyName'])) { ?>
      <br>  <?php echo $session['company_detail']['CompanyName']; ?>
        <?php if($session['company_detail']['company_status']==0 && $session["employer_detail"]["access_level_plus"]==1){?>&nbsp;<span class="btn-danger" style="padding-left: 10px;padding-right: 10px;padding-bottom: 1px; margin-top: 10px; pointer-events: none;" >Closed</span> <br> <?php }?>
    <?php } ?>

    