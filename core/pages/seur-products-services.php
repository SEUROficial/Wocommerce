<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function seur_products_services($post) {
global $wpdb;
?>
<div class="wrap">
    <h2><?php echo __( 'Products / Services', 'seur-oficial' ) ?></h2>

    <h2 class="screen-reader-text"><?php _e( 'Products / Services List', 'seur-oficial' ); ?></h2>
    <p><?php _e( 'Estos servicios y productos pueden no estar disponibles en su oferta comercial. Por favor, verifique con su contacto comercial de SEUR que tiene todas las combinaciones habilitadas.', 'seur-oficial' ); ?></p>
    <p><?php _e( 'Listado de las combinaciones de Servicios y Productos de SEUR, disponibles en el plug-in.', 'seur-oficial' ); ?></p>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'ID', 'seur-oficial' ); ?></th>

                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'Service', 'seur-oficial' ); ?></th>

                <th scope="col" id="comment" class="manage-column column-author"><?php _e( 'Product', 'seur-oficial' ); ?></th>

                <th scope="col" id="response" class="manage-column column-author"><?php _e( 'Description', 'seur-oficial' ); ?></th>
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
                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'ID', 'seur-oficial' ); ?></th>

                <th scope="col" id="author" class="manage-column column-author column-primary"><?php _e( 'Service', 'seur-oficial' ); ?></th>

                <th scope="col" id="comment" class="manage-column column-author"><?php _e( 'Product', 'seur-oficial' ); ?></th>

                <th scope="col" id="response" class="manage-column column-author"><?php _e( 'Description', 'seur-oficial' ); ?></th>
            </tr>
        </tfoot>
    </table>
</div><?php
     //return;
    }
?>