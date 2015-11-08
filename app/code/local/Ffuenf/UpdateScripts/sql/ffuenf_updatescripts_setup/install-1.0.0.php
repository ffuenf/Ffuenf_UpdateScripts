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

/*
 * for reference see
 * http://www.webguys.de/magento/eav-attribute-setup/
 * http://www.webguys.de/magento/adventskalender/turchen-23-installscripte-in-magento/
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$conn = $installer->getConnection();

$installer->startSetup();

/*
 * compatibility to SUPEE-6788 / Magento 1.9.2.2+
 *
$installer->setConfigData('admin/security/extensions_compatibility_mode', '0', 'default', '0');
$blockNames = array(
    ''
);
foreach ($blockNames as $blockName) {
    $whitelistBlock = Mage::getModel('admin/block')->load($blockName, 'block_name');
    $whitelistBlock->setData('block_name', $blockName);
    $whitelistBlock->setData('is_allowed', 1);
    $whitelistBlock->save();
}
$variableNames = array(
    ''
);
foreach ($variableNames as $variableName) {
    $whitelistVar = Mage::getModel('admin/variable')->load($variableName, 'variable_name');
    $whitelistVar->setData('variable_name', $variableName);
    $whitelistVar->setData('is_allowed', 1);
    $whitelistVar->save();
}
*/

/*
 * add transactional email templates
 *

$localeEmailPath = Mage::helper('ffuenf_updatescripts')->getLocaleEmailPath();

$templateCode = 'YOUR TEMPLATE CODE';
$template = Mage::getModel('core/email_template')->loadByCode($templateCode);
$template->setTemplateCode($templateCode)
         ->setTemplateType(2)
         ->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
         ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate());
// load template content from file
$templateText = Mage::helper('ffuenf_updatescripts')->getTemplateContent($localeEmailPath . 'sales/sometemplate.html');
if (!$templateText) {
    Mage::log('Failed to create new E-Mail Template: ' . $templateCode);
    return;
}
if (preg_match('/<!--@subject\s*(.*?)\s*@-->/u', $templateText, $matches)) {
    $template->setTemplateSubject($matches[1]);
    $templateText = str_replace($matches[0], '', $templateText);
}
if (preg_match('/<!--@vars\s*((?:.)*?)\s*@-->/us', $templateText, $matches)) {
    $templateText = str_replace($matches[0], '', $templateText);
}
if (preg_match('/<!--@styles\s*(.*?)\s*@-->/s', $templateText, $matches)) {
    $template->setTemplateStyles($matches[1]);
    $templateText = str_replace($matches[0], '', $templateText);
}
$templateText = preg_replace('#\{\*.*\*\}#suU', '', $templateText);
$template->setTemplateText($templateText)->save();
Mage::log('Created new E-Mail Template: ' . $templateCode);
*/

$installer->endSetup();