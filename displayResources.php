<?php
include 'connection(URMS).php';

echo "<h2>Course Resources</h2>";

$sql = "SELECT * FROM Recourse";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $type = $row['TypeId'];
        $courseID = $row['Course_ID'];
        $link = $row['Link'];

        echo "<p><a href='$link' target='_blank'>Resource for Course $courseID, Type $type</a></p>";
    }
} else {
    echo "No resources found.";
}

mysqli_close($conn);
?>
