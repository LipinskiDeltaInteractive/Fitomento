<?php
/**
 * @package	HikaShop for Joomla!
 * @version	4.4.3
 * @author	hikashop.com
 * @copyright	(C) 2010-2021 HIKARI SOFTWARE. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<style>

#hikashop_messages_warning ul li {color: red !important}
#hikashop_messages_warning ul li a{color: red !important}

</style>
<style>
#hikashop_messages_error ul li{padding:15px !important; color:red !important;margin-bottom:20px !important; font-size:19px !important; font-weight:700 !important; background: rgba(193, 66, 66, 0.3) !important}
#hikashop_messages_error ul	{padding:0 !important; margin:0 !important; list-style-type: none !important;}
</style>
<?php if(empty($this->ajax)) { ?>
<div id="hikashop_checkout_coupon_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>" data-checkout-step="<?php echo $this->step; ?>" data-checkout-pos="<?php echo $this->module_position; ?>" class="hikashop_checkout_coupon">
<?php } ?>
	<div class="hikashop_checkout_loading_elem"></div>
	<div class="hikashop_checkout_loading_spinner"></div>

<?php
	$this->checkoutHelper->displayMessages('coupon');

	$cart = $this->checkoutHelper->getCart();
	//
	$czy_produkt_za_kod=0;
	foreach($cart->products as $produkt_pojedynczy){
		if($produkt_pojedynczy->produkttylkozkod=='tak'){ $czy_produkt_za_kod=1; }
		
	}
	if(empty($cart->coupon)) {
		if($czy_produkt_za_kod==0){}else{ 
?><style>
#hikabtn_checkout_next{display:none}
</style>
<div style="font-size:16px !important; font-weight:400">W Twoim koszyku znajduje się produkt dostępny do zakupu jedynie za KOD. Wpisz swój kod i kliknij "Dodaj", a następnie przejdź "Dalej”.<br/><br/>
</div>

	<label style="font-size:20px !important" for="hikashop_checkout_coupon_input_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>"><?php echo JText::_('HIKASHOP_ENTER_COUPON'); ?></label>
	<div class="input-append">
		<input class="hikashop_checkout_coupon_field" id="hikashop_checkout_coupon_input_<?php echo $this->step; ?>_<?php echo $this->module_position; ?>" type="text" name="checkout[coupon]" value=""/>
		<button type="submit" onclick="return window.checkout.submitCoupon(<?php echo $this->step.','.$this->module_position; ?>);" class="<?php echo $this->config->get('css_button','hikabtn'); ?> hikabtn-primary hikabtn_checkout_coupon_add"><?php
			echo JText::_('ADD');
		?></button>
		</div>
<?php
		}
		} else {
		echo JText::sprintf('HIKASHOP_COUPON_LABEL', @$cart->coupon->discount_code);
		if(empty($cart->cart_params->coupon_autoloaded)) {
			global $Itemid;
			$url_itemid = '';
			if(!empty($Itemid))
				$url_itemid = '&Itemid=' . $Itemid;
?>
	<br/><br/><a style="font-size:16px !important;font-weight:400" href="#removeCoupon" onclick="return window.checkout.removeCoupon(<?php echo $this->step; ?>,<?php echo $this->module_position; ?>);" title="<?php echo JText::_('REMOVE_COUPON'); ?>">
		<i class="fas fa-trash"></i>Zmień kod kuponu
	</a>
<?php
		}
	}

	if(empty($this->ajax)) { ?>
</div>
<script type="text/javascript">
if(!window.checkout) window.checkout = {};
window.Oby.registerAjax(['checkout.coupon.updated','cart.updated'], function(params){
	if(params && (params.cart_empty || (params.resp && params.resp.empty))) return;
	window.checkout.refreshCoupon(<?php echo (int)$this->step; ?>, <?php echo (int)$this->module_position; ?>);
});
window.checkout.refreshCoupon = function(step, id) { return window.checkout.refreshBlock('coupon', step, id); };
window.checkout.submitCoupon = function(step, id) {
	var el = document.getElementById('hikashop_checkout_coupon_input_' + step + '_' + id);
	if(!el)
		return false;
	if(el.value == '') {
		window.Oby.addClass(el, 'hikashop_red_border');
		return false;
	}
	return window.checkout.submitBlock('coupon', step, id);
};
window.checkout.removeCoupon = function(step, id) {
	window.checkout.submitBlock('coupon', step, id, {'checkout[removecoupon]':1});
	return false;
};
</script>
<?php }
