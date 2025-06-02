type BrowserFields = 'Chrome' | 'Firefox' | 'Safari' | 'Edge' | 'other';
type DeviceTypeFields = 'Desktop' | 'Mobile' | 'Tablet';
/**
 * Auto-populates Gravity Form Contact Form fields with user data.
 */
export default class BugReportFormHandler {
	constructor() {
		this.wireEventListener();
	}

	/**
	 * Attaches an event listener to the contact modal form to populate fields when the modal is shown.
	 * This method is called in the constructor to ensure the event listener is set up when the class is instantiated.
	 */
	private wireEventListener() {
		const form = document.getElementById( 'contact-modal' );
		if ( ! form ) {
			return;
		}
		form.addEventListener( 'show.bs.modal', () => {
			this.populateFields();
		} );
	}

	/**
	 * Retrieves the current session information including URL, browser, and device type.
	 * @returns An object containing the current URL, browser, and device type.
	 */
	private getSessionInfo(): {
		currentUrl: string;
		browser: BrowserFields;
		deviceType: DeviceTypeFields;
	} {
		const currentUrl = window.location.href;
		const userAgent = navigator.userAgent;
		let browser: BrowserFields = 'other';
		if ( /chrome|crios|crmo/i.test( userAgent ) ) {
			browser = 'Chrome';
		} else if ( /firefox|fxios/i.test( userAgent ) ) {
			browser = 'Firefox';
		} else if (
			/safari/i.test( userAgent ) &&
			! /chrome|crios|crmo/i.test( userAgent )
		) {
			browser = 'Safari';
		} else if ( /edg/i.test( userAgent ) ) {
			browser = 'Edge';
		}

		// Get device type
		let deviceType: DeviceTypeFields = 'Desktop';
		if ( /Mobi|Android/i.test( userAgent ) ) {
			deviceType = 'Mobile';
		}
		if ( /Tablet|iPad/i.test( userAgent ) ) {
			deviceType = 'Tablet';
		}

		return { currentUrl, browser, deviceType };
	}

	/**
	 * Retrieves the Gravity Form fields from the DOM.
	 * @throws Will throw an error if the fields are not found.
	 */
	private getGravityFormFields() {
		const urlField =
			document.querySelector< HTMLInputElement >( '#input_1_6' );
		const browserField =
			document.querySelector< HTMLDivElement >( '#input_1_5' );
		const deviceTypeField =
			document.querySelector< HTMLDivElement >( '#input_1_4' );
		if ( ! urlField || ! browserField || ! deviceTypeField ) {
			throw new Error(
				'Gravity Form fields not found. Please ensure the form is correctly set up.'
			);
		}
		return {
			urlField,
			browserField,
			deviceTypeField,
		};
	}

	/**
	 * Populates the Gravity Form fields with user data.
	 */
	public populateFields() {
		try {
			const { urlField, browserField, deviceTypeField } =
				this.getGravityFormFields();
			const { currentUrl, browser, deviceType } = this.getSessionInfo();
			urlField.value = currentUrl;
			this.setBrowser( browserField, browser );
			this.setDeviceType( deviceTypeField, deviceType );
		} catch ( error ) {
			console.error( error );
		}
	}

	/**
	 * Sets the browser in the Gravity Form.
	 */
	private setBrowser( browserField: HTMLDivElement, browser: BrowserFields ) {
		const browserOptions = browserField.querySelectorAll( 'input' );
		browserOptions.forEach( ( option ) => {
			if ( option.value === browser ) {
				option.checked = true;
			}
		} );
	}

	/**
	 * Sets the device type in the Gravity Form.
	 */
	private setDeviceType(
		deviceTypeField: HTMLDivElement,
		deviceType: DeviceTypeFields
	) {
		const deviceTypeOptions = deviceTypeField.querySelectorAll( 'input' );
		deviceTypeOptions.forEach( ( option ) => {
			if ( option.value === deviceType ) {
				option.checked = true;
			}
		} );
	}
}
