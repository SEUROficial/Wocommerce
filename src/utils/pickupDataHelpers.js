/**
 * Cleans up pickup data from localStorage.
 * Should be called when address changes or checkout is completed.
 */
export const cleanupPickupData = () => {
	localStorage.removeItem( 'seur_pickup_data' );
};

/**
 * Retrieves pickup data from localStorage.
 *
 * @return {Object|null} The stored pickup data or null if not found
 */
export const getPickupDataFromStorage = () => {
	try {
		const data = localStorage.getItem( 'seur_pickup_data' );
		return data ? JSON.parse( data ) : null;
	} catch ( error ) {
		console.error( 'Error parsing pickup data from localStorage:', error );
		return null;
	}
};
