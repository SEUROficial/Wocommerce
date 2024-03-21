<?php
/**
 * Seur Installer
 *
 * @package SEUR
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function deleteTableSeurSpvr() {
    global $wpdb;
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}seur_svpr" );
}

function createTableSeurCustomRates() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'seur_custom_rates';
    $sql        = 'CREATE TABLE IF NOT EXISTS ' . $table_name . " (
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
		) ".$charset_collate;
    dbDelta( $sql );
}

function createTableSeurStatus() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'seur_status';

    $sql = 'CREATE TABLE IF NOT EXISTS `'.$table_name.'` (
            `id_status` int(11) NOT NULL,
            `cod_situ` varchar(10) NOT NULL,
            `grupo` varchar(60) NOT NULL
        ) '. $charset_collate;
    dbDelta( $sql );

    $sql = 'ALTER TABLE `'.$table_name.'`
            ADD PRIMARY KEY (`id_status`),
            ADD KEY `grupo` (`grupo`),
            ADD KEY `cod_situ` (`cod_situ`)';
    dbDelta( $sql );
}

function updateMetaSeurShippingMethodService() {
    global $wpdb;
    include_once SEUR_DATA_PATH . 'seur-products.php';
    $products = get_seur_product();

    $sql = "SELECT option_name, option_value as custom_name
            FROM ".$wpdb->prefix."options 
            where option_name like '%_custom_name_field' and option_value != ''";
    $ratesCustomNames = $wpdb->get_results( $sql ); //custom names

    foreach ($products as $code => $product) {
        $ratesNames[$code] = $code;
        foreach ($ratesCustomNames as $rate) {
            $rateName = str_replace('_custom_name_field', '', $rate->option_name);
            if ($product['field'] == $rateName) {
                $ratesNames[$code] = $rate->custom_name;
            }
        }
    }

    $sql = "select order_id, order_item_name 
            from ".$wpdb->prefix . "woocommerce_order_items
            where order_item_type = 'shipping'";
    $ordersShippingMethods = $wpdb->get_results( $sql ); //custom names
    foreach ($ordersShippingMethods as $orderShippingMethod) {
        $rateName = $orderShippingMethod->order_item_name;
        $customName = array_keys($ratesNames, $orderShippingMethod->order_item_name);
        if (!empty($customName)) {
            $rateName = $customName[0];
        }
        //update_post_meta($orderShippingMethod->order_id, '_seur_shipping_method_service_real_name', $rateName);
        $order = seur_get_order($orderShippingMethod->order_id);
        $order->update_meta_data('_seur_shipping_method_service_real_name', $rateName);
        $order->save_meta_data();
    }
}

/**
 * Seur Create Tables Hook
 */
function seur_create_tables_hook() {
	$seur_db_version_saved = get_option( 'seur_db_version' );

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    createTableSeurCustomRates();
    deleteTableSeurSpvr();

    if ( $seur_db_version_saved && ($seur_db_version_saved !== '1.0.4') && ( SEUR_DB_VERSION === '1.0.4') ) {
        createTableSeurStatus();
    }
    update_option( 'seur_db_version', SEUR_DB_VERSION );
}

