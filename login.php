<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Form</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginstyle.css"> 
    <style>
        #privilege {
            display: none;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Login Form</h1>

        <?php
        if (isset($_GET['error']) && $_GET['error'] === 'invalidcredentials') {
            echo '<p class="error-message">Invalid username or password.</p>';
        } elseif (isset($_GET['error']) && $_GET['error'] === 'emptyfields') {
            echo '<p class="error-message">Please fill in all fields.</p>';
        } elseif (isset($_GET['error']) && $_GET['error'] === 'success') {
            echo '<p class="success-message">Authentication successful.</p>';
        }
        ?>

        <label for="username">Username</label>
        <input type="text" placeholder="Email or Phone" id="username" name="username">
    
        <label for="password">Password</label>
        <input type="password" placeholder="Password" id="password" name="password">

        <label for="privilege" id="privilege-label"></label>
        <input type="text" placeholder="Privilege" id="privilege" name="privilege">
    
        <button type="submit" class="btn">Log In</button>
    </form>

    <script src="loginscript.js"></script> 

    <?php
        include 'connection(urms).php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ID = $_POST['username'];
            $pass = $_POST['password'];
            $privilege = $_POST['privilege'];
            $error = false;  // Initialize the error variable
            
            // Check for empty fields
            if (empty($ID) || empty($pass) || empty($privilege)) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=emptyfields");
                exit();
            }

            // Prepare SQL statement to prevent SQL injection
            switch ($privilege) {
                case 1:
                    $stmt = $conn->prepare("SELECT ID, pass FROM SystemAdmin WHERE ID = ?");
                    break;
                case 2:
                    $stmt = $conn->prepare("SELECT ID, pass FROM Teacher WHERE ID = ?");
                    break;
                case 3:
                    $stmt = $conn->prepare("SELECT ID, pass FROM Student WHERE ID = ?");
                    break;
                default:
                    // Handle other cases or show an error message
                    $error = true;
            }
            
            if (!$error) {
                $stmt->bind_param("s", $ID);
                // Execute the query
                $stmt->execute();
            
                // Store the result to check if any rows returned
                $result = $stmt->get_result();
            
                if ($row = $result->fetch_assoc()) {
                    // Directly compare the plaintext passwords
                    if (strcmp($pass, $row['pass']) === 0) {
                        // Redirect to new page with success message and user ID as query parameter
                        // Adjust redirection based on the privilege level
                        switch ($privilege) {
                            case 1:
                                header("Location: options.html?error=success&userID=" . $row['ID']);
                                break;
                            case 2:
                                header("Location: teacherprofilepage.php?error=success&userID=" . $row['ID']);
                                break;
                            case 3:
                                header("Location: studentprofilepage.php?error=success&userID=" . $row['ID']);
                                break;
                            default:
                                // Handle other cases or show an error message
                                $error = true;
                        }
                        exit();
                    } else {
                        // Set error variable for invalid credentials
                        $error = true;
                    }
                } else {
                    // Set error variable for invalid credentials
                    $error = true;
                }
            }
        
            // Close statement and connection
            $stmt->close();
            $conn->close();

            // Check if there's an error and redirect accordingly
            if ($error) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?error=invalidcredentials");
                exit();
            }
        }
    ?>
</body>
</html>
