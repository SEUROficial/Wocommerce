<?php

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div id="seur-dashboard" class="wrap seur-status">
	<h1><?php esc_html_e( 'Seur Scripts', 'seur' ); ?></h1>
<?php
global $wpdb;
?>
<p><?php _e( 'Copy this code and use phpMyAdmin to create Seur Tables.', 'seur' ); ?></p>
<?php
$sitetableprefix = $wpdb->base_prefix;
$charset_collate = $wpdb->get_charset_collate();
echo "<textarea class='code' readonly='readonly' cols='80' rows='16'>
CREATE TABLE " . $sitetableprefix . "seur_svpr (
	ID bigint(20) unsigned NOT NULL auto_increment,
	ser varchar(3) NOT NULL,
	pro varchar(3) NOT NULL,
	descripcion varchar(50) NOT NULL,
	tipo varchar(50) NOT NULL,
	PRIMARY KEY (ID)
	) " . $charset_collate . ";
CREATE TABLE " . $sitetableprefix . "seur_custom_rates (
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
	) ".$charset_collate.";
</textarea>";
$table_name = $wpdb->prefix . 'seur_svpr';
?>
<p><?php echo esc_html__( 'Copy this code and use phpMyAdmin to create Seur content. Table: ', 'seur' ) . '<code>' . $table_name . '</code>'; ?></p>

<?php


echo "<textarea class='code' readonly='readonly' cols='80' rows='16'>
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('31','2','B2C Estándar','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('3','2','SEUR 10 Estándar','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('3','18','SEUR 10 Frío','FRIO');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('9','2','SEUR 13:30 Estándar','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('9','18','SEUR 13:30 Frío','FRIO');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('15','2','SEUR 48H Estándar','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('13','2','SEUR 72H Estándar','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('77','70','Classic Internacional Terrestre','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('1','48','SEUR 2SHOP','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('7','108','COURIER INT AEREO PAQUETERIA','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('7','54','COURIER INT AEREO DOCUMENTOS','ESTANDAR');
INSERT INTO `" . $table_name . "` (`ser`,`pro`,`descripcion`,`tipo`) VALUES ('19','70','NETEXPRESS INT TERRESTRE','ESTANDAR');
</textarea>";
$table_name = $wpdb->prefix . 'seur_custom_rates';
?>
<p><?php echo esc_html__( 'Copy this code and use phpMyAdmin to create Seur content. Table: ', 'seur' ) . '<code>' . $table_name . '</code>'; ?></p>
<?php
echo "<textarea class='code' readonly='readonly' cols='80' rows='16'>
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','*','*','0','60','0','1000','B2C Estándar','10');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','*','*','60','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','PT','*','*','0','60','0','1000','B2C Estándar','10');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','PT','*','*','60','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','AD','*','*','0','60','0','1000','B2C Estándar','10');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','AD','*','*','60','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','PM','*','0','100','0','1000','B2C Estándar','15');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','PM','*','100','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','GC','*','0','200','0','1000','B2C Estándar','35');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','GC','*','200','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','CE','*','0','300','0','1000','B2C Estándar','40');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','CE','*','300','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','ML','*','0','300','0','1000','B2C Estándar','40');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','ES','ML','*','300','9999999','0','1000','B2C Estándar','0');
INSERT INTO `" . $table_name . "` (`type`,`country`,`state`,`postcode`,`minprice`,`maxprice`,`minweight`,`maxweight`,`rate`,`rateprice` ) VALUES ('price','*','*','*','0','9999999','0','1000','Classic Internacional Terrestre','15');

</textarea>";

$password       = get_site_option( 'seur_pass_for_download' );
$file_path_name = get_site_option( 'seur_download_file_path' );

preg_match( '/(?:seur\-downloader\-)(?:.*)(?:\.php)$/', $file_path_name, $file_name );

?>
<p><?php echo esc_html__( 'Create a file called', 'seur' ) . ' ' . '<code>' . $file_name[0] . '</code>' . ' ' . esc_html__( 'in', 'seur' ) . '<code>' . ' ' . $file_path_name . '</code>'; ?></p>
<p><?php esc_html_e( 'Add this code to the file', 'seur' ); ?></p>
<?php
echo '<textarea class="code" readonly="readonly" cols="80" rows="16">
<?php
	$file      = $_GET["label"];
	$name      = $_GET["label_name"];
	$password  = $_GET["pass"];
	$file_type = $_GET["file_type"];

	if ( $file_type == "pdf" ){
		$headercontent = "application/pdf";
	} else {
		$headercontent = "text/plain";
	}

	if( $password == "' . $password . '" ) {
		if ( file_exists( $file ) ) {
			header("Content-Disposition: attachment; filename=" . $name );
			header("Content-type: " . $headercontent );
			header("Expires: 0");
			header("Cache-Control: must-revalidate");
			header("Pragma: public");
			header("Content-Length: " . filesize( $file ) . "");

			readfile( $file );
			exit;
		}
	} else {
		exit;
	}
</textarea>';
