<?php
$pageTitle = 'Съобщения';
include dirname(__FILE__).'/includes/header.php';

if (isset($_SESSION['isLogged']) && isset($_SESSION['username'])) {
    $con = mysqli_connect('localhost', 'root', 'wdr9173zdv50', 'forum');
    if (!$con) {
        echo "Моля, презаредете страницата";
        exit;
    }
    mysqli_set_charset($con, 'utf8');
    $orderWay = -1;
    if (isset($_GET['orderWay']) && isset($ordering_ways[$_GET['orderWay']])) {
        $orderWay = $_GET['orderWay'];
    }
    $orderBy = -1;
    if (isset($_GET['orderBy']) && isset($columns[$_GET['orderBy']])) {
        $orderBy = $_GET['orderBy'];
    }
    $filterBygroup = -1;
    if (isset($_GET['filterBygroup']) && isset($groups[$_GET['filterBygroup']])) {
        $filterBygroup = $_GET['filterBygroup'];
    }
    echo "<form method='POST'>
			<input type='submit' name='newmsg' value='Ново съобщение'>
		</form>
    	<form method='GET'>
			<input type='submit' value='Сортиране'>
			<select name='orderWay'>
				<option value='-1'>Без сортиране</option>";
    foreach ($ordering_ways as $key => $value) {
        echo '<option value="' . $key . '" ' . ($key == $orderWay ? 'selected' : '') . '>' . $value . '</option>';
    }
    echo "</select>
		<select name='orderBy'>
			<option value='-1'>Без сортиране</option>";
    foreach ($columns as $key => $value) {
        echo '<option value="' . $key . '" ' . ($key == $orderBy ? 'selected' : '') . '>' . $value . '</option>';
    }
    echo "</select>
		</form>
		<form method='GET'>
		<input type='submit' value='Филтрирай'>
		<select name='filterBygroup'>
		<option value='-1'>Вички групи</option>";
    foreach ($groups as $key => $value) {
        echo '<option value="' . $key . '"' . ($key == $filterBygroup ? 'selected' : '') . '>' . $value . '</option>';
    }
    echo "</select>
		</form>";
    if (isset($_POST['newmsg'])) {
        header('Location: newmsg.php');
        exit;
    }
    if ($_SESSION['admin'] && isset($_POST['delete'])) {       
            $del = mysqli_query($con, 'DELETE FROM msg WHERE msg_id in ('.implode(',', $_POST['delete']).')');
    }
    if (isset($_POST['exit'])) {
        session_destroy();
        header('Location: index.php');
        exit;
    }
    if ($filterBygroup != -1) {
        $q = mysqli_query($con, 'SELECT * FROM msg WHERE groups=' . $filterBygroup . ' ORDER BY ' . $orderBy . ' ' . $orderWay);
        if (!$q) {
            echo "Грешка";
            echo mysqli_error($con);
        }
    } else {
        $q = mysqli_query($con, 'SELECT * FROM msg ORDER BY ' . $orderBy . ' ' . $orderWay);
    }
    echo "<form method='POST'>
			<table border='1' width='1100'>
				<tr><td width='150'>Дата</td>
				<td width='150'>Потребителско име</td>
				<td width='200'>Заглавие</td>
				<td width='400'>Съобщение</td>
				<td width='150'>Група</td>";
    if ($_SESSION['admin']) {
        echo "<td width='50'><input type='submit' name='delete' value='Изтрий'></td></tr>";
    }
    while ($row = $q->fetch_assoc()) {
        echo "<tr><td width='150'>" . $row['datemsg'] . "</td>
				<td width='150'>" . $row['username'] . "</td>
				<td width='200'>" . $row['title'] . "</td>
				<td width='400'>" . $row['body'] . "</td>
				<td width='150'>" . $groups[$row['groups']] . "</td>";
        // var_dump((int)$row['groups'],$groups);
        if ($_SESSION['admin']) {
            echo "<td width='50'><input type='checkbox' name='delete[]' value=" . $row['msg_id'] . "></td></tr>";
        }
    }
    echo "</table></form>
		<form method='POST'>
		<input type='submit' name='exit' value='Изход''>
		</form>";
}
if (!isset($_SESSION['isLogged']) || !isset($_SESSION['username'])) {
    echo '<p>Не си се логнал, върни се на страницата за <a href="./index.php">Вход</a></p>';
}
?>

<?php
include dirname(__FILE__).'/includes/footer.php'
?>