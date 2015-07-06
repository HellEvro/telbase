<?php
	//Настройка подключения к MySQL
    $host="localhost";
    $user="root";
    $pswd="";
	$db="telbase";
	
	//Подключение
    $mysql_conn = mysql_connect($host,$user,$pswd) or die('Запрос не удался: ' . mysql_error());
    mysql_select_db($db,$mysql_conn) or die('Не удалось выбрать базу данных');
	
	//Установка обработки данных в кодировке cp1251 (windows-1251)
    mysql_query("set character_set_client	='cp1251'");
    mysql_query("set character_set_results	='cp1251'");
    mysql_query("set collation_connection	='cp1251_general_ci'");
	
	// сюда вынесем обработку суперглобальных массивов от слешей
	if (isset($el)) {
		function slashes(&$el)
		{
			if (is_array($el))
				foreach($el as $k=>$v)
					slashes($el[$k]);
			else $el = stripslashes($el); 
		}

		if (ini_get('magic_quotes_gpc'))
		{
			slashes($_GET);
			slashes($_POST);    
			slashes($_COOKIE);
		}
	}
	////////////////////////////////////////////////////////////////	
?>