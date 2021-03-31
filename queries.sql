-- Added approver and reporting managers to team
-- 03/16/2021
ALTER TABLE `departments_team_management` ADD `approvers` TEXT NOT NULL AFTER `deleted_date`, ADD `reporting_managers` TEXT NOT NULL AFTER `approvers`;
ALTER TABLE `departments_management` ADD `approvers` TEXT NOT NULL AFTER `deleted_date`, ADD `reporting_managers` TEXT NOT NULL AFTER `approvers`;

-- Added required and signature required checks
-- 03/24/2021
ALTER TABLE `documents_assigned` ADD `is_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `updated_at`, 
ADD `is_signature_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_required`;

ALTER TABLE `documents_assigned_history` ADD `is_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `updated_at`, 
ADD `is_signature_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_required`;

ALTER TABLE `applicant_i9form` ADD `is_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `lista_part3_issuing_select_input`, 
ADD `is_signature_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_required`;

ALTER TABLE `form_w4_original` ADD `is_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `dw_input_5`, 
ADD `is_signature_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_required`;

ALTER TABLE `applicant_w9form` ADD `is_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `uploaded_by_sid`, 
ADD `is_signature_required` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_required`;

-- Added employee visibility to job fair
-- 03/22/2021
ALTER TABLE `job_fairs_recruitment` ADD `visibility_employees` LONGTEXT NOT NULL AFTER `page_url`;
ALTER TABLE `job_fairs_forms` ADD `visibility_employees` LONGTEXT NOT NULL AFTER `page_url`;
ALTER TABLE `job_fairs_recruitment` CHANGE `visibility_employees` `visibility_employees` LONGTEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;
