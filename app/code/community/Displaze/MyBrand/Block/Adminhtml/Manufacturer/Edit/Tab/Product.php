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


class Displaze_MyBrand_Block_Adminhtml_Manufacturer_Edit_Tab_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_manufacturer_edit_tab_product';
        $this->_blockGroup = 'mybrand';
        parent::__construct();
    }

    /**
     * Get HTML code for button View Available Products
     *
     * @return string
     */
    public function getAddButtonHtml()
    {
        $addButtonData = array(
            'id'    => 'product_add_button',
            'label' => $this->__('Add Product(s)'),
            //'on_click'   => 'product.showGridBox(event)',
            'class' => 'add',
        );
        return $this->getLayout()
            ->createBlock('adminhtml/widget_button')
            ->setData($addButtonData)
            ->toHtml();
    }

    public function getAddSelectedProductButtonHtml()
    {
        $addButtonData = array(
            'id'    => 'add_button_product',
            'label' => $this->__('Add Selected Products(s) to Manufacturer'),
            'onclick' => 'brandProduct.productGridAddSelected(event)',
            'class' => 'add',
        );
        return $this->getLayout()
                ->createBlock('adminhtml/widget_button')
                ->setData($addButtonData)
                ->toHtml();
    }

    public function getDeleteButtonHtml()
    {
        $deleteButtonData = array(
            'id'    => 'delete_product_button',
            'label' => $this->__('Delete Product'),
            'onclick' => 'brandProduct.remove(this)',
            'class' => 'delete icon-btn',
        );
        return $this->getLayout()
                ->createBlock('adminhtml/widget_button')
                ->setData($deleteButtonData)
                ->toHtml();
    }

    
    public function getHeaderText()
    {
        return $this->__('Please Select Products to Add to Manufacturer');
    }

    public function getProductGridContainerUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    public function getManufacturerProductCollection()
    {
        return $productCollection = Mage::getModel('catalog/product')->getCollection()
                   ->addAttributeToSelect('*')
                    ->addAttributeToFilter('entity_id', array('IN'=> $this->_getManufacturerProductIds()));
        
    }

    protected function _getManufacturerProductIds()
    {
        $manufacturer = Mage::registry('current_manufacturer');
        if($manufacturer == null) {
            $id = Mage::app()->getRequest()->getParam('id');
            $manufacturer = Mage::getModel('mybrand/manufacturer')->load($id);
        }
          
        $productIds = array(0);
        $productIds = $manufacturer->getProductIds();
        
        return $productIds;
    }

    /**
    * Prepare label for tab
    *
    * @return string
    */
    public function getTabLabel()
    {
        return Mage::helper('mybrand')->__('Products');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('mybrand')->__('Products');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}