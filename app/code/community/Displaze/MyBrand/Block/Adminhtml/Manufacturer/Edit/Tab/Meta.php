<?php

/* 
 * Meta.php
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

class Displaze_MyBrand_Block_Adminhtml_Manufacturer_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    protected function _prepareForm()
    {
         /* @var $model Displaze_MyBrand_Brand */
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

        
        $fieldset->addField('meta_title', 'text', array(
            'name'      => 'meta_title',
            'label'     => Mage::helper('mybrand')->__('Meta Title'),
            'title'     => Mage::helper('mybrand')->__('Meta Title'),
            'required'  => false,
            'disabled'  => $isElementDisabled
        ));
        
        $fieldset->addField('meta_keyword', 'text', array(
            'name'      => 'meta_keyword',
            'label'     => Mage::helper('mybrand')->__('Meta Keywords'),
            'title'     => Mage::helper('mybrand')->__('Meta Keywords'),
            'required'  => false,
            'disabled'  => $isElementDisabled
        ));
        
        $fieldset->addField('meta_description', 'textarea', array(
            'name'      => 'meta_description',
            'label'     => Mage::helper('mybrand')->__('Meta Description'),
            'title'     => Mage::helper('mybrand')->__('Meta Description'),
            'required'  => false,
            'disabled'  => $isElementDisabled
        ));
        
        
        

       // Mage::dispatchEvent('renegade_mylink_link_edit_tab_main_prepare_form', array('form' => $form));
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

   
    /**
    * Prepare label for tab
    *
    * @return string
    */
    public function getTabLabel()
    {
        return Mage::helper('mybrand')->__('Meta Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('mybrand')->__('Meta Information');
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