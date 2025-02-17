<?php
    include 'connection(URMS).php';

    $stmt = $conn->prepare("INSERT INTO student(ID, Student_Name, pass, Student_email, Student_phone, Student_joining_date) VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssss", $ID, $Student_Name, $pass, $Student_email, $Student_phone, $Student_joining_date);

    $students = array(
        array("K205755", "Hina Ashraf", "Pass_5773", "k205755@nu.edu.pk", "0339-4081731", "2020-08-29"),
        array("K203725", "Saba Qamar", "Pass_1231", "k203725@nu.edu.pk", "0332-9744163", "2020-08-21"),
        array("K206197", "Umar Farooq", "Pass_7580", "k206197@nu.edu.pk", "0305-2520674", "2020-08-31"),
        array("K212268", "Waqas Ali", "Pass_1608", "k212268@nu.edu.pk", "0305-0482937", "2021-08-05"),
        array("K203539", "Ali Ahmed", "Pass_2874", "k203539@nu.edu.pk", "0335-7567627", "2020-08-18"),
        array("K212601", "Hira Tariq", "Pass_9909", "k212601@nu.edu.pk", "0308-8949294", "2021-08-16"),
        array("K212248", "Nida Ali", "Pass_1386", "k212248@nu.edu.pk", "0311-6952961", "2021-08-21"),
        array("K239878", "Asad Ullah", "Pass_6497", "k239878@nu.edu.pk", "0310-4524616", "2023-08-19"),
        array("K212237", "Haris Khan", "Pass_7094", "k212237@nu.edu.pk", "0310-9193304", "2021-08-26"),
        array("K228583", "Ayesha Khan", "Pass_5898", "k228583@nu.edu.pk", "0311-9757482", "2022-08-26"),
        array("K222752", "Maryam Naseer", "Pass_7015", "k222752@nu.edu.pk", "0344-5473071", "2022-08-07"),
        array("K223992", "Usman Malik", "Pass_6630", "k223992@nu.edu.pk", "0300-9938992", "2022-08-30"),
        array("K235982", "Junaid Akram", "Pass_2896", "k235982@nu.edu.pk", "0341-2356943", "2023-08-20"),
        array("K204012", "Zara Aslam", "Pass_7603", "k204012@nu.edu.pk", "0329-5101058", "2020-08-12"),
        array("K236021", "Bilal Yousaf", "Pass_5458", "k236021@nu.edu.pk", "0327-8152050", "2023-08-07"),
        array("K201316", "Amna Riaz", "Pass_4693", "k201316@nu.edu.pk", "0317-8220742", "2020-08-17"),
        array("K228543", "Fahad Sheikh", "Pass_5770", "k228543@nu.edu.pk", "0308-9919573", "2022-08-28"),
        array("K229585", "Sara Iqbal", "Pass_3753", "k229585@nu.edu.pk", "0341-0801042", "2022-08-18"),
        array("K238947", "Fatima Hussain", "Pass_6715", "k238947@nu.edu.pk", "0331-8504440", "2023-08-06")
    );

    foreach ($students as $student) {
        list($ID, $Student_Name, $pass, $Student_email, $Student_phone, $Student_joining_date) = $student;

        $stmt->execute();
    }

    echo "Records inserted";
?>