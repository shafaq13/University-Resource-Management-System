<?php
include 'connection(urms).php';
//create table
$sql = "CREATE TABLE SystemAdmin (
    ID VARCHAR(7) PRIMARY KEY,
    Admin_Name VARCHAR(50) NOT NULL,
    pass VARCHAR(20) NOT NULL
    )";
    if (mysqli_query($conn, $sql)) {
    echo "Admin table created successfully\n";
    } else {
    echo "Error creating table: " . mysqli_error($conn);
    }
    
$sql = "CREATE TABLE Teacher (
    ID VARCHAR(7) PRIMARY KEY,
    Teacher_Name VARCHAR(50) NOT NULL,
    pass VARCHAR(20) NOT NULL,
    Teacher_email Varchar(255) ,
    Teacher_phone Varchar(12) ,
    CONSTRAINT chk_id CHECK (ID REGEXP '^t[0-9]{5}$'),
    CONSTRAINT chk_email CHECK (Teacher_email = CONCAT(LOWER(SUBSTRING_INDEX(Teacher_Name, ' ', 1)), '.', LOWER(SUBSTRING_INDEX(Teacher_Name, ' ', -1)), '@nu.edu.pk')),
    CONSTRAINT chk_phone_number CHECK (Teacher_phone REGEXP '^03[0-9]{2}-[0-9]{7}$')
    )";
    if (mysqli_query($conn, $sql)) {
    echo "Teacher table created successfully\n";
    } else {
    echo "Error creating table: " . mysqli_error($conn);
    }
$sql = "CREATE TABLE Student (
    ID VARCHAR(7) PRIMARY KEY,
    Student_Name VARCHAR(50) NOT NULL,
    pass VARCHAR(20) NOT NULL,
    Student_email Varchar(255) ,
    Student_phone Varchar(12) ,
    Student_joining_date DATE NOT NULL,
    CONSTRAINT chk_id CHECK (ID REGEXP '^k[0-9]{6}$'),
    CONSTRAINT chk_email CHECK (Student_email = CONCAT(ID, '@nu.edu.pk')),
    CONSTRAINT chk_phone_number CHECK (Student_phone REGEXP '^03[0-9]{2}-[0-9]{7}$')
    )
    ";
    if (mysqli_query($conn, $sql)) {
    echo "Student table created successfully\n";
    } else {
    echo "Error creating table: " . mysqli_error($conn);
    }
$sql = "CREATE TABLE Course (
    Course_ID VARCHAR(6) PRIMARY KEY,
    Course_Name VARCHAR(50) NOT NULL,
    Course_sem INT NOT NULL CHECK (Course_sem > 0 AND Course_sem < 9),
    CONSTRAINT chk_course_id CHECK (Course_id REGEXP '^[A-Z]{2}[0-9]{4}$' OR Course_id REGEXP '^[A-Z]{2}[0-9]{3}$')
    )";
    if (mysqli_query($conn, $sql)) {
    echo "Course table created successfully\n";
    } else {
    echo "Error creating table: " . mysqli_error($conn);
    }
$sql = "CREATE TABLE Enrolled (
    Student_ID VARCHAR(7) ,
    Course_ID VARCHAR(6) ,
    Teacher_ID VARCHAR(7),
    PRIMARY KEY(Student_ID,Course_ID),
    FOREIGN KEY (Student_ID) REFERENCES Student(ID) ON DELETE CASCADE, 
    FOREIGN KEY (Course_ID) REFERENCES Course(Course_ID) ON DELETE CASCADE,
    FOREIGN KEY (Teacher_ID) REFERENCES Teacher(ID) ON DELETE CASCADE
    )";
    if (mysqli_query($conn, $sql)) {
    echo "Enrolled table created successfully\n";
    } else {
    echo "Error creating table: " . mysqli_error($conn);
    }
$sql = "CREATE TABLE RecourseType (
    TypeId INT CHECK (TypeId IN (1, 2, 3, 4)),
    TypeName VARCHAR(20) NOT NULL CHECK (TypeName IN ('Assignment' , 'Lecture' , 'Paper', 'Quiz')),
    PRIMARY KEY (TypeId)
    )";
    if (mysqli_query($conn, $sql)) {
    echo "Recourse Type table created successfully\n";
    } else {
    echo "Error creating table: " . mysqli_error($conn);
    }
$sql = "CREATE TABLE Recourse (
    TypeId INT CHECK (TypeId IN (1, 2, 3, 4)),
    Course_ID VARCHAR(6) ,
    Teacher_ID VARCHAR(7),
    Link VARCHAR(255), 
    -- PRIMARY KEY (TypeId, Course_Id),
    FOREIGN KEY (TypeId) REFERENCES RecourseType(TypeId) ON DELETE CASCADE,
    FOREIGN KEY (Course_Id) REFERENCES Course(Course_ID) ON DELETE CASCADE,
    FOREIGN KEY (Teacher_Id) REFERENCES Teacher(ID) ON DELETE CASCADE
    )";
    if (mysqli_query($conn, $sql)) {
    echo "Recourse table created successfully\n";
    } else {
    echo "Error creating table: " . mysqli_error($conn);
    }  
?>