<?php
//posme:2023-02-27
namespace App\Controllers;
use App\Models\Item_Model;
use CodeIgniter\Session\Session;
use Config\Services;

class app_mobile_api extends _BaseController
{

	function setPositionGps()
	{
		$nickname 					= /*inicio get post*/ $this->request->getPost("txtNickname");
		$password 					= /*inicio get post*/ $this->request->getPost("txtPassword");
		$latituded					= /*inicio get post*/ $this->request->getPost("txtLatituded");
		$longituded 				= /*inicio get post*/ $this->request->getPost("txtLongituded");
		$reference1 				= /*inicio get post*/ $this->request->getPost("txtReference1");
		$companyName 				= /*inicio get post*/ $this->request->getPost("txtCompanyName");
		$objPosition				= null;
		
		try {
			
            $objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);			
			$objPosition["entityID"]	= $objUser["user"]->employeeID;
        } 
		catch (\Exception $ex) 
		{			
            $objPosition["entityID"]	= 0;
        }
				
		$objPosition["isActive"]	= 1;
		$objPosition["createdOn"]	= helper_getDateTime();
		$objPosition["latituded"]	= $latituded;
		$objPosition["longituded"]	= $longituded;
		$objPosition["reference1"]	= $reference1;
		$objPosition["userName"]	= $nickname;
		$objPosition["companyName"]	= $companyName;
		$positionID					= $this->Entity_Location_Model->insert_app_posme($objPosition);
		
