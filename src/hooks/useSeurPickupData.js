/**
 * External dependencies
 */
import { useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

/**
 * Custom hook to manage SEUR pickup point data storage.
 * Uses localStorage for client-side persistence and WooCommerce session for server-side.
 *
 * @return {Object} Object with updateSeurExtensionData function
 */
export const useSeurPickupData = () => {
	/**
	 * Updates SEUR pickup data in localStorage and WooCommerce session.
	 *
	 * @param {Object} seurData The pickup point data to save
	 */
	const updateSeurExtensionData = useCallback( async ( seurData ) => {
		// Save in localStorage for client-side persistence
		localStorage.setItem( 'seur_pickup_data', JSON.stringify( seurData ) );

		// Save in WooCommerce session (server-side)
		try {
			await apiFetch( {
				path: '/seur/v1/save-pickup',
				method: 'POST',
				data: seurData,
			} );
		} catch ( error ) {
			console.error(
				'SEUR: Error guardando datos en sesión:',
				error.message
			);
		}
	}, [] );

	return { updateSeurExtensionData };
};
