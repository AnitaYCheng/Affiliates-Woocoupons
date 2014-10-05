Affiliates-Woocoupons
=====================

Applies Woocommerce Coupon automatically if you are referred by an affiliate that has a coupon assigned. Works with the <a href=“http://www.itthinx.com/documentation/affiliates/“>Affiliates, Affiliates Pro and Affiliates Coupon plugins by Itthinx</a>.

Tested up to WooCommerce 2.2.4 and WordPress 4.0.


## Why?
I set this up so that a referred customer automatically has a cart discount applied if they clicked through an affiliate link. No need for the affiliate to dole out a separate coupon code - all they need is their link!

## Changes
This code is essentially the same as the <a href=“https://github.com/eggemplo/Affiliates-Woocoupons”>original repo by eggemplo</a>, aside from a change on line 84, that does the following:

If there is no coupon assigned to an affiliate (biggest example would be not using an affiliate at all), it clears the error message. Without this change, any non-referred customers (hint: likely most of them) would have a persistent “Coupon does not exist” error on their cart and checkout page. Not cool! So I cleared that.

How to use
==========
Upload the PHP file to your WordPress plugins folder, then activate as a plugin. Everything else is automatic.