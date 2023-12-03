<?php
//posme:2023-02-27
namespace App\Libraries;
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


class core_web_google 
{
   
   const posme_clientID     = "375862165409-djln0nqcpgsbl0sbov66cadpo4sdr695.apps.googleusercontent.com";
   const posme_clientSecret = "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";
   const posme_urlRedirect  =  URL_REDIRECT_CALENDAR_POSME;
   const posme_scope        = "https://www.googleapis.com/auth/calendar";
   const posme_auth         = "https://accounts.google.com/o/oauth2/auth?";
   const posme_tokenUrl	 	= "https://accounts.google.com/o/oauth2/token";
   const posme_zoneHoariUrl = "https://www.googleapis.com/calendar/v3/users/me/settings/timezone";
   const posme_calendarUrl  = "https://www.googleapis.com/calendar/v3/calendars/";
   
   
   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){		
         
   }
   public function getRequestPermission_Posme($state)
   {			
			$scope         	=   '';
			$client_id      =   '';
			$redirect_uri   =   '';

			$params = array(
								'response_type' =>   'code',
								'client_id'     =>   self::posme_clientID,
								'redirect_uri'  =>   self::posme_urlRedirect,
								'scope'         =>   self::posme_scope,
								'state'			=>	 $state
								);
			$url = self::posme_auth . http_build_query($params);        
			return $url;
   }
   public function getRequestToket_Posme($code)
   {
		//solicitar tocketn 
		$curlPost = 'client_id=' . self::posme_clientID . '&redirect_uri=' . self::posme_urlRedirect . '&client_secret=' . self::posme_clientSecret . '&code='. $code . '&grant_type=authorization_code'; 
		$ch = curl_init();         
		curl_setopt($ch, CURLOPT_URL,self::posme_tokenUrl);         
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
		curl_setopt($ch, CURLOPT_POST, 1);         
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);     
		$data = json_decode(curl_exec($ch), true); 
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
		 
		if ($http_code != 200) { 
			$error_msg = 'Failed to receieve access token'; 
			if (curl_errno($ch)) { 
				$error_msg = curl_error($ch); 
			} 
			throw new \Exception('Error '.$http_code.': '.$error_msg); 
		} 
		return  $data['access_token']; 
   }
   
   public function setEvent_Posme($access_token,$titulo,$descripcion,$date,$hora,$horafin)
   {
	   
			//especificar zona horaria
			$ch = curl_init();         
			curl_setopt($ch, CURLOPT_URL, self::posme_zoneHoariUrl );         
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));     
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
			$data = json_decode(curl_exec($ch), true); 
			$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
			 
			if ($http_code != 200) { 
				$error_msg = 'Failed to fetch timezone'; 
				if (curl_errno($ch)) { 
					$error_msg = curl_error($ch); 
				} 
				throw new \Exception('Error '.$http_code.': '.$error_msg); 
			} 
	 
			$user_timezone = $data['value']; 
			
			
			//crear evento
            $calendar_event = array( 
                'summary' => $titulo, 
                'location' => "Managua", 
                'description' => $descripcion 
            ); 
             
            $event_datetime = array( 
                'event_date' => $date, 
                'start_time' => $hora, 
                'end_time' => $horafin
            ); 
			
			
            $all_day 		= 0;
            $calendar_id 	= 'primary';
			$event_timezone	= $user_timezone;
			$event_data 	= $calendar_event;
			$apiURL 		= self::posme_calendarUrl. $calendar_id . '/events'; 
         
			$curlPost = array(); 			 
			if(!empty($event_data['summary'])){ 
				$curlPost['summary'] = $event_data['summary']; 
			} 
			 
			if(!empty($event_data['location'])){ 
				$curlPost['location'] = $event_data['location']; 
			} 
			 
			if(!empty($event_data['description'])){ 
				$curlPost['description'] = $event_data['description']; 
			} 
			 
			$event_date = !empty($event_datetime['event_date'])?$event_datetime['event_date']:date("Y-m-d"); 
			$start_time = !empty($event_datetime['start_time'])?$event_datetime['start_time']:date("H:i:s"); 
			$end_time = !empty($event_datetime['end_time'])?$event_datetime['end_time']:date("H:i:s"); 
	 
			if($all_day == 1)
			{ 
				$curlPost['start'] = array('date' => $event_date); 
				$curlPost['end'] = array('date' => $event_date); 
			}else
			{ 
				
				$current   			= timezone_open($event_timezone); 
				$utcTime  			= new \DateTime('now', new \DateTimeZone('UTC')); 
				$offsetInSecs 		=  timezone_offset_get($current, $utcTime); 
				$hoursAndSec 		= gmdate('H:i', abs($offsetInSecs)); 
				$timezone_offset 	= stripos($offsetInSecs, '-') === false ? "+{$hoursAndSec}" : "-{$hoursAndSec}"; 
		
				
				$timezone_offset = !empty($timezone_offset)?$timezone_offset:'07:00'; 
				$dateTime_start = $event_date.'T'.$start_time.$timezone_offset; 
				$dateTime_end = $event_date.'T'.$end_time.$timezone_offset; 
				 
				$curlPost['start'] = array('dateTime' => $dateTime_start, 'timeZone' => $event_timezone); 
				$curlPost['end'] = array('dateTime' => $dateTime_end, 'timeZone' => $event_timezone); 
			} 
			$ch = curl_init();         
			curl_setopt($ch, CURLOPT_URL, $apiURL);         
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
			curl_setopt($ch, CURLOPT_POST, 1);         
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));     
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));     
			$data = json_decode(curl_exec($ch), true); 
			$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);         
			 
			if ($http_code != 200) { 
				$error_msg = 'Failed to create event'; 
				if (curl_errno($ch)) { 
					$error_msg = curl_error($ch); 
				} 
				throw new \Exception('Error '.$http_code.': '.$error_msg); 
			} 
	 
			return "evento agregado:".$data['id']; 
			 
   }
  
}
?>