/**
 * Creates a custom Maplace control for the SEUR pickup dropdown.
 *
 * @param {Object} params Control parameters
 * @param {Array} params.locations Array of pickup locations
 * @param {Object} params.dropdownRef React ref for dropdown element
 * @param {Function} params.setSelectedPickupIndex Function to update selected index
 * @param {Function} params.updateSeurExtensionData Function to save pickup data
 * @return {Object} Maplace control object
 */
export const createMapControl = ( {
	locations,
	dropdownRef,
	setSelectedPickupIndex,
	updateSeurExtensionData,
} ) => {
	/**
	 * Saves the selected pickup point data.
	 *
	 * @param {number} index The 0-based index of the selected location
	 */
	const saveSelectedPickup = ( index ) => {
		if ( typeof updateSeurExtensionData !== 'function' ) {
			return;
		}

		const pickupValue = ( index + 1 ).toString();
		const selectedLocation = locations[ index ] || {};

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
	};

	return {
		/**
		 * Activates the dropdown option for the given index.
		 *
		 * @param {number} index The 1-based index to activate
		 */
		activateCurrent: function ( index ) {
			const select = this.html_element?.querySelector( 'select.seur-pickup-select2' );
			if ( select ) {
				select.value = index;
			}
		},

		/**
		 * Generates the HTML structure for the dropdown control.
		 *
		 * @return {HTMLElement|null} The control wrapper element
		 */
		getHtml: function () {
			const self = this;

			if ( this.ln <= 1 ) {
				return null;
			}

			// Create select element
			const select = document.createElement( 'select' );
			select.name = 'seur_pickup';
			select.required = true;
			select.className = 'seur-pickup-select2' + ( this.o.controls_cssclass || '' );

			// Add "View all" option if enabled
			if ( this.ShowOnMenu( this.view_all_key ) ) {
				const optAll = document.createElement( 'option' );
				optAll.value = this.view_all_key;
				optAll.selected = true;
				optAll.textContent = this.o.view_all_text;
				select.appendChild( optAll );
			}

			// Add location options
			for ( let a = 0; a < this.ln; a += 1 ) {
				if ( this.ShowOnMenu( a ) ) {
					const option = document.createElement( 'option' );
					option.value = a + 1;
					option.textContent = this.o.locations[ a ].title || '#' + ( a + 1 );
					select.appendChild( option );
				}
			}

			// Add change event listener
			select.addEventListener( 'change', function () {
				const pickupValue = this.value || '';

				// Ignore invalid selections
				if ( ! pickupValue || pickupValue === 'all' || pickupValue === '0' ) {
					return;
				}

				const index = parseInt( pickupValue ) - 1;

				// Update map view
				self.ViewOnMap( pickupValue );

				// Save pickup data
				saveSelectedPickup( index );
			} );

			// Store reference for React
			dropdownRef.current = select;

			// Create wrapper container
			const wrapper = document.createElement( 'div' );
			wrapper.className = 'wrap_controls';

			// Add title if configured
			if ( this.o.controls_title ) {
				const titleDiv = document.createElement( 'div' );
				titleDiv.className = 'controls_title';
				titleDiv.textContent = this.o.controls_title;

				// Apply CSS if enabled
				if ( this.o.controls_applycss ) {
					Object.assign( titleDiv.style, {
						fontWeight: 'bold',
						fontSize: this.o.controls_on_map ? '12px' : 'inherit',
						padding: '3px 10px 5px 0',
					} );
				}

				wrapper.appendChild( titleDiv );
			}

			wrapper.appendChild( select );

			// Store reference for activateCurrent
			this.html_element = wrapper;

			return wrapper;
		},
	};
};
