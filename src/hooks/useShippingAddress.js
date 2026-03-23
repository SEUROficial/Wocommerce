/**
 * External dependencies
 */
import { useSelect } from '@wordpress/data';

/**
 * Custom hook to get the current shipping address from checkout.
 *
 * @return {Object} Object with country, city, and postcode
 */
export const useShippingAddress = () => {
	return useSelect( ( select ) => {
		const cartStore = select( 'wc/store/cart' );
		
		if ( ! cartStore || typeof cartStore.getCustomerData !== 'function' ) {
			return { country: '', city: '', postcode: '' };
		}

		const customerData = cartStore.getCustomerData() || {};
		const billingAddress = customerData.billingAddress || {};
		const shippingAddress = customerData.shippingAddress || {};

		let useShippingAsBilling = true;
		try {
			const checkoutStore = select( 'wc/store/checkout' );
			useShippingAsBilling = checkoutStore?.getUseShippingAsBilling?.() ?? true;
		} catch ( e ) {
			// Fallback to shipping address
		}

		const addr = useShippingAsBilling ? shippingAddress : billingAddress;

		return {
			country: addr.country || '',
			city: addr.city || '',
			postcode: addr.postcode || '',
		};
	}, [] );
};
