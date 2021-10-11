<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.4.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2021 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php
if(empty($this->html)) {
	echo '<div style="font-size:16px; font-weight:700;">Dziękujemy za złożenie zamówienia. Wkrótce otrzymasz paczkę. 😊<br><br/><a style="font-weight:400; font-size:13px;" href="https://www.fitomento.com/kategorie-produktow">Wróć do listy kategorii produktów</a></div>';
	//if(!empty($this->url))
		//echo '<br/>'.JText::sprintf('YOU_CAN_NOW_ACCESS_YOUR_ORDER_HERE', $this->url);
} else {
	echo $this->html;
}
$this->nextButton = false;
