<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Table</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styletable.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>

<?php 
include 'connection(urms).php'; 
$mysqli = new mysqli($servername, $username, $password, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handling the deletion of a row
if (isset($_GET['delete'])) 
{
    $id = $mysqli->real_escape_string($_GET['delete']);
    $query = "DELETE FROM Course WHERE Course_ID = '$id'";
    if ($mysqli->query($query) === TRUE) {
        header("Location: coursetablepage.php");
        exit();
    } else {
        echo "Error: " . $mysqli->error;
    }
}

if (isset($_GET['update'])) 
{
    $id = $mysqli->real_escape_string($_GET['update']);
    $query = "SELECT * FROM Course WHERE Course_ID = '$id'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    if ($row) { ?>
        <form action="courseUpdateForm.php" method="post">
            <input type="hidden" name="ID" value="<?php echo $row['Course_ID']; ?>">
            <input type="hidden" name="table" value="Course">
            <input type="text" name="Course_Name" value="<?php echo $row['Course_Name']; ?>">
            <input type="number" name="sem" value="<?php echo $row['Course_sem']; ?>">
            <input type="submit" value="Update">
        </form>
    <?php }
}


// Handling the insertion of a new row
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $newcourseID = $mysqli->real_escape_string($_POST['newcourseID']);
    $newcourseName = $mysqli->real_escape_string($_POST['newcourseName']);
   // $newsem = $_POST['newsem'];
    $newsem = $mysqli->real_escape_string($_POST['newsem']);
    

    $query = "INSERT INTO Course (Course_ID, Course_Name, Course_sem) VALUES ('$newcourseID', '$newcourseName', '$newsem')";
    if ($mysqli->query($query) === TRUE) {
        header("Location: coursetablepage.php");
        exit();
    } else {
        echo "Error: " . $mysqli->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $mysqli->real_escape_string($_POST['Course_ID']);
    $name = $mysqli->real_escape_string($_POST['Course_Name']);
    $newsem = $_POST['Course_sem'];
    //$sem = $mysqli->real_escape_string($_POST['Course_Name']);

    $stmt = $mysqli->prepare("UPDATE Course SET Course_Name = ?, Course_sem = ? WHERE Course_ID = ?");
    $stmt->bind_param("sis", $name, $newsem, $id);

    if ($stmt->execute()) {
        header("Location: coursetablepage.php");
        exit();
    } else {
        echo "Error updating record: " . $mysqli->error;
    }

    $stmt->close();
}

$query = "SELECT * FROM Course order by Course_sem";
$result = $mysqli->query($query);
?>

<!-- Table Display -->
<div class="table-wrapper">
<table>
    <tr>
        <th>ID</th>
        <th>Course_Name</th>
        <th>Semester</th>
        <th>Options</th>
    </tr>
    <?php 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($row['Course_ID']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['Course_Name']); ?>
                </td>
                <td>
                    <?php echo htmlspecialchars($row['Course_sem']); ?>
                </td>
                <td>
                    <a href="courseUpdateForm.php?ID=<?php echo $row['Course_ID']; ?>" class="edit-link">Edit</a>
                    <a href="?delete=<?php echo $row['Course_ID']; ?>" class="delete-button" onclick="return confirm('Do you really want to delete?');"><i class="fas fa-times"></i></a>
                </td>
            </tr>
        <?php endwhile;
    } ?>
    <tr>
        <form method="post">
            <input type="hidden" name="action" value="insert">
            <td><input type="text" name="newcourseID"></td>
            <td><input type="text" name="newcourseName"></td>
            <td><input type="number" name="newsem"></td>
            <td><button type="submit">+</button></td>
        </form>

    </tr>
</table>
</div>
<?php
$mysqli->close();
?>

</body>
</html>
