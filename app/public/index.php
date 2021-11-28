<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <a href="managementindex.php">Click here to go to the management page!</a>
        <h1>Write something in our guestbook!</h1>

        <form method="POST">
            <div class="form-field">
                <label>Name: </label>   
                <input type="text" name="name" />
            </div>
            <div class="form-field">
                <label>Message: </label>
                <textarea name="message"></textarea>
            </div>
            <div class="form-field">
                <label>&nbsp;</label>
                <input type="submit" value="Send" />
            </div>
        </form>

        
        <?php
        //connect to server & startup code

        //forcing errors to show
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        //Shows the requested url
        //  echo "Requested URL: " . $_SERVER['REQUEST_URI'];

        require_once("../dbconfig.php");

        try {
            $connection = new PDO("mysql:host=$servername;dbname=$databasename", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
        } catch (PDOException $e) {
            //echo "Connection failed: " . $e->getMessage();
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        if(isset($_POST["name"]) && isset($_POST["message"])) {
            $stmt = $connection->prepare("INSERT INTO posts
                                        (name, message, posted_at, ip_address)
                                        VALUES
                                        (:name, :message, now(), :ip_address)");

            $stmt->bindParam(':name', $_POST["name"]);
            $stmt->bindParam(':message', $_POST["message"]);
            $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR']);

            $stmt->execute();
        }
        ?>


        <?php
        $sql = "SELECT * FROM posts";
        $result = $connection->query($sql);

        echo nl2br("");

        foreach ($result as $row) {
            ?>

        <div class="message">
            <h2><?php echo $row['name']?></h2>
            <article><?php echo $row['message']?></article>
            <p class="postedat">posted at: <?php echo $row['posted_at']?></p>
        </div>
            <?php
        }
            //echo nl2br("\n" . $row['id']);
            //echo nl2br("\n" . $row['name']);
            //echo nl2br("\n" . $row['message']);
            //echo nl2br("\n" . $row['ip_address']);
            //echo nl2br("\n" . $row['posted_at'] . "\n");
        //phpinfo();
        ?>
    </body>
</html>