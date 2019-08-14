<?php

/* 
 * Navigation.php
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

class Displaze_MyBrand_Block_Page_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{
    
    public function _construct()
    { 
        parent::_construct();
        $this->setTemplate('displaze/mybrand/page/html/topmenu.phtml');
    }
    
    
    public function getManufacturerCollection()
    {
        
        return $manufacturerCollection = Mage::getModel('mybrand/manufacturer')
                ->getCollection()
                ->addFieldToFilter('status', array('eq' => 1));
    }
    
    /*
     * This method has been overridden merely for the purpose of setting up a new view file
     * to be used in place of the default theme folder.
     *
     * @reference http://inchoo.net/ecommerce/magento/how-to-override-magento-admin-view-template-files-quick-and-dirty-way/
     *
     * @see app/code/core/Mage/Core/Block/Mage_Core_Block_Template#fetchView($fileName)
     */
    public function fetchView($fileName)
    {
        extract($this->_viewVars);
        $do = $this->getDirectOutput();
        $html = '';
        if (!$do) {
            ob_start();
        }
        include getcwd() . '/app/design/frontend/default/default/template/displaze/mybrand/page/html/topmenu.phtml';

        if (!$do) {
            $html = ob_get_clean();
        } 
        return $html;
    }
}