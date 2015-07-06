<?php
///////////////////////////////////////////////////////////
// SQL запрос
///////////////////////////////////////////////////////////
function func_sql_zapros($sql_zapros)
{
    $query = $sql_zapros;
    $result = mysql_query($sql_zapros)or die( mysql_error());    
    while ($a = mysql_fetch_assoc($result))
    {    
        print_r($a);   
    }
}
///////////////////////////////////////////////////////////
// Назначение прав доступа
///////////////////////////////////////////////////////////
function func_submit_rights($login,$rights,$sessoin_id)
{
    $query = "
    UPDATE users SET rights= '".$rights."' WHERE login= '".$login."'
    ";
    $result = mysql_query($query) or die( mysql_error());
    $access = mysql_result(mysql_query("SELECT description FROM access WHERE access.access = '".$rights."'"),0) or die( mysql_error());
    echo 'Пользователю '.$login.' были назначены права доступа - '.$access;
}
///////////////////////////////////////////////////////////
// Удаление пользователя
///////////////////////////////////////////////////////////
function func_delete_user($login)
{
    $query = "
    DELETE FROM `users` WHERE  login= '".$login."'
    ";
    $result = mysql_query($query) or die( mysql_error());
    echo 'Пользователь '.$login.' был удален из базы данных';
}
///////////////////////////////////////////////////////////
// Смена почтового ящика
///////////////////////////////////////////////////////////
function func_change_mail($newmail, $contract) {
$query = "UPDATE `department` SET `mail`='".$newmail."' WHERE  `contract`='".$contract."' ";
$result = mysql_query($query) or die( mysql_error());
echo 'Управлению '.$contract.' был назначен E-mail - '.$newmail;
}

?>