function insertIntoSeurStatus()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'seur_status';
    $sql = "DELETE FROM `".$table_name."`";
    dbDelta( $sql );

    $sql = "INSERT INTO `".$table_name."` (`id_status`, `cod_situ`, `grupo`) VALUES
        (1, 'LC002', 'EN TRÁNSITO'),
        (2, 'LC001', 'EN TRÁNSITO'),
        (3, 'LC005', 'EN TRÁNSITO'),
        (4, 'LC006', 'EN TRÁNSITO'),
        (5, 'LC101', 'EN TRÁNSITO'),
        (6, 'LC003', 'EN TRÁNSITO'),
        (7, 'LC004', 'EN TRÁNSITO'),
        (8, 'LC845', 'EN TRÁNSITO'),
        (9, 'LC860', 'EN TRÁNSITO'),
        (10, 'SC845', 'EN TRÁNSITO'),
        (11, 'SC883', 'EN TRÁNSITO'),
        (12, 'SC999', 'EN TRÁNSITO'),
        (13, 'SC001', 'EN TRÁNSITO'),
        (14, 'LD221', 'EN TRÁNSITO'),
        (15, 'LD223', 'EN TRÁNSITO'),
        (16, 'LD846', 'EN TRÁNSITO'),
        (17, 'LD841', 'EN TRÁNSITO'),
        (18, 'II718', 'EN TRÁNSITO'),
        (19, 'II701', 'DEVOLUCIÓN EN CURSO'),
        (20, 'II721', 'APORTAR SOLUCIÓN'),
        (21, 'II717', 'APORTAR SOLUCIÓN'),
        (22, 'II719', 'APORTAR SOLUCIÓN'),
        (23, 'II722', 'APORTAR SOLUCIÓN'),
        (24, 'II732', 'APORTAR SOLUCIÓN'),
        (25, 'II740', 'EN TRÁNSITO'),
        (26, 'II731', 'APORTAR SOLUCIÓN'),
        (27, 'II741', 'APORTAR SOLUCIÓN'),
        (28, 'II743', 'APORTAR SOLUCIÓN'),
        (29, 'II748', 'APORTAR SOLUCIÓN'),
        (33, 'II753', 'APORTAR SOLUCIÓN'),
        (34, 'II754', 'APORTAR SOLUCIÓN'),
        (35, 'II742', 'APORTAR SOLUCIÓN'),
        (36, 'II755', 'APORTAR SOLUCIÓN'),
        (37, 'II744', 'APORTAR SOLUCIÓN'),
        (38, 'II746', 'APORTAR SOLUCIÓN'),
        (39, 'II745', 'APORTAR SOLUCIÓN'),
        (40, 'II756', 'APORTAR SOLUCIÓN'),
        (41, 'II757', 'APORTAR SOLUCIÓN'),
        (42, 'II758', 'APORTAR SOLUCIÓN'),
        (43, 'II759', 'APORTAR SOLUCIÓN'),
        (45, 'II761', 'APORTAR SOLUCIÓN'),
        (46, 'II762', 'APORTAR SOLUCIÓN'),
        (48, 'LI566', 'EN TRÁNSITO'),
        (49, 'LI567', 'EN TRÁNSITO'),
        (50, 'LI717', 'APORTAR SOLUCIÓN'),
        (51, 'LI718', 'APORTAR SOLUCIÓN'),
        (52, 'LI719', 'APORTAR SOLUCIÓN'),
        (53, 'LI721', 'APORTAR SOLUCIÓN'),
        (54, 'LI722', 'APORTAR SOLUCIÓN'),
        (55, 'LI731', 'APORTAR SOLUCIÓN'),
        (56, 'LI732', 'APORTAR SOLUCIÓN'),
        (57, 'LI452', 'EN TRÁNSITO'),
        (58, 'LI456', 'EN TRÁNSITO'),
        (59, 'LI457', 'EN TRÁNSITO'),
        (60, 'LI460', 'EN TRÁNSITO'),
        (61, 'LI461', 'EN TRÁNSITO'),
        (62, 'LI462', 'EN TRÁNSITO'),
        (63, 'LI463', 'EN TRÁNSITO'),
        (64, 'LI464', 'EN TRÁNSITO'),
        (65, 'LI406', 'EN TRÁNSITO'),
        (66, 'LI407', 'EN TRÁNSITO'),
        (67, 'LI409', 'EN TRÁNSITO'),
        (68, 'LI328', 'EN TRÁNSITO'),
        (69, 'LI403', 'EN TRÁNSITO'),
        (70, 'LI335', 'EN TRÁNSITO'),
        (71, 'LI410', 'EN TRÁNSITO'),
        (72, 'LI411', 'EN TRÁNSITO'),
        (73, 'LI434', 'EN TRÁNSITO'),
        (74, 'LI439', 'EN TRÁNSITO'),
        (75, 'LI438', 'EN TRÁNSITO'),
        (76, 'LI436', 'EN TRÁNSITO'),
        (77, 'LI437', 'EN TRÁNSITO'),
        (78, 'LI444', 'EN TRÁNSITO'),
        (79, 'LI443', 'EN TRÁNSITO'),
        (80, 'LI412', 'EN TRÁNSITO'),
        (81, 'LI440', 'EN TRÁNSITO'),
        (82, 'LI433', 'EN TRÁNSITO'),
        (83, 'LI441', 'EN TRÁNSITO'),
        (84, 'LI435', 'EN TRÁNSITO'),
        (85, 'LI423', 'EN TRÁNSITO'),
        (86, 'LI442', 'EN TRÁNSITO'),
        (87, 'LI446', 'EN TRÁNSITO'),
        (88, 'LI447', 'EN TRÁNSITO'),
        (89, 'LI458', 'EN TRÁNSITO'),
        (90, 'LI459', 'EN TRÁNSITO'),
        (91, 'LI497', 'EN TRÁNSITO'),
        (92, 'LI510', 'INCIDENCIA'),
        (93, 'LI515', 'EN TRÁNSITO'),
        (94, 'LI516', 'EN TRÁNSITO'),
        (95, 'LI517', 'INCIDENCIA'),
        (96, 'LI518', 'EN TRÁNSITO'),
        (97, 'LI519', 'INCIDENCIA'),
        (98, 'LI520', 'INCIDENCIA'),
        (99, 'LI521', 'INCIDENCIA'),
        (100, 'LI522', 'INCIDENCIA'),
        (101, 'LI523', 'INCIDENCIA'),
        (102, 'LI524', 'INCIDENCIA'),
        (103, 'LI525', 'EN TRÁNSITO'),
        (104, 'LI530', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (105, 'LI531', 'INCIDENCIA'),
        (106, 'LI532', 'APORTAR SOLUCIÓN'),
        (107, 'LI533', 'DEVOLUCIÓN EN CURSO'),
        (108, 'LI535', 'EN TRÁNSITO'),
        (109, 'LI536', 'INCIDENCIA'),
        (110, 'LI537', 'INCIDENCIA'),
        (111, 'LI548', 'EN TRÁNSITO'),
        (112, 'LI550', 'INCIDENCIA'),
        (113, 'LI553', 'INCIDENCIA'),
        (114, 'LI554', 'INCIDENCIA'),
        (115, 'LI563', 'EN TRÁNSITO'),
        (116, 'LI564', 'ENTREGADO'),
        (117, 'LI565', 'EN TRÁNSITO'),
        (118, 'LI725', 'APORTAR SOLUCIÓN'),
        (119, 'LI465', 'ENTREGADO'),
        (120, 'LI466', 'ENTREGADO'),
        (121, 'LI467', 'ENTREGADO'),
        (122, 'LI469', 'ENTREGADO'),
        (123, 'LI470', 'ENTREGADO'),
        (124, 'LI472', 'ENTREGADO'),
        (125, 'LI474', 'ENTREGADO'),
        (126, 'LI476', 'ENTREGADO'),
        (127, 'LI477', 'ENTREGADO'),
        (128, 'LI478', 'ENTREGADO'),
        (129, 'LI480', 'ENTREGADO'),
        (130, 'LI486', 'ENTREGADO'),
        (131, 'LI492', 'ENTREGADO'),
        (132, 'LI493', 'ENTREGADO'),
        (133, 'LI494', 'ENTREGADO'),
        (134, 'LI495', 'ENTREGADO'),
        (135, 'LI308', 'ENTREGADO'),
        (136, 'LI346', 'ENTREGADO'),
        (137, 'LI349', 'ENTREGADO'),
        (138, 'LI401', 'ENTREGADO'),
        (139, 'LI402', 'ENTREGADO'),
        (140, 'LI408', 'ENTREGADO'),
        (141, 'LI420', 'ENTREGADO'),
        (142, 'LI421', 'ENTREGADO'),
        (143, 'LI422', 'ENTREGADO'),
        (144, 'LI425', 'ENTREGADO'),
        (145, 'LI426', 'ENTREGADO'),
        (146, 'LI427', 'ENTREGADO'),
        (147, 'LI428', 'ENTREGADO'),
        (148, 'LI431', 'ENTREGADO'),
        (149, 'LI313', 'ENTREGADO'),
        (150, 'LI350', 'ENTREGADO'),
        (151, 'LI367', 'ENTREGADO'),
        (152, 'LI368', 'ENTREGADO'),
        (153, 'LI416', 'ENTREGADO'),
        (154, 'LI417', 'ENTREGADO'),
        (155, 'LI418', 'ENTREGADO'),
        (156, 'LI445', 'ENTREGADO'),
        (157, 'LI448', 'ENTREGADO'),
        (158, 'LI449', 'ENTREGADO'),
        (159, 'LI453', 'ENTREGADO'),
        (160, 'LI471', 'ENTREGADO'),
        (161, 'LI475', 'ENTREGADO'),
        (162, 'LI482', 'ENTREGADO'),
        (163, 'LI484', 'ENTREGADO'),
        (164, 'LI488', 'ENTREGADO'),
        (165, 'LI490', 'ENTREGADO'),
        (166, 'LI496', 'ENTREGADO'),
        (167, 'LI498', 'ENTREGADO'),
        (168, 'LI499', 'ENTREGADO'),
        (169, 'LI501', 'INCIDENCIA'),
        (170, 'LI511', 'ENTREGADO'),
        (171, 'LI512', 'ENTREGADO'),
        (172, 'LI513', 'APORTAR SOLUCIÓN'),
        (173, 'LI526', 'INCIDENCIA'),
        (174, 'LI528', 'INCIDENCIA'),
        (175, 'LI529', 'INCIDENCIA'),
        (176, 'LI538', 'INCIDENCIA'),
        (177, 'LI540', 'APORTAR SOLUCIÓN'),
        (178, 'LI542', 'INCIDENCIA'),
        (179, 'LI545', 'INCIDENCIA'),
        (180, 'LI546', 'INCIDENCIA'),
        (181, 'LI547', 'EN TRÁNSITO'),
        (182, 'LI552', 'INCIDENCIA'),
        (183, 'LI555', 'INCIDENCIA'),
        (184, 'LI556', 'INCIDENCIA'),
        (185, 'LI560', 'INCIDENCIA'),
        (186, 'LI561', 'INCIDENCIA'),
        (187, 'LI527', 'INCIDENCIA'),
        (188, 'LI539', 'INCIDENCIA'),
        (189, 'LI541', 'INCIDENCIA'),
        (190, 'LI310', 'EN TRÁNSITO'),
        (191, 'LI364', 'EN TRÁNSITO'),
        (192, 'LI575', 'INCIDENCIA'),
        (193, 'LI405', 'EN TRÁNSITO'),
        (194, 'LI413', 'EN TRÁNSITO'),
        (195, 'LI419', 'EN TRÁNSITO'),
        (196, 'LI450', 'EN TRÁNSITO'),
        (197, 'LI489', 'EN TRÁNSITO'),
        (198, 'LI483', 'EN TRÁNSITO'),
        (199, 'LI491', 'EN TRÁNSITO'),
        (200, 'LI534', 'APORTAR SOLUCIÓN'),
        (201, 'LI551', 'INCIDENCIA'),
        (202, 'LI799', 'EN TRÁNSITO'),
        (203, 'LI544', 'INCIDENCIA'),
        (204, 'LI388', 'EN TRÁNSITO'),
        (205, 'LI404', 'EN TRÁNSITO'),
        (206, 'LI424', 'EN TRÁNSITO'),
        (207, 'LI429', 'EN TRÁNSITO'),
        (208, 'LI468', 'EN TRÁNSITO'),
        (209, 'LI569', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (210, 'LI432', 'EN TRÁNSITO'),
        (211, 'LI451', 'EN TRÁNSITO'),
        (212, 'LI314', 'EN TRÁNSITO'),
        (213, 'LI329', 'EN TRÁNSITO'),
        (214, 'LI414', 'EN TRÁNSITO'),
        (215, 'LI415', 'EN TRÁNSITO'),
        (216, 'LI454', 'EN TRÁNSITO'),
        (217, 'LI479', 'EN TRÁNSITO'),
        (218, 'LI481', 'EN TRÁNSITO'),
        (219, 'LI485', 'EN TRÁNSITO'),
        (220, 'LI487', 'EN TRÁNSITO'),
        (221, 'LI873', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (222, 'LI880', 'INCIDENCIA'),
        (223, 'LI882', 'INCIDENCIA'),
        (224, 'LI862', 'INCIDENCIA'),
        (225, 'LI866', 'INCIDENCIA'),
        (226, 'LI868', 'INCIDENCIA'),
        (227, 'LI869', 'INCIDENCIA'),
        (228, 'LI849', 'INCIDENCIA'),
        (229, 'LI863', 'INCIDENCIA'),
        (230, 'LI876', 'INCIDENCIA'),
        (231, 'LI879', 'INCIDENCIA'),
        (232, 'LI853', 'INCIDENCIA'),
        (233, 'LI847', 'INCIDENCIA'),
        (234, 'LI848', 'INCIDENCIA'),
        (235, 'LI864', 'APORTAR SOLUCIÓN'),
        (236, 'LI865', 'INCIDENCIA'),
        (237, 'LI881', 'INCIDENCIA'),
        (238, 'LI399', 'EN TRÁNSITO'),
        (239, 'LI752', 'APORTAR SOLUCIÓN'),
        (240, 'LI549', 'EN TRÁNSITO'),
        (241, 'LI571', 'DEVOLUCIÓN EN CURSO'),
        (242, 'LI572', 'DEVOLUCIÓN EN CURSO'),
        (243, 'LI574', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (244, 'LI580', 'EN TRÁNSITO'),
        (245, 'LI559', 'APORTAR SOLUCIÓN'),
        (246, 'LI543', 'DEVOLUCIÓN EN CURSO'),
        (247, 'LI558', 'EN TRÁNSITO'),
        (248, 'LI576', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (249, 'LI557', 'EN TRÁNSITO'),
        (250, 'LI473', 'EN TRÁNSITO'),
        (251, 'LI323', 'EN TRÁNSITO'),
        (252, 'LI455', 'EN TRÁNSITO'),
        (253, 'LI430', 'EN TRÁNSITO'),
        (254, 'LI369', 'EN TRÁNSITO'),
        (255, 'LI370', 'EN TRÁNSITO'),
        (256, 'LI380', 'EN TRÁNSITO'),
        (257, 'LI351', 'EN TRÁNSITO'),
        (258, 'LI352', 'EN TRÁNSITO'),
        (259, 'LI353', 'EN TRÁNSITO'),
        (260, 'LI354', 'EN TRÁNSITO'),
        (261, 'LI355', 'EN TRÁNSITO'),
        (262, 'LI356', 'EN TRÁNSITO'),
        (263, 'LI357', 'EN TRÁNSITO'),
        (264, 'LI359', 'EN TRÁNSITO'),
        (265, 'LI371', 'EN TRÁNSITO'),
        (266, 'LI374', 'EN TRÁNSITO'),
        (267, 'LI375', 'EN TRÁNSITO'),
        (268, 'LI377', 'EN TRÁNSITO'),
        (269, 'LI381', 'EN TRÁNSITO'),
        (270, 'LI358', 'EN TRÁNSITO'),
        (271, 'LI361', 'EN TRÁNSITO'),
        (272, 'LI362', 'EN TRÁNSITO'),
        (273, 'LI363', 'EN TRÁNSITO'),
        (274, 'LI365', 'EN TRÁNSITO'),
        (275, 'LI366', 'EN TRÁNSITO'),
        (276, 'LI372', 'EN TRÁNSITO'),
        (277, 'LI373', 'EN TRÁNSITO'),
        (278, 'LI376', 'EN TRÁNSITO'),
        (279, 'LI378', 'EN TRÁNSITO'),
        (280, 'LI379', 'EN TRÁNSITO'),
        (281, 'LI382', 'EN TRÁNSITO'),
        (282, 'LI383', 'EN TRÁNSITO'),
        (283, 'LI384', 'EN TRÁNSITO'),
        (284, 'LI577', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (285, 'LI578', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (286, 'LI573', 'INCIDENCIA'),
        (287, 'LJ100', 'EN TRÁNSITO'),
        (288, 'LJ105', 'EN TRÁNSITO'),
        (289, 'LJ524', 'EN TRÁNSITO'),
        (290, 'LJ833', 'DEVOLUCIÓN EN CURSO'),
        (291, 'LJ303', 'DOCUMENTACIÓN RECTIFICADA'),
        (292, 'LK552', 'DOCUMENTACIÓN RECTIFICADA'),
        (293, 'LK553', 'DOCUMENTACIÓN RECTIFICADA'),
        (294, 'LK554', 'DOCUMENTACIÓN RECTIFICADA'),
        (295, 'LK555', 'DOCUMENTACIÓN RECTIFICADA'),
        (296, 'LK556', 'DOCUMENTACIÓN RECTIFICADA'),
        (297, 'LK560', 'INCIDENCIA'),
        (298, 'LK573', 'INCIDENCIA'),
        (299, 'LK583', 'DOCUMENTACIÓN RECTIFICADA'),
        (300, 'LK510', 'DOCUMENTACIÓN RECTIFICADA'),
        (301, 'LK511', 'DOCUMENTACIÓN RECTIFICADA'),
        (302, 'LK512', 'DOCUMENTACIÓN RECTIFICADA'),
        (303, 'LK513', 'DOCUMENTACIÓN RECTIFICADA'),
        (304, 'LK514', 'DOCUMENTACIÓN RECTIFICADA'),
        (305, 'LK515', 'DOCUMENTACIÓN RECTIFICADA'),
        (306, 'LK516', 'DOCUMENTACIÓN RECTIFICADA'),
        (307, 'LK517', 'DOCUMENTACIÓN RECTIFICADA'),
        (308, 'LK521', 'INCIDENCIA'),
        (309, 'LK522', 'INCIDENCIA'),
        (310, 'LK531', 'DOCUMENTACIÓN RECTIFICADA'),
        (311, 'LK530', 'DOCUMENTACIÓN RECTIFICADA'),
        (312, 'LK532', 'DOCUMENTACIÓN RECTIFICADA'),
        (313, 'LK545', 'DOCUMENTACIÓN RECTIFICADA'),
        (314, 'LK523', 'INCIDENCIA'),
        (315, 'LK536', 'DOCUMENTACIÓN RECTIFICADA'),
        (316, 'LK524', 'DOCUMENTACIÓN RECTIFICADA'),
        (317, 'LK537', 'DOCUMENTACIÓN RECTIFICADA'),
        (318, 'LK533', 'DOCUMENTACIÓN RECTIFICADA'),
        (319, 'LK551', 'DOCUMENTACIÓN RECTIFICADA'),
        (320, 'SK610', 'DOCUMENTACIÓN RECTIFICADA'),
        (321, 'SK611', 'DOCUMENTACIÓN RECTIFICADA'),
        (322, 'SK612', 'DOCUMENTACIÓN RECTIFICADA'),
        (323, 'SK613', 'DOCUMENTACIÓN RECTIFICADA'),
        (324, 'SK614', 'DOCUMENTACIÓN RECTIFICADA'),
        (325, 'SK615', 'DOCUMENTACIÓN RECTIFICADA'),
        (326, 'SK616', 'DOCUMENTACIÓN RECTIFICADA'),
        (327, 'SK622', 'INCIDENCIA'),
        (328, 'SK623', 'INCIDENCIA'),
        (329, 'SK636', 'DOCUMENTACIÓN RECTIFICADA'),
        (330, 'SK637', 'DOCUMENTACIÓN RECTIFICADA'),
        (331, 'SK651', 'DOCUMENTACIÓN RECTIFICADA'),
        (332, 'SK652', 'DOCUMENTACIÓN RECTIFICADA'),
        (333, 'SK653', 'DOCUMENTACIÓN RECTIFICADA'),
        (334, 'SK660', 'INCIDENCIA'),
        (335, 'LL001', 'ENTREGADO'),
        (336, 'LL003', 'ENTREGADO'),
        (337, 'LL007', 'ENTREGADO'),
        (338, 'LL060', 'ENTREGADO'),
        (339, 'LL091', 'INCIDENCIA'),
        (340, 'LL300', 'DEVOLUCIÓN EN CURSO'),
        (341, 'LL301', 'DOCUMENTACIÓN RECTIFICADA'),
        (342, 'LL303', 'APORTAR SOLUCIÓN'),
        (343, 'LL872', 'ENTREGADO'),
        (344, 'LL873', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (345, 'LL874', 'INCIDENCIA'),
        (346, 'LL833', 'DEVOLUCIÓN EN CURSO'),
        (347, 'LL010', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (348, 'LL020', 'ENTREGADO'),
        (349, 'LL030', 'DISPONIBLE PARA RECOGER EN TIENDA'),
        (351, 'LM001', 'ENTREGADO'),
        (352, 'LM002', 'ENTREGADO'),
        (353, 'LM003', 'ENTREGADO'),
        (354, 'LM004', 'ENTREGADO'),
        (355, 'LM005', 'ENTREGADO'),
        (356, 'LM006', 'ENTREGADO'),
        (357, 'LM009', 'ENTREGADO'),
        (358, 'LM010', 'ENTREGADO'),
        (359, 'LM011', 'ENTREGADO'),
        (360, 'LM012', 'ENTREGADO'),
        (361, 'LM013', 'ENTREGADO'),
        (362, 'LM014', 'ENTREGADO'),
        (363, 'LM015', 'ENTREGADO'),
        (364, 'LM016', 'ENTREGADO'),
        (365, 'LM017', 'ENTREGADO'),
        (366, 'LM019', 'ENTREGADO'),
        (367, 'LM018', 'ENTREGADO'),
        (368, 'LM020', 'ENTREGADO'),
        (369, 'LM021', 'ENTREGADO'),
        (370, 'LM022', 'ENTREGADO'),
        (371, 'LM060', 'ENTREGADO'),
        (372, 'LM999', 'ENTREGADO'),
        (373, 'LM025', 'ENTREGADO'),
        (374, 'LM024', 'ENTREGADO'),
        (375, 'LM026', 'ENTREGADO'),
        (376, 'LM027', 'ENTREGADO'),
        (377, 'LM023', 'ENTREGADO'),
        (378, 'LO001', 'EN TRÁNSITO'),
        (379, 'LO002', 'INCIDENCIA'),
        (380, 'LR802', 'INCIDENCIA'),
        (381, 'IS322', 'EN TRÁNSITO'),
        (382, 'LS004', 'EN TRÁNSITO'),
        (383, 'LS857', 'EN TRÁNSITO'),
        (384, 'LT859', 'EN TRÁNSITO'),
        (385, 'SW089', 'EN TRÁNSITO'),
        (386, 'SW189', 'EN TRÁNSITO'),
        (387, 'SW999', 'EN TRÁNSITO'),
        (388, 'LX510', 'DOCUMENTACIÓN RECTIFICADA'),
        (389, 'LX511', 'DOCUMENTACIÓN RECTIFICADA'),
        (390, 'LX512', 'DOCUMENTACIÓN RECTIFICADA'),
        (391, 'LX513', 'DOCUMENTACIÓN RECTIFICADA'),
        (392, 'LX515', 'DOCUMENTACIÓN RECTIFICADA'),
        (393, 'LX516', 'DOCUMENTACIÓN RECTIFICADA'),
        (394, 'LX517', 'DOCUMENTACIÓN RECTIFICADA'),
        (395, 'LX524', 'DOCUMENTACIÓN RECTIFICADA'),
        (396, 'LX530', 'DOCUMENTACIÓN RECTIFICADA'),
        (397, 'LX531', 'DOCUMENTACIÓN RECTIFICADA'),
        (398, 'LX532', 'DOCUMENTACIÓN RECTIFICADA'),
        (399, 'LX533', 'DOCUMENTACIÓN RECTIFICADA'),
        (400, 'LX536', 'DOCUMENTACIÓN RECTIFICADA'),
        (401, 'LX537', 'DOCUMENTACIÓN RECTIFICADA'),
        (402, 'LX545', 'DOCUMENTACIÓN RECTIFICADA'),
        (403, 'LX551', 'DOCUMENTACIÓN RECTIFICADA'),
        (404, 'LX552', 'DOCUMENTACIÓN RECTIFICADA'),
        (405, 'LX553', 'DOCUMENTACIÓN RECTIFICADA'),
        (406, 'LX554', 'DOCUMENTACIÓN RECTIFICADA'),
        (407, 'LX555', 'DOCUMENTACIÓN RECTIFICADA'),
        (408, 'LX556', 'DOCUMENTACIÓN RECTIFICADA'),
        (409, 'LX573', 'APORTAR SOLUCIÓN'),
        (410, 'LX583', 'DOCUMENTACIÓN RECTIFICADA'),
        (411, 'SX001', 'EN TRÁNSITO'),
        (412, 'SX010', 'EN TRÁNSITO')";
    dbDelta( $sql );
}

function insertIntoSeurCustomRates() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'seur_custom_rates';

    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => '*',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '60',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '10',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => '*',
            'postcode'  => '*',
            'minprice'  => '60',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '0',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'PT',
            'state'     => '*',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '60',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '10',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'PT',
            'state'     => '*',
            'postcode'  => '*',
            'minprice'  => '60',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '0',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'AD',
            'state'     => '*',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '60',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '10',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'AD',
            'state'     => '*',
            'postcode'  => '*',
            'minprice'  => '60',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '0',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'PM',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '100',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '15',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'PM',
            'postcode'  => '*',
            'minprice'  => '100',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '0',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'GC',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '200',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '35',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'GC',
            'postcode'  => '*',
            'minprice'  => '200',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '0',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'CE',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '300',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '40',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'CE',
            'postcode'  => '*',
            'minprice'  => '300',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '0',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'ML',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '300',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '40',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => 'ES',
            'state'     => 'ML',
            'postcode'  => '*',
            'minprice'  => '300',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'B2C Estándar',
            'rateprice' => '0',
        )
    );
    $wpdb->insert(
        $table_name,
        array(
            'type'      => 'price',
            'country'   => '*',
            'state'     => '*',
            'postcode'  => '*',
            'minprice'  => '0',
            'maxprice'  => '9999999',
            'minweight' => '0',
            'maxweight' => '1000',
            'rate'      => 'Classic Internacional Terrestre',
            'rateprice' => '15',
        )
    );
}
/**
 * Seur Add Date to Table Hook.
 */
function seur_add_data_to_tables_hook() {
	$seur_table_version_saved = get_option( 'seur_table_version' );

	if ( ! $seur_table_version_saved || '' === $seur_table_version_saved ) {
		insertIntoSeurCustomRates();
	}
    if ( $seur_table_version_saved && ($seur_table_version_saved !== '1.0.4') && ( SEUR_TABLE_VERSION === '1.0.4') ) {
        insertIntoSeurStatus();
        updateMetaSeurShippingMethodService();
    }
    update_option( 'seur_table_version', SEUR_TABLE_VERSION );
}


/**
 * Seur Create Random String.
 */
function seur_create_random_string() {

	$characters           = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$string               = '';
	$max                  = strlen( $characters ) - 1;
	$random_string_length = 10;
	for ( $i = 0; $i < $random_string_length; $i++ ) {
		$string .= $characters[ wp_rand( 0, $max ) ];
	}
	return $string;
}

/**
 * Seur Create upload flder Hook.
 */
function seur_create_upload_folder_hook() {

	$seur_upload_dir    = get_option( 'seur_uploads_dir' );
	$seur_download_file = get_site_option( 'seur_download_file_path' );

	if ( $seur_download_file ) {
		wp_delete_file( $seur_download_file );
		delete_site_option( 'seur_download_file_path' );
	}

	if ( $seur_upload_dir && file_exists( $seur_upload_dir ) ) {
		return;
	} else {
		$upload_dir               = wp_upload_dir();
		$random_string            = seur_create_random_string();
		$seur_uploads_name_prefix = 'seur_uploads_';
		$seur_uploads_name        = $seur_uploads_name_prefix . $random_string;
		$seur_upload_dir          = $upload_dir['basedir'] . '/' . $seur_uploads_name;
		$seur_upload_dir_labels   = $upload_dir['basedir'] . '/' . $seur_uploads_name . '/labels';
		$seur_upload_dir_manifest = $upload_dir['basedir'] . '/' . $seur_uploads_name . '/manifests';
		$seur_upload_url          = $upload_dir['baseurl'] . '/' . $seur_uploads_name;
		$seur_upload_url_labels   = $upload_dir['baseurl'] . '/' . $seur_uploads_name . '/labels';
		$seur_upload_url_manifest = $upload_dir['baseurl'] . '/' . $seur_uploads_name . '/manifests';

		if ( ! file_exists( $seur_upload_dir ) ) {
			wp_mkdir_p( $seur_upload_dir );
			wp_mkdir_p( $seur_upload_dir_labels );
			wp_mkdir_p( $seur_upload_dir_manifest );
			update_option( 'seur_uploads_dir', $seur_upload_dir );
			update_option( 'seur_uploads_url', $seur_upload_url );
			update_option( 'seur_uploads_dir_labels', $seur_upload_dir_labels );
			update_option( 'seur_uploads_dir_manifest', $seur_upload_dir_manifest );
			update_option( 'seur_uploads_url_labels', $seur_upload_url_labels );
			update_option( 'seur_uploads_url_manifest', $seur_upload_url_manifest );
		}
	}
}

/**
 * Seur add avanced settings preset.
 */
function seur_add_avanced_settings_preset() {

	$seur_add = get_option( 'seur_add_advanced_settings_field_pre' );

	if ('2' === $seur_add ) {
		update_option( 'seur_add_advanced_settings_field_pre', '3' );
	}
	if ( '1' === $seur_add ) {
		update_option( 'seur_add_advanced_settings_field_pre', '2' );
	}
	if ( ! $seur_add ) {
		update_option( 'seur_after_get_label_field', 'shipping' );
		update_option( 'seur_preaviso_notificar_field', null );
		update_option( 'seur_reparto_notificar_field', null );
		update_option( 'seur_tipo_notificacion_field', 'EMAIL' );
		update_option( 'seur_tipo_etiqueta_field', 'PDF' );
		update_option( 'seur_aduana_origen_field', 'D' );
		update_option( 'seur_aduana_destino_field', 'D' );
		update_option( 'seur_tipo_mercancia_field', 'C' );
		update_option( 'seur_id_mercancia_field', '400' );
		update_option( 'seur_descripcion_field', 'MANUFACTURAS DIVERSAS' );
		update_option( 'seur_add_advanced_settings_field_pre', '1' );
	}

}

