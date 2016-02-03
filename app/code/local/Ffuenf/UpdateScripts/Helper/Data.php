<?php
/**
 * Ffuenf_UpdateScripts extension.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ffuenf
 *
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_UpdateScripts_Helper_Data extends Ffuenf_Common_Helper_Core
{
    const CONFIG_EXTENSION_ACTIVE = 'ffuenf_updatescripts/general/enabled';

    /**
     * @var array
     */
    protected $_localeTemplatePath = array();

    /**
     * Variable for if the extension is active.
     *
     * @var bool
     */
    protected $_bExtensionActive;

    /**
     * Check to see if the extension is active.
     *
     * @return bool
     */
    public function isExtensionActive()
    {
        return $this->getStoreFlag(self::CONFIG_EXTENSION_ACTIVE, '_bExtensionActive');
    }

    /**
     * Takes a serialized data and do a search/replace in it
     *
     * @param string $search_for    string to search for (which should be replaced)
     * @param string $replace_with  string to replace search for with
     * @param string $data          serialized data to search in
     *
     * @return string updated serialized data
     */
    public function updateSerializedData($search_for, $replace_with, $data)
    {
        $unserialized = unserialize($data);
        recursive_array_replace($search_for, $replace_with, $unserialized);
        $edited_serialized_data = serialize($unserialized);
        return $edited_serialized_data;
    }

    /**
     * Takes an array and add it (merges it) with a serialized data
     *
     * @param array $add_to_array  array which should be included in the serialized data
     * @param string $data         serialized data to update
     *
     * @return string updated serialized data
     */
    public function addToSerializedData($add_to_array, $data)
    {
        $unserialized = unserialize($data);
        $merged = array_merge($unserialized, $add_to_array);
        $edited_serialized_data = serialize($merged);
        return $edited_serialized_data;
    }

    /**
     * Takes a serialized data and removes an entry in it
     *
     * @param string $search_for_array    string to search for (which should be replaced)
     * @param string $data          serialized data to search in
     *
     * @return string updated serialized data
     */
    public function removeFromSerializedData($search_for_array, $data)
    {
        $unserialized = unserialize($data);
        foreach ($search_for_array as $search_for) {
            $this->recursive_array_remove($search_for, $unserialized);
        }
        $edited_serialized_data = serialize($unserialized);
        return $edited_serialized_data;
    }

    /**
     * Helper function for updateSerializedData
     *
     * @param string $find     string to search for (which should be replaced)
     * @param string $replace  string to replace search for with
     * @param string &$data    serialized data to search in
     *
     * @return void
     */
    protected function recursive_array_replace($find, $replace, &$data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    recursive_array_replace($find, $replace, $data[$key]);
                } else {
                    // have to check if it's string to ensure no switching to string for booleans/numbers/nulls - don't need any nasty conversions
                    if (is_string($value)) {
                        $data[$key] = str_replace($find, $replace, $value);
                    }
                }
            }
        } else {
            if (is_string($data)) {
                $data = str_replace($find, $replace, $data);
            }
        }
    }

    /**
     * Helper function for removeFromSerializedData
     *
     * @param string &$data    serialized data to search in
     *
     * @return void
     */
    protected function recursive_array_remove($search_for, &$data) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->recursive_array_remove($search_for, $data[$key]);
            } else {
                if (strpos($key, $search_for) !== false) {
                    unset($data[$key]);
                }
            }
        }
    }

    /**
     * Retrieve email template path for given locale
     *
     * @param  string $locale Locale
     * @return string Locale Template Path
     */
    public function getLocaleEmailPath($locale = 'de_DE')
    {
        if (!isset($this->_localeTemplatePath[$locale])) {
            $_localeTemplatePath = 'app' . DS . 'locale' . DS . $locale . DS . 'template' . DS . 'email' . DS;
            $this->_localeTemplatePath[$locale] = Mage::getBaseDir() . DS . $_localeTemplatePath;
            if (!is_dir($this->_localeTemplatePath[$locale])) {
                Mage::throwException(
                    Mage::helper('ffuenf_updatescripts')->__(
                        'Directory "%s" not found. Locale not installed?',
                        $this->_localeTemplatePath[$locale]
                    )
                );
            }
        }
        return $this->_localeTemplatePath[$locale];
    }

    /**
     * Get template content
     *
     * @param  string $filename Template file name
     * @return string Template content
     */
    public function getTemplateContent($filename)
    {
        return @file_get_contents($filename);
    }

    /**
     * add cms pages or static blocks
     *
     * @param  array $data
     * @param  int $storeId store-id
     * @param  bool $isPage whether its a page or static block
     */
    public function saveCmsData($data, $storeId, $isPage = false) {
        $store = array($storeId);
        $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
        if ($isPage) {
            $model = Mage::getModel('cms/page');
        } else {
            $model = Mage::getModel('cms/block');
        }
        $collection = $model->getCollection()
                            ->addFieldToFilter('identifier', $data['identifier'])
                            ->addStoreFilter($storeId)
                            ->addFieldToFilter('store_id', array('in' => $store));
        $cmsItem = $collection->getFirstItem();
        if ($cmsItem && ($cmsItem->getBlockId()||$cmsItem->getPageId())) {
            $cmsItem->load();
            $oldData = $cmsItem->getData();
            $data = array_merge($oldData, $data);
            $cmsItem->setData($data)->save();
        } else {
            $model->setData($data)->save();
        }
        return $this;
    }
}
