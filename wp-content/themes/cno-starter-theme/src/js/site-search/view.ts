import Modal from 'bootstrap/js/dist/modal';

export default class View {
	modalEl: HTMLElement;
	modal: Modal;
	searchInput: HTMLInputElement;
	searchResults: HTMLElement;
	recentSearches: string[] | null;
	private searchTimeoutId: number | null = null;

	constructor( recentSearches: string[] | null ) {
		this.recentSearches = recentSearches;
		const { modalEl, searchInput, searchResults } = this.getElements();
		this.searchInput = searchInput;
		this.searchResults = searchResults;
		this.modalEl = modalEl;
		this.modal = new Modal( this.modalEl );
		this.wireEventListeners();
	}

	/**
	 * Gets the elements from the DOM.
	 */
	private getElements() {
		return {
			modalEl: document.getElementById(
				'site-search-modal'
			) as HTMLElement,
			searchInput: document.getElementById(
				'site-search-input'
			) as HTMLInputElement,
			searchResults: document.getElementById(
				'site-search-results'
			) as HTMLElement,
		};
	}

	private wireEventListeners() {
		this.addGlobalKeyboardListener();
		this.setFocusOnModalShown();
	}

	/**
	 * Add a keyboard listener to open the search modal when the user presses the '/' key.
	 * This is a global listener that will work on any page.
	 */
	private addGlobalKeyboardListener() {
		document.addEventListener( 'keypress', ( event ) => {
			if ( '/' === event.key ) {
				event.preventDefault();
				this.modal.show();
			}
		} );
	}

	/**
	 * Set focus on the search input field when the modal is shown.
	 */
	private setFocusOnModalShown() {
		this.modalEl.addEventListener( 'shown.bs.modal', () => {
			this.searchInput.focus();
		} );
		this.showRecentSearches();
		this.handleRecentSearchEvents();
	}

	/**
	 * Shows Recent Searches in the search results while no search is performed.
	 * @returns
	 */
	private showRecentSearches() {
		if ( ! this.recentSearches || this.searchInput.value !== '' ) {
			return;
		}
		let markup =
			'<section="recent-pages"><h5>Recent</h5><ul class="list-group ms-0" id="recent-search-queries">';
		markup += this.recentSearches
			.map( ( query ) => {
				return `<li class="list-group-item"><a class="list-group-item-action" href="#">${ query }</a></li>`;
			} )
			.join( '' );
		markup += '</ul></section>';
		this.searchResults.innerHTML = markup;
	}

	/**
	 * Handle events for the recent searches.
	 */
	private handleRecentSearchEvents() {
		const recentSearches = document.getElementById(
			'recent-search-queries'
		);
		if ( ! recentSearches ) {
			return;
		}
		recentSearches.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			const target = event.target as HTMLElement;
			if ( target.tagName === 'A' ) {
				this.searchInput.value = target.textContent || '';
			}
		} );
	}

	/**
	 * Add an input event listener to the search input field to perform a search when the user types.
	 */
	addSearchInputListener(
		performSearch: ( query: string ) => Promise< any[] | null >
	) {
		const typingDelay = 250;
		this.searchInput.addEventListener( 'input', () => {
			if ( this.searchTimeoutId ) {
				clearTimeout( this.searchTimeoutId );
			}
			this.searchTimeoutId = window.setTimeout( () => {
				performSearch( this.searchInput.value ).then( ( results ) =>
					this.updateSearchResults( results )
				);
			}, typingDelay );
		} );
	}

	private renderSpinner() {
		return '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
	}

	/**
	 * Update the search results in the DOM.
	 *
	 * @param results The search results to display.
	 */
	updateSearchResults( results: any[] | null ) {
		this.searchResults.innerHTML = '';
		if ( ! results ) {
			this.searchResults.innerHTML = '<p>No Results found</p>';
			return;
		}
		this.searchResults.innerHTML = this.renderSpinner();
		let markup = '<ul class="list-group">';
		markup += results
			.map( ( result ) => {
				console.log( result );
				return `<li class="list-group-item"><a class="list-group-item-action" href="${ result.link }">${ result.title }</a></li>`;
			} )
			.join( '' );
		markup += '</ul>';
		this.searchResults.innerHTML = markup;
	}
}
