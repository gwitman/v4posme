<?php
//posme:2023-02-27
namespace App\Libraries\financial;

use App\Models\Core\Bd_Model;
use App\Models\Core\Branch_Model;
use App\Models\Core\Catalog_Item_convertion_Model;
use App\Models\Core\Catalog_Item_Model;
use App\Models\Core\Catalog_Model;
use App\Models\Core\Company_Component_flavor_Model;
use App\Models\Core\Company_Component_Model;
use App\Models\Core\Company_Data_View_Model;
use App\Models\Core\Company_Default_Data_View_Model;
use App\Models\Core\Company_Model;
use App\Models\Core\Company_Parameter_Model;
use App\Models\Core\Company_Subelement_audit_Model;
use App\Models\Core\Component_Audit_detail_Model;
use App\Models\Core\Component_Audit_Model;
use App\Models\Core\Component_Autorization_Model;
use App\Models\Core\Component_Model;
use App\Models\Core\Counter_Model;
use App\Models\Core\Currency_Model;
use App\Models\Core\Data_View_Model;
use App\Models\Core\Element_Model;
use App\Models\Core\Exchangerate_Model;
use App\Models\Core\Log_Model;
use App\Models\Core\Membership_Model;
use App\Models\Core\Menu_Element_Model;
use App\Models\Core\Parameter_Model;
use App\Models\Core\Role_Autorization_Model;
use App\Models\Core\Role_Model;
use App\Models\Core\Sub_Element_Model;
use App\Models\Core\Transaction_Concept_Model;
use App\Models\Core\Transaction_Model;
use App\Models\Core\User_Model;
use App\Models\Core\User_Permission_Model;
use App\Models\Core\Workflow_Model;
use App\Models\Core\Workflow_Stage_Model;
use App\Models\Core\Workflow_Stage_Relation_Model;



use App\Models\Accounting_Balance_Model;
use App\Models\Account_Level_Model;
use App\Models\Account_Model;
use App\Models\Account_Type_Model;
use App\Models\Biblia_Model;
use App\Models\Center_Cost_Model;
use App\Models\Company_Component_Concept_Model;
use App\Models\Company_Currency_Model;
use App\Models\Company_Log_Model;
use App\Models\Component_Cycle_Model;
use App\Models\Component_Period_Model;
use App\Models\Credit_Line_Model;
use App\Models\Customer_Consultas_Sin_Riesgo_Model;
use App\Models\Customer_Credit_Amortization_Model;
use App\Models\Customer_Credit_Document_Endity_Related_Model;
use App\Models\Customer_Credit_Document_Model;
use App\Models\Customer_Credit_Line_Model;
use App\Models\Customer_Credit_Model;
use App\Models\Customer_Model;
use App\Models\Employee_Calendar_Pay_detail_Model;
use App\Models\Employee_Calendar_Pay_Model;
use App\Models\Employee_Model;
use App\Models\Entity_Account_Model;
use App\Models\Entity_Email_Model;
use App\Models\Entity_Model;
use App\Models\Entity_Phone_Model;
use App\Models\Error_Model;
use App\Models\Fixed_Assent_Model;
use App\Models\Itemcategory_Model;
use App\Models\Itemwarehouse_Model;
use App\Models\Item_Data_Sheet_Detail_Model;
use App\Models\Item_Data_Sheet_Model;
use App\Models\Item_Model;
use App\Models\Item_Warehouse_Expired_Model;
use App\Models\Journal_Entry_Detail_Model;
use App\Models\Journal_Entry_Model;
use App\Models\Legal_Model;
use App\Models\List_Price_Model;
use App\Models\Natural_Model;
use App\Models\Notification_Model;
use App\Models\Price_Model;
use App\Models\Provideritem_Model;
use App\Models\Provider_Model;
use App\Models\Relationship_Model;
use App\Models\Remember_Model;
use App\Models\Tag_Model;
use App\Models\Transaction_Causal_Model;

use App\Models\Transaction_Master_Concept_Model;
use App\Models\Transaction_Master_Detail_Credit_Model;
use App\Models\Transaction_Master_Detail_Model;
use App\Models\Transaction_Master_Info_Model;
use App\Models\Transaction_Master_Model;

use App\Models\Transaction_Profile_Detail_Model;
use App\Models\Userwarehouse_Model;
use App\Models\User_Tag_Model;
use App\Models\Warehouse_Model;


class financial_amort{
	var $amount;       			//amount of the loan
	var $rate;         			//percentage rate of the loan
	var $numberPay;    			//number of years of the loan
	var $npmts;        			//number of payments of the loan
	var $mrate;        			//monthly interest rate
	var $tpmnt;        			//total amount paid on the loan
	var $tint;         			//total interest paid on the loan
	var $pmnt;         			//monthly payment of the loan
	var $firstDate;				//yyyy-mm-dd	 
	var $typeAmortization;		//tipo de amortization 193-constante,194-frances,195-aleman,196-americano
	var $periodPay; 
	var $objCatalogItems_DiasNoCobrables;
	var $objCatalogItems_DiasFeridos365;
	var $objCatalogItems_DiasFeridos366;
	var $objCatalogItems_DiasExcluded;
	var $flavorID;
	var $dayDesplazados;
	var $dayOriginal;
	
	 
	//*******************************
	//193 Amortizacion Constante
	// Es un sistema de amortizacion que se caracteriza por cuotas
	// e interes, decrecientes y los valores de amortizacion del principal
	// constante.
	
	//*******************************
	//194 Amortizacion Frances
	// Es un sistema de amortizacion que se caracteriza por cuotas
	// iguales, valores de amortizacion del principal e interes crecientes
	
	//*******************************
	//195 Amortizacion Aleman
	// Es un sistema de amortizacion que se caracteriza por el interes pagado por adelantado
	// pagos iguales, a excepcion de la primera parcela lo que corresponde a lo intereses
	// la amortizacion del capital es creciente y los intereses decrecientes.
	
	//*******************************
	//196 Amortizacion Americano
	// Es un sistema de amortizacion que se caracteriza por el pago de cuotas iguales
	// al interes, excepto el ultimo, cuando el valor total del principal se añade.
	
