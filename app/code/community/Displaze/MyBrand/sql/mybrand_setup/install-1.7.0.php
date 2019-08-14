<?php

/*
 * install-1.7.0.php
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

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

/**
 * Create table 'myslideshow/slideshow'
 */
$table = $installer->getConnection()
        ->newTable($installer->getTable('mybrand/manufacturer'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
                ), 'Manufacturer ID')
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => false,
                ), 'Manufacturer Title')
        ->addColumn('identifier', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable' => false,
                ), 'Manufacturer Url Key')
        ->addColumn('logo', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
                ), 'Manufacturer Logo')
        ->addColumn('cover_image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
                ), 'Manufacturer Cover Image')
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'nullable' => false,
            'default' => '1',
                ), 'Manufacturer Status')
        ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
            'nullable' => true,
                ), 'Manufacturer Content')
        ->addColumn('meta_title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
            'nullable' => true,
                ), 'Manufacturer Meta Title')
        ->addColumn('meta_keyword', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
            'nullable' => true,
                ), 'Manufacturer Keywords')
        ->addColumn('meta_description', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
            'nullable' => true,
                ), 'Manufacturer Keywords')
        ->addColumn('creation_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Manufacturer Creation Time')
        ->addColumn('update_time', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
                ), 'Manufacturer Modification Time')

        /* ->addColumn('activation_time_from', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
          ), 'Manufactuer Activation Time From')

          ->addColumn('activation_time_to', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
          ), 'Manufactuer Activation Time To') */
        ->setComment('Displaze MyBrand Manufacturer Table');

$installer->getConnection()->createTable($table);

/**
 * Create table 'mybrand/manufacturer_store'
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('mybrand/manufacturer_store'))
    ->addColumn('manufacturer_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Manufacturer ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($installer->getIdxName('mybrand/manufacturer_store', array('store_id')),
        array('store_id'))
    ->addForeignKey($installer->getFkName('mybrand/manufacturer_store', 'manufacturer_id', 'mybrand/manufacturer', 'id'),
        'manufacturer_id', $installer->getTable('mybrand/manufacturer'), 'id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($installer->getFkName('mybrand/manufacturer_store', 'store_id', 'core/store', 'store_id'),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('MyBrand Manufacturer To Store Linkage Table');
$installer->getConnection()->createTable($table);

/**
 * Create table mybrand/manufacturer_product  
 */

$table = $installer->getConnection()
        ->newTable($installer->getTable('mybrand/manufacturer_product'))
        ->addColumn('manufacturer_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'nullable'  => false
            
        ), 'Manufacturer ID')
        
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => true,
            'unasigned' => true
        ), 'Product ID')
        
        ->addColumn('product_sku', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
        ), 'Manufacturer Product Sku')
        
        ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ), 'Position')
        
        ->addIndex($installer->getIdxName('mybrand/manufacturer_product', array('manufacturer_id')), array('manufacturer_id'))
        ->addIndex($installer->getIdxName('mybrand/manufacturer_product', array('product_id')), array('product_id'))
        
        ->addForeignKey($installer->getFkName('mybrand/manufacturer_product', 'product_id', 'catalog/product', 'entity_id'),
                        'product_id', $installer->getTable('catalog/product'), 'entity_id',
                        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        
        ->addForeignKey($installer->getFkName('mybrand/manufacturer_product', 'manufacturer_id', 'mybrand/manufacturer', 'id'),
                        'manufacturer_id', $installer->getTable('mybrand/manufacturer'), 'id',
                        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)

        ->setComment('Manufacturer Product');

$installer->getConnection()->createTable($table);

$installer->endSetup();


