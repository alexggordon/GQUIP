CREATE TABLE CTSEquipment.dbo.users
(id int NOT NULL,
department varchar(45) NOT NULL,
first_name varchar(45) NOT NULL,
middle_name varchar(45),
last_name varchar(45) NOT NULL,
email varchar(45),
role VARCHAR(45) NOT NULL,
building VARCHAR(45) NOT NULL,
room INT NOT NULL,
phone VARCHAR(45),
barcode VARCHAR(45),
active VARCHAR(45) NOT NULL,
on_campus BIT NOT NULL,
state VARCHAR(45),
country VARCHAR(45) NOT NULL,
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
[control] [varchar](45) NOT NULL,
[control_ts] [date] NOT NULL,
[department_id] [varchar](45),
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
[name] [varchar](45) NOT NULL,
[software_type] [varchar](45) NOT NULL,
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
[software_ts] [date](45) NOT NULL,
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

CREATE TABLE [dbo].[FacStaff] (
  ID INT NOT NULL,
  last_updated_by VARCHAR(45) NOT NULL,
  OnCampusDepartment VARCHAR(45) NOT NULL,
  Dept VARCHAR(45) NOT NULL,
  Type VARCHAR(45) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  FirstName VARCHAR(45) NOT NULL,
  LastName VARCHAR(45) NOT NULL,
  Email VARCHAR(45) NOT NULL,
  PRIMARY KEY (id))


CREATE TABLE IF NOT EXISTS mydb.computers (
  control VARCHAR(45) NOT NULL,
  legacy_department VARCHAR(45) NOT NULL,
  last_updated_by VARCHAR(45) NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  serial VARCHAR(45) NOT NULL,
  model VARCHAR(45) NOT NULL,
  manufacturer VARCHAR(45) NOT NULL,
  purchase_date DATETIME NOT NULL,
  purchase_price FLOAT NOT NULL,
  purchase_acct VARCHAR(45) NOT NULL,
  usage_status VARCHAR(45) NOT NULL,
  memory INT NULL,
  hard_drive INT NULL,
  warranty_length VARCHAR(45) NULL,
  warranty_start VARCHAR(45) NULL,
  warranty_type VARCHAR(45) NULL,
  replacement_year YEAR NULL,
  computer_type VARCHAR(45) NULL,
  legacy_userid VARCHAR(45) NULL,
  cameron_id VARCHAR(45) NULL,
  part_number VARCHAR(45) NULL,
  ip_address VARCHAR(45) NULL,
  PRIMARY KEY (control))
ENGINE = InnoDB

CREATE TABLE IF NOT EXISTS mydb.hardware_assignments (
  id INT NOT NULL,
  user_id INT NULL,
  last_updated_by VARCHAR(45) NOT NULL,
  control VARCHAR(45) NOT NULL,
  department_id VARCHAR(45) NOT NULL,
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
  PRIMARY KEY (id),
  INDEX fk_Hardware_assignment_User1_idx (user_id ASC),
  INDEX fk_Hardware_assignment_Computer1_idx (control ASC),
  CONSTRAINT fk_Hardware_assignment_User1
    FOREIGN KEY (user_id)
    REFERENCES mydb.FacStaff (id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_Hardware_assignment_Computer1
    FOREIGN KEY (control)
    REFERENCES mydb.computers (control)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB

CREATE TABLE IF NOT EXISTS mydb.comments (
  id INT NOT NULL,
  user_name VARCHAR(45) NOT NULL,
  computer_id INT NOT NULL,
  last_updated_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL,
  body TEXT NOT NULL,
  PRIMARY KEY (id),
  INDEX fk_Comment_Computer1_idx (computer_id ASC),
  CONSTRAINT fk_Comment_Computer1
    FOREIGN KEY (computer_id)
    REFERENCES mydb.computers (control)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB


