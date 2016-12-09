<?php
$pageTitle = 'Форум';
include './includes/header.php';

if (isset($_POST['reg'])) {
    header('Location: ./registration.php');
    exit;
}
$con = mysqli_connect('localhost', 'root', 'wdr9173zdv50', 'forum');
if (!$con) {
    echo "Моля презаредете страницата";
    exit;
}
mysqli_set_charset($con, 'utf8');

if (isset($_POST['vhod'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);
    $q        = 'SELECT username, password, admin
				FROM users
				WHERE username="' . $username . '" AND password="' . $password . '"';
    if ($result = mysqli_query($con, $q)) {
        $res      = $result->fetch_assoc();
        $rowcount = mysqli_num_rows($result);
        if ($rowcount != 0) {
            $_SESSION['isLogged'] = true;
            $_SESSION['username'] = $username;

            if ($res['admin'] == "1") {
                $_SESSION['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
            }
            header('Location: ./allmsg.php');
            exit;
        } else {
            $_SESSION['isLogged'] = false;
            echo "Грешно потребителско име или парола";
        }
    }
}
?>

<h2>Здравейте, влезте във форума!</h2>
<form method="POST">
	<table border="0">
	<tr><td><div>Потребителско име</td><td><input type="text" name="username"></div></td></tr>
	<tr><td><div>Парола</td><td><input type="text" name="password"></div></td></tr>
	<tr><td><div><input type="submit" name="reg" value="Регистрация"></div></td>
	<td><div><input type="submit" name="vhod" value="Влез"></div></td></tr>
	</table>
</form>

<?php
include './includes/footer.php'
?>