	//*******************************
	//463 Amortizacion Simple
	// Es un sistema de amortizacion que se caracteriza por el pago de cuotas iguales
	// y el interese se multiplica por el mismo numero de meses, como que si no se disminyllera el principal
	
	function amort($amount=0,$rate=0,$numberPay=0,$periodPay = 0,$firstDate="",$typeAmortization="",$objCatalogItems_DiasNoCobrables="",$objCatalogItems_DiasFeridos365="",$objCatalogItems_DiasFeridos366="",$objCatalogItems_DiasExcluded ="",$flavorID = 0 )
	{
		 date_default_timezone_set(APP_TIMEZONE);
		 $this->amount				=	$amount;  					//monto
		 $this->rate				=	$rate;   					//interes anual 
		 $this->numberPay			=	$numberPay;   				//numero de pagos
		 $this->periodPay 			= 	$periodPay;					//periodo de pago
		 $this->typeAmortization	=	$typeAmortization;			//tipo de amortizacion
		 $this->firstDate			=   date_create($firstDate);	//fecha del credito
		 $this->dayOriginal			=   (int)date_format(date_create($firstDate), 'd');

		 $this->objCatalogItems_DiasNoCobrables			=   $objCatalogItems_DiasNoCobrables;
		 $this->objCatalogItems_DiasFeridos365			=   $objCatalogItems_DiasFeridos365;
		 $this->objCatalogItems_DiasFeridos366			=   $objCatalogItems_DiasFeridos366;
		 $this->objCatalogItems_DiasExcluded			= 	$objCatalogItems_DiasExcluded;
		 $this->flavorID								=	$flavorID;
		 $this->dayDesplazados							= 	0;
		 
	}
	function getPmtValueAleman($pv,$n,$i){
		//Tasa 0: amortizacion lineal, evita la division entre cero
		if($i == 0)
			return $pv / $n;

		$pmt = (($pv* $i) / (1-( pow((1 - $i),$n))));
		return $pmt;
	}
	
