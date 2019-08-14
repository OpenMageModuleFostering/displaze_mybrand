<?php
/**
 * Renegade Group
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Renegade
 * @copyright  Copyright (c) 2008-2011 Renegade Group (http://www.renegadefurniture.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Displaze_MyBrand_Block_Adminhtml_Manufacturer_Edit_Tab_Product_List extends Mage_Adminhtml_Block_Widget
{

    public function __construct() {
        parent::__construct();
        $this->setSkipGenerateContent(true);
       // $this->setTemplate('displaze/mybrand/manufacturer/edit/tabs/product/list.phtml');
    }

    
    public function getFamilyProducts() {
       /* $myproductfamilyModel=Mage::registry("myproductfamily_data");
        $familyId = $myproductfamilyModel->getId();

        if( isset($familyId) && $familyId > 0 )
        {
            $collection = Mage::getModel('catalog/product')->getCollection();
            $collection->addAttributeToSelect(array('name', 'url_key', 'type_id'));
            $collection->getSelect()->join(
                  array('fp'=>'my_product_family_content'),
                  'e.entity_id = fp.product_entity_id')
                  ->where('fp.myproductfamily_id=' . $familyId);

            $collection->load();
            return  $collection;
        }
        else
        {
            return false;
        }*/
    }

    public function getProductDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_product_button');
    }
    public function getProductAddButtonHtml()
    {
        return $this->getChildHtml('add_product_button');
    }

    public function getProductGridBoxUrl()
    {
        return $this->getUrl('*/*/gridbox', array('_current'=>true));
    }
     public function getProductGridContainerUrl()
    {
        return $this->getUrl('*/*/gridcontainer', array('_current'=>true));
    }


}