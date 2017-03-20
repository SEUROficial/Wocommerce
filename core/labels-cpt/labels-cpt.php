<?php
// Register Custom Post Type
function seur_cpt_labels() {

	$labels = array(
		'name'                  => _x( 'Labels', 'Post Type General Name', SEUR_TEXTDOMAIN ),
		'singular_name'         => _x( 'Label', 'Post Type Singular Name', SEUR_TEXTDOMAIN ),
		'menu_name'             => __( 'Labels', SEUR_TEXTDOMAIN ),
		'name_admin_bar'        => __( 'Labels', SEUR_TEXTDOMAIN ),
		'archives'              => __( 'Labels', SEUR_TEXTDOMAIN ),
		'attributes'            => __( 'Labels Atributte', SEUR_TEXTDOMAIN ),
		'parent_item_colon'     => __( 'Parent Item:', SEUR_TEXTDOMAIN ),
		'all_items'             => __( 'All Labels', SEUR_TEXTDOMAIN ),
		'add_new_item'          => __( 'Add New Label', SEUR_TEXTDOMAIN ),
		'add_new'               => __( 'Add New', SEUR_TEXTDOMAIN ),
		'new_item'              => __( 'New Label', SEUR_TEXTDOMAIN ),
		'edit_item'             => __( 'Edit Labels', SEUR_TEXTDOMAIN ),
		'update_item'           => __( 'Update Label', SEUR_TEXTDOMAIN ),
		'view_item'             => __( 'View Label', SEUR_TEXTDOMAIN ),
		'view_items'            => __( 'View Labels', SEUR_TEXTDOMAIN ),
		'search_items'          => __( 'Search Label', SEUR_TEXTDOMAIN ),
		'not_found'             => __( 'Not found', SEUR_TEXTDOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', SEUR_TEXTDOMAIN ),
		'featured_image'        => __( 'Featured Image', SEUR_TEXTDOMAIN ),
		'set_featured_image'    => __( 'Set featured image', SEUR_TEXTDOMAIN ),
		'remove_featured_image' => __( 'Remove featured image', SEUR_TEXTDOMAIN ),
		'use_featured_image'    => __( 'Use as featured image', SEUR_TEXTDOMAIN ),
		'insert_into_item'      => __( 'Insert into Label', SEUR_TEXTDOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this Label', SEUR_TEXTDOMAIN ),
		'items_list'            => __( 'Labels list', SEUR_TEXTDOMAIN ),
		'items_list_navigation' => __( 'Labels list navigation', SEUR_TEXTDOMAIN ),
		'filter_items_list'     => __( 'Filter Labels list', SEUR_TEXTDOMAIN ),
	);
	$args = array(
		'label'                 => __( 'Label', SEUR_TEXTDOMAIN ),
		'description'           => __( 'Seur Labels', SEUR_TEXTDOMAIN ),
		'labels'                => $labels,
		'supports'              => false,
		'taxonomies'            => array( 'labels-product' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => false,
		'menu_position'         => 10,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'rewrite'               => false,
		'capability_type'       => 'post',
		'capabilities' => array(
						'create_posts' => false, // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
						),
		'map_meta_cap' => true,
	);
	register_post_type( 'seur_labels', $args );

}
add_action( 'init', 'seur_cpt_labels', 0 );

// Adding columns to Labels List

add_filter( 'manage_seur_labels_posts_columns', 'seur_set_custom_label_columns' );
function seur_set_custom_label_columns( $columns ) {

    unset( $columns['title'] );
    unset( $columns['date'] );
    unset( $columns['taxonomy-labels-product']);
    $columns['title']			  	 = __( 'Label ID', SEUR_TEXTDOMAIN );
    $columns['order_id'] 		  	 = __( 'Order ID', SEUR_TEXTDOMAIN );
    $columns['product'] 		  	 = __( 'Product', SEUR_TEXTDOMAIN );
	$columns['customer_name'] 	  	 = __( 'Customer Name', SEUR_TEXTDOMAIN );
	$columns['customer_comments'] 	 = __( 'Customer Comments', SEUR_TEXTDOMAIN );
	$columns['weight'] 			     = __( 'Weight', SEUR_TEXTDOMAIN );
	$columns['taxonomy-labels-product'] = __( 'Service/Product', SEUR_TEXTDOMAIN );
	$columns['print'] 			  	 = __( 'Print/Download', SEUR_TEXTDOMAIN );
	$columns['date'] 			  	 = __( 'Label Date', SEUR_TEXTDOMAIN );

    return $columns;
}

add_action( 'manage_seur_labels_posts_custom_column' , 'seur_custom_label_column_data', 10, 2 );

function seur_custom_label_column_data( $column, $post_id ) {
	global $woocommerce;

	$seur_shipping_method = get_post_meta( $post_id, '_seur_shipping_method',   				true );
    $weight		   		  = get_post_meta( $post_id, '_seur_shipping_weight',   				true );
    $num_packages 		  = get_post_meta( $post_id, '_seur_shipping_packages',   				true );
    $order_id	   		  = get_post_meta( $post_id, '_seur_shipping_order_id',   				true );
    $customer_name 		  = get_post_meta( $post_id, '_seur_label_customer_name', 				true );
    $order_comments 	  = get_post_meta( $post_id, '_seur_shipping_order_customer_comments',	true );
    $label_file_name 	  = get_post_meta( $post_id, '_seur_shipping_order_label_file_name',    true );
    $label_path 		  = get_post_meta( $post_id, '_seur_shipping_order_label_path_name',    true );
    $label_url 			  = get_post_meta( $post_id, '_seur_shipping_order_label_url_name',   	true );

    $order        = new WC_Order( $order_id );
	$product_list = '';
    $order_item   = $order->get_items();
    foreach( $order_item as $product ) {
                $product_name[] = '<li>' . $product['name']." x ".$product['qty'] . '</li>';

            }
    $product_list = implode( '', $product_name );


    switch ( $column ) {

       case 'order_id' :
            $link = admin_url( 'post.php?post=' . $order_id . '&action=edit' );
            echo '<a href="' . $link . '" target="_blank">' . $order_id . '</a>';
            break;
        case 'product' :
        	echo '<ul>';
        	echo $product_list;
        	echo '</ul>';
        	break;
        case 'customer_name' :
        	echo $customer_name;
        	break;
        case 'customer_comments' :
        	echo $order_comments;
        	break;

		case 'weight' :
        	echo $weight;
        	break;

		case 'print' :
        	//echo '<a href="' . $label_url . '" onClick="window.print();return false">​​​​​​​​​​​​​​​​​print pdf</a>';

        	echo '<a href="' . $label_url . '" class="button" target="_blank">' . __( ' Open ', SEUR_TEXTDOMAIN ) . '</a>';
        	break;
    }
}

// Register Custom Taxonomy
function seur_add_label_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Products', 'Taxonomy General Name', 'seur-official' ),
		'singular_name'              => _x( 'Product', 'Taxonomy Singular Name', 'seur-official' ),
		'menu_name'                  => __( 'Product', 'seur-official' ),
		'all_items'                  => __( 'All products', 'seur-official' ),
		'parent_item'                => __( 'Parent Item', 'seur-official' ),
		'parent_item_colon'          => __( 'Parent Item:', 'seur-official' ),
		'new_item_name'              => __( 'New Product', 'seur-official' ),
		'add_new_item'               => __( 'Add new product', 'seur-official' ),
		'edit_item'                  => __( 'Edit Product', 'seur-official' ),
		'update_item'                => __( 'Update Product', 'seur-official' ),
		'view_item'                  => __( 'View Product', 'seur-official' ),
		'separate_items_with_commas' => __( 'Separate Products with commas', 'seur-official' ),
		'add_or_remove_items'        => __( 'Add or remove Products', 'seur-official' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'seur-official' ),
		'popular_items'              => __( 'Popular Products', 'seur-official' ),
		'search_items'               => __( 'Search Products', 'seur-official' ),
		'not_found'                  => __( 'Not Found', 'seur-official' ),
		'no_terms'                   => __( 'No Products', 'seur-official' ),
		'items_list'                 => __( 'Product list', 'seur-official' ),
		'items_list_navigation'      => __( 'Products list navigation', 'seur-official' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
	);
	register_taxonomy( 'labels-product', array( 'seur_labels' ), $args );

}
add_action( 'init', 'seur_add_label_taxonomy', 0 );

/***************************************************/
/************* Metabox Labels SEUR *****************/
/***************************************************/

/**
 * Register meta box(es).
 */
function seur_label_register_meta_box() {
    add_meta_box( 'seurmetaboxlabel', __( 'Data Label', SEUR_TEXTDOMAIN ), 'seur_metabox_label_callback', 'seur_labels', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'seur_label_register_meta_box' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function seur_metabox_label_callback( $post ) {


	$weight		   = get_post_meta( $post->ID, '_seur_shipping_weight',   true );
	$order_id	   = get_post_meta( $post->ID, '_order_id',           true );
	$product	   = get_post_meta( $post->ID, '_seur_product',       true );
	$customer_name = get_post_meta( $post->ID, '_seur_customer_name', true );

   // Display code/markup goes here. Don't forget to include nonces!
	?>
	<div id="seur_content_metabox">


			<label><?php _e( 'Packages Weight', SEUR_TEXTDOMAIN); ?></label><br />
			<input title="<?php _e('Weight', SEUR_TEXTDOMAIN ); ?>" type='text' name='weight' class='form-control' placeholder='<?php _e( 'EX: 0.300', SEUR_TEXTDOMAIN ); ?>' value='<?php if ( $weight ) echo $weight; ?>' required=''><br />
			<label><?php _e( 'Number of Packages', SEUR_TEXTDOMAIN); ?></label><br />
			<input title="<?php _e('Number of Packages', SEUR_TEXTDOMAIN ); ?>" type='text' name='number-packages' class='form-control' placeholder='<?php _e( 'EX: 2', SEUR_TEXTDOMAIN ); ?>' required=""><br />
			<input type="button" class="button getlabel" value="<?php _e( 'Get labels', SEUR_TEXTDOMAIN ); ?>">
    </div>
<?php }

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function seur_label_save_meta_box( $post_id ) {

    // Save logic goes here. Don't forget to include nonce checks!
}
add_action( 'save_post', 'seur_label_save_meta_box' );


function seur_label_pdf_register_meta_box() {
    add_meta_box( 'seurmetaboxpdf', __( 'Label', SEUR_TEXTDOMAIN ), 'seur_metabox_pdf_callback', 'seur', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'seur_label_pdf_register_meta_box' );

//add_filter('display_post_states', 'wpsites_custom_post_states');

function wpsites_custom_post_states($states) {
    global $post;
    if( ( 'seur_label' == get_post_type( $post->ID ) ) ){
        $states[] = '__return_false';
    }
}


function seur_metabox_pdf_callback( $post ) { ?>
 <!-- <body tabindex="1" class="loadingInProgress"> -->
    <div id="outerContainer">

      <div id="sidebarContainer">
        <div id="toolbarSidebar">
          <div class="splitToolbarButton toggled">
            <button id="viewThumbnail" class="toolbarButton group toggled" title="Show Thumbnails" tabindex="2" data-l10n-id="thumbs">
               <span data-l10n-id="thumbs_label">Thumbnails</span>
            </button>
            <button id="viewOutline" class="toolbarButton group" title="Show Document Outline" tabindex="3" data-l10n-id="outline">
               <span data-l10n-id="outline_label">Document Outline</span>
            </button>
            <button id="viewAttachments" class="toolbarButton group" title="Show Attachments" tabindex="4" data-l10n-id="attachments">
               <span data-l10n-id="attachments_label">Attachments</span>
            </button>
          </div>
        </div>
        <div id="sidebarContent">
          <div id="thumbnailView">
          </div>
          <div id="outlineView" class="hidden">
          </div>
          <div id="attachmentsView" class="hidden">
          </div>
        </div>
      </div>  <!-- sidebarContainer -->

      <div id="mainContainer">
        <div class="findbar hidden doorHanger hiddenSmallView" id="findbar">
          <label for="findInput" class="toolbarLabel" data-l10n-id="find_label">Find:</label>
          <input id="findInput" class="toolbarField" tabindex="91">
          <div class="splitToolbarButton">
            <button class="toolbarButton findPrevious" title="" id="findPrevious" tabindex="92" data-l10n-id="find_previous">
              <span data-l10n-id="find_previous_label">Previous</span>
            </button>
            <div class="splitToolbarButtonSeparator"></div>
            <button class="toolbarButton findNext" title="" id="findNext" tabindex="93" data-l10n-id="find_next">
              <span data-l10n-id="find_next_label">Next</span>
            </button>
          </div>
          <input type="checkbox" id="findHighlightAll" class="toolbarField" tabindex="94">
          <label for="findHighlightAll" class="toolbarLabel" data-l10n-id="find_highlight">Highlight all</label>
          <input type="checkbox" id="findMatchCase" class="toolbarField" tabindex="95">
          <label for="findMatchCase" class="toolbarLabel" data-l10n-id="find_match_case_label">Match case</label>
          <span id="findResultsCount" class="toolbarLabel hidden"></span>
          <span id="findMsg" class="toolbarLabel"></span>
        </div>  <!-- findbar -->

        <div id="secondaryToolbar" class="secondaryToolbar hidden doorHangerRight">
          <div id="secondaryToolbarButtonContainer">
            <button id="secondaryPresentationMode" class="secondaryToolbarButton presentationMode visibleLargeView" title="Switch to Presentation Mode" tabindex="51" data-l10n-id="presentation_mode">
              <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
            </button>

            <button id="secondaryOpenFile" class="secondaryToolbarButton openFile visibleLargeView" title="Open File" tabindex="52" data-l10n-id="open_file">
              <span data-l10n-id="open_file_label">Open</span>
            </button>

            <button id="secondaryPrint" class="secondaryToolbarButton print visibleMediumView" title="Print" tabindex="53" data-l10n-id="print">
              <span data-l10n-id="print_label">Print</span>
            </button>

            <button id="secondaryDownload" class="secondaryToolbarButton download visibleMediumView" title="Download" tabindex="54" data-l10n-id="download">
              <span data-l10n-id="download_label">Download</span>
            </button>

            <a href="#" id="secondaryViewBookmark" class="secondaryToolbarButton bookmark visibleSmallView" title="Current view (copy or open in new window)" tabindex="55" data-l10n-id="bookmark">
              <span data-l10n-id="bookmark_label">Current View</span>
            </a>

            <div class="horizontalToolbarSeparator visibleLargeView"></div>

            <button id="firstPage" class="secondaryToolbarButton firstPage" title="Go to First Page" tabindex="56" data-l10n-id="first_page">
              <span data-l10n-id="first_page_label">Go to First Page</span>
            </button>
            <button id="lastPage" class="secondaryToolbarButton lastPage" title="Go to Last Page" tabindex="57" data-l10n-id="last_page">
              <span data-l10n-id="last_page_label">Go to Last Page</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="pageRotateCw" class="secondaryToolbarButton rotateCw" title="Rotate Clockwise" tabindex="58" data-l10n-id="page_rotate_cw">
              <span data-l10n-id="page_rotate_cw_label">Rotate Clockwise</span>
            </button>
            <button id="pageRotateCcw" class="secondaryToolbarButton rotateCcw" title="Rotate Counterclockwise" tabindex="59" data-l10n-id="page_rotate_ccw">
              <span data-l10n-id="page_rotate_ccw_label">Rotate Counterclockwise</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="toggleHandTool" class="secondaryToolbarButton handTool" title="Enable hand tool" tabindex="60" data-l10n-id="hand_tool_enable">
              <span data-l10n-id="hand_tool_enable_label">Enable hand tool</span>
            </button>

            <div class="horizontalToolbarSeparator"></div>

            <button id="documentProperties" class="secondaryToolbarButton documentProperties" title="Document Properties…" tabindex="61" data-l10n-id="document_properties">
              <span data-l10n-id="document_properties_label">Document Properties…</span>
            </button>
          </div>
        </div>  <!-- secondaryToolbar -->

        <div class="toolbar">
          <div id="toolbarContainer">
            <div id="toolbarViewer">
              <div id="toolbarViewerLeft">
                <button id="sidebarToggle" class="toolbarButton" title="Toggle Sidebar" tabindex="11" data-l10n-id="toggle_sidebar">
                  <span data-l10n-id="toggle_sidebar_label">Toggle Sidebar</span>
                </button>
                <div class="toolbarButtonSpacer"></div>
                <button id="viewFind" class="toolbarButton group hiddenSmallView" title="Find in Document" tabindex="12" data-l10n-id="findbar">
                   <span data-l10n-id="findbar_label">Find</span>
                </button>
                <div class="splitToolbarButton">
                  <button class="toolbarButton pageUp" title="Previous Page" id="previous" tabindex="13" data-l10n-id="previous">
                    <span data-l10n-id="previous_label">Previous</span>
                  </button>
                  <div class="splitToolbarButtonSeparator"></div>
                  <button class="toolbarButton pageDown" title="Next Page" id="next" tabindex="14" data-l10n-id="next">
                    <span data-l10n-id="next_label">Next</span>
                  </button>
                </div>
                <label id="pageNumberLabel" class="toolbarLabel" for="pageNumber" data-l10n-id="page_label">Page: </label>
                <input type="number" id="pageNumber" class="toolbarField pageNumber" value="1" size="4" min="1" tabindex="15">
                <span id="numPages" class="toolbarLabel"></span>
              </div>
              <div id="toolbarViewerRight">
                <button id="presentationMode" class="toolbarButton presentationMode hiddenLargeView" title="Switch to Presentation Mode" tabindex="31" data-l10n-id="presentation_mode">
                  <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
                </button>

                <button id="openFile" class="toolbarButton openFile hiddenLargeView" title="Open File" tabindex="32" data-l10n-id="open_file">
                  <span data-l10n-id="open_file_label">Open</span>
                </button>

                <button id="print" class="toolbarButton print hiddenMediumView" title="Print" tabindex="33" data-l10n-id="print">
                  <span data-l10n-id="print_label">Print</span>
                </button>

                <button id="download" class="toolbarButton download hiddenMediumView" title="Download" tabindex="34" data-l10n-id="download">
                  <span data-l10n-id="download_label">Download</span>
                </button>
                <a href="#" id="viewBookmark" class="toolbarButton bookmark hiddenSmallView" title="Current view (copy or open in new window)" tabindex="35" data-l10n-id="bookmark">
                  <span data-l10n-id="bookmark_label">Current View</span>
                </a>

                <div class="verticalToolbarSeparator hiddenSmallView"></div>

                <button id="secondaryToolbarToggle" class="toolbarButton" title="Tools" tabindex="36" data-l10n-id="tools">
                  <span data-l10n-id="tools_label">Tools</span>
                </button>
              </div>
              <div class="outerCenter">
                <div class="innerCenter" id="toolbarViewerMiddle">
                  <div class="splitToolbarButton">
                    <button id="zoomOut" class="toolbarButton zoomOut" title="Zoom Out" tabindex="21" data-l10n-id="zoom_out">
                      <span data-l10n-id="zoom_out_label">Zoom Out</span>
                    </button>
                    <div class="splitToolbarButtonSeparator"></div>
                    <button id="zoomIn" class="toolbarButton zoomIn" title="Zoom In" tabindex="22" data-l10n-id="zoom_in">
                      <span data-l10n-id="zoom_in_label">Zoom In</span>
                     </button>
                  </div>
                  <span id="scaleSelectContainer" class="dropdownToolbarButton">
                     <select id="scaleSelect" title="Zoom" tabindex="23" data-l10n-id="zoom">
                      <option id="pageAutoOption" title="" value="auto" selected="selected" data-l10n-id="page_scale_auto">Automatic Zoom</option>
                      <option id="pageActualOption" title="" value="page-actual" data-l10n-id="page_scale_actual">Actual Size</option>
                      <option id="pageFitOption" title="" value="page-fit" data-l10n-id="page_scale_fit">Fit Page</option>
                      <option id="pageWidthOption" title="" value="page-width" data-l10n-id="page_scale_width">Full Width</option>
                      <option id="customScaleOption" title="" value="custom" hidden="true"></option>
                      <option title="" value="0.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 50 }'>50%</option>
                      <option title="" value="0.75" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 75 }'>75%</option>
                      <option title="" value="1" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 100 }'>100%</option>
                      <option title="" value="1.25" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 125 }'>125%</option>
                      <option title="" value="1.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 150 }'>150%</option>
                      <option title="" value="2" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 200 }'>200%</option>
                      <option title="" value="3" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 300 }'>300%</option>
                      <option title="" value="4" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 400 }'>400%</option>
                    </select>
                  </span>
                </div>
              </div>
            </div>
            <div id="loadingBar">
              <div class="progress">
                <div class="glimmer">
                </div>
              </div>
            </div>
          </div>
        </div>

        <menu type="context" id="viewerContextMenu">
          <menuitem id="contextFirstPage" label="First Page"
                    data-l10n-id="first_page"></menuitem>
          <menuitem id="contextLastPage" label="Last Page"
                    data-l10n-id="last_page"></menuitem>
          <menuitem id="contextPageRotateCw" label="Rotate Clockwise"
                    data-l10n-id="page_rotate_cw"></menuitem>
          <menuitem id="contextPageRotateCcw" label="Rotate Counter-Clockwise"
                    data-l10n-id="page_rotate_ccw"></menuitem>
        </menu>

        <div id="viewerContainer" tabindex="0">
          <div id="viewer" class="pdfViewer"></div>
        </div>

        <div id="errorWrapper" hidden='true'>
          <div id="errorMessageLeft">
            <span id="errorMessage"></span>
            <button id="errorShowMore" data-l10n-id="error_more_info">
              More Information
            </button>
            <button id="errorShowLess" data-l10n-id="error_less_info" hidden='true'>
              Less Information
            </button>
          </div>
          <div id="errorMessageRight">
            <button id="errorClose" data-l10n-id="error_close">
              Close
            </button>
          </div>
          <div class="clearBoth"></div>
          <textarea id="errorMoreInfo" hidden='true' readonly="readonly"></textarea>
        </div>
      </div> <!-- mainContainer -->

      <div id="overlayContainer" class="hidden">
        <div id="passwordOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <p id="passwordText" data-l10n-id="password_label">Enter the password to open this PDF file:</p>
            </div>
            <div class="row">
              <!-- The type="password" attribute is set via script, to prevent warnings in Firefox for all http:// documents. -->
              <input id="password" class="toolbarField">
            </div>
            <div class="buttonRow">
              <button id="passwordCancel" class="overlayButton"><span data-l10n-id="password_cancel">Cancel</span></button>
              <button id="passwordSubmit" class="overlayButton"><span data-l10n-id="password_ok">OK</span></button>
            </div>
          </div>
        </div>
        <div id="documentPropertiesOverlay" class="container hidden">
          <div class="dialog">
            <div class="row">
              <span data-l10n-id="document_properties_file_name">File name:</span> <p id="fileNameField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_file_size">File size:</span> <p id="fileSizeField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_title">Title:</span> <p id="titleField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_author">Author:</span> <p id="authorField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_subject">Subject:</span> <p id="subjectField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_keywords">Keywords:</span> <p id="keywordsField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_creation_date">Creation Date:</span> <p id="creationDateField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_modification_date">Modification Date:</span> <p id="modificationDateField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_creator">Creator:</span> <p id="creatorField">-</p>
            </div>
            <div class="separator"></div>
            <div class="row">
              <span data-l10n-id="document_properties_producer">PDF Producer:</span> <p id="producerField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_version">PDF Version:</span> <p id="versionField">-</p>
            </div>
            <div class="row">
              <span data-l10n-id="document_properties_page_count">Page Count:</span> <p id="pageCountField">-</p>
            </div>
            <div class="buttonRow">
              <button id="documentPropertiesClose" class="overlayButton"><span data-l10n-id="document_properties_close">Close</span></button>
            </div>
          </div>
        </div>
      </div>  <!-- overlayContainer -->

    </div> <!-- outerContainer -->
    <div id="printContainer"></div>
