<?php

/* 
 * ManufacturerController.php
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

class Displaze_MyBrand_ManufacturerController extends Mage_Core_Controller_Front_Action
{
    /**
     * list products for current manufacturer 
     */
    public function viewAction()
    { 
        $this->loadLayout();
        try {
            $id = $this->getRequest()->getParam('id', $this->getRequest()->getParam('id', false));
            
            if($manufacturer = Mage::helper('mybrand/manufacturer')->renderLink($this, $id)) {
                //register current manufacturer
                Mage::register('current_manufacturer', $manufacturer);
                
            }
        } catch(Exception $e) {
            
        }
        
        $this->renderLayout();
    }
}