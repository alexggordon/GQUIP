INSERT INTO gordonstudents (id, Email, grad_student, Class, FirstName, MiddleName, LastName)
SELECT DISTINCT id, Email, grad_student, Class, FirstName, MiddleName, LastName
FROM students stnts
WHERE
   NOT EXISTS (SELECT * FROM gordonstudents gstnts
              WHERE stnts.id = gstnts.id);

UPDATE gordonstudents
SET Email = (SELECT Email FROM students as stnts WHERE stnts.id = gordonstudents.id), 
grad_student = (SELECT grad_student FROM students as stnts WHERE stnts.id = gordonstudents.id), 
Class = (SELECT Class FROM students as stnts WHERE stnts.id = gordonstudents.id), 
FirstName = (SELECT FirstName FROM students as stnts WHERE stnts.id = gordonstudents.id), 
MiddleName = (SELECT MiddleName FROM students as stnts WHERE stnts.id = gordonstudents.id), 
LastName = (SELECT LastName FROM students as stnts WHERE stnts.id = gordonstudents.id)
WHERE
   (EXISTS (SELECT * FROM students
              WHERE students.id = gordonstudents.id));

INSERT INTO FacandStaff (ID, Email, Type, Dept, OnCampusDepartment, FirstName, LastName)
SELECT DISTINCT ID, Email, Type, Dept, OnCampusDepartment, FirstName, LastName
FROM FacStaff fs
WHERE
   NOT EXISTS (SELECT * FROM FacandStaff fas
              WHERE fs.ID = fas.ID);

