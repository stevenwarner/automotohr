    <?php if (!empty($session['company_detail']['Logo'])) { ?>
      <img src="<?php echo 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo']; ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
    <?php } ?>
    <?php if (!empty($session['company_detail']['CompanyName'])) { ?>
      <br> <?php echo $session['company_detail']['CompanyName']; ?>
      <?php if ($session['company_detail']['company_status'] == 0 && isPayrollOrPlus(true)) { ?>
        <label class="label label-danger" title="The store is closed." placement="top">
          Closed
        </label> <br>
      <?php } ?>
    <?php } ?>