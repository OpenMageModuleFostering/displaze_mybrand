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

class Displaze_MyBrand_Model_Manufacturer extends Mage_Core_Model_Abstract
{
    
    /**
     * Slide's Statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    
    
    protected $_path = '';
    
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('mybrand/manufacturer');
    }
    
    /**
     * get Manufacturer product collection for current manufacturer 
     */
    public function getProductCollection()
    {
        $id = $this->getId();
        return $this->_getResource()->getProductCollection($id);
    }
    
    /**
     * Save Slideshow image. 
     */
    public function saveImage()
    {
        if(isset($_FILES['logo']['name']) && (file_exists($_FILES['logo']['tmp_name']))) {
            
            try {
                
                $uploader = new Varien_File_Uploader('logo');
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'bmp'));
                
                $uploader->setAllowRenameFiles(true);
                //$path = Mage::getBaseDir('media') . DS ;// 'displaze' . DS . 'myslideshow' . DS;
                $path = $this->_initFolderStructure();
                       
                $uploader->save($path, $_FILES['logo']['name']);
                
                $filename = $uploader->getUploadedFileName();
                $this->setData('logo', $filename);
                
                
            } catch(Exception $e) {
                
            }
        }
        
        return $this;
    }
    
    /**
     * initialize folder structure if it does not exist.
     * @return type 
     */
    protected function _initFolderStructure()
    {
        $path = Mage::getBaseDir('media') . DS . 'displaze' . DS;
        if(!is_dir($path)) {
            mkdir($path, 0755);
        }
        
        $path = $path . 'mybrand' . DS;
        if(!is_dir($path)) {
            mkdir($path, 0755);
        }
        
        return $this->_path = $path;
        
    }
    
    public function save() 
    {
        $deleteImage = Mage::app()->getRequest()->getPost('delete_image');
        if($deleteImage) {
            
        }
        
        $this->saveImage();
        parent::save();
        
        return $this;
    }
    
    
    /**
     * Prepare slide's statuses.
     * Available event mybrand_manufacturer_get_available_statuses to customize statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        $statuses = new Varien_Object(array(
            self::STATUS_ENABLED => Mage::helper('mybrand')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('mybrand')->__('Disabled'),
        ));

        Mage::dispatchEvent('mybrand_manufacturer_get_available_statuses', array('statuses' => $statuses));

        return $statuses->getData();
    }
    
    
   
    
    /**
     * Check if manufacturer identifier exist for specific store
     * return manufacturer id if it exists
     *
     * @param string $identifier
     * @param int $storeId
     * @return int
     */
    public function checkIdentifier($identifier, $storeId)
    {
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }
}