<div class="container">
  <div class="sidebar1">
  	<h3 align="left" style="background:#FF7800" >Меню</h3>
    <ul class="nav">
	  <li><a href="index.php">Главная</a></li>
      <li><a href="base.php">База</a></li>
<?php     
if (isset($_SESSION['user_id']))
{   
    $rights = $access;
        if ($rights == '1' || $rights == '2' || $rights == '3' || $rights == '5')
        {
    	  echo '<li><a href="report.php">Отчеты</a></li>';      
        }
        if ($rights == '1' || $rights == '2')
        {
          echo '<li><a href="data.php">Данные</a></li>';        
        }   
        if ($rights == '1')
        {
          echo '<li><a href="admin.php">Администрирование</a></li>';        
        }              
}  
?>    

      
    </ul>
    <!--
    <p align="justify">
	<h4><b>Тут можно размещать сообщения</b> </h4>
	<i><h5> 
	 --> 
	
	
	</h5></i>
	<h4>E-mail Администратора:</h4>
	<h4>esrodchenkov@purneftegaz.ru</h4>
    <!-- end .sidebar1 -->
	
	</div>
	