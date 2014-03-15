CREATE TABLE CTSEquipment.dbo.users
(id int NOT NULL,
department VARCHAR(255) NOT NULL,
first_name VARCHAR(255) NOT NULL,
middle_name VARCHAR(255),
last_name VARCHAR(255) NOT NULL,
email VARCHAR(255),
role VARCHAR(255) NOT NULL,
building VARCHAR(255) NOT NULL,
room INT NOT NULL,
phone VARCHAR(255),
barcode VARCHAR(255),
active VARCHAR(255) NOT NULL,
on_campus BIT NOT NULL,
state VARCHAR(255),
country VARCHAR(255) NOT NULL,
zip INT NULL,
PRIMARY KEY (id))



CREATE TABLE [dbo].[computers](
	[control] [varchar](255) NOT NULL,
	[department] [varchar](255) NOT NULL,
	[last_updated_by] [varchar](255) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[last_updated_at] [datetime] NOT NULL,
	[serial_num] [varchar](255) NULL,
	[model] [varchar](255) NOT NULL,
	[manufacturer] [varchar](255) NOT NULL,
	[purchase_date] [date] NOT NULL,
	[purchase_price] [varchar](255) NOT NULL,
	[purchase_acct] [varchar](255) NOT NULL,
	[usage_status] [varchar](255) NULL,
	[memory] [varchar](255) NULL,
	[hard_drive] [varchar](255) NULL,
	[warranty_start] [date](255) NOT NULL,
	[warranty_length] [varchar](255) NOT NULL,
	[warranty_type] [varchar](255) NULL,
	[replacement_year] [varchar](255) NOT NULL,
	[computer_type] [varchar](255) NULL,
	[legacy_user_id] [varchar](255) NULL,
	[cameron_id] [varchar](255) NULL,
	[part_number] [varchar](255) NULL,
	[ip_address] [varchar](255) NULL,
	PRIMARY KEY (control))
PRIMARY KEY CLUSTERED 
(
	[control] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO







CREATE TABLE [dbo].[hardware_assignments]
([id] [int] NOT NULL,
[user_id] [int],
[last_updated_by] [int] NOT NULL,
[control] [varchar](255) NOT NULL,
[control_ts] [date] NOT NULL,
[department_id] [varchar](255),
[fullorpart] [bit] NOT NULL,						# <!!!> need re-clarification of what this is, thanks
[primary_computer] [bit] NOT NULL,					# <!!!> need re-clarification of what this is, thanks
[replace_with_recycled] [bit] NOT NULL,
[nextneed_macpc] [bit] NOT NULL,
[nextneed_laptopdesktop] [bit] NOT NULL,
[start_assignment] [date] NOT NULL,
[assignment_type] [int] NOT NULL,					# <!!!> assignment_type takes place of dedicated, special, and lab: 
													# <!!!> 1 = dedicated, 2 = special, 3 = lab, 4 = kiosk, 5 = printer
													# <!!!> (is the printer option needed?)
[end_assignment] [date],
[nextneed_note] [text],
PRIMARY KEY (id))


ALTER TABLE dbo.hardware_assignments WITH CHECK ADD CONSTRAINT [fk_hardware_assignments_users_1] FOREIGN KEY([user_id])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.hardware_assignments WITH CHECK ADD CONSTRAINT [fk_hardware_assignments_users_2] FOREIGN KEY([last_updated_by])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.hardware_assignments WITH CHECK ADD CONSTRAINT [fk_hardware_assignments_computers_1] FOREIGN KEY([control], [control_ts])
REFERENCES [dbo].[computers] ([control], [last_updated_at])
GO

ALTER TABLE [dbo].[hardware_assignments] CHECK CONSTRAINT [fk_hardware_assignments_users_1]
GO

ALTER TABLE [dbo].[hardware_assignments] CHECK CONSTRAINT [fk_hardware_assignments_users_2]
GO

ALTER TABLE [dbo].[hardware_assignments] CHECK CONSTRAINT [fk_hardware_assignments_computers_1]
GO






CREATE TABLE [dbo].[comments]
([id] [int] NOT NULL,
[user_id] [int],
[control_number] [varchar](255) NOT NULL,
[control_timestamp] [datetime] NOT NULL,
[created_at] [datetime] NOT NULL,
[body] [text] NOT NULL,
PRIMARY KEY (id))


ALTER TABLE dbo.comments WITH CHECK ADD CONSTRAINT [fk_comments_control_number] FOREIGN KEY([control], [control_timestamp])
REFERENCES [dbo].[computers] ([control], [created_at])
GO


ALTER TABLE [dbo].[comments] CHECK CONSTRAINT [fk_comments_control_number]
GO






CREATE TABLE [dbo].[software]
([id] [int] NOT NULL,
[last_updated_by] [int] NOT NULL,
[name] [varchar](255) NOT NULL,
[software_type] [varchar](255) NOT NULL,
PRIMARY KEY (id, last_updated_by))


ALTER TABLE dbo.software WITH CHECK ADD CONSTRAINT [fk_software_users_1] FOREIGN KEY([last_updated_by])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE [dbo].[software] CHECK CONSTRAINT [fk_software_users_1]
GO






CREATE TABLE [dbo].[licenses]
([id] [int] NOT NULL,
[last_updated_by] [int] NOT NULL,
[user_id] [int] NOT NULL,
[software] [int] NOT NULL,
[software_ts] [date] NOT NULL,
PRIMARY KEY (id))


ALTER TABLE dbo.licenses WITH CHECK ADD CONSTRAINT [fk_licenses_users_1] FOREIGN KEY([last_updated_by])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.licenses WITH CHECK ADD CONSTRAINT [fk_licenses_users_2] FOREIGN KEY([user_id])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.licenses WITH CHECK ADD CONSTRAINT [fk_licenses_software_1] FOREIGN KEY([software], [software_ts])
REFERENCES [dbo].[software] ([id], [last_updated_at])
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_users_1]
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_users_2]
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_software_1]
GO

