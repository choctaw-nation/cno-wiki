/**
 * Calculates the height of the header and sets a CSS variable (`--header-offset`) with the value.
 */
export default class HeaderOffsetHandler {
	/**
	 * The header element to calculate the offset from.
	 */
	private siteHeader: HTMLElement;

	/**
	 * The CSS variable name to set the offset value.
	 */
	private cssVar: string;

	/**
	 * The default offset value if the header is not found.
	 */
	private defaultOffset = 114;

	/**
	 * The calculated height of the header (#site-header).
	 */
	private headerHeight: number;

	/**
	 * Creates a new instance of the HeaderOffsetGenerator class.
	 */
	constructor() {
		this.cssVar = '--site-header-offset';
		const siteHeader = document.getElementById( 'site-header' );
		if ( siteHeader ) {
			this.siteHeader = siteHeader;
		}

		this.setOffset();

		window.addEventListener( 'resize', () => this.setOffset() );
	}

	/**
	 * Sets the offset value as a CSS variable and updates the headerHeight property.
	 */
	private setOffset() {
		this.headerHeight = this.siteHeader.offsetHeight;
		document.documentElement.style.setProperty(
			this.cssVar,
			`${ this.headerHeight || this.defaultOffset }px`
		);
		if (
			! document.documentElement.style.getPropertyValue( this.cssVar )
		) {
			console.warn( 'Header offset not found.' );
		}
	}

	/**
	 * Calculates the offset value based on the target element.
	 *
	 * @param target The target element to calculate the offset from.
	 * @return The calculated offset value
	 */
	private calcOffset( target: HTMLElement ): number {
		const targetTop = target.getBoundingClientRect().top + window.scrollY;
		const EXTRA_OFFSET = 50;
		const offset =
			targetTop -
			( ( this.headerHeight || this.defaultOffset ) - EXTRA_OFFSET );
		return offset;
	}

	/**
	 * Handles the click event on the navigation links to correctly handle scroll and/or page reload.
	 *
	 * @param ev The click event.
	 */
	private handleNavClick( ev: Event ) {
		const target = ev.target as HTMLElement;
		const href = target.getAttribute( 'href' );
		if (
			href &&
			href.includes(
				this.trailingSlashHandler( window.location.pathname )
			)
		) {
			if ( ! href.includes( '#' ) ) {
				return;
			}
			ev.preventDefault();
			if (
				href ===
				`${ window.location.pathname }${ window.location.hash }`
			) {
				return;
			}
			window.history.pushState( null, '', href );
			this.handleScrollBehavior( `#${ href.split( '#' )[ 1 ] }` );
		}
	}

	/**
	 * Removes the trailing slash from the href value.
	 *
	 * @param href The href value to remove the trailing slash from.
	 * @return The href value without the trailing
	 */
	private trailingSlashHandler( href: string ): string {
		if ( href.charAt( href.length - 1 ) === '/' ) {
			return href.slice( 0, -1 );
		}
		return href;
	}
}
