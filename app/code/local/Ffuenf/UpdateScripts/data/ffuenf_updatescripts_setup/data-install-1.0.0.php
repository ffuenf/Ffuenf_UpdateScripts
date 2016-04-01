<?php
/*
 * for reference see
 * http://www.webguys.de/magento/eav-attribute-setup/
 * http://www.webguys.de/magento/adventskalender/turchen-23-installscripte-in-magento/
 */
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
 * add transactional email template
 *
$identifier      = '_identifier';
$storeId         = 1;
$locale          = 'en_US';
$templateContent = Mage::helper('ffuenf_updatescripts')->getTemplateContent($identifier, $locale, 'email');
$cmsPage         = array(
                    'title'      => 'Your title',
                    'identifier' => $identifier,
                    'content'    => $templateContent
                 );
Mage::helper('ffuenf_updatescripts')->saveCmsData($cmsPage, $storeId, 'email');
*/

/*
 * add cms static block
 *
$identifier      = '_identifier';
$storeId         = 1;
$locale          = 'en_US';
$templateContent = Mage::helper('ffuenf_updatescripts')->getTemplateContent($identifier, $locale, 'block');
$cmsBlock        = array(
                    'title'      => 'Your title',
                    'identifier' => $identifier,
                    'content'    => $templateContent,
                    'is_active'  => 1,
                    'sort_order' => 0,
                    'stores'     => array($storeId),
                    'view_mode'  => 'core' # or 'easytemplate' for Webguys_EasyTemplate
                 );
Mage::helper('ffuenf_updatescripts')->saveCmsData($cmsBlock, $storeId, 'block');
*/

/*
 * add cms page
 *
$identifier      = '_identifier';
$storeId         = 1;
$locale          = 'en_US';
$templateContent = Mage::helper('ffuenf_updatescripts')->getTemplateContent($identifier, $locale, 'page');
$cmsPage         = array(
                    'title'      => 'Your title',
                    'identifier' => $identifier,
                    'content'    => $templateContent,
                    'is_active'  => 1,
                    'sort_order' => 0,
                    'stores'     => array($storeId),
                    'view_mode'  => 'core' # or 'easytemplate' for Webguys_EasyTemplate
                 );
Mage::helper('ffuenf_updatescripts')->saveCmsData($cmsPage, $storeId, 'page');
*/

$installer->endSetup();