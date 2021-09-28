<?php
/**
 * Rewrite of Customer Form Register block
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Block_Customer_Form_Register extends Mage_Customer_Block_Form_Register
{
    /**
     *  Rewrite core function to evt. return false
     *
     *  @return boolean
     */
    public function isNewsletterEnabled()
    {
		if (Mage::helper('zendmonk_easydisable')->isNewsletterDisabled() ||
			Mage::helper('zendmonk_easydisable/dashboard')->removeNewsletterSubscriptions()) {
			return false;
		}
        return parent::isNewsletterEnabled();
    }
}
