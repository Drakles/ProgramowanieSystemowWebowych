<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    die();
}
$errors = array();

$registrationFormRequiredFields = array(
    "username",
    "password"
);

$fields = array(
    "username" => "Login",
    "password" => "Hasło"
);

$loginData = array(
    "username" => "admin",
    "password" => "admin1"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_login_form'])) {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = escapeCharacters($_POST['username']);
            $password = escapeCharacters($_POST['password']);

            if (empty($username)) {
                $errors['username'] = "Login jest pusty";
            }

            if (empty($password)) {
                $errors['password'] = "Hasło jest puste";
            }

            if (!empty($password) && !empty($username)) {
                require 'connect.php';
                $sql = "SELECT id, username, password FROM users WHERE username = :username";
                $statement = $pdo->prepare($sql);
                $statement->bindValue(':username', $username);
                $statement->execute();
                $user = $statement->fetch();
                if (!$user) {
                    $errors['login_or_password'] = "Błędny login lub hasło";
                } else {
                    $isPasswordValid = password_verify($password, $user['password']);
                    if ($isPasswordValid) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['logged_in'] = true;
                        setcookie('last_user',$username,time()+99999999,"/");
                        header('Location: profile.php');
                        die();
                    } else {
                        $errors['login_or_password'] = "Błędny login lub hasło";
                    }
                }
            }
        } else {
            die("Żądanie jest niepoprawne");
        }

    } else {
        die("Niestety żądanie nie może zostać przetworzone");
    }
}

function escapeCharacters($in)
{
    return trim(stripslashes(htmlspecialchars($in)));
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="sklep internetowy">
    <meta name="author" content="BL, KD">
    <title>Strona główna</title>
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
                if (!isset($_SESSION['user_id'])) {
                    echo '<li><a href="login.php">Logowanie</a></li>';
                } else {
                    echo '<li><a href="profile.php">Profil</a></li>';
                    echo '<li><a href="logout.php">Wyloguj</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php
    foreach ($errors as $error) {
        if (!empty($error)) {
            echo '<div class="alert alert-danger">
  					<strong>Błąd!</strong>  ' . $error . '
					</div>';
        }
    }
    ?>
    <div class="row main">
        <div class="panel-heading">
            <div class="panel-title text-center">
                <h1 class="title">Logowanie</h1>
                <hr/>
            </div>
        </div>
        <div class="main-login main-center col-lg-offset-3 col-lg-6">
            <form action="login.php" method="POST" class="form-horizontal">
                <div class="form-group">
                    <label for="username" class="cols-sm-2 control-label">Nazwa użytkownika</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                            <input autocomplete="false"
                                    type="text" class="form-control" name="username" id="username"
                                   placeholder="Login"
                                   value="<?php
                                   echo(isset($_COOKIE['last_user']) ? $_COOKIE['last_user'] : '');
                                   ?>"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="cols-sm-2 control-label">Hasło</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input autocomplete="false" type="password" class="form-control" name="password" id="password"
                                   placeholder="Hasło"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" name="send_login_form"
                            class="btn btn-primary btn-lg btn-block login-button" id="submit">Zaloguj sie
                    </button>
                    <label for="submit"></label>
                </div>
            </form>
        </div>
    </div>
</div>
<footer>
    <div class="text-center bg-primary">
        <p>Copyright &copy; Allewro 2018</p>
    </div>
</footer>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>

