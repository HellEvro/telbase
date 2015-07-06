<?php
//////////////////////////////////////////////////////////////////////
//Загрузка файла на сервер ///////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
function func_load_data($upload_dir)                                                            
{                              
    echo 'Временный файл >>> '.($_FILES["path"]["tmp_name"]); 
    echo("<br />"); 
        if($_FILES["path"]["size"] > 1024*10*1024)                                              
        {                                                                                       
         echo ("Размер файла превышает 10 мегабайт");                                           
         exit;                                                                                  
        }                                                                                       
        // Проверяем загружен ли файл   
        if(is_uploaded_file($_FILES["path"]["tmp_name"]))                                       
        {                                                                                       
         // Если файл загружен успешно, перемещаем его                                          
         // из временной директории в конечную                                                  
         move_uploaded_file($_FILES["path"]["tmp_name"], $upload_dir.$_FILES["path"]["name"]);  
            echo 'Файл загружен в: '.$upload_dir.$_FILES["path"]["name"].'<br/>';               
        }                                                                                       
        else {                                                                                  
          echo("Ошибка загрузки файла");                                                        
          echo("<br />"); 
          exit;                                                                      
        }                                                                                       
}         
////////////////////////////////////////////////////////////////////////////////
//Парсер массива из данных файла ////////////////////////////////////
/////////////////////////////////////////////////////////////
//Чистка файла Пурсатком (отчет)
/////////////////////////////////////////////////////////////
function func_pursatcom_clean_array($afields) 
{
    echo '<br/>Обработка файла ООО "Пурсатком"... ';
    $afields= str_replace("-","",$afields);
    $afields= str_replace("  "," ",$afields);
    $afields= str_replace("\"\"\"","",$afields);
    $afields= str_replace("\"\"","",$afields);
    $afields= str_replace("\"","",$afields);
    #$afields= str_replace(";;",";",$afields);
    $afields = str_replace(" ;",";",$afields);
    $afields = str_replace("; ",";",$afields);
    $afields = str_replace(";\r\n","\r\n",$afields);
    $afields = str_replace(";\n\r","\n\r",$afields);
    $afields = str_replace(";\r","\r",$afields);
    $afields = str_replace(";\n","\n",$afields);
    foreach($afields as $k => $v)
    {
        if ($v=='') unset($afields[$k]);
    }
    //Переназначим индексы по возрастанию (сбросим нулевые строки с индексами)
    $afields = array_values($afields);
    echo 'Ок.';
return($afields);    
}
/////////////////////////////////////////////////////////////
//Чистка файла Ростелеком (отчет)
/////////////////////////////////////////////////////////////
function func_rostelecom_clean_array($afields,$regcode) 
{
    echo '<br/>Обработка файла ОАО "Ростелеком"... ';
        unset($afields[0]);
        unset($afields[1]);
        unset($afields[2]);
        unset($afields[3]);
        unset($afields[4]);
        unset($afields[5]);
        unset($afields[6]);
        unset($afields[7]);
        unset($afields[9]);
    $afields= str_replace(('7'.$regcode),"",$afields);
    $afields= str_replace($regcode,"",$afields);
    $afields= str_replace(";;;;;;;;;;;\r\n","",$afields); //Убираем пустые строки файла
    $afields= str_replace(";;;;;;;;;;;\n\r","",$afields); //Убираем пустые строки файла
    $afields = str_replace(" ;",";",$afields); //Убираем пробелы до ;
    $afields = str_replace("; ",";",$afields); //Убираем пробелы после ;
    $afields= str_replace(";;",";",$afields); //Убираем двойные ; (пустые ячейки)
    $afields = str_replace(";\r\n","\r\n",$afields);
    $afields = str_replace(";\n\r","\n\r",$afields);
    $afields = str_replace(";\r","\r",$afields);
    $afields = str_replace(";\n","\n",$afields);
	$afields= str_replace("\"","",$afields);
    //Убираем пустые элементы массива
    foreach($afields as $k => $v)
    {
        if ($v=='') unset($afields[$k]);
    }
    //Переназначим индексы по возрастанию (сбросим нулевые строки с индексами)
    $afields = array_values($afields);       
    echo 'Ок.';
return($afields);       
}
/////////////////////////////////////////////////////////////
//Чистка файла РН-Информ (отчет)
/////////////////////////////////////////////////////////////
function func_rninform_clean_array($afields) 
{
    echo '<br/>Обработка файла ООО "РН-Информ"... ';
    $afields= str_replace("  "," ",$afields);
    $afields= str_replace("\"\"\"","",$afields);
    $afields= str_replace("\"\"","",$afields);
    $afields= str_replace("\"","",$afields);
    $afields = str_replace(" ;",";",$afields);
    $afields = str_replace("; ",";",$afields);
    $afields = str_replace(";\r\n","\r\n",$afields);
    $afields = str_replace(";\n\r","\n\r",$afields);
    $afields = str_replace(";\r","\r",$afields);
    $afields = str_replace(";\n","\n",$afields);
    foreach($afields as $k => $v)
    {
        if ($v=='') unset($afields[$k]);
    }
    //Переназначим индексы по возрастанию (сбросим нулевые строки с индексами)
    $afields = array_values($afields);
    echo 'Ок.';
return($afields);      
}
///////////////////////////////////////////////////////////////////
//Переименование заголовков в полученном массиве данных из файлов 
///////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
//массив данных Пурсатком
/////////////////////////////////////////////////////////////
function func_pursatcom_header_set($afields) //Функция выставления заголовков для таблицы БД массиве
{
    echo '<br/>Переименование заголовков в массиве файла ООО "Пурсатком"... ';
    $afields = str_replace('Договор','contract',$afields);    
    $afields = str_replace('Наименование предприятия','dep',$afields);
    $afields = str_replace('Телефон','number',$afields);
    $afields = str_replace('Стоимость без НДС','cost_local',$afields);
    $afields = str_replace('Длительность, мин.','traff_local',$afields);
    //Новый шаблон выгрузки от 01.04.2013 от оператора.
    $afields = str_replace('CONTRACT','contract',$afields); 
    $afields = str_replace('CLIENT_NAME','dep',$afields);
    $afields = str_replace('DEVICE','number',$afields);
    $afields = str_replace('стоимость','cost_local',$afields);
    $afields = str_replace('продолжть','traff_local',$afields);
    $afields = str_replace('продолж-ть','traff_local',$afields);
    $afields = str_replace('продолжительность','traff_local',$afields);
    $afields = array_values($afields);//Переназначим индексы по возрастанию (сбросим нулевые строки с индексами) 
    echo 'Ок.';
    return($afields);    
}
/////////////////////////////////////////////////////////////
//массив данных Ростелеком
/////////////////////////////////////////////////////////////
function func_rostelecom_header_set($afields,$regcode) //Функция выставления заголовков для таблицы БД массиве
{
    echo '<br/>Переименование заголовков в массиве файла ОАО "Ростелеком"... ';
    $afields = str_replace($regcode,'',$afields);
    $afields = str_replace('Абонент','number',$afields);
    $afields = str_replace('Дата','date_call',$afields);
    $afields = str_replace('Время','time_call',$afields);
    $afields = str_replace('Услуга','in_out',$afields);
    $afields = str_replace('Вызывающий номер','number1',$afields);
    $afields = str_replace('Вызываемый номер','to_number',$afields);
    $afields = str_replace('Продол-сть(сек)/Кол-во(шт)','traff',$afields);
    $afields = str_replace('Стоимость (руб)','cost',$afields);
    $afields = str_replace('Направление','direction',$afields);
    $afields = array_values($afields);//Переназначим индексы по возрастанию (сбросим нулевые строки с индексами)
    echo 'Ок.'; 
return($afields);
}
/////////////////////////////////////////////////////////////
//массив данных РН-Информ
/////////////////////////////////////////////////////////////
function func_rninform_header_set($afields) //Функция выставления заголовков для таблицы БД массиве
{
    echo '<br/>Переименование заголовков в массиве файла ООО "РН-Информ"... ';    
    $afields = str_replace('Nakt','contract',$afields);
    $afields = str_replace('STOIM','cost_other',$afields);
    $afields = str_replace('Data_akta','date_call',$afields);
    $afields = str_replace('субподрядчик','subworker',$afields);
    $afields = str_replace('NAIMZ','direction',$afields);
    foreach($afields as $k => $v)
    {
        if (substr($v,0,2)==';;') unset($afields[$k]); // Удаляем пустые строки
    }    
    $afields = array_values($afields);//Переназначим индексы по возрастанию (сбросим нулевые строки с индексами)
return($afields);      
} 
//////////////////////////////////////////////////////////////////////
//Создание таблицы для данных из загружаемого файла //////////////////
//////////////////////////////////////////////////////////////////////
function func_create_table($table_name,$afields,$company,$regcode)
{  
    echo '<br/>Создание таблицы '.$table_name.'... ';  
    if ($company == 'pursatcom')  
        {
        $header_line = explode("\r\n",$afields[0]); //Берем только 1 строку (1 элемент массива)
        $header_line = explode(";",$header_line[0]);
        $header_line = implode(" VARCHAR (255),",$header_line);
        $header_line = mysql_escape_string($header_line);   
        }   
    if ($company == 'rostelecom')  
        {
        $header_line = explode("\r\n",$afields[0]);
        $header_line = explode(";",$header_line[0]);
        $header_line = implode(" VARCHAR (255),",$header_line);
        $header_line = mysql_escape_string($header_line);   
        $header_line = str_replace('\"','',$header_line);
		$header_line = str_replace('\"','',$header_line); 
		$header_line = str_replace('\"','',$header_line); 		
        }
    if ($company == 'rninform')  
        {
        $header_line = explode("\r\n",$afields[0]);
        $header_line = explode(";",$header_line[0]);
        $header_line = implode(" VARCHAR (255),",$header_line);
        $header_line = mysql_escape_string($header_line);   
        $header_line = str_replace('\"','',$header_line);        
        }
    $query = "
        CREATE TABLE ".$table_name."
        (
        `id` INT(10) DEFAULT NULL AUTO_INCREMENT,
        ".$header_line." VARCHAR (255),
        PRIMARY KEY (`id`)
        )
        ENGINE=InnoDB
        AUTO_INCREMENT=1
        ";  
    $query = str_replace(", VARCHAR (255),",",",$query); 
    	//print_r ($query);
        $result = mysql_query($query) or die('Запрос создания таблицы не удался - ' . mysql_error());    
        echo 'Ок.';  
}
//////////////////////////////////////////////////////////////////////
// ИМПОРТ ДАННЫХ /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////
function func_import_csv($table_name,$filename,$upload_dir,$db,$afields,$company,$regcode)     
{
    echo '<br/>Импорт данных в таблицу '.$table_name.'... '; 
    $fields = mysql_list_fields($db,$table_name);
    $columns = mysql_num_fields($fields); //читаем названия столбцов в нашей таблице
        for ($i = 1; $i < $columns; $i++) 
        {
            $col_name[$i] = mysql_field_name($fields, $i) . "\n"; 
        } 
    $header = $col_name; //вписываем в пассив имена столбцов
        for ($i=0;$i<count($header);$i++) 
        {
            $header = str_replace("\r","",$header);
            $header = str_replace("\n","",$header);
            $header = implode(",",$header);
        }
    //file_put_contents('$header.txt',$header); 
    unset($afields[0]); //убираем заголовки для таблицы. Оставляем только данные 
    //////////////////////////////////////////
        $afields = array_values($afields); //Переназначим индексы по возрастанию (сбросим нулевые строки с индексами)
        $afields = str_replace(";","','",$afields); //Подготавливаем строки массива (1-строка = 1 элемент массива) для VALUES
        $afields = str_replace("\r\n","",$afields);
        $num = count($afields); 
    echo '<br />';   
    echo '<u>Строк в файле:</u> '.$num;
    echo '<br />';
    //print_r ($afields);
    for ($i=0; $i<$num-1; $i++) 
    {    
        $line = $afields[$i];
        $id= $i+1;
        $query = "INSERT INTO $table_name  ";
        $query .= "VALUES ('$id','$line')";
        $query .= ";";
        //print_r ($query);
        $result = mysql_query($query) or die('<br/>Запрос на добавления данных в таблицу ' .$table_name. ' не удался, ошибка MySQL: <br/>' . mysql_error());
    }
    if ($result = true)
    {
        echo 'Ок.';
        echo '<br/>Загрузка файла <b>'.$filename.'</b> в базу данных под именем <b>'.$table_name.'</b> прошла успешно!';
    }
return ($result); 
}
/////////////////////////////////////////////////////////////////
//Поиск и Объединение таблиц с трафиком за год и месяц
/////////////////////////////////////////////////////////////////
function func_merge_tables_month($year,$month)     
{
        $table_date = $year.$month;
        echo '<br/>Импорт данных в таблицу traffic_'.$table_date.'... ';
        $query1 = mysql_query("SELECT id FROM rostelecom_$table_date LIMIT 1");
        $query2 = mysql_query("SELECT id FROM pursatcom_$table_date LIMIT 1");
        $query3 = mysql_query("SELECT id FROM rninform_$table_date LIMIT 1");
        if (($query1==true) || ($query2==true) || ($query3==true))
        {
    $query_create_table = "
        CREATE TABLE IF NOT EXISTS traffic_".$table_date." (
    	`id` INT(10) NOT NULL AUTO_INCREMENT,
    	`number` CHAR(11) NULL DEFAULT NULL,
    	`to_number` CHAR(12) NULL DEFAULT NULL,
    	`date_call` CHAR(10) NULL DEFAULT NULL,
    	`time_call` CHAR(8) NULL DEFAULT NULL,
    	`direction` VARCHAR(255) NULL DEFAULT NULL,
    	`traff_local` SMALLINT(10) NULL DEFAULT NULL,
    	`traff_zone` SMALLINT(10) NULL DEFAULT NULL,
    	`traff_amts` SMALLINT(10) NULL DEFAULT NULL,
    	`traffic_cost` DOUBLE(10,2) NULL DEFAULT NULL,
    	`other_cost` DOUBLE(10,2) NULL DEFAULT NULL,
    	`contract` CHAR(10) NULL DEFAULT NULL,
    	PRIMARY KEY (`id`),
    	INDEX `number` (`number`),
    	INDEX `contract` (`contract`)
        )
        ENGINE=InnoDB
        AUTO_INCREMENT=1;
        ";
        $result_create_table = mysql_query($query_create_table) 
        or die('<br/>Создание таблицы  traffic_'.$table_date.' не удалось, ошибка MySQL: <br/>' . mysql_error());
        $query_truncate_table = "TRUNCATE TABLE traffic_$table_date";
        $result_truncate_table = mysql_query($query_truncate_table) 
        or die('<br/>Обнуление таблицы  traffic_'.$year.$moths.' не удалось, ошибка MySQL: <br/>' . mysql_error());
        ////////////////////////////////////////////////////////////////////////
    	$pursatcom_test_query = "SELECT id FROM pursatcom_$table_date";
    	$pursatcom_test_result = mysql_query($pursatcom_test_query);
	if ($pursatcom_test_result==true)
	   {	
		$query_pursatcom = "
		INSERT INTO traffic_$table_date (number,traff_local,traffic_cost,contract) 
		SELECT 
        a.number,
        a.traff_local,
        a.cost_local,
        a.contract 
		FROM pursatcom_$table_date as a
		"; 
		//print_r ($query_pursatcom);
		$result_pursatcom = mysql_query($query_pursatcom)or die('<br/>ошибка MySQL: <br/>' . mysql_error());
	   }
    ////////////////////////////////////////////////////////////////////////
	$rostelecom_test_query = "SELECT id FROM rostelecom_$table_date";
	$rostelecom_test_result = mysql_query($rostelecom_test_query);
	if ($rostelecom_test_result==true)
	{
	$query_rostelecom_vz = "
		INSERT INTO traffic_$table_date (number,to_number,date_call,time_call,direction,traff_zone,traffic_cost) 
		SELECT
		a.number,
		a.to_number,
		a.date_call,
		a.time_call,
		a.direction,
		a.traff,
		a.cost
		FROM rostelecom_$table_date as a
		WHERE a.direction LIKE '%Вн/зон%'
		"; 
		$result_rostelecom_vz = mysql_query($query_rostelecom_vz)or die('<br/>ошибка MySQL: <br/>' . mysql_error());
	$query_rostelecom_mg = "
		INSERT INTO traffic_$table_date (number,to_number,date_call,time_call,direction,traff_amts,traffic_cost) 
		SELECT 
		a.number,
		a.to_number,
		a.date_call,
		a.time_call,
		a.direction,
		a.traff,
		a.cost
		FROM rostelecom_$table_date as a
		WHERE a.direction LIKE '%МГ предварит%'
		"; 
		$result_rostelecom_mg = mysql_query($query_rostelecom_mg)or die('<br/>ошибка MySQL: <br/>' . mysql_error());
	$query_rostelecom_mn = "
		INSERT INTO traffic_$table_date (number,to_number,date_call,time_call,direction,traff_amts,traffic_cost) 
		SELECT 
		a.number,
		a.to_number,
		a.date_call,
		a.time_call,
		a.direction,
		a.traff,
		a.cost
		FROM rostelecom_$table_date as a
		WHERE a.direction LIKE '%МН предварит%'
		"; 
		$result_rostelecom_mn = mysql_query($query_rostelecom_mn)or die('<br/>ошибка MySQL: <br/>' . mysql_error());
	}
    ////////////////////////////////////////////////////////////////////////
    $rninform_other_test_query = "SELECT id FROM rninform_$table_date";
	$rninform_other_test_result = mysql_query($rninform_other_test_query);
	if ($rninform_other_test_result==true)
	{
	$query_rninform_other = "
		INSERT INTO traffic_$table_date (date_call,direction,other_cost,contract) 
		SELECT a.date_call,a.direction,a.cost_other,a.contract 
		FROM rninform_$table_date as a
		WHERE a.direction NOT LIKE '%внутризон%телефон%'
		AND a.direction NOT LIKE '%телефон%соединен%'
		"; 
		$result_rninform_other = mysql_query($query_rninform_other)or die('<br/>ошибка MySQL: <br/>' . mysql_error());
	}
    ////////////////////////////////////////////////////////////////////////
    echo 'Ок.';
    return ($result); 
    }
    else
    {
        echo '<br />Что-то пошло не так!<br />';
    }
}
/////////////////////////////////////////////////////////////////
//Создание таблицы по отчету за год
/////////////////////////////////////////////////////////////////
function func_create_year_traffic_table($year,$traffic_table)
{   
    $query_truncate_table = "TRUNCATE TABLE ".$traffic_table." ";
    $query_create_tables = "  
    CREATE TABLE IF NOT EXISTS ".$traffic_table."(
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`number` CHAR(11) NULL DEFAULT NULL,
	`to_number` CHAR(12) NULL DEFAULT NULL,
	`date_call` CHAR(10) NULL DEFAULT NULL,
	`time_call` CHAR(8) NULL DEFAULT NULL,
	`direction` VARCHAR(255) NULL DEFAULT NULL,
	`traff_local` SMALLINT(10) NULL DEFAULT NULL,
	`traff_zone` SMALLINT(10) NULL DEFAULT NULL,
	`traff_amts` SMALLINT(10) NULL DEFAULT NULL,
	`traffic_cost` DOUBLE(10,2) NULL DEFAULT NULL,
	`other_cost` DOUBLE(10,2) NULL DEFAULT NULL,
	`contract` CHAR(10) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `number` (`number`),
	INDEX `contract` (`contract`)
    )
    ENGINE=InnoDB
    AUTO_INCREMENT=1;
    ";              
    $query_test = mysql_query("SELECT * FROM ".$traffic_table." LIMIT 1");
    if ($query_test == TRUE)
    {            
        $result_truncate = mysql_query($query_truncate_table) or die('Запрос на обнуление годовой таблицы не удался - ' . mysql_error());
    }           
    else
    {
        $result_create_tables = mysql_query($query_create_tables) or die('Запрос на создание годовой таблицы не удался - ' . mysql_error());
    }  
}
/////////////////////////////////////////////////////////////////
//Поиск и Объединение таблиц с трафиком за год
/////////////////////////////////////////////////////////////////
function func_merge_tables_year($year,$traffic_table)
{    
    $traffic_table = 'traffic_'.$year;
    $query =("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_name IN (SELECT table_name FROM information_schema.tables WHERE table_name LIKE '$traffic_table%')
    ");
    $tables_names = mysql_query($query);
    if ($tables_names==false)
    {
        echo '<br/>Не найдено таблиц хотя бы за 1 месяц по запросу за ' .$year. ' год<br/>';
    }
    else
    {
        // Формируем сам запрос в MySQL с выборкой по таблицам, если они есть.
        $select_headers= "number,to_number,time_call,traff_local,traff_zone,traff_amts,traffic_cost,other_cost,date_call,direction,contract";
        
        while ($table = mysql_fetch_array($tables_names))
            {
                $t = $table['table_name'];
                $query_update = "
                INSERT INTO ".$traffic_table." (".$select_headers.")
                SELECT ".$select_headers." 
                FROM $t
                ";
                $result = mysql_query($query_update) or die('Запрос на объединение таблиц в одну годовую не удался - ' . mysql_error());  
            }
        if ($result == true)
            {                                              
                echo '<br/>Таблицы данных успешно сформированы. <br/>Трафик за год находится в таблице "'.$traffic_table.'"<br/>';
            }
    }
}
///////////////////////////////////////////////////
//Обновление телефонного справочника
///////////////////////////////////////////////////
function func_phone_update()
{
    $query_table_names =("
			SELECT table_name 
			FROM information_schema.tables 
			WHERE table_name IN (SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'pursatcom%')
			");
    $tables_names = mysql_query($query_table_names);
    if ($tables_names==false)
    {
        echo 'Не найдено таблиц хотя бы за 1 месяц по запросу за ' .$year. ' год';
    }
    else
    {
        $query_id = mysql_result(mysql_query("SELECT MAX(id) FROM phone"),0) or die( mysql_error());
        $id = $query_id*1+1;
        $query_auto_id = mysql_query("ALTER TABLE phone AUTO_INCREMENT = $id") or die( mysql_error());
        /////////////////////////////////////////////////////////////
        while ($table = mysql_fetch_assoc($tables_names))
        {
            $t = $table['table_name'];
			//запрос на выборку всех номеров без номеров договора(phone.contract), для обновления из $t.contract
			$query_phone_list_tmp = "
			SELECT number 
			FROM phone
			WHERE phone.contract = NULL OR contract = '0' AND phone.number != ''
			";
			//получили массив номеров phone, для сравнения с $t.number
			$result_phone_number_tmp = mysql_query($query_phone_list_tmp);
			//$arr_phone_number = mysql_fetch_assoc($result_phone_number_tmp);
			$num_rows = mysql_num_rows($result_phone_number_tmp);
        echo 'Записей без номера договора: '.$num_rows.'<br />';
			if ($num_rows > 0)
			{
				for ($i = 0; $i < $num_rows; $i++) 
				{
					//для каждого номера теперь будем заносить номера договоров (phone.contract)
				 while ($phone_number = mysql_fetch_assoc($result_phone_number_tmp))
					{    
					//чистим от всех букв, символов и оставляем только цифры и запятую
					$phone_number = implode($phone_number);
					$phone_number = trim($phone_number);
					$phone_number = preg_replace("/[^0-9]{5}[^\,]*[a-zA-Zа-яА-Я\s\w]*/i","",$phone_number);
					$phone_number = str_replace(" ","",$phone_number); //подчистим оставшиеся пробелы
					$phone_number = explode(",",$phone_number);	
					$phone_number = preg_replace("/[^0-9]{5}[^\,]*[a-zA-Zа-яА-Я\s\w]*/i","",$phone_number);
					$phone_number = str_replace(" ","",$phone_number); //подчистим оставшиеся пробелы			
						foreach($phone_number as $k => $v)
						{
								//для каждого номера делается выборка номера договора		
                            $query_phone_number_tmp = "
								SELECT DISTINCT $t.number, $t.contract
								FROM   $t, phone
								WHERE  $t.number LIKE \"%$v%\"
								";	
								$query_phone_number = mysql_query($query_phone_number_tmp);									
								$result_phone_number = mysql_fetch_assoc($query_phone_number);
							if ($result_phone_number['contract'] !='' or $result_phone_number['contract'] = '0')
							{
							$query_update_phone = "
								UPDATE phone 
								SET phone.contract = '".$result_phone_number['contract']."' 
								WHERE phone.number LIKE \"%".$result_phone_number['number']."%\"
								";							
								$result_update = mysql_query($query_update_phone) or die( mysql_error());
							}					
						}
					}
				}
			}
			else
				{
				echo '<br />';
				echo 'Нет данных для обновления!';
				}
		echo '<br />';		
		echo 'Данные обновлены!';
		}
	}
}
///////////////////////////////////////////////////////////
// Добавление новых телефонов
///////////////////////////////////////////////////////////
function func_phone_add()
{
    $query_table_names =("
		SELECT table_name 
		FROM information_schema.tables 
		WHERE table_name IN (SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'pursatcom%')
		");
    $tables_names = mysql_query($query_table_names);
    if ($tables_names==false)
    {
        echo 'Не найдено таблиц хотя бы за 1 месяц по запросу за ' .$year. ' год';
    }
    else
    {
        $query_id = mysql_result(mysql_query("SELECT MAX(id) FROM phone"),0) or die( mysql_error());
        $id = $query_id*1+1;
        $query_auto_id = mysql_query("ALTER TABLE phone AUTO_INCREMENT = $id") or die( mysql_error());
        //Добавляем новые телефоны из таблиц-отчетов pursatcom_xxxxXX
        while ($table1 = mysql_fetch_assoc($tables_names))
        {
            $t0 = $table1['table_name'];
            $query_upd1 = "
            INSERT IGNORE INTO phone (contract,number)            
            SELECT $t0.contract, $t0.number 
            FROM $t0 
            WHERE id != '';
            ";
            $result_upd1 = mysql_query($query_upd1) or die ('<br/>ошибка MySQL: <br/>' . mysql_error());
            echo '<br />';		
            echo 'Данные обновлены!';
        }
    } 
}
?>       