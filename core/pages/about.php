<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	function seur_about_page(){ ?>
		<div class="wrap about-wrap">
			<h1><?php  printf( __( 'Welcome to SEUR %s' ), SEUR_OFFICIAL_VERSION ); ?></h1>
			<div class="about-text"><?php  printf( __( 'Thank you for install Seur %s!', 'seur'  ), SEUR_OFFICIAL_VERSION ); ?></div>
			<div class="seur-badge"><?php  printf( __( 'Version %s' ), SEUR_OFFICIAL_VERSION ); ?></div>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur_about_page' ), 'admin.php' ) ) ); ?>" class="nav-tab nav-tab-active"><?php  _e( 'SEUR', 'seur' ); ?></a>
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur_about_page' ), 'admin.php' ) ) ); ?>" class="nav-tab"><?php  _e( 'What&#8217;s New SEUR Plugin', 'seur' ); ?></a>
			</h2>
			<p class="about-description"><?php  _e( 'SEUR Shipping Method.', 'seur' ); ?></p>
			<p><?php _e( 'Did you know that 52% of on-line shoppers consider delivery to be a key element when choosing between one e-commerce and another? At SEUR, we want to contribute to ensuring that the clients choose you. To do so, we&apos;ve designed new services and solutions that make the customer the focal point, thereby guaranteeing the best experience during the delivery process', 'seur' ); ?></p>

<center><iframe width="560" height="315" src="https://www.youtube.com/embed/0qiLsRVt8MY" frameborder="0" allowfullscreen></iframe></center>

<p><?php _e( 'And what does SEUR have that others don&apos;t? In-depth knowledge of the e-commerce sector, where we are leaders with a market share of over 30%. Your customer is ours as well, and we know what they want: to be the owner of their time, to have control over their deliveries and to have a personalised experience. That&apos;s why we&apos;ve developed a series of innovative and simple solutions to facilitate and provide a more flexible package shipping and delivery process.', 'seur' ); ?></p>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur' ), 'admin.php' ) ) ); ?>"><?php  _e( 'Go to SEUR Settings', 'seur' ); ?></a>
			</div>
		</div>
		<?php
}
