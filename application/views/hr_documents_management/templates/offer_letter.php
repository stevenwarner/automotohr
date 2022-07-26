<div class="js-page-partial" id="js-offer-letter-area-add">
	<div class="row">
		<div class="col-sm-12">
			<!-- 1 -->
			<div class="form-group">
				<label>Template Type <span class="cs-required">*</span></label>
				<br />
				<label class="control control--radio">
					<input type="radio" class="js-template-type" name="js-template-type" value="uploaded" data-type="offer_letter_add" /> Upload &nbsp;
					<div class="control__indicator"></div>
				</label>
				<label class="control control--radio">
					<input type="radio" class="js-template-type" name="js-template-type" value="generated" data-type="offer_letter_add" /> Generate &nbsp;
					<div class="control__indicator"></div>
				</label>
				<?php if (checkIfAppIsEnabled('hybrid_document')) { ?>
					<label class="control control--radio">
						<input type="radio" class="js-template-type" name="js-template-type" value="hybrid_document" data-type="offer_letter_add" /> Hybrid &nbsp;
						<div class="control__indicator"></div>
					</label>
				<?php } ?>
				<label class="control control--radio">
					<input type="radio" class="js-template-type" name="js-template-type" value="template" data-type="offer_letter_add" /> Select Template &nbsp;
					<div class="control__indicator"></div>
				</label>

				<hr />
			</div>
			<!-- 0 -->
			<div class="form-group js-template-row" style="display: none;">
				<label>Template(s) </label>
				<select id="js-templates-add" class="js-templates">
					<option value="0">[Select Offer Letter / Pay Plan Template]</option>
					<?php
					if (sizeof($offer_letters)) {
						foreach ($offer_letters as $k => $v) {
							echo '<option value="' . ($v['sid']) . '">' . ($v['letter_name']) . ' (' . (ucwords($v['letter_type'])) . ')</option>';
						}
					}
					?>
				</select>
			</div>
			<!-- 1 -->
			<div class="form-group">
				<label>Template Name <span class="cs-required">*</span></label>
				<input type="text" class="form-control" id="js-template-name" />
				<p class="cs-error js-error"></p>
			</div>
			<!-- 2 -->
			<div class="form-group js-for-generated">
				<label>Template Letter Body <span class="cs-required">*</span></label>
				<div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link', ['show' => false]); ?></div>
				<textarea id="js-template-body"></textarea>
			</div>
			<!-- 2 -->
			<div class="form-group js-for-uploaded js-guidence-box">
				<label>Instructions / Guidance</label>
				<div style="margin-bottom:5px;"><?php $this->load->view('templates/_parts/ckeditor_gallery_link', ['show' => false]); ?></div>
				<textarea id="js-template-guidence"></textarea>
				<p class="cs-error js-error"></p>
			</div>
			<!-- 3 -->
			<div class="form-group js-for-uploaded">
				<label>Browse Document<span class="staric">*</span></label>
				<input style="display: none;" type="file" name="document" id="upload_document" />
				<p class="cs-error js-error"></p>
			</div>
			<!-- 3 -->
			<div class="form-group">
				<label>Acknowledgment Required</label>
				<div class="hr-select-dropdown">
					<select id="js-template-acknowledgment" class="form-control">
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
					<select id="js-template-download" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<p class="help-text">Enable the Download Required, if you need the Employee or Onboarding Candidate to download this form.</p>
			</div>
			<!-- 5 -->
			<div class="form-group">
				<label class="jsAddOLTypeLabel jsAddOLTypeLabel_signature">Upload Required</label>
				<div class="hr-select-dropdown">
					<select id="js-template-signature" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<p class="help-text jsAddOLTypeP jsAddOLTypeHint">Enable the Signature Required option, if you need the Employee or Onboarding Candidate to complete and Sign the document with a pen, and then upload the completed document into the system.</p>
			</div>
			<!-- 6 -->
			<div class="form-group">
				<label>Sort Order</label>
				<input type="text" class="form-control" id="js-template-sort-order" />
			</div>
			<!-- 8 -->
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h5>
								<strong>Visibility</strong>&nbsp;<i class="fa fa-question-circle-o csClickable jsHintBtn" aria-hidden="true" data-target="visibilty"></i>
								<p class="jsHintBody" data-hint="visibilty"><br /><?= getUserHint('visibility_hint'); ?></p>
							</h5>
						</div>
						<div class="panel-body">
							<!-- Payroll -->
							<label class="control control--checkbox">
								Visible To Payroll
								<input type="checkbox" name="visible_to_payroll" class="js-payroll-offer-letter-add" value="1" />
								<div class="control__indicator"></div>
							</label>
							<hr />
							<!-- Roles -->
							<label>Roles</label>
							<select name="roles[]" id="js-roles-offer-letter-add" multiple>
							</select>
							<br />
							<br />
							<!-- Departments -->
							<label>Departments</label>
							<select name="departments[]" id="js-department-offer-letter-add" multiple>
							</select>
							<br />
							<br />
							<!-- Teams -->
							<label>Teams</label>
							<select name="teams[]" id="js-teams-offer-letter-add" multiple>
							</select>
							<br />
							<br />
							<!-- Employees -->
							<label>Employees</label>
							<select name="employees[]" id="js-employees-offer-letter-add" multiple>
							</select>
						</div>
					</div>
				</div>
			</div>
			<!-- 9 -->

			<?php //$this->load->view('hr_documents_management/partials/approvers_section'); ?>
			<?php $this->load->view(
                'hr_documents_management/partials/test_approvers_section', 
                [
                    "appCheckboxIdx" => "jsHasApprovalFlowModal", 
                    "containerIdx" => "jsApproverFlowContainerModal", 
                    "addEmployeeIdx" => "jsAddDocumentApproversModal", 
                    "intEmployeeBoxIdx" => "jsEmployeesadditionalBoxModal", 
                    "extEmployeeBoxIdx" => "jsEmployeesadditionalExternalBoxModal", 
                    "approverNoteIdx" => "jsApproversNoteModal"
                ]
            ); ?>

			<br>

			<div class="row">
				<div class="col-xs-12">
					<div class="hr-box">
						<div class="hr-box-header">
							<strong>Automatically assign after Days:</strong>
						</div>
						<div class="hr-innerpadding">
							<div class="row">
								<div class="col-xs-12">
									<div class="">
										<div class="">
											<label class="control control--radio">
												Days
												<input type="radio" name="assign_type" value="days" />
												<div class="control__indicator"></div>
											</label> &nbsp;
											<label class="control control--radio font-normal">
												Months
												<input type="radio" name="assign_type" value="months" />
												<div class="control__indicator"></div>
											</label>
										</div>
									</div>
								</div>
							</div>
							<br />

							<div class="row">
								<div class="col-xs-6 js-type-days js-type">
									<div class="universal-form-style-v2">
										<div class="input-group pto-time-off-margin-custom">
											<input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-days">
											<span class="input-group-addon">Days</span>
										</div>
									</div>
								</div>
								<div class="col-xs-6 js-type-months js-type">
									<div class="universal-form-style-v2">
										<div class="input-group pto-time-off-margin-custom">
											<input type="number" class="form-control" value="<?php echo isset($document_info['automatic_assign_in']) && !empty($document_info['automatic_assign_in']) ? $document_info['automatic_assign_in'] : 0; ?>" name="assign-in-months">
											<span class="input-group-addon">Months</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<br>


			<!--  Document Settings - Confidenti -->
			<?php $this->load->view('hr_documents_management/partials/settings', [
				'is_confidential' =>  $document_info['is_confidential']
			]); ?>

			<!-- 7 -->
			<div class="form-group js-for-generated">
				<label>Authorized Management Signers</label>
				<select id="js-template-signers" multiple="true">
					<?php
					if (sizeof($managers_list)) {
						foreach ($managers_list as $k => $v) {
							echo '<option value="' . ($v['sid']) . '">' . (remakeEmployeeName($v)) . '</option>';
						}
					}
					?>
				</select>
			</div>
			<!-- 8 -->
			<div class="form-group">
				<label>Send an email notification?</label>
				<br />
				<label class="control control--radio">
					<input type="radio" class="js-template-send-email" name="js-template-send-email" value="no" /> No &nbsp;
					<div class="control__indicator"></div>
				</label>
				<label class="control control--radio">
					<input type="radio" class="js-template-send-email" name="js-template-send-email" value="yes" /> Yes &nbsp;
					<div class="control__indicator"></div>
				</label>
			</div>
			<!-- 9 -->
			<div class="form-group">
				<label>Is this document required?</label>
				<p class="help-text">If marked yes, then the applicant needs to complete this document to complete the onboarding process.</p>
				<label class="control control--radio">
					<input type="radio" class="js-template-required" name="js-template-required" value="0" /> No &nbsp;
					<div class="control__indicator"></div>
				</label>
				<label class="control control--radio">
					<input type="radio" class="js-template-required" name="js-template-required" value="1" /> Yes &nbsp;
					<div class="control__indicator"></div>
				</label>
			</div>
			<!-- 10 -->
			<div class="form-group hidden">
				<label>Is signature required?</label>
				<p class="help-text">If marked yes, then the applicant needs to add e-sign this document to complete the onboarding process.</p>
				<label class="control control--radio">
					<input type="radio" class="js-template-signature-required" name="js-template-signature-required" value="0" /> No &nbsp;
					<div class="control__indicator"></div>
				</label>
				<label class="control control--radio">
					<input type="radio" class="js-template-signature-required" name="js-template-signature-required" value="1" /> Yes &nbsp;
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