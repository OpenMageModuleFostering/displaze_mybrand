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

class Displaze_MyBrand_Model_Resource_Manufacturer extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Store model
     *
     * @var null|Mage_Core_Model_Store
     */
    protected $_store  = null;

    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('mybrand/manufacturer', 'id');
    }
    
     /**
     * Process page data before saving
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Displaze_MySlideshow_Model_Resource_Slideshow
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        /*
         * For two attributes which represent timestamp data in DB
         * we should make converting such as:
         * If they are empty we need to convert them into DB
         * type NULL so in DB they will be empty and not some default value
         */
        foreach (array('activation_time_from', 'activation_time_to') as $field) {
            $value = !$object->getData($field) ? null : $object->getData($field);
            $object->setData($field, $this->formatDate($value));
        }

        if (!$this->getIsUniqueManufacturerToStores($object)) {
            Mage::throwException(Mage::helper('mybrand')->__('A manufacturer URL key for specified store already exists.'));
        }

        if (!$this->isValidManufacturerIdentifier($object)) {
            Mage::throwException(Mage::helper('mybrand')->__('The manufacturer URL key contains capital letters or disallowed symbols.'));
        }

        if ($this->isNumericManufacturerIdentifier($object)) {
            Mage::throwException(Mage::helper('mybrand')->__('The manufacturer URL key cannot consist only of numbers.'));
        }
        
        // modify create / update dates
        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime(Mage::getSingleton('core/date')->gmtDate());
        }

        
        $object->setUpdateTime(Mage::getSingleton('core/date')->gmtDate());

        return parent::_beforeSave($object);
    }
    
    
    /**
     * Assign page to store views
     *
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('mybrand/manufacturer_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'manufacturer_id = ?'     => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'manufacturer_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        $this->_saveProducts($object); //save manufacturer products if any
        return parent::_afterSave($object);
    }
    
    /**
     * save products associated with this manufacturer
     * @return Renegade_MyBrand_Model_Resource_Manufacturer $link
     */
    protected function _saveProducts(Mage_Core_Model_Abstract $object)
    {
        $condition = $this->_getWriteAdapter()->quoteInto('manufacturer_id = ?', $object->getId());
        $this->_getWriteAdapter()->delete($this->getTable('mybrand/manufacturer_product'), $condition);

        foreach((array)$object->getData('product') as $productArray) {
            //product_id and product_sku is already included in $productArray so we don't need to explicitly assign those, like below manufacturer_id
            $productArray['manufacturer_id'] = $object->getId();
            $this->_getWriteAdapter()->insert($this->getTable('mybrand/manufacturer_product'), $productArray);
        }

        return $this;
    }

    
    /* _afterLoad is called when the collection is loaded.
     * Appends the data with ManufacturerCollection for Store
     * @param Mage_Core_Model_Abstract $object
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('mybrand/manufacturer_store'))
            ->where('manufacturer_id = ?', $object->getId());

        if ($data = $this->_getReadAdapter()->fetchAll($select)) {
            $storesArray = array();
            foreach ($data as $row) {
                $storesArray[] = $row['store_id'];
            }
            $object->setData('store_id', $storesArray);
        }

        $this->_getProductIds($object); //append product ids with current link collection

        return parent::_afterLoad($object);
    }
    
    /**
     * Retrieves brand manufacturer identifier from DB by passed id.
     *
     * @param string $id
     * @return string|false
     */
    public function getManufacturerIdentifierById($id)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getMainTable(), 'identifier')
            ->where('id = :id');

        $binds = array(
            'id' => (int) $id
        );

        return $adapter->fetchOne($select, $binds);
    }
    
    
    /**
     * get Manufacturer product collection for current manufacturer 
     */
    public function getProductCollection($manufacturerId)
    {
        $id = $manufacturerId;
        
        $table = $this->getTable('mybrand/manufacturer_product'); //manufacturer products
        
        $adapter = $this->_getReadAdapter();
        
        $select  = $adapter->select()
            ->from(array('main_table' => $this->getMainTable()), 'id')
            ->where('main_table.id = :id');
        
        $select->join(
            array('related' => $table),
            'main_table.id = related.manufacturer_id'    
        );
        
        $binds = array(
            'id' => (int) $id
        );
        
        $productIds = array();
        foreach($adapter->fetchAll($select, $binds) as $product) {
            $productIds[] = $product['product_id'];
        }
        
        $productCollection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', array('in' => $productIds));
        
       
        return $productCollection;
        
    }
    
    protected function _getProductIds(Mage_Core_Model_Abstract $object)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getTable('mybrand/manufacturer_product'))
            ->where('manufacturer_id = ?', $object->getId());

        if($data = $this->_getReadAdapter()->fetchAll($select)) {
            $productArray = array();
            foreach($data as $row) {
                $productArray[] = $row['product_id'];
            }
            $object->setData('product_ids', $productArray);
        }
    }
    
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($manufacturerId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('mybrand/manufacturer_store'), 'store_id')
            ->where('manufacturer_id = ?',(int)$manufacturerId);

        return $adapter->fetchCol($select);
    }
    
    
    /**
     * Check for unique of identifier of manufacturer to selected store(s).
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    public function getIsUniqueManufacturerToStores(Mage_Core_Model_Abstract $object)
    {
        if (Mage::app()->isSingleStoreMode() || !$object->hasStores()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = (array)$object->getData('stores');
        }

        $select = $this->_getLoadByIdentifierSelect($object->getData('identifier'), $stores);

        if ($object->getId()) {
            $select->where('bms.manufacturer_id <> ?', $object->getId());
        }

        if ($this->_getWriteAdapter()->fetchRow($select)) {
            return false;
        }

        return true;
    }
    
     /**
     * Retrieve load select with filter by identifier, store and activity
     *
     * @param string $identifier
     * @param int|array $store
     * @param int $isActive
     * @return Varien_Db_Select
     */
    protected function _getLoadByIdentifierSelect($identifier, $store, $isActive = null)
    {
        $select = $this->_getReadAdapter()->select()
            ->from(array('bm' => $this->getMainTable()))
            ->join(
                array('bms' => $this->getTable('mybrand/manufacturer_store')),
                'bm.id = bms.manufacturer_id',
                array())
            ->where('bm.identifier = ?', $identifier)
            ->where('bms.store_id IN (?)', $store);

        if (!is_null($isActive)) {
            $select->where('bm.status = ?', $isActive);
        }

        return $select;
    }
    
    /**
     *  Check whether manufacturer identifier is numeric
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    protected function isNumericManufacturerIdentifier(Mage_Core_Model_Abstract $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     *  Check whether manufacturer identifier is valid
     *
     *  @param    Mage_Core_Model_Abstract $object
     *  @return   bool
     */
    protected function isValidManufacturerIdentifier(Mage_Core_Model_Abstract $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }
    
    /**
     * Check if manufacturer identifier exist for specific store
     * return manufacturer id if page exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID, $storeId);
        $select = $this->_getLoadByIdentifierSelect($identifier, $stores, 1);
        $select->reset(Zend_Db_Select::COLUMNS)
            ->columns('bm.id')
            ->order('bms.store_id DESC')
            ->limit(1);

        return $this->_getReadAdapter()->fetchOne($select);
    }
}
