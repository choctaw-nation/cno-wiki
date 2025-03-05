export default class TOCGenerator {
	/**
	 * The `<ul>` element that holds the TOC links
	 */
	private toc: HTMLUListElement;

	/**
	 * The collection of all headings in the content
	 */
	private headingElements: NodeListOf< HTMLHeadingElement >;

	constructor(
		tocContainer: HTMLElement,
		headingElements: NodeListOf< HTMLHeadingElement >
	) {
		this.headingElements = headingElements;
		this.toc = tocContainer.querySelector( 'ol' )!;
	}

	/**
	 * Collects the headings
	 */
	public init() {
		const listItems = Array.from( this.headingElements ).map(
			( heading ) => {
				this.setHeadingId( heading );
				return {
					headingLevel: Number( heading.tagName.substring( 1 ) ),
					listItem: this.createListItem( heading ),
				};
			}
		);
		this.createTOCElements( listItems );
	}

	/**
	 * Sets the ID attribute on the heading element or its parent block
	 */
	private setHeadingId( heading: HTMLHeadingElement ) {
		const { headingSlug } = this.sanitizeHeading( heading );
		const parentBlock = heading.closest( '.wp-block-group' );
		if ( parentBlock && ! parentBlock.id ) {
			parentBlock.id = headingSlug;
		} else {
			heading.id = headingSlug;
		}
	}

	/**
	 * Creates a list item for each heading with a nested anchor
	 * @param heading an HTML heading element
	 * @returns HTMLLIElement a list item element
	 */
	private createListItem( heading: HTMLHeadingElement ): HTMLLIElement {
		const { headingText, headingSlug } = this.sanitizeHeading( heading );
		const listItem = document.createElement( 'li' );
		const link = document.createElement( 'a' );
		link.href = `#${ headingSlug }`;
		link.classList.add( 'text-decoration-none', 'nav-link' );
		link.textContent = headingText;
		listItem.appendChild( link );
		return listItem;
	}

	/**
	 * Sorts the list items and generates the nested TOC elements
	 */
	private createTOCElements(
		listItems: { headingLevel: number; listItem: HTMLLIElement }[]
	) {
		const stack: HTMLUListElement[] = [ this.toc ];
		listItems.forEach(
			( { listItem, headingLevel: currentLevel }, index ) => {
				if ( 0 === index ) {
					stack[ 0 ].appendChild( listItem );
					return;
				}

				const previousLevel = listItems[ index - 1 ]
					? listItems[ index - 1 ].headingLevel
					: null;

				// is last
				if ( null === previousLevel ) {
					stack[ stack.length - 1 ].appendChild( listItem );
					return;
				}
				if ( currentLevel > previousLevel ) {
					const ol = document.createElement( 'ol' );
					ol.classList.add(
						'nav',
						'nav-pills',
						'nav-fill',
						'flex-column'
					);
					ol.appendChild( listItem );
					stack[ stack.length - 1 ].appendChild( ol );
					stack.push( ol );
					return;
				}
				if ( currentLevel === previousLevel ) {
					stack[ stack.length - 1 ].appendChild( listItem );
					return;
				}
				if ( currentLevel < previousLevel ) {
					const diff = previousLevel - currentLevel;
					stack[ stack.length - 1 - diff ].appendChild( listItem );
					stack.splice( stack.length - diff, diff );
					return;
				}
			}
		);
	}

	/**
	 * Sanitize headings by removing special characters and generating slugs
	 */
	private sanitizeHeading(
		heading: HTMLHeadingElement,
		parentSlug?: string
	): {
		headingText: string;
		headingSlug: string;
	} {
		const headingText = heading
			.textContent!.replaceAll( ':', '' )
			.replace( /\(.*?\)/g, '' );

		const headingSlug = headingText
			.toLowerCase()
			.replaceAll( ' &', '' )
			.replaceAll( ',', '' )
			.replaceAll( '.', '' )
			.replaceAll( /\s/g, '-' );

		const headingId = parentSlug
			? `${ parentSlug }-${ headingSlug }`
			: headingSlug;
		return { headingText, headingSlug: headingId };
	}
}
