<?php
session_start();
define ( 'READFILE', true );
?>

<?php
include ("$_SERVER[DOCUMENT_ROOT]/template/head.php");
include ("$_SERVER[DOCUMENT_ROOT]/auth/auth.php");
include ("$_SERVER[DOCUMENT_ROOT]/template/main_menu.php");
?>

<div class="content">
<?php
if (isset($_SESSION['user_id']))
{   
    $rights = $access;
        if ($rights == '1' || $rights == '2' || $rights == '3' || $rights == '5')
        {
            // показываем защищенные данные.
            include ("$_SERVER[DOCUMENT_ROOT]/content/content_reports.php");
            
        }
        else
        {
            echo 'У вас недостаточно прав для доступа к данной странице!';
            
        }
}
else
{
	die('Доступ закрыт, даём ссылку на авторизацию. — <a href="./login.php">Авторизоваться</a>');
}	
?>
<!-- end .content --></div>

<?php

include ("$_SERVER[DOCUMENT_ROOT]/template/head_end.php");
?>