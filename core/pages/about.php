<?php
/**
 * SEUR About.
 *
 * @package SEUR.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * SEUR About.
 */
function seur_about_page() { ?>
	<div class="wrap about-wrap">
		<h1>
			<?php
			// translators: SEUR Version.
			printf( esc_html__( 'Welcome to SEUR %s' ), esc_html( SEUR_OFFICIAL_VERSION ) );
			?>
		</h1>
		<div class="about-text">
			<?php
			// translators: SEUR Version.
			printf( esc_html__( 'Thank you for install Seur %s!', 'seur' ), esc_html( SEUR_OFFICIAL_VERSION ) );
			?>
		</div>
		<div class="seur-badge">
			<?php
			// translators: Seur version.
			printf( esc_html__( 'Version %s' ), esc_html( SEUR_OFFICIAL_VERSION ) );
			?>
		</div>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur_about_page' ), 'admin.php' ) ) ); ?>" class="nav-tab nav-tab-active"><?php esc_html_e( 'SEUR', 'seur' ); ?></a>
		</h2>
		<p class="about-description"><?php esc_html_e( 'SEUR Shipping Method.', 'seur' ); ?></p>
		<p><?php esc_html_e( 'Did you know that 52% of on-line shoppers consider delivery to be a key element when choosing between one e-commerce and another? At SEUR, we want to contribute to ensuring that the clients choose you. To do so, we&apos;ve designed new services and solutions that make the customer the focal point, thereby guaranteeing the best experience during the delivery process', 'seur' ); ?></p>
		<center><iframe width="560" height="315" src="https://www.youtube.com/embed/0qiLsRVt8MY" frameborder="0" allowfullscreen></iframe></center>
		<p><?php esc_html_e( 'And what does SEUR have that others don&apos;t? In-depth knowledge of the e-commerce sector, where we are leaders with a market share of over 30%. Your customer is ours as well, and we know what they want: to be the owner of their time, to have control over their deliveries and to have a personalised experience. That&apos;s why we&apos;ve developed a series of innovative and simple solutions to facilitate and provide a more flexible package shipping and delivery process.', 'seur' ); ?></p>
		<div class="return-to-dashboard">
			<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur' ), 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Go to SEUR Settings', 'seur' ); ?></a>
		</div>
	</div>
	<?php
}
