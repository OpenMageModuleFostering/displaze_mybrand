
<?php

/*
 * Product.php
 * 
 * Copyright (c) 2012 Aftab Naveed <aftabnaveed@gmail.com>. 
 * 
 * This file is part of Displaze Web Services Inc..
 * 
 * Displaze Web Services Inc. is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Displaze Web Services Inc. is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Displaze Web Services Inc..  If not, see <http ://www.gnu.org/licenses/>.
 */

class Displaze_MyBrand_Model_Observer_Product 
{

    /**
     * Inject one tab into the product edit page in the Magento admin
     *
     * @param Varien_Event_Observer $observer
     */
    public function injectTabs(Varien_Event_Observer $observer) 
    {
        $block = $observer->getEvent()->getBlock();
        
        $request = Mage::app()->getRequest();
        
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs) {
            if ($request->getActionName() == 'edit' || $request->getParam('type')) {
                $block->addTab('mybrand-manufacturer-tab', array(
                    'label' => 'Manufacturer',
                    'content' => $block->getLayout()->createBlock('mybrand/adminhtml_manufacturer_tab', 'mybrand-manufacturer-tab-content', array('template' => 'displaze/mybrand/manufacturer/tab.phtml'))->toHtml(),
                ));
            }
        }
    }
    
    /**
     * This method will run when the product is saved
     * Use this function to update the product model and save
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveTabData(Varien_Event_Observer $observer) {
        
        $request = Mage::app()->getRequest();
        if ($request->getParam('manufacturer_id') > 0) {
            try {
                // Load the current product model	
                $product = Mage::registry('product');
                
                // 2 load manufacturer product model and delete it
                $manufacturerProduct = Mage::getModel('mybrand/manufacturer_product');
                $manufacturerProduct->load($product->getId(), 'product_id');
                $manufacturerProduct->delete();
                
                // 3 save the product against the manufacturer
                $manufacturerProduct->setData(
                        array(
                            'product_id'        => $product->getId(),
                            'manufacturer_id'   => $request->getParam('manufacturer_id')
                        )
                );
                
                $manufacturerProduct->save();
                
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }

}