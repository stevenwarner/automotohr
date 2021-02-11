

<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
            	<!-- Side Menu -->
            	<?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
            	<div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
	                <div class="dashboard-content">
	                	<div class="dash-inner-block">
	                		<div class="row">
	                			<!-- Block heading -->
	                			<div class="col-sm-12">
                    				<div class="heading-title page-title">
                                    	<h1 class="page-title"><i class="fa fa-envelope"></i>Job Feed URL</h1>
                                        <a class="btn black-btn pull-right" href="<?=base_url('manage_admin/dashboard');?>"><i class="fa fa-long-arrow-left"></i>Dashboard</a>
                                    </div>
                                </div>
                                <!-- SMS display area -->
                                <div class="col-sm-12" style="margin: 30px 0 0;">
                                    <label>Job Feed Iframe:</label>
                                    <div class="js-code-box">
                                        <button type="button" class="btn btn-default" id="js-copy" name="button">Copy Iframe</button>
                                        <code class="form-control" style="
                                        height: auto;
                                        padding: 20px;
                                        background-color: #222222;
                                        color: #ffffff;
                                        font-size: 20px;
                                        word-wrap: break-word;
                                        " id="js-textarea">
                                        <?=htmlentities('<iframe');?>
                                        <br />&nbsp;&nbsp;
                                        <?=htmlentities('src="'.$JobFeedURL.'"');?>
                                        <br />&nbsp;&nbsp;
                                        <?=htmlentities('width="100%"');?>
                                        <br />&nbsp;&nbsp;
                                        <?=htmlentities('height="100%"');?>
                                        <br />&nbsp;&nbsp;
                                        <?=htmlentities('frameborder="0">');?>
                                        <br />
                                        <?=htmlentities('</iframe>');?>
                                    </code>
                                    </div>
                                </div>
	                		</div>
	                	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    document.getElementById('js-copy').addEventListener('click', () => {
        copyToClipboard(document.getElementById('js-textarea').innerText);
    });

    const copyToClipboard = str => {
        console.log(str);
      const el = document.createElement('textarea');  // Create a <textarea> element
      el.value = str;                                 // Set its value to the string that you want copied
      el.setAttribute('readonly', '');                // Make it readonly to be tamper-proof
      el.style.position = 'absolute';
      el.style.left = '-9999px';                      // Move outside the screen to make it invisible
      document.body.appendChild(el);                  // Append the <textarea> element to the HTML document
      const selected =
        document.getSelection().rangeCount > 0        // Check if there is any content selected previously
          ? document.getSelection().getRangeAt(0)     // Store selection if found
          : false;                                    // Mark as false to know no selection existed before
      el.select();                                    // Select the <textarea> content
      document.execCommand('copy');                   // Copy - only works as a result of a user action (e.g. click events)
      document.body.removeChild(el);                  // Remove the <textarea> element
      if (selected) {                                 // If a selection existed before copying
        document.getSelection().removeAllRanges();    // Unselect everything on the HTML document
        document.getSelection().addRange(selected);   // Restore the original selection
      }
      alertify.success('Iframe is copied.');
    };
</script>


<style media="screen">
    .js-code-box{ position: relative; }
    .js-code-box button{ position: absolute; right: 20px; top: 20px; }
</style>
