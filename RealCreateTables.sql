    CREATE TABLE users (
    	id INT NOT NULL,
    	last_updated_by INT NOT NULL,
    	department VARCHAR(45),
    	last_updated_at DATETIME NOT NULL,
    	created_at DATETIME NOT NULL,
    	auth INT NOT NULL,
    	first VARCHAR(45) NOT NULL,
    	middle VARCHAR(45) NOT NULL,
    	last VARCHAR(45) NOT NULL,
    	role VARCHAR(45) NOT NULL,
    	building VARCHAR(45) NOT NULL,
    	room INT NOT NULL,
    	email VARCHAR(45) NOT NULL,
    	phone VARCHAR(45),
    	barcode VARCHAR(45),
    	active VARCHAR(45) NOT NULL,
    	on_campus BIT NOT NULL,
    	state VARCHAR(45),
    	country VARCHAR(45) NOT NULL,
    	zip INT NULL)
	
	ALTER TABLE
		users
	ADD PRIMAY KEY
		(id)


	ALTER TABLE
		users
	ADD FOREIGN KEY
		(column_name)
	REFERENCES
		 (column_name)
		
		










		
	ALTER TABLE
		table_name
	ADD PRIMAY KEY
		(column_name)


    CREATE TABLE table_name (
	column_name column_type (column_size) column_constraint,)
	
	ALTER TABLE
		table_name
	ADD FOREIGN KEY
		(column_name)
	REFERENCES
		table_name (column_name)
		
		
		
	ALTER TABLE
		table_name
	ADD
		column_name data_type (size)

		
		
	ALTER TABLE
		table_name
	ADD PRIMAY KEY
		(column_name)


