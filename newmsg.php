<?php
$pageTitle = 'Ново съобщение';
include './includes/header.php';

if (isset($_SESSION['isLogged'])) {
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        if (isset($_POST['nazad'])) {
            header('Location: ./allmsg.php');
            exit;
        }
        $con = mysqli_connect('localhost', 'root', 'wdr9173zdv50', 'forum');
        if (!$con) {
            echo "Моля, презаредете страницата";
            exit;
        }
        mysqli_set_charset($con, 'utf8');
        $q = mysqli_query($con, 'SELECT * FROM users WHERE username="' . $username . '"');
        if (!$q) {
            echo "error";
            echo mysqli_error($con);
        }
        $result = $q->fetch_assoc();
        if (isset($_POST['add'])) {
            $title = trim($_POST['title']);
            $body  = $_POST['body'];
            $error = false;
            if (mb_strlen($title) < 1 || mb_strlen($title) > 50) {
                echo "<p>Заглавието трябва да съдържа поне един символ и да не е по-дълго от 50 символа!</p>";
                $error = true;
            }
            $username = mysqli_real_escape_string($con, $username);
            if (mb_strlen($body) > 250) {
                echo "<p>Съобщението не може да е по-дълго от 250 символа!</p>";
                $error = true;
            }
            $body = mysqli_real_escape_string($con, $body);
            if (!$error) {
                $query = 'INSERT INTO msg (title,body,datemsg,username,groups)
						VALUES ("' . $title . '","' . $body . '", NOW() ,"' . $result['username'] . '","' . $_POST['group'] . '")';
                if (mysqli_query($con, $query)) {
                    //session_destroy();
                    header('Location: ./allmsg.php');
                } else {
                    echo "Error";
                }
            }
        }
        $group = -1;
        if (isset($_POST['group']) && isset($groups[$_POST['group']])) {
            $group = $_POST['group'];
        }
        echo "<h2>Ново съобщение</h2>
		<form method='POST' action='./newmsg.php'>
			<table border='1'>
				<tr><td><h3>Заглавие</h3></td><td><input type='text' name='title'>
						<select name='group'>";
        foreach ($groups as $key => $value) {
            echo '<option value="' . $key . '"' . ($key == $group ? 'selected' : '') . '>' . $value . '</option>';
        }
        echo "</select>
				</td></tr>
				<tr><td>Съобщение</td><td><textarea type='text' name='body' rows='5' cols='50' ></textarea></td></tr>
				<tr><td><input type='submit' name='nazad' value='Назад'></td>
				<td><input type='submit' name='add' value='Добави'></td></tr>
			</table>
		</form>";
    }
}
if (!isset($_SESSION['isLogged']) || !isset($_SESSION['username'])) {
    echo 'Не си се логнал, върни се на страницата за <a href="./index.php">Вход</a>';
}
?>

<?php
include './includes/footer.php'
?>