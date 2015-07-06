<?php 
session_start();
include("$_SERVER[DOCUMENT_ROOT]/config/mysql.php");
include("$_SERVER[DOCUMENT_ROOT]/content/func_data.php");
?>

<?php 
////////// ПЕРЕМЕННЫЕ ФОРМЫ ЗАПРОСА////////////////
$company = $_POST['company'];
$month = $_POST['month'];
$year = $_POST['year'];
//Код города для удаления в файлах Ростелекома 
$regcode = '34936';
$upload_dir = "$_SERVER[DOCUMENT_ROOT]/reports_in/";
$table_name = $company.'_'.$year.''.$month ;
$traffic_table = 'traffic_'.$year;
?>

<table cellspacing="12px">
<tr>	
<td valign="top">

<h3>1. Загрузка данных из файла в базу данных</h3>
<form method="POST" enctype="multipart/form-data">
 <table cellspacing="12px" bgcolor="#FFA200">
    	
    <tr>	
    <td>Формат файла от</td>
    <td>: 
		 <select name="company" value="<? echo $company ?>">
			<option value="pursatcom">ООО "Пурсатком"</option>
			<option value="rostelecom">ОАО "Ростелеком"</option>
			<option value="rninform">ООО "РН-Информ"</option>
		</select>    
    </td>
    </tr>
	
    <tr>
    <td>За месяц</td>
    <td>:
		 <select name="month" value="<? echo $month ?>">
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
    </td>
    </tr>
    
    <tr>
    <td>Год</td>
    <td>:
        <input type="text" name="year" placeholder="Введите год 20хх" value="<? echo $year ?>"/>
    </td>
    </tr>    
    
    <tr>
    <td>Выбрать файл</td>
    <td>:
        <input type="file" name="path" value="<? echo $path ?>"/>
    </td>
    </tr>  	
	
	<tr>
    <td></td>
    <td><input type="submit" name="save_to_bd" value="Загрузить файл"/></td>
    </tr>
		
 </table>
</form>

<br/>
<h3>2. Формирование данных за месяц</h3>
<form method="POST" enctype="multipart/form-data">
 <table cellspacing="12px" bgcolor="#FFA200">

	<tr>
    <td>
    <select name="month" value="<? echo $month ?>">
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
    <input type="text" name="year" placeholder="Введите год 20хх" value="<? echo $year ?>"/>&nbsp;Год&nbsp;
    </td>
    <td><input type="submit" name="save_to_table_month" value="Сформировать" /></td>
    </tr>
 </table>    
</form>

<br/>

<h3>3. Формирование данных за год</h3>
<form method="POST" enctype="multipart/form-data">
<table cellspacing="12px" bgcolor="#FFA200">

	<tr>
    <td>  
    <input type="text" name="year" placeholder="Введите год 20хх" value="<? echo $year ?>"/>&nbsp;Год&nbsp;
    </td>
    <td><input type="submit" name="save_to_table_year" value="Сформировать" /></td>
    </tr>
 </table>    
</form>

<br/>

<h3>4. Добавление в Базу новых телефонных номеров</h3>
<form method="POST">
    <table cellspacing="12px" bgcolor="#FFA200">
        <tr>
        <td>Добавить новые номера?&nbsp;<input type="submit" name="phone_add" value="Да" /></td>
        </tr>        
    </table> 
</form>
 
<br/> 
 
<h3>5. Обновление базы телефонных номеров</h3>
<form method="POST">
    <table cellspacing="12px" bgcolor="#FFA200">
        <tr>
            <td>5. Обновить базы телефонных номеров?&nbsp;<input type="submit" name="phone_update" value="Да" /></td>
        </tr>        
    </table> 
</form>

</td>


