<?php
/**
 * Rewrite of Customer Account Navigation block
 *
 * @category   ZendMonk
 * @package    ZendMonk_EasyDisable
 * @author     Carl Monk <@ZendMonk>
 */
class ZendMonk_EasyDisable_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation
{
	/**
	 * Custom function to remove Account Navigation Link by name
	 *
	 * @param string $name
	 * @return ZendMonk_EasyDisable_Block_Customer_Account_Navigation
	 */
	public function removeLinkByName($name)
	{
		unset($this->_links[$name]);
		return $this;
	}
}