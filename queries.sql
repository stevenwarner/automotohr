-- Added approver and reporting managers to team
-- 03/16/2021
ALTER TABLE `departments_team_management` ADD `approvers` TEXT NOT NULL AFTER `deleted_date`, ADD `reporting_managers` TEXT NOT NULL AFTER `approvers`;
ALTER TABLE `departments_management` ADD `approvers` TEXT NOT NULL AFTER `deleted_date`, ADD `reporting_managers` TEXT NOT NULL AFTER `approvers`;

