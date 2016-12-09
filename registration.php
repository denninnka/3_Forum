<?php
mb_internal_encoding('UTF-8');
$pageTitle = 'Регистрация';
include './includes/header.php';

$connection = mysqli_connect('localhost', 'root', 'wdr9173zdv50', 'forum');
if (!$connection) {
    echo "Моля, презаредете страницата";
    exit;
}
mysqli_set_charset($connection, 'utf8');

if ($_POST) {
    $username = trim($_POST['username']);
    $pass     = trim($_POST['password']);
    $repass   = trim($_POST['repassword']);
    $error    = false;
    if (mb_strlen($username) < 5) {
        echo "<p>Името трябва да е не по-късо от 5 символа</p>";
        $error = true;
    }
    if (mb_strlen($pass) < 5) {
        echo "<p>Паролата трябва да не е по-къса от 5 символа</p>";
        $error = true;
    }
    if ($_POST['password'] != $_POST['repassword']) {
        echo 'Неправилна парола';
        $error = true;
    }
    $username = mysqli_real_escape_string($connection, $username);
    $query    = 'SELECT username FROM users WHERE username="' . $username . '"';
    if ($result = mysqli_query($connection, $query)) {
        $rowcount = mysqli_num_rows($result);
        if ($rowcount != 0) {
            echo "Съществува такова име";
            $error = true;
        }
    }
    $pass = mysqli_real_escape_string($connection, $pass);
    if (!$error) {
        $q = 'INSERT INTO users (username, password) VALUES ("' . $username . '", "' . $pass . '")';
        if (mysqli_query($connection, $q)) {
            header('Location: ./index.php');
            exit;
        } else {
            echo "Error";
            echo mysqli_error($connection);
        }
    }
}
if (isset($_POST['nazad'])) {
    header('Location: ./index.php');
    exit;
}
?>

<h2>Моля, регистрирайте се във форума!</h2>
<form method="POST">
	<table border="0">
	<tr><td><div>Потребителско име</td><td><input type="text" name="username"></div></td></tr>
	<tr><td><div>Парола</td><td><input type="text" name="password"></div></td></tr>
	<tr><td><div>Повторете паролата</td><td><input type="text" name="repassword"></div></td></tr>
	<tr><td><div><input type="submit" name="newreg" value="Регистрирай се"></div></td>
	<td><div><input type="submit" name="nazad" value="Върни се назад"></div></td>
	</table>
</form>

<?php
include './includes/footer.php'
?>