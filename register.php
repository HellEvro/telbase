<?php
session_start();
?>

<?php
include ("$_SERVER[DOCUMENT_ROOT]/template/head.php");
include ("$_SERVER[DOCUMENT_ROOT]/auth/auth.php");
include ("$_SERVER[DOCUMENT_ROOT]/template/main_menu.php");
?>

<div class="content"  align="center">
<?php
include ("$_SERVER[DOCUMENT_ROOT]/content/content_register.php");	
?>
<!-- end .content --></div>

<?php

include ("$_SERVER[DOCUMENT_ROOT]/template/head_end.php");
?>