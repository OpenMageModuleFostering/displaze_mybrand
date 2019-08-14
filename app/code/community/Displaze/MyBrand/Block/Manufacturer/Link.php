<?php

/* 
 * Link.php
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

/**
 * For magento 1.6 this block needs to be called explicitly, for magento 1.7 and later 
 * no need to call this  
 */
class Displaze_MyBrand_Block_Manufacturer_Link extends Mage_Core_Block_Template
{
    /**
     * set the default template for this block, 
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('displaze/mybrand/manufacturer/link.phtml');
    }
    
    /**
     * Return all active/enabled manufacturers
     * @return type 
     */
    public function getManufacturerCollection()
    {
        return $manufacturerCollection = Mage::getModel('mybrand/manufacturer')
                ->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
    }
}