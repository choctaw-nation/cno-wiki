import ScrollSpy from 'bootstrap/js/dist/scrollspy';

new ( class TOCScrollHandler {
	private scrollContainer: HTMLElement;
	private tocContainer: HTMLElement;
	private toc: HTMLUListElement;
	private headingElements: HTMLHeadingElement[][];

	constructor() {
		this.scrollContainer = document.querySelector(
			'article.docs-content'
		)!;
		this.tocContainer = document.getElementById( 'table-of-contents' )!;
		this.toc = this.tocContainer.querySelector( 'ul' )!;
		this.init();
		ScrollSpy.getInstance( this.scrollContainer )?.refresh();
	}

	private init() {
		new ScrollSpy( this.scrollContainer, {
			target: '#table-of-contents',
			smoothScroll: true,
		} );
		if ( ! this.toc ) {
			return;
		}
		this.collectHeadings();
		this.generateTOC();
	}

	/**
	 * Collect all heading elements (below H1) and group them by their level
	 *
	 */
	private collectHeadings() {
		const flatHeadings =
			this.scrollContainer.querySelectorAll< HTMLHeadingElement >(
				'h2,h3,h4,h5,h6'
			);
		const nestedHeadings: HTMLHeadingElement[][] = [];
		let currentHeadingLevel: number | null = null;
		let currentArray: HTMLHeadingElement[] = [];

		flatHeadings.forEach( ( heading ) => {
			const headingLevel = parseInt( heading.tagName.substring( 1 ), 10 );

			if (
				currentHeadingLevel === null ||
				headingLevel < currentHeadingLevel
			) {
				if ( currentArray.length ) {
					nestedHeadings.push( currentArray );
				}
				currentArray = [];
			}
			currentArray.push( heading );
			currentHeadingLevel = headingLevel;
		} );

		if ( currentArray.length ) {
			nestedHeadings.push( currentArray );
		}

		this.headingElements = nestedHeadings;
	}

	/**
	 * Generate the TOC from the collected headings
	 */
	private generateTOC() {
		this.headingElements.forEach( ( headingArray ) => {
			const heading = headingArray[ 0 ];
			const { headingText, headingSlug } =
				this.sanitizeHeading( heading );
			this.toc.appendChild(
				this.createTOCItem( headingText, headingSlug )
			);
			if ( headingArray.length > 1 ) {
				const subList = document.createElement( 'ul' );
				subList.classList.add(
					'nav-pills',
					'flex-column',
					'list-group',
					'list-unstyled'
				);
				headingArray.slice( 1 ).forEach( ( subHeading ) => {
					const { headingText, headingSlug } =
						this.sanitizeHeading( subHeading );
					subList.appendChild(
						this.createTOCItem( headingText, headingSlug )
					);
				} );
				this.toc.appendChild( subList );
			}
		} );
	}

	/**
	 * Create a TOC ite
	 */
	private createTOCItem(
		headingText: string,
		headingSlug: string
	): HTMLLIElement {
		const listItem = document.createElement( 'li' );
		listItem.classList.add( 'nav-item', 'list-group-item' );
		const anchor = document.createElement( 'a' );
		anchor.href = `#${ headingSlug }`;
		anchor.textContent = headingText;
		anchor.classList.add( 'nav-link', 'fs-base' );
		listItem.appendChild( anchor );
		return listItem;
	}

	/**
	 * Sanitize headings by removing special characters and generating slugs
	 */
	private sanitizeHeading( heading: HTMLHeadingElement ): {
		headingText: string;
		headingSlug: string;
	} {
		const headingText = heading
			.textContent!.replaceAll( ':', '' )
			.replace( /\(.*?\)/g, '' );

		const headingSlug = headingText
			.toLowerCase()
			.replaceAll( ' &', '' )
			.replaceAll( /\s/g, '-' );

		heading.id = headingSlug;
		return { headingText, headingSlug };
	}
} )();
