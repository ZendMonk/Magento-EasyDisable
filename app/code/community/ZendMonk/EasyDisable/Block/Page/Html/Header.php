<?php
/**
 * Rewrite of Page Html Header block
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Block_Page_Html_Header extends Mage_Page_Block_Html_Header
{
	/**
	 * Rewrite core function to evt. remove Login Welcome Message
	 *
	 * @return string
	 */
	public function getWelcome()
	{
		if (Mage::helper('zendmonk_easydisable/dashboard')->removeLoginWelcomeMessage()) {
			return '';
		}
		return parent::getWelcome();
	}
}