<!-- Main Start -->
<div class="main-content">
	<div class="container">
		<div class="row">					
			<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
				<header class="hr-header-sec"></header>
			</div>
			<div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">				
				<div class="hr-left-nav">
					<ul>						
						<li class="active"><a href="javascript:;">Personal Details</a></li>						
						<li><a href="javascript:;">Login</a></li>
						<li><a href="javascript:;">Payment Methods</a></li>
					</ul>
				</div>
			</div>
			<div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
				<div class="universal-form-style">
					<ul>
						<form>
							<li class="form-col-50-left">
								<label>frist name</label>
								<input class="invoice-fields" type="text" name="fname">
							</li>
							<li class="form-col-50-right">
								<label>last name</label>
								<input class="invoice-fields" type="text" name="lname">
							</li>
							<li class="form-col-100">
								<label>last name</label>
								<input class="invoice-fields" type="text" name="address">
							</li>
							<li class="form-col-50-left">
								<label>city</label>
								<input class="invoice-fields" type="text" name="city">
							</li>
							<li class="form-col-50-right">
								<label>zip code</label>
								<input class="invoice-fields" type="text" name="zipcode">
							</li>
							<li class="form-col-50-left">
								<label>country</label>								
								<div class="hr-select-dropdown">
									<select class="invoice-fields">
										<option>Pakistan</option>
										<option>India</option>
										<option>Canada</option>
										<option>Japan</option>
										<option>USA</option>
									</select>
								</div>								
							</li>
							<li class="form-col-50-right">	
								<label>state</label>									
								<div class="hr-select-dropdown">
									<select class="invoice-fields">
										<option>Punjab</option>
										<option>California</option>
										<option>London</option>
									</select>
								</div>								
							</li>
							<li class="form-col-100">
								<label>position</label>
								<input class="invoice-fields" type="text" name="position">
							</li>
							<li class="form-col-75-left">	
								<label>phone</label>									
								<div class="hr-select-dropdown">
									<select class="invoice-fields">
										<option>united state (+1)</option>
										<option>united state (+1)</option>
										<option>united state (+1)</option>
										<option>united state (+1)</option>
									</select>
								</div>								
							</li>
							<li class="form-col-25-right">	
								<label></label>									
								<input class="invoice-fields" type="text" name="position">								
							</li>
							<li class="form-col-75-left">	
								<label>cell</label>									
								<div class="hr-select-dropdown">
									<select class="invoice-fields">
										<option>united state (+1)</option>
										<option>united state (+1)</option>
										<option>united state (+1)</option>
										<option>united state (+1)</option>
									</select>
								</div>								
							</li>
							<li class="form-col-25-right">	
								<label></label>									
								<input class="invoice-fields" type="text" name="position">								
							</li>
							<li class="form-col-75-left">	
								<label>fax</label>									
								<div class="hr-select-dropdown">
									<select class="invoice-fields">
										<option>united state (+1)</option>
										<option>united state (+1)</option>
										<option>united state (+1)</option>
										<option>united state (+1)</option>
									</select>
								</div>								
							</li>
							<li class="form-col-25-right">	
								<label></label>									
								<input class="invoice-fields" type="text" name="position">								
							</li>
							<li class="form-col-100">
								<div class="checkbox-field">
									<div class="checkbox-wrap">
                                      <input type="checkbox" value="None" id="squaredFour1" name="check" checked="">
                                      <label for="squaredFour1"></label>
                                    </div>
									<p>Show my name and social profile on job ads I post</p>
								</div>
							</li>
							<li class="form-col-100">
								<div class="btn-wrp">
									<input class="reg-btn" type="submit" value="save">
								</div>
							</li>
						</form>
					</ul>
				</div>
			</div>
			<div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
				<p class="image-preview-text">Photo Preview</p> 
                <div class="profile-iamge">
                    <img src="<?= base_url() ?>assets/images/profile-image.png" class="image-logo">    
                </div>
                <div class="upload-btn">
                    <div class="btn-inner">
                        <input type="file" class="choose-file-filed"/> 
                        <a class="select-photo" href="">upload photo</a>
                    </div>                                                    
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Main End -->