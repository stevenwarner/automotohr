<div class="js-page-partial" id="js-offer-letter-area-edit">	
	<div class="row">
		<div class="col-sm-12">
			<!-- 1 -->
			<div class="form-group">
				<label>Template Type <span class="cs-required">*</span></label>
				<br />
				<label class="control control--radio">
					<input type="radio" class="js-template-type-edit" name="js-template-type" value="uploaded" data-type="offer_letter_edit" /> Uploaded &nbsp;
					<div class="control__indicator"></div>
				</label>
				<label class="control control--radio">
					<input type="radio" class="js-template-type-edit" name="js-template-type" value="generated" data-type="offer_letter_edit" /> Generated &nbsp;
					<div class="control__indicator"></div>
				</label>

				<hr />
			</div>
			<!-- 1 -->
			<div class="form-group">
				<label>Template Name <span class="cs-required">*</span></label>
				<input type="text" class="form-control" id="js-template-name-edit" />
				<p class="cs-error js-error"></p>
			</div>
			<!-- 2 -->
			<div class="form-group js-for-generated">
				<label>Template Letter Body <span class="cs-required">*</span></label>
				<div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link', ['show' => false]); ?></div>
				<textarea id="js-template-body-edit"></textarea>
			</div>
			<!-- 2 -->
			<div class="form-group js-for-uploaded">
				<label>Instructions / Guidance</label>
				<div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link', ['show' => false]); ?></div>
				<textarea id="js-template-guidence-edit"></textarea>
				<p class="cs-error js-error"></p>
			</div>
			<!-- 3 -->
			<div class="form-group js-for-uploaded">
				<label>Browse Document<span class="staric">*</span></label>
                <div class="upload-file" style="height: 46px; border: 1px solid #ccc;">
                    <input type="file" name="document" id="js-offer-letter-file-add" required />
	                <div id="remove_image" class="profile-picture">
	                    <a href="javascript:;" class="action-btn js-show-current-document">
	                        <i class="fa fa-lightbulb-o fa-2x"></i>
	                        <span class="btn-tooltip">View Current Document</span>
	                    </a>
	                </div>
                    <p class="cs-error" style="padding: 5px;padding-left: 55px;">Allowed formats (doc, docx, xls, xlsx, pdf)</p>
                    <p class="name_document"></p>
                    <a href="javascript:;">Choose File</a>
                </div>
                <p class="cs-error js-error"></p>
			</div>
			<!-- 3 -->
			<div class="form-group">
				<label>Acknowledgment Required</label>
				<div class="hr-select-dropdown">
					<select id="js-template-acknowledgment-edit" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<p class="help-text">Enable the Acknowledgment Requirement, if you need a confirmation that a Document has been received by the Employee or Onboarding Candidate.</p>
			</div>
			<!-- 4 -->
			<div class="form-group">
				<label>Download Required</label>
				<div class="hr-select-dropdown">
					<select id="js-template-download-edit" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<p class="help-text">Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.</p>
			</div>
			<!-- 5 -->
			<div class="form-group">
				<label>Signature Required</label>
				<div class="hr-select-dropdown">
					<select id="js-template-signature-edit" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<p class="help-text">Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.</p>
			</div>
			<!-- 6 -->
			<div class="form-group">
				<label>Sort Order</label>
				<input type="text" class="form-control" id="js-template-sort-order-edit" />
			</div>
			<!-- 7 -->
			<div class="form-group js-for-generated">
				<label>Authorized Management Signers</label>
				<select id="js-template-signers-edit" multiple="true">
					<!-- <option value="all">All</option> -->
					<?php 
						if(sizeof($managers_list)){
							foreach ($managers_list as $k => $v) {
								echo '<option value="'.( $v['sid'] ).'">'.( remakeEmployeeName( $v ) ).'</option>';
							}
						}
					?>
				</select>
			</div>
			<!-- 9 -->
			<div class="form-group">
				<label>Is this document required?</label>
				<p class="help-text">If marked yes, then the applicant needs to complete this document to complete the onboarding process.</p>
				<label class="control control--radio">
					<input type="radio" class="js-template-required-edit" name="js-template-required-edit" value="0" /> No &nbsp;
					<div class="control__indicator"></div>
				</label>
				<label class="control control--radio">
					<input type="radio"  class="js-template-required-edit" name="js-template-required-edit" value="1" /> Yes &nbsp;
					<div class="control__indicator"></div>
				</label>
			</div>
			<!-- 10 -->
			<div class="form-group hidden">
				<label>Is signature required?</label>
				<p class="help-text"><p class="help-text">If marked yes, then the applicant needs to add e-sign this document to complete the onboarding process.</p></p>
				<label class="control control--radio">
					<input type="radio" class="js-template-signature-required-edit" name="js-template-signature-required-edit" value="0" /> No &nbsp;
					<div class="control__indicator"></div>
				</label>
				<label class="control control--radio">
					<input type="radio"  class="js-template-signature-required-edit" name="js-template-signature-required-edit" value="1" /> Yes &nbsp;
					<div class="control__indicator"></div>
				</label>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<?php $this->load->view('hr_documents_management/templates/tags'); ?>
		</div>
	</div>
</div>