<td bgcolor="#0080C0" style="color:white;" width="50%" valign="top">

    <table cellspacing="12px" align="top">
        <tr>
            <td align="center"><h2>Инструкция:</h2></td>
        </tr> 
        <tr>
            <td align="justify">1. Выберите в разделе №1 (Загрузка данных из файла в базу данных) - тип файла, месяц и год (в формате 20хх, например 2013). Данные должны соответствовать типу и отчетному периоду загружаемого файла</td>
        </tr>   
        <tr>
            <td align="justify">2. Нажмите в разделе №1 (Загрузка данных из файла в базу данных) - "Загрузить файл". В случае неправильного выполнения пункта №1 появится ошибка, файл не будет загружен</td>
        </tr>  
        <tr>
            <td align="justify">3. Повторите действия описанные в п.1-2 данной инструкции для всех типов файлов данных (ООО "Пурсатком", ОАО "Ростелеком", ООО "РН-Информ")  </td>
        </tr> 
        <tr>
            <td align="justify">4. [Для каждого месяца] После загрузки всех файлов за один отчетный период, в разделе №2 (Формирование данных за месяц) выберите месяц и укажите год. Нажмите кнопку "Сформировать" </td>
        </tr> 
        <tr>
            <td align="justify">5. После формирования данных по месяцам, необходимо сформировать текущие данные за год. Для этого в разделе №3 (Формирование данных за год) введите в пустое поле значение года в формате {20хх}, например - 2013, и нажмите кнопку "Сформировать". Данный пункт необходимо повторять после загрузки и формирования в БД очередных данных за месяц. </td>
        </tr>     
        <tr>
            <td align="justify">6. Для обновления данных о телефонах Компании, можно нажать на кнопку "Да" в разделе №4 (Обновление базы телефонных номеров). Данные берутся из таблицы, сформированной по отчетам ООО "Пурсатком"</td>
        </tr>                                            
        <tr>
            <td align="center"><h3>Важно!</h3> Для правильного формирования базы данных, необходимо соблюдать данную инструкцию. Порядок действий очень важен.</td>
        </tr>     
    </table> 
    

</td>
</tr>        
</table> 
<?php
////////// Проверка POST!!! ////////////////////////
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if ( strlen($year) == 2)
        {  
        //вписываем в переменную полную дату года, если было введено, например - 12, то год будет в итоге выглядеть 2012
        $traffic_table = 'traffic_'.($year+2000).$month;
        }
        else
            {
            $traffic_table = 'traffic_'.($year).$month;    
            }
    //Если нажата кнопка загрузки файла
    if (isset($_POST['save_to_bd'])) 
    {
        //удаляем старую таблицу, если уже была добавлена
        $query_empty = mysql_query("DROP TABLE IF EXISTS ".$table_name." ") 
                        or die('Ошибка при удалении таблицы '.$table_name.' - ' . mysql_error());;
        echo '<br />База очищена от таблицы: '.$table_name. '<br />';
        ////////// Переменные //////////////////////////  
        $filename = $_FILES["path"]["name"];  
        ////////// ЗАГРУЗКА ФАЙЛА НА СЕРВЕР /////////////
        func_load_data($upload_dir);   
        // запускаем парсер $afields массива данных от загруженного файла
        $afields= file($upload_dir.$filename);
        
        if ($company == 'pursatcom')  //Если файл от Пурсатком
            {
                $afields = func_pursatcom_clean_array($afields); 
                $afields = func_pursatcom_header_set($afields); 
            }    
        if ($company == 'rostelecom')  
            {
                $afields = func_rostelecom_clean_array($afields,$regcode); //Если файл от Ростелеком
                $afields = func_rostelecom_header_set($afields,$regcode);
            }
        if ($company == 'rninform')  
            {
                $afields = func_rninform_clean_array($afields); //Если файл от РН-Информ
                $afields = func_rninform_header_set($afields);
            }      
           
             
        ///////////////// Создаем таблицу ////////////////
        func_create_table($table_name,$afields,$company,$regcode);
        ///////////////// Вносим данные //////////////////  
        func_import_csv($table_name,$filename,$upload_dir,$db,$afields,$company,$regcode);
    } 
    //Если нажата кнопка формирования данных за месяц.
    if (isset($_POST['save_to_table_month'])) 
    {   
         echo 'Вы выбрали период: ' .$month. '.' .$year;
         if ($year != '' and $month != '')
            {    
                func_merge_tables_month($year,$month);
            }
            else 
            {
                echo '<br/>Введите данные полностью';
            }
    }
    
    //Если нажата кнопка формирования данных за год
    if (isset($_POST['save_to_table_year'])) 
    {   
        echo 'Формирование данных за: ' .$year. ' год...';
         
         if ($year != '')
            {   
                func_create_year_traffic_table($year,$traffic_table);
                func_merge_tables_year($year,$traffic_table);
            }
            else 
            {
                echo '<br/>Введите данные полностью';
            }
    }
    // Обновление телефонного справочника, если нажата кнопка обновления базы номеров 
    if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['phone_update'])) 
    {   
      func_phone_update();
    }
    // Добавление номеров в телефонный справочник, если нажата кнопка добавления номеров в БД 
    if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['phone_add'])) 
    {   
      func_phone_add();
    }    
}
$query = "SELECT num1 FROM phone num1 phone num2 WHERE num1.number = num2.number";
$result = mysql_query ($query);
?> 