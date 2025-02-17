<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Table</title>
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
    $query = "DELETE FROM SystemAdmin WHERE ID = '$id'";
    if ($mysqli->query($query) === TRUE) {
        header("Location: admintablepage.php");
        exit();
    } else {
        echo "Error: " . $mysqli->error;
    }
}

if (isset($_GET['update'])) 
{
    $id = $mysqli->real_escape_string($_GET['update']);
    $query = "SELECT * FROM SystemAdmin WHERE ID = '$id'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();

    if ($row) { ?>
        <form action="adminUpdateForm.php" method="post">
            <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
            <input type="hidden" name="table" value="SystemAdmin">
            <input type="text" name="Admin_Name" value="<?php echo $row['Admin_Name']; ?>">
            <input type="password" name="pass" value="<?php echo $row['pass']; ?>">
            <input type="submit" value="Update">
        </form>
    <?php }
}


// Handling the insertion of a new row
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $newAdminID = $mysqli->real_escape_string($_POST['newAdminID']);
    $newAdminName = $mysqli->real_escape_string($_POST['newAdminName']);
    $newAdminPass = $_POST['newAdminPass'];

    $query = "INSERT INTO SystemAdmin (ID, Admin_Name, pass) VALUES ('$newAdminID', '$newAdminName', '$newAdminPass')";
    if ($mysqli->query($query) === TRUE) {
        header("Location: admintablepage.php");
        exit();
    } else {
        echo "Error: " . $mysqli->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $mysqli->real_escape_string($_POST['ID']);
    $name = $mysqli->real_escape_string($_POST['Admin_Name']);
    $pass = $mysqli->real_escape_string($_POST['pass']);

    $stmt = $mysqli->prepare("UPDATE SystemAdmin SET Admin_Name = ?, pass = ? WHERE ID = ?");
    $stmt->bind_param("sss", $name, $pass, $id);

    if ($stmt->execute()) {
        header("Location: admintablepage.php");
        exit();
    } else {
        echo "Error updating record: " . $mysqli->error;
    }

    $stmt->close();
}

// Fetching all rows from the admin table
$query = "SELECT * FROM SystemAdmin order by ID";
$result = $mysqli->query($query);
?>

<!-- Table Display -->
<div class="table-wrapper">
<table>
    <tr>
        <th>ID</th>
        <th>Admin_Name</th>
        <th>Password</th>
        <th>Options</th>
    </tr>
    <?php 
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <span class="static-text id"><?php echo htmlspecialchars($row['ID']); ?></span>
                    <input type="text" class="edit-field name" value="<?php echo htmlspecialchars($row['ID']); ?>" style="display: none;" />
                </td>
                <td class="name-cell">
                    <span class="static-text name"><?php echo htmlspecialchars($row['Admin_Name']); ?></span>
                    <input type="text" class="edit-field name" value="<?php echo htmlspecialchars($row['Admin_Name']); ?>" style="display: none;" />
                </td>
                <td class="pass-cell">
                    <span class="static-text pass"><?php echo htmlspecialchars($row['pass']); ?></span>
                    <input type="text" class="edit-field pass" value="<?php echo htmlspecialchars($row['pass']); ?>" style="display: none;" />
                </td>
                <td>
                    <a href="adminUpdateForm.php?ID=<?php echo $row['ID']; ?>" class="edit-link">Edit</a>
                    <a href="?delete=<?php echo $row['ID']; ?>" class="delete-button" onclick="return confirm('Do you really want to delete?');"><i class="fas fa-times"></i></a>
                </td>
            </tr>
        <?php endwhile;
    } ?>
    <tr>
        <form method="post">
            <input type="hidden" name="action" value="insert">
            <td><input type="text" name="newAdminID"></td>
            <td><input type="text" name="newAdminName"></td>
            <td><input type="password" name="newAdminPass"></td>
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
