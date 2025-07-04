# CHANGELOG

## 3.36.34 - 2017-10-26

* `Aws\CloudFront` - You can now specify additional options for MinimumProtocolVersion, which controls the SSL/TLS protocol that CloudFront uses to communicate with viewers. The minimum protocol version that you choose also determines the ciphers that CloudFront uses to encrypt the content that it returns to viewers.
* `Aws\EC2` - You are now able to create and launch EC2 P3 instance, next generation GPU instances, optimized for machine learning and high performance computing applications. With up to eight NVIDIA Tesla V100 GPUs, P3 instances provide up to one petaflop of mixed-precision, 125 teraflops of single-precision, and 62 teraflops of double-precision floating point performance, as well as a 300 GB/s second-generation NVLink interconnect that enables high-speed, low-latency GPU-to-GPU communication. P3 instances also feature up to 64 vCPUs based on custom Intel Xeon E5 (Broadwell) processors, 488 GB of DRAM, and 25 Gbps of dedicated aggregate network bandwidth using the Elastic Network Adapter (ENA).

## 3.36.33 - 2017-10-24

* `Aws\ConfigService` - AWS Config support for CodeBuild Project resource type
* `Aws\ElastiCache` - Amazon ElastiCache for Redis today announced support for data encryption both for data in-transit and data at-rest. The new encryption in-transit functionality enables ElastiCache for Redis customers to encrypt data for all communication between clients and Redis engine, and all intra-cluster Redis communication. The encryption at-rest functionality allows customers to encrypt their S3 based backups. Customers can begin using the new functionality by simply enabling this functionality via AWS console, and a small configuration change in their Redis clients. The ElastiCache for Redis service automatically manages life cycle of the certificates required for encryption, including the issuance, renewal and expiration of certificates. Additionally, as part of this launch, customers will gain the ability to start using the Redis AUTH command that provides an added level of authentication.
* `Aws\Glue` - AWS Glue: Adding a new API, BatchStopJobRun, to stop one or more job runs for a specified Job. 
* `Aws\Pinpoint` - Added support for APNs VoIP messages. Added support for collapsible IDs, message priority, and TTL for APNs and FCM/GCM.

## 3.36.32 - 2017-10-23

