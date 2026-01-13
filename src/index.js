/**
 * External dependencies
 */
import { registerPlugin } from '@wordpress/plugins';
import { ExperimentalOrderShippingPackages } from '@woocommerce/blocks-checkout';

/**
 * Internal dependencies
 */
import { SeurMapConditionalDisplay } from './components/SeurMapConditionalDisplay';

/**
 * Main render function for the SEUR checkout extension.
 *
 * @param {Object} props Plugin props
 * @return {JSX.Element} The rendered component
 */
const render = ( props ) => {
	return (
		<ExperimentalOrderShippingPackages>
			<SeurMapConditionalDisplay { ...props } />
		</ExperimentalOrderShippingPackages>
	);
};

registerPlugin( 'seur-map-checkout-extension', {
	render,
	scope: 'woocommerce-checkout',
} );