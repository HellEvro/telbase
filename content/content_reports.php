<?php
session_start();
include ("$_SERVER[DOCUMENT_ROOT]/config/mysql.php");
include("$_SERVER[DOCUMENT_ROOT]/content/func_reports.php");
?>

<h1>Формирование отчетов по трафику СУЗ "ТС"</h1>

<h3>Сформировать:</h3>

 <table border="0" cellspacing="12px" bgcolor="#FFA200">
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- Доступ к отчетам за год и месяц только для определенных пользователей -->
<?php if (isset($_SESSION['user_id']))
{   
    $rights = $access;
        if ($rights == '1' || $rights == '2' || $rights == '3')
        {
?>
<!-- Доступ к отчетам за год и месяц только для определенных пользователей -->
<form method="GET" action="" >
 	<tr>
    <td valign="top" style="alighn:top"><a class="podskazka" >Годовой отчет за &nbsp<span>Формирует отчет по трафику за выбранный год - включает в себя все месяцы по которым имеется информация в БД.</span></a></td>
	<td><a class="podskazka" ><input name="year" type="text" value="<?php echo $year ?>"/><span>Вводить данные необходимо в формате: 20хх (например 2013). <br /><br />"Ок" - для вывода на экран.<br /> "В файл" - для сохранения результата в файле.</span></a> год.</td>
    <td>
    <input type="submit" name="submit_year" value="Ok" />
    </td>
    <td>
    <input type="submit" name="submit_year_tofile" value="В файл" accesskey="1"/>
    </td>
    </li>
</form>	

<form method="GET" action="">
 	<tr>
    <td valign="top" style="alighn:top"><a class="podskazka" >Месячный отчет за <span>Формирует отчет по трафику за выбранный месяц и год.</span></a>
    </td>
	<td>
		 <select name="month" value="<?php echo $month ?>">
			<option value="01">01. Январь</option>
			<option value="02">02. Февраль</option>
			<option value="03">03. Март</option>
			<option value="04">04. Апрель</option>
			<option value="05">05. Май</option>
			<option value="06">06. Июнь</option>
			<option value="07">07. Июль</option>
			<option value="08">08. Август</option>
			<option value="09">09. Сентябрь</option>
			<option value="10">10. Октябрь</option>
			<option value="11">11. Ноябрь</option>
			<option value="12">12. Декабрь</option>
		</select>	
		<a class="podskazka" ><input type="text" name="year" value="<?php echo $year ?>"/><span>Сначала нужно выбрать "Месяц", а затем ввести данные о годе в формате: 20хх (например 2013).<br /><br />"Ок" - для вывода на экран.<br /> "В файл" - для сохранения результата в файле.</span></a> года
    </td>
    <td>
    <input type="submit" name="submit_month" value="Ok" />
    </td>
    <td>
    <input type="submit" name="submit_month_tofile" value="В файл" />
    </td>	
    </tr>
</form>	

<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<form method="GET" action="" >

	<tr>
    <td valign="top" style="alighn:top">
    <a class="podskazka" >Отчеты по всем лицевым счетам<span>
    Формирует отчеты по лицевым счетам Управлений за месяц указанного года и сохраняет в отдельные файлы.</span></a>
    </td> 
	<td>
		<select name="month" value="<?php echo $month ?>">
			<option value="01">01. Январь</option>
			<option value="02">02. Февраль</option>
			<option value="03">03. Март</option>
			<option value="04">04. Апрель</option>
			<option value="05">05. Май</option>
			<option value="06">06. Июнь</option>
			<option value="07">07. Июль</option>
			<option value="08">08. Август</option>
			<option value="09">09. Сентябрь</option>
			<option value="10">10. Октябрь</option>
			<option value="11">11. Ноябрь</option>
			<option value="12">12. Декабрь</option>
		</select>
		<a class="podskazka" ><input type="text" name="year" value="<?php echo $year ?>"/><span>
  Необходимо выбрать "Управление", "Месяц" и ввести данные о годе в формате: 20хх (например 2013).<br /><br />"Ок" - для вывода на экран.<br /> "В файл" - для сохранения результата в файле.</span></a> годa
    </td>
	<td>
	</td>
    <td>
    <input type="submit" name="submit_deps_tofiles" value="В файлы" />
    <input type="submit" name="submit_deps_tomails" value="Отправить по почте" />
    </td>
    </tr>
</form>	
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!-- Доступ к отчетам за год и месяц только для определенных пользователей -->
<?php            
        }
}
?>
<!-- Доступ к отчетам за год и месяц только для определенных пользователей -->
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<form method="GET" action="" >
	<tr>
    <td valign="top" style="alighn:top">
    <a class="podskazka" >Отчет по лицевому счету<span>
    Формирует отчет по лицевому счету Управления за месяц указанного года.</span></a>
    </td> 
	<td>
		<select name="dep" value="<?php echo $dep ?>">
			<?php
			// Запрос, чтобы показать все Управления
			$query = "SELECT * FROM `department` WHERE limit_all != '0,00' ORDER BY `contract` ASC LIMIT 1000";
			$result = mysql_query($query);
			while ($data = mysql_fetch_array($result))
			{
			// Каждое управление, которое считывается из таблицы вставляется в <option> тегом </ опции>
			echo "<option value='".$data['contract']."'>".$data['dep']."</option>";
			}
			?>
		</select>
		
		<br />
		<select name="month" value="<?php echo $month ?>">
			<option value="01">01. Январь</option>
			<option value="02">02. Февраль</option>
			<option value="03">03. Март</option>
			<option value="04">04. Апрель</option>
			<option value="05">05. Май</option>
			<option value="06">06. Июнь</option>
			<option value="07">07. Июль</option>
			<option value="08">08. Август</option>
			<option value="09">09. Сентябрь</option>
			<option value="10">10. Октябрь</option>
			<option value="11">11. Ноябрь</option>
			<option value="12">12. Декабрь</option>
		</select>
		<a class="podskazka" ><input type="text" name="year" value="<?php echo $year ?>"/><span>
  Необходимо выбрать "Управление", "Месяц" и ввести данные о годе в формате: 20хх (например 2013).<br /><br />"Ок" - для вывода на экран.<br /> "В файл" - для сохранения результата в файле.</span></a> годa
    </td>
    <td>
    <input type="submit" name="submit_dep" value="Ok" />
    
    </td>
    <td>
    <input type="submit" name="submit_dep_tofile" value="В файл" />
    <input type="submit" name="submit_dep_tomail" value="Отправить" /></td>
    </tr>
