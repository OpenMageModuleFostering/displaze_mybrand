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

class Displaze_MyBrand_Model_Resource_Manufacturer_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('mybrand/manufacturer_product', 'id');
    }
    
    /**
     * Retrieve related products
     *
     * @return array
     */
    public function addManufacturerIdFilter($manufacturerId) 
    {
        $manufacturerTable = $this->getTable('mybrand/manufacturer');
        
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from(array('main_table' => $this->getMainTable()), 'manufacturer_id')
            ->where('main_table.manufacturer_id = ?',(int)$manufacturerId);
        
        $select->join(
            array('related' => $manufacturerTable ),
                'main_table.manufacturer_id = related.id'
        )->order('related.id');
        
        echo $select;exit;
        return $this;
    }
}