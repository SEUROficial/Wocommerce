=== SEUR Oficial ===
Contributors: seuroficial
Tags: woocommerce, shipping, seur, logistica, enviar paquete, pedidos, entregas
Requires at least: 4.0
Tested up to: 6.6.2
Stable tag: 2.2.13
WC requires at least: 3.0
WC tested up to: 9.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add SEUR shipping method to WooCommerce. The SEUR plugin for WooCommerce allows you to manage your order dispatches in a fast and easy way

== Description ==

The SEUR plugin for WooCommerce allows you to manage your order dispatches in a fast and easy way. Generate your labels for each order and request collection from your own facilities whenever you need. You can configure your shipping rates based on urgency of delivery, weight, product price or buyer's postcode.
Choose from a wide range of national and international shipping services. We also offer specific solutions such as SEUR controlled temperature and cash-on-delivery services.
All just a few clicks away, with a Technical Support team to help you get started.

Discover the full potential of the SEUR plugin! Boost your online store!

= Predict =

Our Predict service lets you inform the buyer of the estimated delivery time. Optimise your and your buyers' time, and offer them the best possible buying experience.
Moreover, being part of DPD group means your international customers will have the same Predict experience in their own language.

= Several simultaneous delivery methods =

You can offer your buyers as many delivery options as you wish. Next day delivery, urgent morning delivery, even international deliveries and cold shipping.

= Automatic labels =

Easily generate your order labels with all the recipient's details and download them as often as you want. In thermal hardcopy or PDF format.

= All options just a click away =

Check the status of your shipments, organise collection at your storage facilities, generate the daily package list, check SEUR rates… all part of your store's back-office.

= Quick set-up =