</form>	
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->

<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<form method="GET" action="" >
	<tr>
    <td valign="top" style="alighn:top"><a class="podskazka" >Отчет по номеру<span>Формирует отчет по номеру за выбранный месяц и год.</span></a></td> 
	<td><a class="podskazka" ><input type="text" name="number" value="<?php echo $number ?>"/><span>
  Необходимо ввести существующий телефонный номер в формате <b>"5хххх"</b>, затем выбрать "Месяц" и ввести данные о годе в формате: 20хх (например 2013).<br /><br />"Ок" - для вывода на экран.<br /> "В файл" - для сохранения результата в файле.</span></a> за

		<select name="month" value="<?php echo $month ?>">
			<option value="01">01. Январь</option>
			<option value="02">02. Февраль</option>
			<option value="03">03. Март</option>
			<option value="04">04. Апрель</option>
			<option value="05">05. Май</option>
			<option value="06">06. Июнь</option>
			<option value="07">07. Июль</option>
			<option value="08">08. Август</option>
			<option value="09">09. Сентябрь</option>
			<option value="10">10. Октябрь</option>
			<option value="11">11. Ноябрь</option>
			<option value="12">12. Декабрь</option>
		</select>
		<a class="podskazka" ><input type="text" name="year" value="<?php echo $year ?>"/><span>
  Необходимо ввести существующий телефонный номер в формате <b>"5хххх"</b>, затем выбрать "Месяц" и ввести данные о годе в формате: 20хх (например 2013).<br /><br />"Ок" - для вывода на экран.<br /> "В файл" - для сохранения результата в файле.</span></a> годa
    </td>
    <td>
    <input type="submit" name="submit_number" value="Ok" />
    </td>
    <td>
    <input type="submit" name="submit_number_tofile" value="В файл" />
    </td>
    </tr>
</form>	
<!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<form method="GET" action="" >
    <tr>
	<td><input type="reset" name="reset" value="Сбросить" /></td>    
    <td></td>
    <td></td>
    </tr>

 </table>
</form>
<table width="100%" border="0">
  <tr>
    <td bgcolor="" height="50px">&nbsp;</td>
  </tr>
