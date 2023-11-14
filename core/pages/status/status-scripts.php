<?php
/**
 * SEUR tatus Scripts.
 *
 * @package SEUR
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div id="seur-dashboard" class="wrap seur-status">
	<h1><?php esc_html_e( 'Seur Scripts', 'seur' ); ?></h1>
<?php
global $wpdb;
?>
<p><?php esc_html_e( 'Copy this code and use phpMyAdmin to create Seur Tables.', 'seur' ); ?></p>
<?php
$sitetableprefix  = $wpdb->base_prefix;
$charset_collate2 = $wpdb->get_charset_collate();
echo "<textarea class='code' readonly='readonly' cols='80' rows='16'>
CREATE TABLE " . esc_html( $sitetableprefix ) . "seur_custom_rates (
	ID bigint(20) unsigned NOT NULL auto_increment,
	type varchar(50) NOT NULL default 'price',
	country varchar(50) NOT NULL default '',
	state varchar(200) NOT NULL default '',
	postcode varchar(7) NOT NULL default '00000',
	minprice decimal(20,2) unsigned NOT NULL default '0.00',
	maxprice decimal(20,2) unsigned NOT NULL default '0.00',
	minweight decimal(20,2) unsigned NOT NULL default '0.00',
	maxweight decimal(20,2) unsigned NOT NULL default '0.00',
	rate varchar(200) NOT NULL default '',
	rateprice decimal(20,2) unsigned NOT NULL default '0.00',
	PRIMARY KEY (ID)
	) " . esc_html( $charset_collate2 ) . ';
</textarea>';

$table_name = $wpdb->prefix . 'seur_custom_rates';
?>
<p><?php echo esc_html__( 'Copy this code and use phpMyAdmin to create Seur content. Table: ', 'seur' ) . '<code>' . esc_html( $table_name ) . '</code>'; ?></p>
<?php
echo "<textarea class='code' readonly='readonly' cols='80' rows='16'>
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','*','*','0','60','0','1000','B2C Estándar','10');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','*','*','60','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','PT','*','*','0','60','0','1000','B2C Estándar','10');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','PT','*','*','60','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','AD','*','*','0','60','0','1000','B2C Estándar','10');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','AD','*','*','60','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','PM','*','0','100','0','1000','B2C Estándar','15');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','PM','*','100','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','GC','*','0','200','0','1000','B2C Estándar','35');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','GC','*','200','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','CE','*','0','300','0','1000','B2C Estándar','40');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','CE','*','300','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','ML','*','0','300','0','1000','B2C Estándar','40');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','ML','*','300','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . esc_html( $table_name ) . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','*','*','*','0','9999999','0','1000','Classic Internacional Terrestre','15');

</textarea>";
