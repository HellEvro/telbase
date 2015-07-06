<?php
session_start();
include ('./config/mysql.php');

/*
** Функция для генерации соли, используемоей в хешировании пароля
** возращает 3 случайных символа
*/

function GenerateSalt($n=3)
{
	$key = '';
	$pattern = '1234567890abcdefghijklmnopqrstuvwxyz.,*_-=+';
	$counter = strlen($pattern)-1;
	for($i=0; $i<$n; $i++)
	{
		$key .= $pattern{rand(0,$counter)};
	}
	return $key;
}

if (empty($_POST))
{
	?>
	
	<h3>Заполните регистрационные данные</h3>
	
	<form action="register.php" method="post" accept-charset="utf-8">
		<table>
			<tr>
				<td>Логин:</td>
				<td><input type="text" name="login" placeholder="Введите e-mail" value="<? echo $login ?>"/></td>
			</tr>
			<tr>
				<td>Пароль:</td>
				<td><input type="password" name="password" placeholder="Введите пароль" value="<? echo $password ?>"/></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Зарегистрироваться" /></td>
			</tr>
		</table>
	</form>
	
	
	<?php
}
else
{
	// обрабатываем пришедшие данные функцией mysql_real_escape_string перед вставкой в таблицу БД
	
	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
	$password = (isset($_POST['password'])) ? mysql_real_escape_string($_POST['password']) : '';
	
	
	// проверяем на наличие ошибок (например, длина логина и пароля)
	
	$error = false;
	$errort = '';
	if (!preg_match('/^([a-z0-9])(\w|[.]|-|_)+([a-z0-9])@([a-z0-9])([a-z0-9.-]*)([a-z0-9])([.]{1})([a-z]{2,4})$/is', $_POST['login'])) 
	{
		$error = true;
		$errort .= 'Логин должен быть в формате: exemlpe@mail.ru.<br />';
	}
	if (strlen($login) < 3)
	{
		$error = true;
		$errort .= 'Длина логина должна быть не менее 3-х символов.<br />';
	}
	if (strlen($password) < 4)
	{
		$error = true;
		$errort .= 'Длина пароля должна быть не менее 4 символов.<br />';
	}
	
	// проверяем, если юзер в таблице с таким же логином
	$query = "SELECT `id`
				FROM `users`
				WHERE `login`='{$login}'
				LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($sql)==1)
	{
		$error = true;
		$errort .= 'Пользователь с таким логином уже существует в базе данных, введите другой.<br />';
	}
	
	
	// если ошибок нет, то добавляем юзаре в таблицу
	
	if (!$error)
	{
		// генерируем соль и пароль
		
		$salt = GenerateSalt();
		$hashed_password = md5(md5($password) . $salt);
		
		$query = "INSERT
					INTO `users`
					SET
						`login`='{$login}',
						`password`='{$hashed_password}',
						`salt`='{$salt}'";
		$sql = mysql_query($query) or die(mysql_error());
		
		
		print '<h4>Поздравляем, Вы успешно зарегистрированы!</h4><a href="login.php">Авторизоваться</a>';
        print '<br/>Для полноценного доступа к системе отчетов, Вам необходимо обратиться к Администратору системы';
	}
	else
	{
		print '<h4>Возникли следующие ошибки</h4>' . $errort;
	}
}

?>