# create_tables.sql
# made by Alex Gordon, Elliott Staude for use with the GQUIP application by Gordon College
# last updated April 7 2014
#
# this file is used for creating the SQL tables required for GQUIP's execution

#These first two entries just describe the tables that are intended to be used
# ...for faculty/staff and students 

#They are actually created further down the line with a select into statement

#Note that these are not directly created; instead, they are copied off of an
# ...extant pair of views created from the Gordon College Active Directory


# EDIT !!! for hardware_assignments: user_id now is able to accept nulls


#CREATE TABLE [dbo].[FacandStaff] (
#  ID NVARCHAR(255) NOT NULL,
#  OnCampusDepartment VARCHAR(255) NULL,
#  Dept VARCHAR(255) NULL,
#  Type VARCHAR(255) NULL,
#  FirstName VARCHAR(255) NULL,
#  LastName VARCHAR(255) NULL,
#  Email VARCHAR(255) NULL,
#  PRIMARY KEY (ID)
#  )

#CREATE TABLE [dbo].[gordonstudents] (
#  id VARCHAR(255) NOT NULL,
#  FirstName VARCHAR(255) NULL,
#  MiddleName VARCHAR(255) NULL,
#  LastName VARCHAR(255) NULL,
#  Class VARCHAR(255) NULL,
#  Email VARCHAR(255) NULL,
#  grad_student CHAR(1) NULL,
#  PRIMARY KEY (id)
#  )

#  GO



SELECT FacStaff.ID, FacStaff.OnCampusDepartment, FacStaff.Dept, 
    FacStaff.Type, FacStaff.FirstName, FacStaff.LastName,
    FacStaff.Email
INTO dbo.FacandStaff
FROM dbo.FacStaff
GO

UPDATE FacandStaff SET ID='NULL' WHERE ID IS NULL
GO

ALTER TABLE FacandStaff ALTER COLUMN ID NVARCHAR(15) NOT NULL
GO

ALTER TABLE FacandStaff 
ADD CONSTRAINT PK_FacandStaff_ID PRIMARY KEY CLUSTERED (ID)
GO





SELECT students.id, students.Class, students.Email, 
    students.grad_student, students.FirstName, students.MiddleName,
    students.LastName
INTO dbo.gordonstudents
FROM dbo.students
GO

ALTER TABLE gordonstudents
ADD CONSTRAINT PK_gordonstudents_id PRIMARY KEY CLUSTERED (id)
GO





CREATE TABLE [dbo].computers (
  computer_id INT IDENTITY NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  control VARCHAR(255) NOT NULL,
  serial_num VARCHAR(255) NULL,
  model VARCHAR(255) NOT NULL,
  manufacturer VARCHAR(255) NOT NULL,
  purchase_date DATETIME NOT NULL,
  purchase_price FLOAT NOT NULL,
  purchase_acct VARCHAR(255) NOT NULL,
  usage_status VARCHAR(255) NOT NULL,
  memory INT NOT NULL,
  hard_drive INT NOT NULL,
  warranty_length VARCHAR(255) NULL,
  warranty_start VARCHAR(255) NULL,
  warranty_type VARCHAR(255) NULL,
  replacement_year VARCHAR(255) NULL,
  computer_type VARCHAR(255) NULL,
  cameron_id VARCHAR(255) NULL,
  part_number VARCHAR(255) NULL,
  ip_address VARCHAR(255) NULL,
  inventoried TINYINT NOT NULL DEFAULT '0',
  PRIMARY KEY (computer_id)
  )

ALTER TABLE dbo.computers 
ADD CONSTRAINT control_unique UNIQUE (control)
GO






CREATE TABLE [dbo].hardware_assignments (
  id INT IDENTITY NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  user_id NVARCHAR(15) NULL,
  computer int NOT NULL,
  department_id VARCHAR(255) NOT NULL,
  full_time TINYINT NOT NULL DEFAULT '0',
  primary_computer TINYINT NOT NULL DEFAULT '0',
  start_assignment DATETIME NOT NULL,
  end_assignment DATETIME NULL,
  assignment_type VARCHAR(255) NOT NULL,
  nextneed_note TEXT NULL,
  PRIMARY KEY (id)
  )
GO

ALTER TABLE dbo.hardware_assignments WITH CHECK ADD CONSTRAINT [fk_hardware_computers] FOREIGN KEY([computer])
REFERENCES [dbo].[computers] ([computer_id])
GO

ALTER TABLE dbo.hardware_assignments WITH CHECK ADD CONSTRAINT [fk_hardware_FacStaff] FOREIGN KEY([user_id])
REFERENCES [dbo].[FacandStaff] ([ID])
GO

ALTER TABLE [dbo].[hardware_assignments] CHECK CONSTRAINT [fk_hardware_computers]
GO

ALTER TABLE [dbo].[hardware_assignments] CHECK CONSTRAINT [fk_hardware_FacStaff]
GO





CREATE TABLE [dbo].comments (
  index_id INT IDENTITY NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  computer_id INT NOT NULL,
  user_name VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (index_id)
  )

ALTER TABLE dbo.comments WITH CHECK ADD CONSTRAINT [fk_comments_computers] FOREIGN KEY([computer_id])
REFERENCES [dbo].[computers] ([computer_id])

ALTER TABLE [dbo].[comments] CHECK CONSTRAINT [fk_comments_computers]





CREATE TABLE [dbo].changes (
  computer_id INT NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  creator VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (computer_id, created_at)
)
GO

ALTER TABLE dbo.changes WITH CHECK ADD CONSTRAINT [fk_changes_computers] FOREIGN KEY([computer_id])
REFERENCES [dbo].[computers] ([computer_id])
GO

ALTER TABLE [dbo].[changes] CHECK CONSTRAINT [fk_changes_computers]
GO





CREATE TABLE [dbo].[software]
(
index_id INT IDENTITY NOT NULL,
last_updated_by VARCHAR(255) NOT NULL,
last_updated_at DATETIME NOT NULL,
created_at DATETIME NOT NULL,
name VARCHAR(255) NOT NULL,
software_type VARCHAR(255) NOT NULL,
PRIMARY KEY (index_id)
)
GO





CREATE TABLE [dbo].[licenses]
(
index_id INT IDENTITY NOT NULL,
last_updated_by VARCHAR(255) NOT NULL,
last_updated_at DATETIME NOT NULL,
created_at DATETIME NOT NULL,
date_sold DATETIME NOT NULL,
id VARCHAR(255) NOT NULL,
seller VARCHAR(255) NOT NULL,
software_id int NOT NULL,
PRIMARY KEY (index_id)
)

ALTER TABLE dbo.licenses WITH CHECK ADD CONSTRAINT [fk_licenses_students] FOREIGN KEY([id])
REFERENCES [dbo].[gordonstudents] ([id])
GO

ALTER TABLE dbo.licenses WITH CHECK ADD CONSTRAINT [fk_licenses_software] FOREIGN KEY([software_id])
REFERENCES [dbo].[software] ([index_id])
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_students]
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_software]
GO



