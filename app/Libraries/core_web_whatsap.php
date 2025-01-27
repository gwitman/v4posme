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
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;


class core_web_whatsap {

   /**********************Variables Estaticas********************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
	private $CI;


   /**********************Funciones******************************/
   /*************************************************************/
   /*************************************************************/
   /*************************************************************/
   public function __construct(){

   }
   function validSendMessage($companyID)
   {

		$Parameter_Model 			= new Parameter_Model();
		$Company_Parameter_Model 	= new Company_Parameter_Model();

		$objPWhatsapMonth 					= $Parameter_Model->get_rowByName("WHATSAP_MONTH");
		$objPWhatsapMonthId 				= $objPWhatsapMonth->parameterID;
		$objCP_WhatsapMonth					= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapMonthId);

		$objPWhatsapMessageByMonto 			= $Parameter_Model->get_rowByName("WHATSAP_MESSAGE_BY_MONTO");
		$objPWhatsapMessageByMontoId 		= $objPWhatsapMessageByMonto->parameterID;
		$objCP_WhatsapMessageByMonto		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapMessageByMontoId);

		$objPWhatsapCounterMessage 			= $Parameter_Model->get_rowByName("WHATSAP_COUNTER_MESSAGE");
		$objPWhatsapCounterMessageId		= $objPWhatsapCounterMessage->parameterID;
		$objCP_WhatsapCounterMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapCounterMessageId);

		$objPWhatsapPropertyNumber 			= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
		$objPWhatsapPropertyNumberId 		= $objPWhatsapPropertyNumber->parameterID;
		$objCP_WhatsapPropertyNumber		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPropertyNumberId);

		$objPWhatsapToken 					= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
		$objPWhatsapTokenId 				= $objPWhatsapToken->parameterID;
		$objCP_WhatsapToken					= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapTokenId);

		$objPWhatsapUrlSession				= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
		$objPWhatsapUrlSessionId 			= $objPWhatsapUrlSession->parameterID;
		$objCP_WhatsapUrlSession			= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSessionId);

		$objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
		$objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
		$objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);

		$fechaNow  							= \DateTime::createFromFormat('Y-m-d',date("Y-m-d"));
		$fechaNow							= $fechaNow->format("Y-m")."-01";
		$fechaNow 							= \DateTime::createFromFormat('Y-m-d',$fechaNow);


		$fechaMonth 						= $objCP_WhatsapMonth->value;
		$fechaMonth 						= \DateTime::createFromFormat('Y-m-d',$fechaMonth);



		//permitod enviar el email
		if(  ($fechaNow->format("Y-m-d") == $fechaMonth->format("Y-m-d") ) && (intval($objCP_WhatsapCounterMessage->value) <= intval($objCP_WhatsapMessageByMonto->value))  )
		{
			//ingrementar el contador
			$data 			= null;
			$data["value"]	= intval($objCP_WhatsapCounterMessage->value) + 1;
			$Company_Parameter_Model->update_app_posme($objCP_WhatsapCounterMessage->companyID,$objCP_WhatsapCounterMessage->parameterID,$data);

			return true;
		}
		if( ($fechaNow > $fechaMonth) && (intval($objCP_WhatsapCounterMessage->value) > 0)  )
		{
			//actualizar la fecha
			$data 			= null;
			$data["value"]	= $fechaNow->format("Y-m-d");
			$Company_Parameter_Model->update_app_posme($objCP_WhatsapMonth->companyID,$objCP_WhatsapMonth->parameterID,$data);

			//actualizar el contador en 1
			$data 			= null;
			$data["value"]	= "1";
			$Company_Parameter_Model->update_app_posme($objCP_WhatsapCounterMessage->companyID,$objCP_WhatsapCounterMessage->parameterID,$data);



			return true;

		}

		//no enviar el whatsapp
		return false;


   }

   function sendMessage($companyID,$message)
   {
	    //https://app.whaticket.com/tickets
	    //Cada mensaje cuesta al cliene: 0.2 dolares
	    //Habilitar 30 mensaje le cuesta: 6 dolares mensuales
		//try
		//{
			$Parameter_Model 			= new Parameter_Model();
			$Company_Parameter_Model 	= new Company_Parameter_Model();


			$objPWhatsapPropertyNumber 			= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
			$objPWhatsapPropertyNumberId 		= $objPWhatsapPropertyNumber->parameterID;
			$objCP_WhatsapPropertyNumber		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPropertyNumberId);

			$objPWhatsapToken 					= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
			$objPWhatsapTokenId 				= $objPWhatsapToken->parameterID;
			$objCP_WhatsapToken					= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapTokenId);

			//https://api.whaticket.com/api/v1/whatsapps
			$objPWhatsapUrlSession				= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
			$objPWhatsapUrlSessionId 			= $objPWhatsapUrlSession->parameterID;
			$objCP_WhatsapUrlSession			= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSessionId);


			//https://api.whaticket.com/api/v1/messages
			$objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
			$objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
			$objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);


			//Obtener session
			$clientCurl = Services::curlrequest();
			$response = $clientCurl->request('GET', $objCP_WhatsapUrlSession->value, [
				'headers' => [
					'Content-Type' 	=> 'application/json',
					'accept'     	=> 'application/json',
					'Authorization' => "Bearer ".$objCP_WhatsapToken->value
				],

			]);


			$response 	= json_decode($response->getBody(), true);
			if(count($response) > 0)
			{
				if(array_key_exists("id",$response[0]))
				{
					$sessionId 	= $response[0]["id"];
					//Enviar whatsapp
					$sendWhatsapp 								= array();
					$sendWhatsapp["whatsappId"] 				= $sessionId;
					$sendWhatsapp["messages"]					= array();
					$sendWhatsapp["messages"][0]["number"]		= $objCP_WhatsapPropertyNumber->value;
					$sendWhatsapp["messages"][0]["name"]		= "posMe";
					$sendWhatsapp["messages"][0]["body"]		= $message;

					$clientCurl2 = Services::curlrequest();
					$response = $clientCurl2->request('POST', $objCP_WhatsapUrlSendMessage->value,
						[
							'headers' => [
								'Content-Type' 	=> 'application/x-www-form-urlencoded',
								'accept'     	=> '*/*'
							],
							"debug" => true,
							"form_params" => $sendWhatsapp
						]
					);

				}

			}
		//}
		//catch(\Exception $ex)
		//{
		//		exit($ex->getMessage());
		//}



   }

   function sendMessageByLiveconnect($companyID, $message, $phoneDestino){
        //2024-07-22
        //api token: https://api.liveconnect.chat/prod/account/token

        $Parameter_Model 			= new Parameter_Model();
        $Company_Parameter_Model 	= new Company_Parameter_Model();

        $objPWhatsapPrivatekey		= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
        $objPWhatsapPrivatekeyId	= $objPWhatsapPrivatekey->parameterID;
        $objPWhatsapPrivatekey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $objPWhatsapCkey		= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
        $objPWhatsapCkeyId		= $objPWhatsapCkey->parameterID;
        $objPWhatsapCkey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapCkeyId);

        $objPWhatsapUrlTokenMessage			= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
        $objPWhatsapUrlTokenMessageId 		= $objPWhatsapUrlTokenMessage->parameterID;
        $objCP_WhatsapUrlTokenMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlTokenMessageId);

        $objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
        $objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
        $objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);

        //id canal
        $objPWhatsapIdCanal		= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION_PARAMETERF1");
        $objPWhatsapPrivatekeyId	= $objPWhatsapIdCanal->parameterID;
        $objPWhatsapIdCanal		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $objCP_WhatsapUrlTokenMessage->value,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'cKey' => $objPWhatsapCkey->value,
                'privateKey' => $objPWhatsapPrivatekey->value
            ]),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json, application/xml",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        }
        //return $response;
        $response_data 	= json_decode($response, true);

        if($response_data['status'] ==1)
        {
            $token = $response_data['PageGearToken'];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $objCP_WhatsapUrlSendMessage->value,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'id_canal' => $objPWhatsapIdCanal->value,
                    'numero'=>$phoneDestino,
                    'mensaje' => $message
                ]),
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "PageGearToken: ".$token
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                $response_data 	= json_decode($response, true);
                return $response_data['status_message'];
            }
        }
        return "";
    }
	
	
	function sendMessageByLiveconnectFileGlobalPro($companyID, $message, $phoneDestino){
        //2024-07-22
        //api token: https://api.liveconnect.chat/prod/account/token

        $Parameter_Model 			= new Parameter_Model();
        $Company_Parameter_Model 	= new Company_Parameter_Model();

        $objPWhatsapPrivatekey		= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
        $objPWhatsapPrivatekeyId	= $objPWhatsapPrivatekey->parameterID;
        $objPWhatsapPrivatekey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $objPWhatsapCkey		= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
        $objPWhatsapCkeyId		= $objPWhatsapCkey->parameterID;
        $objPWhatsapCkey		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapCkeyId);

        $objPWhatsapUrlTokenMessage			= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
        $objPWhatsapUrlTokenMessageId 		= $objPWhatsapUrlTokenMessage->parameterID;
        $objCP_WhatsapUrlTokenMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlTokenMessageId);

        $objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
        $objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
        $objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);

        //id canal
        $objPWhatsapIdCanal		= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION_PARAMETERF1");
        $objPWhatsapPrivatekeyId	= $objPWhatsapIdCanal->parameterID;
        $objPWhatsapIdCanal		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPrivatekeyId);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $objCP_WhatsapUrlTokenMessage->value,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'cKey' => $objPWhatsapCkey->value,
                'privateKey' => $objPWhatsapPrivatekey->value
            ]),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json, application/xml",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        }
        //return $response;
        $response_data 	= json_decode($response, true);

        if($response_data['status'] ==1)
        {
            $token = $response_data['PageGearToken'];

            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.liveconnect.chat/prod/direct/wa/sendFile",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'id_canal' => $objPWhatsapIdCanal->value,
                    'numero'=>$phoneDestino,                    
					"url"=> "https://posme.net/v4posme/posme/public/resource/img/logos/logo-micro-finanza.jpg",
					"nombre"=> "logo-micro-finanza",
				    "extension"=> "jpg"
  
                ]),
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "PageGearToken: ".$token
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                $response_data 	= json_decode($response, true);
                return $response_data['status_message'];
            }
        }
        return "";
    }

   function sendMessageByWaapi($companyID, $message, $phoneDestino)
   {
	    //2024-06-30
		//https://waapi.app/account/
		//tocken  qMAsXGyf0jIswU6xttfuZvORRhCRJnlrLClmlBgMe31db7ac
		//tocken  S0EEmlFcUcvlDRdW3cIE8WQedbtdk2GVRKypXWJu8649891a
		//api     https://waapi.app/api/v1/instances/12905/client/action/send-message

		//gabriel.ley@grupogasani.com
		//Sistema123.


		$Parameter_Model 			= new Parameter_Model();
		$Company_Parameter_Model 	= new Company_Parameter_Model();


		$objPWhatsapPropertyNumber 			= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
		$objPWhatsapPropertyNumberId 		= $objPWhatsapPropertyNumber->parameterID;
		$objCP_WhatsapPropertyNumber		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPropertyNumberId);

		$objPWhatsapToken 					= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
		$objPWhatsapTokenId 				= $objPWhatsapToken->parameterID;
		$objCP_WhatsapToken					= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapTokenId);

		$objPWhatsapUrlSession				= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
		$objPWhatsapUrlSessionId 			= $objPWhatsapUrlSession->parameterID;
		$objCP_WhatsapUrlSession			= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSessionId);

		//https://api.ultramsg.com/instance65915/messages/chat
		$objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WHATSAP_URL_ENVIO_MENSAJE");
		$objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
		$objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);



		$phoneDestino	= !isset($phoneDestino) ? "" : $phoneDestino;
		$phoneDestino	= is_null($phoneDestino) ? "" : $phoneDestino;
		$phoneDestino	= empty($phoneDestino) ? $objCP_WhatsapPropertyNumber->value : $phoneDestino;


		$clientCurlRequest		= Services::curlrequest();
		$response  				= $clientCurlRequest->request(
			'POST',
			$objCP_WhatsapUrlSendMessage->value,
			[
				'body' => '{"chatId":"'.$phoneDestino.'@c.us","message":"'.$message.'"}',
				'headers' 		=> [
					'accept'     	=> 'application/json',
					'authorization' => 'Bearer '.$objCP_WhatsapToken->value,
					'content-type' => 'application/json'
				]
			]
		);


   }


   function sendMessageUltramsg( $companyID, $message, $phoneDestino)
   {
		//password: 180389Witman
		//usuario: wgonzalez@gruposi.com
	    //https://user.ultramsg.com/
	    //Cada mensaje cuesta al cliene: 0.2 dolares
	    //Habilitar 30 mensaje le cuesta: 6 dolares mensuales
		//try
		//{
			$Parameter_Model 			= new Parameter_Model();
			$Company_Parameter_Model 	= new Company_Parameter_Model();


			$objPWhatsapPropertyNumber 			= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
			$objPWhatsapPropertyNumberId 		= $objPWhatsapPropertyNumber->parameterID;
			$objCP_WhatsapPropertyNumber		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPropertyNumberId);

			$objPWhatsapToken 					= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
			$objPWhatsapTokenId 				= $objPWhatsapToken->parameterID;
			$objCP_WhatsapToken					= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapTokenId);

			$objPWhatsapUrlSession				= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
			$objPWhatsapUrlSessionId 			= $objPWhatsapUrlSession->parameterID;
			$objCP_WhatsapUrlSession			= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSessionId);


			//https://api.ultramsg.com/instance65915/messages/chat
			$objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
			$objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
			$objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);



			$phoneDestino	= !isset($phoneDestino) ? "" : $phoneDestino;
			$phoneDestino	= is_null($phoneDestino) ? "" : $phoneDestino;
			$phoneDestino	= empty($phoneDestino) ? $objCP_WhatsapPropertyNumber->value : $phoneDestino;

			$params=array(
			'token' 	=> $objCP_WhatsapToken->value,
			'to' 		=> $phoneDestino,
			'body' 		=> $message
			);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $objCP_WhatsapUrlSendMessage->value,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_SSL_VERIFYHOST => 0,
			  CURLOPT_SSL_VERIFYPEER => 0,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => http_build_query($params),
			  CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err)
			{
			  echo "cURL Error #:" . $err;
			}
			else
			{
			  echo $response;
			}


		//}
		//catch(\Exception $ex)
		//{
		//		exit($ex->getMessage());
		//}



   }

   function sendMessageTypeImagUltramsg( $companyID, $message,$title, $phoneDestino="" )
   {
		//password: 180389Witman
		//usuario: wgonzalez@gruposi.com
	    //https://user.ultramsg.com/
	    //Cada mensaje cuesta al cliene: 0.2 dolares
	    //Habilitar 30 mensaje le cuesta: 6 dolares mensuales
		//try
		//{
			$Parameter_Model 			= new Parameter_Model();
			$Company_Parameter_Model 	= new Company_Parameter_Model();


			$objPWhatsapPropertyNumber 			= $Parameter_Model->get_rowByName("WHATSAP_CURRENT_PROPIETARY_COMMERSE");
			$objPWhatsapPropertyNumberId 		= $objPWhatsapPropertyNumber->parameterID;
			$objCP_WhatsapPropertyNumber		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapPropertyNumberId);

			$objPWhatsapToken 					= $Parameter_Model->get_rowByName("WHATSAP_TOCKEN");
			$objPWhatsapTokenId 				= $objPWhatsapToken->parameterID;
			$objCP_WhatsapToken					= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapTokenId);

			$objPWhatsapUrlSession				= $Parameter_Model->get_rowByName("WHATSAP_URL_REQUEST_SESSION");
			$objPWhatsapUrlSessionId 			= $objPWhatsapUrlSession->parameterID;
			$objCP_WhatsapUrlSession			= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSessionId);


			//https://api.ultramsg.com/instance65915/messages/chat
			$objPWhatsapUrlSendMessage			= $Parameter_Model->get_rowByName("WAHTSAP_URL_ENVIO_MENSAJE");
			$objPWhatsapUrlSendMessageId 		= $objPWhatsapUrlSendMessage->parameterID;
			$objCP_WhatsapUrlSendMessage		= $Company_Parameter_Model->get_rowByParameterID_CompanyID($companyID,$objPWhatsapUrlSendMessageId);



			$phoneDestino	= isset($phoneDestino) ? "" : $phoneDestino;
			$phoneDestino	= is_null($phoneDestino) ? "" : $phoneDestino;
			$phoneDestino	= empty($phoneDestino) ? $objCP_WhatsapPropertyNumber->value : $phoneDestino;


			$params=array(
			'token' 	=> $objCP_WhatsapToken->value,
			'to' 		=> $phoneDestino,
			'image'		=> $message,
			'caption'	=> $title
			);
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $objCP_WhatsapUrlSendMessage->value,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_SSL_VERIFYHOST => 0,
			  CURLOPT_SSL_VERIFYPEER => 0,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => http_build_query($params),
			  CURLOPT_HTTPHEADER => array(
				"content-type: application/x-www-form-urlencoded"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err)
			{
			  echo "cURL Error #:" . $err;
			}
			else
			{
			  echo $response;
			}


		//}
		//catch(\Exception $ex)
		//{
		//		exit($ex->getMessage());
		//}



   }


}
?>