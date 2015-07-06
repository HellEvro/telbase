<!--ѕравый столбец -->
<div class="sidebar2">
<!-- јвториизаци¤ -->

<?php
include ('./config/mysql.php');
// если пользователь не авторизован
if (!isset($_SESSION['id']))
{
	// то провер¤ем его куки
	// вдруг там есть логин и пароль к нашему скрипту
	if (isset($_COOKIE['login']) && isset($_COOKIE['password']))
	{
		// если же такие имеютс¤
		// то пробуем авторизовать пользовател¤ по этим логину и паролю
		$login = mysql_escape_string($_COOKIE['login']);
		$password = mysql_escape_string($_COOKIE['password']);
		// и по аналогии с авторизацией через форму:
		// делаем запрос к Ѕƒ
		// и ищем юзера с таким логином и паролем
		$query = "SELECT `id`
					FROM `users`
					WHERE `login`='{$login}' AND `password`='{$password}'
					LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());
		// если такой пользователь нашелс¤
		if (mysql_num_rows($sql) == 1)
		{
			// то мы ставим об этом метку в сессии (будем ставить ID пользовател¤)
			$row = mysql_fetch_assoc($sql);
			$_SESSION['user_id'] = $row['id'];
			// не забываем, что дл¤ работы с сессионными данными, у нас в каждом скрипте должно присутствовать session_start();
		}
	}
}

if (isset($_SESSION['user_id']))
{
	$query = "SELECT `login`
				FROM `users`
				WHERE `id`='{$_SESSION['user_id']}'
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	// если нету такой записи с пользователем
	// ну вдруг удалили его пока он лазил по сайту.. =)
	// то надо ему убить ID, установленный в сессии, чтобы он был гостем
	if (mysql_num_rows($sql) != 1)
	{
		header('Location: ./login.php?logout');
		exit;
	}
	
	$row = mysql_fetch_assoc($sql);
	$welcome = $row['login'];
    
    //ѕрава доступа
    $query1 = (" 
		SELECT users.rights,access.description FROM users,access 
		WHERE rights IN (SELECT users.rights FROM users WHERE login = '".$welcome."' )
        AND description IN (SELECT description FROM access WHERE users.rights = access.access)  
        ;") or die('«апрос не удалс¤ - ' . mysql_error());
    $result = mysql_query($query1);    
    $sql1 = mysql_query($query1);
    $row1 = mysql_fetch_assoc($sql1);
    $rights = $row1['description'];
    $access = $row1['rights'];
    
}
else
{
	$welcome = '√ость';
}
print '&nbsp&nbsp&nbsp&nbsp ¬ы - <b>' . $welcome . '.</b>';
if ($welcome != '√ость')
{
print '&nbsp&nbsp&nbsp&nbsp ƒоступ - <b>'.$rights.'</b>';
}

if (!isset($_SESSION['user_id']))
{

	print '&nbsp&nbsp&nbsp&nbsp <a href="./login.php">јвторизаци¤</a>';
	print '&nbsp&nbsp&nbsp&nbsp <a href="./register.php">–егистраци¤</a>';

}
else
{
	print '&nbsp&nbsp&nbsp&nbsp <a href="./login.php?logout">¬ыход</a><br />';
}


?>
<!-- end .sidebar2 -->

</div>