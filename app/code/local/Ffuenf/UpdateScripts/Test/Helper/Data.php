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

/**
 * @see Ffuenf_UpdateScripts_Helper_Data
 *
 * @loadSharedFixture shared
 */
class Ffuenf_UpdateScripts_Test_Helper_Data extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Tests whether extension is active.
     *
     * @test
     * @covers Ffuenf_UpdateScripts_Helper_Data::isExtensionActive
     */
    public function testIsExtensionActive()
    {
        $this->assertTrue(
            Mage::helper('ffuenf_updatescripts')->isExtensionActive(),
            'Extension is not active please check config'
        );
    }
}