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

-- Online video
-- 04/05/2021
ALTER TABLE `learning_center_online_videos` CHANGE `employees_assigned_sid` `employees_assigned_sid` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `applicants_assigned_sid` `applicants_assigned_sid` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `department_sids` `department_sids` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `learning_center_online_videos` CHANGE `applicants_assigned_to` `applicants_assigned_to` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'all';

-- State by applied ip
-- 04/07/2021
ALTER TABLE `portal_applicant_jobs_list` ADD `ip_state` VARCHAR(70) NULL DEFAULT NULL AFTER `from_indeed`, ADD `ip_city` VARCHAR(70) NULL DEFAULT NULL AFTER `ip_state`;
ALTER TABLE `portal_applicant_jobs_list` ADD `latitude` VARCHAR(50) NULL AFTER `ip_city`, ADD `longitude` VARCHAR(50) NULL AFTER `latitude`;

-- LMS
-- 04/12/2021
ALTER TABLE `learning_center_online_videos` ADD `is_video_expired` VARCHAR(5) NOT NULL DEFAULT 'no' AFTER `department_sids`, 
ADD `expired_number` INT(11) NULL DEFAULT NULL AFTER `is_video_expired`, 
ADD `expired_type` VARCHAR(10) NULL DEFAULT NULL AFTER `expired_number`, 
ADD `expired_start_date` DATETIME NULL DEFAULT NULL AFTER `expired_type`;
ALTER TABLE `learning_center_online_videos` ADD `video_start_date` DATE NULL DEFAULT NULL AFTER `sent_email`;
ALTER TABLE `learning_center_online_videos_assignments` ADD `is_deleted` TINYINT(1) NOT NULL DEFAULT '0' AFTER `completed`, ADD `deleted_at` DATETIME NULL DEFAULT NULL AFTER `is_deleted`;

-- Employee visibility to network
-- 04/15/2021
ALTER TABLE `talent_network_content_config` ADD `visibility_employees` TEXT NULL DEFAULT NULL AFTER `picture_or_video`;
ALTER TABLE `portal_themes` CHANGE `job_fair_career_page_url` `job_fair_career_page_url` VARCHAR(700) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;

-- Save old email
-- 04/19/2021
CREATE TABLE IF NOT EXISTS `fix_email_address_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(11) NOT NULL,
  `email` varchar(225) NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
ALTER TABLE `portal_applicant_jobs_list` ADD `for_notification` TINYINT(1) NOT NULL DEFAULT '0' AFTER `job_fair_key`;

-- learning center history
-- 04/20/2021
DROP TABLE IF EXISTS `learning_center_assign_user_history`;
CREATE TABLE IF NOT EXISTS `learning_center_assign_user_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `learning_center_online_videos_sid` int(11),
  `video_title` text,
  `video_url` text,
  `video_source` varchar(128),
  `video_start_date` date,
  `user_type` varchar(128),
  `user_sid` int(11),
  `date_assigned` datetime,
  `watched` tinyint(1),
  `date_watched` datetime,
  `status` tinyint(1),
  `attempt_status` tinyint(1),
  `duration` varchar(255),
  `completed` tinyint(1),
  `from_training_session` tinyint(1),
  `questionnaire_name` text NULL DEFAULT NULL,
  `questionnaire` text NULL DEFAULT NULL,
  `questionnaire_result` varchar(32) NULL DEFAULT NULL,
  `questionnaire_attend_timestamp` timestamp NULL DEFAULT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