<div id="mozPrintCallback-shim" hidden>
  <style>
@media print {
  #printContainer div {
    page-break-after: always;
    page-break-inside: avoid;
  }
}
  </style>
  <style scoped>
#mozPrintCallback-shim {
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  z-index: 9999999;

  display: block;
  text-align: center;
  background-color: rgba(0, 0, 0, 0.5);
}
#mozPrintCallback-shim[hidden] {
  display: none;
}
@media print {
  #mozPrintCallback-shim {
    display: none;
  }
}

#mozPrintCallback-shim .mozPrintCallback-dialog-box {
  display: inline-block;
  margin: -50px auto 0;
  position: relative;
  top: 45%;
  left: 0;
  min-width: 220px;
  max-width: 400px;

  padding: 9px;

  border: 1px solid hsla(0, 0%, 0%, .5);
  border-radius: 2px;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);

  background-color: #474747;

  color: hsl(0, 0%, 85%);
  font-size: 16px;
  line-height: 20px;
}
#mozPrintCallback-shim .progress-row {
  clear: both;
  padding: 1em 0;
}
#mozPrintCallback-shim progress {
  width: 100%;
}
#mozPrintCallback-shim .relative-progress {
  clear: both;
  float: right;
}
#mozPrintCallback-shim .progress-actions {
  clear: both;
}
  </style>
  <div class="mozPrintCallback-dialog-box">
    <!-- TODO: Localize the following strings -->
    Preparing document for printing...
    <div class="progress-row">
      <progress value="0" max="100"></progress>
      <span class="relative-progress">0%</span>
    </div>
    <div class="progress-actions">
      <input type="button" value="Cancel" class="mozPrintCallback-cancel">
    </div>
  </div>
</div>

 <!-- </body> -->
 <?php }