* `Aws\` - Override passed in starting token for a ResultPaginator when moving to the next command.
* `Aws\Organizations` - This release supports integrating other AWS services with AWS Organizations through the use of an IAM service-linked role called AWSServiceRoleForOrganizations. Certain operations automatically create that role if it does not already exist.

## 3.36.31 - 2017-10-20

* `Aws\EC2` - Adding pagination support for DescribeSecurityGroups for EC2 Classic and VPC Security Groups

## 3.36.30 - 2017-10-19

* `Aws\S3` - PostObject[V4] classes now obey use_path_style_endpoint client configuration in form generation.
* `Aws\SQS` - Added support for tracking cost allocation by adding, updating, removing, and listing the metadata tags of Amazon SQS queues.
* `Aws\SSM` - EC2 Systems Manager versioning support for Parameter Store. Also support for referencing parameter versions in SSM Documents.

## 3.36.29 - 2017-10-18

* `Aws\Lightsail` - This release adds support for Windows Server-based Lightsail instances. The GetInstanceAccessDetails API now returns the password of your Windows Server-based instance when using the default key pair. GetInstanceAccessDetails also returns a PasswordData object for Windows Server instances containing the ciphertext and keyPairName. The Blueprint data type now includes a list of platform values (LINUX_UNIX or WINDOWS). The Bundle data type now includes a list of SupportedPlatforms values (LINUX_UNIX or WINDOWS).

## 3.36.28 - 2017-10-17

* `Aws\CloudHSMV2` - Service Region Launch.
* `Aws\ElasticsearchService` - This release adds support for VPC access to Amazon Elasticsearch Service.
* `Aws\S3` - No longer override supplied ContentType parameter when performing a multipart upload.

## 3.36.27 - 2017-10-16

* `Aws\CloudHSM` - Documentation updates for AWS CloudHSM Classic.
* `Aws\EC2` - You can now change the tenancy of your VPC from dedicated to default with a single API operation. For more details refer to the documentation for changing VPC tenancy.
* `Aws\ElasticsearchService` - AWS Elasticsearch adds support for enabling slow log publishing. Using slow log publishing options customers can configure and enable index/query slow log publishing of their domain to preferred AWS Cloudwatch log group.
* `Aws\RDS` - Adds waiters for DBSnapshotAvailable and DBSnapshotDeleted.
* `Aws\WAF` - This release adds support for regular expressions as match conditions in rules, and support for geographical location by country of request IP address as a match condition in rules.
* `Aws\WAFRegional` - This release adds support for regular expressions as match conditions in rules, and support for geographical location by country of request IP address as a match condition in rules.

## 3.36.26 - 2017-10-12

* `Aws\CodeCommit` - This release includes the DeleteBranch API and a change to the contents of a Commit object.
* `Aws\DatabaseMigrationService` - This change includes addition of new optional parameter to an existing API
* `Aws\ElasticBeanstalk` - Added the ability to add, delete or update Tags
* `Aws\Polly` - Amazon Polly exposes two new voices: "Matthew" (US English) and "Takumi" (Japanese)
* `Aws\RDS` - You can now call DescribeValidDBInstanceModifications to learn what modifications you can make to your DB instance. You can use this information when you call ModifyDBInstance.

## 3.36.25 - 2017-10-11

* `Aws\ECR` - Adds support for new API set used to manage Amazon ECR repository lifecycle policies. Amazon ECR lifecycle policies enable you to specify the lifecycle management of images in a repository. The configuration is a set of one or more rules, where each rule defines an action for Amazon ECR to apply to an image. This allows the automation of cleaning up unused images, for example expiring images based on age or status. A lifecycle policy preview API is provided as well, which allows you to see the impact of a lifecycle policy on an image repository before you execute it
* `Aws\SES` - Added content related to email template management and templated email sending operations.

## 3.36.24 - 2017-10-10

* `Aws\EC2` - This release includes updates to AWS Virtual Private Gateway.
* `Aws\ElasticLoadBalancingv2` - Server Name Indication (SNI) is an extension to the TLS protocol by which a client indicates the hostname to connect to at the start of the TLS handshake. The load balancer can present multiple certificates through the same secure listener, which enables it to support multiple secure websites using a single secure listener. Application Load Balancers also support a smart certificate selection algorithm with SNI. If the hostname indicated by a client matches multiple certificates, the load balancer determines the best certificate to use based on multiple factors including the capabilities of the client.
* `Aws\OpsWorksCM` - Provide engine specific information for node associations.

## 3.36.23 - 2017-10-06

* `Aws\ConfigService` - Revert: Added missing enumeration values for ConfigurationItemStatus
* `Aws\SQS` - Documentation updates regarding availability of FIFO queues and miscellaneous corrections.

## 3.36.22 - 2017-10-06

* `Aws\ConfigService` - Added missing enumeration values for ConfigurationItemStatus
* `Aws\SQS` - Documentation updates regarding availability of FIFO queues and miscellaneous corrections.

## 3.36.21 - 2017-10-05

* `Aws\Redshift` - DescribeEventSubscriptions API supports tag keys and tag values as request parameters. 
* `Aws\S3` - Properly parse s3:// uri used with StreamWrapper.

## 3.36.20 - 2017-10-04

* `Aws\` - Optionally preserve CommandPool keys during generation
* `Aws\KinesisAnalytics` - Kinesis Analytics now supports schema discovery on objects in S3. Additionally, Kinesis Analytics now supports input data preprocessing through Lambda.
* `Aws\Route53Domains` - Added a new API that checks whether a domain name can be transferred to Amazon Route 53.

## 3.36.19 - 2017-10-03

* `Aws\EC2` - This release includes service updates to AWS VPN.
* `Aws\SSM` - EC2 Systems Manager support for tagging SSM Documents. Also support for tag-based permissions to restrict access to SSM Documents based on these tags.

## 3.36.18 - 2017-10-02

* `Aws\CloudHSM` - Documentation updates for CloudHSM

## 3.36.17 - 2017-09-29

* `Aws\AppStream` - Includes APIs for managing and accessing image builders, and deleting images.
* `Aws\CodeBuild` - Adding support for Building GitHub Pull Requests in AWS CodeBuild
* `Aws\MTurk` - Today, Amazon Mechanical Turk (MTurk) supports SQS Notifications being delivered to Customers' SQS queues when different stages of the MTurk workflow are complete. We are going to create new functionality so that Customers can leverage SNS topics as a destination for notification messages when various stages of the MTurk workflow are complete. 
* `Aws\Organizations` - This release flags the HandshakeParty structure's Type and Id fields as 'required'. They effectively were required in the past, as you received an error if you did not include them. This is now reflected at the API definition level. 
* `Aws\Route53` - This change allows customers to reset elements of health check.
* `Aws\rds` - Introduce DBSnapshotAvailable and DBSnapshotDeleted waiters for DBSnapshot

## 3.36.16 - 2017-09-27

* `Aws\Pinpoint` - Added two new push notification channels: Amazon Device Messaging (ADM) and, for push notification support in China, Baidu Cloud Push. Added support for APNs auth via .p8 key file. Added operation for direct message deliveries to user IDs, enabling you to message an individual user on multiple endpoints.

## 3.36.15 - 2017-09-26

* `Aws\CloudFormation` - You can now prevent a stack from being accidentally deleted by enabling termination protection on the stack. If you attempt to delete a stack with termination protection enabled, the deletion fails and the stack, including its status, remains unchanged. You can enable termination protection on a stack when you create it. Termination protection on stacks is disabled by default. After creation, you can set termination protection on a stack whose status is CREATE_COMPLETE, UPDATE_COMPLETE, or UPDATE_ROLLBACK_COMPLETE.

## 3.36.14 - 2017-09-22

* `Aws\ConfigService` - AWS Config support for DynamoDB tables and Auto Scaling resource types
* `Aws\ECS` - Amazon ECS users can now add and drop Linux capabilities to their containers through the use of docker's cap-add and cap-drop features. Customers can specify the capabilities they wish to add or drop for each container in their task definition. 
* `Aws\RDS` - Documentation updates for rds

## 3.36.13 - 2017-09-21

* `Aws\Budgets` - Including "DuplicateRecordException" in UpdateNotification and UpdateSubscriber. 
* `Aws\CloudWatchLogs` - Adds support for associating LogGroups with KMS Keys.
* `Aws\EC2` - Add EC2 APIs to copy Amazon FPGA Images (AFIs) within the same region and across multiple regions, delete AFIs, and modify AFI attributes. AFI attributes include name, description and granting/denying other AWS accounts to load the AFI.

## 3.36.12 - 2017-09-20

* `Aws\AppStream` - API updates for supporting On-Demand fleets.
* `Aws\CodePipeline` - This change includes a PipelineMetadata object that is part of the output from the GetPipeline API that includes the Pipeline ARN, created, and updated timestamp.
* `Aws\Greengrass` - Reset Deployments feature allows you to clean-up the cloud resource so you can delete the group. It also cleans up the core so that it goes back to the pre-deployment state.
* `Aws\Greengrass` - AWS Greengrass is now available in the Asia Pacific (Tokyo) region, ap-northeast-1.
* `Aws\LexRuntimeService` - Request attributes can be used to pass client specific information from the client to Amazon Lex as part of each request.
* `Aws\RDS` - Introduces the --option-group-name parameter to the ModifyDBSnapshot CLI command. You can specify this parameter when you upgrade an Oracle DB snapshot. The same option group considerations apply when upgrading a DB snapshot as when upgrading a DB instance. For more information, see http://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_UpgradeDBInstance.Oracle.html#USER_UpgradeDBInstance.Oracle.OGPG.OG

## 3.36.11 - 2017-09-19

* `Aws\EC2` - Fixed bug in EC2 clients preventing ElasticGpuSet from being set.

## 3.36.10 - 2017-09-18

* `Aws\EC2` - Amazon EC2 now lets you opt for Spot instances to be stopped in the event of an interruption instead of being terminated. Your Spot request can be fulfilled again by restarting instances from a previously stopped state, subject to availability of capacity at or below your preferred price. When you submit a persistent Spot request, you can choose from "terminate" or "stop" as the instance interruption behavior. Choosing "stop" will shutdown your Spot instances so you can continue from this stopped state later on. This feature is only available for instances with Amazon EBS volume as their root device.
* `Aws\IAM` - A new API, DeleteServiceLinkedRole, submits a service-linked role deletion request and returns a DeletionTaskId, which you can use to check the status of the deletion.
* `Aws\SES` - Amazon Simple Email Service (Amazon SES) now lets you customize the domains used for tracking open and click events. Previously, open and click tracking links referred to destinations hosted on domains operated by Amazon SES. With this feature, you can use your own branded domains for capturing open and click events.

## 3.36.9 - 2017-09-15

* `Aws\APIGateway` - Add a new enum "REQUEST" to '--type <value>' field in the current create-authorizer API, and make "identitySource" optional.

## 3.36.8 - 2017-09-14

* `Aws\CodeBuild` - Supporting Parameter Store in environment variables for AWS CodeBuild
* `Aws\Organizations` - Documentation updates for AWS Organizations
* `Aws\ServiceCatalog` - This release of Service Catalog adds API support to copy products.

## 3.36.7 - 2017-09-13

* `Aws\AutoScaling` - Customers can create Life Cycle Hooks at the time of creating Auto Scaling Groups through the CreateAutoScalingGroup API
* `Aws\Batch` - Documentation updates for batch
* `Aws\CloudWatchEvents` - Exposes ConcurrentModificationException as one of the valid exceptions for PutPermission and RemovePermission operation.
* `Aws\EC2` - You are now able to create and launch EC2 x1e.32xlarge instance, a new EC2 instance in the X1 family, in us-east-1, us-west-2, eu-west-1, and ap-northeast-1. x1e.32xlarge offers 128 vCPUs, 3,904 GiB of DDR4 instance memory, high memory bandwidth, large L3 caches, and leading reliability capabilities to boost the performance and reliability of in-memory applications.

## 3.36.6 - 2017-09-12

* `Aws\EC2` - Fixed bug in EC2 clients preventing HostOfferingSet from being set

## 3.36.5 - 2017-09-11

* `Aws\DeviceFarm` - DeviceFarm has added support for two features - RemoteDebugging and Customer Artifacts. Customers can now do remote Debugging on their Private Devices and can now retrieve custom files generated by their tests on the device and the device host (execution environment) on both public and private devices. 

## 3.36.4 - 2017-09-08

* `Aws\CloudWatchLogs` - Adds support for the PutResourcePolicy, DescribeResourcePolicy and DeleteResourcePolicy APIs.

## 3.36.3 - 2017-09-07

* `Aws\ApplicationAutoScaling` - Documentation updates for application-autoscaling
* `Aws\EC2` - With Tagging support, you can add Key and Value metadata to search, filter and organize your NAT Gateways according to your organization's needs.
* `Aws\ElasticLoadBalancingv2` - The feature enables the new Network Load Balancer that is optimized to handle volatile traffic patterns while using a single static IP address per Availability Zone. Network Load Balancer operates at the connection level (Layer 4), routing connections to Amazon EC2 instances and containers, within Amazon Virtual Private Cloud (Amazon VPC) based on IP protocol data.
* `Aws\LexModelBuildingService` - Amazon Lex provides the ability to export your Amazon Lex chatbot definition as a JSON file that can be added to the target platform. The JSON configuration file contains the structure of your Amazon Lex chatbot, including the intent schema with utterances, slots, prompts and slot-types.
* `Aws\Route53` - You can configure Amazon Route 53 to log information about the DNS queries that Amazon Route 53 receives for your domains and subdomains. When you configure query logging, Amazon Route 53 starts to send logs to CloudWatch Logs. You can use various tools, including the AWS console, to access the query logs.

## 3.36.2 - 2017-09-06

* `Aws\Budgets` - Add an optional "thresholdType" to notifications to support percentage or absolute value thresholds.

## 3.36.1 - 2017-09-05

* `Aws\CodeStar` - Added support to tag CodeStar projects. Tags can be used to organize and find CodeStar projects on key-value pairs that you can choose. For example, you could add a tag with a key of "Release" and a value of "Beta" to projects your organization is working on for an upcoming beta release.

## 3.36.0 - 2017-09-01

* `Aws\GameLift` - GameLift VPC resources can be peered with any other AWS VPC. R4 memory-optimized instances now available to deploy.
* `Aws\Mobile` - AWS Mobile Hub is an integrated experience designed to help developers build, test, configure and release cloud-based applications for mobile devices using Amazon Web Services. AWS Mobile Hub provides a console and API for developers, allowing them to quickly select desired features and integrate them into mobile applications. Features include NoSQL Database, Cloud Logic, Messaging and Analytics. With AWS Mobile Hub, you pay only for the underlying services that Mobile Hub provisions based on the features you choose in the Mobile Hub console.
* `Aws\SSM` - Adding KMS encryption support to SSM Inventory Resource Data Sync. Exposes the ClientToken parameter on SSM StartAutomationExecution to provide idempotent execution requests.

## 3.35.3 - 2017-08-31

* `Aws\CodeBuild` - The AWS CodeBuild HTTP API now provides the BatchDeleteBuilds operation, which enables you to delete existing builds.
* `Aws\EC2` - Descriptions for Security Group Rules enables customers to be able to define a description for ingress and egress security group rules . The Descriptions for Security Group Rules feature supports one description field per Security Group rule for both ingress and egress rules . Descriptions for Security Group Rules provides a simple way to describe the purpose or function of a Security Group Rule allowing for easier customer identification of configuration elements . Prior to the release of Descriptions for Security Group Rules , customers had to maintain a separate system outside of AWS if they wanted to track Security Group Rule mapping and their purpose for being implemented. If a security group rule has already been created and you would like to update or change your description for that security group rule you can use the UpdateSecurityGroupRuleDescription API.
* `Aws\ElasticLoadBalancingv2` - This change now allows Application Load Balancers to distribute traffic to AWS resources using their IP addresses as targets in addition to the instance IDs. You can also load balance to resources outside the VPC hosting the load balancer using their IP addresses as targets. This includes resources in peered VPCs, EC2-Classic, and on-premises locations reachable over AWS Direct Connect or a VPN connection.
* `Aws\LexModelBuildingService` - Amazon Lex now supports synonyms for slot type values. If the user inputs a synonym, it will be resolved to the corresponding slot value.

## 3.35.2 - 2017-08-30

* `Aws\ApplicationAutoScaling` - Application Auto Scaling now supports the DisableScaleIn option for Target Tracking Scaling Policies. This allows customers to create scaling policies that will only add capacity to the target.
* `Aws\Organizations` - The exception ConstraintViolationException now contains a new reason subcode MASTERACCOUNT_MISSING_CONTACT_INFO to make it easier to understand why attempting to remove an account from an Organization can fail. We also improved several other of the text descriptions and examples.

## 3.35.1 - 2017-08-29

* `Aws\ConfigService` - Increased the internal size limit of resourceId
* `Aws\EC2` - Provides capability to add secondary CIDR blocks to a VPC.

## 3.35.0 - 2017-08-25

* `Aws\` - Update CloudHSM smoke tests to CloudHSMV2
* `Aws\CloudFormation` - Rollback triggers enable you to have AWS CloudFormation monitor the state of your application during stack creation and updating, and to roll back that operation if the application breaches the threshold of any of the alarms you've specified.
* `Aws\GameLift` - Update spelling of MatchmakingTicket status values for internal consistency.
* `Aws\RDS` - Option group options now contain additional properties that identify requirements for certain options. Check these properties to determine if your DB instance must be in a VPC or have auto minor upgrade turned on before you can use an option. Check to see if you can downgrade the version of an option after you have installed it.

## 3.34.2 - 2017-08-24

* `Aws\Rekognition` - Update the enum value of LandmarkType and GenderType to be consistent with service response

## 3.34.1 - 2017-08-23

* `Aws\AppStream` - Documentation updates for appstream

## 3.34.0 - 2017-08-22

* `Aws\` - Fixes an issue where exceptions weren't being fully loaded when using a `SaveAs` parameter set to a file path on Guzzle v5.
* `Aws\` - Update Composer to add dependencies on `simplexml`, `pcre`, `spl` and `json`. This change will cause Composer updates to fail if you do not have these PHP extensions installed.
* `Aws\SSM` - Changes to associations in Systems Manager State Manager can now be recorded. Previously, when you edited associations, you could not go back and review older association settings. Now, associations are versioned, and can be named using human-readable strings, allowing you to see a trail of association changes. You can also perform rate-based scheduling, which allows you to schedule associations more granularly.

## 3.33.4 - 2017-08-21

* `Aws\Firehose` - This change will allow customers to attach a Firehose delivery stream to an existing Kinesis stream directly. You no longer need a forwarder to move data from a Kinesis stream to a Firehose delivery stream. You can now run your streaming applications on your Kinesis stream and easily attach a Firehose delivery stream to it for data delivery to S3, Redshift, or Elasticsearch concurrently.
* `Aws\Route53` - Amazon Route 53 now supports CAA resource record type. A CAA record controls which certificate authorities are allowed to issue certificates for the domain or subdomain.

## 3.33.3 - 2017-08-18

* `Aws\CodeStar` - Launch AWS CodeStar in the US West (N. California) and EU (London) regions.

## 3.33.2 - 2017-08-16

* `Aws\` - Fixes a bug in `ClientResolver` that would provide incorrect information on required parameters set to `null` when resolving a client.
* `Aws\GameLift` - The Matchmaking Grouping Service is a new feature that groups player match requests for a given game together into game sessions based on developer configured rules.

## 3.33.1 - 2017-08-15

* `Aws\EC2` - Fixed bug in EC2 clients preventing HostReservation from being set

## 3.33.0 - 2017-08-14

* `Aws\Batch` - This release enhances the DescribeJobs API to include the CloudWatch logStreamName attribute in ContainerDetail and ContainerDetailAttempt
* `Aws\CloudHSMV2` - CloudHSM provides hardware security modules for protecting sensitive data and cryptographic keys within an EC2 VPC, and enable the customer to maintain control over key access and use. This is a second-generation of the service that will improve security, lower cost and provide better customer usability.
* `Aws\EFS` - Customers can create encrypted EFS file systems and specify a KMS master key to encrypt it with.
* `Aws\Glue` - AWS Glue is a fully managed extract, transform, and load (ETL) service that makes it easy for customers to prepare and load their data for analytics. You can create and run an ETL job with a few clicks in the AWS Management Console. You simply point AWS Glue to your data stored on AWS, and AWS Glue discovers your data and stores the associated metadata (e.g. table definition and schema) in the AWS Glue Data Catalog. Once cataloged, your data is immediately searchable, queryable, and available for ETL. AWS Glue generates the code to execute your data transformations and data loading processes. AWS Glue generates Python code that is entirely customizable, reusable, and portable. Once your ETL job is ready, you can schedule it to run on AWS Glue's fully managed, scale-out Spark environment. AWS Glue provides a flexible scheduler with dependency resolution, job monitoring, and alerting. AWS Glue is serverless, so there is no infrastructure to buy, set up, or manage. It automatically provisions the environment needed to complete the job, and customers pay only for the compute resources consumed while running ETL jobs. With AWS Glue, data can be available for analytics in minutes.
* `Aws\MigrationHub` - AWS Migration Hub provides a single location to track migrations across multiple AWS and partner solutions. Using Migration Hub allows you to choose the AWS and partner migration tools that best fit your needs, while providing visibility into the status of your entire migration portfolio. Migration Hub also provides key metrics and progress for individual applications, regardless of which tools are being used to migrate them. For example, you might use AWS Database Migration Service, AWS Server Migration Service, and partner migration tools to migrate an application comprised of a database, virtualized web servers, and a bare metal server. Using Migration Hub will provide you with a single screen that shows the migration progress of all the resources in the application. This allows you to quickly get progress updates across all of your migrations, easily identify and troubleshoot any issues, and reduce the overall time and effort spent on your migration projects. Migration Hub is available to all AWS customers at no additional charge. You only pay for the cost of the migration tools you use, and any resources being consumed on AWS.
* `Aws\SSM` - Systems Manager Maintenance Windows include the following changes or enhancements: New task options using Systems Manager Automation, AWS Lambda, and AWS Step Functions; enhanced ability to edit the targets of a Maintenance Window, including specifying a target name and description, and ability to edit the owner field; enhanced ability to edits tasks; enhanced support for Run Command parameters; and you can now use a --safe flag when attempting to deregister a target. If this flag is enabled when you attempt to deregister a target, the system returns an error if the target is referenced by any task. Also, Systems Manager now includes Configuration Compliance to scan your fleet of managed instances for patch compliance and configuration inconsistencies. You can collect and aggregate data from multiple AWS accounts and Regions, and then drill down into specific resources that aren't compliant.
* `Aws\StorageGateway` - Add optional field ForceDelete to DeleteFileShare api.

## 3.32.7 - 2017-08-11

* `Aws\CodeDeploy` - Adds support for specifying Application Load Balancers in deployment groups, for both in-place and blue/green deployments.
* `Aws\CognitoIdentityProvider` - We have added support for features for Amazon Cognito User Pools that enable application developers to easily add and customize a sign-up and sign-in user experience, use OAuth 2.0, and integrate with Facebook, Google, Login with Amazon, and SAML-based identity providers.
* `Aws\EC2` - Provides customers an opportunity to recover an EIP that was released

## 3.32.6 - 2017-08-10

* `Aws\CloudDirectory` - Enable BatchDetachPolicy
* `Aws\CodeBuild` - Supporting Bitbucket as source type in AWS CodeBuild.

## 3.32.5 - 2017-08-09

* `Aws\RDS` - Documentation updates for RDS.

## 3.32.4 - 2017-08-08

* `Aws\ElasticBeanstalk` - Add support for paginating the result of DescribeEnvironments. Include the ARN of described environments in DescribeEnvironments output.
* `Aws\Signature` - Fixed edgecase in expiration duration check on signature when seconds roll between implicit startime and relative end time.

## 3.32.3 - 2017-08-01

* `Aws\CodeDeploy` - AWS CodeDeploy now supports the use of multiple tag groups in a single deployment group (an intersection of tags) to identify the instances for a deployment. When you create or update a deployment group, use the new ec2TagSet and onPremisesTagSet structures to specify up to three groups of tags. Only instances that are identified by at least one tag in each of the tag groups are included in the deployment group.
* `Aws\ConfigService` - Added new API, GetDiscoveredResourceCounts, which returns the resource types, the number of each resource type, and the total number of resources that AWS Config is recording in the given region for your AWS account.
* `Aws\EC2` - Ec2 SpotInstanceRequestFulfilled waiter update
* `Aws\ElasticLoadBalancingv2` - Add TargetInService and TargetDeregistered waiters 
* `Aws\Pinpoint` - This release of the Pinpoint SDK enables App management - create, delete, update operations, Raw Content delivery for APNs and GCM campaign messages and From Address override.
* `Aws\SES` - This update adds information about publishing email open and click events. This update also adds information about publishing email events to Amazon Simple Notification Service (Amazon SNS).

## 3.32.2 - 2017-07-31

* `Aws\CodeStar` -  AWS CodeStar is now available in the following regions: Asia Pacific (Singapore), Asia Pacific (Sydney), EU (Frankfurt)
* `Aws\Inspector` - Inspector's StopAssessmentRun API has been updated with a new input option - stopAction. This request parameter can be set to either START_EVALUATION or SKIP_EVALUATION. START_EVALUATION (the default value, and the previous behavior) stops the AWS agent data collection and begins the results evaluation for findings generation based on the data collected so far. SKIP_EVALUATION cancels the assessment run immediately, after which no findings are generated.
* `Aws\SSM` - Adds a SendAutomationSignal API to SSM Service. This API is used to send a signal to an automation execution to change the current behavior or status of the execution.

## 3.32.1 - 2017-07-27

* `Aws\EC2` - The CreateDefaultVPC API enables you to create a new default VPC . You no longer need to contact AWS support, if your default VPC has been deleted.
* `Aws\KinesisAnalytics` - Added additional exception types and clarified documentation.

## 3.32.0 - 2017-07-26

* `Aws\` - Support for changes regarding PHP 7.2 releases.
* `Aws\CloudWatch` - This release adds high resolution features to CloudWatch, with support for Custom Metrics down to 1 second and Alarms down to 10 seconds.
* `Aws\DynamoDB` - Corrected a typo.
* `Aws\EC2` - Amazon EC2 Elastic GPUs allow you to easily attach low-cost graphics acceleration to current generation EC2 instances. With Amazon EC2 Elastic GPUs, you can configure the right amount of graphics acceleration to your particular workload without being constrained by fixed hardware configurations and limited GPU selection.

## 3.31.10 - 2017-07-25

* `Aws\CloudDirectory` - Cloud Directory adds support for additional batch operations.
* `Aws\CloudFormation` - AWS CloudFormation StackSets enables you to manage stacks across multiple accounts and regions.

## 3.31.9 - 2017-07-24

* `Aws\AppStream` - Amazon AppStream 2.0 image builders and fleets can now access applications and network resources that rely on Microsoft Active Directory (AD) for authentication and permissions. This new feature allows you to join your streaming instances to your AD, so you can use your existing AD user management tools. 
* `Aws\EC2` - Spot Fleet tagging capability allows customers to automatically tag instances launched by Spot Fleet. You can use this feature to label or distinguish instances created by distinct Spot Fleets. Tagging your EC2 instances also enables you to see instance cost allocation by tag in your AWS bill.

## 3.31.8 - 2017-07-20

* `Aws\EMR` - Amazon EMR now includes the ability to use a custom Amazon Linux AMI and adjustable root volume size when launching a cluster.

## 3.31.7 - 2017-07-19

* `Aws\Budgets` - Update budget Management API's to list/create/update RI_UTILIZATION type budget. Update budget Management API's to support DAILY timeUnit for RI_UTILIZATION type budget.
* `Aws\S3` - Properly handle reading mismatched regions from S3's AuthorizationHeaderMalformed exception for S3MultiRegionClient.

## 3.31.6 - 2017-07-17

* `Aws\CognitoIdentityProvider` - Allows developers to configure user pools for email/phone based signup and sign-in.
* `Aws\Lambda` - Lambda@Edge lets you run code closer to your end users without provisioning or managing servers. With Lambda@Edge, your code runs in AWS edge locations, allowing you to respond to your end users at the lowest latency. Your code is triggered by Amazon CloudFront events, such as requests to and from origin servers and viewers, and it is ready to execute at every AWS edge location whenever a request for content is received. You just upload your Node.js code to AWS Lambda and Lambda takes care of everything required to run and scale your code with high availability. You only pay for the compute time you consume - there is no charge when your code is not running.

## 3.31.5 - 2017-07-14

* `Aws\ApplicationDiscoveryService` - Adding feature to the Export API for Discovery Service to allow filters for the export task to allow export based on per agent id.
* `Aws\EC2` - New EC2 GPU Graphics instance
* `Aws\MarketplaceCommerceAnalytics` - Update to Documentation Model For New Report Cadence / Reformat of Docs

## 3.31.4 - 2017-07-13

* `Aws\APIGateway` - Adds support for management of gateway responses.
* `Aws\EC2` - X-ENI (or Cross-Account ENI) is a new feature that allows the attachment or association of Elastic Network Interfaces (ENI) between VPCs in different AWS accounts located in the same availability zone. With this new capability, service providers and partners can deliver managed solutions in a variety of new architectural patterns where the provider and consumer of the service are in different AWS accounts.
* `Aws\LexModelBuildingService` - Fixed broken links to reference and conceptual content.

## 3.31.3 - 2017-07-12

* `Aws\AutoScaling` - Auto Scaling now supports a new type of scaling policy called target tracking scaling policies that you can use to set up dynamic scaling for your application.
* `Aws\S3` - Fixes an issue introduced in 3.31.0 that was not setting the ContentLength for all MultipartUploader::createPart streams, therefore potentially using an incorrect, $options['params'] value.
* `Aws\SWF` - Added support for attaching control data to Lambda tasks. Control data lets you attach arbitrary strings to your decisions and history events.

## 3.31.2 - 2017-07-06

* `Aws\DirectoryService` - You can now improve the resilience and performance of your Microsoft AD directory by deploying additional domain controllers. Added UpdateNumberofDomainControllers API that allows you to update the number of domain controllers you want for your directory, and DescribeDomainControllers API that allows you to describe the detailed information of each domain controller of your directory. Also added the 'DesiredNumberOfDomainControllers' field to the DescribeDirectories API output for Microsoft AD.
* `Aws\Ecs` - ECS/ECR now available in BJS
* `Aws\KMS` - This release of AWS Key Management Service introduces the ability to determine whether a key is AWS managed or customer managed.
* `Aws\Kinesis` - You can now encrypt your data at rest within an Amazon Kinesis Stream using server-side encryption. Server-side encryption via AWS KMS makes it easy for customers to meet strict data management requirements by encrypting their data at rest within the Amazon Kinesis Streams, a fully managed real-time data processing service.
* `Aws\SSM` - Amazon EC2 Systems Manager now expands Patching support to Amazon Linux, Red Hat and Ubuntu in addition to the already supported Windows Server.

## 3.31.1 - 2017-07-05

* `Aws\CloudWatch` - We are excited to announce the availability of APIs and CloudFormation support for CloudWatch Dashboards. You can use the new dashboard APIs or CloudFormation templates to dynamically build and maintain dashboards to monitor your infrastructure and applications. There are four new dashboard APIs - PutDashboard, GetDashboard, DeleteDashboards, and ListDashboards APIs. PutDashboard is used to create a new dashboard or modify an existing one whereas GetDashboard is the API to get the details of a specific dashboard. ListDashboards and DeleteDashboards are used to get the names or delete multiple dashboards respectively. Getting started with dashboard APIs is similar to any other AWS APIs. The APIs can be accessed through AWS SDK or through CLI tools.
* `Aws\Route53` - Bug fix for InvalidChangeBatch exception.

## 3.31.0 - 2017-06-30

* `Aws\MarketplaceCommerceAnalytics` - Documentation updates for AWS Marketplace Commerce Analytics.
* `Aws\S3` - API Update for S3: Adding Object Tagging Header to MultipartUpload Initialization
* `Aws\S3` - A new `params` option is available in the `MultipartUploader` and `MultipartCopy` classes for parameters that should be applied to all sub-commands of their upload functionality. This also improves functionality around passing `params` directly to `ObjectUploader` and `ObjectCopier`. A new `before_lookup` callback has been added to `ObjectCopier` for operating on the `HeadObject` command directly; `params` will be passed to HeadObject as well. Since these are changes to existing options, they may alter current functionality.

## 3.30.4 - 2017-06-29

* `Aws\CloudWatchEvents` - CloudWatch Events now allows different AWS accounts to share events with each other through a new resource called event bus. Event buses accept events from AWS services, other AWS accounts and PutEvents API calls. Currently all AWS accounts have one default event bus. To send events to another account, customers simply write rules to match the events of interest and attach an event bus in the receiving account as the target to the rule. The PutTargets API has been updated to allow adding cross account event buses as targets. In addition, we have released two new APIs - PutPermission and RemovePermission - that enables customers to add/remove permissions to their default event bus.
* `Aws\GameLift` - Allow developers to download GameLift fleet creation logs to assist with debugging.
* `Aws\SSM` - Adding Resource Data Sync support to SSM Inventory. New APIs: * CreateResourceDataSync - creates a new resource data sync configuration, * ListResourceDataSync - lists existing resource data sync configurations, * DeleteResourceDataSync - deletes an existing resource data sync configuration. 

## 3.30.3 - 2017-06-27

* `Aws\Greengrass` - AWS Greengrass is now available in new regions.
* `Aws\ServiceCatalog` - Proper tagging of resources is critical to post-launch operations such as billing, cost allocation, and resource management. By using Service Catalog's TagOption Library, administrators can define a library of re-usable TagOptions that conform to company standards, and associate these with Service Catalog portfolios and products. Learn how to move your current tags to the new library, create new TagOptions, and view and associate your library items with portfolios and products. Understand how to ensure that the right tags are created on products launched through Service Catalog and how to provide users with defined selectable tags.

## 3.30.2 - 2017-06-23

* `Aws\Lambda` - The Lambda Invoke API will now throw new exception InvalidRuntimeException (status code 502) for invokes with deprecated runtimes.

## 3.30.1 - 2017-06-22

* `Aws\CodePipeline` - A new API, ListPipelineExecutions, enables you to retrieve summary information about the most recent executions in a pipeline, including pipeline execution ID, status, start time, and last updated time. You can request information for a maximum of 100 executions. Pipeline execution data is available for the most recent 12 months of activity.
* `Aws\DatabaseMigrationService` - Added tagging for DMS certificates.
* `Aws\ElasticLoadBalancing` - Add retry error state to InstanceInService waiter for ElasticLoadBalancer
* `Aws\Lambda` - Lambda is now available in the Canada (Central) region.
* `Aws\Lightsail` - This release adds a new nextPageToken property to the result of the GetOperationsForResource API. Developers can now get the next set of items in a list by making subsequent calls to GetOperationsForResource API with the token from the previous call. This release also deprecates the nextPageCount property, which previously returned null (use the nextPageToken property instead). This release also deprecates the customImageName property on the CreateInstancesRequest class, which was previously ignored by the API.
* `Aws\Route53` - This release reintroduces the HealthCheckInUse exception.

## 3.30.0 - 2017-06-21

* `Aws\DAX` - Amazon DynamoDB Accelerator (DAX) is a fully managed, highly available, in-memory cache for DynamoDB that delivers up to a 10x performance improvement - from milliseconds to microseconds - even at millions of requests per second. DAX does all the heavy lifting required to add in-memory acceleration to your DynamoDB tables, without requiring developers to manage cache invalidation, data population, or cluster management.
* `Aws\Route53` - Amazon Route 53 now supports multivalue answers in response to DNS queries, which lets you route traffic approximately randomly to multiple resources, such as web servers. Create one multivalue answer record for each resource and, optionally, associate an Amazon Route 53 health check with each record, and Amazon Route 53 responds to DNS queries with up to eight healthy records.
* `Aws\SSM` - Adding hierarchy support to the SSM Parameter Store API. Added support tor tagging. New APIs: GetParameter - retrieves one parameter, DeleteParameters - deletes multiple parameters (max number 10), GetParametersByPath - retrieves parameters located in the hierarchy. Updated APIs: PutParameter - added ability to enforce parameter value by applying regex (AllowedPattern), DescribeParameters - modified to support Tag filtering.
* `Aws\WAF` - You can now create, edit, update, and delete a new type of WAF rule with a rate tracking component.

## 3.29.9 - 2017-06-20

* `Aws\WorkDocs` - This release provides a new API to retrieve the activities performed by WorkDocs users.

## 3.29.8 - 2017-06-19

* `Aws\Organizations` - Improvements to Exception Modeling

## 3.29.7 - 2017-06-16

* `Aws\Batch` - AWS Batch is now available in the ap-northeast-1 region.
* `Aws\XRay` - Add a response time histogram to the services in response of GetServiceGraph API.

## 3.29.6 - 2017-06-15

* `Aws\EC2` - Adds API to describe Amazon FPGA Images (AFIs) available to customers, which includes public AFIs, private AFIs that you own, and AFIs owned by other AWS accounts for which you have load permissions.
* `Aws\ECS` - Added support for cpu, memory, and memory reservation container overrides on the RunTask and StartTask APIs.
* `Aws\IoT` - Revert the last release: remove CertificatePem from DescribeCertificate API.
* `Aws\ServiceCatalog` - Added ProvisioningArtifactSummaries to DescribeProductAsAdmin's output to show the provisioning artifacts belong to the product. Allow filtering by SourceProductId in SearchProductsAsAdmin for AWS Marketplace products. Added a verbose option to DescribeProvisioningArtifact to display the CloudFormation template used to create the provisioning artifact.Added DescribeProvisionedProduct API. Changed the type of ProvisionedProduct's Status to be distinct from Record's Status. New ProvisionedProduct's Status are AVAILABLE, UNDER_CHANGE, TAINTED, ERROR. Changed Record's Status set of values to CREATED, IN_PROGRESS, IN_PROGRESS_IN_ERROR, SUCCEEDED, FAILED.

## 3.29.5 - 2017-06-14

* `Aws\ApplicationAutoScaling` - Application Auto Scaling now supports automatic scaling of read and write throughput capacity for DynamoDB tables and global secondary indexes.
* `Aws\CloudDirectory` - Documentation update for Cloud Directory

## 3.29.4 - 2017-06-13

* `Aws\ConfigService` - With this release AWS Config supports the Amazon CloudWatch alarm resource type.

## 3.29.3 - 2017-06-12

* `Aws\RDS` - API Update for RDS: this update enables copy-on-write, a new Aurora MySQL Compatible Edition feature that allows users to restore their database, and support copy of TDE enabled snapshot cross region.

## 3.29.2 - 2017-06-09

* `Aws\OpsWorks` - Tagging Support for AWS OpsWorks Stacks

## 3.29.1 - 2017-06-08

* `Aws\IoT` - In addition to using certificate ID, AWS IoT customers can now obtain the description of a certificate with the certificate PEM.
* `Aws\Pinpoint` - Starting today Amazon Pinpoint adds SMS Text and Email Messaging support in addition to Mobile Push Notifications, providing developers, product managers and marketers with multi-channel messaging capabilities to drive user engagement in their applications. Pinpoint also enables backend services and applications to message users directly and provides advanced user and app analytics to understand user behavior and messaging performance.
* `Aws\Rekognition` - API Update for AmazonRekognition: Adding RecognizeCelebrities API

## 3.29.0 - 2017-06-07

* `Aws\CodeBuild` - Add support to APIs for privileged containers. This change would allow performing privileged operations like starting the Docker daemon inside builds possible in custom docker images.
* `Aws\Greengrass` - AWS Greengrass is software that lets you run local compute, messaging, and device state synchronization for connected devices in a secure way. With AWS Greengrass, connected devices can run AWS Lambda functions, keep device data in sync, and communicate with other devices securely even when not connected to the Internet. Using AWS Lambda, Greengrass ensures your IoT devices can respond quickly to local events, operate with intermittent connections, and minimize the cost of transmitting IoT data to the cloud.

## 3.28.10 - 2017-06-06

* `Aws\ACM` - Documentation update for AWS Certificate Manager.
* `Aws\CloudFront` - Doc update to fix incorrect prefix in S3OriginConfig
* `Aws\IoT` - Update client side validation for SalesForce action.

## 3.28.9 - 2017-06-05

* `Aws\AppStream` - AppStream 2.0 Custom Security Groups allows you to easily control what network resources your streaming instances and images have access to. You can assign up to 5 security groups per Fleet to control the inbound and outbound network access to your streaming instances to specific IP ranges, network protocols, or ports.
* `Aws\AutoScaling` - Autoscaling resource model update.
* `Aws\IoT` -  Added Salesforce action to IoT Rules Engine.

## 3.28.8 - 2017-06-02

* `Aws\KinesisAnalytics` - Kinesis Analytics publishes error messages CloudWatch logs in case of application misconfigurations
* `Aws\WorkDocs` - This release includes new APIs to manage tags and custom metadata on resources and also new APIs to add and retrieve comments at the document level.

## 3.28.7 - 2017-06-01

* `Aws\CodeDeploy` - AWS CodeDeploy has improved how it manages connections to GitHub accounts and repositories. You can now create and store up to 25 connections to GitHub accounts in order to associate AWS CodeDeploy applications with GitHub repositories. Each connection can support multiple repositories. You can create connections to up to 25 different GitHub accounts, or create more than one connection to a single account. The ListGitHubAccountTokenNames command has been introduced to retrieve the names of stored connections to GitHub accounts that you have created. The name of the connection to GitHub used for an AWS CodeDeploy application is also included in the ApplicationInfo structure. Two new fields, lastAttemptedDeployment and lastSuccessfulDeployment, have been added to DeploymentGroupInfo to improve the handling of deployment group information in the AWS CodeDeploy console. Information about these latest deployments can also be retrieved using the GetDeploymentGroup and BatchGetDeployment group requests. Also includes a region update (us-gov-west-1).
* `Aws\CognitoIdentityProvider` - Added support within Amazon Cognito User Pools for 1) a customizable hosted UI for user sign up and sign in and 2) integration of external identity providers.
* `Aws\ElasticLoadBalancingv2` - Update the existing DescribeRules API to support pagination.
* `Aws\LexModelBuildingService` - Updated documentation and added examples for Amazon Lex Runtime Service.

## 3.28.6 - 2017-05-31

* `Aws\RDS` - Amazon RDS customers can now easily and quickly stop and start their DB instances.

## 3.28.5 - 2017-05-30

* `Aws\CloudDirectory` - Cloud Directory has launched support for Typed Links, enabling customers to create object-to-object relationships that are not hierarchical in nature. Typed Links enable customers to quickly query for data along these relationships. Customers can also enforce referential integrity using Typed Links, ensuring data in use is not inadvertently deleted.
* `Aws\S3` - New example snippets for Amazon S3.
* `Aws\S3` - S3 calls are now done with a host style URL by default. Options for path style on the client and command levels are available as `use_path_style_endpoint` and `@use_path_style_endpoint`, respectively. [More details on the differences between the styles can be found here.](http://docs.aws.amazon.com/AmazonS3/latest/dev/UsingBucket.html#access-bucket-intro)

## 3.28.4 - 2017-05-25

* `Aws\AppStream` - Support added for persistent user storage, backed by S3.
* `Aws\Rekognition` - Updated the CompareFaces API response to include orientation information, unmatched faces, landmarks, pose, and quality of the compared faces.

## 3.28.3 - 2017-05-24

* `Aws\IAM` - The unique ID and access key lengths were extended from 32 to 128
* `Aws\STS` - The unique ID and access key lengths were extended from 32 to 128.
* `Aws\StorageGateway` - Two Storage Gateway data types, Tape and TapeArchive, each have a new response element, TapeUsedInBytes. This element helps you manage your virtual tapes. By using TapeUsedInBytes, you can see the amount of data written to each virtual tape.

## 3.28.2 - 2017-05-23

* `Aws\DatabaseMigrationService` - This release adds support for using Amazon S3 and Amazon DynamoDB as targets for database migration, and using MongoDB as a source for database migration. For more information, see the AWS Database Migration Service documentation.

## 3.28.1 - 2017-05-22

* `Aws\ResourceGroupsTaggingAPI` - You can now specify the number of resources returned per page in GetResources operation, as an optional parameter, to easily manage the list of resources returned by your queries.
* `Aws\SQS` - MD5 Validation of `MessageAttributes` is now being performed on `ReceiveMessage` calls. SQS uses a custom encoding for generating the hash input, [details on that scheme are available here.](http://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-attributes.html#sqs-attrib-md5)

## 3.28.0 - 2017-05-18

* `Aws\Athena` - This release adds support for Amazon Athena. Amazon Athena is an interactive query service that makes it easy to analyze data in Amazon S3 using standard SQL. Athena is serverless, so there is no infrastructure to manage, and you pay only for the queries that you run.
* `Aws\Lightsail` - This release adds new APIs that make it easier to set network port configurations on Lightsail instances. Developers can now make a single request to both open and close public ports on an instance using the PutInstancePublicPorts operation.

## 3.27.5 - 2017-05-17

* `Aws\AutoScaling` - Various Auto Scaling documentation updates
* `Aws\CloudWatchEvents` - Various CloudWatch Events documentation updates.
* `Aws\CloudWatchLogs` - Various CloudWatch Logs documentation updates.
* `Aws\Polly` - Amazon Polly adds new German voice "Vicki"

## 3.27.4 - 2017-05-16

* `Aws\CodeDeploy` - This release introduces the previousRevision field in the responses to the GetDeployment and BatchGetDeployments actions. previousRevision provides information about the application revision that was deployed to the deployment group before the most recent successful deployment. Also, the fileExistsBehavior parameter has been added for CreateDeployment action requests. In the past, if the AWS CodeDeploy agent detected files in a target location that weren't part of the application revision from the most recent successful deployment, it would fail the current deployment by default. This new parameter provides options for how the agent handles these files: fail the deployment, retain the content, or overwrite the content.
* `Aws\GameLift` - Allow developers to specify how metrics are grouped in CloudWatch for their GameLift fleets. Developers can also specify how many concurrent game sessions activate on a per-instance basis.
* `Aws\Inspector` - Adds ability to produce an assessment report that includes detailed and comprehensive results of a specified assessment run.
* `Aws\KMS` - Update documentation for KMS.

## 3.27.3 - 2017-05-15

* `Aws\SSM` - UpdateAssociation API now supports updating document name and targets of an association. GetAutomationExecution API can return FailureDetails as an optional field to the StepExecution Object, which contains failure type, failure stage as well as other failure related information for a failed step.

## 3.27.2 - 2017-05-11

* `Aws\ElasticLoadBalancing` - Add a new API to allow customers to describe their account limits, such as load balancer limit, target group limit etc.
* `Aws\ElasticLoadBalancingv2` - Add a new API to allow customers to describe their account limits, such as load balancer limit, target group limit etc.
* `Aws\LexModelBuildingService` - Releasing new DeleteBotVersion, DeleteIntentVersion and DeleteSlotTypeVersion APIs.
* `Aws\Organizations` - AWS Organizations APIs that return an Account object now include the email address associated with the account’s root user.

## 3.27.1 - 2017-05-09

* `Aws\CodeStar` - Updated documentation for AWS CodeStar.
* `Aws\WorkSpaces` - Doc-only Update for WorkSpaces

## 3.27.0 - 2017-05-04

* `Aws\ECS` - Exposes container instance registration time in ECS:DescribeContainerInstances.
* `Aws\Lambda` - Support for UpdateFunctionCode DryRun option
* `Aws\MarketplaceEntitlementService` - AWS Marketplace Entitlement Service enables AWS Marketplace sellers to determine the capacity purchased by their customers.
* `Aws\S3` - Fixed possible security issue in `Transfer`s download `transfer` operation where files could be downloaded to a directory outside the destination directory if the key contained relative paths. Ignoring files to continue with your transfer can be done through passing an iterator of files to download to `Transfer`s parameter: `$source`. These can be generated on `s3://` paths if you have registered the SDK's `StreamWrapper` via `\Aws\recursive_dir_iterator`.