</table>
<?php
//Получение данных из формы, через переменные <input type="text" name="year" и т.д.>
//Год
$year = trim($_GET['year']);
$year = isset($year) ? htmlspecialchars($year) : '';
#$year = isset($year) ? mysql_escape_string($year) : '';
//Месяц
$month = ($_GET['month']);
$month = isset($month) ? htmlspecialchars($month) : '';
#$month = isset($month) ? mysql_escape_string($month) : '';
//Управление
$dep = ($_GET['dep']);
$dep = isset($dep) ? htmlspecialchars($dep) : '';
#$dep = isset($dep) ? mysql_escape_string($dep) : '';
//Год
//дописать обработчик удаления символов, например "-", "()"
$number = trim($_GET['number']);
$number = isset($number) ? htmlspecialchars($number) : '';
#$number = isset($number) ? mysql_escape_string($number) : '';
$number = str_replace("-","",$number);
$number = str_replace("(","",$number);
$number = str_replace(")","",$number);
$number = str_replace("+","",$number);
//////////////////////////////////////////////////////////////////////////////////////////////////////		
//Проверка на нажатие кнопки
if (($_SERVER['REQUEST_METHOD'] == 'GET'))
{

	//Обработка ввода строки с годом для формирования названия таблицы
	if ( strlen($year) == 2)
		{  
		//вписываем в переменную полную дату года, если было введено, например - 12, то год будет выглядеть = 2012
		$traffic_table = 'traffic_'.($year+2000).$month;
		}
		else
		{
		$traffic_table = 'traffic_'.($year).$month;    
		}
			
	//вписываем в переменную последние 2 цифры года, для полноценного поиска по году = .%2012 => .%12
	if ( strlen($year) == 4)
		{  
		$year = substr($year, -2);
		}

	//вписываем в переменную последние 3 цифры Лицевого счета, для полноценного поиска по ЛС = %10901% => .%901%    
	if ( strlen($dep) >= 5)
		{  
		$dep = substr($dep, -3);
		}    

/////////////////////////////////////////////////////////////////////////////////////////////////////
//Проверка на пустые поля
/////////////////////////////////////////////////////////////////////////////////////////////////////  
	if ((empty($year)) and (empty($month)) and (empty($dep)) and (empty($number)))
			{ 
			echo 'Пожалуйста, заполните поля.<br>'; 
			}
/////////////////////////////////////////////////////////////////////////////////////////////////////
//Вывод отчета на экран за год
////////////////////////////////////////// 

     if(isset($_GET['submit_year']))
     {  
        if (isset($year) and ($year != ''))	
            {
                echo func_rep_year($year,$traffic_table);
            }
     }   
////////////////////////////////////////// 
//Вывод отчета в файл за год
//////////////////////////////////////////       	
     if(isset($_GET['submit_year_tofile']))
     {           
        if ( isset($year) and ($year != '') )	
        	{
        		echo $save = func_rep_year_tofile($year,$traffic_table);
        	} 
     }     
//////////////////////////////////////////
// Вывод отчета за месяц
////////////////////////////////////////// 
     if(isset($_GET['submit_month']))
     {              
        if (isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_month($month,$year,$traffic_table);
                #echo '<pre>';
            }
     }   
//////////////////////////////////////////
// Вывод отчета за месяц в файл
////////////////////////////////////////// 
     if(isset($_GET['submit_month_tofile']))
     {              
        if (isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_month_tofile($month,$year,$traffic_table);
                #echo '<pre>';
            }
     }
	 
//////////////////////////////////////////
// Вывод отчета за месяц по всем управлениям в файлы
////////////////////////////////////////// 
     if(isset($_GET['submit_deps_tofiles']))
     {              
        if (isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_deps_tofiles($month,$year,$traffic_table);
                #echo '<pre>';
            }
     }	

//////////////////////////////////////////
// Вывод отчета за месяц по всем управлениям ПО ПОЧТЕ
////////////////////////////////////////// 
     if(isset($_GET['submit_deps_tomails']))
     {              
        if (isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_deps_tomails($month,$year,$traffic_table);
                #echo '<pre>';
            }
     }	     
     
     
//////////////////////////////////////////
// Вывод отчета за месяц по Управлению   
////////////////////////////////////////// 
     if(isset($_GET['submit_dep']))
     {              
        if (isset($dep) and ($dep != '') and isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_dep($dep,$month,$year,$traffic_table);
                #echo '<pre>';
            }
     }
//////////////////////////////////////////
// Вывод отчета за месяц по Управлению в файл
////////////////////////////////////////// 
     if(isset($_GET['submit_dep_tofile']))
     {              
        if (isset($dep) and ($dep != '') and isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_dep_tofile($dep,$month,$year,$traffic_table);
                #echo '<pre>';
            }
     }
//////////////////////////////////////////
// Отправляет отчет за месяц по Управлению согласно листа рассылки из БД
////////////////////////////////////////// 
     if(isset($_GET['submit_dep_tomail']))
     {              
        if (isset($dep) and ($dep != '') and isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_dep_tomail($dep,$month,$year,$traffic_table);
                #echo '<pre>';
            }
     }    

//////////////////////////////////////////  
// Вывод отчета за месяц по Номеру
//////////////////////////////////////////
     if(isset($_GET['submit_number']))
     {              
        if (isset($number) and ($number != '') and isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_number($number,$month,$year,$traffic_table);
                #echo '<pre>';
            }
     }
//////////////////////////////////////////
// Вывод отчета за месяц по Номеру в файл
////////////////////////////////////////// 
     if(isset($_GET['submit_number_tofile']))
     {              
        if (isset($number) and ($number != '') and isset($month) and ($month != '') and isset($year) and ($year != ''))	
            {
                #echo '<pre>';
                echo func_rep_number_tofile($number,$month,$year,$traffic_table);
                #echo '<pre>';
            }
     }
//////////////////////////////////////////    
/////////////////////////////////////////////////////////////////////////////////////////////////////
}


?>
<table width="100%" border="0">
  <tr>
    <td bgcolor="" height="100px">&nbsp;</td>
  </tr>
</table>