UPDATE FacandStaff
SET Email = (SELECT Email FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
OnCampusDepartment = (SELECT OnCampusDepartment FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
Type = (SELECT Type FROM FacStaff as fs WHERE fs.id = FacandStaff.id),
Dept = (SELECT Dept FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
FirstName = (SELECT FirstName FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
LastName = (SELECT LastName FROM FacStaff as fs WHERE fs.id = FacandStaff.id)
WHERE
   (EXISTS (SELECT * FROM FacStaff
              WHERE FacStaff.ID = FacandStaff.ID));


/////////////////////////////////////////////////////////////////
/
/////////////////////////////////////////////////////////////////
/
/ WARNING: things past this point not to be used in sql stored procedure
/
/////////////////////////////////////////////////////////////////
/
/////////////////////////////////////////////////////////////////


#v.1 The iteration currently used for GQUIP

#FOR BOTH TABLES
#1. INSERT STUFF NOT IN THE TABLE BUT IN THE VIEW
#2. UPDATE STUFF IN THE TABLE AND IN THE VIEW

#gordonstudents

  #1. INSERTING 

INSERT INTO gordonstudents (id, Email, grad_student, Class, FirstName, MiddleName, LastName)
SELECT DISTINCT id, Email, grad_student, Class, FirstName, MiddleName, LastName
FROM students stnts
WHERE
   NOT EXISTS (SELECT * FROM gordonstudents gstnts
              WHERE stnts.id = gstnts.id);


  #2. UPDATING

UPDATE gordonstudents
SET Email = (SELECT Email FROM students as stnts WHERE stnts.id = gordonstudents.id), 
grad_student = (SELECT grad_student FROM students as stnts WHERE stnts.id = gordonstudents.id), 
Class = (SELECT Class FROM students as stnts WHERE stnts.id = gordonstudents.id), 
FirstName = (SELECT FirstName FROM students as stnts WHERE stnts.id = gordonstudents.id), 
MiddleName = (SELECT MiddleName FROM students as stnts WHERE stnts.id = gordonstudents.id), 
LastName = (SELECT LastName FROM students as stnts WHERE stnts.id = gordonstudents.id)
WHERE
   (EXISTS (SELECT * FROM students
              WHERE students.id = gordonstudents.id));

#FacandStaff

  #1. INSERTING 

INSERT INTO FacandStaff (ID, Email, Type, Dept, OnCampusDepartment, FirstName, LastName)
SELECT DISTINCT ID, Email, Type, Dept, OnCampusDepartment, FirstName, LastName
FROM FacStaff fs
WHERE
   NOT EXISTS (SELECT * FROM FacandStaff fas
              WHERE fs.ID = fas.ID);

  #2. UPDATING

UPDATE FacandStaff
SET Email = (SELECT Email FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
OnCampusDepartment = (SELECT OnCampusDepartment FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
Type = (SELECT Type FROM FacStaff as fs WHERE fs.id = FacandStaff.id),
Dept = (SELECT Dept FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
FirstName = (SELECT FirstName FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
LastName = (SELECT LastName FROM FacStaff as fs WHERE fs.id = FacandStaff.id)
WHERE
   (EXISTS (SELECT * FROM FacStaff
              WHERE FacStaff.ID = FacandStaff.ID));


#v.2 Not the one to be used - requires adding a present_in_ad column to schema (WIP)

#FOR BOTH TABLES
#1. INSERT STUFF NOT IN THE TABLE BUT IN THE VIEW   (!!! NYI - adding present_in_ad column to user rows as they are inserted !!!)
#2. OUTMODE STUFF IN THE TABLE BUT NOT IN THE VIEW
#3. UPDATE STUFF IN THE TABLE AND IN THE VIEW

#gordonstudents

  #1. INSERTING 

INSERT INTO gordonstudents (id, Email, grad_student, Class, FirstName, MiddleName, LastName)
SELECT DISTINCT id, Email, grad_student, Class, FirstName, MiddleName, LastName
FROM students stnts
WHERE
   NOT EXISTS (SELECT * FROM gordonstudents gstnts
              WHERE stnts.id = gstnts.id);

  #2. OUTMODING

UPDATE gordonstudents
SET present_in_ad = 0
WHERE
   (NOT EXISTS (SELECT * FROM students
              WHERE students.ID = gordonstudents.ID));

  #3. UPDATING

UPDATE gordonstudents
SET Email = (SELECT Email FROM students as stnts WHERE stnts.id = gordonstudents.id), 
grad_student = (SELECT grad_student FROM students as stnts WHERE stnts.id = gordonstudents.id), 
Class = (SELECT Class FROM students as stnts WHERE stnts.id = gordonstudents.id), 
FirstName = (SELECT FirstName FROM students as stnts WHERE stnts.id = gordonstudents.id), 
MiddleName = (SELECT MiddleName FROM students as stnts WHERE stnts.id = gordonstudents.id), 
LastName = (SELECT LastName FROM students as stnts WHERE stnts.id = gordonstudents.id)
WHERE
   (EXISTS (SELECT * FROM students
              WHERE students.id = gordonstudents.id) 
   AND gordonstudents.present_in_ad = 1);

#FacandStaff

  #1. INSERTING 

INSERT INTO FacandStaff (ID, Email, Type, Class, Dept, OnCampusDepartment, FirstName, LastName)
SELECT DISTINCT ID, Email, Type, Class, Dept, OnCampusDepartment, FirstName, LastName
FROM FacStaff fs
WHERE
   NOT EXISTS (SELECT * FROM FacandStaff fas
              WHERE fs.ID = fas.ID);

  #2. OUTMODING

UPDATE FacandStaff
SET present_in_ad = 0
WHERE
   (NOT EXISTS (SELECT * FROM FacStaff
              WHERE FacStaff.ID = FacandStaff.ID));

  #3. UPDATING

UPDATE FacandStaff
SET Email = (SELECT Email FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
OnCampusDepartment = (SELECT OnCampusDepartment FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
Type = (SELECT Type FROM FacStaff as fs WHERE fs.id = FacandStaff.id),
Dept = (SELECT Dept FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
FirstName = (SELECT FirstName FROM FacStaff as fs WHERE fs.id = FacandStaff.id), 
LastName = (SELECT LastName FROM FacStaff as fs WHERE fs.id = FacandStaff.id)
WHERE
   (EXISTS (SELECT * FROM FacStaff
              WHERE FacStaff.ID = FacandStaff.ID)
   AND FacandStaff.present_in_ad = 1);






# Testing data queries - prepare the database for testing the update procedure


DELETE from gordonstudents
WHERE gordonstudents.id = 123456789;

DELETE from FacandStaff
WHERE FacandStaff.id = 111000111;




DELETE from gordonstudents
WHERE (gordonstudents.id = 50091531
OR gordonstudents.id = 9591935);

UPDATE gordonstudents
set LastName = 'Silly'
WHERE (gordonstudents.id = 50037759
OR gordonstudents.id = 50051103);

INSERT INTO gordonstudents (id, Email, grad_student, Class, FirstName, MiddleName, LastName)
VALUES (123456789, 'Tom.Bombadil@gordon.edu', 'Y', 4, 'Tom', 'Silmaril', 'Bombadil');




DELETE from FacandStaff
WHERE (FacandStaff.ID = 40000009
OR FacandStaff.ID = 40000009);

UPDATE FacandStaff
set LastName = 'Silly'
WHERE (FacandStaff.ID = 40000435
OR FacandStaff.ID = 8660209);

INSERT INTO FacandStaff (ID, Email, Type, Dept, OnCampusDepartment, FirstName, LastName)
VALUES (111000111, 'Gandalf.Thegray@gordon.edu', 'Staff','DEV', 'Development', 'Gandalf', 'Thegray');




# Run this delete pair to make sure the database is back to normal after

DELETE from gordonstudents
WHERE gordonstudents.id = 123456789;

DELETE from FacandStaff
WHERE FacandStaff.id = 111000111;