## 3.26.5 - 2017-04-28

* `Aws\CloudFormation` - Adding back the removed waiters and paginators.

## 3.26.4 - 2017-04-28

* `Aws\CloudFormation` - API update for CloudFormation: New optional parameter ClientRequestToken which can be used as an idempotency token to safely retry certain operations as well as tagging StackEvents.
* `Aws\RDS` - The DescribeDBClusterSnapshots API now returns a SourceDBClusterSnapshotArn field which identifies the source DB cluster snapshot of a copied snapshot.
* `Aws\Rekognition` - Fix for missing file type check
* `Aws\SQS` - Adding server-side encryption (SSE) support to SQS by integrating with AWS KMS; adding new queue attributes to SQS CreateQueue, SetQueueAttributes and GetQueueAttributes APIs to support SSE.
* `Aws\Snowball` - The Snowball API has a new exception that can be thrown for list operation requests.

## 3.26.3 - 2017-04-26

* `Aws\RDS` - With Amazon Relational Database Service (Amazon RDS) running MySQL or Amazon Aurora, you can now authenticate to your DB instance using IAM database authentication.

## 3.26.2 - 2017-04-21

* `Aws\AppStream` - The new feature named "Default Internet Access" will enable Internet access from AppStream 2.0 instances - image builders and fleet instances. Admins will check a flag either through AWS management console for AppStream 2.0 or through API while creating an image builder or while creating/updating a fleet.
* `Aws\Kinesis` - Adds a new waiter, StreamNotExists, to Kinesis 

## 3.26.1 - 2017-04-20

* `Aws\DeviceFarm` - API Update for AWS Device Farm: Support for Deals and Promotions 
* `Aws\DirectConnect` - Documentation updates for AWS Direct Connect.
* `Aws\ElasticLoadBalancingv2` - Adding LoadBalancersDeleted waiter for Elasticloadbalancingv2
* `Aws\KMS` - Doc-only update for Key Management Service (KMS): Update docs for GrantConstraints and GenerateRandom
* `Aws\Route53` - Release notes: SDK documentation now includes examples for ChangeResourceRecordSets for all types of resource record set, such as weighted, alias, and failover.
* `Aws\Route53Domains` - Adding examples and other documentation updates.

## 3.26.0 - 2017-04-19

* `Aws\APIGateway` - Add support for "embed" property.
* `Aws\CodeStar` - AWS CodeStar is a cloud-based service for creating, managing, and working with software development projects on AWS. An AWS CodeStar project creates and integrates AWS services for your project development toolchain. AWS CodeStar also manages the permissions required for project users.
* `Aws\EC2` - Adds support for creating an Amazon FPGA Image (AFI) from a specified design checkpoint (DCP).
* `Aws\IAM` - This changes introduces a new IAM role type, Service Linked Role, which works like a normal role but must be managed via services' control. 
* `Aws\Lambda` - Lambda integration with CloudDebugger service to enable customers to enable tracing for the Lambda functions and send trace information to the CloudDebugger service.
* `Aws\LexModelBuildingService` - Amazon Lex is a service for building conversational interfaces into any application using voice and text.
* `Aws\Polly` - API Update for Amazon Polly: Add support for speech marks
* `Aws\Rekognition` - Given an image, the API detects explicit or suggestive adult content in the image and returns a list of corresponding labels with confidence scores, as well as a taxonomy (parent-child relation) for each label.

## 3.25.8 - 2017-04-18

* `Aws\Lambda` - You can use tags to group and filter your Lambda functions, making it easier to analyze them for billing allocation purposes. For more information, see Tagging Lambda Functions.  You can now write or upgrade your Lambda functions using Python version 3.6. For more information, see Programming Model for Authoring Lambda Functions in Python. Note: Features will be rolled out in the US regions on 4/19.

## 3.25.7 - 2017-04-11

* `Aws\APIGateway` - API Gateway request validators
* `Aws\Batch` - API Update for AWS Batch: Customer provided AMI for MANAGED Compute Environment 
* `Aws\GameLift` - Allows developers to utilize an improved workflow when calling our Queues API and introduces a new feature that allows developers to specify a maximum allowable latency per Queue.
* `Aws\OpsWorks` - Cloudwatch Logs agent configuration can now be attached to OpsWorks Layers using CreateLayer and UpdateLayer. OpsWorks will then automatically install and manage the CloudWatch Logs agent on the instances part of the OpsWorks Layer.

## 3.25.6 - 2017-04-07

* `Aws\Redshift` - This update adds the GetClusterCredentials API which is used to get temporary login credentials to the cluster. AccountWithRestoreAccess now has a new member AccountAlias, this is the identifier of the AWS support account authorized to restore the specified snapshot. This is added to support the feature where the customer can share their snapshot with the Amazon Redshift Support Account without having to manually specify the AWS Redshift Service account ID on the AWS Console/API.

## 3.25.5 - 2017-04-06

* `Aws\ElasticLoadBalancingv2` - Adds supports a new condition for host-header conditions to CreateRule and ModifyRule

## 3.25.4 - 2017-04-05

* `Aws\ElastiCache` - ElastiCache added support for testing the Elasticache Multi-AZ feature with Automatic Failover.

## 3.25.3 - 2017-04-04

* `Aws\CloudWatch` - Amazon Web Services announced the immediate availability of two additional alarm configuration rules for Amazon CloudWatch Alarms. The first rule is for configuring missing data treatment. Customers have the options to treat missing data as alarm threshold breached, alarm threshold not breached, maintain alarm state and the current default treatment. The second rule is for alarms based on percentiles metrics that can trigger unnecassarily if the percentile is calculated from a small number of samples. The new rule can treat percentiles with low sample counts as same as missing data. If the first rule is enabled, the same treatment will be applied when an alarm encounters a percentile with low sample counts.

## 3.25.2 - 2017-04-03

* `Aws\LexRuntimeService` - Adds support to PostContent for speech input

## 3.25.1 - 2017-03-31

* `Aws\CloudDirectory` - ListObjectAttributes now supports filtering by facet.

## 3.25.0 - 2017-03-31

* `Aws\CloudFormation` - Adding paginators for ListExports and ListImports
* `Aws\CloudFront` - Amazon CloudFront now supports user configurable HTTP Read and Keep-Alive Idle Timeouts for your Custom Origin Servers
* `Aws\ResourceGroupsTaggingAPI` - Resource Groups Tagging APIs can help you organize your resources and enable you to simplify resource management, access management, and cost allocation.
* `Aws\StorageGateway` - File gateway mode in AWS Storage gateway provides access to objects in S3 as files on a Network File System (NFS) mount point. Once a file share is created, any changes made externally to the S3 bucket will not be reflected by the gateway. Using the cache refresh feature in this update, the customer can trigger an on-demand scan of the keys in their S3 bucket and refresh the file namespace cached on the gateway. It takes as an input the fileShare ARN and refreshes the cache for only that file share. Additionally there is new functionality on file gateway that allows you configure what squash options they would like on their file share, this allows a customer to configure their gateway to not squash root permissions. This can be done by setting options in NfsOptions for CreateNfsFileShare and UpdateNfsFileShare APIs.

## 3.24.9 - 2017-03-28

* `Aws\Batch` - Customers can now provide a retryStrategy as part of the RegisterJobDefinition and SubmitJob API calls. The retryStrategy object has a number value for attempts. This is the number of non successful executions before a job is considered FAILED. In addition, the JobDetail object now has an attempts field and shows all execution attempts.
* `Aws\EC2` - Customers can now tag their Amazon EC2 Instances and Amazon EBS Volumes at the time of their creation. You can do this from the EC2 Instance launch wizard or through the RunInstances or CreateVolume APIs. By tagging resources at the time of creation, you can eliminate the need to run custom tagging scripts after resource creation. In addition, you can now set resource-level permissions on the CreateVolume, CreateTags, DeleteTags, and the RunInstances APIs. This allows you to implement stronger security policies by giving you more granular control over which users and groups have access to these APIs. You can also enforce the use of tagging and control what tag keys and values are set on your resources. When you combine tag usage and resource-level IAM policies together, you can ensure your instances and volumes are properly secured upon creation and achieve more accurate cost allocation reporting. These new features are provided at no additional cost. 

## 3.24.8 - 2017-03-27

* `Aws\SSM` - Updated validation rules for SendCommand and RegisterTaskWithMaintenanceWindow APIs

## 3.24.7 - 2017-03-23

* `Aws\ApplicationAutoScaling` - Application AutoScaling is launching support for a new target resource (AppStream 2.0 Fleets) as a scalable target.

## 3.24.6 - 2017-03-22

* `Aws\ApplicationDiscoveryService` - Adds export configuration options to the AWS Discovery Service API.
* `Aws\ElasticLoadBalancingv2` - Adding waiters for Elastic Load Balancing V2
* `Aws\Lambda` - Adds support for new runtime Node.js v6.10 for AWS Lambda service

## 3.24.5 - 2017-03-21

* `Aws\DirectConnect` - Deprecated DescribeConnectionLoa, DescribeInterconnectLoa, AllocateConnectionOnInterconnect and DescribeConnectionsOnInterconnect operations in favor of DescribeLoa, DescribeLoa, AllocateHostedConnection and DescribeHostedConnections respectively.
* `Aws\MarketplaceCommerceAnalytics` - This update adds a new data set, us_sales_and_use_tax_records, which enables AWS Marketplace sellers to programmatically access to their U.S. Sales and Use Tax report data.
* `Aws\Pinpoint` - Added support for segment endpoints by user attributes in addition to endpoint attributes, publishing raw app analytics and campaign events as events streams to Kinesis and Kinesis Firehose

## 3.24.4 - 2017-03-14
* `Aws\CloudWatchEvents` - Update documentation

## 3.24.3 - 2017-03-13

* `Aws\CloudWatchEvents` - This update extends Target Data Type for configuring Target behavior during invocation.
* `Aws\DeviceFarm` - Network shaping allows users to simulate network connections and conditions while testing their Android, iOS, and web apps with AWS Device Farm.

## 3.24.2 - 2017-03-10

* `Aws\CodeDeploy` - Add paginators for Codedeploy
* `Aws\EMR` - This release includes support for instance fleets in Amazon EMR.

## 3.24.1 - 2017-03-09

* `Aws\APIGateway` - API Gateway has added support for ACM certificates on custom domain names. Both Amazon-issued certificates and uploaded third-part certificates are supported.
* `Aws\CloudDirectory` - Introduces a new Cloud Directory API that enables you to retrieve all available parent paths for any type of object (a node, leaf node, policy node, and index node) in a hierarchy.

## 3.24.0 - 2017-03-08

* `Aws\WorkDocs` - The Administrative SDKs for Amazon WorkDocs provides full administrator level access to WorkDocs site resources, allowing developers to integrate their applications to manage WorkDocs users, content and permissions programmatically

## 3.23.3 - 2017-03-08

* `Aws\RDS` - Add support to using encrypted clusters as cross-region replication masters. Update CopyDBClusterSnapshot API to support encrypted cross region copy of Aurora cluster snapshots.

## 3.23.2 - 2017-03-06

* `Aws\Budgets` - When creating or editing a budget via the AWS Budgets API you can define notifications that are sent to subscribers when the actual or forecasted value for cost or usage exceeds the notificationThreshold associated with the budget notification object. Starting today, the maximum allowed value for the notificationThreshold was raised from 100 to 300. This change was made to give you more flexibility when setting budget notifications.
* `Aws\OpsWorksCM` - OpsWorks for Chef Automate has added a new field "AssociatePublicIpAddress" to the CreateServer request, "CloudFormationStackArn" to the Server model and "TERMINATED" server state.

## 3.23.1 - 2017-02-28

* `Aws\MTurk` - Update namespace for `Amazon Mechanical Turk`

## 3.23.0 - 2017-02-28

* `Aws\DynamoDB` - Time to Live (TTL) is a feature that allows you to define when items in a table expire and can be purged from the database, so that you don't have to track expired data and delete it manually. With TTL enabled on a DynamoDB table, you can set a timestamp for deletion on a per-item basis, allowing you to limit storage usage to only those records that are relevant.
* `Aws\DynamoDBStreams` - Added support for TTL on a DynamoDB tables
* `Aws\IAM` - Added support for AWS Organizations service control policies (SCPs) to SimulatePrincipalPolicy operation. If there are SCPs associated with the simulated user's account, their effect on the result is captured in the OrganizationDecisionDetail element in the EvaluationResult.
* `Aws\MechanicalTurkRequesterService` - Amazon Mechanical Turk is a web service that provides an on-demand, scalable, human workforce to complete jobs that humans can do better than computers, for example, recognizing objects in photos.
* `Aws\Organizations` - AWS Organizations is a web service that enables you to consolidate your multiple AWS accounts into an organization and centrally manage your accounts and their resources.

