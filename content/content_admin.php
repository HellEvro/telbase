<?php 
session_start();
include("$_SERVER[DOCUMENT_ROOT]/config/mysql.php");
include("$_SERVER[DOCUMENT_ROOT]/content/func_admin.php");
?>
<!--************************************************************************************************-->
<h3>Управление базой данных</h3>
<form method="POST" enctype="multipart/form-data">
<table cellspacing="12px" bgcolor="#FFA200">	
    <tr>	
        <td>Введите SQL запрос</td>
        <td>: 
        <input type="text" align="left" size="100%" name="sql_zapros" placeholder="Любой SQL запрос" value="<?php echo $sql_zapros ?>"/>
        </td>
        <td> 
        <input type="submit" name="submit_sql_zapros" title="ОК" value="ОК"/>
        </td>
    
        </tr>
    	
    </tr>	
</table>
</form>

<br/>
<!--************************************************************************************************-->
<h3>Установка прав доступа </h3>
<form method="POST" action="" enctype="">
<table cellspacing="12px" bgcolor="#FFA200"> 
    <tr>
        <td>Пользователь: 
        <select name="login" value="<?php echo $login ?>">
            <?php
            // Запрос, чтобы показать все Управления
            $query = "SELECT * FROM `users` ORDER BY `id` ASC LIMIT 1000";
            $result = mysql_query($query);
            while ($data = mysql_fetch_array($result))
            {
            // Каждое управление, которое считывается из таблицы вставляется в <option> тегом </ опции>
            echo "<option value='".$data['login']."'>".trim($data['login'])."</option>";
            }
            ?>
            </select>

        </td>   
        <td>Права 
            <select name="rights" value="<?php echo $rights ?>">
            <?php
            // Запрос, чтобы показать все Управления
            $query1 = "SELECT * FROM `access` ORDER BY `access` ASC LIMIT 1000";
            $result1 = mysql_query($query1);
            while ($data = mysql_fetch_array($result1))
            {
            // Каждое управление, которое считывается из таблицы вставляется в <option> тегом </ опции>
            echo "<option value='".$data['access']."'>".trim($data['description'])."</option>";
            }
            ?>
            </select> 
            <input type="submit" name="submit_rights" value="Установить" />
        </td>        
    </tr>
</table>
</form>

<br/>
<!--************************************************************************************************-->
<h3>Смена E-mail адреса Управления </h3>
<form method="POST" action="" enctype="">
<table cellspacing="12px" bgcolor="#FFA200"> 
    <tr>
        <td>Управление: 
        <select name="contract" value="<?php echo $contract ?>">
            <?php
            // Запрос, чтобы показать все Управления
            $query_mail = "SELECT * FROM `department` WHERE limit_ALL !='0,00' ORDER BY `id` ASC LIMIT 1000";
            $result_mail = mysql_query($query_mail);
            while ($data_mail = mysql_fetch_array($result_mail))
            {
            // Каждое управление, которое считывается из таблицы вставляется в <option> тегом </ опции>
            echo "<option value='".$data_mail['contract']."'>".trim($data_mail['dep'])." // E-mail: ".$data_mail['mail']."</option>";
            }
            ?>
            </select>

        </td>
        <tr>   
        <td>Новый E-mail: 
            <input type="text" align="left" size="30%" name="newmail" placeholder="Введите новый E-mail" value="@purneftegaz.ru"/>
            <input type="submit" name="submit_change_mail" value="Сменить" />
        </td>   
        </tr>     
    </tr>
</table>
</form>
<!--************************************************************************************************-->    
<br/>
    
<h3>Удаление пользователя</h3>
<form method="POST" enctype="multipart/form-data">
<table cellspacing="12px" bgcolor="#FFA200"> 	
<tr>	
        <td>Выберите пользователя: 
        <select name="login" value="<?php echo $login ?>">
            <?php
            // Запрос, чтобы показать все Управления
            $query = "SELECT * FROM `users` ORDER BY `id` ASC LIMIT 1000";
            $result = mysql_query($query);
            while ($data = mysql_fetch_array($result))
            {
            // Каждое управление, которое считывается из таблицы вставляется в <option> тегом </ опции>
            echo "<option value='".$data['login']."'>".'('.$data['id'].') '.trim($data['login'])."</option>";
            }
            ?>
            </select>

        </td>
    <td> 
    <input type="submit" name="submit_delete_user" value="Удалить"/>
    </td>

    </tr>
</tr> 
</table>
</form> 
<!--************************************************************************************************-->  

<?php
////////// ПЕРЕМЕННЫЕ ФОРМЫ ЗАПРОСА////////////////
$sql_zapros = $_POST['sql_zapros'];
$sql_zapros = mysql_escape_string($sql_zapros);

$newmail = $_POST['newmail'];
$newmail = mysql_escape_string($newmail);
$contract = $_POST['contract'];

$login = $_POST['login'];

$rights = $_POST['rights'];

$sessoin_id = $_SESSION['user_id'];
?>

<?php


////////// Проверка POST!!! ////////////////////////
if (($_SERVER['REQUEST_METHOD'] == 'POST')) 
{
    if(isset($_POST['submit_sql_zapros']))
     {              
        if (isset($sql_zapros))	
            {
                echo '<pre>';
                echo func_sql_zapros($sql_zapros);
                echo '<pre>';
            }
     }  
    
    if(isset($_POST['submit_rights']))
     {            

                echo '<pre>';
                echo func_submit_rights($login,$rights,$sessoin_id);
                echo '<pre>';
     }       
    
    if(isset($_POST['submit_delete_user']))
     {            

                echo '<pre>';
                echo func_delete_user($login);
                echo '<pre>';
     }    
     
    if(isset($_POST['submit_change_mail']))
     {            

                echo '<pre>';
                echo func_change_mail($newmail,$contract);
                echo '<pre>';
     }       
} 
?> 