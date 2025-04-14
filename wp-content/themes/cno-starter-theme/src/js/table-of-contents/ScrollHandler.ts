import TOCGenerator from './TOCGenerator';

export default class ScrollHandler {
	/**
	 * TOC Generator
	 */
	private tocGenerator: TOCGenerator;

	/**
	 * The actual "body" content that ScrollSpy watches
	 */
	private scrollContainer: HTMLElement;

	/**
	 * The `<nav>` element that holds the TOC `<ul>`
	 */
	private tocContainer: HTMLElement;

	constructor() {
		this.scrollContainer = document.querySelector( '.docs-content' )!;
		this.tocContainer = document.getElementById( 'table-of-contents' )!;
		this.init();
	}

	/**
	 * Wires up the ScrollSpy and collects the headings, or hides the TOC if no headings are found
	 */
	private init() {
		const headingElements =
			this.scrollContainer.querySelectorAll< HTMLHeadingElement >(
				'h2,h3,h4,h5,h6'
			);
		if ( 0 === headingElements.length ) {
			this.hideTOC();
			return;
		}
		this.tocGenerator = new TOCGenerator(
			this.tocContainer,
			headingElements
		);
		this.tocGenerator.init();
		this.addIntersectionObserver();
	}

	/**
	 * Hide the TOC when no headings are found
	 */
	private hideTOC() {
		const tocContainer =
			this.tocContainer.closest< HTMLElement >( '.docs-toc-sidebar' )!;
		tocContainer.style.display = 'none';

		const contentGrid =
			tocContainer.closest< HTMLElement >( '.docs-main' )!;
		contentGrid.classList.add( 'd-block', 'container-lg' );
	}

	/**
	 * Add the Intersection Observer to watch the headings
	 */
	private addIntersectionObserver() {
		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					const targetId = entry.target.getAttribute( 'id' );
					if ( ! targetId ) return;

					const tocLink = this.tocContainer.querySelector(
						`a[href="#${ targetId }"]`
					);

					if ( entry.isIntersecting ) {
						tocLink?.classList.add( 'active' );
					} else {
						tocLink?.classList.remove( 'active' );
					}
				} );
			},
			{
				threshold: 0.3,
				rootMargin: '-50px',
			}
		);

		const entries =
			this.scrollContainer.querySelectorAll< HTMLElement >(
				'article *[id]'
			);
		entries.forEach( ( entry ) => observer.observe( entry ) );
	}
}
