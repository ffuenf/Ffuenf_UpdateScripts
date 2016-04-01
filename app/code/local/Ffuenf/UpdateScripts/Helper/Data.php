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
     * @param string &$data serialized data to search in
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
     * Retrieve template path for given locale
     *
     * @param  string $locale Locale
     * @param  string $type
     * @return string Locale Template Path
     */
    public function getLocaleTemplatePath($locale = 'de_DE', $type = 'block')
    {
        if (!isset($this->_localeTemplatePath[$locale][$type])) {
            $_localeTemplatePath = 'app' . DS . 'locale' . DS . $locale . DS . 'template' . DS . $type . DS;
            $this->_localeTemplatePath[$locale][$type] = Mage::getBaseDir() . DS . $_localeTemplatePath;
            if (!is_dir($this->_localeTemplatePath[$locale][$type])) {
                throw new Ffuenf_Common_Exception('Directory ' . $this->_localeTemplatePath[$locale][$type] . ' not found. Locale not installed?');
            }
        }
        return $this->_localeTemplatePath[$locale][$type];
    }

    /**
     * Get template content
     *
     * @param  string $filename Template file name
     * @return string Template content
     */
    public function getTemplateContent($identifier, $locale = 'de_DE', $type = 'block')
    {
        $filepath = $this->getLocaleTemplatePath($locale, $type);
        $filename = $filepath . DS . $identifier . '.html';
        return @file_get_contents($filename);
    }

    /**
     * add cms pages, static blocks or transactional email templates
     *
     * @param  array $data
     * @param  int $storeId store-id
     * @param  string $type whether its a page, static block or transactional email template
     */
    public function saveCmsData($data, $storeId, $type = 'block') {
        $store = array($storeId);
        $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
        switch ($type) {
            case 'email':
                if (preg_match('/<!--@subject\s*(.*?)\s*@-->/u', $data['content'], $matches)) {
                    $template->setTemplateSubject($matches[1]);
                    $data['content'] = str_replace($matches[0], '', $templateText);
                }
                if (preg_match('/<!--@vars\s*((?:.)*?)\s*@-->/us', $data['content'], $matches)) {
                    $data['content'] = str_replace($matches[0], '', $data['content']);
                }
                if (preg_match('/<!--@styles\s*(.*?)\s*@-->/s', $data['content'], $matches)) {
                    $template->setTemplateStyles($matches[1]);
                    $data['content'] = str_replace($matches[0], '', $data['content']);
                }
                $data['content'] = preg_replace('#\{\*.*\*\}#suU', '', $data['content']);
                $template->setTemplateCode($data['identifier'])
                         ->setTemplateType(2)
                         ->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
                         ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate());
                $template->setTemplateText($data['content'])->save();
                Ffuenf_Common_Model_Logger::logSystem(
                    array(
                        'class'   => 'Ffuenf_UpdateScripts',
                        'level'   => Zend_Log::DEBUG,
                        'message' => 'Created new E-Mail Template: ' . $data['title'],
                        'details' => $data['content']
                    )
                );
                break;
            case 'page':
            case 'block':
            default:
                $model = ($type == 'page') ? Mage::getModel('cms/page') : Mage::getModel('cms/block');
                if (preg_match('/<!--@title\s*(.*?)\s*@-->/u', $data['content'], $matches)) {
                    $data['title'] = $matches[1];
                    $data['content'] = str_replace($matches[0], '', $data['content']);
                }
                if (preg_match('/<!--@heading\s*(.*?)\s*@-->/u', $data['content'], $matches)) {
                    $data['content_heading'] = $matches[1];
                    $data['content'] = str_replace($matches[0], '', $data['content']);
                }
                if (preg_match('/<!--@root_template\s*(.*?)\s*@-->/s', $data['content'], $matches)) {
                    $data['root_template'] = $matches[1];
                    $data['content'] = str_replace($matches[0], '', $data['content']);
                }
                if (preg_match('/<!--@view_mode\s*(.*?)\s*@-->/s', $data['content'], $matches)) {
                    $data['view_mode'] = $matches[1];
                    $data['content'] = str_replace($matches[0], '', $data['content']);
                }
                $data['content'] = preg_replace('#\{\*.*\*\}#suU', '', $data['content']);
                $collection = $model->getCollection()
                                    ->addFieldToFilter('identifier', $data['identifier'])
                                    ->addStoreFilter($storeId)
                                    ->addFieldToFilter('store_id', array('in' => $store));
                $cmsItem = $collection->getFirstItem();
                if ($cmsItem && ($cmsItem->getBlockId() || $cmsItem->getPageId())) {
                    $cmsItem->load();
                    $oldData = $cmsItem->getData();
                    $data = array_merge($oldData, $data);
                    $cmsItem->setData($data)->save();
                } else {
                    $model->setData($data)->save();
                }
                Ffuenf_Common_Model_Logger::logSystem(
                    array(
                        'class'   => 'Ffuenf_UpdateScripts',
                        'level'   => Zend_Log::DEBUG,
                        'message' => 'Created new CMS ' . $type . ': ' . $data['title'],
                        'details' => $data['content']
                    )
                );
                break;
        }
        return $this;
    }
}
