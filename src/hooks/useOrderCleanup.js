/**
 * External dependencies
 */
import { useEffect } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { cleanupPickupData } from '../utils/pickupDataHelpers';

/**
 * Custom hook to cleanup pickup data when order is completed or component unmounts.
 * This ensures data is cleaned from localStorage when:
 * - User completes checkout
 * - User navigates away from checkout
 * - Component is unmounted
 */
export const useOrderCleanup = () => {
	useEffect( () => {
		// Cleanup function that runs when component unmounts
		return () => {
			// Check if we're navigating to order-received page
			const currentPath = window.location.pathname;
			const searchParams = new URLSearchParams( window.location.search );
			
			// If we're on the thank you page (order-received), clean up
			if ( 
				currentPath.includes( 'order-received' ) || 
				searchParams.has( 'order-received' ) ||
				currentPath.includes( 'checkout/order-received' )
			) {
				cleanupPickupData();
			}
		};
	}, [] );
};
