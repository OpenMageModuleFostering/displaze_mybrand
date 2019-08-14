<?php

/* 
 * Manufacturer.php
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
 * Container for Grid.
 * 
 * @package Displaze_MyBrand
 * @subpackage Manufacturer
 */
class Displaze_MyBrand_Block_Adminhtml_Manufacturer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->setId('mybrand_manufacturer_gridbox');
        $this->_controller = 'adminhtml_manufacturer';
        $this->_blockGroup = 'mybrand';
        $this->_headerText = 'Manufacturer Management';
        $this->_addButtonLabel = 'Add Manufacturer';
        parent::__construct();
    }
}