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

// Delete User's Record
if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['delete_cred_php'])) {
    $DeleteUserQuery = 'DELETE FROM cred_table WHERE id = :id';
    $deleteRecord = $pdo->prepare($DeleteUserQuery);
    $deleteRecord->execute([':id'=>$id]);
    echo "<script>alert('✅ Record Successfully Deleted ✅'); window.location.href = 'index.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Page</title>
    <link rel="stylesheet" href="stylesheets/style-delete-data.css">
</head>
<body>
    <main>
        <div class="container">
            <h1><span>&#9888;</span> Delete Confirmation <span>&#9888;</span></h1>
            <p>Are you sure you want to delete this data&#63;</p>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Hobby</th>
                </tr>
                <tr>
                    <td><?php echo $user['id'] ?></td>
                    <td><?php echo $user['first_name'] ?></td>
                    <td><?php echo $user['last_name'] ?></td>
                    <td><?php echo $user['email'] ?></td>
                    <td><?php echo $user['hobby'] ?></td>
                </tr>
            </table>
            <form action="delete-data.php?id=<?php echo $id; ?>" method="post">
                <div class="form-buttons">
                    <input type="submit" value="Delete" name="delete_cred_php">
                    <a href="index.php">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>