## 3.22.11 - 2017-02-24

* `Aws\ElasticsearchService` - Added three new API calls to existing Amazon Elasticsearch service to expose Amazon Elasticsearch imposed limits to customers.

## 3.22.10 - 2017-02-24

* `Aws\Ec2` - New EC2 I3 instance type

## 3.22.9 - 2017-02-22

* `Aws\CloudDirectory` - ListObjectAttributes documentation updated based on forum feedback
* `Aws\ElasticBeanstalk` - Elastic Beanstalk adds support for creating and managing custom platform.
* `Aws\GameLift` - Allow developers to configure global queues for creating GameSessions. Allow PlayerData on PlayerSessions to store player-specific data.
* `Aws\Route53` - Added support for operations CreateVPCAssociationAuthorization and DeleteVPCAssociationAuthorization to throw a ConcurrentModification error when a conflicting modification occurs in parallel to the authorizations in place for a given hosted zone.

## 3.22.8 - 2017-02-21

* `Aws\Ec2` - Added the billingProduct parameter to the RegisterImage API

## 3.22.7 - 2017-02-17

* `Aws\DirectConnect` - Adding operations to support new LAG feature

## 3.22.6 - 2017-02-17

* `Aws\CognitoIdentity` - Allow createIdentityPool and updateIdentityPool API to set server side token check value on identity pool
* `Aws\Config` - Enable customers to use dryrun mode for PutEvaluations

## 3.22.5 - 2017-02-15

* `Aws\Kms` - Added support for tagging

## 3.22.4 - 2017-02-14

* `Aws\Ec2` - Added support for new `ModifyVolume` API

## 3.22.3 - 2017-02-10

* Update endpoints.json with valid endpoints

## 3.22.2 - 2017-02-10

* `Aws\StorageGateway` - Added support for addition of clientList parameter to existing File share APIs

## 3.22.1 - 2017-02-09

* `Aws\Ec2` - Added support to associate `IAM profiles` to running instances API
* `Aws\Rekognition` - Added support for `age` to the face description from `DetectFaces` and `IndexFaces`

## 3.22.0 - 2017-02-08

* `Aws\LexRuntimeService` - Added support for new service `Amazon Lex Runtime Service`

## 3.21.6 - 2017-01-27

* `Aws\CloudDirectory` - Added support for new service `AWS Cloud Directory`
* `Aws\CodeDeploy` - Added support for blue/green deployments
* `Aws\Ec2` - Added support to Add instance health check functionality to replace unhealthy EC2 Spot fleet instances with fresh ones.
* `Aws\Rds` -  Upgraded Snapshot Engine Version

## 3.21.5 - 2017-01-25

* `Aws\ElasticLoadBalancing` - Added support for New load balancer type
* `Aws\Rds` - Added support for Cross Region Read Replica Copying

## 3.21.4 - 2017-01-25

* `Aws\CodeCommit` - Added a new API to list the different files between 2 commits 
* `Aws\Ecs` - Added support for Container instance draining

## 3.21.3 - 2017-01-20

* `Aws\Acm` - Updated response elements for DescribeCertificate API in support of managed renewal.

## 3.21.2 - 2017-01-19

* `Aws\Ec2` - Added support for new parameters to SpotPlacement in RequestSpotInstances API

## 3.21.1 - 2017-01-18

* `Aws\Rds` - Added support for `Mysql` to `Aurora` Replication

## 3.21.0 - 2017-01-17

* `Aws\Credentials` - Added support for AssumeRoleCredentialProvider and support for source ini credentials from ./aws/config file in defaultProvider
* `Aws\DynamoDb` - Added tagging Support for Amazon DynamoDB Tables and Indexes
* `Aws\Route53` - Added support for ca-central-1 and eu-west-2 enum values in CloudWatchRegion enum

## 3.20.16 - 2017-01-16

* Fix manifest

## 3.20.15 - 2017-01-16

* `Aws\Cur` - Added Support for new service `AWS CostAndUsageReport`

## 3.20.14 - 2017-01-16

* `Aws\Config` - Updated the models to include InvalidNextTokenException in API response

## 3.20.13 - 2017-01-04

* `Aws\Config` - Added support for customers to use/write rules based on OversizedConfigurationItemChangeNotification mesage type.
* `Aws\MarketplaceAnalytics` - Added support for data set disbursed_amount_by_instance_hours, with historical data available starting 2012-09-04

## 3.20.12 - 2016-12-29

* `Aws\CodeDeploy` - Added support for IAM Session Arns in addition to IAM User Arns for on premise host authentication.
* `Aws\Ecs` - Added the ability to customize the placement of tasks on container instances.

## 3.20.11 - 2016-12-22

* `Aws\ApiGateway` - Added support for generating SDKs in more languages.
* `Aws\ElasticBeanstalk` - Added Support for Resource Lifecycle Feature
* `Aws\Iam`- Added service-specific credentials to IAM service to make it easier to onboard CodeCommit customers

## 3.20.10 - 2016-12-21

* `Aws\Ecr` - Added implementation for Docker Image Manifest V2, Schema 2
* `Aws\Rds` - Added support for Cross Region Encrypted Snapshot Copying (CopyDBSnapshot) 

## 3.20.9 - 2016-12-20

* `Aws\Firehose` - Added Support for Processing Feature
* `Aws\Route53` - Enum updates for eu-west-2 and ca-central-1
* `Aws\StorageGateway` - Added new storage type for files to complement block and tape

## 3.20.8 - 2016-12-19

* `Aws\CognitoIdentity` - Added Groups to Cognito user pools. 
* `Aws\DiscoveryService` - Added new APIs to group discovered servers into Applications with get summary and neighbors. 
  Includes additional filters for `ListConfigurations` and `DescribeAgents` API.

## 3.20.7 - 2016-12-15

* `Aws\CognitoIdentityProvider` - Adding support for fine-grained role-based access control (RBAC)
* `Aws\Ssm` - Adding support for access to the Patch Baseline and Patch Compliance APIs

## 3.20.6 - 2016-12-14

* `Aws\Batch` - Added support for new service `AWS Batch`
* `Aws\CloudWatchLogs` - Added support for associating LogGroups with `AWSTagris` tags
* `Aws\Dms` - Added support for SSL enabled Oracle endpoints
* `Aws\MarketplaceCommerceAnalytics` -  Add new enum to `DataSetType`

## 3.20.5 - 2016-12-12

* `Aws\Credentials` - Fix `EcsCredential` latency issue

## 3.20.4 - 2016-12-08

* `Aws\Cloudfront` - Adding lambda function associations to cache behaviors
* `Aws\Rds` - Add cluster create time to DBCluster
* `Aws\WafRegional` - Adding support for new service `AWS WAF Regional`

## 3.20.3 - 2016-12-07

* `Aws\Config` - Adding support for Redshift resource types
* `Aws\S3` - Adding Version ID to Get/Put ObjectTagging

## 3.20.2 - 2016-12-06

* `Aws\Ec2` - Adding T2.xlarge, T2.2xlarge, and R4 instance type
* `Aws\Config` - Adding support for `DescribeConfigRuleEvaulationStatus`
* `Aws\Pinpoint` - Adding support for fixed type

## 3.20.1 - 2016-12-01

* `Aws\ApiGateway` - Added support for publishing your APIs on `Amazon API Gateway`
  as products on the `AWS Marketplace`
* `Aws\AppStream` - Added support for new service `AWS AppStream`
* `Aws\CodeBuild` - Added support for new service `AWS CodeBuild`
* `Aws\DirectConnect` - Added support for `Ipv6` support
* `Aws\Ec2` - Added support for native `IPv6` support for VPCs
* `Aws\ElasticBeanstalk` - Added support for `CodeBuild` Integration
* `Aws\Lambda` - Added support for new API `GetAccountSettings`
* `Aws\Health` - Added support for new service `AWS Health`
* `Aws\OpsWorksCM` - Added support for new service `AWS OpsWorks Managed Chef`
* `Aws\Pinpoint` - Added support for new service `AWS Pinpoint`
* `Aws\Sfn` - Added support for `AWS Step Functions`
* `Aws\Shield` - Added support for new service `AWS Shield`
* `Aws\SSm` - Added support for 6 new sets of APIs
* `Aws\XRay` - Added support for new service `AWS X-Ray`


## 3.20.0 - 2016-11-30

* `Aws\Lightsail` - Added support for new service `AWS Lightsail`
* `Aws\Polly` - Added support for new service `AWS Polly Service`
* `Aws\Rekognition` - Added support for new service `AWS Rekognition Service`
* `Aws\Snowball` - Added support for a new job type, new APIs, and
  the new `AWS Snowball` Edge device to support local compute and storage use cases

## 3.19.33 - 2016-11-29

* `Aws\S3` - Added support for Storage Insights, Object Tagging, Lifecycle Filtering

## 3.19.32 - 2016-11-22

* `Aws\Cloudformation` - Added support for List-imports API
* `Aws\Glacier` - Added support for retrieving data with different tiers
* `Aws\Route53` - Added support for expanding current IPAddress
  field to accept IPv6 address
* `Aws\S3` - Added support for Glacier retrieval tier information

## 3.19.31 - 2016-11-21

* `Aws\CloudTrail` - Added support for S3 data plane operations
* `Aws\Ecs` - Added support for new "version" field for tasks and container instances

## 3.19.30 - 2016-11-18

* `Aws\ApplicationAutoscaling` - Added  support for a new target resource
  (EMR Instance Groups) as a scalable target

## 3.19.29 - 2016-11-18

* `Aws\ElasticTranscoder` - Added support for multiple media input files
  that can be stitched together
* `Aws\Emr` - Added support for Automatic Scaling of EMR clusters based on metrics
* `Aws\Lambda` -  Added support for Environment variables
* `Aws\GameLift` - Added support for remote access into GameLift managed servers.

## 3.19.28 - 2016-11-17

* `Aws\ApiGateway` - Added support for custom encoding feature
* `Aws\CloudWatch` - Added support for percentile statistic (pN) to metrics and alarms
* `Aws\MarketplaceAnalytics` - Added support for third party metrics
* `Aws\Sqs` - Added support for creating FIFO (first-in-first-out) queues

## 3.19.27 - 2016-11-16

* `Aws\ServiceCatalog` - Added support for new operations
* `Aws\Route53` Added support for cross account VPC Association

## 3.19.26 - 2016-11-15

* `Aws\DirectoryService` - Added support for `SchemaExtensions`
* `Aws\Elasticache` - Added support for `AuthToken`
* `Aws\Kinesis` - Added support for Describe shard limit, open shard count
 and stream creation timestamp

## 3.19.25 - 2016-11-14

* `Aws\CognitoIdentityProvider` - Added support for schema attributes in `CreateUserPool`

## 3.19.24 - 2016-11-10

* `Aws\CloudWatchLogs` - Added support for capability that helps pivot from
 your logs-extracted metrics

## 3.19.23 - 2016-11-03

* `Aws\DirectConnect` - Added support for tagging on `DirectConnect` resources.

## 3.19.22 - 2016-11-02

* `Aws\Ses` - Adding support for `SES` Metrics

## 3.19.21 - 2016-11-01

* `Aws\CloudFormation` - Adding ResourcesToSkip parameter to `ContinueUpdateRollback` API,
  adding support for `ListExports`, new `ChangeSet` types and `Transforms`
* `Aws\Ecr` - Added support for updated paginators

## 3.19.20 - 2016-10-25

* Documentation update for `Autoscaling` and `ElasticloadbalancingV2`

## 3.19.19 - 2016-10-24

* `Aws\Sms` - Added support for new service `AWS Server Migration Service`

## 3.19.18 - 2016-10-20

* `Aws\Budgets` - Added support for new service `AWSBudgetService`

## 3.19.17 - 2016-10-18

* `Aws\Config` -  Added support for S3 Bucket resource type
* `Aws\CloudFront` - Added support for `isIPV6Enabled` property for http distributions
* `Aws\Iot` - Added DynamoActionV2 action to IoT Rules Engine
* `Aws\Rds` - Added support for AWS roles integration with `Aurora Cluster`

## 3.19.16 - 2016-10-17

* `Aws\Route53` - Added support for API updates

## 3.19.15 - 2016-10-13

* `Aws\Acm` - Added support for third-party `SSL/TLS` certificates
* `Aws\ElasticBeanstalk` - Added support for `Pagination` for `DescribeApplicationVersions`
* `Aws\Gamelift` - Added support for resource protection

## 3.19.14 - 2016-10-12

* `Aws\Elasticache` - Added support for Redis Cluster
* `Aws\Ecr` - Added support for new API `DescribeImages`
* `Aws\S3` - Added support for `s3-accelerate.dualstack` endpoint

## 3.19.13 - 2016-10-06

* `Aws\Kms` -  Add `InvalidMarkerException` as modeled exception in `ListKeys`
* `Aws\CognitoIdentityProvider` - Added new operation `AdminCreateUser`
* `Aws\Waf` - Added support for IPV6 in `IPSetDescriptorType`

## 3.19.12 - 2016-09-29

* `Aws\Ec2` - Added support for new Ec2 instance types and
  EC2 Convertible RIs and the EC2 RI regional benefit
* `Aws\S3` - Added support for `partNumber` extension

## 3.19.11 - 2016-09-27

* `Aws\CloudFormation` - Added support for `roleArn`
* `Aws\S3` - Fixed `PostObjectV4` with security token option

## 3.19.10 - 2016-09-22

* `Aws\ApiGateway` - Added new enum values to the service

## 3.19.9 - 2016-09-20

* `Aws\CodeDeploy` - Added support for Rollback deployment
* `Aws\Emr` - Added support for the new end-to-end encryption
* `Aws\Rds` - Added support for local time zone
* `Aws\Redshift` - Added support for `EnhancedVpcRouting` feature

## 3.19.8 - 2016-09-15

* `Aws\Iot` - Added support for changes in `RegisterCertificate` API &
  Adding a new field "cannedAcl" in S3 action
* `Aws\Rds` - Added support for Aurora cluster reader endpoint

## 3.19.7 - 2016-09-13

* `Aws\ServiceCatalog` - Added support for API Update for AWS Service Catalog

## 3.19.6 - 2016-09-08

* `Aws\CloudFront` - Added support for HTTP2

## 3.19.5 - 2016-09-06

* `Aws\Codepipeline` - Added support for pipeline execution details
* `Aws\Rds` - Added support for `DescribeSourceRegions` API
* `Aws\Sns` - Added new exceptions

## 3.19.4 - 2016-09-01

* `Aws\ApplicationAutoScaling` - Added support for automatically scaling an
  Amazon EC2 Spot fleet in order to manage application availability and
  costs during changes in demand based on conditions you define
* `Aws\CognitoIdentity` - Added support for bulk import of users
* `Aws\Rds` - Added support for the information about option conflicts
  to the describe-option-group-options api response
* `Aws\ConfigService` - Added support for a application loadbalancer type
* `Aws\GameLift` - Added support for Linux instance

## 3.19.3 - 2016-08-30

* `Aws\CloudFront` - Added support for QueryString Whitelisting
* `Aws\CodePipeline` - Added support for return pipeline execution details
* `Aws\Ecs` - Added support for simplified waiter
* `Aws\Route53` - Added support for `NAPTR` and new operation `TestDNSAnswer`

## 3.19.2 - 2016-08-23

* `Aws\Rds` - Added support for addition of resource ARNs to `Describe` APIs

## 3.19.1 - 2016-08-18

* `Aws\Ec2` - Added support for for Dedicated Host Reservations and
  API Update for `EC2-SpotFleet`
* `Aws\ElasticLoadBalancingV2` - Fix `ElasticLoadBalancingV2` endpoints
* `Aws\WorkSpaces` - Added support for Hourly WorkSpaces APIs

## 3.19.0 - 2016-08-16

* `Aws\Acm` - Added support for increased tagging limit
* `Aws\ApiGateway` - Added support for API usage plans
* `Aws\Ecs` - Added support for memory reservation and `networkMode` on task definitions

## 3.18.39 - 2016-08-11

* `Aws\AutoScaling` - Added support for `ELB` L7 integration
* `Aws\ElasticLoadBalancing` - Added support for `ELBv2` support
* `Aws\KinesisAnalytics` - Added support for new service that 9allows customers to perform SQL queries against streaming data
* `Aws\Kms` - Added support for importing customer-supplied cryptographic keys
* `Aws\S3` - Added support for IPv6
* `Aws\SnowBall` - Added support for new service `SnowBall`: snowball job management

## 3.18.38 - 2016-08-09

* `Aws\CloudFront` - Added support for tagging API
* `Aws\Ecr` - Added support for `ListImages` filtering
* `Aws\MarketplaceCommerceAnalytics` - Added support for `StartSupportDataExport`
* `Aws\Rds` - Fixing duplicate acceptors in waiters

## 3.18.37 - 2016-08-04

* `Aws\GameLift` - Added support for `GameSession` Search
* `Aws\Lambda` - Added support for throttling reasons, new exception for bad zip file,
  and Event Source Token field for add permission request
* `Aws\Rds` - Added support for `MoveToVpc` feature and S3 Snapshot Ingestion

## 3.18.36 - 2016-08-02

* `Aws\CloudWatchLogs` - Added support for Optional Parameter to PutMetricFilterRequest
* `Aws\Emr` - Added support for Enhanced Debugging
* `Aws\Iot` - Added support for `ListOutgoingCertificates` & `AutoRegistration` flag
* `Aws\MachineLearning` - Added support for computing time and entity timestamp
* `Aws\MarketplaceMetering` - API Constraint Update
* `Aws\Rds` - Added support for license migration between BYOL and LI API Update for `AWS-RDS`,
  Enable `version` with RDS Options

## 3.18.35 - 2016-07-28

* `Aws\Route53Domains` - API Updates

## 3.18.34 - 2016-07-28

* `Aws\CodeDeploy` - Added support for  `DeploymentSuccessful ` waiter
* `Aws\ApiGateway` - Added support for `Cognito`User Pools Auth Support
* `Aws\Ec2` - Added support for DNS for VPC Peering
* `Aws\DirectoryService` - Added support for new API for Microsoft AD to manage routing
* `Aws\Route53Domains` - Added support for `getDomainSuggestions` capability
* `Aws\CognitoIdentity` - Added support for `User Pools`
* `Aws\ElasticsearchService` - Added support for pipeline aggregations to perform advanced
  analytics like moving averages and derivatives, and enhancements to geospatial queries

## 3.18.33 - 2016-07-26

`Aws\Iot` - Added support for Thing Types, ":" in Thing Name, and
  `separator` in `Firehose` action
`Aws\CloudSearchDomain` - Fix query value in `POST` request

## 3.18.32 - 2016-07-21

`Aws\Acm` - Added support for additional field to return for `Describe Certificate `
`Aws\Config` - Added support for `ACM`, `RDS` resource types, introducing
  Hybrid Rules & Forced Evaluation feature
`Aws\CloudSearchDomain` - Convert long query request to `POST`
`Aws\CloudFormation` - Added support for enum value for API parameter :`Capabilities`
`Aws\ElasticTranscoder` - Added support for WAV file output format
`Aws\Ssm` - Fixing missing paginator for SSM `DescribeInstanceInformation`

## 3.18.31 - 2016-07-19

`Aws\Ssm` - Added support for notification
`Aws\DeviceFarm` - Added support for session based APIs

## 3.18.30 - 2016-07-18

Fix composer version constraints.

## 3.18.29 - 2016-07-18

Updating dependency to a version of Guzzle that addresses CVE-2016-5385.
Please upgrade your version of the SDK or Guzzle if you are using the AWS SDK for PHP
in a CGI process that connects to an `http` endpoint.

See https://httpoxy.org for more details on the vulnerability.

## 3.18.28 - 2016-07-13

* `Aws\DatabaseMigrationService` - Added support for SSL Endpoint and Replication
* `Aws\Ecs` - Added support for IAM roles for ECS Tasks
* `Aws\Rds` - Adds new method `CopyDBClusterParameterGroup` and
  new parameter `TargetDBInstanceIdentifier` to `FailoverDBCluster` API

## 3.18.27 - 2016-07-07

* `Aws\ServiceCatalog` - Added support for `Aws\ServiceCatalog`

## 3.18.26 - 2016-07-07

* `Aws\Config` - Added support for `DeleteConfigurationRecorder` API
* `Aws\DirectoryService` - Added support for tagging APIs

## 3.18.25 - 2016-07-05

* `Aws\CodePipeline` - Added support for manual approvals.

## 3.18.24 - 2016-07-01

* Update composer dependency `"guzzlehttp/psr7": "~1.3.1"`

## 3.18.23 - 2016-06-30

* `Aws\DatabaseMigrationService` - Added support for specify `VpcSecurityGroupId`
  for the replication instance
* `Aws\Ssm` - Added support for registering customer servers to enable command function

## 3.18.22 - 2016-06-28

* `Aws\Ec2` - Added support for ENA supported instances
* `Aws\Efs` - Added support for "PerformanceMode" parameter for
  CreateFileSystem and DescribeFileSystems
