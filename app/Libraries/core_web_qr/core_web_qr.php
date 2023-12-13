<?php
namespace App\Libraries\core_web_qr;
include_once "phpqrcode.php";

class core_web_qr
{
	function generate($texto,$path,$level,$size)
	{
		\QRcode::png($texto,$path,$level,$size,2);
	}
}


?>

