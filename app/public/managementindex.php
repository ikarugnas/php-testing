<html>
    <head>
        <link rel="stylesheet" href="managementstyle.css">
    </head>
    <body>
        <a href="index.php">Click here to go to the homepage!</a>
        <h1>Welcome to the management page</h1>
        
        <?php
        //connect to server & startup code

        //forcing errors to show
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        //  echo "Requested URL: " . $_SERVER['REQUEST_URI'];

        require_once("../dbconfig.php");

        try {
            $connection = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        } catch (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
        }

        try {
            function deleteButton($connection, $id) {
                echo $id;
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                if(isset($_POST['delete post'])) {
                    $idToUse = $id;
                    
                    $stmt = $connection->prepare("DELETE from posts WHERE :id = $idToUse");
    
                    $stmt->bindParam(':id', $idToUse);
    
                    $sqlQuery = "DELETE from posts WHERE id = $idToUse";
                    $connection->query($sqlQuery);
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $sql = "SELECT * FROM posts";
        $result = $connection->query($sql);

        echo nl2br("");

        ?>

        <table>
            <tr>
                <th>Id</th>
                <th>Posted At</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Ip Address</th>
            </tr>
            <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo $row['id']?></td>
                <td><?php echo $row['posted_at']?></td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['email']?></td>
                <td><?php echo $row['message']?></td>
                <td><?php echo $row['ip_address']?></td>
                <td><form method="POST" action=managementindex.php name="deletebutton" class="deleteform"><input type="submit" id="<?= $row['id'] ?>" class="dltbutton" name="delete post" value="delete post" onclick="<?php deleteButton($connection, $row['id']) ?>"/></form></td>
            </tr>
                <?php
        }
        ?>
        </table>
    </body>
</html>