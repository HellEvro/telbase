<?php
session_start();
include("$_SERVER[DOCUMENT_ROOT]/config/mysql.php");
include("$_SERVER[DOCUMENT_ROOT]/content/func_base.php");
?>

<?php
/////////////////////////////////////////////////////////////////////////
$number = '';
$contract = '';
$caller_name = '';
$address = '';

//Телефонный номер
$number = trim($_GET['number']);
$number = isset($number) ? htmlspecialchars($number) : '';
$number = isset($number) ? mysql_escape_string($number) : '';
$number = str_replace("-","",$number);
$number = str_replace("(","",$number);
$number = str_replace(")","",$number);
$number = str_replace("+","",$number);

//Управление
$contract = ($_GET['contract']);

// ФИО Абонента
$caller_name = trim($_GET['caller_name']);
$caller_name = isset($caller_name) ? htmlspecialchars($caller_name) : '';

//Место установки
$address = trim($_GET['address']);
$address = isset($address) ? htmlspecialchars($address) : '';
/////////////////////////////////////////////////////////////////////////
?>

<h2>База данных АИС СУЗ "ТС" ООО "РН-Пурнефтегаз"</h2>
<h3>Поиск по:</h3>
    
    <table cellspacing="12px" bgcolor="#FFA200">
    
	<form method="GET" action="" enctype="">
    <tr>
    <td><a class="podskazka" >Телефонный номер<span>Осуществляет поиск в БД по номеру телефона. Введите телефонный номер в формате 5хххх.<br /></span></a></td>
    <td>: <input type="text" name="number" value="<? echo $number ?>"/><input type="submit" name="submit_number" value="Найти" /></td>
    </tr>
	</form>
    
    <form method="GET" action="" enctype="">
    <tr>	
    <td><a class="podskazka" >Абонент (ФИО)<span>Осуществляет поиск в БД по Ф.И.О. абонента<br /></span></a></td>
    <td>: <input type="text" name="caller_name" value="<? echo $caller_name ?>"/><input type="submit" name="submit_caller_name" value="Найти" /></td>
    </tr>
	</form>
    
    <form method="GET" action="" enctype="">
    <tr>
    <td><a class="podskazka" >Место установки<span>Осуществляет поиск в БД по месту установки (подключения, расположения) телефонного номера<br /></span></a></td>
    <td>: <input type="text" name="address" value="<? echo $address ?>"/><input type="submit" name="submit_address" value="Найти" /></td>
    </tr>	
    </form>
    
    <form method="GET" action="" enctype="">
    <tr>
    <td><a class="podskazka" >Управление <span>Осуществляет вывод всех телефонных номеров принадлежащих одному из выбранных управлений с подробной информацией об Абоненте<br /></span></a></td>
    <td>: <select name="contract" value="<? echo $contract ?>">
        <?php
        // Запрос, чтобы показать все Управления
        $query = "SELECT * FROM `department` WHERE limit_all != '0' ORDER BY `contract` ASC LIMIT 1000";
        $result = mysql_query($query);
        while ($data = mysql_fetch_array($result))
        {
        // Каждое управление, которое считывается из таблицы вставляется в <option> тегом </ опции>
        echo "<option value='".$data['contract']."'>".trim($data['dep'])."</option>";
        }
        ?>
    </select><input type="submit" name="submit_contract" value="Найти" />
    </td>
    </tr>
    </form>
    
    <form method="GET" action="" enctype="">
    <tr>
    <td><a class="podskazka" >Показать лимиты?<span>Показывает информацию по действующим лимитам на связь в разрезе Управлений<br /></span></a> </td>
    <td>: <input type="submit" name="submit_limits" value="Показать" /></td>
    <td></td>
    </tr>
    </form>	
		
    </table>

<br />

<?php
//////////////////////////////////////////	

if (($_SERVER['REQUEST_METHOD'] == 'GET'))
{
    if (isset($_GET['submit_number']) or isset($_GET['submit_contract']) or isset($_GET['submit_caller_name']) or isset($_GET['submit_address']))
    {
        if ((empty($number)) and (empty($contract)) and (empty($caller_name)) and (empty($address)))
            	{ 
            	echo 'Поля не заполнены!<br>'; 
                }           
     }

        //////////////////////////////////////////
     if(isset($_GET['submit_number']))
     {              
        if (isset($number) and ($number != ''))	
            {
                echo '<pre>';
                echo func_Number($number);
                echo '<pre>';
            }
     }   
        //////////////////////////////////////////    	
     if(isset($_GET['submit_contract']))
     {           
        if ( isset($contract) and ($contract != '') and ($contract != '0'))	
        	{
        		echo '<pre>';
        		echo func_Contract($contract);
        		echo '<pre>';
        	} 
     }     
        //////////////////////////////////////////
     if(isset($_GET['submit_limits']))
     {            
            	echo '<pre>';
                echo func_Limits();
                echo '<pre>';			
     }   
        //////////////////////////////////////////	
     if(isset($_GET['submit_caller_name']))
     {             
        if ( isset($caller_name) and ($caller_name != ''))
        	{	
            	echo '<pre>';
                echo func_Caller_name($caller_name);
                echo '<pre>';			
            } 
     }   
        //////////////////////////////////////////
     if(isset($_GET['submit_address']))
     {         
        if ( isset($address) and ($address != ''))
        	{	
            	echo '<pre>';
                echo func_Address($address);
                echo '<pre>';			
            } 
     }      

//////////////////////////////////////////
}
?>
<table width="100%" border="0">
  <tr>
    <td bgcolor="" height="100px">&nbsp;</td>
  </tr>
</table>