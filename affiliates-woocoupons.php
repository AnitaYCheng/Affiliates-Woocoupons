<?php
/**
 * affiliates-woocoupons.php
 *
 * Copyright (c) 2011,2012 Antonio Blanco http://www.blancoleon.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Antonio Blanco
 * @package affiliates-woocoupons
 * @since affiliates-woocoupons 1.1
 *
 * Plugin Name: Affiliates WooCoupons Link
 * Plugin URI: http://itthinx.com
 * Description: Applies Woocommerce coupon automatically if you are referred by an affiliate that has a coupon assigned.
 * Author: eggemplo, modified by Anita Cheng
 * Version: 1.1
 * Author URI: http://www.eggemplo.com
**/

define( 'AFFILIATES_WOOCOUPONS_DOMAIN', 'affiliates-woocoupons' );

define( 'AFFILIATES_WOOCOUPONS_FILE', __FILE__ );


class Affiliates_Woocoupons_Plugin {

	public static function init() {
		
		add_action( 'init', array( __CLASS__, 'wp_init' ) );
	}

	public static function wp_init() {

		// Add coupon when user views cart before checkout (shipping calculation page).
		add_action('woocommerce_before_cart_table', array(__CLASS__, 'apply_coupon'));
		
		// Add coupon when user views checkout page (would not be added otherwise, unless user views cart first).
		add_action('woocommerce_before_checkout_form', array(__CLASS__, 'apply_coupon'));
		
	}

	public static function apply_coupon() {
	
		global $woocommerce, $affiliates_db;
		
		if (!class_exists("Affiliates_Service"))
			include_once( AFFILIATES_CORE_LIB . '/class-affiliates-service.php' );
		
		$aff_id = Affiliates_Service::get_referrer_id();
		
		
		if ( $aff_id ) {
			$attrTable = $affiliates_db->get_tablename( 'affiliates_attributes' );
			$attributes = $affiliates_db->get_objects( "SELECT * FROM $attrTable WHERE affiliate_id = %d", $aff_id );
			$values = array();
			foreach ( $attributes as $attribute ) {
				if ( $attribute->attr_key == "coupons" )
					$coupon_code = $attribute->attr_value;
			}

			// If coupon has been already been added remove it.
			if ($woocommerce->cart->has_discount(sanitize_text_field($coupon_code))) {
				
				if (!$woocommerce->cart->remove_coupons(sanitize_text_field($coupon_code))) {
					
					$woocommerce->show_messages();
			
				}
			
			}
			
			// Add coupon
			if (!$woocommerce->cart->add_discount(sanitize_text_field($coupon_code))) {
				
				$woocommerce->clear_messages();	
			
			} else {
				
				$woocommerce->clear_messages();
				$woocommerce->add_message('"' . $coupon_code . __('" coupon automatically applied', AFFILIATES_WOOCOUPONS_DOMAIN) );
				$woocommerce->show_messages();
			
			}
			
			// Manually recalculate totals.  If you do not do this, a refresh is required before user will see updated totals when discount is removed.
			$woocommerce->cart->calculate_totals();
			
			
		}
		
	}
}

Affiliates_Woocoupons_Plugin::init();

?>
