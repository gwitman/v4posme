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
use App\Models\Public_Catalog_Model;
use App\Models\Public_Catalog_Detail_Model;



class core_web_catalog {
   
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
   function getCatalogAllItem($table,$field,$companyID){
		$Company_Model = new Company_Model();
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();
		$Catalog_Model = new Catalog_Model();  
		$Catalog_Item_Model = new Catalog_Item_Model();  
		$Public_Catalog_Model = new Public_Catalog_Model();
		$Public_Catalog_Detail_Model = new Public_Catalog_Detail_Model();
		
		//Obtener compania
		$objCompanyModel = $Company_Model->get_rowByPK($companyID);
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente catalogo
		$objComponent = $Component_Model->get_rowByName("tb_catalog");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_catalog' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el catalogo
		if(!$objSubElement->catalogID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL CATALOGO CONFIGURADO");
		
		$objCatalog = $Catalog_Model->get_rowByCatalogID($objSubElement->catalogID);
		if(!$objCatalog)
		throw new \Exception("NO EXISTE EL CATALOGO ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objCatalog->catalogID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//obtener la lista de catalogItem	
		$objCatalogItem = $Catalog_Item_Model->get_rowByCatalogIDAndFlavorID($objCatalog->catalogID,$objCompanyModel->flavorID);
		if(!$objCatalogItem)
		$objCatalogItem = $Catalog_Item_Model->get_rowByCatalogIDAndFlavorID($objCatalog->catalogID,$objCompanyComponentFlavor->flavorID);
	
	
		//Obtener si el catalog, usa un catalogo personalizable
		if ( is_null( $objCatalog->publicCatalogSystemName ) ) 
			return $objCatalogItem;
		
		if ( empty( $objCatalog->publicCatalogSystemName ) ) 
			return $objCatalogItem;
		
		
		$objPublicCatalog 		= $Public_Catalog_Model->getBySystemNameAndFlavorID($objCatalog->publicCatalogSystemName,$objCompanyModel->flavorID);
		if(!$objPublicCatalog)
			return $objCatalogItem;		
			
		$objPublicCatalogItem 	= $Public_Catalog_Detail_Model->getView($objPublicCatalog->publicCatalogID);
		if(!$objPublicCatalogItem)
			return $objCatalogItem;		
			
		
		//Usar public catalog item
		foreach($objPublicCatalogItem as $objItem)
		{
			array_push(
				$objCatalogItem,
				(object)[
					"catalogItemID" 	=> $objItem->publicCatalogDetailID,
					"name" 				=> $objItem->name,
					"display" 			=> $objItem->display,
					"description" 		=> $objItem->description,
					"sequence" 			=> $objItem->sequence
				]
			);
		}

		return $objCatalogItem;
		
   }
   function getCatalogAllItemByNameCatalogo($name,$companyID){
	    
		$Company_Model = new Company_Model();
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();
		$Catalog_Model = new Catalog_Model();  
		$Catalog_Item_Model = new Catalog_Item_Model();  
		
		//Obtener compania
		$objCompanyModel = $Company_Model->get_rowByPK($companyID);
		
		
		
		//obtener componente catalogo
		$objComponent = $Component_Model->get_rowByName("tb_catalog");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_catalog' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		$objCatalog = $Catalog_Model->get_rowByName($name);
		if(!$objCatalog)
		throw new \Exception("NO EXISTE EL CATALOGO ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objCatalog->catalogID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//obtener la lista de catalogItem		
		 
		
		$objCatalogItem = $Catalog_Item_Model->get_rowByCatalogIDAndFlavorID($objCatalog->catalogID,$objCompanyModel->flavorID);
		if(!$objCatalogItem)
		$objCatalogItem = $Catalog_Item_Model->get_rowByCatalogIDAndFlavorID($objCatalog->catalogID,$objCompanyComponentFlavor->flavorID);
	
		
		
		//Obtener si el catalog, usa un catalogo personalizable
		if ( is_null( $objCatalog->publicCatalogSystemName ) ) 
			return $objCatalogItem;
		
		if ( empty( $objCatalog->publicCatalogSystemName ) ) 
			return $objCatalogItem;
		
		
		$objPublicCatalog 		= $Public_Catalog_Model->getBySystemNameAndFlavorID($objCatalog->publicCatalogSystemName,$objCompanyModel->flavorID);
		if(!$objPublicCatalog)
			return $objCatalogItem;		
			
		$objPublicCatalogItem 	= $Public_Catalog_Detail_Model->getView($objPublicCatalog->publicCatalogID);
		if(!$objPublicCatalogItem)
			return $objCatalogItem;		
			
		
		//Usar public catalog item
		foreach($objPublicCatalogItem as $objItem)
		{
			array_push(
				$objCatalogItem,
				(object)[
					"catalogItemID" 	=> $objItem->publicCatalogDetailID,
					"name" 				=> $objItem->name,
					"display" 			=> $objItem->display,
					"description" 		=> $objItem->description,
					"sequence" 			=> $objItem->sequence
				]
			);
		}	
		
		
		return $objCatalogItem;
		
   }
   
   function getCatalogAllItem_Parent($table,$field,$companyID,$parentCatalogItemID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();
		$Catalog_Model = new Catalog_Model();  
		$Catalog_Item_Model = new Catalog_Item_Model();  
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente catalogo
		$objComponent = $Component_Model->get_rowByName("tb_catalog");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_catalog' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el catalogo
		if(!$objSubElement->catalogID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL CATALOGO CONFIGURADO");
		
		$objCatalog = $Catalog_Model->get_rowByCatalogID($objSubElement->catalogID);
		if(!$objCatalog)
		throw new \Exception("NO EXISTE EL CATALOGO ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objCatalog->catalogID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//obtener la lista de catalogItem
		$objCatalogItem = $Catalog_Item_Model->get_rowByCatalogIDAndFlavorID_Parent($objCatalog->catalogID,$objCompanyComponentFlavor->flavorID,$parentCatalogItemID);
		
		
		
		return $objCatalogItem;
		
   }
   function getCatalogItem($table,$field,$companyID,$catalogItemID){
		$Component_Model = new Component_Model();
		$Element_Model = new Element_Model();
		$Sub_Element_Model = new Sub_Element_Model();
		$Company_Component_Flavor_Model = new Company_Component_Flavor_Model();
		$Catalog_Model = new Catalog_Model();  
		$Catalog_Item_Model = new Catalog_Item_Model();  
		
		//obtener elemento 
		$objElement 	= $Element_Model->get_rowByName($table,ELEMENT_TYPE_TABLE);
		if(!$objElement)
		throw new \Exception("NO EXISTE LA TABLA '".$table."' DENTRO DE LOS REGISTROS DE ELEMENT ");
		
		//obtener subelement
		$objSubElement 	= $Sub_Element_Model->get_rowByNameAndElementID($objElement->elementID,$field); 
		if(!$objSubElement)
		throw new \Exception("NO EXISTE EL CAMPO '".$field."' DENTRO DE LOS REGISTROS DE SUBELEMENT PARA EL ELEMENTO '".$table."' ");
		
		//obtener componente catalogo
		$objComponent = $Component_Model->get_rowByName("tb_catalog");
		if(!$objComponent)
		throw new \Exception("NO EXISTE EL COMPONENTE 'tb_catalog' DENTROS DE LOS REGISTROS DE 'Component' ");
		
		
		//obtener el catalogo
		if(!$objSubElement->catalogID)
		throw new \Exception("EN LA TABLA SUBELEMENT PARA '".$field."' NO EXISTE EL CATALOGO CONFIGURADO");
		
		$objCatalog = $Catalog_Model->get_rowByCatalogID($objSubElement->catalogID);
		if(!$objCatalog)
		throw new \Exception("NO EXISTE EL CATALOGO ");
				
		//obtener flavor
		$objCompanyComponentFlavor = $Company_Component_Flavor_Model->get_rowByCompanyAndComponentAndComponentItemID($companyID,$objComponent->componentID,$objCatalog->catalogID);
		if(!$objCompanyComponentFlavor)
		throw new \Exception("NO EXISTE EL FLAVOR PARA EL COMPONENTE DE CATALOGO ");
		
		//obtener la lista de catalogItem
		$objCatalogItem = $Catalog_Item_Model->get_rowByCatalogItemID($catalogItemID);
		
		
		//Obtener si el catalog, usa un catalogo personalizable
		if ( is_null( $objCatalog->publicCatalogSystemName ) ) 
			return $objCatalogItem;
		
		if ( empty( $objCatalog->publicCatalogSystemName ) ) 
			return $objCatalogItem;
			
		
		
		
		$objPublicCatalog 		= $Public_Catalog_Model->getBySystemNameAndFlavorID($objCatalog->publicCatalogSystemName,$objCompanyModel->flavorID);
		if(!$objPublicCatalog)
			return $objCatalogItem;		
			
		$objPublicCatalogItem 	= $Public_Catalog_Detail_Model->getView($objPublicCatalog->publicCatalogID);
		if(!$objPublicCatalogItem)
			return $objCatalogItem;		
		
		
			
		//Filtrar los registros		
		$objPublicCatalogItem 	= array_filter($objPublicCatalogItem, function($i) use ($catalogItemID) {
				return $i->publicCatalogDetailID == $catalogItemID;
		});
		if(!$objPublicCatalogItem)
			return $objCatalogItem;
		
		
		//Usar public catalog item
		foreach($objPublicCatalogItem as $objItem)
		{
			array_push(
				$objCatalogItem,
				(object)[
					"catalogItemID" 	=> $objItem->publicCatalogDetailID,
					"name" 				=> $objItem->name,
					"display" 			=> $objItem->display,
					"description" 		=> $objItem->description,
					"sequence" 			=> $objItem->sequence
				]
			);
		}	
		
		
		return $objCatalogItem;
		
   }
}
?>