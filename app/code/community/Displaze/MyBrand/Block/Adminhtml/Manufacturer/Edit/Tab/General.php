<?php

/* 
 * General.php
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

class Displaze_MyBrand_Block_Adminhtml_Manufacturer_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
         /* @var $model Displaze_MySlideshow_Slideshow */
        $model = Mage::registry('mybrand_manufacturer');

        /*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('mybrand_manufacturer_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('mybrand')->__('Manufacturer')));
        if($model) {
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }
        }

        
        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('mybrand')->__('Title'),
            'title'     => Mage::helper('mybrand')->__('Title'),
            'required'  => true,
            'disabled'  => $isElementDisabled
        ));
        
        $fieldset->addField('identifier', 'text', array(
            'name'      => 'identifier',
            'label'     => Mage::helper('mybrand')->__('Url Key'),
            'title'     => Mage::helper('mybrand')->__('Url Key'),
            'required'  => true,
            'disabled'  => $isElementDisabled
        ));
        
        
        $fieldset->addField('logo', 'file', array(
            'label'     => Mage::helper('mybrand')->__('Logo Image'),
            'required'  => false,
            'name'      => 'logo',
            'after_element_html' => $this->_getImage() != false ? $this->_getImage() : ''
        ));
        
        $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'label'     => Mage::helper('mybrand')->__('Content'),
            'title'     => Mage::helper('mybrand')->__('Content'),
            'required'  => true,
            'disabled'  => $isElementDisabled
        ));

      
        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('mybrand')->__('Store View'),
                'title'     => Mage::helper('mybrand')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('mybrand')->__('Status'),
            'title'     => Mage::helper('mybrand')->__('Status'),
            'name'      => 'status',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('mybrand')->__('Enabled'),
                '0' => Mage::helper('mybrand')->__('Disabled'),
            ),
            'disabled'  => $isElementDisabled
        ));

        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '0' : '1');
        }

       // Mage::dispatchEvent('renegade_mylink_link_edit_tab_main_prepare_form', array('form' => $form));
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * return image url if it exists
     * @return boolean 
     */
    protected function _getImage()
    {
        $id = $this->getRequest()->getParam('id');
        if($id) {
            $manufacturer = Mage::getModel('mybrand/manufacturer')->load($id);
            $image = Mage::helper('mybrand')->getBrandMediaUrl() . $manufacturer->getLogo();
            //return "<img src={$image} width='22' height='22' /><br /><input type='checkbox' id='delete-image' name='delete_image' /><label for='delete-image'> Delete Image</label>";
            return "<img src={$image} width='22' height='22' />";
        }
        
        return false;
    }

    /**
    * Prepare label for tab
    *
    * @return string
    */
    public function getTabLabel()
    {
        return Mage::helper('mybrand')->__('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('mybrand')->__('General');
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

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        //return Mage::getSingleton('admin/session')->isAllowed('mylink/link/' . $action);
        return true;
    }

    
}