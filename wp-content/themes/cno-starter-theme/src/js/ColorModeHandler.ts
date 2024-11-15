/*
 * Modified version of the Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
 * Copyright 2011-2024 The Bootstrap Authors
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 */
type ColorMode = 'light' | 'dark' | 'auto';

new ( class ColorModeHandler {
	/**
	 * The color-theme buttons
	 */
	private themeButtons: NodeListOf< HTMLButtonElement >;

	/**
	 * The user's preferred theme from media query
	 */
	private userPreference: 'light' | 'dark';

	constructor() {
		this.userPreference = window.matchMedia(
			'(prefers-color-scheme: dark)'
		)
			? 'dark'
			: 'light';
		this.setTheme( this.getPreferredTheme() );
		this.wireEventListeners();
	}

	/**
	 * Gets the color-theme button elements
	 */
	private getThemeButtons(): NodeListOf< HTMLButtonElement > {
		return document.querySelectorAll< HTMLButtonElement >(
			'[data-bs-theme-value]'
		);
	}

	/**
	 * Updates the root element with the selected theme
	 */
	private setTheme( theme: ColorMode ) {
		if ( 'auto' == theme ) {
			document.documentElement.setAttribute(
				'data-bs-theme',
				this.userPreference
			);
		} else {
			document.documentElement.setAttribute( 'data-bs-theme', theme );
		}
	}

	/**
	 * Stores the selected theme in local storage
	 */
	private storeSelectedTheme( theme: ColorMode ) {
		localStorage.setItem( 'theme', theme );
	}

	/**
	 * Gets the stored theme from local storage
	 */
	private getStoredTheme(): ColorMode | null {
		return localStorage.getItem( 'theme' ) as ColorMode | null;
	}

	/**
	 * Gets the user's preferred theme from either local storage or the browser's settings
	 */
	private getPreferredTheme(): ColorMode {
		const storedTheme = this.getStoredTheme();
		return storedTheme || this.userPreference;
	}

	/**
	 * Wires the event listeners
	 */
	private wireEventListeners() {
		// Update the theme when the user changes their system preference
		window
			.matchMedia( '(prefers-color-scheme: dark)' )
			.addEventListener( 'change', () => {
				const storedTheme = this.getStoredTheme();
				if ( storedTheme !== 'light' && storedTheme !== 'dark' ) {
					this.setTheme( this.getPreferredTheme() );
				}
			} );

		window.addEventListener( 'DOMContentLoaded', () => {
			this.themeButtons = this.getThemeButtons();
			// Set the theme when the page loads
			this.showActiveTheme( this.getPreferredTheme() );

			// Update the theme when the user selects a theme
			this.themeButtons.forEach( ( toggle ) => {
				toggle.addEventListener( 'click', () => {
					const theme = toggle.getAttribute(
						'data-bs-theme-value'
					) as ColorMode;
					this.storeSelectedTheme( theme );
					this.setTheme( theme );
					this.showActiveTheme( theme, true );
				} );
			} );
		} );
	}

	/**
	 * Shows the active theme in the toggler element
	 */
	private showActiveTheme( theme: ColorMode, focus = false ) {
		const colorModeToggler =
			document.querySelector< HTMLButtonElement >( '#bd-theme' );

		if ( ! colorModeToggler ) {
			return;
		}

		const {
			colorModeTogglerText,
			activeModeIcon,
			selectedColorMode,
			selectedColorModeIcon,
			selectedColorModeCheck,
		} = this.getTogglerElements( theme );

		// first clear all active states
		this.themeButtons.forEach( ( element ) => {
			element.classList.remove( 'active' );
			element.setAttribute( 'aria-pressed', 'false' );
			element.querySelectorAll( 'svg' )[ 1 ].classList.add( 'd-none' );
		} );

		// then set the active state for the selected theme
		selectedColorMode.classList.add( 'active' );
		selectedColorMode.setAttribute( 'aria-pressed', 'true' );
		selectedColorModeCheck.classList.remove( 'd-none' );
		activeModeIcon.innerHTML = selectedColorModeIcon.innerHTML;
		const themeSwitcherLabel = `Toggle color mode (${ selectedColorMode.dataset.bsThemeValue })`;
		colorModeToggler.setAttribute( 'aria-label', themeSwitcherLabel );

		if ( focus ) {
			colorModeToggler.focus();
		}
	}

	/**
	 * Gets the toggler elements
	 */
	private getTogglerElements( theme: ColorMode ): {
		colorModeTogglerText: HTMLSpanElement;
		activeModeIcon: SVGElement;
		selectedColorMode: HTMLButtonElement;
		selectedColorModeIcon: SVGElement;
		selectedColorModeCheck: SVGElement;
	} {
		const colorModeTogglerText = document.querySelector(
			'#color-mode-toggle-text'
		) as HTMLSpanElement;

		const activeModeIcon = document.querySelector(
			'.theme-icon-active'
		) as SVGElement;

		const selectedColorMode = document.querySelector(
			`[data-bs-theme-value="${ theme }"]`
		) as HTMLButtonElement;

		const selectedColorModeIcon = selectedColorMode.querySelector(
			'svg'
		) as SVGElement;

		const selectedColorModeCheck =
			selectedColorMode.querySelectorAll< SVGElement >( 'svg' )[ 1 ];

		return {
			colorModeTogglerText,
			activeModeIcon,
			selectedColorMode,
			selectedColorModeIcon,
			selectedColorModeCheck,
		};
	}
} )();
