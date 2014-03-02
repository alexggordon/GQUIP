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
PRIMARY KEY (id, last_updated_at))


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

#<!!!> NEW STUFF


#FacStaff table creation
CREATE TABLE [dbo].[FacStaff] (
  ID INT NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  OnCampusDepartment VARCHAR(255) NOT NULL,
  Dept VARCHAR(255) NOT NULL,
  Type VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  FirstName VARCHAR(255) NOT NULL,
  LastName VARCHAR(255) NOT NULL,
  Email VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
  )

#computers table creation
CREATE TABLE [dbo].computers (
  control VARCHAR(255) NOT NULL,
  legacy_department VARCHAR(255) NOT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  serial VARCHAR(255) NOT NULL,
  model VARCHAR(255) NOT NULL,
  manufacturer VARCHAR(255) NOT NULL,
  purchase_date DATETIME NOT NULL,
  purchase_price FLOAT NOT NULL,
  purchase_acct VARCHAR(255) NOT NULL,
  usage_status VARCHAR(255) NOT NULL,
  memory INT NULL,
  hard_drive INT NULL,
  warranty_length VARCHAR(255) NULL,
  warranty_start VARCHAR(255) NULL,
  warranty_type VARCHAR(255) NULL,
  replacement_year YEAR NULL,
  computer_type VARCHAR(255) NULL,
  cameron_id VARCHAR(255) NULL,
  part_number VARCHAR(255) NULL,
  ip_address VARCHAR(255) NULL,
  PRIMARY KEY (control)
  )

#hardware_assignments table creation
CREATE TABLE [dbo].hardware_assignments (
  id INT NOT NULL,
  user_id INT NULL,
  last_updated_by VARCHAR(255) NOT NULL,
  control VARCHAR(255) NOT NULL,
  department_id VARCHAR(255) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  fullorpart TINYINT(1) NOT NULL,
  primary_computer TINYINT(1) NOT NULL,
  replace_with_recycled TINYINT(1) NOT NULL,
  nextneed_macpc TINYINT(1) NOT NULL,
  nextneed_laptopdesktop TINYINT(1) NOT NULL,
  start_assignment DATETIME NOT NULL,
  end_assignment DATETIME NULL,
  nextneed_note TEXT NULL,
  PRIMARY KEY (id)
  )

#creation of foreign key constraint for computer_id - a row in computers must have an id corresponding to this value
ALTER TABLE dbo.hardware_assignments WITH CHECK ADD CONSTRAINT [fk_hardware_computers] FOREIGN KEY([computer_id])
REFERENCES [dbo].[computers] ([control])

#creation of foreign key constraint for user_id - a row in FacStaff must have an id corresponding to this value
ALTER TABLE dbo.hardware_assignments WITH CHECK ADD CONSTRAINT [fk_hardware_FacStaff] FOREIGN KEY([user_id])
REFERENCES [dbo].[FacStaff] ([ID])

#application of the first of the above foreign key constraints
ALTER TABLE [dbo].[hardware_assignments] CHECK CONSTRAINT [fk_hardware_computers]

#application of the second of the above foreign key constraints
ALTER TABLE [dbo].[hardware_assignments] CHECK CONSTRAINT [fk_hardware_FacStaff]

CREATE TABLE [dbo].comments (
  id INT NOT NULL,
  user_name VARCHAR(255) NOT NULL,
  computer_id INT NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (id)
  )

#creation of foreign key constraint for computer_id - a row in computers must have an id corresponding to this value
ALTER TABLE dbo.comments WITH CHECK ADD CONSTRAINT [fk_comments_computers] FOREIGN KEY([computer_id])
REFERENCES [dbo].[computers] ([control])

#application of the above foreign key constraint
ALTER TABLE [dbo].[comments] CHECK CONSTRAINT [fk_comments_computers]

CREATE TABLE [dbo].changes (
  computer_id INT NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (computer_id, created_at)
  )

#creation of foreign key constraint for computer_id - a row in computers must have an id corresponding to this value
ALTER TABLE dbo.changes WITH CHECK ADD CONSTRAINT [fk_changes_computers] FOREIGN KEY([computer_id])
REFERENCES [dbo].[computers] ([control])

#application of the above foreign key constraint
ALTER TABLE [dbo].[changes] CHECK CONSTRAINT [fk_changes_computers]

#<!!!> other 'island' of tables

#software table creation

CREATE TABLE [dbo].[software]
(
id int NOT NULL,
last_updated_by VARCHAR(255) NOT NULL,
name VARCHAR(255) NOT NULL,
software_type VARCHAR(255) NOT NULL,
PRIMARY KEY (id, last_updated_by)
)

#students table creation

CREATE TABLE [dbo].[students]
(
id int NOT NULL,
first_name VARCHAR(255) NOT NULL,
middle_name VARCHAR(255) NOT NULL,
last_name int NOT NULL,
email VARCHAR(255) NOT NULL,
PRIMARY KEY (id)
)

#licenses table creation

CREATE TABLE [dbo].[licenses]
(
id int NOT NULL,
last_updated_by VARCHAR(255) NOT NULL,
user_id int NOT NULL,
software_id int NOT NULL,
PRIMARY KEY (id)
)

#creation of foreign key constraint for user_id - a row in students must have an id corresponding to this value
ALTER TABLE dbo.licenses WITH CHECK ADD CONSTRAINT [fk_licenses_students] FOREIGN KEY([user_id])
REFERENCES [dbo].[students] ([id])
GO


#creation of foreign key constraint for software - a row in software must have an id corresponding to this value
ALTER TABLE dbo.licenses WITH CHECK ADD CONSTRAINT [fk_licenses_software] FOREIGN KEY([software_id])
REFERENCES [dbo].[software] ([id])
GO

#application of the first of the above foreign key constraints
ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_students]
GO

#application of the second of the above foreign key constraints
ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_software]
GO

#<!!!> TABLE altercation statements for ID columns in FacStaff and students

alter table FacStaff
alter column ID nvarchar(255)

alter table students
alter column id varchar(255)

