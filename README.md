# AWS Project - Medizone
Medizon is a scalable cloud-based web-scale microservice application that allows employees to securely store and quickly retrieve sensitive documents, images, and metadata of patients.

## Infrastructure 
<Image>

## Networking & Security

### VPC
A Virtual Private Cloud (VPC) is designed to function as a segregated and private network with enhanced security and privacy measures. Medizone has a single VPC that spans across 2 availability zones (AZs) and consists of 4 subnets.

The 2 public subnets host EC2 servers and have routes to an internet gateway, allowing for inbound and outbound internet access. They are associated with Network Access Control Lists (NACLs) that are appropriately configured to control traffic and security, permitting HTTP, HTTPS, and SSH access as needed.

The 2 private subnets contain Amazon Aurora databases and have a security group configured to allow access only from specific trusted sources. SSH, MySQL/Aurora, HTTP, and HTTPS access are granted to ensure secure communication within the VPC.

<Image>

### EC2, Application Load Balancer (ALB) & Auto Scaling 
Medizone utilizes customized EC2 virtual servers with Linux as the Operating System. The instance type chosen is t2.medium, which provides 2 virtual CPUs and 4 GiB of memory.

A primary EC2 instance is created in one of the AZs and is configured with an Apache web server, phpMyAdmin, MariaDB, httpd, AWS SDK, and connected to an S3 bucket to download Medizone's application code. This primary instance serves as a template, and an Amazon Machine Image (AMI) is used to replicate it and launch additional instances in other AZs.

The ALB (Application Load Balancer) is set up to distribute incoming traffic across the EC2 instances running in different AZs, ensuring high availability and fault tolerance.

Auto Scaling is configured to maintain a minimum of 2 and a maximum of 4 EC2 instances. This elasticity allows the infrastructure to scale according to demand by automatically replacing any failed or unhealthy instances.

## Database 

### Amazon Aurora
Medizone's database engine is Amazon Aurora using the MySQL-compatible version. A primary Aurora instance is created in one AZ to ensure data persistence and durability.

To improve read performance and redundancy, a read replica is created across the second AZ. This replica can serve read-only queries, offloading some of the read workload from the primary instance.

The database contains metadata of patients' personal information, notes, document IDs, and image file names. Doctors upload this information and documents through the server's (index.html page) interface.

Access to the database is restricted to specific EC2 instances and protected with strong passwords to ensure data security.

### Amazon S3
Amazon S3 (Simple Storage Service) is used as the primary storage solution for documents and images uploaded by doctors.

To optimize storage costs, a lifecycle policy is enabled on the S3 bucket. It automatically transitions files from the Standard storage class to the Glacier Deep Archive storage class after 182 days of their creation time. This ensures that older data is stored in a more cost-effective manner while still being accessible when needed.

Bucket versioning is enabled to protect against accidental file deletions or modifications, ensuring data reliability and allowing easy recovery if needed.

## Lambda & SNS
A Lambda function is employed and connected to Amazon SNS (Simple Notification Service) to send email notifications to user subscribers when images are uploaded to the S3 bucket. This provides real-time updates to relevant parties and enhances the user experience.
