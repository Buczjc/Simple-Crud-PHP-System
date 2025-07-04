<?php
include 'includes/dbconnection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CRUD</title>
    <link rel="stylesheet" href="stylesheets/style.css">
</head>

<body>
    <main>
        <div class="container">
            <h1>Simple CRUD Program</h1>
            <p>By: Bucz</p>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Hobby</th>
                    <th>Actions</th>
                </tr>
                <?php
                // Displays the database table
                $fetchAllDataQuery = 'SELECT * FROM cred_table';
                $fetchAllData = $pdo->query($fetchAllDataQuery);
                while ($row = $fetchAllData->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                    <tr>
                        <td>{$row['id']}</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['last_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['hobby']}</td>
                        <td>
                            <a href='update-data.php?id=".$row['id']."' id='editbtn'>Edit</a>
                            <a href='delete-data.php?id=".$row['id']."' id='deletebtn'>Del</a>
                        </td>
                    </tr>";
                }
                ?>
            </table>
            <div class="add_cred_container">
                <a href="insert-data.php">+</a>
            </div>
        </div>
    </main>
</body>

</html>