[Leave your details here](https://pub.s7.exacttarget.com/thi131hffjt). Ask SEUR for your registration details, fill in the configuration form and set the transport rate for your buyers. Trust your shipments to the leading e-commerce logistics partner in just 3 steps.


== Installation ==

1. Upload `seur` folder to the `/wp-content/plugins/` directory
3. seur needs write permissions during plugin activation at wp-content and uploads.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Is it a free plugin? =

Yes, you can use the WooCommerce plugin completely free of charge.

= Do I have to be a SEUR customer? =

Yes, only SEUR customers can set up the plugin. You can register with us using this link:

= Can the rates be configured by price or by weight? =

Yes, you can use the plugin's Settings menu to register the rates which will appear in the front-office, based on cart weight or price.

= How can I include SEUR as a shipping option at checkout? =

Make sure you have set up a rate for this product weight or cart price for the zone or province for delivery. Once the rate has been created (for this zone or province), it should appear under these conditions.

= How can I report shipment to SEUR? =

Use the "Get label SEUR" button inside each order. The shipping information will be sent to SEUR automatically when ordering the label.

= Which service/product should I choose when creating a rate? =

The service/product is related to how urgent the delivery is. Contact your SEUR sales advisor for the range of options and rates associated with each service.

= Can I cancel or modify a label? =

No, the information on the label is final, so be sure to check that all the details are correct before generating it.

= How can I ask SEUR to collect? =

The "Collection" menu can be used to request collection in the time zone that best suits you. Collection must be ordered at least two hours in advance.

= What is the cargo manifest and how can I generate it? =

The cargo manifest is a record of the packages given to our SEUR agent for delivery; this document can be downloaded in the "Manifest" section via the SEUR menu. Remember to print two copies: one for you and one for the driver.

= How can I track my Shipments? =

The "SEUR > Shipments" menu includes the option to track your shipment and see the latest status of all shipments made with SEUR.

== Screenshots ==

== Changelog ==

== 2.2.13 ==

* FIXED: Vulnerability Report CVE-2024-9438

== 2.2.12 ==

* ADDED: Add new service 13:30 Frío
* ADDED: PHP8.2 compatibility
* CHANGED: Change _seur_2shop_codCentro value
* FIXED: Fix manifest consig_name
* FIXED: Fix plugins path include
* FIXED: Set shipping address to pick-up location address for pick-up orders
* FIXED: Order weight is computed in excess when the order contains product packs
* FIXED: Cannot select country when editing custom rate
* FIXED: Erroneous rate calculation for local service SEUR 2SHOP

== 2.2.11 ==

* CHANGED: Rates ajax call
* CHANGED: Remove optional parameters to avoid deprecated notice in tcpdf library
* CHANGED: Remove id_number (nif) from request data
* FIXED: Fix COD value
* FIXED: Manifest remove redirect
* FIXED: Woocommerce bug with metadata in checkout block
* FIXED: Woocommerce bug undefined wc_get_page_screen_id

== 2.2.10 ==

* ADDED: Import CSV custom rates
* ADDED: Pickup ID validation in local shipping methods
* FIXED: HPOS generate labels bulk action
* FIXED: Default weight
* FIXED: Custom rates country selector problem with select2 library

== 2.2.9 ==

* CHANGED: Generate PDF Manifest in local

== 2.2.8 ==

* ADDED: Add timetable info in Google Map points
* ADDED: Add customer comments in label
* CHANGED: Use shipping address to get Google Map points when is different from billing address

== 2.2.7 ==

* ADDED: Add ISO info in labels
* CHANGED: Change country field
* FIXED: Fix install required files

== 2.2.6 ==

* ADDED: Clean phone numbers
* ADDED: Add A4_3 printer format
* ADDED: Free shipping message when cost is 0
* ADDED: HPOS compatibility
* ADDED: Default values for international pudo info

== 2.2.5 ==

* ADDED: Add ccc in manifest api call
* CHANGED: Remove deprecated config fields
* FIXED: Fix orders filter
* FIXED: Show all Seur shipping options when transports are available
* FIXED: Fix append labels ZPL

== 2.2.4 ==

* ADDED: Add traking statuses
* ADDED: PDF massive print
* ADDED: Add platform name in shipments endpoint
* ADDED: Add 'change' service
* ADDED: Add accountId in manifest endpoint
* FIXED: Fix international pickup
* FIXED: Fix has_label function warning
* FIXED: Fix filter orders

== 2.2.3 ==

* ADDED: Check if really has label
* FIXED: Error while generating manifest
* FIXED: Incorrect rate computed for items with weight less than 1Kg
* FIXED: Custom name rates field
* FIXED: Check shipping method Seur
* FIXED: Add Classic 2shop as pickup shipping methods to print map
* FIXED: Termica massive print option

== 2.2.2 ==

* CHANGED: Show Seur metabox only for Seur orders
* FIXED: Get Seur products/services from data file instead DB in label generation

== 2.2.1 ==

* CHANGED: Change SOAP calls to API Rest in shipments, labels, pickups points and manifesto

== 2.1.1 ==

* FIXED: An error when using weight for rates.

== 2.1.0 ==

* NEW: New tracking API
* FIXED: Fixed Collections

== 2.0.3 ==

* FIXED: Under some circunstances DB tables were not created.

== 2.0.2 ==

* FIXED: Fixed a priority code sequence.

== 2.0.1 ==

* FIXED: Fixed a problem where under some circumstances a correct date was not generated in the pickups.

== 2.0.0 ==

* NEW: New SEUR API
* Minor fix

== 1.8.0.1 ==

* FIXED: Fixed SEUR nottice link

== 1.8.0 ==

* NEW: Now you can select is Tax are checked for apply rates.
* NEW: Now Shops Managers can create & download Labels.
* Fixed text.

== 1.7.2 ==

* Fixed error with Add rate.


== 1.7.1 ==

* Fixed error with Add rate.

== 1.7.0 ==

* Sanitization, escaping & Code is Poetry.

== 1.6.0 ==

* Added compatibility with WordPress 5.5

== 1.5.2 ==

* Fixed a problem with Free Shipping with different tax.
* Declared compatibility with WooCommerce 4.3

== 1.5.1 ==

* Fixed a problem with pickup method. The selection of the Pickup method in the checkout produced a duplication of other methods.

== 1.5.0 ==

* Declared compatibility with WordPress 5.3 & WooCommerce 4.0
* Added International Pickup
* Removed TGMPA. Many plugins authors doesn't use correctly this plugin causing many problems.


== 1.4.2.1 ==
* Removed some debug lines

== 1.4.2 ==
* Fixed a problem with 13:30 Frío and France
* Fixed a validation with Notify Collection
* Compatibility declared with WooCommerce 3.7
* Added an admin notice

== 1.4.1 ==
* Fixed a problem with dates in Manifest
* Added 13:30 Frío to France rates

== 1.4.0 ==
* New: Added GeoLabel, the new standard for European labels.

== 1.3.0 ==
* Now shop_manager role can manage Labels
* New Status page with Seur Status and Scripts

== 1.2.2 ==
* Improved checkout validation

== 1.2.1 ==
* Fixed a little problem with coupons

== 1.2.0 ==
* NEW: Added compatibility with Coupons, now you can apply a free shipping coupon to SEUR.
* Fixed a problem with SEUR Local Pickup, now works.

== 1.1.0 ==
* NEW: Added Local Pickup
* Added Portugal to some forms.
+ Added International CCC (you need to add it to the plugin settings).
* Fixed some bugs.
* Added SEUR Pickup
* Added 3 new International product/Service

= 1.0.3 =
* Fixed maximun price to charge fee
* Fixed search for Rates

= 1.0.2 =
* Added compatibility with WordPress Multisite.

= 1.0.1 =
* Fixed a bug that breaks the admin orders search when rates are not customized.

= 1.0 =
* First public release.
