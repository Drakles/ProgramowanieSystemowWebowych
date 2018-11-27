<?php
$registrationFormRequiredFields = array(
    "name",
    "lastname",
    "birth_day",
    "address",
    "voivodeship",
    "gender",
    "email",
    "username",
    "password",
    "password2",
);

$registrationFormAllFields = array_merge(
    array("accept_rules", "accept_mails", "accept_personal_data"), $registrationFormRequiredFields
);

$fields = array(
    "name" => "Imię",
    "lastname" => "Nazwisko",
    "birth_day" => "Data urodzenia",
    "address" => "Adres",
    "voivodeship" => "Województwo",
    "gender" => "Płeć",
    "email" => "E-mail",
    "username" => "Nazwa użytkownika",
    "password" => "Hasło",
    "password2" => "Powtórzone hasło",
    "accept_rules" => "Akceptacja regulaminu"
);


define("PASSWORD_MIN_LENGTH", 6);
$errors = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send_registration_form'])) {
        for ($i = 0; $i < count($registrationFormRequiredFields); $i++) {
            $fieldName = $registrationFormRequiredFields[$i];
            if (!isset($_POST[$fieldName])) {
                die("Nieprawidłowe żądanie");
            }
        }

        foreach ($registrationFormAllFields as $fieldName) {
            $errors[$fieldName] = "";
        }

        foreach ($registrationFormRequiredFields as $fieldName) {
            $data = escapeCharacters($_POST[$fieldName]);

            if (empty($data)) {
                $name = $fields[$fieldName];
                $errors[$fieldName] = "Pole " . $name . " nie może być puste";
            }
        }

        if ($_POST["password"] !== $_POST["password2"]) {
            $errors["password"] = 'Podane hasła nie są takie same';
        }
        if (strlen($_POST["password2"]) <= PASSWORD_MIN_LENGTH) {
            $errors["password2"] = 'Hasło musi miec więcej niż 6 znaków';
        }

        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['name'])) {
            $errors['name'] = 'Imię może zawierać tylko litery, cyfry i białe znaki';
        }

        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['name'])) {
            $errors['username'] = 'Nazwa użytkownika może zawierać tylko litery, cyfry i białe znaki';
        }

        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['lastname'])) {
            $errors['lastname'] = 'Nazwisko może zawierać tylko litery, cyfry i białe znaki';
        }

        if (!preg_match('/^[a-zA-Z0-9\s]+$/', $_POST['address'])) {
            $errors['address'] = 'Adres może zawierać tylko litery, cyfry i białe znaki';
        }

        if (!(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data))) {
            $errors['birth_date'] = 'Data musi mieć format YYYY-MM-DD';
        }

        if (!(preg_match("/^[MK]{1}$/", $errors['gender']))) {
            $errors['gender'] = 'Płeć to Mężczyzna lub Kobieta';
        }

        if (!(preg_match("'/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'", $errors['email']))) {
            $errors['email'] = 'Email ma nieprawidłowy format';
        }
    } else {
        die("Nieprawidłowe parametry żądania");
    }
}

function escapeCharacters($in)
{
    return trim(stripslashes(htmlspecialchars($in)));
}

function getIpAddress()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Passion+One' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
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
                    <a href="index.html">Strona główna</a>
                </li>
                <li>
                    <a href="register.php">Rejestracja</a>
                </li>
                <li>
                    <a href="login.html">Logowanie</a>
                </li>
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
                <h1 class="title">Tworzenie nowego konta</h1>
                <hr/>
            </div>
        </div>
        <div class="main-login main-center col-lg-offset-3 col-lg-6">
            <form action="register.php" method="POST" class="form-horizontal">
                <div>
                    Twoj adres ip: <?php echo getIpAddress(); ?>
                </div>
                <div class="form-group">
                    <label for="name" class="cols-sm-2 control-label">Imię</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Wpisz imię"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="lastname" class="cols-sm-2 control-label">Nazwisko</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="lastname" id="lastname"
                                   placeholder="Wpisz nazwisko"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="birth_day" class="cols-sm-2 control-label">Data urodzenia</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="birth_day" id="birth_day"
                                   placeholder="YYYY-MM-DD"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="cols-sm-2 control-label">Adres</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-home fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="address" id="address"
                                   placeholder="Wpisz adres odbioru przesyłek"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="voivodeship" class="cols-sm-2 control-label">Województwo</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <select class="form-control" name='voivodeship' id="voivodeship">
                                <option>Dolnośląskie</option>
                                <option>Kujawsko-pomorskie</option>
                                <option>Lubelskie</option>
                                <option>Łódzkie</option>
                                <option>Małopolskie</option>
                                <option>Mazowieckie</option>
                                <option>Opolskie</option>
                                <option>Podkarpackie</option>
                                <option>Podlaskie</option>
                                <option>Pomorskie</option>
                                <option>Śląskie</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender" class="cols-sm-2 control-label">Płeć</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="M" class="required" checked title="*">
                                Mężczyzna
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="F" class="required" title="*">
                                Kobieta
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="cols-sm-2 control-label">
                        Wielkosc miejscowosci
                    </label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <label class="radio-inline">
                                <input id="village" type="radio" name="city-size[]" value="wieś"/>
                                Wieś
                            </label>
                            <br/>
                            <label class="radio-inline">
                                <input id="small-city" type="radio" name="city-size[]" value="małe miasto"/>
                                Małe miasto
                            </label>
                            <br/>
                            <label class="radio-inline">
                                <input id="medium-size-city" type="radio" name="city-size[]" value="średnie miasto"/>
                                Średnie miasto
                            </label>
                            <br/>
                            <label class="radio-inline">
                                <input id="big-city" type="radio" name="city-size[]" value="Duże miasto"/>
                                Duże miasto
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="cols-sm-2 control-label">E-mail</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Wpisz e-mail"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username" class="cols-sm-2 control-label">Nazwa użytkownika</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                            <input type="text" class="form-control" name="username" id="username"
                                   placeholder="Wpisz nazwę użytkownika"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="cols-sm-2 control-label">Hasło</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password" id="password"
                                   placeholder="Wpisz hasło"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password2" class="cols-sm-2 control-label">Powtórz hasło</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                            <input type="password" class="form-control" name="password2" id="password2"
                                   placeholder="Powtórz hasło"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm" class="cols-sm-2 control-label">Regulamin i oferty marketingowe</label>
                    <div class="cols-sm-10">
                        <div class="input-group">
                            <div class="checkbox">
                                <label><input type="checkbox" name="accept_rules" value="">Akceptuję <a href="#">regulamin</a></label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="accept_mails" value="">Zgadzam się na otrzymywanie
                                    maili z ofertami marketingowymi</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="accept_personal_data" value="">Zgadzam się na
                                    przetwarzanie moich danych osobowych w celach marketingowych</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" name="send_registration_form"
                            class="btn btn-primary btn-lg btn-block login-button">Stwórz konto
                    </button>
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
