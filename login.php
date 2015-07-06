<?php
session_start();
include ("$_SERVER[DOCUMENT_ROOT]/config/mysql.php");
//Проверяем на содержание сессии, и если пользователь нажал на "Выход" (передав команду logout.php?logout)
if (isset($_GET['logout']))
{
	if (isset($_SESSION['user_id']))
		unset($_SESSION['user_id']);
	//то чистим куки ...		
	setcookie('login', '', 0, "/");
	setcookie('password', '', 0, "/");
	// и переносим его на главную
	header('Location: ./index.php');
	exit;
}

//Проверяем на содержание сессии, и если...
if (isset($_SESSION['user_id']))
{
	// юзер уже залогинен, перекидываем его отсюда на страницу БД
	header('Location: ./index.php');
	exit;
}

//Если пользователь ввел какие-либо данные то проверяем на логин и пароль
if (!empty($_POST))
{
	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
	
	$query = "SELECT `salt`
				FROM `users`
				WHERE `login`='{$login}'
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	
	if (mysql_num_rows($sql) == 1)
	{
		$row = mysql_fetch_assoc($sql);
		
		// итак, вот она соль, соответствующая этому логину:
		$salt = $row['salt'];
		
		// теперь хешируем введенный пароль как надо и повторям шаги, которые были описаны выше:
		$password = md5(md5($_POST['password']) . $salt);
		
		// и пошло поехало...

		// делаем запрос к БД
		// и ищем юзера с таким логином и паролем

		$query = "SELECT `id`
					FROM `users`
					WHERE `login`='{$login}' AND `password`='{$password}'
					LIMIT 1";
		$sql = mysql_query($query) or die(mysql_error());

		// если такой пользователь нашелся
		if (mysql_num_rows($sql) == 1)
		{
			// то мы ставим об этом метку в сессии (допустим мы будем ставить ID пользователя)

			$row = mysql_fetch_assoc($sql);
			$_SESSION['user_id'] = $row['id'];
			
			
			// если пользователь решил "запомнить себя"
			// то ставим ему в куку логин с хешем пароля
			
			$time = 86400; // ставим куку на 24 часа
			if (isset($_POST['remember']))
			{
				setcookie('login', $login, time()+$time, "/");
				setcookie('password', $password, time()+$time, "/");
			}
			
			// и перекидываем его на закрытую страницу
			header('Location: ./index.php');
			exit;

			// не забываем, что для работы с сессионными данными, у нас в каждом скрипте должно присутствовать session_start();
		}
		else
		{
			print('Такой логин с паролем не найдены в базе данных. И даём ссылку на повторную авторизацию. — <a href="./login.php">Авторизоваться</a>');
		}
	}
	else
	{
		print('пользователь с таким логином не найден, даём ссылку на повторную авторизацию. — <a href="./login.php">Авторизоваться</a>');
	}
}
$time_real = strval(($time)/3600);
include ("$_SERVER[DOCUMENT_ROOT]/template/head.php");
include ("$_SERVER[DOCUMENT_ROOT]/auth/auth.php");
include ("$_SERVER[DOCUMENT_ROOT]/template/main_menu.php");
print 
'
<div class="content"  align="center">
<h3>Авторизация</h3>
<form action="./login.php" method="post">
	<table>
		<tr>
			<td><a class="podskazka" >Логин:<span>Введите свой логин, в формате: example@purneftegaz.ru</span></a></td>
			<td><input type="text" name="login" placeholder="Введите e-mail"/></td>
		</tr>
		<tr>
			<td><a class="podskazka" >Пароль:<span>Введите свой пароль</span></a></td>
			<td><input type="password" name="password" placeholder="Введите пароль"/></td>
		</tr>
		<tr>
			<td><a class="podskazka" >Запомнить:<span>Если поставить галочку, это позволит входить автоматически каждый раз при посещении сайта</span></a></td>
			<td><input type="checkbox" name="remember" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Авторизоваться" /></td>
		</tr>
	</table>
</form>
</div>
';

include ("$_SERVER[DOCUMENT_ROOT]/template/head_end.php");
?>