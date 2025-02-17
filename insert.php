<?php

include 'connection(urms).php';
//inserting in SystemAdmin table
$stmt = $conn->prepare("INSERT INTO SystemAdmin (ID, Admin_Name,pass) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $ID, $Admin_Name, $pass);

$ID  = "3178";
$Admin_Name = "Fatima Saleem";
$pass = "fast1234";
$stmt->execute();

$ID = "3198";
$Admin_Name = "Shafaq Saleem";
$pass = "fast1234";
$stmt->execute();

echo "Records inserted in SystemAdmin table successfully";

//inserting in Course table
$stmt = $conn->prepare("INSERT INTO Course (Course_ID, Course_Name,Course_sem) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $Course_ID, $Course_Name, $Course_sem);

$Course_ID  = "MT119";
$Course_Name = "Calculus";
$Course_sem = "1";
$stmt->execute();

$Course_ID = "NS1001";
$Course_Name = "Applied Physics";
$Course_sem = "1";
$stmt->execute();

$Course_ID = "CS118";
$Course_Name = "Programming Fundamentals";
$Course_sem = "1";
$stmt->execute();

$Course_ID = "CS1004";
$Course_Name = "Object Oriented Programming";
$Course_sem = "2";
$stmt->execute();

$Course_ID = "EE1005";
$Course_Name = "Digital Logic Design";
$Course_sem = "2";
$stmt->execute();

$Course_ID = "EE2003";
$Course_Name = "Computer and Organizational Language";
$Course_sem = "2";
$stmt->execute();


$Course_ID = "CS218";
$Course_Name = "Data Structures";
$Course_sem = "3";
$stmt->execute();


$Course_ID = "MT104";
$Course_Name = "Linear Algebra";
$Course_sem = "3";
$stmt->execute();


$Course_ID = "CS211";
$Course_Name = "Discrete Structures";
$Course_sem = "3";
$stmt->execute();


$Course_ID = "CS2006";
$Course_Name = "Operating System";
$Course_sem = "4";
$stmt->execute();


$Course_ID = "CS325";
$Course_Name = "Numerical Computing";
$Course_sem = "4";
$stmt->execute();


$Course_ID = "CS3005";
$Course_Name = "Theory of Automata";
$Course_sem = "4";
$stmt->execute();


$Course_ID = "CS2005";
$Course_Name = "Database System";
$Course_sem = "5";
$stmt->execute();


$Course_ID = "MT3001";
$Course_Name = "Graph Theory";
$Course_sem = "5";
$stmt->execute();


$Course_ID = "CS3004";
$Course_Name = "Software Design and Analysis";
$Course_sem = "5";
$stmt->execute();


$Course_ID = "CS307";
$Course_Name = "Computer Networks";
$Course_sem = "6";
$stmt->execute();


$Course_ID = "CS461";
$Course_Name = "Artifical Intelligence";
$Course_sem = "6";
$stmt->execute();


$Course_ID = "SS108";
$Course_Name = "Technical Business Writing";
$Course_sem = "6";
$stmt->execute();


$Course_ID = "CS3001";
$Course_Name = "Information Security";
$Course_sem = "7";
$stmt->execute();


$Course_ID = "CS4042";
$Course_Name = "Information Processing Techniques";
$Course_sem = "7";
$stmt->execute();


$Course_ID = "CS4046";
$Course_Name = "Business Analytics Techniques";
$Course_sem = "8";
$stmt->execute();


$Course_ID = "SE4031";
$Course_Name = "Design Defects and Reconstructing";
$Course_sem = "8";
$stmt->execute();

echo "Records inserted in Course table successfully";

// inserting in Teacher table
$stmt = $conn->prepare("INSERT INTO Teacher (ID, Teacher_Name,pass,Teacher_email,Teacher_phone) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $ID, $Teacher_Name,$pass,$Teacher_email, $Teacher_phone);

$ID = "t10005";
$Teacher_Name = "Nadeem Khan";
$pass = "Fast1234";
$Teacher_email = "nadeem.khan@nu.edu.pk";
$Teacher_phone = "0312-3456789";
$stmt->execute();

$ID = "t10006";
$Teacher_Name = "Asma Masood";
$pass = "Fast1234";
$Teacher_email = "asma.masood@nu.edu.pk";
$Teacher_phone = "0321-4567890";
$stmt->execute();


$ID = "t10018";
$Teacher_Name = "Anaum Qureshi";
$pass = "Fast1234";
$Teacher_email = "anaum.qureshi@nu.edu.pk";
$Teacher_phone = "0300-1234567";
$stmt->execute();


$ID = "t10008";
$Teacher_Name = "Sonia Nasir";
$pass = "Fast1234";
$Teacher_email = "sonia.nasir@nu.edu.pk";
$Teacher_phone = "0333-2345678";
$stmt->execute();


$ID = "t10009";
$Teacher_Name = "Basit Jasani";
$pass = "Fast1234";
$Teacher_email = "basit.jasani@nu.edu.pk";
$Teacher_phone = "0345-3456781";
$stmt->execute();


$ID = "t10010";
$Teacher_Name = "Muhammad Shahzad";
$pass = "Fast1234";
$Teacher_email = "muhammad.shahzad@nu.edu.pk";
$Teacher_phone = "0334-4567892";
$stmt->execute();


$ID = "t10011";
$Teacher_Name = "Abeer Gauher";
$pass = "Fast1234";
$Teacher_email = "abeer.gauher@nu.edu.pk";
$Teacher_phone = "0322-5678910";
$stmt->execute();


$ID = "t10012";
$Teacher_Name = "Farooque Hassan";
$pass = "Fast1234";
$Teacher_email = "farooque.hassan@nu.edu.pk";
$Teacher_phone = "0313-6789012";
$stmt->execute();


$ID = "t10013";
$Teacher_Name = "Aashir Mahboob";
$pass = "Fast1234";
$Teacher_email = "aashir.mahboob@nu.edu.pk";
$Teacher_phone = "0314-7890123";
$stmt->execute();


$ID = "t10014";
$Teacher_Name = "Rabia Tabassum";
$pass = "Fast1234";
$Teacher_email = "rabia.tabassum@nu.edu.pk";
$Teacher_phone = "0315-8901234";
$stmt->execute();


$ID = "t10015";
$Teacher_Name = "Zakir Hussain";
$pass = "Fast1234";
$Teacher_email = "zakir.hussain@nu.edu.pk";
$Teacher_phone = "0316-9012345";
$stmt->execute();


$ID = "t10017";
$Teacher_Name = "Fahad Sherwani";
$pass = "Fast1234";
$Teacher_email = "fahad.sherwani@nu.edu.pk";
$Teacher_phone = "0318-1234560";
$stmt->execute();


$ID = "t10000";
$Teacher_Name = "Fareeha Sultan";
$pass = "Fast1234";
$Teacher_email = "fareeha.sultan@nu.edu.pk";
$Teacher_phone = "0320-3456012";
$stmt->execute();


$ID = "t10002";
$Teacher_Name = "Shoaib Raza";
$pass = "Fast1234";
$Teacher_email = "shoaib.raza@nu.edu.pk";
$Teacher_phone = "0324-5601234";
$stmt->execute();


$ID = "t10019";
$Teacher_Name = "Abdul Rahman";
$pass = "Fast1234";
$Teacher_email = "abdul.rahman@nu.edu.pk";
$Teacher_phone = "0327-1234578";
$stmt->execute();


$ID = "t10020";
$Teacher_Name = "Usama Antuley";
$pass = "Fast1234";
$Teacher_email = "usama.antuley@nu.edu.pk";
$Teacher_phone = "0328-2345789";
$stmt->execute();


$ID = "t10021";
$Teacher_Name = "Khusro Mian";
$pass = "Fast1234";
$Teacher_email = "khusro.mian@nu.edu.pk";
$Teacher_phone = "0329-3456890";
$stmt->execute();


$ID = "t10022";
$Teacher_Name = "Bakhtawer Abbasi";
$pass = "Fast1234";
$Teacher_email = "bakhtawer.abbasi@nu.edu.pk";
$Teacher_phone = "0331-5678902";
$stmt->execute();


$ID = "t10024";
$Teacher_Name = "Fizza Mansoor";
$pass = "Fast1234";
$Teacher_email = "fizza.mansoor@nu.edu.pk";
$Teacher_phone = "0335-7890124";
$stmt->execute();


$ID = "t10026";
$Teacher_Name = "Nazish Kanwal";
$pass = "Fast1234";
$Teacher_email = "nazish.kanwal@nu.edu.pk";
$Teacher_phone = "0337-9012346";
$stmt->execute();


$ID = "t10028";
$Teacher_Name = "Nida Munawar";
$pass = "Fast1234";
$Teacher_email = "nida.munawar@nu.edu.pk";
$Teacher_phone = "0338-0123458";
$stmt->execute();


$ID = "t10030";
$Teacher_Name = "Ghufran Ahmed";
$pass = "Fast1234";
$Teacher_email = "ghufran.ahmed@nu.edu.pk";
$Teacher_phone = "0341-3456712";
$stmt->execute();

echo "Records inserted in Teacher table successfully";

?>