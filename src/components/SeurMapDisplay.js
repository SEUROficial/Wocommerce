/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useRef, useState } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { useMapInitialization } from '../hooks/useMapInitialization';
import { useSeurPickupData } from '../hooks/useSeurPickupData';
import { MapLoadingIndicator } from './MapLoadingIndicator';

/**
 * Component that displays the SEUR pickup point map and dropdown selector.
 *
 * @param {Object} props Component props
 * @return {JSX.Element} The map display component
 */
export const SeurMapDisplay = ( props ) => {
	const mapContainerRef = useRef( null );
	const dropdownRef = useRef( null );
	const maPlaceRef = useRef( null );
	const [ selectedPickupIndex, setSelectedPickupIndex ] = useState( null );
	const [ isLoading, setIsLoading ] = useState( false );

	const { updateSeurExtensionData } = useSeurPickupData();

	useMapInitialization( {
		mapContainerRef,
		dropdownRef,
		maPlaceRef,
		selectedPickupIndex,
		setSelectedPickupIndex,
		updateSeurExtensionData,
		setIsLoading,
	} );

	return (
		<div
			style={ {
				border: '3px solid #007cba',
				padding: '25px',
				margin: '20px 0',
				backgroundColor: '#f0f8ff',
				borderRadius: '8px',
				textAlign: 'center',
			} }
		>
			<h3>{ __( '🌐 Punto SEUR', 'seur' ) }</h3>
			<p>
				{ __(
					'Selecciona una ubicación de recogida en el mapa.',
					'seur'
				) }
			</p>

			<div style={ { position: 'relative' } }>
				{ isLoading && (
					<div
						style={ {
							position: 'absolute',
							top: 0,
							left: 0,
							right: 0,
							bottom: 0,
							backgroundColor: '#f0f8ff',
							zIndex: 10,
							display: 'flex',
							alignItems: 'center',
							justifyContent: 'center',
						} }
					>
						<MapLoadingIndicator />
					</div>
				) }

				<div id="controls"></div>

				<div
					id="seur-gmap"
					ref={ mapContainerRef }
					style={ { width: '100%', height: '250px', marginTop: '10px' } }
				/>
			</div>
		</div>
	);
};
