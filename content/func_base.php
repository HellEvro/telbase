<?php
////////////////////////////////////////////////////////////////
//Показать информацию по номеру
////////////////////////////////////////////////////////////////
function func_Number($number)
{
    $query = ("
		SELECT * FROM phone,department 
		WHERE number LIKE '%".$number."%' 
		AND dep = (SELECT dep FROM department WHERE phone.contract=department.contract)
        ;") 
        or die('Запрос не удался - ' . mysql_error());
        echo  '<h1>По запросу - '.$_GET['number'].', найдено:</h1>';
        $result = mysql_query($query);
    if (mysql_num_rows($result) > 0)
        {   
			echo '
    			<table border="1" width="100%">
    			<tr>
    			<td width="40%" align="center"><b>Управление</b></td>
    			<td width="30%" align="center"><b>Абонент</b></td>
    			<td width="10%" align="center"><b>Номер</b></td>
    			<td width="10%" align="center"><b>Дата</b></td>
    			<td width="10%" align="center"><b>Тип</b></td>
    			</tr>
    			';
            $num = mysql_num_rows($result); 
            for($i=0;$i<$num;$i++)
            {
            $row = mysql_fetch_array($result);            
    			echo '
    			<tr>
    			<td align="left">'  .trim($row['dep']).'</td>
    			<td align="left">'.$row['caller_name'].'</td>
    			<td align="center">'.$row['number'].'</td>
    			<td align="center">'.$row['date'].'</td>
    			<td align="center">'.$row['type'].'</td>
    			</tr>';
            }    
                echo '</table>';
        }
        else
			{
				echo 'Телефонного номера <b>'.$number.'</b> нет в базе';
			} 
}
////////////////////////////////////////////////////////////////
//Показать всю информацию по Лицевому счету
////////////////////////////////////////////////////////////////
function func_Contract($contract)
{       
    $query = ("
        SELECT * FROM phone,department 
        WHERE phone.contract LIKE '%".$contract."%' 
        AND phone.contract != '0'
        AND department.dep = (
        SELECT dep 
        FROM department 
        WHERE phone.contract=department.contract)
        ;") 
        or die('Запрос2 не удался - ' . mysql_error());
        $result = mysql_query($query);
        $dep = mysql_fetch_array($result);
		echo '<h2>'.trim($dep['dep']).':</h2>'; 
        $result = mysql_query($query);
        if ($result > 0)
    	{	
    		echo '
    		<table border="1" width="100%">
    		<tr>
    		<td width="20%" align="center"><b>Номер</b></td>
    		<td width="50%" align="center"><b>Абонент</b></td>
    		<td width="30%" align="center"><b>Место установки</b></td>
    		</tr>
            ';
            $num = mysql_num_rows($result); 
            for($i=0;$i<$num;$i++)
            {
                $row = mysql_fetch_array($result);
        		echo '
        		<tr>
        		<td align="center">'.$row['number'].'</td>
        		<td align="left">'.$row['caller_name'].'</td>
        		<td align="center">'.$row['address'].'</td>
        		</tr>';
    		}
            echo '</table>';
        }
        else
            {
                echo 'Телефонных номеров на данном лицевом счете '.$dep['dep'].' нет.';
            }
}        
////////////////////////////////////////////////////////////////
//Показать номер по ЛС
////////////////////////////////////////////////////////////////
function func_Number_Contract($number,$contract)
{
	$query = mysql_query(" 
        SELECT * 
        ROM phone,department
        WHERE number = (
        SELECT number 
        FROM phone 
        WHERE phone.contract=department.contract 
        AND phone.number LIKE '%".$number."%')
        ;") 
        or die('Запрос3 не удался - ' . mysql_error());
	   $result = mysql_fetch_array($query);
    if (mysql_num_rows($query) > 0)
		{
		if ($result['contract'] == $contract)
			{
			echo 'Да, телефонный номер <b>'.$number.'</b> принадлежит ЛС - <b>' .trim($result['dep']).'</b>';	
			}
			elseif ( ($result['contract'] != $contract) && ($result['number'] == $number))
				{ 
				echo '<b>Нет</b>, телефонный номер <b>'.$number.'</b> принадлежит ЛС - <b>' .trim($result['dep']).'</b>';
				}
			elseif ( ($result['contract'] != $contract) && ($result['number'] != $number))
				{
					echo 'Телефонного номера <b>'.$number.'</b> нет в базе';
				} 	
        }
}
////////////////////////////////////////////////////////////////
//Показать лимиты
////////////////////////////////////////////////////////////////
function func_Limits()
{
    $query = (" 
    
    SELECT * FROM department 
    WHERE  limit_all != '0,00'
    
    ;")   
    or die('Запрос4 не удался - ' . mysql_error());
    
    $result = mysql_query($query);
    
    if ($result > 0)
	{
    	echo '<h1>Все лимиты по управлениям: </h1>';		
    	echo '			
    	<table border="1" width="100%">
    	<tr>
    	<td width="10%" align="center">Лицевой счет</td>
    	<td width="60%" align="center">Управление</td>
    	<td width="15%" align="center">Лимиты Общие</td>
    	<td width="15%" align="center">Лимиты АМТС</td>
    	</tr>
    	';
        
        $num = mysql_num_rows($result); 
        for($i=0;$i<$num;$i++)
        {
            $row = mysql_fetch_array($result);
            echo '
        	<tr>
        	<td align="left">'.$row['contract'].'</td>
        	<td align="left">'  .trim($row['dep']).'</td>
            <td align="right">'.$row['limit_all'].'</td>
        	<td align="right">'.$row['limit_AMTS'].'</td>
        	</tr>
            ';
        }   
        echo '</table>';           
    }           
}
////////////////////////////////////////////////////////////////
function func_Caller_name ($caller_name)
{
    $query = ("  
		SELECT * FROM phone,department 
		WHERE LOWER(caller_name) LIKE '%".strtolower($caller_name)."%'
		AND dep = (SELECT dep FROM department WHERE phone.contract=department.contract) 
		;") or die('Запрос не удался - ' . mysql_error());
        echo  '<h1>По запросу - '.$_GET['caller_name'].', найдено:</h1>';
        $result = mysql_query($query);
    if ( isset($result))
        {	 
	    echo '
		<table border="1" width="100%">
		<tr>
		<td width="40%" align="center"><b>Управление</b></td>
		<td width="40%" align="center"><b>Абонент</b></td>
        <td width="20%" align="center"><b>Номер</b></td>
		<td width="20%" align="center"><b>Место установки</b></td>
		</tr>
		';
        $num = mysql_num_rows($result); 
        for($i=0;$i<$num;$i++)
            {
                $row = mysql_fetch_array($result);
                echo '
    			<tr>
    			<td align="left">'.trim($row['dep']).'</td>
    			<td align="left">'.$row['caller_name'].'</td>
                <td align="center">'.$row['number'].'</td>
    			<td align="center">'.$row['address'].'</td>
                </tr>
                '; 
            }
            echo '</table>';
        }
}
////////////////////////////////////////////////////////////////
//Показать информацию по адресу
////////////////////////////////////////////////////////////////
function func_Address ($address)
{
		$query = (" 
		SELECT * FROM phone,department 
		WHERE LOWER(phone.address) LIKE '%".strtolower($address)."%'
		AND dep = (SELECT dep FROM department WHERE phone.contract=department.contract) 
        ;") or die('Запрос2 не удался - ' . mysql_error());
        echo  '<h1>По запросу - '.$_GET['address'].', найдено:</h1>'; 
        
        $result = mysql_query($query);
        if ($result > 0)
        {	 
		    echo '
			<table border="1" width="100%">
			<tr>
			<td width="40%" align="center"><b>Управление</b></td>
			<td width="40%" align="center"><b>Абонент</b></td>
            <td width="20%" align="center"><b>Номер</b></td>
			<td width="20%" align="center"><b>Место установки</b></td>
			</tr>
			';
            $num = mysql_num_rows($result); 
            for($i=0;$i<$num;$i++)
            {
                $row = mysql_fetch_array($result);
                echo '
    			<tr>
    			<td align="left">'.trim($row['dep']).'</td>
    			<td align="left">'.$row['caller_name'].'</td>
                <td align="center">'.$row['number'].'</td>
    			<td align="center">'.$row['address'].'</td>
                </tr>
                '; 
            }
            echo '</table>';
        }
}
?>