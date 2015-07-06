<?php
/////////////////////////////////////////////////////////////////////
//Отчет по году
/////////////////////////////////////////////////////////////////////
function func_rep_year($year,$traffic_table)
{     
echo  '<h3><br/>Формирование отчета за - '.$_GET['year'].' год.</h3>';   
    $query_dep = "
        SELECT DISTINCT(department.dep),limit_all,limit_AMTS 
        FROM ".$traffic_table.",department,phone 
        WHERE ".$traffic_table.".number=phone.number 
        AND phone.contract=department.contract 
		ORDER BY department.id
        ";
        $dep = mysql_query($query_dep) or die('Запрос не удался - ' . mysql_error());
        #print_r($query_dep);
        $num1 = mysql_num_rows($dep) or die('Запрос не удался - ' . mysql_error());
        if ($num1 > 0)
    	{       
        echo '
         <table width="100%" border="1">       
          <tr align="center">
            <th rowspan="2" scope="col">Управление</th>
            <th rowspan="2" scope="col">Все затраты</th>
            <th colspan="3" scope="col">Затраты по трафику</th>
            <th rowspan="2" scope="col">Остальные затраты</th>
            <th rowspan="2" scope="col">Лимиты общие</th>
            <th rowspan="2" scope="col">Лимиты АМТС</th>
          </tr>
          <tr align="center">
            <th scope="col">Локальный</th>
            <th scope="col">Зоновый</th>
            <th scope="col">Межгород</th>
          </tr>
         ';        
if (ob_get_level() == 0) ob_start();       
        for($i=0;$i<$num1;$i++)
            { 			
            $row1 = mysql_fetch_array($dep);
        $query_cost_local = ("
            SELECT SUM(traffic_cost) as cost_local
            FROM ".$traffic_table."
            WHERE traff_local != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_zone = ("
            SELECT SUM(traffic_cost) as cost_zone
            FROM ".$traffic_table."
            WHERE traff_zone != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_amts = ("
            SELECT SUM(traffic_cost) as cost_amts
            FROM ".$traffic_table."
            WHERE traff_amts != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone ,department WHERE ".$traffic_table.".number = phone.number AND phone.contract = department.contract AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_other = ("
            SELECT SUM(other_cost) as cost_other
            FROM ".$traffic_table.",department
            WHERE ".$traffic_table.".contract = RIGHT(department.contract,3) 
            AND department.dep = '".$row1['dep']."'
            ;");            
            $row2 = mysql_fetch_array(mysql_query($query_cost_local));
            $row3 = mysql_fetch_array(mysql_query($query_cost_zone));
            $row4 = mysql_fetch_array(mysql_query($query_cost_amts));
            $row5 = mysql_fetch_array(mysql_query($query_cost_other));
        $a1 = $row2['cost_local'];
        $a2 = $row3['cost_zone'];
        $a3 = $row4['cost_amts'];
        $a4 = $row5['cost_other'];
        $cost_all_mgmnzone_other = $a1+$a2+$a3+$a4;
        $limits_all = ($row1['limit_all'])*12;
        $limits_AMTS = ($row1['limit_AMTS'])*12;
            $a11 = $a11+$a1;
            $a22 = $a22+$a2;
            $a33 = $a33+$a3;
            $a44 = $a44+$a4;
            $cost_all_all = $cost_all_all + $cost_all_mgmnzone_other;
            $limits_all_itogo = $limits_all_itogo + $limits_all;
            $limits_AMTS_itogo = $limits_AMTS_itogo + $limits_AMTS;
          //Дорисовываем таблицу, заполняем данными
          echo  '<tr >';
          echo  '<th scope="row" align="left">&nbsp'.$row1['dep'].'&nbsp</th>';
          echo  '<td align="right">&nbsp'.$cost_all_mgmnzone_other.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a1.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a2.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a3.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a4.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_all.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_AMTS.'&nbsp</td>';
          echo  '</tr>';  	
ob_flush();
flush();  // Необходимо для работы - ob_flush
usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец
  		  }
          echo  '<tr >';
          echo  '<th scope="row" align="left">&nbsp Итого: &nbsp</th>';
          echo  '<td align="right">&nbsp'.$cost_all_all.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a11.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a22.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a33.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a44.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_all_itogo.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_AMTS_itogo.'&nbsp</td>';          
          echo  '</tr>'; 
         echo '</table>';
         }   
        else
            {
                echo 'За '.$_GET['year'].' год в базе данны информации нет.';
            }
}
/////////////////////////////////////////////////////////////////////
//Отчет по году в файл
/////////////////////////////////////////////////////////////////////
function func_rep_year_tofile($year,$traffic_table)
{
   		$query_dep = ("
        SELECT DISTINCT(department.dep),limit_all,limit_AMTS 
        FROM ".$traffic_table.",department,phone 
        WHERE dep = (
        SELECT dep 
        FROM department 
        WHERE ".$traffic_table.".number=phone.number 
        AND phone.contract=department.contract)
        ORDER BY department.id
        ;") 
        or die('Запрос не удался - ' . mysql_error());
        echo  '<h3><br/>Формирование отчета за - '.$_GET['year'].' год.</h3>';
        $dep = mysql_query($query_dep);
        $num1 = mysql_num_rows($dep);
        $csv_file =''; // создаем переменную, в которую записываем строки
        if ($num1 > 0)
    	{       
        $csv_file .="Управление;Все затраты;Локальный;Зоновый;Межгород;Остальные затраты;Лимиты общие;Лимиты АМТС\r\n";
if (ob_get_level() == 0) ob_start();     
        for($i=0;$i<$num1;$i++)
            { 
            $row1 = mysql_fetch_array($dep);
        $query_cost_local = ("
            SELECT SUM(traffic_cost) as cost_local
            FROM ".$traffic_table."
            WHERE traff_local != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_zone = ("
            SELECT SUM(traffic_cost) as cost_zone
            FROM ".$traffic_table."
            WHERE traff_zone != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_amts = ("
            SELECT SUM(traffic_cost) as cost_amts
            FROM ".$traffic_table."
            WHERE traff_amts != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_other = ("
            SELECT SUM(other_cost) as cost_other
            FROM ".$traffic_table.",department
            WHERE ".$traffic_table.".contract = RIGHT(department.contract,3) 
            AND department.dep = '".$row1['dep']."'
            AND ".$traffic_table.".number = ''
            ;");  
            $row2 = mysql_fetch_array(mysql_query($query_cost_local));
            $row3 = mysql_fetch_array(mysql_query($query_cost_zone));
            $row4 = mysql_fetch_array(mysql_query($query_cost_amts));
            $row5 = mysql_fetch_array(mysql_query($query_cost_other));
                $a1 = $row2['cost_local'];
                $a2 = $row3['cost_zone'];
                $a3 = $row4['cost_amts'];
                $a4 = $row5['cost_other'];
                $cost_all_mgmnzone_other = $a1+$a2+$a3+$a4;
                $limits_all = ($row1['limit_all'])*12;
                $limits_AMTS = ($row1['limit_AMTS'])*12;
            $a11 = $a11+$a1;
            $a22 = $a22+$a2;
            $a33 = $a33+$a3;
            $a44 = $a44+$a4;
            $cost_all_all = $cost_all_all + $cost_all_mgmnzone_other;
            $limits_all_itogo = $limits_all_itogo + $limits_all;
            $limits_AMTS_itogo = $limits_AMTS_itogo + $limits_AMTS;
          //Дорисовываем таблицу, заполняем данными/////////////////
          	$csv_file .=
             $row1['dep'].";"
            .$cost_all_mgmnzone_other.";"
            .$a1.";"
            .$a2.";"
            .$a3.";"
            .$a4.";"
            .$limits_all.";"
            .$limits_AMTS.";\r\n";
            // в качестве начала и конца полей я указал " (двойные кавычки)
            // в качестве разделителей полей я указал ; (запятая)
            //   \r\n - это перенос строки
ob_flush();
flush();  // Необходимо для работы - ob_flush
usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец	
         }
            $csv_file .=
             "Итого;"
            .$cost_all_all.";"
            .$a11.";"
            .$a22.";"
            .$a33.";"
            .$a44.";"
            .$limits_all_itogo.";"
            .$limits_AMTS_itogo.";\r\n";
		$file_path = $_SERVER['DOCUMENT_ROOT']."/reports_ls_out/";
        $file_name = 'Годовой Отчет за '.$_GET['year'].'год.csv'; // название файла
        $file = fopen($file_path.$file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл
        print '<b>Выгрузка данных в файл:</b> ' .$file_name. ' прошла успешно.<br/>';  
        print '<h3>Скачать файл: <a href ="'.$file_path.$file_name.'">'.$file_name.'</a> </h3>';       
      }
        else
            {
                echo 'За '.$_GET['year'].' год в базе данны информации нет.';
            }
}
/////////////////////////////////////////////////////////////////////
//Отчет по месяцу
/////////////////////////////////////////////////////////////////////
function func_rep_month($month,$year,$traffic_table)
{
   		$query_dep = "
        SELECT DISTINCT(department.dep),limit_all,limit_AMTS FROM ".$traffic_table.",department,phone 
        WHERE dep = (SELECT dep FROM department WHERE ".$traffic_table.".number=phone.number AND phone.contract=department.contract)
		ORDER BY department.id
        ;";   
        echo  '<h3><br/>Отчет за - '.$_GET['month'].'.'.$_GET['year'].' год(а).</h3>';   
        $dep = mysql_query($query_dep) or die('Запрос не удался - ' . mysql_error());
        $num1 = mysql_num_rows($dep) or die('Запрос не удался - ' . mysql_error());
        if ($num1 > 0)
    	{       
        echo '
         <table width="100%" border="1">       
          <tr align="center">
            <th rowspan="2" scope="col">Управление</th>
            <th rowspan="2" scope="col">Все затраты</th>
            <th colspan="3" scope="col">Затраты по трафику</th>
            <th rowspan="2" scope="col">Остальные затраты</th>
            <th rowspan="2" scope="col">Лимиты общие</th>
            <th rowspan="2" scope="col">Лимиты АМТС</th>
          </tr>
          <tr align="center">
            <th scope="col">Локальный</th>
            <th scope="col">Зоновый</th>
            <th scope="col">Межгород</th>
          </tr>
         ';
if (ob_get_level() == 0) ob_start();    
        for($i=0;$i<$num1;$i++)
            { 
            $row1 = mysql_fetch_array($dep);
        $query_cost_local = ("
            SELECT SUM(traffic_cost) as cost_local
            FROM ".$traffic_table."
            WHERE traff_local != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");           
        $query_cost_zone = ("
            SELECT SUM(traffic_cost) as cost_zone
            FROM ".$traffic_table."
            WHERE traff_zone != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_amts = ("
            SELECT SUM(traffic_cost) as cost_amts
            FROM ".$traffic_table."
            WHERE traff_amts != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone ,department 
            WHERE ".$traffic_table.".number = phone.number 
            AND phone.contract = department.contract 
            AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_other = ("
            SELECT SUM(other_cost) as cost_other
            FROM ".$traffic_table.",department
            WHERE ".$traffic_table.".contract = RIGHT(department.contract,3) 
            AND department.dep = '".$row1['dep']."'
            ;");            
                $row2 = mysql_fetch_array(mysql_query($query_cost_local));
                $row3 = mysql_fetch_array(mysql_query($query_cost_zone));
                $row4 = mysql_fetch_array(mysql_query($query_cost_amts));
                $row5 = mysql_fetch_array(mysql_query($query_cost_other));
            $a1 = $row2['cost_local'];
            $a2 = $row3['cost_zone'];
            $a3 = $row4['cost_amts'];
            $a4 = $row5['cost_other'];
            $cost_all_mgmnzone_other = $a1+$a2+$a3+$a4;
            $limits_all = $row1['limit_all'];
            $limits_AMTS = $row1['limit_AMTS'];
                $a11 = $a11+$a1;
                $a22 = $a22+$a2;
                $a33 = $a33+$a3;
                $a44 = $a44+$a4;
                $cost_all_all = $cost_all_all + $cost_all_mgmnzone_other;
                $limits_all_itogo = $limits_all_itogo + $limits_all;
                $limits_AMTS_itogo = $limits_AMTS_itogo + $limits_AMTS;
          //Дорисовываем таблицу, заполняем данными/////////////////
          echo  '<tr >';
          echo  '<th scope="row" align="left">&nbsp'.$row1['dep'].'&nbsp</th>';
          echo  '<td align="right">&nbsp'.$cost_all_mgmnzone_other.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a1.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a2.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a3.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a4.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_all.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_AMTS.'&nbsp</td>';
          echo  '</tr>';  
ob_flush();
flush();  // Необходимо для работы - ob_flush
usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец			  
  		  }
          echo  '<tr >';
          echo  '<th scope="row" align="left">&nbsp Итого: &nbsp</th>';
          echo  '<td align="right">&nbsp'.$cost_all_all.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a11.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a22.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a33.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a44.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_all_itogo.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$limits_AMTS_itogo.'&nbsp</td>';          
          echo  '</tr>'; 
         echo '</table>';        
  		  } 
        else
            {
                echo 'За  период '.$_GET['month'].'.'.$_GET['year'].' в базе данны информации нет.';
            }             
}
/////////////////////////////////////////////////////////////////////
//Отчет по месяцу в файл
/////////////////////////////////////////////////////////////////////
function func_rep_month_tofile($month,$year,$traffic_table)
{
  		$query_dep = ("
        SELECT DISTINCT(department.dep),limit_all,limit_AMTS 
        FROM ".$traffic_table.",department,phone 
        WHERE dep = (
        SELECT dep 
        FROM department 
        WHERE ".$traffic_table.".number=phone.number 
        AND phone.contract=department.contract)
        ORDER BY department.id
        ;") 
        or die('Запрос не удался - ' . mysql_error());              
        echo  '<h3><br/>Отчет за - '.$_GET['month'].'.'.$_GET['year'].' год(а).</h3>';  
        $dep = mysql_query($query_dep);
        $num1 = mysql_num_rows($dep);
        $csv_file =''; // создаем переменную, в которую записываем строки
        if ($num1 > 0)
    	{       
        $csv_file .="Управление;Все затраты;Локальный;Зоновый;Межгород;Остальные затраты;Лимиты общие;Лимиты АМТС\r\n";
if (ob_get_level() == 0) ob_start();    
        for($i=0;$i<$num1;$i++)
            { 
            $row1 = mysql_fetch_array($dep);
        $query_cost_local = ("
            SELECT SUM(traffic_cost) as cost_local
            FROM ".$traffic_table."
            WHERE traff_local != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone ,department WHERE ".$traffic_table.".number = phone.number AND phone.contract = department.contract AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_zone = ("
            SELECT SUM(traffic_cost) as cost_zone
            FROM ".$traffic_table."
            WHERE traff_zone != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone ,department WHERE ".$traffic_table.".number = phone.number AND phone.contract = department.contract AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_amts = ("
            SELECT SUM(traffic_cost) as cost_amts
            FROM ".$traffic_table."
            WHERE traff_amts != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone ,department WHERE ".$traffic_table.".number = phone.number AND phone.contract = department.contract AND department.dep = '".$row1['dep']."')
            ;");
        $query_cost_other = ("
            SELECT SUM(other_cost) as cost_other
            FROM ".$traffic_table.",department
            WHERE ".$traffic_table.".contract = RIGHT(department.contract,3) 
            AND department.dep = '".$row1['dep']."'
            AND ".$traffic_table.".number = ''
            ;");
            $row2 = mysql_fetch_array(mysql_query($query_cost_local));
            $row3 = mysql_fetch_array(mysql_query($query_cost_zone));
            $row4 = mysql_fetch_array(mysql_query($query_cost_amts));
            $row5 = mysql_fetch_array(mysql_query($query_cost_other));
                $a1 = $row2['cost_local'];
                $a2 = $row3['cost_zone'];
                $a3 = $row4['cost_amts'];
                $a4 = $row5['cost_other'];
                $cost_all_mgmnzone_other = $a1+$a2+$a3+$a4;
                $limits_all = $row1['limit_all'];
                $limits_AMTS = $row1['limit_AMTS'];
            $a11 = $a11+$a1;
            $a22 = $a22+$a2;
            $a33 = $a33+$a3;
            $a44 = $a44+$a4;
                $cost_all_all = $cost_all_all + $cost_all_mgmnzone_other;
                $limits_all_itogo = $limits_all_itogo + $limits_all;
                $limits_AMTS_itogo = $limits_AMTS_itogo + $limits_AMTS;
              //Дорисовываем таблицу, заполняем данными/////////////////
          	$csv_file .=
             $row1['dep'].";"
            .$cost_all_mgmnzone_other.";"
            .$a1.";"
            .$a2.";"
            .$a3.";"
            .$a4.";"
            .$limits_all.";"
            .$limits_AMTS.";\r\n";
            // в качестве начала и конца полей я указал " (двойные кавычки)
            // в качестве разделителей полей я указал ; (запятая)
            //   \r\n - это перенос строки
ob_flush();
flush();  // Необходимо для работы - ob_flush
usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец				
            }
        $csv_file .="Итого;".$cost_all_all.";".$a11.";".$a22.";".$a33.";".$a44.";".$limits_all_itogo.";".$limits_AMTS_itogo.";\r\n";
            // в качестве начала и конца полей я указал " (двойные кавычки)
            // в качестве разделителей полей я указал ; (запятая)
            //   \r\n - это перенос строки
        $file_path = $_SERVER['DOCUMENT_ROOT']."/reports_ls_out/";
        $file_name = 'Отчет за '.$_GET['month'].'.'.$_GET['year'].'.csv'; // название файла
        $file = fopen($file_path.$file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл
        print '<b>Выгрузка данных в файл:</b> ' .$file_name. ' прошла успешно.';        
        print '<h3>Скачать файл: <a href ="'.$file_path.$file_name.'">'.$file_name.'</a> </h3>';       
      }
        else
            {
                echo 'За '.$_GET['year'].' год в базе данны информации нет.';
            }
}
/////////////////////////////////////////////////////////////////////
//Отчет по всем управлениям в файл, за  месяц в файлы.
/////////////////////////////////////////////////////////////////////
function func_rep_deps_tofiles($month,$year,$traffic_table)
{
	$query = "
        SELECT *
        FROM `department`
        WHERE limit_all != '0,00' AND contract
        ORDER BY `contract` ASC LIMIT 1000";
	   $result = mysql_query($query);
    if (ob_get_level() == 0) ob_start(); 
    while ($array = mysql_fetch_array($result))
        {
            $dep = $array ['contract'];
            //print_r($dep);
            func_rep_dep_tofile($dep,$month,$year,$traffic_table);
    	ob_flush();
    	flush();  // Необходимо для работы - ob_flush
    	usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец	
        }
}
/////////////////////////////////////////////////////////////////////
//Отчет по всем управлениям в файл, за  месяц в файлы.
/////////////////////////////////////////////////////////////////////
function func_rep_deps_tomails($month,$year,$traffic_table)
{
	$query = "
        SELECT * FROM `department` 
        WHERE limit_all != '0,00' AND contract
        ORDER BY `contract` ASC LIMIT 1000";
	   $result = mysql_query($query);
    if (ob_get_level() == 0) ob_start(); 
    while ($array = mysql_fetch_array($result))
    {
        $dep = $array['contract'];
        $mail = $array ['mail'];
        //print_r($mail);
        func_rep_dep_tomail($dep,$month,$year,$traffic_table,$mail);
	ob_flush();
	flush();  // Необходимо для работы - ob_flush
	usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец	
    }
}
/////////////////////////////////////////////////////////////////////
//Отчет по месяцу и по управлению
/////////////////////////////////////////////////////////////////////
function func_rep_dep($dep,$month,$year,$traffic_table)
{
    $tel_number = "
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table
        WHERE $traffic_table.number IN 
        (
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table,department,phone 
        WHERE $traffic_table.number=phone.number 
        AND phone.contract LIKE '%$dep'
        )
		ORDER BY $traffic_table.number
        ";
        $result_tel_number = mysql_query($tel_number) or die('Запрос не удался - ' . mysql_error());
        $num1 = mysql_num_rows($result_tel_number) or die('Запрос не удался - ' . mysql_error());
        //Получаем название лицевого счета
    $query_dep = "
        SELECT DISTINCT dep 
        FROM department,phone,$traffic_table 
        WHERE department.contract LIKE '%$dep'";
        $result_dep = mysql_query($query_dep);
        $result_dep = mysql_fetch_array($result_dep);
        $result_dep = $result_dep[0];
        echo  '<h3><br/>Отчет за - '.$_GET['month'].'.'.$_GET['year'].' год(а).<br/>Лицевой счет - '.$result_dep.' ('.$dep.')<br/></h3>';        
        if ($num1 > 0)
    	{       
        echo '
         <table width="100%" border="1">       
          <tr align="center">
            <th rowspan="2" scope="col">Номер</th>
            <th rowspan="2" scope="col">Все затраты</th>
            <th colspan="3" scope="col">Затраты по трафику</th>
            <th rowspan="2" scope="col">Остальные затраты</th>
          </tr>
          <tr align="center">
            <th scope="col">Локальный</th>
            <th scope="col">Зоновый</th>
            <th scope="col">Межгород</th>
          </tr>
         ';
if (ob_get_level() == 0) ob_start();    
        for($i=0;$i<$num1;$i++)
            { 
            $row1 = mysql_fetch_array($result_tel_number);
        $query_cost_local = ("
            SELECT SUM(traffic_cost) as cost_local
            FROM ".$traffic_table."
            WHERE traff_local != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone WHERE ".$traffic_table.".number = '".$row1['number']."' AND phone.contract LIKE '%$dep')
            ;");
        $query_cost_zone = ("
            SELECT SUM(traffic_cost) as cost_zone
            FROM ".$traffic_table."
            WHERE traff_zone != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone WHERE ".$traffic_table.".number = '".$row1['number']."' AND phone.contract LIKE '%$dep')
            ;");
        $query_cost_amts = ("
            SELECT SUM(traffic_cost) as cost_amts
            FROM ".$traffic_table."
            WHERE traff_amts != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone WHERE ".$traffic_table.".number = '".$row1['number']."' AND phone.contract LIKE '%$dep')
            ;");
            $row2 = mysql_fetch_array(mysql_query($query_cost_local));
            $row3 = mysql_fetch_array(mysql_query($query_cost_zone));
            $row4 = mysql_fetch_array(mysql_query($query_cost_amts));
                $a1 = $row2['cost_local'];
                $a2 = $row3['cost_zone'];
                $a3 = $row4['cost_amts'];
                $cost_all_mgmnzone = $a1+$a2+$a3;
            $a11 = $a11+$a1;
            $a22 = $a22+$a2;
            $a33 = $a33+$a3;
            $cost_mgmnzone = $a22+$a33;
            $cost_all_all = $cost_all_all + $cost_all_mgmnzone;
          //Дорисовываем таблицу, заполняем данными/////////////////
          echo  '<th scope="row" align="left">&nbsp'.$row1['number'].'&nbsp</th>';
          echo  '<td align="right">&nbsp'.$cost_all_mgmnzone.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a1.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a2.'&nbsp</td>';
          echo  '<td align="right">&nbsp'.$a3.'&nbsp</td>';
          echo  '<td align="right">&nbsp&nbsp</td>';
          echo  '</tr>';
ob_flush();
flush();  // Необходимо для работы - ob_flush
usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец	  
  		  }
        $query_cost_other = ("
            SELECT SUM(other_cost) as cost_other
            FROM ".$traffic_table.",department
            WHERE ".$traffic_table.".contract = RIGHT(department.contract,3) 
            AND department.contract LIKE '%$dep'  
            ;");   
            $row5 = mysql_fetch_array(mysql_query($query_cost_other));
            $a4 = $row5['cost_other'];
            $cost_all_all = $cost_all_all+$a4;
        echo  '<tr>';
        echo  '<th scope="row" align="left">&nbsp Остальные затраты &nbsp</th>';
        echo  '<td align="right">&nbsp&nbsp</td>';
        echo  '<td align="right">&nbsp&nbsp</td>';
        echo  '<td align="right">&nbsp&nbsp</td>';
        echo  '<td align="right">&nbsp&nbsp</td>';
        echo  '<td align="right">&nbsp'.$a4.'&nbsp</td>';    
        echo  '</tr>';
        echo  '<tr>';
        echo  '<th scope="row" align="left">&nbsp Итого &nbsp</th>';
        echo  '<td align="right">&nbsp'.$cost_all_all.'&nbsp</td>';
        echo  '<td align="right">&nbsp'.$a11.'&nbsp</td>';
        echo  '<td align="right">&nbsp'.$a22.'&nbsp</td>';
        echo  '<td align="right">&nbsp'.$a33.'&nbsp</td>';
        echo  '<td align="right">&nbsp'.$a4.'&nbsp</td>';    
        echo  '</tr>';
        echo '</table>';
         }
        else
            {
                echo 'За период '.$_GET['month'].'.'.$_GET['year'].' по Управлению '.$result_dep.' в базе данны информации нет.';
            }                 
}
/////////////////////////////////////////////////////////////////////
//Отчет по месяцу и по управлению в файл
/////////////////////////////////////////////////////////////////////
function func_rep_dep_tofile($dep,$month,$year,$traffic_table)
{
   		$tel_number = "
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table
        WHERE $traffic_table.number IN 
        (
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table,department,phone 
        WHERE $traffic_table.number=phone.number 
        AND phone.contract LIKE '%$dep'
        )
		ORDER BY $traffic_table.number
        ";
        $result_tel_number = mysql_query($tel_number);
        $num1 = mysql_num_rows($result_tel_number);
        //Получаем название лицевого счета
    $query_dep = "SELECT DISTINCT dep FROM department,phone,$traffic_table WHERE department.contract LIKE '%$dep' AND limit_all != '0,00'";
        $result_dep = mysql_query($query_dep);
        $result_dep = mysql_fetch_array($result_dep);
        $result_dep = $result_dep[0];
        echo  '<h3><br/>Отчет за - '.$_GET['month'].'.'.$_GET['year'].' год(а).<br/>Лицевой счет - '.$result_dep.' ('.$dep.')<br/></h3>'; 
        //Получаем лимиты
    $query_limits = "SELECT DISTINCT limit_all,limit_AMTS FROM department WHERE contract LIKE '%$dep' AND limit_all != '0,00'";
        $result_limits = mysql_query($query_limits);
        $result_limits = mysql_fetch_array($result_limits);        
        $limit_all = $result_limits['limit_all'];
        $limit_AMTS = $result_limits['limit_AMTS'];
        if ($num1 > 0)
    	{
        $csv_file .="Номер;Все затраты,руб.;Трафик Локальный,руб.;Трафик Зоновый,руб.;Трафик Межгород,руб.;Остальные затраты,руб.;\r\n";
if (ob_get_level() == 0) ob_start();       
        for($i=0;$i<$num1;$i++)
            { 
            $row1 = mysql_fetch_array($result_tel_number);
    $query_cost_local = ("
            SELECT SUM(traffic_cost) as cost_local
            FROM ".$traffic_table."
            WHERE traff_local != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone WHERE ".$traffic_table.".number = '".$row1['number']."' AND phone.contract LIKE '%$dep')
            ;");
    $query_cost_zone = ("
            SELECT SUM(traffic_cost) as cost_zone
            FROM ".$traffic_table."
            WHERE traff_zone != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone WHERE ".$traffic_table.".number = '".$row1['number']."' AND phone.contract LIKE '%$dep')
            ;");
    $query_cost_amts = ("
            SELECT SUM(traffic_cost) as cost_amts
            FROM ".$traffic_table."
            WHERE traff_amts != '0'
            AND ".$traffic_table.".number IN (SELECT ".$traffic_table.".number FROM ".$traffic_table.",phone WHERE ".$traffic_table.".number = '".$row1['number']."' AND phone.contract LIKE '%$dep')
            ;");
            $row2 = mysql_fetch_array(mysql_query($query_cost_local));
            $row3 = mysql_fetch_array(mysql_query($query_cost_zone));
            $row4 = mysql_fetch_array(mysql_query($query_cost_amts));
                $a1 = $row2['cost_local'];
                $a2 = $row3['cost_zone'];
                $a3 = $row4['cost_amts'];
                    $cost_all_mgmnzone = $a1+$a2+$a3;
            $a11 = $a11+$a1;
            $a22 = $a22+$a2;
            $a33 = $a33+$a3;
            $cost_mgmnzone = $a22+$a33;
                $cost_all_all = $cost_all_all + $cost_all_mgmnzone;
                //Дорисовываем таблицу, заполняем данными/////////////////
              	$csv_file .= $row1['number'].";".$cost_all_mgmnzone.";".$a1.";".$a2.";".$a3.";".";\r\n";
                // в качестве начала и конца полей я указал " (двойные кавычки)
                // в качестве разделителей полей я указал ; (запятая)
                //   \r\n - это перенос строки
ob_flush();
flush();  // Необходимо для работы - ob_flush
usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец				
            }
        $query_cost_other = ("
        SELECT SUM(other_cost) as cost_other
        FROM ".$traffic_table.",department
        WHERE ".$traffic_table.".contract = RIGHT(department.contract,3) 
        AND department.contract LIKE '%$dep'  
        ;");   
            $row5 = mysql_fetch_array(mysql_query($query_cost_other));
            $a4 = $row5['cost_other'];
            $cost_all_all = $cost_all_all+$a4;
        $csv_file .= "Остальные затраты;".";".";".";".";".$a4.";\r\n";
        $csv_file .= "ИТОГО;".$cost_all_all.";".$a11.";".$a22.";".$a33.";".$a4.";\r\n";
        $csv_file .= "ЛИМИТЫ (в мес.);".$limit_all.";;;".$limit_AMTS.";;\r\n";   
            $file_path = $_SERVER['DOCUMENT_ROOT']."/reports_ls_out/"; 
            $file_name = 'Отчет по ЛС '.$result_dep.' за '.$_GET['month'].'.'.$_GET['year'].'.csv'; // название файла
            $file = fopen($file_path.$file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
            fwrite($file,trim($csv_file)); // записываем в файл строки
            fclose($file); // закрываем файл
        print '<b>Выгрузка данных в файл:</b> ' .$file_name. ' прошла успешно.';     
        print '<h3>Скачать файл: <a href ="'.$file_path.$file_name.'">'.$file_name.'</a> </h3>';       
      }  
        else
            {
                echo 'За период '.$_GET['month'].'.'.$_GET['year'].' по Управлению '.$result_dep.' в базе данны информации нет.';
            }
}
//********************************************************************************************************************************

/////////////////////////////////////////////////////////////////////
//Отправка отчета по месяцу и по управлению по почте
/////////////////////////////////////////////////////////////////////
function func_rep_dep_tomail($contract,$month,$year,$traffic_table)
{
    $tel_number = "
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table
        WHERE $traffic_table.number IN 
        (
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table,department,phone 
        WHERE $traffic_table.number=phone.number 
        AND phone.contract LIKE '%$contract'
        )
		ORDER BY $traffic_table.number
        ";        
        $result_tel_number = mysql_query($tel_number);
        $num1 = mysql_num_rows($result_tel_number);
        //Получаем название лицевого счета
    $query_dep = "
        SELECT DISTINCT dep 
        FROM department,phone,$traffic_table 
        WHERE department.contract LIKE '%$contract'";
        $result_dep = mysql_query($query_dep);
        $result_dep = mysql_fetch_array($result_dep);
        $result_dep = $result_dep[0];
        echo  '<h3><br/>Отчет за - '.$_GET['month'].'.'.$_GET['year'].' год(а).<br/>Лицевой счет - '.$result_dep.' ('.$contract.')<br/></h3>'; 
        //Получаем лимиты
    $query_limits = "SELECT DISTINCT limit_all,limit_AMTS,mail 
        FROM department 
        WHERE contract LIKE '%$contract'";
        $result_limits = mysql_query($query_limits);
        $result_limits = mysql_fetch_array($result_limits);        
        $limit_all = $result_limits['limit_all'];
        $limit_AMTS = $result_limits['limit_AMTS'];
            $mail = $result_limits['mail'];
        if ($num1 > 0)
    	{  
        $csv_file .="Номер;Все затраты,руб.;Трафик Локальный,руб.;Трафик Зоновый,руб.;Трафик Межгород,руб.;Остальные затраты,руб.;\r\n";
if (ob_get_level() == 0) ob_start();       
        for($i=0;$i<$num1;$i++)
            { 
            $row1 = mysql_fetch_array($result_tel_number);
        $query_cost_local = ("
            SELECT SUM(traffic_cost) as cost_local
            FROM ".$traffic_table."
            WHERE traff_local != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone 
            WHERE ".$traffic_table.".number = '".$row1['number']."' 
            AND phone.contract LIKE '%$contract')
            ;");
        $query_cost_zone = ("
            SELECT SUM(traffic_cost) as cost_zone
            FROM ".$traffic_table."
            WHERE traff_zone != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone 
            WHERE ".$traffic_table.".number = '".$row1['number']."' 
            AND phone.contract LIKE '%$contract')
            ;");
        $query_cost_amts = ("
            SELECT SUM(traffic_cost) as cost_amts
            FROM ".$traffic_table."
            WHERE traff_amts != '0'
            AND ".$traffic_table.".number IN (
            SELECT ".$traffic_table.".number 
            FROM ".$traffic_table.",phone 
            WHERE ".$traffic_table.".number = '".$row1['number']."' 
            AND phone.contract LIKE '%$contract')
            ;");
            $row2 = mysql_fetch_array(mysql_query($query_cost_local));
            $row3 = mysql_fetch_array(mysql_query($query_cost_zone));
            $row4 = mysql_fetch_array(mysql_query($query_cost_amts));
                $a1 = $row2['cost_local'];
                $a2 = $row3['cost_zone'];
                $a3 = $row4['cost_amts'];
                    $cost_all_mgmnzone = $a1+$a2+$a3;
            $a11 = $a11+$a1;
            $a22 = $a22+$a2;
            $a33 = $a33+$a3;
            $cost_mgmnzone = $a22+$a33;
            $cost_all_all = $cost_all_all + $cost_all_mgmnzone;
            //Дорисовываем таблицу, заполняем данными/////////////////
          	$csv_file .= $row1['number'].";".$cost_all_mgmnzone.";".$a1.";".$a2.";".$a3.";".";\r\n";
            // в качестве начала и конца полей я указал " (двойные кавычки)
            // в качестве разделителей полей я указал ; (запятая)
            //   \r\n - это перенос строки
ob_flush();
flush();  // Необходимо для работы - ob_flush
usleep(50000);//Время задержки вывода инфы - 0.5 секунды. Вывод по 1 строке - конец				
            }
        $query_cost_other = ("
            SELECT SUM(other_cost) as cost_other
            FROM ".$traffic_table.",department
            WHERE ".$traffic_table.".contract = RIGHT(department.contract,3) 
            AND department.contract LIKE '%$contract'  
            ;");   
                $row5 = mysql_fetch_array(mysql_query($query_cost_other));
                $a4 = $row5['cost_other'];
                $cost_all_all = $cost_all_all+$a4;
            $csv_file .= "Остальные затраты;".";".";".";".";".$a4.";\r\n";
            $csv_file .= "ИТОГО;".$cost_all_all.";".$a11.";".$a22.";".$a33.";".$a4.";\r\n";
            $csv_file .= "ЛИМИТЫ (в мес.);".$limit_all.";;;".$limit_AMTS.";;\r\n";   
                $file_path = $_SERVER['DOCUMENT_ROOT']."/reports_ls_out/"; 
                $file_name = 'Отчет по ЛС '.$result_dep.' за '.$_GET['month'].'.'.$_GET['year'].'.csv'; // название файла
                $file = fopen($file_path.htmlspecialchars($file_name),"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
                fwrite($file,trim($csv_file)); // записываем в файл строки
                fclose($file); // закрываем файл
            print '<b>Выгрузка данных в файл:</b> ' .$file_name. ' прошла успешно.';     
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++        
        echo "<br>";
        $filename = $file_name;
        $path = $_SERVER['DOCUMENT_ROOT']."/reports_ls_out/";
        $mailto = $mail;
        $from_mail = "";//при отправке из MS Outlook через почтовый Exchenge сервер, отправителя указывать нельзя, иначе не даст права на отправку.
        $from_name = "";//при отправке из MS Outlook через почтовый Exchenge сервер, отправителя указывать нельзя, иначе не даст права на отправку.
        $replyto = "";
        $subject = "Отчет по услугам связи за ".$_GET['month'].".".$_GET['year'];
        $message = "".$result_dep.":\r\n\r\n";
        $message .= "Всего потрачено по услугам связи: ".$cost_all_all."руб. (лимит по управлению в мес.: ".$limit_all."руб.)\r\n";
        if ($cost_all_mgmnzone !='')
        {
        $message .="\r\nВсего потрачено по междугородним (международным) переговорам: ".$cost_mgmnzone."руб. (лимит по управлению в мес.: ".$limit_AMTS."руб.)";
        }
        mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message);
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++        
      }
        else {echo 'За период '.$_GET['month'].'.'.$_GET['year'].' по Управлению '.$result_dep.' в базе данны информации нет.';}
}
//********************************************************************************************************************************

/////////////////////////////////////////////////////////////////////
//Отчет по месяцу и по номеру
/////////////////////////////////////////////////////////////////////
function func_rep_number($number,$month,$year,$traffic_table)
{
    $tel_number = "
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table,department,phone
        WHERE date_call LIKE '%$month%'\".\"'%$year'
        AND $traffic_table.number LIKE '%$number%'
        ";
        print ($tel_number);
        $result_number = mysql_query($tel_number) or die('Запрос не удался - ' . mysql_error());
        $result_number = mysql_fetch_array($result_number) or die('Запрос не удался - ' . mysql_error());
        $result_number = $result_dep[0];
        //Получаем название лицевого счета
    $query = "
        SELECT * FROM $traffic_table
        WHERE $traffic_table.number = '$number'
        ";              
        $result = mysql_query($query);
        $num1 = mysql_num_rows($result);
        echo  '<h3><br/>Отчет по номеру '.$_GET['number'].' за '.$month.'.'.$year.' <br/></h3>';        
        if ($num1 > 0)
    	{       
        echo '
         <table width="100%" border="1">       
          <tr align="center">
            <td>Номер</td>
            <td>Звонили на номер</td>
            <td>Дата</td>
            <td>Время</td>
            <td>Куда звонили</td>
            <td>Минут внутреннего трафика</td>
            <td>Секунд зонового трафика</td>
            <td>Секунд междугороднего(международного) трафика</td>
            <td>Стоимость</td>
          </tr>
         ';
     $traffic_table_query = "
        SELECT number,to_number,date_call,time_call,direction,traff_local,traff_zone,traff_amts,traffic_cost FROM $traffic_table
        WHERE $traffic_table.number = '$number'
        ORDER BY 'date_call' LIMIT 1000
        ";
        $traffic_table_rows = mysql_query($traffic_table_query);
        while ($row1 = mysql_fetch_array($traffic_table_rows))
        {
            echo  '<tr >';
            echo  '<td align="left" >&nbsp'.$row1['number'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['to_number'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['date_call'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['time_call'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['direction'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['traff_local'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['traff_zone'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['traff_amts'].'&nbsp</td>';
            echo  '<td align="right">&nbsp'.$row1['traffic_cost'].'&nbsp</td>';
            echo  '</tr>';  
        }
        $query_traff_local = "
            SELECT SUM(traff_local) as traff_local
            FROM ".$traffic_table."
            WHERE number = '$number'
            ;";
        $query_traff_zone = "
            SELECT SUM(traff_zone) as traff_zone
            FROM ".$traffic_table."
            WHERE number = '$number'
            ;";
        $query_traff_amts = "
            SELECT SUM(traff_amts) as traff_amts
            FROM ".$traffic_table."
            WHERE number = '$number'
            ;";
        $query_traffic_cost = "
            SELECT SUM(traffic_cost) as traffic_cost
            FROM ".$traffic_table."
            WHERE number = '$number'
            ;";
            $row2 = mysql_query($query_traff_local);
            $row3 = mysql_query($query_traff_zone);
            $row4 = mysql_query($query_traff_amts);
            $row5 = mysql_query($query_traffic_cost);
                $row2 = mysql_fetch_array($row2);
                $row3 = mysql_fetch_array($row3);
                $row4 = mysql_fetch_array($row4);
                $row5 = mysql_fetch_array($row5);
          //Дорисовываем таблицу, заполняем данными
          echo  '<tr >';
          echo  '<th colspan="5" align="right">Итого:&nbsp</th>';
          echo  '<td align="right">'.$row2['traff_local'].'&nbsp</td>';
          echo  '<td align="right">'.$row3['traff_zone'].'&nbsp</td>';
          echo  '<td align="right">'.$row4['traff_amts'].'&nbsp</td>';
          echo  '<td align="right">'.$row5['traffic_cost'].'&nbsp</td>';
          echo  '</tr>';          
          echo '</table>';
         }
        else
        {
            echo 'За  период '.$_GET['month'].'.'.$_GET['year'].' по номеру '.$_GET['number'].' в базе данны информации нет.';
        }        
}
/////////////////////////////////////////////////////////////////////
//Отчет по месяцу и по номеру в файл
/////////////////////////////////////////////////////////////////////
function func_rep_number_tofile($number,$month,$year,$traffic_table)
{
    $tel_number = "
        SELECT DISTINCT $traffic_table.number 
        FROM $traffic_table,department,phone
        WHERE date_call LIKE '%$month%'\".\"'%$year'
        AND $traffic_table.number LIKE '%$number%'
        ";
        $result_number = mysql_query($tel_number);
        $result_number = mysql_fetch_array($result_number);
        $result_number = $result_dep[0];
        //Получаем название лицевого счета
    $query = "
        SELECT * 
        FROM $traffic_table
        WHERE $traffic_table.number = '$number'
        ";              
        $result = mysql_query($query);
        $num1 = mysql_num_rows($result);
        echo  '<h3><br/>Поиск по номеру '.$_GET['number'].' <br/></h3>';
        $csv_file =''; // создаем переменную, в которую записываем строки
        if ($num1 > 0)
    	{   
    	$csv_file .="Номер;Звонили на номер;Дата;Время;Куда звонили;Минут внутреннего трафика;Секунд зонового трафика;Секунд междугороднего(международного) трафика;Стоимость;\r\n";   
    $traffic_table_query = "
        SELECT number,to_number,date_call,time_call,direction,traff_local,traff_zone,traff_amts,traffic_cost 
        FROM $traffic_table
        WHERE $traffic_table.number = '$number'
        ORDER BY 'date_call' LIMIT 1000
        ";
        $traffic_table_rows = mysql_query($traffic_table_query); 
        while ($row1 = mysql_fetch_array($traffic_table_rows))
        {  
          	$csv_file .=
             $row1['number'].";"
            .$row1['to_number'].";"
            .$row1['date_call'].";"
            .$row1['time_call'].";"
            .$row1['direction'].";"
            .$row1['traff_local'].";"
            .$row1['traff_zone'].";"
            .$row1['traff_amts'].";"
            .$row1['traffic_cost'].";\r\n";// в качестве разделителей полей я указал ; (запятая). \r\n - это перенос строки          
        }
     $query_traff_local = "
        SELECT SUM(traff_local) as traff_local
        FROM ".$traffic_table."
        WHERE number = '$number'
        ;";
     $row2 = mysql_fetch_array(mysql_query($query_traff_local));
        $query_traff_zone = "
        SELECT SUM(traff_zone) as traff_zone
        FROM ".$traffic_table."
        WHERE number = '$number'
        ;";
        $row3 = mysql_fetch_array(mysql_query($query_traff_zone));
     $query_traff_amts = "
        SELECT SUM(traff_amts) as traff_amts
        FROM ".$traffic_table."
        WHERE number = '$number'
        ;";
        $row4 = mysql_fetch_array(mysql_query($query_traff_amts));
     $query_traffic_cost = "
        SELECT SUM(traffic_cost) as traffic_cost
        FROM ".$traffic_table."
        WHERE number = '$number'
        ;";
        $row5 = mysql_fetch_array(mysql_query($query_traffic_cost));
            $csv_file .=
             "Итого;;;;;"
            .$row2['traff_local'].";"
            .$row3['traff_zone'].";"
            .$row4['traff_amts'].";"
            .$row5['traffic_cost'].";\r\n";
        $file_path = $_SERVER['DOCUMENT_ROOT']."/reports_ls_out/";
        $file_name = 'Отчет по номеру '.$number.' за '.$_GET['month'].'.'.$_GET['year'].'.csv'; // название файла
        $file = fopen($file_path.$file_name,"w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
        fwrite($file,trim($csv_file)); // записываем в файл строки
        fclose($file); // закрываем файл
        print '<b>Выгрузка данных в файл:</b> ' .$file_name. ' прошла успешно.';        
        print '<h3>Скачать файл: <a href ="'.$file_path.$file_name.'">'.$file_name.'</a> </h3>';       
        } 
        else
            {
                echo 'За  период '.$_GET['month'].'.'.$_GET['year'].' по номеру '.$_GET['number'].' в базе данны информации нет.';
            }                  
}
///////////////////////////////////////////////////////////
// Отправка почты с волжениями
///////////////////////////////////////////////////////////
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=windows-1251\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, "", $header)) {
        echo "Отправка почты на $mailto - OK<br />"; // or use booleans here
    } else {
        echo "Отправка почты $mailto - ОШИБКА<br />!";
    }
}
?>