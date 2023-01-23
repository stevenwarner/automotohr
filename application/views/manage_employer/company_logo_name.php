            <?php if(!empty($session['company_detail']['Logo'])) { ?>
            <img src="<?php echo 'https://automotohrattachments.s3.amazonaws.com/'.$session['company_detail']['Logo'] ?>" style="width: 75px; height: 75px;" class="img-rounded"><br>
            <?php } ?>
            <?php if(!empty($session['company_detail']['CompanyName'])) { ?>
            <?php echo $session['company_detail']['CompanyName']; ?><br>
            <?php } ?>
