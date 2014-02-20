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



CREATE SYNONYM CTSEquipment.dbo.users for ???; # <!!!> what is the name of the schema in AD?



CREATE TABLE [dbo].[computers](
	[control] [varchar](45) NOT NULL,
	[department] [varchar](45) NOT NULL,
	[last_updated_by] [int] NOT NULL,
	[serial_num] [varchar](25) NULL,
	[model] [varchar](25) NOT NULL,
	[manufacturer] [varchar](25) NOT NULL,
	[purchase_date] [date] NOT NULL,
	[purchase_price] [varchar](25) NOT NULL,
	[purchase_acct] [varchar](25) NOT NULL,
	[usage_status] [varchar](25) NULL,
	[memory] [varchar](25) NULL,
	[hard_drive] [varchar](25) NULL,
	[warranty_start] [date](25) NOT NULL,
	[warranty_length] [varchar](25) NOT NULL,
	[warranty_type] [varchar](25) NULL,
	[replacement_year] [varchar](25) NOT NULL,
	[computer_type] [varchar](25) NULL,
	[legacy_user_id] [varchar](25) NULL,
	[cameron_id] [varchar](25) NULL,
	[part_number] [varchar](25) NULL,
	[ip_address] [varchar](25) NULL,
PRIMARY KEY CLUSTERED 
(
	[control] ASC
)WITH (PAD_INDEX  = OFF, STATISTICS_NORECOMPUTE  = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS  = ON, ALLOW_PAGE_LOCKS  = ON) ON [PRIMARY]
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO

ALTER TABLE [dbo].[computers]  WITH CHECK ADD  CONSTRAINT [fk_computers_users_1] FOREIGN KEY([last_updated_by])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE [dbo].[computers] CHECK CONSTRAINT [fk_computers_users_1]
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


ALTER TABLE dbo.hardware_assignments  WITH CHECK ADD  CONSTRAINT [fk_hardware_assignments_users_1] FOREIGN KEY([user_id])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.hardware_assignments  WITH CHECK ADD  CONSTRAINT [fk_hardware_assignments_users_2] FOREIGN KEY([last_updated_by])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.hardware_assignments  WITH CHECK ADD  CONSTRAINT [fk_hardware_assignments_computers_1] FOREIGN KEY([control], [control_ts])
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
[computer] [varchar](45) NOT NULL,
[computer_ts] [date] NOT NULL,
[body] [text] NOT NULL,
PRIMARY KEY (id))


ALTER TABLE dbo.comments  WITH CHECK ADD  CONSTRAINT [fk_comments_users_1] FOREIGN KEY([user_id])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.comments  WITH CHECK ADD  CONSTRAINT [fk_comments_computers_1] FOREIGN KEY([control], [control_ts])
REFERENCES [dbo].[computers] ([control], [last_updated_at])
GO

ALTER TABLE [dbo].[comments] CHECK CONSTRAINT [fk_comments_users_1]
GO

ALTER TABLE [dbo].[comments] CHECK CONSTRAINT [fk_comments_computers_1]
GO






CREATE TABLE [dbo].[software]
([id] [int] NOT NULL,
[last_updated_by] [int] NOT NULL,
[name] [varchar](45) NOT NULL,
[software_type] [varchar](45) NOT NULL,
PRIMARY KEY (id, last_updated_by))


ALTER TABLE dbo.software  WITH CHECK ADD  CONSTRAINT [fk_software_users_1] FOREIGN KEY([last_updated_by])
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


ALTER TABLE dbo.licenses  WITH CHECK ADD  CONSTRAINT [fk_licenses_users_1] FOREIGN KEY([last_updated_by])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.licenses  WITH CHECK ADD  CONSTRAINT [fk_licenses_users_2] FOREIGN KEY([user_id])
REFERENCES [dbo].[users] ([id])
GO

ALTER TABLE dbo.licenses  WITH CHECK ADD  CONSTRAINT [fk_licenses_software_1] FOREIGN KEY([software], [software_ts])
REFERENCES [dbo].[software] ([id], [last_updated_at])
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_users_1]
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_users_2]
GO

ALTER TABLE [dbo].[licenses] CHECK CONSTRAINT [fk_licenses_software_1]
GO