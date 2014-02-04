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
	[control] [varchar](45) NOT NULL,
	[department] [varchar](45) NOT NULL,
	[last_updated_by] [int] NOT NULL,
	[serial] [varchar](25) NULL,
	[model] [varchar](25) NOT NULL,
	[manufacturer] [varchar](25) NOT NULL,
	[purchase_date] [varchar](25) NOT NULL,
	[purchase_price] [varchar](25) NOT NULL,
	[purchase_acct] [varchar](25) NOT NULL,
	[usage_status] [varchar](25) NULL,
	[memory] [varchar](25) NULL,
	[hard_drive] [varchar](25) NULL,
	[warranty_length] [varchar](25) NOT NULL,
	[warranty_end] [varchar](25) NOT NULL,
	[warranty_type] [varchar](25) NULL,
	[replacement_year] [varchar](25) NOT NULL,
	[computer_type] [varchar](25) NULL,
	[user_id] [varchar](25) NULL,
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

CREATE TABLE CTSEquipment.dbo.hardware_assignments
(id INT NOT NULL,
user_id INT,
last_updated_by INT NOT NULL,
control VARCHAR(45) NOT NULL,
control_ts DATETIME NOT NULL,
department_id VARCHAR(45),
last_updated_at DATETIME NOT NULL,
created_at DATETIME NOT NULL,
fullorpart BIT NOT NULL,
dedicated BIT NOT NULL,
primary_computer BIT NOT NULL,
replace_with_recycled BIT NOT NULL,
nextneed_macpc BIT NOT NULL,
nextneed_laptopdesktop BIT NOT NULL,
special BIT NOT NULL,
start_assignment DATETIME NOT NULL,
lab BIT NOT NULL,
end_assignment DATETIME,
nextneed_note TEXT,
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