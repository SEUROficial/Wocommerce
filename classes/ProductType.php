<?php
/**
 * @category SeurTransporte
 * @package SeurTransporte/seur
 * @author Maria Jose Santos <mariajose.santos@ebolution.com>
 * @copyright 2023 Seur Transporte
 * @license https://seur.com/ Proprietary
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
class ProductType
{
    const PRODUCT_TYPE_ATTRIBUTE_CODE = 'seur_product_type';
    const PRODUCT_TYPE_FOOD_MEAT = 'Alimentación/carne';
    const PRODUCT_TYPE_FOOD_FISH = 'Alimentación/pescado';
    const PRODUCT_TYPE_FOOD_DAIRY = 'Alimentación/lácteos';
    const PRODUCT_TYPE_FOOD_PROCESSED = 'Alimentación/preparados';
    const PRODUCT_TYPE_FOOD_OTHER = 'Alimentación/otros';
    const PRODUCT_TYPE_OTHER = 'Otros/no alimentación';

    public function __construct()
    {

    }
    /**
         * @return array
         */
    public static function getValues()
    {
        return [
            self::PRODUCT_TYPE_FOOD_MEAT,
            self::PRODUCT_TYPE_FOOD_FISH,
            self::PRODUCT_TYPE_FOOD_DAIRY,
            self::PRODUCT_TYPE_FOOD_PROCESSED,
            self::PRODUCT_TYPE_FOOD_OTHER,
            self::PRODUCT_TYPE_OTHER
        ];
    }

    public static function install() {
        wc_create_attribute( [
            'name'   => self::PRODUCT_TYPE_ATTRIBUTE_CODE,
            'type'   => 'select'
        ] );

        // Deal with global attributes.
        $term_name = "pa_" . sanitize_title(self::PRODUCT_TYPE_ATTRIBUTE_CODE);
        register_taxonomy(
            $term_name,
            apply_filters( 'woocommerce_taxonomy_objects_' . $term_name, array( 'product' ) ),
            apply_filters( 'woocommerce_taxonomy_args_' . $term_name, array(
                'labels'       => array(
                    'name' => $term_name,
                ),
                'hierarchical' => false,
                'show_ui'      => false,
                'query_var'    => true,
                'rewrite'      => false,
            ) )
        );

        //Clear caches
        delete_transient( 'wc_attribute_taxonomies' );

        $attributeValues = self::getValues();
        foreach ($attributeValues as $value) {
            if (!get_term_by('name', $value, $term_name)) {
                wp_insert_term($value, $term_name);
            }
        }

        update_option( 'seur_table_version', SEUR_TABLE_VERSION );
    }

}