	function getBaseRatio($periodPay){
		if ($periodPay == 7) /*semanal*/
			return 52;
		else if ($periodPay == 15) /*quincenal*/
			return 24;
		else if ($periodPay == 14) /*catorcenal*/
			return 26;
		else if ($periodPay == 30) /*mensual*/
			return 12;
		else if ($periodPay == 1)  /*diario*/
			return 365;
		else if ($periodPay == 45) /*mes y medio*/
			return 8;
		else
			return 0; /*no hay de otra*/
	}
	function validarDecimal($numero,$begin,$end) 
	{
		// Obtener la parte decimal
		$decimal = $numero - floor($numero);

		// Redondear a dos decimales
		$decimal = round($decimal, 2);

		// Verificar si está dentro del rango [0.01, 0.20]
		$enRango = ($decimal >= $begin && $decimal <= $end);

		return [
			'rango' => $enRango,
			'valor' => number_format($decimal, 2, '.', '')
		];
	}
	//Devuelve la parte decimal del plazo (ej. 50.666 => 0.666).
	//Si la fraccion es <= 0.001 se considera plazo entero y devuelve 0.
	function getFraccionPlazo($numberPay)
	{
		$numero		= (float)$numberPay;
		$fraccion	= round($numero - floor($numero),6);
		return $fraccion > 0.001 ? $fraccion : 0;
	}
	//Plazo que usan las tablas Constante y Francesa.
	//Si la fraccion es despreciable (<= 0.001) pero el plazo no es entero exacto, se usa floor(n)
	//(minimo 1 cuota) para que el saldo cierre en la cuota k. En cualquier otro caso devuelve
	//el valor original sin modificar (plazo entero o con fraccion prorrateable).
	function getPlazoEfectivo($numberPay)
	{
		$numero		= (float)$numberPay;
		if($this->getFraccionPlazo($numberPay) == 0 && $numero != floor($numero))
		{
			$cuotas	= floor($numero);
			return $cuotas < 1 ? 1 : $cuotas;
		}
		return $numberPay;
	}
	//Fecha de la cuota fraccionaria: fecha base + round(fraccion * dias del periodo), minimo 1 dia,
	//corrida al siguiente dia cobrable. Trabaja sobre una copia para no mutar la fecha recibida.
	function getFechaFraccion($fechaBase,$fraccion,$periodPay)
	{
		$fechaReturn	= clone $fechaBase;
		$dias			= (int)round($fraccion * $periodPay);
		if($dias < 1)
			$dias = 1;

		$fechaReturn	= date_add($fechaReturn,date_interval_create_from_date_string($dias.' days'));
		for($ii = 0 ; $ii <= 10 ; $ii++ )
		{
			if($this->fechaEsFeriada($fechaReturn))
			{
				$fechaReturn = date_add($fechaReturn,date_interval_create_from_date_string('1 days'));
			}
			else
			{
				break;
			}
		}
		return $fechaReturn;
	}
	//Tasa 0: la cuota $k absorbe la diferencia de redondeo para que la suma del capital
	//sea igual al monto y el saldo final quede en 0 (tablas con formato sprintf %01.2f)
	function cerrarCuotaTasaCero($result,$k,$amount)
	{
		if(!$result["detail"] || !isset($result["detail"][$k]))
			return $result;

		$totalPrincipal							= array_sum(array_map('floatval', array_column($result["detail"], "principal")));
		$principalK								= round(floatval($result["detail"][$k]["principal"]) + round($amount - $totalPrincipal,2),2);
		$result["detail"][$k]["principal"] 		= sprintf("%01.2f",$principalK) ;
		$result["detail"][$k]["cuota"] 			= sprintf("%01.2f",$principalK + floatval($result["detail"][$k]["interes"]));
		$result["detail"][$k]["saldo"] 			= sprintf("%01.2f",0) ;
		return $result;
	}
	//Plazo decimal: recalcula saldoInicial y saldo de las filas $desde..$hasta a partir de los capitales
	//mostrados, para que encadenen exacto (saldoInicial(t+1) = saldo(t), saldoInicial - capital = saldo).
	//No cambia capital, interes ni cuota. Formato sprintf %01.2f (tablas Simple y Aleman).
	function encadenarSaldos($result,$desde,$hasta,$saldoInicial)
	{
		$saldoCadena	= round($saldoInicial,2);
		for($t = $desde ; $t <= $hasta ; $t++ )
		{
			if(!isset($result["detail"][$t]))
				continue;

			$result["detail"][$t]["saldoInicial"] 	= sprintf("%01.2f",$saldoCadena) ;
			$saldoCadena							= round($saldoCadena - floatval($result["detail"][$t]["principal"]),2);
			if($saldoCadena == 0)
				$saldoCadena						= 0; //evita "-0.00"
			$result["detail"][$t]["saldo"]			= sprintf("%01.2f",$saldoCadena) ;
		}
		return $result;
	}
	function fechaEsFeriada($fecha)
	{
		
		$fecha				= date_create(date_format($fecha,"Y-m-d"));
		$fechaUltimoDia		= date_format($fecha,"Y")."-12-31";		
		$fechaUltimoDia 	= \DateTime::createFromFormat('Y-m-d',$fechaUltimoDia);	
		
		$diaSemana 			= date("w", strtotime(date_format($fecha,"Y-m-d")));		
		$diaAno 			= date("z", strtotime(date_format($fecha,"Y-m-d")));		
		$diasTotalesDelAno 	= date("z", strtotime(date_format($fechaUltimoDia,"Y-m-d")));
		$diasTotalesDelAno	= $diasTotalesDelAno + 1;	
		

		
		if($this->objCatalogItems_DiasExcluded)
		{
			$arrayDiasExcluided	= explode(",", $this->objCatalogItems_DiasExcluded->reference1);			
			//validar dias de semana cobrales 
			foreach($arrayDiasExcluided as $catalogItem)
			{
				if($catalogItem == $diaSemana)
				{
					
					return true;
				}
			}
		}
		
		//validar si es domingo o segun el catalogo de configuracion		
		foreach($this->objCatalogItems_DiasNoCobrables as $catalogItem)
		{			
			if($catalogItem->sequence == $diaSemana)
			{
				return true;
			}
		}
		
		
		if($diasTotalesDelAno == 365)
		{
			//validar si la fecha es un dia feriado
			foreach($this->objCatalogItems_DiasFeridos365 as $catalogItem)
			{
			
				if($catalogItem->sequence == $diaAno)
				{
					return true;
				}
			}
		}
		
		if($diasTotalesDelAno == 366)		
		{		
			//validar si la fecha es un dia feriado
			foreach($this->objCatalogItems_DiasFeridos366 as $catalogItem)
			{
				
				if($catalogItem->sequence == $diaAno){
					return true;
				}
			}
		}
		
		return false;
		
	}
	function getNextDate($date,$periodPay){
		
		
		$fechaReturn 		= null;
		$day				= date_format($date, 'd');
		$firstDateMonth		= date_format($date, 'Y-m');
		$firstDateMonth		= $firstDateMonth."-01";
		$firstDateMonth		= date_create($firstDateMonth);
		 
		$lastDateMonth		= date_create(date_format($firstDateMonth,"Y-m-d"));
		$lastDateMonth		= date_add($lastDateMonth, date_interval_create_from_date_string('1 months'));
		$lastDateMonth		= date_sub($lastDateMonth, date_interval_create_from_date_string('1 days'));
		
		/*semanal*/
		if ($periodPay == 7)
		{
			$fechaReturn = date_add($date,date_interval_create_from_date_string('7 days'));
			if($this->fechaEsFeriada($fechaReturn))
			{
				$fechaReturn = date_add($fechaReturn,date_interval_create_from_date_string('1 days'));
			}
			return $fechaReturn;
		}
		 /*quincenal*/ 
		else if ($periodPay == 15)
		{
			$fechaReturn = date_add($date,date_interval_create_from_date_string('15 days'));	
			if($this->fechaEsFeriada($fechaReturn))
			{
				$fechaReturn = date_add($fechaReturn,date_interval_create_from_date_string('1 days'));
			}
			return $fechaReturn;			
		}
		/*catorcenal*/ 
		else if ($periodPay == 14)
		{
			$fechaReturn = date_add($date,date_interval_create_from_date_string('14 days'));	
			if($this->fechaEsFeriada($fechaReturn))
			{
				$fechaReturn = date_add($fechaReturn,date_interval_create_from_date_string('1 days'));
			}
			return $fechaReturn;			
		}	
		/*mensual*/
		else if ($periodPay == 30 )
		{
			// Mes y año base: los de la fecha TEORICA anterior (fecha mostrada menos los dias corridos
			// por dia no cobrable), para que el corrimiento no arrastre la cuota al mes siguiente
			$fechaTeorica	= date_sub(clone $date, date_interval_create_from_date_string(((int)$this->dayDesplazados).' days'));
			$mesBase = (int)date_format($fechaTeorica, 'm');
			$añoBase = (int)date_format($fechaTeorica, 'Y');
			
			// Avanzar un mes
			$mesSiguiente = $mesBase + 1;
			$añoSiguiente = $añoBase;
			
			if($mesSiguiente > 12) {
				$mesSiguiente = 1;
				$añoSiguiente++;
			}
			
			// Obtener cuántos días tiene el mes siguiente
			$ultimoDiaDelMes = (int)date('t', mktime(0, 0, 0, $mesSiguiente, 1, $añoSiguiente));
			
			// SIEMPRE usar el día original, a menos que el mes no tenga suficientes días
			$diaFinal = ($this->dayOriginal <= $ultimoDiaDelMes) ? $this->dayOriginal : $ultimoDiaDelMes;
			
			// Crear la fecha con el día original
			$fechaReturn 	= date_create(sprintf('%04d-%02d-%02d', $añoSiguiente, $mesSiguiente, $diaFinal));
			// Mientras no sea dia cobrable, mover al siguiente dia (con tope, como en la rama diaria).
			// Se guarda el total de dias corridos para calcular la siguiente fecha teorica.
			$this->dayDesplazados = 0;
			for($ii = 0 ; $ii <= 10 ; $ii++ )
			{
				if($this->fechaEsFeriada($fechaReturn))
				{
					$fechaReturn 			= date_add($fechaReturn, date_interval_create_from_date_string('1 days'));
					$this->dayDesplazados	= $this->dayDesplazados + 1;
				}
				else
				{
					break;
				}
			}

			return $fechaReturn;
		}
		/*diario*/
		else if ($periodPay == 1)
		{
			
			$fechaReturn = $date;
			$fechaReturn = date_add($fechaReturn,date_interval_create_from_date_string('1 days'));			
			for($ii = 0 ; $ii <= 10 ; $ii++ )
			{	
				if($this->fechaEsFeriada($fechaReturn))
				{
					$fechaReturn = date_add($fechaReturn,date_interval_create_from_date_string('1 days'));				
				}
				else 
				{
					break;
				}
			}
			return $fechaReturn;
		}
		/*45 dias*/ 
		else if ($periodPay == 45) 
		{
			$fechaReturn = 			
				date_add(
					date_add($date,date_interval_create_from_date_string('1 months')),
					date_interval_create_from_date_string("15 days")
				);
				
			if($this->fechaEsFeriada($fechaReturn))
			{
				$fechaReturn = date_add($fechaReturn,date_interval_create_from_date_string('1 days'));
			}
				
			return $fechaReturn;
		}
		else
		{
			return $date;
		}
	}
	
