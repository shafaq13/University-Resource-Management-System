<?php
include 'connection(urms).php';
//inserting in Recourse table
$stmt = $conn->prepare("INSERT INTO RecourseType (TypeId,TypeName) VALUES (?, ?)");
$stmt->bind_param("ss", $TypeId,$TypeName);

$TypeId  = 1;
$TypeName = "Assignment";
$stmt->execute();

$TypeId  = 2;
$TypeName = "Lecture";
$stmt->execute();

$TypeId  = 3;
$TypeName = "Paper";
$stmt->execute();

$TypeId  = 4;
$TypeName = "Quiz";
$stmt->execute();


   
?>