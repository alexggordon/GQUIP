CREATE TABLE CTSEquipment.dbo.users
(id int NOT NULL,
department varchar(45) NOT NULL,
first_name varchar(45) NOT NULL,
last_name varchar(45) NOT NULL,
email varchar(45),
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