	function getPmtValueFrances($pv,$n,$i){
		//Tasa 0: amortizacion lineal, evita la division entre cero
		if($i == 0)
			return $pv / $n;

		$pmt = ($pv * $i*( pow( 1 + $i ,$n )) / ( pow( 1 + $i , $n ) - 1));
		return $pmt;
	}
	function getPmtValueSimple($pv,$n,$i){
		//Tasa 0: amortizacion lineal, evita la division entre cero
		if($i == 0)
			return round($pv / $n,2,PHP_ROUND_HALF_UP);

		$pmt = ($pv * $i*( pow( 1 + $i ,$n )) / ( pow( 1 + $i , $n ) - 1));
		$pmt = round(($pv + ($pv * ($i) * $n)) / $n,2,PHP_ROUND_HALF_UP); 
		return $pmt;
	}
	function getTable(){
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= 0;
		$result["summary"]["totalIntest"] 	= 0;
		$result["summary"]["totalCuotas"] 	= 0;
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;

		//Reiniciar el corrimiento por dias no cobrables (fechas mensuales)
		$this->dayDesplazados				= 0;

		if ($this->typeAmortization == 194)
			$result = $this->getTableFrances();
		else if ($this->typeAmortization == 195)
			$result = $this->getTableAleman();
		else if ($this->typeAmortization == 196)
			$result = $this->getTableAmericano();
		else if ($this->typeAmortization == 463)
			$result = $this->getTableSimple();
		else if ($this->typeAmortization == 544)
			$result = $this->getTableSimpleNotEmplementable();
		else 
			$result = $this->getTableConstante();
		
			
		return $result;
	
	}
	function getTableSimpleNotEmplementable()
	{
		$capitalDesembolsado 	= $this->amount;
		$numeroDePagos		   	= $this->getPlazoEfectivo($this->numberPay);
		$fraccion				= $this->getFraccionPlazo($this->numberPay);
		$montoTotalInteres  	= round((($this->rate/100) * $this->amount),2);
		$montoTotalApagar		= $montoTotalInteres + $capitalDesembolsado ;
		$montoPorCuota			= round($montoTotalApagar / $numeroDePagos,2);
		
		
		 
		
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= round($montoPorCuota,2);
		$result["summary"]["totalIntest"] 	= round($montoTotalInteres,2);
		$result["summary"]["totalCuotas"] 	= round($montoTotalApagar,2);
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
		$balanceInicial = $capitalDesembolsado;
		$balance 		= $capitalDesembolsado;
		$numpay			= $numeroDePagos;		
		$i 				= 1;
		$nextDate 		= $this->firstDate;
		
		
		
		while ($i <= $numpay) {			
			$amort			= round($capitalDesembolsado / $numeroDePagos,2) ;
			$interes		= round($montoTotalInteres / $numeroDePagos,2) ;
			$payment		= $amort + $interes;			
			$balance		= $balance-$amort;
			
			if($i == $numpay)
			{
				$interes 	= $interes + $balance;
				$payment 	= $payment + $balance;
				$balance	= $balance - $balance;
			}
		
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"] 		= sprintf("%01.2f",round($amort,2)) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",round($interes,2)) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",round($payment,2));							
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",round($balance,2)) ;				
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",round($balanceInicial,2)) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);			
			$balanceInicial							= $balanceInicial - $amort;
			$i++;
		}

		//Plazo con decimales: cuota k+1 con el capital restante y el interes pactado restante
		//(el interes total es fijo); el bloque de validacion ajusta los centavos en la cuota k+1
		if($fraccion > 0)
		{
			$k					= (int)floor((float)$numpay);
			$amort				= round($balance,2);
			if($amort >= 0.01)
			{
				$interes		= round($montoTotalInteres - ($k * round($montoTotalInteres / $numeroDePagos,2)),2);
				$payment		= $amort + $interes;
				$fechaFraccion	= $k > 0 ?
									$this->getFechaFraccion(date_create($result["detail"][$k]["date"]),$fraccion,$this->periodPay) :
									clone $this->firstDate;
				$i				= $k + 1;

				$result["detail"][$i]	  				= null;
				$result["detail"][$i]["pnum"] 			= $i;
				$result["detail"][$i]["date"] 			= date_format($fechaFraccion,"Y-m-d");
				$result["detail"][$i]["principal"] 		= sprintf("%01.2f",round($amort,2)) ;
				$result["detail"][$i]["interes"] 		= sprintf("%01.2f",round($interes,2)) ;
				$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",round($payment,2));
				$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",0) ;
				$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",round($balance,2)) ;
				$result["detail"][$i]["cpmnt"] 			= 0;
				$i++;
			}
			else if($k > 0)
			{
				//El remanente es menor a 0.01: se cierra el saldo en la cuota k (la validacion ajusta capital e interes)
				$result["detail"][$k]["saldo"] 			= sprintf("%01.2f",0) ;
			}
		}


