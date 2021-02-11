<!-- Main Start -->
<div class="main-content">
	<div class="container">
		<div class="row">
			<div class="applicant-profile-wrp">
				<div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
					<script type="text/javascript">
					$(document).ready(function(){
					  $(".tab_content").hide();
					  $(".tab_content:first").show(); 

					  $("ul.tabs li").click(function() {
					    $("ul.tabs li").removeClass("active");
					    $(this).addClass("active");
					    $(".tab_content").hide();
					    var activeTab = $(this).attr("rel"); 
					    $("#"+activeTab).fadeIn(); 
					  });
					  
					});
					</script>
					<div class="application-header">
						<article>
							<figure><img src="<?= base_url() ?>assets/images/applican-img.jpg"></figure>
							<div class="text">
								<h2>Peter Carry</h2>
								<span>Applicant</span>
							</div>
						</article>
					</div>
					<div class="tabs-wrp">					
						<ul class="tabs"> 
					        <li class="active" rel="tab1"><a href="javascript:;">Personal Info</a></li>
					        <li rel="tab2"><a href="javascript:;">Questionnaire</a></li>
					        <li rel="tab3"><a href="javascript:;">Notes</a></li>
					        <li rel="tab4"><a href="javascript:;">Messages</a></li>
					        <li rel="tab5"><a href="javascript:;">Calendar</a></li>
					    </ul>
						<div class="tab_container">
						     <div id="tab1" class="tab_content">
						     	<div class="universal-form-style-v2">
									<ul>
										<form>
											<div class="form-title-section">
												<h2>Personal Information</h2>
												<div class="form-btns">
													<input type="submit" value="edit">
												</div>												
											</div>
											<li class="form-col-50-left">
												<label>frist name:</label>
												<p>Peter</p>
											</li>
											<li class="form-col-50-right">
												<label>last name:</label>
												<p>Carry</p>
											</li>								
											<li class="form-col-50-left">
												<label>email:</label>
												<p>pcarry@egenienext.com</p>
											</li>
											<li class="form-col-50-right">
												<label>phone number:</label>
												<p>0000-000 00000</p>
											</li>
											<li class="form-col-50-left">
												<label>address:</label>
												<p>Proin ornare quam tortor</p>
											</li>
											<li class="form-col-50-right">
												<label>city:</label>
												<p>Lahore</p>
											</li>
											<li class="form-col-50-left">
												<label>date applied:</label>
												<p>11-16-2015</p>
											</li>
											<li class="form-col-50-right">
												<label>zipcode:</label>
												<p>54000</p>
											</li>
											<li class="form-col-50-left">
												<label>country:</label>	
												<p>Canada</p>							
											</li>
											<li class="form-col-50-right">	
												<label>state:</label>									
												<p>Alberta</p>								
											</li>
										</form>
									</ul>
								</div>
								<div class="applicant-video">
									<iframe src="http://player.vimeo.com/video/57564747" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
								</div>					         
						     </div><!-- #tab1 -->
						     <div id="tab2" class="tab_content">
						      	tab2				       
						     </div><!-- #tab2 --> 
						     <div id="tab3" class="tab_content">
						      	tab3				       
						     </div><!-- #tab3 --> 
						     <div id="tab4" class="tab_content">
						      	tab4				       
						     </div><!-- #tab4 --> 
						     <div id="tab5" class="tab_content">
						      	tab5				       
						     </div><!-- #tab5 --> 
						 </div>	
					 </div>				
				</div>
				<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
					
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Main End -->