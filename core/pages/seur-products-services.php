<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_products_services($post) {
global $wpdb;
?>
<div class="wrap">
    <h2><?php echo __( 'Products / Services', 'seur' ) ?></h2>

    <h2 class="screen-reader-text"><?php _e( 'Products / Services List', 'seur' ); ?></h2>
    <p><?php _e( 'These services and products might not be available in your commercial proposal. Please verify with your SEUR sales contact that you have all combinations enabled.', 'seur' ); ?></p>
    <p><?php _e( 'List of combinations of SEUR Services and Products available in the plugin.', 'seur' ); ?></p>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'ID', 'seur' ); ?></th>

                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'Service', 'seur' ); ?></th>

                <th scope="col" id="comment" class="manage-column column-author"><?php _e( 'Product', 'seur' ); ?></th>

                <th scope="col" id="response" class="manage-column column-author"><?php _e( 'Description', 'seur' ); ?></th>
            </tr>

        </thead>
        <tbody id="the-list">
	        <?php

                    $tabla 	   = $wpdb->prefix . SEUR_PLUGIN_SVPR;
                    $sql 	   = "SELECT * FROM $tabla";
                    $registros = $wpdb->get_results($sql);

                    foreach ($registros as $valor)
                    {
	                echo '<tr id="post-2" class="iedit author-self level-0 post-2 type-page status-publish hentry">';
					echo '<td class="author column-author" data-colname="ID">' . $valor->ID . '</td>';
					echo '<td class="author column-author" data-colname="Service">' . $valor->ser . '</td>';
					echo '<td class="author column-author" data-colname="Product">' . $valor->pro . '</td>';
					echo '<td class="author column-author" data-colname="Description">' . $valor->descripcion . '</td>';
					echo '</tr>';
                    }


                    ?>
        </tbody>
         <tfoot>
            <tr>
                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'ID', 'seur' ); ?></th>

                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'Service', 'seur' ); ?></th>

                <th scope="col" id="comment" class="manage-column column-author"><?php _e( 'Product', 'seur' ); ?></th>

                <th scope="col" id="response" class="manage-column column-author"><?php _e( 'Description', 'seur' ); ?></th>
            </tr>
        </tfoot>
    </table>
</div><?php
     //return;
    }
?>