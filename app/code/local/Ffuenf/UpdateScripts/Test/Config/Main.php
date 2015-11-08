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

class Ffuenf_UpdateScripts_Test_Config_Main extends EcomDev_PHPUnit_Test_Case_Config
{

    const CONFIG_EXTENSION_NAME_CAPS = 'Ffuenf_UpdateScripts';
    const CONFIG_EXTENSION_NAME_LOWER = 'ffuenf_updatescripts';

    /**
     * Check if the installed module has the correct module version
     *
     * @test
     */
    public function testModuleConfig()
    {
        $this->assertModuleVersionGreaterThanOrEquals($this->expected('module')->getVersion(), 'module is new enough');
        $this->assertModuleCodePool($this->expected('module')->getCodePool(), 'correct module code pool');
        $this->assertModuleIsActive('module is active');
    }

    /**
     * Check if the helper aliases are returning the correct class names
     *
     * @test
     */
    public function testHelperAliases()
    {
        $this->assertHelperAlias(
            self::CONFIG_EXTENSION_NAME_LOWER, self::CONFIG_EXTENSION_NAME_CAPS . '_Helper_Data',
            'correct helper alias'
        );
    }

}