		//Validar principal
		///////////////////////////////////////////////
		$totalPrincipal 			= array_sum(array_map('floatval', array_column($result["detail"], "principal")));		
		$dif						= round($capitalDesembolsado - $totalPrincipal,2);
		$i 					= $i-1;
		if($dif > 0)
		{
			$result["detail"][$i]["principal"] 			= round($result["detail"][$i]["principal"] + $dif,2);
			$result["detail"][$i]["interes"] 			= round($result["detail"][$i]["interes"] - $dif ,2);
		}
		if($dif < 0)
		{
			$dif										= $dif * -1;
			$result["detail"][$i]["principal"] 			= round($result["detail"][$i]["principal"] - $dif,2);
			$result["detail"][$i]["interes"] 			= round($result["detail"][$i]["interes"] + $dif ,2);
		}		
		$result["detail"][$i]["cuota"] 					= $result["detail"][$i]["principal"] + $result["detail"][$i]["interes"];
		
		
		//Validar Interes
		$totalInteres 					= array_sum(array_map('floatval', array_column($result["detail"], "interes")));	
		$totalInteresCalculado 			= $montoTotalInteres;
		$dif							= $totalInteresCalculado-$totalInteres;
		if($dif > 0)
		{
			$result["detail"][$i]["interes"] 			= round($result["detail"][$i]["interes"] + abs($dif) ,2);
		}
		else
		{
			$result["detail"][$i]["interes"] 			= round($result["detail"][$i]["interes"] - abs($dif) ,2);
		}
		$result["detail"][$i]["cuota"] 	= $result["detail"][$i]["principal"] + $result["detail"][$i]["interes"];
		return $result;
		
	}
	function getTableSimple(){
		$pv 	= $this->amount;
		$n   	= $this->getPlazoEfectivo($this->numberPay);
		$i  	= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;		
		$pmt 	= $this->getPmtValueSimple($pv,$n,$i);
		
		
		
		 
		
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= ($pmt);
		$result["summary"]["totalIntest"] 	= ($pmt * $n) - $pv;
		$result["summary"]["totalCuotas"] 	= ($pmt * $n);
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
		
		
		$amount		=$this->amount;
		$numpay		=$n;
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		$payment	=$pmt;
		$total		=$payment*$numpay;
		$interest	=$total-$amount;
	
		$balance	=$amount;
		$i 			=1;
		$nextDate 	=$this->firstDate;
		
				
		
		
		
		
		
		
		
		while ($i <= $numpay) {
			$newInterest	= $monthly*$amount;
			$amort			= $payment-$newInterest;
			$balance		= $balance-$amort;
			$balanceInicial	= $balance+$amort;
		
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"] 		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$balance) ;				
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$balanceInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);

			$i++;
		}

		//Plazo con decimales: cuota k+1 = capital restante + interes simple proporcional (pv * i * fraccion)
		$fraccion	= $this->getFraccionPlazo($this->numberPay);
		if($fraccion > 0)
		{
			$k					= (int)floor((float)$numpay);
			//Saldos de las filas 1..k encadenados con los capitales mostrados: el saldo de la fila k
			//es el capital de la cuota k+1 (= monto - suma de capitales mostrados)
			$capitalFraccion	= round($amount,2);
			if($k > 0)
			{
				$result				= $this->encadenarSaldos($result,1,$k,$amount);
				$capitalFraccion	= floatval($result["detail"][$k]["saldo"]);
			}

			if($capitalFraccion >= 0.01)
			{
				$fechaFraccion	= $k > 0 ?
									$this->getFechaFraccion(date_create($result["detail"][$k]["date"]),$fraccion,$this->periodPay) :
									clone $this->firstDate;
				$newInterest	= $monthly*$amount*$fraccion;
				$cuotaFraccion	= $capitalFraccion + $newInterest;
				$i				= $k + 1;

				$result["detail"][$i]	  				= null;
				$result["detail"][$i]["pnum"] 			= $i;
				$result["detail"][$i]["date"] 			= date_format($fechaFraccion,"Y-m-d");
				$result["detail"][$i]["principal"] 		= sprintf("%01.2f",$capitalFraccion) ;
				$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;
				$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$cuotaFraccion);
				$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",0) ;
				$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$capitalFraccion) ;
				$result["detail"][$i]["cpmnt"] 			= 0;
				$i++;
			}
			else if($k > 0)
			{
				//El remanente es menor a 0.01: se cierra el saldo en la cuota k
				$principalK								= round(floatval($result["detail"][$k]["principal"]) + $capitalFraccion,2);
				$result["detail"][$k]["principal"] 		= sprintf("%01.2f",$principalK) ;
				$result["detail"][$k]["cuota"] 			= sprintf("%01.2f",$principalK + floatval($result["detail"][$k]["interes"]));
				$result["detail"][$k]["saldo"] 			= sprintf("%01.2f",0) ;
			}
		}

		//Tasa 0: la ultima cuota absorbe el redondeo y el saldo final queda en 0
		//(con plazo decimal el cierre lo hace la cuota k+1)
		if($monthly == 0)
		{
			if($fraccion == 0)
				$result = $this->cerrarCuotaTasaCero($result,$i - 1,$amount);

			$result["summary"]["totalIntest"] 	= 0;
			$result["summary"]["totalCuotas"] 	= $pv;
		}

		return $result;
	}
	function getTableFrances(){
	
		$pv 	= $this->amount;
		$n   	= $this->getPlazoEfectivo($this->numberPay);
		$i  	= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;
		$pmt 	= $this->getPmtValueFrances($pv,$n,$i);
		
		
		
		
		 
		
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= ($pmt);
		$result["summary"]["totalIntest"] 	= ($pmt * $n) - $pv;
		$result["summary"]["totalCuotas"] 	= ($pmt * $n);
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
		
		$amount		=$this->amount;
		$numpay		=$n;
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		//Tasa 0: amortizacion lineal (principal = round(monto/n,2)), evita la division entre cero
		$payment	=$monthly == 0 ? round($amount/$numpay,2) : (($amount*$monthly)/(1-pow((1+$monthly),-$numpay)));
		$total		=$payment*$numpay;
		$interest	=$total-$amount;

		$balance	=$amount;
		$i 			=1;
		$nextDate 	=$this->firstDate;

		while ($i <= $numpay) {
			$newInterest	= $monthly*$balance;
			$amort			= $payment-$newInterest;
			$balance		= $balance-$amort;
			$balanceInicial	= $balance+$amort;
			
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"] 		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$balance) ;				
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$balanceInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);
			$i++;
		}

		//Plazo con decimales: cuota k+1 prorrateada (saldo restante + interes proporcional)
		$fraccion	= $this->getFraccionPlazo($this->numberPay);

		//Tasa 0 con plazo entero: la ultima cuota absorbe el redondeo y el saldo final queda en 0
		if($monthly == 0 && $fraccion == 0)
			$result = $this->cerrarCuotaTasaCero($result,$i - 1,$amount);

		if($fraccion > 0)
		{
			$k					= (int)floor((float)$numpay);
			$totalPrincipal		= $result["detail"] ? array_sum(array_map('floatval', array_column($result["detail"], "principal"))) : 0;
			$capitalFraccion	= round($amount - $totalPrincipal,2);

			if($capitalFraccion >= 0.01)
			{
				$fechaFraccion	= $k > 0 ?
									$this->getFechaFraccion(date_create($result["detail"][$k]["date"]),$fraccion,$this->periodPay) :
									clone $this->firstDate;
				$newInterest	= $monthly*$capitalFraccion*$fraccion;
				$cuotaFraccion	= $capitalFraccion + $newInterest;
				$i				= $k + 1;

				$result["detail"][$i]	  				= null;
				$result["detail"][$i]["pnum"] 			= $i;
				$result["detail"][$i]["date"] 			= date_format($fechaFraccion,"Y-m-d");
				$result["detail"][$i]["principal"] 		= sprintf("%01.2f",$capitalFraccion) ;
				$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;
				$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$cuotaFraccion);
				$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",0) ;
				$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$capitalFraccion) ;
				$result["detail"][$i]["cpmnt"] 			= 0;

				//Resumen: k cuotas completas + la cuota fraccionaria
				$result["summary"]["totalIntest"] 		= (($pmt * $k) + $cuotaFraccion) - $pv;
				$result["summary"]["totalCuotas"] 		= (($pmt * $k) + $cuotaFraccion);
			}
			else if($k > 0)
			{
				//El remanente es menor a 0.01: se cierra el saldo en la cuota k
				$principalK								= round(floatval($result["detail"][$k]["principal"]) + $capitalFraccion,2);
				$result["detail"][$k]["principal"] 		= sprintf("%01.2f",$principalK) ;
				$result["detail"][$k]["cuota"] 			= sprintf("%01.2f",$principalK + floatval($result["detail"][$k]["interes"]));
				$result["detail"][$k]["saldo"] 			= sprintf("%01.2f",0) ;
			}
		}

		//Tasa 0: sin intereses, el total de cuotas es el monto
		if($monthly == 0)
		{
			$result["summary"]["totalPay"] 		= $payment;
			$result["summary"]["totalIntest"] 	= 0;
			$result["summary"]["totalCuotas"] 	= $pv;
		}

		return $result;
	}
	function getTableAleman(){
		
		$pv 		= $this->amount;
		$n   		= $this->getPlazoEfectivo($this->numberPay);
		$i  		= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;
		$pmt 		= $this->getPmtValueAleman($pv,$n,$i);
		$interest 	= $pv*$i;
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= ($pmt);
		$result["summary"]["totalIntest"] 	= (($pmt * $n) + $interest) - $pv;
		$result["summary"]["totalCuotas"] 	= (($pmt * $n) + $interest);
		$result["summary"]["pagoMensual"] 	= $pv * $i;
		$result["detail"] 					= null;
		
		$amount		=$this->amount;
		$numpay		=$n;
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;



		$Init_interest 	=$monthly*$amount;
		$Init_parcela 	=$Init_interest;
		$s 				=(1-$monthly);
		//Tasa 0: amortizacion lineal (principal = round(monto/n,2)), evita la division entre cero
		$payment		=$monthly == 0 ? round($amount/$numpay,2) : (($amount* $monthly) / (1-( pow($s,$numpay))));
		$amort 			=0;
		$principal 		=$payment;
		$saldo 			=$amount;
		$n 				=$numpay;
		$total			=$payment*$numpay;
		$interest		=$total-$amount;
		$nextDate 		=$this->firstDate;
		$saldo			=$amount;
		$i 				=1;
		
		
		
		$result["detail"][$i-1]	  					= null;
		$result["detail"][$i-1]["pnum"] 			= $i;									
		$result["detail"][$i-1]["date"] 			= date_format($nextDate,"Y-m-d");		
		$result["detail"][$i-1]["interes"] 			= sprintf("%01.2f",$Init_interest) ;
		$result["detail"][$i-1]["cuota"] 			= sprintf("%01.2f",$Init_parcela) ;
		$result["detail"][$i-1]["principal"] 		= 0;
		$result["detail"][$i-1]["saldoInicial"] 	= sprintf("%01.2f",$amount) ;
		$result["detail"][$i-1]["saldo"] 			= sprintf("%01.2f",$amount) ;
		$result["detail"][$i-1]["cpmnt"] 			= 0;
		
		
		while ($i <= $numpay) {
			$amort			=$payment*(pow($s,($n-$i)));
			$saldo			=$saldo-$amort;
			$newInterest	=$monthly*$saldo;
			$newpayment 	=$amort;
			$newInterest	=$monthly*$saldo;
			$saldoInicial	=$saldo + $amort;
			
			if($i==$numpay){
				$newInterest	= 0;
				$parcela 		= $payment;
			}
		
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"]		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$saldo) ;
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$saldoInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;

			$i++;
		}

		//Plazo con decimales (interes por adelantado): la fila k cobra el interes del periodo fraccionario
		//(saldo * tasa * fraccion) y la fila k+1 paga el capital restante con interes 0
		$fraccion	= $this->getFraccionPlazo($this->numberPay);
		if($fraccion > 0)
		{
			$k					= (int)floor((float)$numpay);
			//Saldos de las filas 1..k encadenados con los capitales mostrados (la fila 0 no amortiza):
			//el saldo de la fila k es el capital de la cuota k+1 (= monto - suma de capitales mostrados)
			$capitalFraccion	= round($amount,2);
			if($k > 0)
			{
				$result				= $this->encadenarSaldos($result,1,$k,$amount);
				$capitalFraccion	= floatval($result["detail"][$k]["saldo"]);
			}

			if($capitalFraccion >= 0.01)
			{
				//Fila k (con k = 0 es la fila del interes inicial): interes proporcional al periodo fraccionario
				$interesK								= $monthly*$capitalFraccion*$fraccion;
				$result["detail"][$k]["interes"] 		= sprintf("%01.2f",$interesK) ;
				$result["detail"][$k]["cuota"] 			= sprintf("%01.2f",floatval($result["detail"][$k]["principal"]) + $interesK);

				$fechaFraccion	= $this->getFechaFraccion(date_create($result["detail"][$k]["date"]),$fraccion,$this->periodPay);
				$i				= $k + 1;

				$result["detail"][$i]	  				= null;
				$result["detail"][$i]["pnum"] 			= $i;
				$result["detail"][$i]["date"] 			= date_format($fechaFraccion,"Y-m-d");
				$result["detail"][$i]["principal"]		= sprintf("%01.2f",$capitalFraccion) ;
				$result["detail"][$i]["interes"] 		= sprintf("%01.2f",0) ;
				$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$capitalFraccion);
				$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",0) ;
				$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$capitalFraccion) ;
				$result["detail"][$i]["cpmnt"] 			= 0;
				$i++;
			}
			else if($k > 0)
			{
				//El remanente es menor a 0.01: se cierra el saldo en la cuota k (ultima fila, interes 0)
				$principalK								= round(floatval($result["detail"][$k]["principal"]) + $capitalFraccion,2);
				$result["detail"][$k]["principal"] 		= sprintf("%01.2f",$principalK) ;
				$result["detail"][$k]["interes"] 		= sprintf("%01.2f",0) ;
				$result["detail"][$k]["cuota"] 			= sprintf("%01.2f",$principalK);
				$result["detail"][$k]["saldo"] 			= sprintf("%01.2f",0) ;
			}

			//Resumen a partir del detalle (incluye la fila del interes inicial)
			$result["summary"]["totalCuotas"] 	= array_sum(array_map('floatval', array_column($result["detail"], "cuota")));
			$result["summary"]["totalIntest"] 	= array_sum(array_map('floatval', array_column($result["detail"], "interes")));
		}

		//Tasa 0: la ultima cuota absorbe el redondeo y el saldo final queda en 0
		//(con plazo decimal el cierre lo hace la cuota k+1)
		if($monthly == 0)
		{
			if($fraccion == 0)
				$result = $this->cerrarCuotaTasaCero($result,$i - 1,$amount);

			$result["summary"]["totalPay"] 		= $payment;
			$result["summary"]["totalIntest"] 	= 0;
			$result["summary"]["totalCuotas"] 	= $pv;
		}

		return $result;
	}
	function getTableAmericano(){
		$pv 		= $this->amount;
		$n   		= $this->getPlazoEfectivo($this->numberPay);
		$i  		= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;
		$interest 	= $n * $pv * $i;
		
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= 0;
		$result["summary"]["totalIntest"] 	= $interest;
		$result["summary"]["totalCuotas"] 	= $pv + $interest;
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
		$amount		=$this->amount;
		$numpay		=$n;
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		$payment	=0;
		$amort 		=0;
		$saldo 		=$amount;
		$n 			=$numpay;
		$total		=$payment*$numpay;
		$interest	=$total-$amount;
		$saldo		=$amount;
		$i 			=1;
		$nextDate 	=$this->firstDate;
		
		
		while ($i <= $numpay) {
			$newInterest	=$monthly*$amount;
			$payment 		=$newInterest;
			
			if($i==$numpay){
				$saldo 		= 0;
				$amort 		= $amount;
				$payment 	= $amount+($monthly*$amount);
			}
			
			
			$saldoInicial							= $saldo + $amort;
			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;									
			$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
			$result["detail"][$i]["principal"]		= sprintf("%01.2f",$amort) ;				
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;			
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);				
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",$saldo) ;
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$saldoInicial) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);
			$i++;
		}

		//Plazo con decimales: las cuotas 1..k son solo interes; la cuota k+1 devuelve el capital completo
		//mas el interes proporcional (monto * tasa * fraccion). El resumen (n * pv * i) ya incluye la fraccion.
		$fraccion	= $this->getFraccionPlazo($this->numberPay);
		if($fraccion > 0 && round($amount,2) >= 0.01)
		{
			$k				= (int)floor((float)$numpay);
			$fechaFraccion	= $k > 0 ?
								$this->getFechaFraccion(date_create($result["detail"][$k]["date"]),$fraccion,$this->periodPay) :
								clone $this->firstDate;
			$newInterest	= $monthly*$amount*$fraccion;
			$payment		= $amount + $newInterest;
			$i				= $k + 1;

			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;
			$result["detail"][$i]["date"] 			= date_format($fechaFraccion,"Y-m-d");
			$result["detail"][$i]["principal"]		= sprintf("%01.2f",$amount) ;
			$result["detail"][$i]["interes"] 		= sprintf("%01.2f",$newInterest) ;
			$result["detail"][$i]["cuota"] 			= sprintf("%01.2f",$payment);
			$result["detail"][$i]["saldo"] 			= sprintf("%01.2f",0) ;
			$result["detail"][$i]["saldoInicial"] 	= sprintf("%01.2f",$amount) ;
			$result["detail"][$i]["cpmnt"] 			= 0;
		}

		return $result;

	}
	function getTableConstante(){
	
		$pv 		= $this->amount;
		$n   		= $this->getPlazoEfectivo($this->numberPay);
		$i  		= ($this->rate / $this->getBaseRatio($this->periodPay)) / 100;

		$p 				= $pv/$n;
		$saldo 			= $pv+$p;
		$npv 			= 0;
		$newInterest 	= 0;
		$Totpay 		= 0;

		//Plazo con decimales: se agrega una cuota k+1 prorrateada si su capital es >= 0.01
		$fraccion			= $this->getFraccionPlazo($this->numberPay);
		$cuotasCompletas	= floor((float)$n);
		$aplicarFraccion	= false;
		if($fraccion > 0)
		{
			$capitalFraccion	= round($pv - ($cuotasCompletas * round(round($pv,2)/$n,2)),2);
			$aplicarFraccion	= $capitalFraccion >= 0.01;
		}

		for ($t=1;$t<= $n;$t++ ){
			$npv			= $saldo-$p ;
			$newInterest 	= $newInterest+($i*$npv);
			$saldo 			= $npv;
			$Totpay 		= $Totpay+$npv;

		}

		//Interes proporcional de la cuota fraccionaria
		if($aplicarFraccion)
		{
			$npv			= $saldo-$p ;
			$newInterest 	= $newInterest+($i*$npv*$fraccion);
		}

		
		$Totint 							=  $newInterest;//total de intereses
		$Totpay 							=  $pv+$Totint;//total de pago
		
			
		$result["summary"]					= null;
		$result["summary"]["totalPay"] 		= 0;
		$result["summary"]["totalIntest"] 	= $Totint;
		$result["summary"]["totalCuotas"] 	= $Totpay;
		$result["summary"]["pagoMensual"] 	= 0;
		$result["detail"] 					= null;
		
			
		$amount		=$this->amount;
		$numpay		=$n;
		$rate		=($this->rate / $this->getBaseRatio($this->periodPay));
		$rate		=$rate/100;
		$monthly	=$rate;
		$base		=(1-pow((1+$monthly),-$numpay));
		$payment	=$base == 0 ? ($amount / $numpay) : (($amount*$monthly) / $base);
		$total		=$payment*$numpay;
		$interest	=$total-$amount;
		$saldo		=$amount;
		$Totint 	=0;
		$i 			=1;
		$nextDate 	=$this->firstDate;		
		
		$cuotaAcumulada	= 0;

		//Ultima cuota donde se cierra el saldo: con plazo entero es $numpay (igual que antes);
		//con fraccion sin cuota k+1 (capital < 0.01) el cierre se hace en la cuota k
		$ultimaCuota	= $numpay;
		if($fraccion > 0 && !$aplicarFraccion)
			$ultimaCuota = $cuotasCompletas;

		while ($i <= $numpay) {
			$newInterest	=round(round($monthly,2)*round($saldo,2),2);
			$principal 		=round(round($amount,2)/$numpay,2);
			$parcela		=$principal+$newInterest;

			$saldo			=round($saldo-$principal,2) ;
			$saldoInicial	=$saldo+$principal ;
			$cuotaAcumulada =$cuotaAcumulada + $principal;


			if($i == $ultimaCuota){
				
				$diferencia = round($cuotaAcumulada - $amount,2);
				$principal 	= round($principal - $diferencia,2);//principal
				$parcela 	= round($parcela - $diferencia,2);//cuota
				
				
				$result["detail"][$i]	  				= null;
				$result["detail"][$i]["pnum"] 			= $i;									
				$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
				$result["detail"][$i]["principal"] 		= $principal ;				
				$result["detail"][$i]["interes"] 		= $newInterest ;			
				$result["detail"][$i]["cuota"] 			= $parcela;				
				$result["detail"][$i]["saldo"] 			= 0;				
				$result["detail"][$i]["saldoInicial"] 	= $saldoInicial;
				$result["detail"][$i]["cpmnt"] 			= 0;

			}
			else{
				$result["detail"][$i]	  				= null;
				$result["detail"][$i]["pnum"] 			= $i;									
				$result["detail"][$i]["date"] 			= date_format($nextDate,"Y-m-d");		
				$result["detail"][$i]["principal"] 		= $principal ;				
				$result["detail"][$i]["interes"] 		= $newInterest ;			
				$result["detail"][$i]["cuota"] 			= $parcela;				
				$result["detail"][$i]["saldo"] 			= $saldo ;				
				$result["detail"][$i]["saldoInicial"] 	= $saldoInicial;
				$result["detail"][$i]["cpmnt"] 			= 0;
			}
			$nextDate								= $this->getNextDate($nextDate,$this->periodPay);
			$i++;
		}

		//Plazo con decimales: cuota k+1 prorrateada (saldo restante + interes proporcional)
		if($aplicarFraccion)
		{
			$k				= (int)$cuotasCompletas;
			$fechaFraccion	= $k > 0 ?
								$this->getFechaFraccion(date_create($result["detail"][$k]["date"]),$fraccion,$this->periodPay) :
								clone $this->firstDate;

			$principal		= round($amount - $cuotaAcumulada,2);
			$saldoInicial	= $principal;
			$newInterest	= round(round($monthly,2)*round($saldoInicial,2)*$fraccion,2);
			$parcela		= round($principal + $newInterest,2);
			$i				= $k + 1;

			$result["detail"][$i]	  				= null;
			$result["detail"][$i]["pnum"] 			= $i;
			$result["detail"][$i]["date"] 			= date_format($fechaFraccion,"Y-m-d");
			$result["detail"][$i]["principal"] 		= $principal ;
			$result["detail"][$i]["interes"] 		= $newInterest ;
			$result["detail"][$i]["cuota"] 			= $parcela;
			$result["detail"][$i]["saldo"] 			= 0;
			$result["detail"][$i]["saldoInicial"] 	= $saldoInicial;
			$result["detail"][$i]["cpmnt"] 			= 0;
		}

		return $result;

	}

}

?>