* `Aws\GameLift` - Added support for  declaring and inspecting game server
  runtime configurations on fleets, including server process launch path,
  parameters, and number of concurrent executions
* `Aws\Iot` - Added support for "update" and "delete" an item
  through Dynamo DB rule
* `Aws\Sns` - Added Worldwide SMS support
* `Aws\Route53` - Added support for BOM region

## 3.18.21 - 2016-06-27

## 3.18.20 - 2016-06-23

* `Aws\CognitoIdentity` - Added support for
  Security Assertion Markup Language (SAML) 2.0.
* `Aws\DirectConnect` - Added support for downloading the Letter of Authorization:
   Connecting Facility Assignment (LOA-CFA) for Connections and Interconnects
* `Aws\Ec2` - Added support for new operations DescribeIdentityIdFormat
  & ModifyIdentityIdFormat

## 3.18.19 - 2016-06-21

* `Aws\CodePipeline` - Added support for Retry Failed Actions
* `Aws\Ec2` - Added support for new VPC resource waiters

## 3.18.18 - 2016-06-14

* `Aws\Rds` - Added support for RDS Cross-region managed binlog replication
* `Aws\CloudTrail` - Added support for new exception to handle
  `KMS InvalidStateException`
* `Aws\Ses` - Added support for enhanced customer notifications

## 3.18.17 - 2016-06-09

* `Aws\S3` -  Fixed StartAfter option in ListObjectsV2 operation

## 3.18.16 - 2016-06-07

* `Aws\Iot` - Added support for string and numeric values in `hashKey`
  and `rangeKey`, update `ListPolicyPrincipals`
* `Aws\MachineLearning` - Added support for tagging operations
* `Aws\Ec2` - Added support for `DescribeSpotFleetRequests` paginator
* `Aws\DynamoDbStreams` - Added support for `ApproximationCreationDateTime`
* `Aws\CloudWatch` - Added support for Alarm waiter


## 3.18.15 - 2016-06-02

* `Aws\Ec2` - Added support for `type` parameter in RequestSpotFleet API
 and `fulfilledCapacity` in DescribeSpotFleetRequests API response

## 3.18.14 - 2016-05-26

* `Aws\ElastiCache` - Added support for exporting a Redis snapshot
  to an Amazon S3 bucket

## 3.18.13 - 2016-05-24

* `Aws\Ec2` - Added support for accessing instance console screenshot
* `Aws\Rds` - Added support for cross-account snapshot sharing

## 3.18.12 - 2016-05-19

* `Aws\ApplicationAutoScaling` - Added support for `Aws\ApplicationAutoScaling`
  service

## 3.18.11 - 2016-05-19

* `Aws\Firehose` - Added support for configurable retry window for
  loading data into Amazon Redshift
* `Aws\Ecs` - Added support for status of ListTaskDefinitionFamilies

## 3.18.10 - 2016-05-18

* `Aws\S3` - Fixed signature with S3 presign request

## 3.18.9 - 2016-05-17

* `Aws\ApplicationDiscoveryService` - Fixed an incorrect model from the previous
  release. To use `AWS Discovery` service, please upgrade to this version
* `Aws\WorkSpaces` - Added support for tagging to categorize `Amazon WorkSpaces`,
  which also allows allocating usage to cost centers from AWS account bill

## 3.18.8 - 2016-05-12

* `Aws\ApplicationDiscoveryService` - Added support for `Aws Discovery` service
* `Aws\CloudFormation` - Added support for `ExecutionStatus` in `ChangeSets`
* `Aws\Ec2` - Added support for identifying stale security groups in VPC
* `Aws\Ssm` - Added support for document sharing feature

## 3.18.7 - 2016-05-10

* `Aws\` - Added support for new region and endpoints
* `Aws\Emr` - Added support for ListInstances API having filter on instance state
* `Aws\ImportExport` - Added support for `Aws\ImportExport` service

## 3.18.6 - 2016-05-05

* `Aws\ApiGateway` - Added support for additional field on Integration to
  control passthrough behavior
* `Aws\CloudTrail` - Deprecates the `SnsTopicName` field in favor of `SnsTopicArn`
* `Aws\Ecs` - Added support for non-comprehensive logDriver enum
* `Aws\Kms` - Added support for "pro-lockout" flag
* `Aws\S3` - Amazon S3 Added a new list type to list objects in buckets
  with a large number of delete markers

## 3.18.5 - 2016-05-03

* `Aws\Api` - Fixed serialization of booleans in querystrings
 * `Aws\OpsWorks` - Added support for default tenancy selection

## 3.18.4 - 2016-04-28

* `Aws\OpsWorks` - Added support for default tenancy selection.
* `Aws\Route53Domains` - Added support for getting contact reachability status
  and resending contact reachability emails.

## 3.18.3 - 2016-04-27

* `Aws\Api` - Fixed parsing empty responses
* `Aws\CognitoIdentityProvider` - Remove non-JSON operations.
* `Aws\Ec2` - Added support for ClassicLink over VPC peering
* `Aws\Ecr` - This update makes it easier to find repository URIs,
  which are now appended to the `#describe_repositories`, `#create_repository`,
  and `#delete_repository` responses.
* `Aws\S3` - Added support for Post Object Signature V4
* `Aws\S3` - Fixed Content-MD5 header for PutBucketReplication

## 3.18.1 - 2016-04-21

* `Aws\Acm` - Added support for tagging.
* `Aws\CognitoIdentity` - Minor update to support some new features of
  `Aws\CognitoIdentityProvider`.
* `Aws\Emr` - Added support for smart targeted resizing.
* `Aws\Iot` - Added support for specifying the SQL rules engine to be used.

## 3.18.0 - 2016-04-19

* `Aws\CognitoIdentityProvider` - Added support for the **Amazon Cognito
  Identity Provider** service.
* `Aws\ElasticBeanstalk` - Added support for automatic platform version upgrades
  with managed updates.
* `Aws\Firehose` - Added support for delivery to AWS Elasticsearch Service.
* `Aws\Kinesis` - Added support for enhanced monitoring.
* `Aws\S3` - Added support for S3 Accelerate.
* `Aws\S3` - Fixed bug where stat cache was not being updated following writes.
* `Aws\Signature` - Fixed inefficiency in S3 presigner.

## 3.17.6 - 2016-04-11

* `Aws\Ec2` - Fixed error codes in EC2 waiters.
* `Aws\Iot` - Added support for registering your own signing CA certificates and
  the X.509 certificates signed by your signing CA certificate.

## 3.17.5 - 2016-04-07

* `Aws\DirectoryService` - Added support for conditional forwarders.
* `Aws\ElasticBeanstalk` - Update client to latest version.
* `Aws\Lambda` - Added support for setting the function runtime as Node.js 4.3,
  as well as updating function configuration to set the runtime.

## 3.17.4 - 2016-04-05

* `Aws\ApiGateway` - Added support for importing REST APIs.
* `Aws\Glacier` - Fixed tree hash bug caused when content was a single zero.
* `Aws\Route53` - Added support for metric-based and regional health checks.
* `Aws\Signature` - Fixed presigning bug where the signed headers query
  parameter value was not lowercased.
* `Aws\Sts` - Added support for getting the caller identity.

## 3.17.3 - 2016-03-29

* `Aws\CloudFormation` - Added support for change sets.
* `Aws\Inspector` - Updated model to latest preview version.
* `Aws\Redshift` - Added support for cluster IAM roles.
* `Aws\Waf` - Added support for XSS protection.

## 3.17.2 - 2016-03-24

* `Aws\ElastiCache` - Added support for vertical scaling.
* `Aws\Rds` - Added support for joining SQL Server DB instances to Active
  Directory domains.
* `Aws\StorageGateway` - Added support for setting the local console password.

## 3.17.1 - 2016-03-22

* `Aws\DeviceFarm` - Added support for managing and purchasing offerings.
* `Aws\Rds` - Added support for customizing failover order in Amazon Aurora
  clusters.

## 3.17.0 - 2016-03-17

* `Aws\CloudHsm` - Added support for adding tags to, removing tags from, and
  listing the tags for a given resource.
* `Aws\Iot` - Added support for new Amazon Elasticsearch Service and Amazon
  Cloudwatch rule actions when creating topic rules.
* `Aws\MarketplaceMetering` - Added support for the **AWSMarketplace Metering**
  service.
* `Aws\S3` - Added support for lifecycle expiration policy for incomplete
  multipart upload and lifecycle expiration policy for expired object delete
  marker.
* `Aws\S3` - Added support for automatically removing delete markers which have
  no non-current versions underneath them.
* Fixed error handling in the timer middleware. Previously, exceptions were
  passed to the success handler instead of any registered error handler.
* Added support for multi-region clients.

## 3.16.0 - 2016-03-15

* `Aws\CodeDeploy` - Added support for getting deployment groups in batches.
* `Aws\DatabaseMigrationService` - Added support for the **AWS Database
Migration Service**.
* `Aws\Ses` - Added support for custom MAIL FROM domains.
* Added support for collecting transfer statistics.

## 3.15.9 - 2016-03-10

* `Aws\GameLift` - Added support for new AutoScaling features.
* `Aws\Iam` - Added support for stable, unique identifying string identifiers on
  each entity returned from IAM:ListEntitiesForPolicy.
* `Aws\Redshift` - Added support for restoring a single table from an Amazon
  Redshift snapshot instead of restoring the entire cluster.

## 3.15.8 - 2016-03-08

* `Aws\CodeCommit` - Added support for repository triggers.

## 3.15.7 - 2016-03-03

* `Aws\DirectoryService` - Added support for SNS notifications.
* `Aws\Ec2` - Added support for Cross VPC Security Group References with VPC
  peering and ClassicLink traffic over VPC peering.

## 3.15.6 - 2016-03-01

* `Aws\ApiGateway` - Added support for flushing all authorizer cache entries on
  a stage.
* `Aws\CloudSearchDomain` - Added support for returning field statistics in the
  response to a search operation.
* `Aws\DynamoDb` - Added support for describing account limits.

## 3.15.5 - 2016-02-25

* `Aws\AutoScaling` - Added support for specifying an instance ID instead of an
  action token when completing lifecycle actions or recording lifecycle action
  heartbeats.
* `Aws\CloudFormation` - Added support for retaining specific resources when
  deleting stacks.
* `Aws\CloudFormation` - Added support for adding tags when updating stacks.
* `Aws\S3` - Fixed bug where `ContentEncoding` and `ContentLength` were not
  returned when calling `HeadObject` on GZipped or deflated objects.
* `Aws\S3` - Fixed iteration bug in `Transfer` encountered when downloading more
  than 1,000 objects.
* `Aws\Sns` - Added support for specifying an encoding on an SNS action.

## 3.15.4 - 2016-02-23

* `Aws\Route53` - Added support for SNI health checks.

## 3.15.3 - 2016-02-18

* `Aws\StorageGateway` - Added support for creating tapes with barcodes.
* `Aws\CodeDeploy` - Added support for setting up triggers for a deployment
  group.

## 3.15.2 - 2016-02-16

* `Aws\Emr` - Added support for adding EBS storage to an EMR instance.
* `Aws\Rds` - Added support for cross-account sharing of encrypted DB snapshots.

## 3.15.1 - 2016-02-11

* `Aws\ApiGateway` - Added support for custom request authorizers.
* `Aws\AutoScaling` - Added waiters for checking on a group's existence,
  deletion, and whether at least the minimum number of instance are in service.
* `Aws\Lambda` - Added support for accessing resources in a VPC from a Lambda
  function.

## 3.15.0 - 2016-02-09

* `Aws\Api` - Added support for specifying what kinds of model constraints to
  validate.
* `Aws\DynamoDb` - Fixed requeueing mechanism in `WriteRequestBatch`.
* `Aws\GameLift` - Added support for the **Amazon GameLift** service.
* `Aws\MarketplaceCommerceAnalytics` - Added support for customer defined values.
* Added an adapter for using an instance of  `Psr\Cache\CacheItemPoolInterface`
  as an instance of `Aws\CacheInterface`.
* Updated JsonCompiler to preserve closing parens in strings in source JSON.
* Updated `Aws\AwsClient` to throw a `RuntimeException` on a serialization
  attempt.

## 3.14.2 - 2016-01-28

* `Aws\Waf` - Added support for size constraints.
* `Aws\Ssm` - Added paginators for `ListAssociations`, `ListCommandInvocations`,
  `ListCommands`, and `ListDocuments`.

## 3.14.1 - 2016-01-22

* `Aws\Acm` - Reverted to standard class naming conventions.

## 3.14.0 - 2016-01-21

* `Aws\ACM` - Added support for the **AWS Certificate Manager** service.
* `Aws\CloudFormation` - Added support for continuing update rollbacks.
* `Aws\CloudFront` - Added support using AWS ACM certificates with CloudFront
  distributions.
* `Aws\IoT` - Added support for topic rules.
* `Aws\S3` - Added handler function to automatically request URL encoding and
  then decode affected fields when no specific encoding type was requested.

## 3.13.1 - 2016-01-19

* `Aws\DeviceFarm` - Added support for running Appium tests written in Python
  against your native, hybrid and browser-based apps on AWS Device Farm.
* `Aws\IotDataPlane` - Fixed handling of invalid JSON returned by the `Publish`
  command.