#<UPDATED TABLES>


#These first two just describe, they are actually created further down the line with a select into


CREATE TABLE [dbo].[FacandStaff] (
  ID NVARCHAR(255) NOT NULL,
  OnCampusDepartment VARCHAR(255) NULL,
  Dept VARCHAR(255) NULL,
  Type VARCHAR(255) NULL,
  FirstName VARCHAR(255) NULL,
  LastName VARCHAR(255) NULL,
  Email VARCHAR(255) NULL,
  PRIMARY KEY (ID)
  )





CREATE TABLE [dbo].[gordonstudents]
(
id VARCHAR(255) NOT NULL,
FirstName VARCHAR(255) NULL,
MiddleName VARCHAR(255) NULL,
LastName VARCHAR(255) NULL,
Class VARCHAR(255) NULL,
Email VARCHAR(255) NULL,
grad_student CHAR(1) NULL,
PRIMARY KEY (id)
)
GO


#Beyond here is where the real SQL begins


DROP TABLE hardware_assignments
GO

DROP TABLE changes
GO

DROP TABLE comments
GO

DROP TABLE computers
GO

DROP TABLE FacandStaff
GO

DROP TABLE licenses
GO

DROP TABLE software
GO

DROP TABLE gordonstudents
GO





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
  control VARCHAR(255) NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
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





CREATE TABLE [dbo].hardware_assignments (
  id INT IDENTITY NOT NULL,
  user_id NVARCHAR(15) NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  computer int NOT NULL,
  department_id VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  fullorpart TINYINT NOT NULL,
  primary_computer TINYINT NOT NULL,
  replace_with_recycled TINYINT NOT NULL,
  nextneed_macpc TINYINT NOT NULL,
  nextneed_laptopdesktop TINYINT NOT NULL,
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
  user_name VARCHAR(255) NOT NULL,
  computer_id INT NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (index_id)
  )

ALTER TABLE dbo.comments WITH CHECK ADD CONSTRAINT [fk_comments_computers] FOREIGN KEY([computer_id])
REFERENCES [dbo].[computers] ([computer_id])

ALTER TABLE [dbo].[comments] CHECK CONSTRAINT [fk_comments_computers]





CREATE TABLE [dbo].changes (
  computer_id INT NOT NULL,
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
name VARCHAR(255) NOT NULL,
software_type VARCHAR(255) NOT NULL,
PRIMARY KEY (index_id)
)
GO





CREATE TABLE [dbo].[licenses]
(
index_id INT IDENTITY NOT NULL,
last_updated_by VARCHAR(255) NOT NULL,
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



