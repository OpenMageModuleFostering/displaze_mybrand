<?php
/**
 * Renegade Group
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Renegade
 * @copyright  Copyright (c) 2008-2011 Renegade Group (http://www.renegadefurniture.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/**
 * Products Grid to add to Google Content
 *
 * @category    Mage
 * @package     Mage_GoogleShopping
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Displaze_MyBrand_Block_Adminhtml_Manufacturer_Edit_Tab_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{

    protected $_showRss = false;
    protected $_showActionColumn = false;
    protected $_showCategories = false;

    protected $_showExportType = false;

    /**
     * Massaction block name
     *
     * @var string
     */
  //  protected $_massactionBlockName = 'mybrand/adminhtml_widget_grid_massaction';


    public function __construct()
    {
        parent::__construct();
        $this->setId('mybrandproduct_selection_search_grid');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
        
    
    }

    protected function _prepareCollection() 
    {
        /*$collection = parent::_prepareCollection();
        $collection->addAttributeToFilter('entity_id', array('nin' => $this->_getManufacturerProductIds()));*/
        
        parent::_prepareCollection();
    }
    
    protected function _prepareColumns() 
    {
        $this->addColumn('product_add_checkbox', array(
            'id' => 'product_add_checkbox',
            'header_css_class' => 'a-center',
            'align'     => 'center',
            'type'      => 'checkbox',
            'field_name'=> '',
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'product_id',
            'renderer' => 'mybrand/adminhtml_widget_grid_column_renderer_checkbox'
        ));
        
        
        
        $this->addColumn('name', array(
            'header'    => Mage::helper('sales')->__('Product Name'),
            'index'     => 'name',
            'column_css_class' => 'product_name'
        ));
        
        $this->addColumn('type',
            array(
                'header'=> Mage::helper('catalog')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
                'column_css_class' => 'product_type'
        ));
        
        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();
        
        $this->addColumn('set_name',
            array(
                'header'=> Mage::helper('catalog')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
        ));
        
        $this->addColumn('sku', array(
            'header'    => Mage::helper('sales')->__('SKU'),
            'width'     => '80px',
            'index'     => 'sku',
            'column_css_class'=> 'product_sku'
        ));
        
        $this->addColumn('price', array(
            'header'    => Mage::helper('sales')->__('Price'),
            'align'     => 'center',
            'type'      => 'currency',
            'currency_code' => $this->_getStore()->getDefaultCurrencyCode(),
            'rate'      => $this->_getStore()->getBaseCurrency()->getRate($this->_getStore()->getDefaultCurrencyCode()),
            'index'     => 'price',
            'column_css_class' => 'product_price'
        ));
        
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('websites',
                array(
                    'header'=> Mage::helper('catalog')->__('Websites'),
                    'width' => '100px',
                    'sortable'  => false,
                    'index'     => 'websites',
                    'type'      => 'options',
                    'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
            ));
        }
        
        $this->addColumn('product_status',
            array(
                'header'=> Mage::helper('catalog')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ));

        
    }

    /**
     * define custom widget renderer here.
     */
    /*public function getColumnRenderers()
    {   
        return array('massaction' => 'adminhtml/widget_grid_column_renderer_massaction');
    }*/

    /**
     * Grid url getter
     *
     * @return string current grid url
     */
    public function getGridUrl()
    {   
        return $this->getUrl('*/manufacturer/grid', array('index' => $this->getIndex(),'_current'=>true));
    }

    /**
     * Get array with product ids, which was added to Manufacturer
     *
     * @return array
     */
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
     * Checkbox Check JS Callback
     *
     * @return string
     */
    public function getCheckboxCheckCallback()
    {
        return "function (grid, event) {
            brandProduct.productGridCheckboxCheck(event);
        }";

    }


    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * Get store model by request param
     *
     * @return Mage_Core_Model_Store
     */
    protected function _getStore()
    {
        return Mage::app()->getStore($this->getRequest()->getParam('store'));
    }
}
