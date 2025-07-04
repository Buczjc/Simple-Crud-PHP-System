<?php
include 'includes/dbconnection.php';
//Getting the ID in the database
$id = $_GET['id'];

if (!is_numeric($id)) {
    die("Invalid ID");
}

// Fetching the Values via ID to display in input text
$fetchAllDataViaIDQuery = 'SELECT * FROM cred_table WHERE id = :id';
$fetchAllDataViaID = $pdo->prepare($fetchAllDataViaIDQuery);
$fetchAllDataViaID->execute([':id'=>$id]);
$user = $fetchAllDataViaID->fetch(PDO::FETCH_ASSOC);

// UPDATE QUERY
$UPDATED_FIRSTNAME = "";
$UPDATED_LASTNAME = "";
$UPDATED_EMAIL = "";
$UPDATED_HOBBY = "";

if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_cred_php'])) {
    $UPDATED_FIRSTNAME = $_POST['first-name-php'];
    $UPDATED_LASTNAME = $_POST['last-name-php'];
    $UPDATED_EMAIL = filter_input(INPUT_POST, 'email-php', FILTER_SANITIZE_EMAIL);
    $UPDATED_HOBBY = $_POST['hobby-php'];
    $ERR_VALID = [];
    
    if(empty($UPDATED_FIRSTNAME) || empty($UPDATED_LASTNAME) || empty($UPDATED_EMAIL) || empty($UPDATED_HOBBY)) {
        $ERR_VALID['empty_form_err_valid'] = "⚠️ The form cannot be empty ⚠️";
    }

    if ($UPDATED_FIRSTNAME === $user['first_name'] && $UPDATED_LASTNAME === $user['last_name'] && $UPDATED_EMAIL === $user['email'] && $UPDATED_HOBBY === $user['hobby']) {
        $ERR_VALID['unchanged_form_err_valid'] = "⚠️ No changes were made ⚠️";
    }

    if(!filter_var($UPDATED_EMAIL, FILTER_VALIDATE_EMAIL)) {
        $ERR_VALID['email_format_err_valid'] = "⚠️ Email is not VALID ⚠️";
    }

    // Checks if there's any other user with the same email but not the current one.
    $fetchEmaildb = 'SELECT * FROM cred_table WHERE email = :email AND id != :id';
    $stmt = $pdo->prepare($fetchEmaildb);
    $stmt->execute([
     'email' => $UPDATED_EMAIL,
     'id' => $id
    ]);

if($stmt->fetch()) {
    $ERR_VALID['email_exist_err_valid'] = '⚠️ Email already existed ⚠️';
}

    // Updates the user if the ERR_VALID array is empty
    if(empty($ERR_VALID)) {
        $updateDatadb = "UPDATE cred_table SET first_name = :updfname, last_name = :updlname, email = :updemail, hobby = :updhobby WHERE id = :id;";
        $stmt = $pdo->prepare($updateDatadb);
        $stmt->execute([
            ':updfname'=>$UPDATED_FIRSTNAME,
            ':updlname'=>$UPDATED_LASTNAME,
            ':updemail'=>$UPDATED_EMAIL,
            ':updhobby'=>$UPDATED_HOBBY,
            ':id'=>$id
        ]);
        echo '<script>alert("✅ Successfully updated the user ✅")</script>';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data</title>
    <link rel="stylesheet" href="stylesheets/style-update-data.css">
</head>
<body>
    <main>
        <div class="create-data-container">
            <h1>Update user Data</h1>
            <div class="error-msg-block">
                <!-- ERROR VALIDATION -->
                 <?php
                 if(isset($ERR_VALID['empty_form_err_valid'])) {
                    echo "<p>{$ERR_VALID['empty_form_err_valid']}</p>";
                 }elseif(isset($ERR_VALID['email_format_err_valid'])) {
                    echo "<p>{$ERR_VALID['email_format_err_valid']}</p>";
                 }elseif(isset($ERR_VALID['email_exist_err_valid'])) {
                    echo "<p>{$ERR_VALID['email_exist_err_valid']}</p>";
                 }elseif(isset($ERR_VALID['unchanged_form_err_valid'])) {
                    echo "<p>{$ERR_VALID['unchanged_form_err_valid']}</p>";
                 }
                 ?>
            </div>
            <form action="update-data.php?id=<?php echo $id; ?>" method="post">
                <div class="first-name-container">
                    <label for="first-name-label-id">First Name</label>
                    <input type="text" id="first-name-label-id" placeholder="ex: John" maxlength="50" name="first-name-php" value="<?php echo isset($UPDATED_FIRSTNAME) && $_SERVER['REQUEST_METHOD'] === 'POST' ? htmlspecialchars($UPDATED_FIRSTNAME) : htmlspecialchars($user['first_name']); ?>">
                </div>
                <div class="last-name-container">
                    <label for="last-name-label-id">Last Name</label>
                    <input type="text" id="last-name-label-id" placeholder="ex: Doe" maxlength="50" name="last-name-php" value="<?php echo isset($UPDATED_LASTNAME) && $_SERVER['REQUEST_METHOD'] === 'POST' ? htmlspecialchars($UPDATED_LASTNAME) : htmlspecialchars($user['last_name']); ?>">
                </div>
                <div class="email-container">
                    <label for="email-label-id">Email</label>
                    <input type="text" id="email-label-id" placeholder="ex: example@gmail.com" maxlength="120" name="email-php" value="<?php echo isset($UPDATED_EMAIL) && $_SERVER['REQUEST_METHOD'] === 'POST' ? htmlspecialchars($UPDATED_EMAIL) : htmlspecialchars($user['email']); ?>">
                </div>
                <div class="hobby-container">
                    <label for="hobby-label-id">Hobby &#40;1 word&#41; </label>
                    <input type="text" id="hobby-label-id" placeholder="ex: Playing" maxlength="255" name="hobby-php" value="<?php echo isset($UPDATED_HOBBY) && $_SERVER['REQUEST_METHOD'] === 'POST' ? htmlspecialchars($UPDATED_HOBBY) : htmlspecialchars($user['hobby']); ?>">
                </div>
                <div class="form-buttons">
                    <input type="submit" value="Update" name="update_cred_php">
                    <a href="index.php">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>