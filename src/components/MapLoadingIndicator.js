/**
 * External dependencies
 */
import { Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Loading indicator component for the map.
 *
 * @return {JSX.Element} The loading spinner component
 */
export const MapLoadingIndicator = () => {
	return (
		<div
			style={ {
				display: 'flex',
				flexDirection: 'column',
				alignItems: 'center',
				justifyContent: 'center',
				padding: '40px',
				gap: '16px',
			} }
		>
			<Spinner />
			<p style={ { margin: 0, color: '#666' } }>
				{ __( 'Cargando puntos de recogida...', 'seur' ) }
			</p>
		</div>
	);
};
