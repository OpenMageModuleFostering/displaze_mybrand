<?php

/* 
 * ItemController.php
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

class Displaze_MyBrand_Adminhtml_ManufacturerController extends Mage_Adminhtml_Controller_Action
{
    
    protected function _construct()
    {
        $this->setUsedModuleName('Displaze_MyBrand');
    }
    
    protected function _initAction()
    {
        $this->loadLayout()
         ->_setActiveMenu('displaze')
         ->_addBreadcrumb(Mage::helper('mybrand')->__('Manufacturer'), Mage::helper('mybrand')->__('Manufacturer'))
         ->_addBreadcrumb(Mage::helper('mybrand')->__('Manage Manufacturer'), Mage::helper('mybrand')->__('Manage Manufacturer'));
         return $this;
    }
    
    
    /**
     * Action layout is defined in adminhtml/mybrand.xml 
     */
    public function indexAction() 
    {
        $this->_initAction();
        //layout is loaded from xml file.
        $this->renderLayout();
    }
    
    public function editAction()
    {
        $this->_title($this->__('Displaze MyBrand'))
             ->_title($this->__('Manufactuer'));
             
        
        
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('mybrand/manufacturer');
        
        // 2. Initial checking
        if ($id) {
            $model->load($id); 
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mybrand')->__('This manufacturer no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        
        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Manufacturer'));

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $model->setData($data);
        }
        
        // 4. Register model to use later in blocks
        Mage::register('mybrand_manufacturer', $model);
        
        // 5. Build edit form
        $this->_initAction()
            ->_addBreadcrumb(
                $id ? Mage::helper('mybrand')->__('Edit Manufacturer')
                    : Mage::helper('mybrand')->__('New Manufacturer'),
                $id ? Mage::helper('mybrand')->__('Edit Manufacturer')
                    : Mage::helper('mybrand')->__('New Manufacturer'));

        $this->renderLayout();
    }
    
    
    public function newAction()
    {
        $this->_forward('edit');
    }
    
    /**
     * save manufacturer
     */
    public function saveAction()
    {
       // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            $data = $this->_filterPostData($data);
            //init model and set data
            $model = Mage::getModel('mybrand/manufacturer');

            if ($id = $this->getRequest()->getParam('id')) {
                $model->load($id);
            }

            $model->setData($data);

            Mage::dispatchEvent('mybrand_manufacturer_prepare_save', array('manufacturer' => $model, 'request' => $this->getRequest()));

            //validating
            //if (!$this->_validatePostData($data)) {
            //    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
            //    return;
            //}

            // try to save it
            try {
                // save the data
                $model->save();

                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mybrand')->__('The manufacturer has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current'=>true));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('mybrand')->__('An error occurred while saving the manufacturer.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
        $this->_redirect('*/*/');
    }
    
     /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('activation_time_from', 'activation_time_to'));
        return $data;
    }
    
    
     /**
     * Grid with available products for Brand Manufacturer
     * This action is bind with any grid related events like sorting and
     * filtering of products.
     * 
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()
                ->createBlock('mybrand/adminhtml_manufacturer_edit_tab_product_grid')
                ->setIndex($this->getRequest()->getParam('index'))
                ->toHtml()
           );
    }
    
    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('id')) {
            $title = "";
            try {
                // init model and delete
                $model = Mage::getModel('mybrand/manufacturer');
                $model->load($id);
                $title = $model->getTitle();
                //delete image before deleting the record
                $logo = Mage::helper('mybrand')->getBrandMediaPath() . $model->getLogo();
                @unlink($logo);
                
                $model->delete();
                // display success message
                $message = sprintf(Mage::helper('mybrand')->__('The %s manufactuerer has been deleted.'), $title);
                Mage::getSingleton('adminhtml/session')->addSuccess($message);
                // go to grid
                Mage::dispatchEvent('mybrand_manufacturer_on_delete', array('title' => $title, 'status' => 'success'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::dispatchEvent('mybrand_manufacturer_on_delete', array('title' => $title, 'status' => 'fail'));
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('mybrand')->__('Unable to find a manufacturer to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }

}