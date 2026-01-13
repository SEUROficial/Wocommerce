/**
 * External dependencies
 */
import { useSelect } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { SeurMapDisplay } from './SeurMapDisplay';
import { useOrderCleanup } from '../hooks/useOrderCleanup';

/**
 * Conditional wrapper that only shows the map if SEUR shipping method is selected.
 *
 * @param {Object} props Component props
 * @return {JSX.Element|null} The map display or null
 */
export const SeurMapConditionalDisplay = ( props ) => {
	// Cleanup hook to clear data when order is completed
	useOrderCleanup();

	const isSeurSelected = useSelect( ( select ) => {
		const cartStore = select( 'wc/store/cart' );

		if ( ! cartStore || typeof cartStore.getShippingRates !== 'function' ) {
			return false;
		}

		const shippingRatesPackages = cartStore.getShippingRates();

		if ( shippingRatesPackages.length === 0 ) {
			return false;
		}

		const rates = shippingRatesPackages[ 0 ].shipping_rates;
		const selectedRate = rates.find( ( rate ) => rate.selected === true );
		const selectedMethodId = selectedRate?.method_id;

		return selectedMethodId === 'seurlocal';
	}, [] );

	if ( ! isSeurSelected ) {
		return null;
	}

	return <SeurMapDisplay { ...props } />;
};
