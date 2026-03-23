/**
 * External dependencies
 */
import { useEffect, useRef } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { addQueryArgs } from '@wordpress/url';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { useShippingAddress } from './useShippingAddress';
import { createMapControl } from '../utils/mapControl';
import {
	cleanupPickupData,
	getPickupDataFromStorage,
} from '../utils/pickupDataHelpers';

/**
 * Custom hook that handles map initialization and pickup point loading.
 *
 * @param {Object} params Hook parameters
 * @param {Object} params.mapContainerRef Ref to map container element
 * @param {Object} params.dropdownRef Ref to dropdown element
 * @param {Object} params.maPlaceRef Ref to Maplace instance
 * @param {number|null} params.selectedPickupIndex Currently selected pickup index
 * @param {Function} params.setSelectedPickupIndex Function to update selected index
 * @param {Function} params.updateSeurExtensionData Function to save pickup data
 * @param {Function} params.setIsLoading Function to update loading state
 */
export const useMapInitialization = ( {
	mapContainerRef,
	dropdownRef,
	maPlaceRef,
	selectedPickupIndex,
	setSelectedPickupIndex,
	updateSeurExtensionData,
	setIsLoading,
} ) => {
	const { country, city, postcode } = useShippingAddress();
	const previousAddressRef = useRef( null );

	// Main effect: Initialize map when address changes
	useEffect( () => {
		// Check if address has actually changed
		const currentAddress = `${ country }-${ city }-${ postcode }`;
		const addressChanged =
			previousAddressRef.current !== null &&
			previousAddressRef.current !== currentAddress;

		// Only cleanup if address has changed
		if ( addressChanged ) {
			cleanupPickupData();
			setSelectedPickupIndex( null );
		}

		previousAddressRef.current = currentAddress;

		// Wait for complete address
		if ( ! country || ! city || ! postcode ) {
			setIsLoading( false );
			return;
		}

		const hasGmapApi = window.SEUR_HAS_GMAP_API_KEY?.hasGmapApi;

		// Check if we need to wait for Google Maps libraries
		// Only wait if we have API key
		if ( hasGmapApi && ( ! window.google || ! window.Maplace ) ) {
			console.warn(
				__( 'Google Maps o Maplace aún no están disponibles', 'seur' )
			);
			setIsLoading( false );
			return;
		}

		// Start loading
		setIsLoading( true ); // Fetch pickup locations
		const path = addQueryArgs( '/seur/v1/pickups', {
			country,
			city,
			postcode,
			selected: '0',
		} );

		apiFetch( { path } )
			.then( ( response ) => {
				const { locations, optionSelected } = response;

				if ( ! locations || ! locations.length ) {
					console.warn(
						__(
							'Sin puntos SEUR disponibles para esta dirección',
							'seur'
						)
					);
					setIsLoading( false );
					return;
				}

				// Try to recover previous selection from localStorage
				const savedPickupData = getPickupDataFromStorage();
				let initialSelection = optionSelected;

				if ( savedPickupData && savedPickupData.seur_pickup ) {
					initialSelection = savedPickupData.seur_pickup;
				}

				const hasGmapApi = window.SEUR_HAS_GMAP_API_KEY?.hasGmapApi;

				// Only initialize Maplace if Google Maps API is available
				if ( hasGmapApi && window.Maplace ) {
					// Initialize Maplace with custom control
					const maplace = new window.Maplace();
					const control = createMapControl( {
						locations,
						dropdownRef,
						setSelectedPickupIndex,
						updateSeurExtensionData,
					} );

					maplace.AddControl( 'seurdropdown', control );

					maplace.Load( {
						locations,
						map_div: '#seur-gmap',
						start: initialSelection,
						controls_on_map: false,
						controls_type: 'seurdropdown',
						show_infowindows: true,
						infowindow_type: 'bubble',
						afterShow: function ( index ) {
							// Save data when marker is clicked
							const pickupValue = ( index + 1 ).toString();
							const selectedLocation = locations[ index ] || {};

							const seurData = {
								seur_pickup: pickupValue,
								seur_depot: selectedLocation.depot || '',
								seur_postcode: selectedLocation.post_code || '',
								seur_codCentro:
									selectedLocation.codCentro || '',
								seur_title: selectedLocation.title || '',
								seur_type: selectedLocation.type || '',
								seur_address: selectedLocation.address2 || '',
								seur_city: selectedLocation.city_only || '',
								seur_pudo_id: selectedLocation.pudoId || '',
								seur_lat: selectedLocation.lat || '',
								seur_lng: selectedLocation.lng || '',
								seur_streettype:
									selectedLocation.streettype || '',
								seur_numvia: selectedLocation.numvia || '',
								seur_timetable:
									selectedLocation.timetable || '',
							};

							updateSeurExtensionData( seurData );
							setSelectedPickupIndex( index );
						},
					} );

					// Store Maplace instance
					maPlaceRef.current = maplace;

					// Hide map container if no API
					if ( mapContainerRef.current ) {
						mapContainerRef.current.style.display = 'block';
					}
				} else {
					// No Google Maps API - create dropdown manually
					const control = createMapControl( {
						locations,
						dropdownRef,
						setSelectedPickupIndex,
						updateSeurExtensionData,
					} );

					// Manually create the dropdown without Maplace
					const controlsDiv = document.getElementById( 'controls' );
					if ( controlsDiv ) {
						controlsDiv.innerHTML = '';
						const dropdownHtml = control.getHtml.call( {
							ln: locations.length,
							o: {
								locations,
								controls_cssclass: '',
								view_all_text: __( 'Ver todos', 'seur' ),
							},
							view_all_key: 0,
							ShowOnMenu: () => true,
							ViewOnMap: ( index ) => {
								// This is called by Maplace when it exists
								// In manual mode, the change event handles everything
							},
						} );

						if ( dropdownHtml ) {
							controlsDiv.appendChild( dropdownHtml );
						}
					}

					// Hide map container
					if ( mapContainerRef.current ) {
						mapContainerRef.current.style.display = 'none';
					}
				}

				// Set initial selection if available
				if ( initialSelection && initialSelection !== '0' ) {
					const index = parseInt( initialSelection ) - 1;
					const selectedLocation = locations[ index ] || {};
					const pickupValue = initialSelection.toString();
					const seurData = {
						seur_pickup: pickupValue,
						seur_depot: selectedLocation.depot || '',
						seur_postcode: selectedLocation.post_code || '',
						seur_codCentro: selectedLocation.codCentro || '',
						seur_title: selectedLocation.title || '',
						seur_type: selectedLocation.type || '',
						seur_address: selectedLocation.address2 || '',
						seur_city: selectedLocation.city_only || '',
						seur_pudo_id: selectedLocation.pudoId || '',
						seur_lat: selectedLocation.lat || '',
						seur_lng: selectedLocation.lng || '',
						seur_streettype: selectedLocation.streettype || '',
						seur_numvia: selectedLocation.numvia || '',
						seur_timetable: selectedLocation.timetable || '',
					};

					updateSeurExtensionData( seurData );
					setSelectedPickupIndex( index );
				}

				// Hide loading indicator
				setIsLoading( false );
			} )
			.catch( ( error ) => {
				console.error(
					__( 'Error cargando puntos SEUR', 'seur' ),
					error
				);
				setIsLoading( false );
			} );
	}, [
		country,
		city,
		postcode,
		updateSeurExtensionData,
		setSelectedPickupIndex,
		mapContainerRef,
		maPlaceRef,
		dropdownRef,
		setIsLoading,
	] );

	// Sync dropdown value with state changes
	useEffect( () => {
		if ( selectedPickupIndex === null ) {
			return;
		}

		const dropdown =
			dropdownRef.current ||
			document.querySelector( 'select.seur-pickup-select2' );
		if ( dropdown ) {
			const pickupValue = ( selectedPickupIndex + 1 ).toString();
			if ( dropdown.value !== pickupValue ) {
				dropdown.value = pickupValue;
			}
		}
	}, [ selectedPickupIndex, dropdownRef ] );
};
