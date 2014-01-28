$servername = “AdminTrainSQL.gordon.edu”;
$databaseconnection = mssql_connect($servername, “elliott.staude”, “Hash_1_Gordon_Network”);
echo $databaseconnection;
if ($databaseconnection)
{
	$echoholder = $mssql_query(“CREATE TABLE IF NOT EXISTS 'Users' (
  'id' INT NOT NULL,
  'last_updated_by' INT NOT NULL,
  'department' VARCHAR(45),
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  'auth' INT NOT NULL,
  'first' VARCHAR(45) NOT NULL,
  'middle' VARCHAR(45) NOT NULL,
  'last' VARCHAR(45) NOT NULL,
  'role' VARCHAR(45) NOT NULL,
  'building' VARCHAR(45) NOT NULL,
  'room' INT NOT NULL,
  'email' VARCHAR(45) NOT NULL,
  'phone' VARCHAR(45),
  'barcode' VARCHAR(45),
  'active' VARCHAR(45) NOT NULL,
  'on_campus' BIT NOT NULL,
  'state' VARCHAR(45),
  'country' VARCHAR(45) NOT NULL,
  'zip' INT NULL,
  PRIMARY KEY ('id'),
  INDEX 'fk_Users_Users1_idx' ('last_updated_by' ASC),
    CONSTRAINT 'fk_Users_Users1'
    FOREIGN KEY ('last_updated_by')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

	echo $echoholder . “ >>> 1”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Departments' (
  'name' VARCHAR(45) NOT NULL,
  'full_name' VARCHAR(45) NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  PRIMARY KEY ('name'),
  INDEX 'fk_Departments_Users1_idx' ('last_updated_by' ASC),
  CONSTRAINT 'fk_Departments_Users1'
    FOREIGN KEY ('last_updated_by')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 2”;

	$echoholder = mssql_query(“ALTER TABLE Users
ADD FOREIGN KEY (department)
REFERENCES Departments(name)”, $databaseconnection);

echo $echoholder . “ >>> 3”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Computers' (
  'control' VARCHAR(45) NOT NULL,
  'legacy_department' VARCHAR(45),
  'last_updated_by' INT NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  'serial' VARCHAR(45) NOT NULL,
  'model' VARCHAR(45) NOT NULL,
  'manufacturer' VARCHAR(45) NOT NULL,
  'purchase_date' DATETIME NOT NULL,
  'purchase_price' MONEY NOT NULL,
  'purchase_acct' VARCHAR(45) NOT NULL,
  'usage_status' VARCHAR(45) NOT NULL,
  'memory' INT NULL,
  'hard_drive' INT NULL,
  'warranty_length' VARCHAR(45),
  'warranty_end' VARCHAR(45),
  'warranty_type' VARCHAR(45),
  'replacement_year' YEAR,
  'computer_type' VARCHAR(45),
  'legacy_userid' VARCHAR(45),
  'cameron_id' VARCHAR(45),
  'part_number' VARCHAR(45),
  'ip_address' VARCHAR(45),
  PRIMARY KEY ('control'),
  INDEX 'fk_Computers_Users1_idx' ('last_updated_by' ASC),
  INDEX 'fk_Computers_Departments1_idx' ('legacy_department' ASC),
  CONSTRAINT 'fk_Computers_Users1'
    FOREIGN KEY ('last_updated_by')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Computers_Departments1'
    FOREIGN KEY ('legacy_department')
    REFERENCES 'Departments'('name')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 4”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Hardware_assignments' (
  'id' INT NOT NULL,
  'user_id' INT,
  'last_updated_by' INT NOT NULL,
  'control' VARCHAR(45) NOT NULL,
  'department_id' VARCHAR(45),
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  'fullorpart' BIT NOT NULL,
  'dedicated' BIT NOT NULL,
  'primary_computer' BIT NOT NULL,
  'replace_with_recycled' BIT NOT NULL,
  'nextneed_macpc' BIT NOT NULL,
  'nextneed_laptopdesktop' BIT NOT NULL,
  'special' BIT NOT NULL,
  'start_assignment' DATETIME NOT NULL,
  'lab' BIT NOT NULL,
  'end_assignment' DATETIME,
  'nextneed_note' TEXT,
  PRIMARY KEY ('id'),
  INDEX 'fk_Hardware_assignments_Users1_idx' ('user_id' ASC),
  INDEX 'fk_Hardware_assignments_Departments1_idx' ('department_id' ASC),
  INDEX 'fk_Hardware_assignments_Computers1_idx' ('control' ASC),
  INDEX 'fk_Hardware_assignments_Users2_idx' ('last_updated_by' ASC),
  CONSTRAINT 'fk_Hardware_assignments_Users1'
    FOREIGN KEY ('user_id')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Hardware_assignments_Departments1'
    FOREIGN KEY ('department_id')
    REFERENCES 'Departments'('name')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Hardware_assignments_Computers1'
    FOREIGN KEY ('control')
    REFERENCES 'Computers' ('control')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Hardware_assignments_Users2'
    FOREIGN KEY ('last_updated_by')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 5”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Comments' (
  'id' INT NOT NULL,
  'user_id' INT NOT NULL,
  'last_updated_by' INT NOT NULL,
  'computer_id' INT NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  'body' TEXT NOT NULL,
  PRIMARY KEY ('id'),
  INDEX 'fk_Comments_Computers1_idx' ('computer_id' ASC),
  INDEX 'fk_Comments_Users1_idx' ('user_id' ASC),
  INDEX 'fk_Comments_Users2_idx' ('last_updated_by' ASC),
  CONSTRAINT 'fk_Comments_Computers1'
    FOREIGN KEY ('computer_id')
    REFERENCES 'Computers'('control')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Comments_Users1'
    FOREIGN KEY ('user_id')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Comments_Users2'
    FOREIGN KEY ('last_updated_by')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 6”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Software' (
  'id' INT NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  'name' VARCHAR(45) NOT NULL,
  'software_type' VARCHAR(45) NOT NULL,
  PRIMARY KEY ('id'),
  INDEX 'fk_Software_Users1_idx' ('last_updated_by' ASC),
  CONSTRAINT 'fk_Software_Users1'
    FOREIGN KEY ('last_updated_by')
    REFERENCES 'Users’('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 7”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Licenses' (
  'id' INT NOT NULL,
  'user_id' INT NOT NULL,
  'last_updated_by' INT NOT NULL,
  'software_id' INT NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  PRIMARY KEY ('id'),
  INDEX 'fk_Licenses_Users1_idx' ('user_id' ASC),
  INDEX 'fk_Licenses_Software1_idx' ('software_id' ASC),
  INDEX 'fk_Licenses_Users2_idx' ('last_updated_by' ASC),
  CONSTRAINT 'fk_Licenses_Users1'
    FOREIGN KEY ('user_id')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Licenses_Software1'
    FOREIGN KEY ('software_id')
    REFERENCES 'Software'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Licenses_Users2'
    FOREIGN KEY ('last_updated_by')
    REFERENCES 'Users'('id')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

	echo $echoholder . “ >>> 8”;

mssql_close($databaseconnection);
}
else
{
	echo “ERRAHR!!1! WE CAN HAZ PROBLEM CATPAN”;
}
