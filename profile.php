<?php
session_start();
    if(!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
        die("Musisz się zalogować aby mieć dostęp do strony z profilem");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="sklep internetowy">
    <meta name="author" content="BL, KD">
    <title>Profil</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Rozwiń</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Allewro</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="index.php">Strona główna</a>
                    </li>
                    <li>
                        <a href="register.php">Rejestracja</a>
                    </li>
                    <?php
                        if(!isset($_SESSION['user_id'])) {
                            echo '<li><a href="login.php">Logowanie</a></li>';
                        }
                        else {
                            echo '<li><a href="profile.php">Profil</a></li>';
                            echo '<li><a href="logout.php">Wyloguj</a></li>';
                        }
                    ?>
                </ul>
        </div>
    </div>
</nav>

<div class="container">
<h1>Strona tylko dla zalogowanych użytkowników</h1>
<?php
        require 'connect.php';
        $sql = "SELECT id, username, name, lastname, password, email, address FROM users WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $_SESSION["user_id"]);
        $statement->execute();
        $user = $statement->fetch();
        echo '<b>ID:       </b>' . $user['id'] . '</br>';
        echo '<b>Imię:     </b>' . $user['name'] . '</br>';
        echo '<b>Nazwisko: </b>' . $user['lastname'] . '</br>';
        echo '<b>Adres:    </b>' . $user['address'] . '</br>';
        echo '<b>Email:    </b>' . $user['email'] . '</br>';
        echo '<a class="btn" href="logout.php">Wyloguj</a>'
?>
</div>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

