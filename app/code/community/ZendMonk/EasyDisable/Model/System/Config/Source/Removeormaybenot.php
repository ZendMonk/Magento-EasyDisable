<?php
/**
 * Remove from Dashboard or maybe not config source
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Model_System_Config_Source_Removeormaybenot
{
	/**
     * Retrieve options
     *
     * @return array
     */
	public function toOptionArray()
    {
		return array(
            array('value' => 0, 'label' => Mage::helper('zendmonk_easydisable')->__('Don\'t remove')),
			array('value' => 1, 'label' => Mage::helper('zendmonk_easydisable')->__('Remove if not necessary')),
			array('value' => 2, 'label' => Mage::helper('zendmonk_easydisable')->__('Remove always'))
        );
    }
}