* `Aws\Sts` - Added support for the `RegionDisabledException` (now returned
  instead of an AccessDenied when an admin hasn't turned on an STS region).

## 3.13.0 - 2016-01-14

* `Aws\CloudFront` - Added support for new origin security features.
* `Aws\CloudWatchEvents` - Added support for the **Amazon CloudWatch Events**
  service.
* `Aws\Ec2` - Added support for scheduled instances.
* `Aws\S3` - Fixed support for using `Iterator`s as a source for `Transfer`
  objects.

## 3.12.2 - 2016-01-12

* `Aws\Ec2` - Added support for DNS resolution of public hostnames to private IP
  addresses when queried over ClassicLink. Additionally, private hosted zones
  associated with your VPC can now be accessed from a linked EC2-Classic
  instance.

## 3.12.1 - 2016-01-06

* `Aws\Route53` - Fixed pagination bug on ListResourceRecordSets command.
* `Aws\Sns` - Added the SNS inbound message validator package to the composer
  suggestions list to aid discoverability.
* Documentation improvements and additions.

## 3.12.0 - 2015-12-21

* `Aws\Ecr` - Added support for the Amazon EC2 Container Registry.
* `Aws\Emr` - Added support for specifying a service security group when calling
  the RunJobFlow API.

## 3.11.7 - 2015-12-17

* `Aws\CloudFront` - Added support for generating signed cookies.
* `Aws\CloudFront` - Added support for GZip compression.
* `Aws\CloudTrail` - Added support for multi-region trails.
* `Aws\Config` - Added for IAM resource types.
* `Aws\Ec2` - Added support for managed NATs.
* `Aws\Rds` - Added support for enhanced monitoring.

## 3.11.6 - 2015-12-15

* `Aws\Ec2` - Added support for specifying encryption on CopyImage commands.

## 3.11.5 - 2015-12-08

* `Aws\AutoScaling` - Added support for setting and describing instance
  protection status.
* `Aws\Emr` - Added support for using release labels instead of version numbers.
* `Aws\Rds` - Added support for Aurora encryption at rest.

## 3.11.4 - 2015-12-03

* `Aws\DirectoryService` - Added support for launching a fully managed Microsoft
  Active Directory.
* `Aws\Rds` - Added support for specifying a port number when modifying database
  instances.
* `Aws\Route53` - Added support for Traffic Flow, a traffic management service.
* `Aws\Ses` - Added support for generating SMTP passwords from credentials.

## 3.11.3 - 2015-12-01

* `Aws\Config` - Update documentation.

## 3.11.2 - 2015-11-23

* `Aws\Config` - Reverted doc model change.

## 3.11.1 - 2015-11-23

* `Aws\Ec2` - Added support for EC2 dedicated hosts.
* `Aws\Ecs` - Added support for task stopped reasons and task start and stop
  times.
* `Aws\ElasticBeanstalk` - Added support for composable web applications.
* `Aws\S3` - Added support for the `aws-exec-read` canned ACL on objects.

## 3.11.0 - 2015-11-19

* `Aws\CognitoIdentity` - Added a CognitoIdentity credentials provider.
* `Aws\DeviceFarm` - Marked app ARN as optional on `ScheduleRun` and
  `GetDevicePoolCompatibility` operations.
* `Aws\DynamoDb` - Fixed bug where calling `session_regenerate_id` without
  changing session data would prevent data from being carried over from the
  previous session ID.
* `Aws\Inspector` - Added support for client-side validation of required
  parameters throughout service.
* Fixed error parser bug where certain errors could throw an uncaught
  parsing exception.

## 3.10.1 - 2015-11-12

* `Aws\Config` - Fixed parsing of null responses.
* `Aws\Rds` - Added support for snapshot attributes.

## 3.10.0 - 2015-11-10

* `Aws\ApiGateway` - Added support for stage variables.
* `Aws\DynamoDb` - Updated the session handler to emit warnings on write and
  delete failures.
* `Aws\DynamoDb` - Fixed session ID assignment timing bug encountered in PHP 7.
* `Aws\S3` - Removed ServerSideEncryption parameter from UploadPart operation.
* Added jitter to the default retry delay algorithm.
* Updated the compatibility test script.

## 3.9.4 - 2015-11-03

* `Aws\DeviceFarm` - Added support for managing projects, device pools, runs,
  and uploads.
* `Aws\Sts` - Added support for 64-character role session names.

## 3.9.3 - 2015-11-02

* `Aws\Iam` - Added support for service-aware policy simulation.

## 3.9.2 - 2015-10-29

* `Aws\ApiGateway` - Fixed parameter name collision that occurred when calling
  `PutIntegration`.
* `Aws\S3` - Added support for asynchronous copy and upload.
* `Aws\S3` - Added support for setting a location constraint other than the
  region of the S3 client.

## 3.9.1 - 2015-10-26

* `Aws\ApiGateway` - Fixed erroneous version number. Previous version number
  support kept for backwards compatibility, but "2015-06-01" should be
  considered deprecated.

## 3.9.0 - 2015-10-26

* `Aws\ApiGateway` - Added support for the **AWS API Gateway** service.
* `Aws\Ssm` - Added support for EC2 Run Command, a new EC2 feature that enables
  you to securely and remotely manage the configuration of your Amazon EC2
  Windows instances.

## 3.8.2 - 2015-10-22

* `Aws\AutoScaling` - Added support for EBS encryption.
* `Aws\Iam` - Added support for resource-based policy simulations.

## 3.8.1 - 2015-10-15

* `Aws\Kms` - Added support for scheduling and cancelling key deletions and
  listing retirable grants.
* `Aws\S3` - Added support for specifying server side encryption on an when
  uploading a part of a multipart upload.

## 3.8.0 - 2015-10-08

* `Aws\Ecs` - Added support for more Docker options hostname, Docker labels,
  working directory, networking disabled, privileged execution, read-only root
  filesystem, DNS servers, DNS search domains, ulimits, log configuration, extra
  hosts (hosts to add to /etc/hosts), and security options (for MLS systems like
  SELinux).
* `Aws\Iot` - Added support for the **AWS IoT** service.
* `Aws\IotDataPlane` - Added support for the **AWS IoT Data Plane** service.
* `Aws\Lambda` - Added support for function versioning.

## 3.7.0 - 2015-10-07

* `Aws\ConfigService` - Added support for config rules, evaluation strategies,
  and compliance querying.
* `Aws\Firehose` - Added support for the **Amazon Kinesis Firehose** service.
* `Aws\Inspector` - Added support for the **Amazon Inspector** service.
* `Aws\Kinesis` - Added support for increasing and decreasing stream retention
  periods.
* `Aws\MarketplaceCommerceAnalytics` - Added support for the **AWS Marketplace
  Commerce Analytics** service.

## 3.6.0 - 2015-10-06

* `Aws\CloudFront` - Added support for WebACL identifiers and related
  operations.
* `Aws\CloudFront` - Fixed URL presigner to always sign URL-encoded URLs.
* `Aws\Ec2` - Added support for spot blocks.
* `Aws\S3` - Fixed byte range specified on multipart copies.
* `Aws\Waf` - Added support for AWS WAF.

## 3.5.0 - 2015-10-01

* `Aws\Cloudtrail` - Added support for log file integrity validation, log
  encryption with AWS KMS–Managed Keys (SSE-KMS), and trail tagging.
* `Aws\ElasticsearchService` - Added support for the Amazon Elasticsearch
  Service.
* `Aws\Rds` - Added support for resource tags.
* `Aws\S3` - Added support for copying objects of any size.
* `Aws\Workspaces` - Added support for storage volume encryption with AWS KMS.

## 3.4.1 - 2015-09-29

* `Aws\CloudFormation` - Added support for specifying affected resource types
  in `CreateStack` and `UpdateStack` operations.
* `Aws\CloudFormation` - Added support for the `DescribeAccountLimits` API.
* `Aws\Ec2` - Added support modifying previously created spot fleet requests.
* `Aws\Ses` - Added support for inbound email APIs.
* Fixed validation to allow using objects implementing `__toString` for string
  fields in serialized output.

## 3.4.0 - 2015-09-24

* `Aws\S3` - Fixed retry handling of networking errors and client socket timeout
  errors to ensure the client `retries` option is respected.
* Added `@method` annotations on all clients to support autocomplete and static
  analysis.
* Added performance tests to the acceptance test suite.
* Fixed error when `getIterator` was called on a paginator with no specified
  `output_token`.
* Added support for reading the `aws_session_token` parameter from credentials
  files.

## 3.3.8 - 2015-09-17

* `Aws\CloudWatchLogs` - Added support for export task operations.

## 3.3.7 - 2015-09-16

* `Aws\S3` - Added support for new `STANDARD_IA` storage class.
* `Aws\S3` - Added support for specifying storage class in cross-region
  replication configuration.
* `Aws\Sqs` - Added a 'QueueExists' waiter to create a queue and wait until it
  has been fully provisioned.

## 3.3.6 - 2015-09-15

* `Aws\Ec2` - Added support for the "diversified" SpotFleet allocation strategy.
* `Aws\Ec2` - Added support for reading `StateMessage` and `DataEncryptionKeyId`
  from a `DescribeSnapshots` response.
* `Aws\Efs` - Added support for using a `MountTargetId` parameter instead of a
  `FileSystemId` parameter with the `DescribeMountTargets` command.
* `Aws\Route53` - Added support for calculated and latency health checks.
* `Aws\S3` - Fixed warning emitted by `BatchDelete` when no matching objects
  were found to delete.

## 3.3.5 - 2015-09-10

* `Aws\Iam` - Added support for new policy simulation APIs.
* `Aws\Kinesis` - Added support for timestamped GetRecords call.
* `Aws\MachineLearning` - Fixed invalid validation constraint on `Predict`
  operation.
* `Aws\S3` - Added support for retrying special error cases with the
  `ListObjects`, `CompleteMultipartUpload`, `CopyObject`, and `UploadPartCopy`.

## 3.3.4 - 2015-09-03

* `Aws\StorageGateway` - Added support for tagging and untagging resources.

## 3.3.3 - 2015-08-31

* `Aws\Ec2` - Added support for using instance weights with the
  `RequestSpotFleet` API.

## 3.3.2 - 2015-08-27

* `Aws\ConfigService` - Added support for the `ListDiscoveredResources`
  operation and new resource types.

## 3.3.1 - 2015-08-25

* `Aws\CodePipeline` - Added support for using encryption keys with artifact
  stores.

## 3.3.0 - 2015-08-20

* `Aws\S3` - Added support for event notification filters.
* Fixed waiter logic to always retry connection errors.
* Added support for per-command retry count overrides.
* Added support for defining custom patterns for the client debug log to use
  to scrub sensitive data from the output logged.
* Moved the work being done by `Aws\JsonCompiler` from run time to build time.
* Fixed bug causing the phar autoloader not to be found when the phar was loaded
  from opcache instead of from the filesystem.

## 3.2.6 - 2015-08-12

* `Aws\ElasticBeanstalk` - Added support for enhanced health reporting.
* `Aws\S3` - Fixed retry middleware to ensure that S3 requests are retried
  following errors raised by the HTTP handler.
* `Aws\S3` - Made the keys of the configuration array passed to the constructor
  of `MultipartUploader` case-insensitive so that its configuration would not
  rely on differently-cased keys from that of the `S3Client::putObject`
  operation.
* Added an endpoint validation step to the `Aws\AwsClient` constructor so that
  invalid endpoint would be reported immediately.

## 3.2.5 - 2015-08-06

* `Aws\Swf` - Added support for invoking AWS Lambda tasks from an Amazon SWF
  workflow.

## 3.2.4 - 2015-08-04

* `Aws\DeviceFarm` - Added support for the `GetAccountSettings` operation and
  update documentation to reflect new iOS support.
* Made PHP7 test failures fail the build.
* Added support for custom user-agent additions.

## 3.2.3 - 2015-07-30

* `Aws\OpsWorks` - Added support for operations on ECS clusters.
* `Aws\Rds` - Added support for cluster operations for Amazon Aurora.

## 3.2.2 - 2015-07-28

* `Aws\S3` - Added support for receiving the storage class in the responses for
  `GetObject` and `HeadObject` operations.
* `Aws\CloudWatchLogs` - Added support for 4 new operations: `PutDestination`,
  `PutDestinationPolicy`, `DescribeDestinations`, and `DeleteDestination`.

## 3.2.1 - 2015-07-23

* **SECURITY FIX**: This release addresses a security issue associated with
  CVE-2015-5723, specifically, fixes improper default directory umask behavior
  that could potentially allow unauthorized modifications of PHP code.
* `Aws\Ec2` - Added support for SpotFleetLaunchSpecification.
* `Aws\Emr` - Added support for Amazon EMR release 4.0.0, which includes a new
  application installation and configuration experience, upgraded versions of
  Hadoop, Hive, and Spark, and now uses open source standards for ports and
  paths. To specify an Amazon EMR release, use the release label parameter (AMI
  versions 3.x and 2.x can still be specified with the AMI version parameter).
* `Aws\Glacier` - Added support for the InitiateVaultLock, GetVaultLock,
  AbortVaultLock, and CompleteVaultLock API operations.
* Fixed a memory leak that occurred when clients were created and never used.
* Updated JsonCompiler by addressing a potential race condition and ensuring
  that caches are invalidated when upgrading to a new version of the SDK.
* Updated protocol and acceptance tests.

## 3.2.0 - 2015-07-14

* `Aws\DeviceFarm` - Added support for AWS DeviceFarm, an app testing service
  that enables you to test your Android and Fire OS apps on real, physical
  phones and tablets that are hosted by AWS.
* `Aws\DynamoDb` - Added support for consistent scans and update streams.
* `Aws\DynamoDbStreams` - Added support for Amazon DynamoDB Streams, giving you
  the ability to subscribe to the transactional log of all changes transpiring
  in your DynamoDB table.
* `Aws\S3` - Fixed checksum encoding on multipart upload of non-seekable
  streams.
* `Aws\S3\StreamWrapper` - Added guard on rename functionality to ensure wrapper
  initialized.


## 3.1.0 - 2015-07-09

* `Aws\CodeCommit` - Added support for AWS CodeCommit, a secure, highly
  scalable, managed source control service that hosts private Git repositories.
* `Aws\CodePipeline` - Added support for AWS CodePipeline, a continuous delivery
  service that enables you to model, visualize, and automate the steps required
  to release your software.
* `Aws\Iam` - Added support for uploading SSH public keys for authentication
  with AWS CodeCommit.
* `Aws\Ses` - Added support for cross-account sending through the sending
  authorization feature.

## 3.0.7 - 2015-07-07

* `Aws\AutoScaling` - Added support for step policies.
* `Aws\CloudHsm` - Fixed a naming collision with the `GetConfig` operation. This
  operation is now available through the `GetConfigFiles` method.
* `Aws\DynamoDb` - Improved performance when unmarshalling complex documents.
* `Aws\DynamoDb` - Fixed checksum comparison of uncompressed responses.
* `Aws\Ec2` - Added support for encrypted snapshots.
* `Aws\S3` - Added support for user-provided SHA256 checksums for S3 uploads.
* `Aws\S3` - Added support for custom protocols in `Aws\S3\StreamWrapper`.
* Added cucumber integration tests.
* Updated the test suite to be compatible with PHP 7-alpha 2.

## 3.0.6 - 2015-06-24

* `Aws\CloudFront` - Added support for configurable `MaxTTL` and `DefaultTTL`.
* `Aws\ConfigService` - Added support for recording changes for specific
  resource types.
* `Aws\Ecs` - Added support for sorting, deregistering, and overriding
  environment variables for task definitions.
* `Aws\Glacier` - Added support for the `AddTagsToVault`, `ListTagsForVault`,
  and `RemoveTagsFromVault` API operations.
* `Aws\OpwWorks` - Added support for specifying agent versions to be used on
  instances.
* `Aws\Redshift` - Added support for the `CreateSnapshotCopyGrant`,
  `DescribeSnapshotCopyGrants`, and `DeleteSnapshotCopyGrant` API operations.
* Fixed XML attribute serialization.

## 3.0.5 - 2015-06-18

* `Aws\CognitoSync` - Fixed an issue in the Signature Version 4 implementation
  that was causing issues when signing requests to the Cognito Sync service.
* `Aws\ConfigService` - Fixed an issue that was preventing the
  `ConfigServiceClient` from working properly.
* `Aws\Ecs` - Added support for sorting, deregistering, and overriding
  environment variables for task definitions.
* `Aws\Iam` - Added new paginator and waiter configurations.
* `Aws\S3` - Added support for the `SaveAs` parameter that was in V2.
* `Aws\Sqs` - Fixed an issue that was preventing batch message deletion from
  working properly.
* `Aws` - The `Aws\Sdk::createClient()` method is no longer case-sensitive with
  service names.

## 3.0.4 - 2015-06-11

* `Aws\AutoScaling` - Added support for attaching and detaching load balancers.
* `Aws\CloudWatchLogs` - Added support for the PutSubscriptionFilter,
  DescribeSubscriptionFilters, and DeleteSubscriptionFilter operations.
* `Aws\CognitoIdentity` - Added support for the DeleteIdentities operation,
  and hiding disabled identities with the ListIdentities operation.
* `Aws\Ec2` - Added support for VPC flow logs and the M4 instance types.
* `Aws\Ecs` - Added support for the UpdateContainerAgent operation.
* `Aws\S3` - Improvements to how errors are handled in the `StreamWrapper`.
* `Aws\StorageGateway` - Added support for the ListVolumeInitiators operation.
* `Aws` - Fixes a bug such that empty maps are handled correctly in JSON
  requests.

## 3.0.3 - 2015-06-01

* `Aws\MachineLearning` - Fixed the `Predict` operation to use the provided
  `PredictEndpoint` as the host.

## 3.0.2 - 2015-05-29

* `Aws` - Fixed an issue preventing some clients from being instantiated via
  their constructors due to a mismatch between class name and endpoint prefix.

## 3.0.1 - 2015-05-28

* `Aws\Lambda` - Added Amazon S3 upload support.

## 3.0.0 - 2015-05-27

* Asynchronous requests.
    * Features like _waiters_ and _multipart uploaders_ can also be used
      asynchronously.
    * Asynchronous workflows can be created using _promises_ and _coroutines_.
    * Improved performance of concurrent/batched requests via _command pools_.
* Decoupled HTTP layer.
    * [Guzzle 6](http://guzzlephp.org) is used by default to send requests,
      but Guzzle 5 is also supported out of the box.
    * The SDK can now work in environments where cURL is not available.
    * Custom HTTP handlers are also supported.
* Follows the [PSR-4 and PSR-7 standards](http://php-fig.org).
* Middleware system for customizing service client behavior.
* Flexible _paginators_ for iterating through paginated results.
* Ability to query data from _result_ and _paginator_ objects with
  [JMESPath](http://jmespath.org/).
* Easy debugging via the `'debug'` client configuration option.
* Customizable retries via the `'retries'` client configuration option.
* More flexibility in credential loading via _credential providers_.
* Strictly follows the [SemVer](http://semver.org/) standard going forward.
* **For more details about what has changed, see the
  [Migration Guide](http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/migration.html)**.

## 2.8.7 - 2015-05-26

* `Aws\Efs` - Added support for the [Amazon Elastic File System (Amazon
  EFS)](http://aws.amazon.com/efs/)
* Failing to parse an XML error response will now fail gracefully as a
  `PhpInternalXmlParseError` AWS error code.

## 2.8.6 - 2015-05-21

* `Aws\ElasticBeanstalk` - Added support for ResourceName configuration.
* `Aws\ElasticTranscoder` - Added support for configuring AudioPackingMode and
  additional CodecOptions.
* `Aws\Kinesis` - Added support for MillisBehindLatest in the result of
  GetRecordsOutput.
* `Aws\Kms` - Added support for the UpdateAlias operation.
* `Aws\Lambda` - Fixed an issue with the UpdateFunctionCode operation.

## 2.8.5 - 2015-05-18

* `Aws\Ec2\Ec2Client` - Added support for the new spot fleet API operations.
* `Aws\OpsWorks\OpsWorksClient` - Added support for custom auto-scaling based
  on CloudWatch alarms.

## 2.8.4 - 2015-05-14

* `Aws\DirectoryService` - Added support for the AWS Directory Service.
* `Aws\CloudWatchLogs` - Adds support for the FilterLogEvents operation.
* `Aws\CloudFormation` - Adds additional data to the GetTemplateSummary
  operation.
* `Aws\Ec2` - Adds support for Amazon VPC endpoints for Amazon S3 and APIs for
  migrating Elastic IP Address from EC2-Classic to EC2-VPC.
* `Aws\Ec2` - Fixed an issue with cross-region CopySnapshot such that it now
  works with temporary credentials.
* `Aws\Common` - During credential discovery, an invalid credentials file now
  allows failover to Instance Profile credentials.

## 2.8.3 - 2015-05-07

* `Aws\Glacier` - Added support for vault access policies.
* `Aws\Route53` - Fixed a `GetCheckerIpRangesResponse` response parsing issue.
* `Aws\S3` - Retrying CompleteMultipartUpload failures by retrying the request.
* `Aws\S3` - Corrected some response handling in the S3 multipart upload
   abstraction.
* Expiring instance metadata credentials 30 minutes in advance for more eager
  refreshes before the credentials expire.

## 2.8.2 - 2015-04-23

* `Aws\Ec2` - Added support for new VM Import APIs, `including ImportImage`.
* `Aws\Iam` - Added support for the `GetAccessKeyLastUsed` operation.
* `Aws\CloudSearchDomain` - Search responses now include the expressions requested.

## 2.8.1 - 2015-04-16

* `Aws\ConfigService` - Added the 'GetResourceConfigHistory' iterator.
* `Aws\CognitoSync` - Added support for events.
* `Aws\Lambda` - Fixed an issue with the Invoke operation.

## 2.8.0 - 2015-04-09

See the [Upgrading Guide](https://github.com/aws/aws-sdk-php/blob/master/UPGRADING.md)
for details about any changes you may need to make to your code for this upgrade.

* `Aws\MachineLearning` - Added support for the Amazon Machine Learning service.
* `Aws\WorkSpaces` - Added support for the Amazon WorkSpaces service.
* `Aws\Ecs` - Added support for the ECS service scheduler operations.
* `Aws\S3` - Added support for the `getBucketNotificationConfiguration` and
  `putBucketNotificationConfiguration` operations to the `S3Client` to replace
  the, now deprecated, `getBucketNotification` and `putBucketNotification`
  operations.
* [BC] `Aws\Lambda` - Added support for the new AWS Lambda API, which has been
  changed based on customer feedback during Lambda's preview period.
* `Aws\Common` - Deprecated "facades". They will not be present in Version 3 of
  the SDK.
* `Aws\Common` - Added `getAwsErrorCode`, `getAwsErrorType` and `getAwsRequestId`
  methods to the `ServiceResponseException` to be forward-compatible with
  Version 3 of the SDK.

## 2.7.27 - 2015-04-07

* `Aws\DataPipeline` - Added support for `DeactivatePipeline`
* `Aws\ElasticBeanstalk` - Added support for `AbortEnvironmentUpdate`

## 2.7.26 - 2015-04-02

* `Aws\CodeDeploy` - Added support deployments to on-premises instances.
* `Aws\Rds` - Added support for the `DescribeCertificates` operation.
* `Aws\ElasticTranscoder` - Added support for protecting content with PlayReady
  Digital Rights Management (DRM).

## 2.7.25 - 2015-03-26

* `Aws\ElasticTranscoder` - Added support for job timing.
* `Aws\Iam` - Added `NamedPolicy` to `GetAccountAuthorizationDetails`.
* `Aws\OpsWorks` - Added `BlockDeviceMapping` support.

## 2.7.24 - 2015-03-24

* `Aws\S3` - Added support for cross-region replication.
* `Aws\S3` - Added support for ["Requester Pays" buckets](http://docs.aws.amazon.com/AmazonS3/latest/dev/RequesterPaysBuckets.html).

## 2.7.23 - 2015-03-19

* `Aws\ElasticTranscoder` - API update to support AppliedColorSpaceConversion.
* `Aws\CloudSearchDomain` - Adding 504 status code to retry list.

## 2.7.22 - 2015-03-12

* `Aws\CloudFront` - Fixed #482, which affected pre-signing CloudFront URLs.
* `Aws\CloudTrail` - Added support for the `LookupEvents` operation.
* `Aws\CloudWatchLogs` - Added ordering parameters to the `DescribeLogStreams`
* `Aws\Ec2` - Added pagination parameters to the `DescribeSnapshots` operation.
  operation.

## 2.7.21 - 2015-03-04

* `Aws\CognitoSync` - Added support for Amazon Cognito Streams.

## 2.7.20 - 2015-02-23

* `Aws\DataPipeline` - Added support for pipeline tagging via the `AddTags` and
  `RemoveTags` operations.
* `Aws\Route53` - Added support for the `GetHostedZoneCount` and
  `ListHostedZonesByName` operations.

## 2.7.19 - 2015-02-20

* `Aws\CloudFront` - Added support for origin paths in web distributions.
* `Aws\Ecs` - Added support for specifying volumes and mount points. Also
* `Aws\ElasticTranscoder` - Added support for cross-regional resource warnings.
* `Aws\Route53Domains` - Add iterators for `ListDomains` and `ListOperations`.
* `Aws\Ssm` - Added support for the **Amazon Simple Systems Management Service
  (SSM)**.
* `Aws\Sts` - Added support for regional endpoints.
  switched the client to use a JSON protocol.
* Changed our CHANGELOG format. ;-)

## 2.7.18 - 2015-02-12

* Added support for named and managed policies to the IAM client.
* Added support for tagging operations to the Route 53 Domains client.
* Added support for tagging operations to the ElastiCache client.
* Added support for the Scan API for secondary indexes to the DynamoDB client.
* Added forward compatibility for the `'credentials'`, `'endpoint'`, and
  `'http'` configuration options.
* Made the `marshalValue()` and `unmarshalValue()` methods public in the
  DynamoDB Marshaler.

## 2.7.17 - 2015-01-27

* Added support for `getShippingLabel` to the AWS Import/Export client.
* Added support for online indexing to the DynamoDB client.
* Updated the AWS Lambda client.

## 2.7.16 - 2015-01-20

* Added support for custom security groups to the Amazon EMR client.
* Added support for the latest APIs to the Amazon Cognito Identity client.
* Added support for ClassicLink to the Auto Scaling client.
* Added the ability to set a client's API version to "latest" for forwards
  compatibility with v3.

## 2.7.15 - 2015-01-15

* Added support for [HLS Content Protection](https://aws.amazon.com/releasenotes/3388917394239147)
  to the Elastic Transcoder client.
* Updated client factory logic to add the `SignatureListener`, even when
  `NullCredentials` have been specified. This way, you can update a client's
  credentials later if you want to begin signing requests.

## 2.7.14 - 2015-01-09

* Fixed a regression in the CloudSearch Domain client (#448).

## 2.7.13 - 2015-01-08

* Added the Amazon EC2 Container Service client.
* Added the Amazon CloudHSM client.
* Added support for dynamic fields to the Amazon CloudSearch client.
* Added support for the ClassicLink feature to the Amazon EC2 client.
* Updated the Amazon RDS client to use the latest 2014-10-31 API.
* Updated S3 signature so retries use a new Date header on each attempt.

## 2.7.12 - 2014-12-18

* Added support for task priorities to Amazon Simple Workflow Service.

## 2.7.11 - 2014-12-17

* Updated Amazon EMR to the latest API version.
* Added support for for the new ResetCache API operation to AWS Storage Gateway.

## 2.7.10 - 2014-12-12

* Added support for user data to Amazon Elastic Transcoder.
* Added support for data retrieval policies and audit logging to the Amazon
  Glacier client.
* Corrected the AWS Security Token Service endpoint.

## 2.7.9 - 2014-12-08

* The Amazon Simple Queue Service client adds support for the PurgeQueue
  operation.
* You can now use AWS OpsWorks with existing EC2 instances and on-premises
  servers.

## 2.7.8 - 2014-12-04

* Added support for the `PutRecords` batch operation to `KinesisClient`.
* Added support for the `GetAccountAuthorizationDetails` operation to the
  `IamClient`.
* Added support for the `UpdateHostedZoneComment` operation to `Route53Client`.
* Added iterators for `ListEventSources` and `ListFunctions` operations the
  `LambdaClient`.

## 2.7.7 - 2014-11-25

* Added a DynamoDB `Marshaler` class, that allows you to marshal JSON documents
  or native PHP arrays to the format that DynamoDB requires. You can also
  unmarshal item data from operation results back into JSON documents or native
  PHP arrays.
* Added support for media file encryption to Amazon Elastic Transcoder.
* Removing a few superfluous `x-amz-server-side-encryption-aws-kms-key-id` from
  the Amazon S3 model.
* Added support for using AWS Data Pipeline templates to create pipelines and
  bind values to parameters in the pipeline.

## 2.7.6 - 2014-11-20

* Added support for AWS KMS integration to the Amazon Redshift Client.
* Fixed cn-north-1 endpoint for AWS Identity and Access Management.
* Updated `S3Client::getBucketLocation` method to work cross-region regardless
  of the region's signature requirements.
* Fixed an issue with the DynamoDbClient that allows it to work better with
  with DynamoDB Local.

## 2.7.5 - 2014-11-13

* Added support for AWS Lambda.
* Added support for event notifications to the Amazon S3 client.
* Fixed an issue with S3 pre-signed URLs when using Signature V4.

## 2.7.4 - 2014-11-12

* Added support for the AWS Key Management Service (AWS KMS).
* Added support for AWS CodeDeploy.
* Added support for AWS Config.
* Added support for AWS KMS encryption to the Amazon S3 client.
* Added support for AWS KMS encryption to the Amazon EC2 client.
* Added support for Amazon CloudWatch Logs delivery to the AWS CloudTrail
  client.
* Added the GetTemplateSummary operation to the AWS CloudFormation client.
* Fixed an issue with sending signature version 4 Amazon S3 requests that
  contained a 0 length body.

## 2.7.3 - 2014-11-06

* Added support for private DNS for Amazon Virtual Private Clouds, health check
  failure reasons, and reusable delegation sets to the Amazon Route 53 client.
* Updated the CloudFront model.
* Added support for configuring push synchronization to the Cognito Sync client.
* Updated docblocks in a few S3 and Glacier classes to improve IDE experience.

## 3.0.0-beta.1 - 2014-10-14

* New requirements on Guzzle 5 and PHP 5.5.
* Event system now uses Guzzle 5 events and no longer utilizes Symfony2.
* `version` and `region` are now required parameter for each client
  constructor. You can op-into using the latest version of a service by
  setting `version` to `latest`.
* Removed `Aws\S3\ResumableDownload`.
* More information to follow.

## 2.7.2 - 2014-10-23

* Updated AWS Identity and Access Management (IAM) to the latest version.
* Updated Amazon Cognito Identity client to the latest version.
* Added auto-renew support to the Amazon Route 53 Domains client.
* Updated Amazon EC2 to the latest version.

## 2.7.1 - 2014-10-16

* Updated the Amazon RDS client to the 2014-09-01 API version.
* Added support for advanced Japanese language processing to the Amazon
  CloudSearch client.

## 2.7.0 - 2014-10-08

* Added document model support to the Amazon DynamoDB client, including support
  for the new data types (`L`, `M`, `BOOL`, and `NULL`), nested attributes, and
  expressions.
* Deprecated the `Aws\DynamoDb\Model\Attribute`, `Aws\DynamoDb\Model\Item`,
  and `Aws\DynamoDb\Iterator\ItemIterator` classes, and the
  `Aws\DynamoDb\DynamoDbClient::formatValue` and
  `Aws\DynamoDb\DynamoDbClient::formatAttribute` methods, since they do not
  support the new types in the DynamoDB document model. These deprecated classes
  and methods still work reliably with `S`, `N`, `B`, `SS`, `NS`, and `BS`
  attributes.
* Updated the Amazon DynamoDB client to permanently disable client-side
  parameter validation. This needed to be done in order to support the new
  document model features.
* Updated the Amazon EC2 client to sign requests with Signature V4.
* Fixed an issue in the S3 service description to make the `VersionId`
  work in `S3Client::restoreObject`.

## 2.6.16 - 2014-09-11

* Added support for tagging to the Amazon Kinesis client.
* Added support for setting environment variables to the AWS OpsWorks client.
* Fixed issue #334 to allow the `before_upload` callback to work in the
  `S3Client::upload` method.
* Fixed an issue in the Signature V4 signer that was causing an issue with some
  CloudSearch Domain operations.

## 2.6.15 - 2014-08-14

* Added support for signing requests to the Amazon CloudSearch Domain client.
* Added support for creating anonymous clients.

## 2.6.14 - 2014-08-11

* Added support for tagging to the Elastic Load Balancing client.

## 2.6.13 - 2014-07-31

* Added support for configurable idle timeouts to the Elastic Load Balancing
  client.
* Added support for Lifecycle Hooks, Detach Instances, and Standby to the
  AutoScaling client.
* Added support for creating Amazon ElastiCache for Memcached clusters with
  nodes in multiple availability zones.
* Added minor fixes to the Amazon EC2 model for ImportVolume,
  DescribeNetworkInterfaceAttribute, and DeleteVpcPeeringConnection
* Added support for getGeoLocation and listGeoLocations to the
  Amazon Route 53 client.
* Added support for Amazon Route 53 Domains.
* Fixed an issue with deleting nested folders in the Amazon S3 stream wrapper.
* Fixed an issue with the Amazon S3 sync abstraction to ensure that S3->S3
  communication works correctly.
* Added stricter validation to the Amazon SNS MessageValidator.

## 2.6.12 - 2014-07-16

* Added support for adding attachments to support case communications to the
  AWS Support API client.
* Added support for credential reports and password rotation features to the
  AWS IAM client.
* Added the `ap-northeast-1`, `ap-southeast-1`, and `ap-southeast-2` regions to
  the Amazon Kinesis client.
* Added a `listFilter` stream context option that can be used when using
  `opendir()` and the Amazon S3 stream wrapper. This option is used to filter
  out specific objects from the files yielded from the stream wrapper.
* Fixed #322 so that the download sync builder ignores objects that have a
  `GLACIER` storage class.
* Fixed an issue with the S3 SSE-C logic so that HTTPS is only required when
  the SSE-C parameters are provided.
* Updated the Travis configuration to include running HHVM tests.

## 2.6.11 - 2014-07-09

* Added support for **Amazon Cognito Identity**.
* Added support for **Amazon Cognito Sync**.
* Added support for **Amazon CloudWatch Logs**.
* Added support for editing existing health checks and associating health checks
  with tags to the Amazon Route 53 client.
* Added the ModifySubnetAttribute operation to the Amazon EC2 client.

## 2.6.10 - 2014-07-02

* Added the `ap-northeast-1`, `ap-southeast-1`, and `sa-east-1` regions to the
  Amazon CloudTrail client.
* Added the `eu-west-1` and `us-west-2` regions to the Amazon Kinesis client.
* Fixed an issue with the SignatureV4 implementation when used with Amazon S3.
* Fixed an issue with a test that was causing failures when run on EC2 instances
  that have associated Instance Metadata credentials.

## 2.6.9 - 2014-06-26

* Added support for the CloudSearchDomain client, which allows you to search and
  upload documents to your CloudSearch domains.
* Added support for delivery notifications to the Amazon SES client.
* Updated the CloudFront client to support the 2014-05-31 API.
* Merged PR #316 as a better solution for issue #309.

## 2.6.8 - 2014-06-20

* Added support for closed captions to the Elastic Transcoder client.
* Added support for IAM roles to the Elastic MapReduce client.
* Updated the S3 PostObject to ease customization.
* Fixed an issue in some EC2 waiters by merging PR #306.
* Fixed an issue with the DynamoDB `WriteRequestBatch` by merging PR #310.
* Fixed issue #309, where the `url_stat()` logic in the S3 Stream Wrapper was
  affected by a change in PHP 5.5.13.

## 2.6.7 - 2014-06-12

* Added support for Amazon S3 server-side encryption using customer-provided
  encryption keys.
* Updated Amazon SNS to support message attributes.
* Updated the Amazon Redshift model to support new cluster parameters.
* Updated PHPUnit dev dependency to 4.* to work around a PHP serializing bug.

## 2.6.6 - 2014-05-29

* Added support for the [Desired Partition Count scaling
  option](http://aws.amazon.com/releasenotes/2440176739861815) to the
  CloudSearch client. Hebrew is also now a supported language.
* Updated the STS service description to the latest version.
* [Docs] Updated some of the documentation about credential profiles.
* Fixed an issue with the regular expression in the `S3Client::isValidBucketName`
  method. See #298.

## 2.6.5 - 2014-05-22

* Added cross-region support for the Amazon EC2 CopySnapshot operation.
* Added AWS Relational Database (RDS) support to the AWS OpsWorks client.
* Added support for tagging environments to the AWS Elastic Beanstalk client.
* Refactored the signature version 4 implementation to be able to pre-sign
  most operations.

## 2.6.4 - 2014-05-20

* Added support for lifecycles on versioning enabled buckets to the Amazon S3
  client.
* Fixed an Amazon S3 sync issue which resulted in unnecessary transfers when no
  `$keyPrefix` argument was utilized.
* Corrected the `CopySourceIfMatch` and `CopySourceIfNoneMatch` parameter for
  Amazon S3 to not use a timestamp shape.
* Corrected the sending of Amazon S3 PutBucketVersioning requests that utilize
  the `MFADelete` parameter.

## 2.6.3 - 2014-05-14

* Added the ability to modify Amazon SNS topic settings to the UpdateStack
  operation of the AWS CloudFormation client.
* Added support for the us-west-1, ap-southeast-2, and eu-west-1 regions to the
  AWS CloudTrail client.
* Removed no longer utilized AWS CloudTrail shapes from the model.

## 2.6.2 - 2014-05-06

* Added support for Amazon SQS message attributes.
* Fixed Amazon S3 multi-part uploads so that manually set ContentType values are not overwritten.
* No longer recalculating file sizes when an Amazon S3 socket timeout occurs because this was causing issues with
  multi-part uploads and it is very unlikely ever the culprit of a socket timeout.
* Added better environment variable detection.

## 2.6.1 - 2014-04-25

* Added support for the `~/.aws/credentials` INI file and credential profiles (via the `profile` option) as a safer
  alternative to using explicit credentials with the `key` and `secret` options.
* Added support for query filters and improved conditional expressions to the Amazon DynamoDB client.
* Added support for the `ChefConfiguration` parameter to a few operations on the AWS OpsWorks Client.
* Added support for Redis cache cluster snapshots to the Amazon ElastiCache client.
* Added support for the `PlacementTenancy` parameter to the `CreateLaunchConfiguration` operation of the Auto Scaling
  client.
* Added support for the new R3 instance types to the Amazon EC2 client.
* Added the `SpotInstanceRequestFulfilled` waiter to the Amazon EC2 client (see #241).
* Improved the S3 Stream Wrapper by adding support for deleting pseudo directories (#264), updating error handling
  (#276), and fixing `is_link()` for non-existent keys (#268).
* Fixed #252 and updated the DynamoDB `WriteRequestBatch` abstraction to handle batches that were completely rejected
  due to exceeding provisioned throughput.
* Updated the SDK to support Guzzle 3.9.x

## 2.6.0 - 2014-03-25

* [BC] Updated the Amazon CloudSearch client to use the new 2013-01-01 API version (see [their release
  notes](http://aws.amazon.com/releasenotes/6125075708216342)). This API version of CloudSearch is significantly
  different than the previous one, and is not backwards compatible. See the
  [Upgrading Guide](https://github.com/aws/aws-sdk-php/blob/master/UPGRADING.md) for more details.
* Added support for the VPC peering features to the Amazon EC2 client.
* Updated the Amazon EC2 client to use the new 2014-02-01 API version.
* Added support for [resize progress data and the Cluster Revision Number
  parameter](http://aws.amazon.com/releasenotes/0485739709714318) to the Amazon Redshift client.
* Added the `ap-northeast-1`, `ap-southeast-2`, and `sa-east-1` regions to the Amazon CloudSearch client.

## 2.5.4 - 2014-03-20

* Added support for [access logs](http://docs.aws.amazon.com/ElasticLoadBalancing/latest/DeveloperGuide/access-log-collection.html)
  to the Elastic Load Balancing client.
* Updated the Elastic Load Balancing client to the latest API version.
* Added support for the `AWS_SECRET_ACCESS_KEY` environment variables.
* Updated the Amazon CloudFront client to use the 2014-01-31 API version. See [their release
  notes](http://aws.amazon.com/releasenotes/1900016175520505).
* Updates the AWS OpsWorks client to the latest API version.
* Amazon S3 Stream Wrapper now works correctly with pseudo folder keys created by the AWS Management Console.
* Amazon S3 Stream Wrapper now implements `mkdir()` for nested folders similar to the AWS Management Console.
* Addressed an issue with Amazon S3 presigned-URLs where X-Amz-* headers were not being added to the query string.
* Addressed an issue with the Amazon S3 directory sync where paths that contained dot-segments were not properly.
  resolved. Removing the dot segments consistently helps to ensure that files are uploaded to their intended.
  destinations and that file key comparisons are accurately performed when determining which files to upload.

## 2.5.3 - 2014-02-27

* Added support for HTTP and HTTPS string-match health checks and HTTPS health checks to the Amazon Route 53 client
* Added support for the UPSERT action for the Amazon Route 53 ChangeResourceRecordSets operation
* Added support for SerialNumber and TokenCode to the AssumeRole operation of the IAM Security Token Service (STS).
* Added support for RequestInterval and FailureThreshold to the Amazon Route53 client.
* Added support for smooth streaming to the Amazon CloudFront client.
* Added the us-west-2, eu-west-1, ap-southeast-2, and ap-northeast-1 regions to the AWS Data Pipeline client.
* Added iterators to the Amazon Kinesis client
* Updated iterator configurations for all services to match our new iterator config spec (care was taken to continue
  supporting manually-specified configurations in the old format to prevent BC)
* Updated the Amazon EC2 model to include the latest updates and documentation. Removed deprecated license-related
  operations (this is not considered a BC since we have confirmed that these operations are not used by customers)
* Updated the Amazon Route 53 client to use the 2013-04-01 API version
* Fixed several iterator configurations for various services to better support existing operations and parameters
* Fixed an issue with the Amazon S3 client where an exception was thrown when trying to add a default Content-MD5
  header to a request that uses a non-rewindable stream.
* Updated the Amazon S3 PostObject class to work with CNAME style buckets.

## 2.5.2 - 2014-01-29

* Added support for dead letter queues to Amazon SQS
* Added support for the new M3 medium and large instance types to the Amazon EC2 client
* Added support for using the `eu-west-1` and `us-west-2` regions to the Amazon SES client
* Adding content-type guessing to the Amazon S3 stream wrapper (see #210)
* Added an event to the Amazon S3 multipart upload helpers to allow granular customization of multipart uploads during
  a sync (see #209)
* Updated Signature V4 logic for Amazon S3 to throw an exception if you attempt to create a presigned URL that expires
  later than a week (see #215)
* Fixed the `downloadBucket` and `uploadDirectory` methods to support relative paths and better support
  Windows (see #207)
* Fixed issue #195 in the Amazon S3 multipart upload helpers to properly support additional parameters (see #211)
* [Docs] Expanded examples in the [API reference](http://docs.aws.amazon.com/aws-sdk-php/latest/index.html) by default
  so they don't get overlooked
* [Docs] Moved the API reference links in the [service-specific user guide
  pages](http://docs.aws.amazon.com/aws-sdk-php/guide/latest/index.html#service-specific-guides) to the bottom so
  the page's content takes priority

## 2.5.1 - 2014-01-09

* Added support for attaching existing Amazon EC2 instances to an Auto Scaling group to the Auto Scaling client
* Added support for creating launch configurations from existing Amazon EC2 instances to the Auto Scaling client
* Added support for describing Auto Scaling account limits to the Auto Scaling client
* Added better support for block device mappings to the Amazon AutoScaling client when creating launch configurations
* Added support for [ranged inventory retrieval](http://docs.aws.amazon.com/amazonglacier/latest/dev/api-initiate-job-post.html#api-initiate-job-post-vault-inventory-list-filtering)
  to the Amazon Glacier client
* [Docs] Updated and added a lot of content in the [User Guide](http://docs.aws.amazon.com/aws-sdk-php/guide/latest/index.html)
* Fixed a bug where the `KinesisClient::getShardIterator()` method was not working properly
* Fixed an issue with Amazon SimpleDB where the 'Value' attribute was marked as required on DeleteAttribute and BatchDeleteAttributes
* Fixed an issue with the Amazon S3 stream wrapper where empty place holder keys were being marked as files instead of directories
* Added the ability to specify a custom signature implementation using a string identifier (e.g., 'v4', 'v2', etc)

## 2.5.0 - 2013-12-20

* Added support for the new **China (Beijing) Region** to various services. This region is currently in limited preview.
  Please see <http://www.amazonaws.cn> for more information
* Added support for different audio compression schemes to the Elastic Transcoder client (includes AAC-LC, HE-AAC,
  and HE-AACv2)
* Added support for preset and pipeline pagination to the Elastic Transcoder client. You can now view more than the
  first 50 presets and pipelines with their corresponding list operations
* Added support for [geo restriction](http://docs.aws.amazon.com/AmazonCloudFront/latest/DeveloperGuide/WorkingWithDownloadDistributions.html#georestrictions)
  to the Amazon CloudFront client
* [SDK] Added Signature V4 support to the Amazon S3 and Amazon EC2 clients for the new China (Beijing) Region
* [BC] Updated the AWS CloudTrail client to use their latest API changes due to early user feedback. Some parameters in
  the `CreateTrail`, `UpdateTrail`, and `GetTrailStatus` have been deprecated and will be completely unavailable as
  early as February 15th, 2014. Please see [this announcement on the CloudTrail
  forum](https://forums.aws.amazon.com/ann.jspa?annID=2286). We are calling this out as a breaking change now to
  encourage you to update your code at this time.
* Updated the Amazon CloudFront client to use the 2013-11-11 API version
* [BC] Updated the Amazon EC2 client to use the latest API. This resulted in a small change to a parameter in the
  `RequestSpotInstances` operation. See [this commit](https://github.com/aws/aws-sdk-php/commit/36ae0f68d2a6dcc3bc28222f60ecb318449c4092#diff-bad2f6eac12565bb684f2015364c22bd)
  for the change
* [BC] Removed Signature V3 support (no longer needed) and refactored parts of the signature-related classes

## 2.4.12 - 2013-12-12

* Added support for **Amazon Kinesis**
* Added the CloudTrail `LogRecordIterator`, `LogFileIterator`, and `LogFileReader` classes for reading log files
  generated by the CloudTrail service
* Added support for resource-level permissions to the AWS OpsWorks client
* Added support for worker environment tiers to the AWS Elastic Beanstalk client
* Added support for the new I2 instance types to the Amazon EC2 client
* Added support for resource tagging to the Amazon Elastic MapReduce client
* Added support for specifying a key encoding type to the Amazon S3 client
* Added support for global secondary indexes to the Amazon DynamoDB client
* Updated the Amazon ElastiCache client to use Signature Version 4
* Fixed an issue in the waiter factory that caused an error when getting the factory for service clients without any
  existing waiters
* Fixed issue #187, where the DynamoDB Session Handler would fail to save the session if all the data is removed

## 2.4.11 - 2013-11-26

* Added support for copying DB snapshots from one AWS region to another to the Amazon RDS client
* Added support for pagination of the `DescribeInstances` and `DescribeTags` operations to the Amazon EC2 client
* Added support for the new C3 instance types and the g2.2xlarge instance type to the Amazon EC2 client
* Added support for enabling *Single Root I/O Virtualization* (SR-IOV) support for the new C3 instance types to the
  Amazon EC2 client
* Updated the Amazon EC2 client to use the 2013-10-15 API version
* Updated the Amazon RDS client to use the 2013-09-09 API version
* Updated the Amazon CloudWatch client to use Signature Version 4

## 2.4.10 - 2013-11-14

* Added support for **AWS CloudTrail**
* Added support for identity federation using SAML 2.0 to the AWS STS client
* Added support for configuring SAML-compliant identity providers to the AWS IAM client
* Added support for event notifications to the Amazon Redshift client
* Added support for HSM storage for encryption keys to the Amazon Redshift client
* Added support for encryption key rotation to the Amazon Redshift client
* Added support for database audit logging to the Amazon Redshift client

## 2.4.9 - 2013-11-08

* Added support for [cross-zone load balancing](http://aws.amazon.com/about-aws/whats-new/2013/11/06/elastic-load-balancing-adds-cross-zone-load-balancing/)
  to the Elastic Load Balancing client.
* Added support for a [new gateway configuration](http://aws.amazon.com/about-aws/whats-new/2013/11/05/aws-storage-gateway-announces-gateway-virtual-tape-library/),
  Gateway-Virtual Tape Library, to the AWS Storage Gateway client.
* Added support for stack policies to the the AWS CloudFormation client.
* Fixed issue #176 where attempting to upload a direct to Amazon S3 using the `UploadBuilder` failed when using a custom
  iterator that needs to be rewound.

## 2.4.8 - 2013-10-31

* Updated the AWS Direct Connect client
* Updated the Amazon Elastic MapReduce client to add support for new EMR APIs, termination of specific cluster
  instances, and unlimited EMR steps.

## 2.4.7 - 2013-10-17

* Added support for audio transcoding features to the Amazon Elastic Transcoder client
* Added support for modifying Reserved Instances in a region to the Amazon EC2 client
* Added support for new resource management features to the AWS OpsWorks client
* Added support for additional HTTP methods to the Amazon CloudFront client
* Added support for custom error page configuration to the Amazon CloudFront client
* Added support for the public IP address association of instances in Auto Scaling group via the Auto Scaling client
* Added support for tags and filters to various operations in the Amazon RDS client
* Added the ability to easily specify event listeners on waiters
* Added support for using the `ap-southeast-2` region to the Amazon Glacier client
* Added support for using the `ap-southeast-1` and `ap-southeast-2` regions to the Amazon Redshift client
* Updated the Amazon EC2 client to use the 2013-09-11 API version
* Updated the Amazon CloudFront client to use the 2013-09-27 API version
* Updated the AWS OpsWorks client to use the 2013-07-15 API version
* Updated the Amazon CloudSearch client to use Signature Version 4
* Fixed an issue with the Amazon S3 Client so that the top-level XML element of the `CompleteMultipartUpload` operation
  is correctly sent as `CompleteMultipartUpload`
* Fixed an issue with the Amazon S3 Client so that you can now disable bucket logging using with the `PutBucketLogging`
  operation
* Fixed an issue with the Amazon CloudFront so that query string parameters in pre-signed URLs are correctly URL-encoded
* Fixed an issue with the Signature Version 4 implementation where headers with multiple values were sometimes sorted
  and signed incorrectly

## 2.4.6 - 2013-09-12

* Added support for modifying EC2 Reserved Instances to the Amazon EC2 client
* Added support for VPC features to the AWS OpsWorks client
* Updated the DynamoDB Session Handler to implement the SessionHandlerInterface of PHP 5.4 when available
* Updated the SNS Message Validator to throw an exception, instead of an error, when the raw post data is invalid
* Fixed an issue in the S3 signature which ensures that parameters are sorted correctly for signing
* Fixed an issue in the S3 client where the Sydney region was not allowed as a `LocationConstraint` for the
  `PutObject` operation

## 2.4.5 - 2013-09-04

* Added support for replication groups to the Amazon ElastiCache client
* Added support for using the `us-gov-west-1` region to the AWS CloudFormation client

## 2.4.4 - 2013-08-29

* Added support for assigning a public IP address to an instance at launch to the Amazon EC2 client
* Updated the Amazon EC2 client to use the 2013-07-15 API version
* Updated the Amazon SWF client to sign requests with Signature V4
* Updated the Instance Metadata client to allow for higher and more customizable connection timeouts
* Fixed an issue with the SDK where XML map structures were not being serialized correctly in some cases
* Fixed issue #136 where a few of the new Amazon SNS mobile push operations were not working properly
* Fixed an issue where the AWS STS `AssumeRoleWithWebIdentity` operation was requiring credentials and a signature
  unnecessarily
* Fixed and issue with the `S3Client::uploadDirectory` method so that true key prefixes can be used
* [Docs] Updated the API docs to include sample code for each operation that indicates the parameter structure
* [Docs] Updated the API docs to include more information in the descriptions of operations and parameters
* [Docs] Added a page about Iterators to the user guide

## 2.4.3 - 2013-08-12

* Added support for mobile push notifications to the Amazon SNS client
* Added support for progress reporting on snapshot restore operations to the the Amazon Redshift client
* Updated the Amazon Elastic MapReduce client to use JSON serialization
* Updated the Amazon Elastic MapReduce client to sign requests with Signature V4
* Updated the SDK to throw `Aws\Common\Exception\TransferException` exceptions when a network error occurs instead of a
  `Guzzle\Http\Exception\CurlException`. The TransferException class, however, extends from
  `Guzzle\Http\Exception\CurlException`. You can continue to catch the Guzzle `CurlException` or catch
  `Aws\Common\Exception\AwsExceptionInterface` to catch any exception that can be thrown by an AWS client
* Fixed an issue with the Amazon S3 stream wrapper where trailing slashes were being added when listing directories

## 2.4.2 - 2013-07-25

* Added support for cross-account snapshot access control to the Amazon Redshift client
* Added support for decoding authorization messages to the AWS STS client
* Added support for checking for required permissions via the `DryRun` parameter to the Amazon EC2 client
* Added support for custom Amazon Machine Images (AMIs) and Chef 11 to the AWS OpsWorks client
* Added an SDK compatibility test to allow users to quickly determine if their system meets the requirements of the SDK
* Updated the Amazon EC2 client to use the 2013-06-15 API version
* Fixed an unmarshalling error with the Amazon EC2 `CreateKeyPair` operation
* Fixed an unmarshalling error with the Amazon S3 `ListMultipartUploads` operation
* Fixed an issue with the Amazon S3 stream wrapper "x" fopen mode
* Fixed an issue with `Aws\S3\S3Client::downloadBucket` by removing leading slashes from the passed `$keyPrefix` argument

## 2.4.1 - 2013-06-08

* Added support for setting watermarks and max framerates to the Amazon Elastic Transcoder client
* Added the `Aws\DynamoDb\Iterator\ItemIterator` class to make it easier to get items from the results of DynamoDB
  operations in a simpler form
* Added support for the `cr1.8xlarge` EC2 instance type. Use `Aws\Ec2\Enum\InstanceType::CR1_8XLARGE`
* Added support for the suppression list SES mailbox simulator. Use `Aws\Ses\Enum\MailboxSimulator::SUPPRESSION_LIST`
* [SDK] Fixed an issue with data formats throughout the SDK due to a regression. Dates are now sent over the wire with
  the correct format. This issue affected the Amazon EC2, Amazon ElastiCache, AWS Elastic Beanstalk, Amazon EMR, and
  Amazon RDS clients
* Fixed an issue with the parameter serialization of the `ImportInstance` operation in the Amazon EC2 client
* Fixed an issue with the Amazon S3 client where the `RoutingRules.Redirect.HostName` parameter of the
  `PutBucketWebsite` operation was erroneously marked as required
* Fixed an issue with the Amazon S3 client where the `DeleteObject` operation was missing parameters
* Fixed an issue with the Amazon S3 client where the `Status` parameter of the `PutBucketVersioning` operation did not
  properly support the "Suspended" value
* Fixed an issue with the Amazon Glacier `UploadPartGenerator` class so that an exception is thrown if the provided body
  to upload is less than 1 byte
* Added MD5 validation to Amazon SQS ReceiveMessage operations

## 2.4.0 - 2013-06-18

* [BC] Updated the Amazon CloudFront client to use the new 2013-05-12 API version which includes changes in how you
  configure distributions. If you are not ready to upgrade to the new API, you can configure the SDK to use the previous
  version of the API by setting the `version` option to `2012-05-05` when you instantiate the client (See
  [`UPGRADING.md`](https://github.com/aws/aws-sdk-php/blob/master/UPGRADING.md))
* Added abstractions for uploading a local directory to an Amazon S3 bucket (`$s3->uploadDirectory()`)
* Added abstractions for downloading an Amazon S3 bucket to local directory (`$s3->downloadBucket()`)
* Added an easy to way to delete objects from an Amazon S3 bucket that match a regular expression or key prefix
* Added an easy to way to upload an object to Amazon S3 that automatically uses a multipart upload if the size of the
  object exceeds a customizable threshold (`$s3->upload()`)
* [SDK] Added facade classes for simple, static access to clients (e.g., `S3::putObject([...])`)
* Added the `Aws\S3\S3Client::getObjectUrl` convenience method for getting the URL of an Amazon S3 object. This works
  for both public and pre-signed URLs
* Added support for using the `ap-northeast-1` region to the Amazon Redshift client
* Added support for configuring custom SSL certificates to the Amazon CloudFront client via the `ViewerCertificate`
  parameter
* Added support for read replica status to the Amazon RDS client
* Added "magic" access to iterators to make using iterators more convenient (e.g., `$s3->getListBucketsIterator()`)
* Added the `waitUntilDBInstanceAvailable` and `waitUntilDBInstanceDeleted` waiters to the Amazon RDS client
* Added the `createCredentials` method to the AWS STS client to make it easier to create a credentials object from the
  results of an STS operation
* Updated the Amazon RDS client to use the 2013-05-15 API version
* Updated request retrying logic to automatically refresh expired credentials and retry with new ones
* Updated the Amazon CloudFront client to sign requests with Signature V4
* Updated the Amazon SNS client to sign requests with Signature V4, which enables larger payloads
* Updated the S3 Stream Wrapper so that you can use stream resources in any S3 operation without having to manually
  specify the `ContentLength` option
* Fixed issue #94 so that the `Aws\S3\BucketStyleListener` is invoked on `command.after_prepare` and presigned URLs
  are generated correctly from S3 commands
* Fixed an issue so that creating presigned URLs using the Amazon S3 client now works with temporary credentials
* Fixed an issue so that the `CORSRules.AllowedHeaders` parameter is now available when configuring CORS for Amazon S3
* Set the Guzzle dependency to ~3.7.0

## 2.3.4 - 2013-05-30

* Set the Guzzle dependency to ~3.6.0

## 2.3.3 - 2013-05-28

* Added support for web identity federation in the AWS Security Token Service (STS) API
* Fixed an issue with creating pre-signed Amazon CloudFront RTMP URLs
* Fixed issue #85 to correct the parameter serialization of NetworkInterfaces within the Amazon EC2 RequestSpotInstances
  operation

## 2.3.2 - 2013-05-15

* Added support for doing parallel scans to the Amazon DynamoDB client
* [OpsWorks] Added support for using Elastic Load Balancer to the AWS OpsWorks client
* Added support for using EBS-backed instances to the AWS OpsWorks client along with some other minor updates
* Added support for finer-grained error messages to the AWS Data Pipeline client and updated the service description
* Added the ability to set the `key_pair_id` and `private_key` options at the time of signing a CloudFront URL instead
  of when instantiating the client
* Added a new [Zip Download](http://pear.amazonwebservices.com/get/aws.zip) for installing the SDK
* Fixed the API version for the AWS Support client to be `2013-04-15`
* Fixed issue #78 by implementing `Aws\S3\StreamWrapper::stream_cast()` for the S3 stream wrapper
* Fixed issue #79 by updating the S3 `ClearBucket` object to work with the `ListObjects` operation
* Fixed issue #80 where the `ETag` was incorrectly labeled as a header value instead of being in the XML body for
  the S3 `CompleteMultipartUpload` operation response
* Fixed an issue where the `setCredentials()` method did not properly update the `SignatureListener`
* Updated the required version of Guzzle to `">=3.4.3,<4"` to support Guzzle 3.5 which provides the SDK with improved
  memory management

## 2.3.1 - 2013-04-30

* Added support for **AWS Support**
* Added support for using the `eu-west-1` region to the Amazon Redshift client
* Fixed an issue with the Amazon RDS client where the `DownloadDBLogFilePortion` operation was not being serialized
  properly
* Fixed an issue with the Amazon S3 client where the `PutObjectCopy` alias was interfering with the `CopyObject`
  operation
* Added the ability to manually set a Content-Length header when using the `PutObject` and `UploadPart` operations of
  the Amazon S3 client
* Fixed an issue where the Amazon S3 class was not throwing an exception for a non-followable 301 redirect response
* Fixed an issue where `fflush()` was called during the shutdown process of the stream handler for read-only streams

## 2.3.0 - 2013-04-18

* Added support for Local Secondary Indexes to the Amazon DynamoDB client
* [BC] Updated the Amazon DynamoDB client to use the new 2012-08-10 API version which includes changes in how you
  specify keys. If you are not ready to upgrade to the new API, you can configure the SDK to use the previous version of
  the API by setting the `version` option to `2011-12-05` when you instantiate the client (See
  [`UPGRADING.md`](https://github.com/aws/aws-sdk-php/blob/master/UPGRADING.md)).
* Added an Amazon S3 stream wrapper that allows PHP native file functions to be used to interact with S3 buckets and
  objects
* Added support for automatically retrying *throttled* requests with exponential backoff to all service clients
* Added a new config option (`version`) to client objects to specify the API version to use if multiple are supported
* Added a new config option (`gc_operation_delay`) to the DynamoDB Session Handler to specify a delay between requests
  to the service during garbage collection in order to help regulate the consumption of throughput
* Added support for using the `us-west-2` region to the Amazon Redshift client
* [Docs] Added a way to use marked integration test code as example code in the user guide and API docs
* Updated the Amazon RDS client to sign requests with Signature V4
* Updated the Amazon S3 client to automatically add the `Content-Type` to `PutObject` and other upload operations
* Fixed an issue where service clients with a global endpoint could have their region for signing set incorrectly if a
  region other than `us-east-1` was specified.
* Fixed an issue where reused command objects appended duplicate content to the user agent string
* [SDK] Fixed an issue in a few operations (including `SQS::receiveMessage`) where the `curl.options` could not be
  modified
* [Docs] Added key information to the DynamoDB service description to provide more accurate API docs for some operations
* [Docs] Added a page about Waiters to the user guide
* [Docs] Added a page about the DynamoDB Session Handler to the user guide
* [Docs] Added a page about response Models to the user guide
* Bumped the required version of Guzzle to ~3.4.1

## 2.2.1 - 2013-03-18

* Added support for viewing and downloading DB log files to the Amazon RDS client
* Added the ability to validate incoming Amazon SNS messages. See the `Aws\Sns\MessageValidator` namespace
* Added the ability to easily change the credentials that a client is configured to use via `$client->setCredentials()`
* Added the `client.region_changed` and `client.credentials_changed` events on the client that are triggered when the
  `setRegion()` and `setCredentials()` methods are called, respectively
* Added support for using the `ap-southeast-2` region with the Amazon ElastiCache client
* Added support for using the `us-gov-west-1` region with the Amazon SWF client
* Updated the Amazon RDS client to use the 2013-02-12 API version
* Fixed an issue in the Amazon EC2 service description that was affecting the use of the new `ModifyVpcAttribute` and
  `DescribeVpcAttribute` operations
* Added `ObjectURL` to the output of an Amazon S3 PutObject operation so that you can more easily retrieve the URL of an
  object after uploading
* Added a `createPresignedUrl()` method to any command object created by the Amazon S3 client to more easily create
  presigned URLs

## 2.2.0 - 2013-03-11

* Added support for **Amazon Elastic MapReduce (Amazon EMR)**
* Added support for **AWS Direct Connect**
* Added support for **Amazon ElastiCache**
* Added support for **AWS Storage Gateway**
* Added support for **AWS Import/Export**
* Added support for **AWS CloudFormation**
* Added support for **Amazon CloudSearch**
* Added support for [provisioned IOPS](http://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/Overview.ProvisionedIOPS.html)
  to the the Amazon RDS client
* Added support for promoting [read replicas](http://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_ReadRepl.html)
  to the Amazon RDS client
* Added support for [event notification subscriptions](http://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/USER_Events.html)
  to the Amazon RDS client
* Added support for enabling\disabling DNS Hostnames and DNS Resolution in Amazon VPC to the Amazon EC2 client
* Added support for enumerating account attributes to the Amazon EC2 client
* Added support for copying AMIs across regions to the Amazon EC2 client
* Added the ability to get a Waiter object from a client using the `getWaiter()` method
* [SDK] Added the ability to load credentials from environmental variables `AWS_ACCESS_KEY_ID` and `AWS_SECRET_KEY`.
  This is compatible with AWS Elastic Beanstalk environment configurations
* Added support for using the us-west-1, us-west-2, eu-west-1, and ap-southeast-1 regions with Amazon CloudSearch
* Updated the Amazon RDS client to use the 2013-01-10 API version
* Updated the Amazon EC2 client to use the 2013-02-01 API version
* Added support for using SecurityToken with signature version 2 services
* Added the client User-Agent header to exception messages for easier debugging
* Added an easier way to disable operation parameter validation by setting `validation` to false when creating clients
* Added the ability to disable the exponential backoff plugin
* Added the ability to easily fetch the region name that a client is configured to use via `$client->getRegion()`
* Added end-user guides available at http://docs.aws.amazon.com/aws-sdk-php/guide/latest/
* Fixed issue #48 where signing Amazon S3 requests with null or empty metadata resulted in a signature error
* Fixed issue #29 where Amazon S3 was intermittently closing a connection
* Updated the Amazon S3 client to parse the AcceptRanges header for HeadObject and GetObject output
* Updated the Amazon Glacier client to allow the `saveAs` parameter to be specified as an alias for `command.response_body`
* Various performance improvements throughout the SDK
* Removed endpoint providers and now placing service region information directly in service descriptions
* Removed client resolvers when creating clients in a client's factory method (this should not have any impact to end users)

## 2.1.2 - 2013-02-18

* Added support for **AWS OpsWorks**

## 2.1.1 - 2013-02-15

* Added support for **Amazon Redshift**
* Added support for **Amazon Simple Queue Service (Amazon SQS)**
* Added support for **Amazon Simple Notification Service (Amazon SNS)**
* Added support for **Amazon Simple Email Service (Amazon SES)**
* Added support for **Auto Scaling**
* Added support for **Amazon CloudWatch**
* Added support for **Amazon Simple Workflow Service (Amazon SWF)**
* Added support for **Amazon Relational Database Service (Amazon RDS)**
* Added support for health checks and failover in Amazon Route 53
* Updated the Amazon Route 53 client to use the 2012-12-12 API version
* Updated `AbstractWaiter` to dispatch `waiter.before_attempt` and `waiter.before_wait` events
* Updated `CallableWaiter` to allow for an array of context data to be passed to the callable
* Fixed issue #29 so that the stat cache is cleared before performing multipart uploads
* Fixed issue #38 so that Amazon CloudFront URLs are signed properly
* Fixed an issue with Amazon S3 website redirects
* Fixed a URL encoding inconsistency with Amazon S3 and pre-signed URLs
* Fixed issue #42 to eliminate cURL error 65 for JSON services
* Set Guzzle dependency to [~3.2.0](https://github.com/guzzle/guzzle/blob/master/CHANGELOG.md#320-2013-02-14)
* Minimum version of PHP is now 5.3.3

## 2.1.0 - 2013-01-28

* Waiters now require an associative array as input for the underlying operation performed by a waiter. See
  `UPGRADING.md` for details.
* Added support for **Amazon Elastic Compute Cloud (Amazon EC2)**
* Added support for **Amazon Elastic Transcoder**
* Added support for **Amazon SimpleDB**
* Added support for **Elastic Load Balancing**
* Added support for **AWS Elastic Beanstalk**
* Added support for **AWS Identity and Access Management (IAM)**
* Added support for Amazon S3 website redirection rules
* Added support for the `RetrieveByteRange` parameter of the `InitiateJob` operation in Amazon Glacier
* Added support for Signature Version 2
* Clients now gain more information from service descriptions rather than client factory methods
* Service descriptions are now versioned for clients
* Fixed an issue where Amazon S3 did not use "restore" as a signable resource
* Fixed an issue with Amazon S3 where `x-amz-meta-*` headers were not properly added with the CopyObject operation
* Fixed an issue where the Amazon Glacier client was not using the correct User-Agent header
* Fixed issue #13 in which constants defined by referencing other constants caused errors with early versions of PHP 5.3

## 2.0.3 - 2012-12-20

* Added support for **AWS Data Pipeline**
* Added support for **Amazon Route 53**
* Fixed an issue with the Amazon S3 client where object keys with slashes were causing errors
* Added a `SaveAs` parameter to the Amazon S3 `GetObject` operation to allow saving the object directly to a file
* Refactored iterators to remove code duplication and ease creation of future iterators

## 2.0.2 - 2012-12-10

* Fixed an issue with the Amazon S3 client where non-DNS compatible buckets that was previously causing a signature
  mismatch error
* Fixed an issue with the service description for the Amazon S3 `UploadPart` operation so that it works correctly
* Fixed an issue with the Amazon S3 service description dealing with `response-*` query parameters of `GetObject`
* Fixed an issue with the Amazon S3 client where object keys prefixed by the bucket name were being treated incorrectly
* Fixed an issue with `Aws\S3\Model\MultipartUpload\ParallelTransfer` class
* Added support for the `AssumeRole` operation for AWS STS
* Added a the `UploadBodyListener` which allows upload operations in Amazon S3 and Amazon Glacier to accept file handles
  in the `Body` parameter and file paths in the `SourceFile` parameter
* Added Content-Type guessing for uploads
* Added new region endpoints, including sa-east-1 and us-gov-west-1 for Amazon DynamoDB
* Added methods to `Aws\S3\Model\MultipartUpload\UploadBuilder` class to make setting ACL and Content-Type easier

## 2.0.1 - 2012-11-13

* Fixed a signature issue encountered when a request to Amazon S3 is redirected
* Added support for archiving Amazon S3 objects to Amazon Glacier
* Added CRC32 validation of Amazon DynamoDB responses
* Added ConsistentRead support to the `BatchGetItem` operation of Amazon DynamoDB
* Added new region endpoints, including Sydney

## 2.0.0 - 2012-11-02

* Initial release of the AWS SDK for PHP Version 2. See <http://aws.amazon.com/sdkforphp2/> for more information.
* Added support for **Amazon Simple Storage Service (Amazon S3)**
* Added support for **Amazon DynamoDB**
* Added support for **Amazon Glacier**
* Added support for **Amazon CloudFront**
* Added support for **AWS Security Token Service (AWS STS)**
