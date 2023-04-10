<?php 
	//log_message("ERROR",print_r("prueba de error intencional",true)); 
	$dataSession		= $this->session->all_userdata(); 
	//log_message("ERROR",print_r($dataSession,true));
	$usuario 			= $dataSession["company"]->name.">>>>>".$dataSession["user"]->nickname;
	
?>

<?php echo $usuario; ?>
<br/>
<?php echo $heading; ?>
<br/>
<?php echo $message; ?>