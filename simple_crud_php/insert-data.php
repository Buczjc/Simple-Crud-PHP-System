<?php
include 'includes/dbconnection.php';

$POST_FIRSTNAME = "";
$POST_LASTNAME = "";
$POST_EMAIL = "";
$POST_HOBBY = "";

if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['submit_cred_php'])) {
    $POST_FIRSTNAME = $_POST['first-name-php'];
    $POST_LASTNAME = $_POST['last-name-php'];
    $POST_EMAIL = filter_input(INPUT_POST, 'email-php', FILTER_SANITIZE_EMAIL);
    $POST_HOBBY = $_POST['hobby-php'];
    $ERR_VALID = [];

    // Checks if one the input field is empty
    if(empty($POST_FIRSTNAME) || empty($POST_LASTNAME) || empty($POST_EMAIL) || empty($POST_HOBBY)) {
        $ERR_VALID['empty_form_err_valid'] = "⚠️ The form cannot be empty ⚠️";
    }

    // Checks if the email format is invalid
    if(!filter_var($POST_EMAIL, FILTER_VALIDATE_EMAIL)) {
        $ERR_VALID['email_format_err_valid'] = "⚠️ Email is not VALID ⚠️";
    }
    
    // Checks if the email is already exist in the database
    $fetchEmaildb = 'SELECT * FROM cred_table WHERE email = :email';
    $stmt = $pdo->prepare($fetchEmaildb);
    $stmt->execute(['email'=>$POST_EMAIL]);
    if($stmt->fetch()) {
        $ERR_VALID['email_exist_err_valid'] = '⚠️ Email already existed ⚠️';
    }

    // If the $ERR_VALID array doesnt have a value inside then it will continue the insert execution
    if(empty($ERR_VALID)) {
        $insertDatadb = "INSERT INTO cred_table (first_name, last_name, email, hobby) VALUES (:prpfirstname, :prplastname, :prpemail, :prphobby)";
        $stmt = $pdo->prepare($insertDatadb);
        $stmt->execute([
            'prpfirstname'=>$POST_FIRSTNAME,
            'prplastname'=>$POST_LASTNAME,
            'prpemail'=>$POST_EMAIL,
            'prphobby'=>$POST_HOBBY
        ]);
        echo '<script>alert("✅ Successfully created a new user ✅")</script>';
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data</title>
    <link rel="stylesheet" href="stylesheets/style-insert-data.css">
</head>
<body>
    <main>
        <div class="create-data-container">
            <h1>Create A New User</h1>
            <div class="error-msg-block">
                <!-- ERROR VALIDATION -->
                 <?php
                 if(isset($ERR_VALID['empty_form_err_valid'])) {
                    echo "<p>{$ERR_VALID['empty_form_err_valid']}</p>";
                 }elseif(isset($ERR_VALID['email_format_err_valid'])) {
                    echo "<p>{$ERR_VALID['email_format_err_valid']}</p>";
                 }elseif(isset($ERR_VALID['email_exist_err_valid'])) {
                    echo "<p>{$ERR_VALID['email_exist_err_valid']}</p>";
                 }
                 ?>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="first-name-container">
                    <label for="first-name-label-id">First Name</label>
                    <input type="text" id="first-name-label-id" placeholder="ex: John" maxlength="50" name="first-name-php" value=<?php echo $POST_FIRSTNAME?>>
                </div>
                <div class="last-name-container">
                    <label for="last-name-label-id">Last Name</label>
                    <input type="text" id="last-name-label-id" placeholder="ex: Doe" maxlength="50" name="last-name-php" value=<?php echo $POST_LASTNAME?>>
                </div>
                <div class="email-container">
                    <label for="email-label-id">Email</label>
                    <input type="text" id="email-label-id" placeholder="ex: example@gmail.com" maxlength="120" name="email-php" value=<?php echo $POST_EMAIL?>>
                </div>
                <div class="hobby-container">
                    <label for="hobby-label-id">Hobby &#40;1 word&#41; </label>
                    <input type="text" id="hobby-label-id" placeholder="ex: Playing" maxlength="255" name="hobby-php" value=<?php echo $POST_HOBBY?>>
                </div>
                <div class="form-buttons">
                    <input type="submit" value="Create" name="submit_cred_php">
                    <a href="index.php">Cancel</a>
                </div>
            </form>
        </div>
        
    </main>
</body>
</html>