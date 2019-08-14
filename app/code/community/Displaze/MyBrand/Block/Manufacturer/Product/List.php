<?php

/* 
 * List.php
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

class Displaze_MyBrand_Block_Manufacturer_Product_List extends Mage_Catalog_Block_Product_List
{
    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
       $productCollection = $this->getManufacturer()->getProductCollection();
       return $productCollection;
    }

    /**
     * Get array with product ids, which was added to manufacturer
     *
     * @return array
     */
    protected function _getManufacturerProductIds()
    {
        $manufacturer = $this->getManufacturer();        
        $productIds = array(0);
        $productIds = $manufacturer->getProductIds();
        
        return $productIds;
    }
    
    public function getManufacturer()
    {
        $manufacturer = Mage::registry('current_manufacturer');
        if($manufacturer == null) {
            $id = Mage::app()->getRequest()->getParam('id');
            $manufacturer = Mage::getModel('mybrand/manufacturer')->load($id);
        }
        
        return $manufacturer;
    }
}