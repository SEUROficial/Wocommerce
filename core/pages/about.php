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
			<p class="about-description"><?php  _e( 'SEUR Shipping Method.', 'wangguard' ); ?></p>
			<p>¿Sabías que el 52% de los compradores online consideran el envío un elemento clave a la hora de elegir un e-commerce u otro?
En SEUR queremos contribuir a que te elijan a ti y, para lograrlo, hemos diseñado nuevos servicios y soluciones que sitúan al cliente en el centro y garantizan la mejor experiencia durante el proceso de entrega.</p>

<center><iframe width="560" height="315" src="https://www.youtube.com/embed/0qiLsRVt8MY" frameborder="0" allowfullscreen></iframe></center>

<p>¿Y qué tiene SEUR que no tiene el resto? Un profundo conocimiento del sector del comercio electrónico, donde somos líderes con una cuota de mercado por encima del 30%. Tu cliente, también es el nuestro y sabemos qué quiere: ser dueño de su tiempo, tener el control de sus envíos y vivir una experiencia personalizada, por eso hemos desarrollado una serie de soluciones innovadoras y sencillas, que facilitan y flexibilizan el proceso de envío y entrega de paquetes.</p>

			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'seur' ), 'admin.php' ) ) ); ?>"><?php  _e( 'Go to SEUR Settings', 'seur' ); ?></a>
			</div>
		</div>
		<?php
}