		return $this->response->setJSON(array(
			'error' => false,
			'message' => SUCCESS
		));//--finjson
		
		
	}
	function getPositionGps()
	{		
		$txtCompanyName 				= /*inicio get post*/ $this->request->getPost("txtCompanyName");
		$txtUserName 					= /*inicio get post*/ $this->request->getPost("txtUserName");		
		$txtIndex						= /*inicio get post*/ $this->request->getPost("txtIndex");		
		
		
		$txtCompanyName 		= str_replace("X3A", ":", $txtCompanyName);
		$txtCompanyName 		= str_replace("X2F", "/", $txtCompanyName);
		$txtCompanyName 		= str_replace("X4Z", " ", $txtCompanyName);			
			
		$objListRegisteredLocations                 = $this->Entity_Location_Model->get_UsersLocationByCompanyAndUserLast($txtCompanyName,$txtUserName);
		
		return $this->response->setJSON(array(
			'error' 	=> false,
			'message' 	=> SUCCESS,
			'index' 	=> $txtIndex, 
			'data' 		=> $objListRegisteredLocations
		));//--finjson
	}
    function setDataUpload()
    {
        try {


			log_message("error",print_r("0001",true));
            $nickname 						= "facmob";
            $password 						= "fac789";
			//$nickname 					= /*inicio get post*/ $this->request->getPost("txtNickname");
            //$password 					= /*inicio get post*/ $this->request->getPost("txtPassword");
			
            $objUser 					= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);
			$objListCustomerMap			= [];
            $companyID 					= $objUser["user"]->companyID;
            Services::session()->set("user", $objUser["user"]);
            $objCompany 				= $objUser["company"];
			
            //$objItemsJson 			= "%7B%22ObjCustomers%22%3A%5B%7B%22CustomerId%22%3A229%2C%22companyID%22%3A2%2C%22branchID%22%3A2%2C%22entityID%22%3A-1%2C%22customerNumber%22%3A%22-3%22%2C%22identification%22%3A%2257432550%22%2C%22firstName%22%3A%22ELVIS%22%2C%22lastName%22%3A%22Garcia%22%2C%22balance%22%3A0.0%2C%22currencyID%22%3A1%2C%22currencyName%22%3A%22Cordoba%22%2C%22customerCreditLineID%22%3A-2%7D%2C%7B%22CustomerId%22%3A230%2C%22companyID%22%3A2%2C%22branchID%22%3A2%2C%22entityID%22%3A-4%2C%22customerNumber%22%3A%22-6%22%2C%22identification%22%3A%2284311951%22%2C%22firstName%22%3A%22Julio%22%2C%22lastName%22%3A%22Palacio%22%2C%22balance%22%3A0.0%2C%22currencyID%22%3A1%2C%22currencyName%22%3A%22Cordoba%22%2C%22customerCreditLineID%22%3A-5%7D%2C%7B%22CustomerId%22%3A231%2C%22companyID%22%3A2%2C%22branchID%22%3A2%2C%22entityID%22%3A-7%2C%22customerNumber%22%3A%22-9%22%2C%22identification%22%3A%2289391639%22%2C%22firstName%22%3A%22Liset%22%2C%22lastName%22%3A%22Laso%22%2C%22balance%22%3A0.0%2C%22currencyID%22%3A1%2C%22currencyName%22%3A%22Cordoba%22%2C%22customerCreditLineID%22%3A-8%7D%2C%7B%22CustomerId%22%3A232%2C%22companyID%22%3A2%2C%22branchID%22%3A2%2C%22entityID%22%3A-10%2C%22customerNumber%22%3A%22-12%22%2C%22identification%22%3A%2281102767%22%2C%22firstName%22%3A%22Norma+%22%2C%22lastName%22%3A%22Perez%22%2C%22balance%22%3A0.0%2C%22currencyID%22%3A1%2C%22currencyName%22%3A%22Cordoba%22%2C%22customerCreditLineID%22%3A-11%7D%2C%7B%22CustomerId%22%3A233%2C%22companyID%22%3A2%2C%22branchID%22%3A2%2C%22entityID%22%3A-13%2C%22customerNumber%22%3A%22-15%22%2C%22identification%22%3A%2288297019%22%2C%22firstName%22%3A%22Eddy%22%2C%22lastName%22%3A%22Martines%22%2C%22balance%22%3A0.0%2C%22currencyID%22%3A1%2C%22currencyName%22%3A%22Cordoba%22%2C%22customerCreditLineID%22%3A-14%7D%5D%2C%22ObjItems%22%3A%5B%5D%2C%22ObjTransactionMaster%22%3A%5B%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A28%2C%22TransactionNumber%22%3A%22FAC-0028%22%2C%22EntityId%22%3A750%2C%22TransactionOn%22%3A%222025-04-12T13%3A33%3A15.1544477%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22CustomerCreditLineId%22%3A505%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%2286911644%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A29%2C%22TransactionNumber%22%3A%22FAC-0029%22%2C%22EntityId%22%3A-1%2C%22TransactionOn%22%3A%222025-04-12T13%3A44%3A49.8783215%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A400.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A400.0%2C%22CustomerCreditLineId%22%3A-2%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%2257432550%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A30%2C%22TransactionNumber%22%3A%22FAC-0030%22%2C%22EntityId%22%3A-4%2C%22TransactionOn%22%3A%222025-04-12T13%3A55%3A29.4031587%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A280.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A280.0%2C%22CustomerCreditLineId%22%3A-5%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%2284311951%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A31%2C%22TransactionNumber%22%3A%22FAC-0031%22%2C%22EntityId%22%3A-4%2C%22TransactionOn%22%3A%222025-04-12T13%3A56%3A58.9943425%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A350.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A350.0%2C%22CustomerCreditLineId%22%3A-5%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%2284311951%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A32%2C%22TransactionNumber%22%3A%22FAC-0032%22%2C%22EntityId%22%3A-7%2C%22TransactionOn%22%3A%222025-04-12T14%3A05%3A49.9661948%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A750.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A750.0%2C%22CustomerCreditLineId%22%3A-8%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%2289391639%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A33%2C%22TransactionNumber%22%3A%22FAC-0033%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T14%3A09%3A45.8565675%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A34%2C%22TransactionNumber%22%3A%22FAC-0034%22%2C%22EntityId%22%3A309%2C%22TransactionOn%22%3A%222025-04-12T14%3A11%3A27.6917315%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22CustomerCreditLineId%22%3A7%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000020%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A35%2C%22TransactionNumber%22%3A%22FAC-0035%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T14%3A16%3A41.4297921%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A36%2C%22TransactionNumber%22%3A%22FAC-0036%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T14%3A23%3A12.1431137%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A300.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A300.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A37%2C%22TransactionNumber%22%3A%22FAC-0037%22%2C%22EntityId%22%3A309%2C%22TransactionOn%22%3A%222025-04-12T14%3A25%3A16.3983635%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A140.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A140.0%2C%22CustomerCreditLineId%22%3A7%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000020%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A38%2C%22TransactionNumber%22%3A%22FAC-0038%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T14%3A31%3A07.6027487%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A390.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A390.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A39%2C%22TransactionNumber%22%3A%22FAC-0039%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T14%3A34%3A26.4279761%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A330.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A330.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A40%2C%22TransactionNumber%22%3A%22FAC-0040%22%2C%22EntityId%22%3A-10%2C%22TransactionOn%22%3A%222025-04-12T14%3A53%3A09.4544203%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A260.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A260.0%2C%22CustomerCreditLineId%22%3A-11%2C%22TransactionCausalId%22%3A22%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%2281102767%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A41%2C%22TransactionNumber%22%3A%22FAC-0041%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T15%3A04%3A41.1363012%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A700.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A700.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A42%2C%22TransactionNumber%22%3A%22FAC-0042%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T15%3A08%3A31.3270981%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A210.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A210.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A43%2C%22TransactionNumber%22%3A%22FAC-0043%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T15%3A16%3A18.9733093%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A44%2C%22TransactionNumber%22%3A%22FAC-0044%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T15%3A20%3A14.8162855%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A80.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A80.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A45%2C%22TransactionNumber%22%3A%22FAC-0045%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T15%3A23%3A07.0040606%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A46%2C%22TransactionNumber%22%3A%22FAC-0046%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T15%3A32%3A17.5888665%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A830.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A830.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A47%2C%22TransactionNumber%22%3A%22FAC-0047%22%2C%22EntityId%22%3A-13%2C%22TransactionOn%22%3A%222025-04-12T15%3A38%3A18.9847333%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A240.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A240.0%2C%22CustomerCreditLineId%22%3A-14%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%2288297019%22%7D%2C%7B%22TransactionId%22%3A19%2C%22TypePaymentId%22%3A3%2C%22TransactionMasterId%22%3A48%2C%22TransactionNumber%22%3A%22FAC-0048%22%2C%22EntityId%22%3A13%2C%22TransactionOn%22%3A%222025-04-12T16%3A10%3A46.35601%22%2C%22TransactionOn2%22%3A%220001-01-01T00%3A00%3A00%22%2C%22NextVisit%22%3A%222025-04-12T00%3A00%3A00%22%2C%22Plazo%22%3A1%2C%22FixedExpenses%22%3A0.0%2C%22PeriodPay%22%3A190%2C%22EntitySecondaryId%22%3A%22764%22%2C%22SubAmount%22%3A530.0%2C%22Discount%22%3A0.0%2C%22Taxi1%22%3A0.0%2C%22Amount%22%3A530.0%2C%22CustomerCreditLineId%22%3A359%2C%22TransactionCausalId%22%3A21%2C%22ExchangeRate%22%3A0.0%2C%22CurrencyId%22%3A1%2C%22Comment%22%3A%22Sin+Comentarios%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%2C%22Reference3%22%3A%22%22%2C%22CustomerIdentification%22%3A%22CLI00000000%22%7D%5D%2C%22ObjTransactionMasterDetail%22%3A%5B%7B%22TransactionMasterDetailId%22%3A45%2C%22TransactionMasterId%22%3A28%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A46%2C%22TransactionMasterId%22%3A29%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A5.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A400.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A400.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A47%2C%22TransactionMasterId%22%3A30%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29105%2C%22Quantity%22%3A4.0%2C%22UnitaryCost%22%3A70.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A280.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A280.0%2C%22ItemBarCode%22%3A%22777700001073%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A48%2C%22TransactionMasterId%22%3A31%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29105%2C%22Quantity%22%3A5.0%2C%22UnitaryCost%22%3A70.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A350.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A350.0%2C%22ItemBarCode%22%3A%22777700001073%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A49%2C%22TransactionMasterId%22%3A32%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29107%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A160.0%2C%22UnitaryPrice%22%3A175.0%2C%22SubAmount%22%3A350.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A350.0%2C%22ItemBarCode%22%3A%22777700001075%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A50%2C%22TransactionMasterId%22%3A32%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29108%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A220.0%2C%22UnitaryPrice%22%3A220.0%2C%22SubAmount%22%3A220.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A220.0%2C%22ItemBarCode%22%3A%22777700001076%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A51%2C%22TransactionMasterId%22%3A32%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29110%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A180.0%2C%22UnitaryPrice%22%3A180.0%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22ItemBarCode%22%3A%22777700001078%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A52%2C%22TransactionMasterId%22%3A33%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29107%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A160.0%2C%22UnitaryPrice%22%3A180.0%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22ItemBarCode%22%3A%22777700001075%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A53%2C%22TransactionMasterId%22%3A34%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29107%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A160.0%2C%22UnitaryPrice%22%3A180.0%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22ItemBarCode%22%3A%22777700001075%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A54%2C%22TransactionMasterId%22%3A35%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A55%2C%22TransactionMasterId%22%3A36%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A56%2C%22TransactionMasterId%22%3A36%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29105%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A70.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A140.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A140.0%2C%22ItemBarCode%22%3A%22777700001073%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A57%2C%22TransactionMasterId%22%3A37%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29105%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A70.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A140.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A140.0%2C%22ItemBarCode%22%3A%22777700001073%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A58%2C%22TransactionMasterId%22%3A38%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A3.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A210.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A210.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A59%2C%22TransactionMasterId%22%3A38%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29110%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A180.0%2C%22UnitaryPrice%22%3A180.0%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22ItemBarCode%22%3A%22777700001078%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A60%2C%22TransactionMasterId%22%3A39%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A80.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A80.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A61%2C%22TransactionMasterId%22%3A39%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29107%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A160.0%2C%22UnitaryPrice%22%3A180.0%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22ItemBarCode%22%3A%22777700001075%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A62%2C%22TransactionMasterId%22%3A39%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29105%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A70.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A70.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A70.0%2C%22ItemBarCode%22%3A%22777700001073%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A63%2C%22TransactionMasterId%22%3A40%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A80.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A80.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A64%2C%22TransactionMasterId%22%3A40%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29107%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A160.0%2C%22UnitaryPrice%22%3A180.0%2C%22SubAmount%22%3A180.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A180.0%2C%22ItemBarCode%22%3A%22777700001075%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A65%2C%22TransactionMasterId%22%3A41%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A10.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A700.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A700.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A66%2C%22TransactionMasterId%22%3A42%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A3.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A210.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A210.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A67%2C%22TransactionMasterId%22%3A43%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A68%2C%22TransactionMasterId%22%3A44%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A80.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A80.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A69%2C%22TransactionMasterId%22%3A45%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A70%2C%22TransactionMasterId%22%3A46%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A6.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A480.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A480.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A71%2C%22TransactionMasterId%22%3A46%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29107%2C%22Quantity%22%3A2.0%2C%22UnitaryCost%22%3A160.0%2C%22UnitaryPrice%22%3A175.0%2C%22SubAmount%22%3A350.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A350.0%2C%22ItemBarCode%22%3A%22777700001075%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A72%2C%22TransactionMasterId%22%3A47%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A3.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A240.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A240.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A73%2C%22TransactionMasterId%22%3A48%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29105%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A70.0%2C%22UnitaryPrice%22%3A70.0%2C%22SubAmount%22%3A70.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A70.0%2C%22ItemBarCode%22%3A%22777700001073%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A74%2C%22TransactionMasterId%22%3A48%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29108%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A220.0%2C%22UnitaryPrice%22%3A220.0%2C%22SubAmount%22%3A220.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A220.0%2C%22ItemBarCode%22%3A%22777700001076%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A75%2C%22TransactionMasterId%22%3A48%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29118%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A85.0%2C%22UnitaryPrice%22%3A80.0%2C%22SubAmount%22%3A80.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A80.0%2C%22ItemBarCode%22%3A%22777700001086%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%2C%7B%22TransactionMasterDetailId%22%3A76%2C%22TransactionMasterId%22%3A48%2C%22Componentid%22%3A33%2C%22ComponentItemId%22%3A29112%2C%22Quantity%22%3A1.0%2C%22UnitaryCost%22%3A160.0%2C%22UnitaryPrice%22%3A160.0%2C%22SubAmount%22%3A160.0%2C%22Discount%22%3A0.0%2C%22Tax1%22%3A0.0%2C%22Amount%22%3A160.0%2C%22ItemBarCode%22%3A%22777700001080%22%2C%22Reference1%22%3A%22%22%2C%22Reference2%22%3A%22%22%7D%5D%7D";
            //$data 					= json_decode(urldecode($objItemsJson), false);
			
			
			//$objItemsJson 				= '{  "ObjCustomers" : [ {    "CustomerId" : 154,    "companyID" : 2,    "branchID" : 2,    "entityID" : 13,    "customerNumber" : "CLI00000000",    "identification" : "000-000000-0000A",    "firstName" : "CLIENTE GENERICO",    "lastName" : "DEFAULT",    "balance" : 0.0,    "currencyID" : 1,    "currencyName" : "C$",    "customerCreditLineID" : 359,    "location" : "PANCASAN 2 Mob",    "phone" : "78945612"  }, {    "CustomerId" : 155,    "companyID" : 2,    "branchID" : 2,    "entityID" : -1,    "customerNumber" : "-3",    "identification" : "000-000000-0000G",    "firstName" : "Test mobil",    "lastName" : "Test mobil",    "balance" : 0.0,    "currencyID" : 1,    "currencyName" : "Cordoba",    "customerCreditLineID" : -2,    "location" : "ciudad sandino",    "phone" : "78945612"  } ],  "ObjItems" : [ ],  "ObjTransactionMaster" : [ ],  "ObjTransactionMasterDetail" : [ ]}';		
            //$data 						= json_decode($objItemsJson, false);
			
			
			$objItemsJson 				= $this->request->getPost("txtData");			
            $data 						= json_decode($objItemsJson, false);
			
			
			log_message("error",print_r("datos cargados----->",true));
			log_message("error",print_r($objItemsJson,true));
			
			
			log_message("error",print_r("0002",true));
            if(!isset($data)) {
                return $this->response->setJSON(array(
                    'error' => false,
                    'message' => 'No hay datos a ingresar'
                ));//--finjson
            }
            $items 						= $data->ObjItems;
            $customers                  = $data->ObjCustomers;
            $transactionMasters         = $data->ObjTransactionMaster;
            $transactionMasterDetails   = $data->ObjTransactionMasterDetail;
            $dataSession['user'] 		= $objUser["user"];
            $dataSession['company'] 	= $objCompany;
            $dataSession['role'] 		= $objUser["role"];
            $this->core_web_permission->getValueLicense($companyID,get_class($this)."/"."index");
			log_message("error",print_r("0003",true));
			
			// APLICAR VALIDACIONES
			// 001 validar employer del usuario
			$employee		= $this->Employee_Model->get_rowByEntityID($companyID,$dataSession["user"]->employeeID );
			if(!$employee)
			{
				throw new \Exception("El usuario no tiene un colaborador asignado");
			}
			
			// 002 validar bodega despacho del usuario
			$objListWarehouseTipoDespacho	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID,$objUser["user"]->userID);
			if(!$objListWarehouseTipoDespacho)
			{
				throw new \Exception("El usuario no tiene una bodega tipo despacho configurada");
			}
			
			
			
			log_message("error",print_r("0004",true));
            // INICIO DE CARGA DE ITEMS
            if (count($items) > 0) {
				
                $controller 				= new app_inventory_item();
				log_message("error",print_r("0004.001",true));
                $controller->initController($this->request, $this->response, $this->logger);
                foreach ($items as $va)
                {
					
                    $objOldItem = $this->Item_Model->get_rowByCodeBarra($companyID, $va->barCode);
					log_message("error",print_r("0004.002",true));
                    if (!is_null($objOldItem))
                    {					
                        $method = "edit_customer_mobile";
                        $va->itemID= $objOldItem->itemID;
						log_message("error",print_r("0004.003",true));
                    }
                    else
                    {					
                        $method = "new_customer_mobile";
						log_message("error",print_r("0004.004",true));
                    }
					
                    $controller->save($method, $va, $dataSession);
					
                }
            }
			log_message("error",print_r("0005",true));

            //INICIO DE CARGA DE CUSTOMERS
			$idexCount = 0;
            if (count($customers) > 0) 
			{
				
                $controller = new app_cxc_customer();
                $controller->initController($this->request, $this->response, $this->logger);
				log_message("error",print_r("0005.001",true));
                foreach ($customers as $cus)
                {
                    $companyID	= $cus->companyID;
                    $branchID	= $cus->branchID;
                    $entityID	= $cus->entityID;
					$location   = $cus->location;
					$phone		= $cus->phone;
					log_message("error",print_r("0005.006",true));
                    //si entityid es null o 0, es nuevo, sino un update
                    $objCustomer				= $this->Customer_Model->get_rowByPK($companyID,$branchID,$entityID);
					$objCustomerByIdentifier 	= $this->Customer_Model->get_rowByIdentification($companyID,$cus->identification);
					$objCustomer				= !is_null($objCustomer) ? $objCustomer : false;
					$objCustomer				= $objCustomer ? $objCustomer : $objCustomerByIdentifier;
					
					
                    if (
						!$objCustomer
					)
					{
						
                        $objDataSet 									= $controller->insertElementMobile($dataSession,$cus);
						$entityIDOld 									= $customers[$idexCount]->entityID;
						$customerCreditLineIDOld 						= $customers[$idexCount]->customerCreditLineID;
						log_message("error",print_r("0005.007",true));
						log_message("error",print_r($objDataSet,true));
						
						//Validar si se ingreso bien el customer
						if (!is_array($objDataSet)) 
						{
							if (preg_match('/Linea:.*?\.php/', $objDataSet, $coincidencias)) 
							{
								throw new \Exception($coincidencias[0]);								
							} 
							else 
							{
								throw new \Exception("Error al crear el cliente.");
							}
						} 

						
						$customers[$idexCount]->entityID 				= $objDataSet["entityID"];
						$customers[$idexCount]->customerNumber 			= $objDataSet["customerNumber"];
						$customers[$idexCount]->customerCreditLineID 	= $objDataSet["customerCreditLineID"];
						log_message("error",print_r("0005.008",true));
						$objCustomerMaps = (object)[
								'entityIDOld' 				=> $entityIDOld,
								'customerCreditLineIDOld' 	=> $customerCreditLineIDOld,
								'entityID' 					=> $customers[$idexCount]->entityID,
								'customerCreditLineID' 		=> $customers[$idexCount]->customerCreditLineID 
						];
						$objListCustomerMap[] 							= $objCustomerMaps;
						log_message("error",print_r("0005.009",true));
                    }
					else
					{
                        $objCustomer			=json_decode(json_encode($objCustomer));
						$objCustomerCreditLine 	=$this->Customer_Credit_Line_Model->get_rowByEntity($objCustomer->companyID,$objCustomer->branchID,$objCustomer->entityID);
						log_message("error",print_r("0005.010",true));
                        $objCustomer->firstName 	= $cus->firstName;
                        $objCustomer->lastName		= $cus->lastName;
						$objCustomer->location		= $cus->location;
						$objCustomer->phoneNumber	= $cus->phone;
                        $controller->updateElementMobile($dataSession, $objCustomer);
						log_message("error",print_r("0005.011",true));
						
						$entityIDOld 						= $customers[$idexCount]->entityID;
						$customerCreditLineIDOld 			= $customers[$idexCount]->customerCreditLineID;
						$objCustomerMaps = (object)[
								'entityIDOld' 				=> $entityIDOld,
								'customerCreditLineIDOld' 	=> $customerCreditLineIDOld,
								'entityID' 					=> $objCustomer->entityID,
								'customerCreditLineID' 		=> $objCustomerCreditLine[0]->customerCreditLineID 
						];
						$objListCustomerMap[] 							= $objCustomerMaps;
                    }
					
					$idexCount++;
				
                }
            }
			
			
			
			log_message("error",print_r("0006",true));
            // SINCRONIZACION DE COMPRAS
            if (count($items) > 0) {
                $inventoryController  =new app_inventory_inputunpost();
                $inventoryController->initController($this->request, $this->response, $this->logger);
                $inventoryController->insertElementMobile($dataSession, $items);
            }
            log_message("error",print_r("0007",true));
            //las entradas - salidas < 0
            // SINCRONIZACION DE SALIDAS
            if(count($items)>0){
                $inventoryOutController = new app_inventory_otheroutput();
                $inventoryOutController->initController($this->request, $this->response, $this->logger);
                $inventoryOutController->insertElementMobile($dataSession, $items);     
            }
			log_message("error",print_r("0008",true));
            //SINCRONIZACION FACTURAS
			$idexCount = 0;
            if(count($transactionMasters)>0)
			{
                $billingController 	= new app_invoice_billing();
                $billingController->initController($this->request, $this->response, $this->logger);
                $typeTransaction 	= $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_billing",0);
                $facturas 			= array_filter($transactionMasters, function($tm) use ($typeTransaction) { return $tm->TransactionId == $typeTransaction; });
				log_message("error",print_r("0008.001",true));
                foreach($facturas as $objTm)
				{
					
                    // Filtrar los objetos por TransactionMasterId
                    $transactionMasterId=$objTm->TransactionMasterId;
					$entityID 			=$objTm->EntityId;
					log_message("error",print_r("0008.002",true));
					
					//buscar el entityID si es un entityID Nuevo					
					$objCustomerFilt 	= array_filter($objListCustomerMap, function($e) use ($entityID) { return $e->entityIDOld == $entityID; });
					
					
					log_message("error",print_r("0008.003",true));
					if($objCustomerFilt)
					{
						
						log_message("error",print_r("0008.004.0001",true));
						log_message("error",print_r($objTm,true));
						log_message("error",print_r($objCustomerFilt,true));
						log_message("error",print_r($entityID,true));
						
						$objCustomerFilt 				= is_array($objCustomerFilt) ? reset($objCustomerFilt) : $$objCustomerFilt; 
						$objTm->entityID 				= $objCustomerFilt->entityID;
						$objTm->customerCreditLineID 	= $objCustomerFilt->customerCreditLineID;
						log_message("error",print_r("0008.004.0002",true));
					}
						
					//buscar el detalle
                    $resultado = array_filter($transactionMasterDetails, function($tm) use ($transactionMasterId) { return $tm->TransactionMasterId == $transactionMasterId; });
                    $billingController->insertElementMobil($dataSession,$objTm, $resultado);
					log_message("error",print_r("0008.005",true));
					$idexCount++;
                }
            }
			log_message("error",print_r("0009",true));
            //SINCRONIZACION ABONOS
            if(count($transactionMasters)>0){
                $shareController = new app_box_share();
                $shareController->initController($this->request, $this->response, $this->logger);
                $typeTransaction = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_share",0);
                $abonos = array_filter($transactionMasters, function($tm) use ($typeTransaction) {
                    return $tm->TransactionId == $typeTransaction;
                });
                foreach($abonos as $objTm){
                    $shareController->insertElementMobil($dataSession,$objTm);
                }
            }
			log_message("error",print_r("0010",true));

			//SINCRONIZAR VISITAS O CONSULTAS MEDICAS
			if(count($transactionMasters)>0){
                $medQueryController = new app_med_query();
                $medQueryController->initController($this->request, $this->response, $this->logger);
                $typeTransaction = $this->core_web_transaction->getTransactionID($companyID,"tb_transaction_master_med_asistencia",0);
                $medQuery = array_filter($transactionMasters, function($tm) use ($typeTransaction) {
                    return $tm->TransactionId == $typeTransaction;
                });
                foreach($medQuery as $objTm){
                    $medQueryController->insertElementMobil($dataSession,$objTm);
                }
            }
			
			log_message("error",print_r("0011",true));
            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS
            ));//--finjson

        } catch (\Exception $ex) {
			
			log_message("error",print_r($ex,true));
            return $this->response->setJSON(array(
                'error' => true,
                'message' => 'Linea: ' . $ex->getLine() . " - Error:" . $ex->getMessage()
            ));//--finjson

        }

    }

    function getDataDownload()
    {
        try {

            $nickname 	= /*inicio get post*/ $this->request->getPostGet("txtNickname");
            $password 	= /*inicio get post*/ $this->request->getPostGet("txtPassword");
            $objUser 	= $this->core_web_authentication->get_UserBy_PasswordAndNickname($nickname, $password);
            $companyID 	= $objUser["user"]->companyID;
            $userID 	= $objUser["user"]->userID;
            $objCompany = $objUser["company"];

            //Obtener listado de productos
            $objWarehouse 	= $this->Userwarehouse_Model->getRowByUserIDAndFacturable($companyID, $userID);			
            $objWarehouseID = array_map(fn($warehouseItem) => $warehouseItem->warehouseID, $objWarehouse);
            $objListItem 	= $this->Item_Model->get_rowByCompanyIDToMobile($objWarehouseID);

            //Obtener lista de clients
            $objListCustomer = $this->Customer_Model->get_rowByCompanyIDToMobile($companyID, $userID );

            //Obtener lisa de paramtros
            $objListParameter = $this->Company_Parameter_Model->get_rowByCompanyID($companyID);

            //Obtener documentos pendientes
            $objListDocumentCredit 	= $this->Customer_Credit_Document_Model->get_rowByBalancePendingByCompanyToMobile($companyID, $userID );

            //Obtener lista de amortizaciones
            $objListAmortization 	= $this->Customer_Credit_Amortization_Model->get_rowShareLateByCompanyToMobile($companyID, $userID );


            return $this->response->setJSON(array(
                'error' => false,
                'message' => SUCCESS,
                'ObjCompany' => $objCompany,
                'ListItem' => $objListItem,
                'ListCustomer' => $objListCustomer,
                'ListParameter' => $objListParameter,
                'ListDocumentCredit' => $objListDocumentCredit,
                'ListDocumentCreditAmortization' => $objListAmortization
            ));//--finjson

        } catch (\Exception $ex) {

            return $this->response->setJSON(array(
                'error' => true,
                'message' => $ex->getLine() . " " . $ex->getMessage()
            ));//--finjson

        }

    }
	
	function getUserByCompany()
	{
		try{ 
			
			//AUTENTICACION
			if(!$this->core_web_authentication->isAuthenticated())
			throw new \Exception(USER_NOT_AUTENTICATED);
			$dataSession		= $this->session->get();
			
			
			//Obtener Parametros
			$companyID 				= $dataSession["user"]->companyID;
			$companyName 			= /*inicio get post*/ $this->request->getPost("companyName");	
			if( !$companyID )
			{
					throw new \Exception(NOT_PARAMETER);	
			} 
			
			
			//Lista de usuarios
			if($companyName != "0")
			$catalogItems = $this->Entity_Location_Model->get_UserByCompanyLast($companyName);
		
			if($companyName == "0")
			$catalogItems = $this->Entity_Location_Model->get_UserAll();
			
			
			return $this->response->setJSON(array(
				'error'   		=> false,
				'message' 		=> SUCCESS,
				'catalogItems'  => $catalogItems
			));//--finjson			
			
		}
		catch(\Exception $ex){
			
			return $this->response->setJSON(array(
				'error'   => true,
				'message' => $ex->getLine()." ".$ex->getMessage()
			));//--finjson			
		}
	}
	
	
}

?>