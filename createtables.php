$servername = “AdminTrainSQL.gordon.edu”;
$databaseconnection = mssql_connect($servername, “elliott.staude”, “Hash_1_Gordon_Network”);
echo $databaseconnection;
if ($databaseconnection)
{
	$echoholder = $mssql_query(“CREATE TABLE IF NOT EXISTS 'Users' (
  'id' INT NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_by_ts' DATETIME NOT NULL,
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
  'country' VARCHAR(45) NOT NULL, 'last_updated_by_ts'
  'zip' INT NULL,
  PRIMARY KEY ('id, last_updated_at'),
  INDEX 'fk_Users_Users1_idx' ('last_updated_by', 'last_updated_by_ts' ASC),
    CONSTRAINT 'fk_Users_Users1'
    FOREIGN KEY ('last_updated_by', 'last_updated_by_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

	echo $echoholder . “ >>> 1”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Departments' (
  'name' VARCHAR(45) NOT NULL,
  'full_name' VARCHAR(45) NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_by_ts' DATETIME NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  PRIMARY KEY ('name', 'last_updated_at'),
  INDEX 'fk_Departments_Users1_idx' ('last_updated_by', 'last_updated_by_ts' ASC),
    CONSTRAINT 'fk_Departments_Users1'
    FOREIGN KEY ('last_updated_by', 'last_updated_by_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
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
  'legacy_department_ts' DATETIME NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_by_ts' DATETIME NOT NULL,
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
  PRIMARY KEY ('control', 'last_updated_at'),
  INDEX 'fk_Computers_Users1_idx' ('last_updated_by', 'last_updated_by_ts' ASC),
  INDEX 'fk_Computers_Departments1_idx' ('legacy_department', 'legacy_department_ts' ASC),
  CONSTRAINT 'fk_Computers_Users1'
    FOREIGN KEY ('last_updated_by', 'last_updated_by_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Computers_Departments1'
    FOREIGN KEY ('legacy_department', 'legacy_department_ts')
    REFERENCES 'Departments'('name', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 4”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Hardware_assignments' (
  'id' INT NOT NULL,
  'user_id' INT,
  'user_id_ts' DATETIME NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_by_ts' DATETIME NOT NULL,
  'control' VARCHAR(45) NOT NULL,
  'control_ts' DATETIME NOT NULL,
  'department_id' VARCHAR(45),
  'department_id_ts' DATETIME NOT NULL,
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
  PRIMARY KEY ('id', 'last_updated_at'),
  INDEX 'fk_Hardware_assignments_Users1_idx' ('user_id', 'user_id_ts' ASC),
  INDEX 'fk_Hardware_assignments_Departments1_idx' ('department_id', 'department_id_ts' ASC),
  INDEX 'fk_Hardware_assignments_Computers1_idx' ('control', 'control_ts' ASC),
  INDEX 'fk_Hardware_assignments_Users2_idx' ('last_updated_by', 'last_updated_by_ts' ASC),
  CONSTRAINT 'fk_Hardware_assignments_Users1'
    FOREIGN KEY ('user_id', 'user_id_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Hardware_assignments_Departments1'
    FOREIGN KEY ('department_id', 'department_id_ts')
    REFERENCES 'Departments'('name', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Hardware_assignments_Computers1'
    FOREIGN KEY ('control', 'control_ts')
    REFERENCES 'Computers' ('control', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Hardware_assignments_Users2'
    FOREIGN KEY ('last_updated_by', 'last_updated_by_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 5”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Comments' (
  'id' INT NOT NULL,
  'user_id' INT NOT NULL,
  'user_id_ts' DATETIME NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_by_ts' DATETIME NOT NULL,
  'computer_id' INT NOT NULL,
  'computer_id_ts' DATETIME NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  'body' TEXT NOT NULL,
  PRIMARY KEY ('id', 'last_updated_at'),
  INDEX 'fk_Comments_Computers1_idx' ('computer_id', 'computer_id_ts' ASC),
  INDEX 'fk_Comments_Users1_idx' ('user_id', 'user_id_ts' ASC),
  INDEX 'fk_Comments_Users2_idx' ('last_updated_by', 'last_updated_by_ts' ASC),
  CONSTRAINT 'fk_Comments_Computers1'
    FOREIGN KEY ('computer_id', 'computer_id_ts')
    REFERENCES 'Computers'('control', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Comments_Users1'
    FOREIGN KEY ('user_id', 'user_id_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Comments_Users2'
    FOREIGN KEY ('last_updated_by', 'last_updated_by_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 6”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Software' (
  'id' INT NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_by_ts' DATETIME NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  'name' VARCHAR(45) NOT NULL,
  'software_type' VARCHAR(45) NOT NULL,
  PRIMARY KEY ('id', 'last_updated_at'),
  INDEX 'fk_Software_Users1_idx' ('last_updated_by', 'last_updated_by_ts' ASC),
  CONSTRAINT 'fk_Software_Users1'
    FOREIGN KEY ('last_updated_by', 'last_updated_by_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

echo $echoholder . “ >>> 7”;

	$echoholder = mssql_query(“CREATE TABLE IF NOT EXISTS 'Licenses' (
  'id' INT NOT NULL,
  'user_id' INT NOT NULL,
  'user_id_ts' DATETIME NOT NULL,
  'last_updated_by' INT NOT NULL,
  'last_updated_by_ts' DATETIME NOT NULL,
  'software_id' INT NOT NULL,
  'software_id_ts' DATETIME NOT NULL,
  'last_updated_at' DATETIME NOT NULL,
  'created_at' DATETIME NOT NULL,
  PRIMARY KEY ('id'),
  INDEX 'fk_Licenses_Users1_idx' ('user_id', 'user_id_ts' ASC),
  INDEX 'fk_Licenses_Software1_idx' ('software_id', 'software_id_ts' ASC),
  INDEX 'fk_Licenses_Users2_idx' ('last_updated_by', 'last_updated_by_ts' ASC),
  CONSTRAINT 'fk_Licenses_Users1'
    FOREIGN KEY ('user_id', 'user_id_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Licenses_Software1'
    FOREIGN KEY ('software_id', 'software_id_ts')
    REFERENCES 'Software'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT 'fk_Licenses_Users2'
    FOREIGN KEY ('last_updated_by', 'last_updated_by_ts')
    REFERENCES 'Users'('id', 'last_updated_at')
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);”, $databaseconnection);

	echo $echoholder . “ >>> 8”;

mssql_close($databaseconnection);
}
else
{
	echo “ERRAHR!!1! WE CAN HAZ PROBLEM CATPAN”;
}
