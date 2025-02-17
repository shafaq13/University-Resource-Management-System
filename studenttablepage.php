<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Table</title>
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
    if (isset($_GET['delete'])) {
        $id = $mysqli->real_escape_string($_GET['delete']);
        $query = "DELETE FROM Student WHERE ID = '$id'";
        if ($mysqli->query($query) === TRUE) {
            header("Location: studenttablepage.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    if (isset($_GET['update'])) {
        $id = $mysqli->real_escape_string($_GET['update']);
        $query = "SELECT * FROM Student WHERE ID = '$id'";
        $result = $mysqli->query($query);
        $row = $result->fetch_assoc();
    
        
        if ($row) {
            ?>
            <form action="studentupdatehandler.php" method="post">
                <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                <input type="text" name="Student_Name" value="<?php echo $row['Student_Name']; ?>">
                <input type="password" name="pass" value="<?php echo $row['pass']; ?>">
                <input type="text" name="Student_email" value="<?php echo $row['Student_email']; ?>">
                <input type="text" name="Student_phone" value="<?php echo $row['Student_phone']; ?>">
                <input type="text" name="Student_joining_date" value="<?php echo $row['Student_joining_date']; ?>">
                <input type="submit" value="Update">
            </form>
            <?php
        }
    }

    // Handling the insertion of a new row
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $newID = $mysqli->real_escape_string($_POST['newID']);
        $newName = $mysqli->real_escape_string($_POST['newName']);
        $newPass = $_POST['newPass'];
        $newemail = $mysqli->real_escape_string($_POST['newemail']);
        $newphone = $mysqli->real_escape_string($_POST['newphone']);
        $newdate = $mysqli->real_escape_string($_POST['newdate']);
        $query = "INSERT INTO Student (ID, Student_Name, pass, Student_email, Student_phone, Student_joining_date) VALUES ('$newID', '$newName', '$newPass', '$newemail', '$newphone', $newdate)";
        if ($mysqli->query($query) === TRUE) {
            header("Location: studenttablepage.php");
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $id = $mysqli->real_escape_string($_POST['ID']);
        $name = $mysqli->real_escape_string($_POST['Student_Name']);
        $pass = $mysqli->real_escape_string($_POST['pass']);
        $email = $mysqli->real_escape_string($_POST['Student_email']);
        $phone = $mysqli->real_escape_string($_POST['Student_phone']);
        $date = $mysqli->real_escape_string($_POST['Student_joining_date']);
        $query = "UPDATE Student SET Student_Name = '$name' WHERE ID = '$id'";
        $query = "UPDATE Student SET pass = '$pass' WHERE ID = '$id'";
        $query = "UPDATE Student SET Student_email = '$email' WHERE ID = '$id'";
        $query = "UPDATE Student SET Student_phone = '$phone' WHERE ID = '$id'";
        $query = "UPDATE Student SET Student_joining_date = '$date' WHERE ID = '$id'";
    
        if ($mysqli->query($query) === TRUE) {
            header("Location: studenttablepage.php");
            exit();
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    }
    

$query = "SELECT * FROM Student order by ID";
$result = $mysqli->query($query);
?>

<!-- Table Display -->
<div class="table-wrapper">
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Password</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Joining Date</th>
        <th>Options</th>
    </tr>
    <?php 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <span class="static-text id"><?php echo htmlspecialchars($row['ID']); ?></span>
                    <input type="text" class="edit-field id" value="<?php echo htmlspecialchars($row['ID']); ?>" style="display: none;" />
                </td>
                <td class="name-cell">
                    <span class="static-text name"><?php echo htmlspecialchars($row['Student_Name']); ?></span>
                    <input type="text" class="edit-field name" value="<?php echo htmlspecialchars($row['Student_Name']); ?>" style="display: none;" />
                </td>
                <td class="pass-cell">
                    <span class="static-text pass"><?php echo htmlspecialchars($row['pass']); ?></span>
                    <input type="text" class="edit-field pass" value="<?php echo htmlspecialchars($row['pass']); ?>" style="display: none;" />
                </td>
                <td class="email-cell">
                    <span class="static-text email"><?php echo htmlspecialchars($row['Student_email']); ?></span>
                    <input type="text" class="edit-field email" value="<?php echo htmlspecialchars($row['Student_email']); ?>" style="display: none;" />
                </td>
                <td class="phone-cell">
                    <span class="static-text phone"><?php echo htmlspecialchars($row['Student_phone']); ?></span>
                    <input type="text" class="edit-field phone" value="<?php echo htmlspecialchars($row['Student_phone']); ?>" style="display: none;" />
                </td>
                <td class="date-cell">
                    <span class="static-text date"><?php echo htmlspecialchars($row['Student_joining_date']); ?></span>
                    <input type="text" class="edit-field date" value="<?php echo htmlspecialchars($row['Student_joining_date']); ?>" style="display: none;" />
                </td>
                <td>
                    <a href="studentUpdateForm.php?ID=<?php echo $row['ID']; ?>" class="edit-link">Edit</a>
                    <a href="?delete=<?php echo $row['ID']; ?>" class="delete-button" onclick="return confirm('Do you really want to delete?');"><i class="fas fa-times"></i></a>

                </td>
            </tr>
        <?php endwhile;
    } ?>
    <tr>
        <form method="post">
            <td><input type="text" name="newID"></td>
            <td><input type="text" name="newName"></td>
            <td><input type="password" name="newPass"></td>
            <td><input type="text" name="newemail"></td>
            <td><input type="text" name="newphone"></td>
            <td><input type="text" name="newdate"></td>
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