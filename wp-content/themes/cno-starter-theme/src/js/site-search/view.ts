import Modal from 'bootstrap/js/dist/modal';
import { LocalStorageQuery, SearchQueryResponse } from './utils';

export default class View {
	modalEl: HTMLElement;
	modal: Modal;
	searchInput: HTMLInputElement;
	searchResults: HTMLElement;
	recentSearches: LocalStorageQuery[] | null;
	private getRecentSearches: () => LocalStorageQuery[] | null;
	private searchTimeoutId: number | null = null;

	constructor( getRecentSearches: () => LocalStorageQuery[] | null ) {
		this.getRecentSearches = getRecentSearches;
		this.recentSearches = this.getRecentSearches();
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

	/**
	 * Wire up event listeners.
	 */
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
	}

	/**
	 * Shows Recent Searches in the search results while no search is performed.
	 * @returns
	 */
	private showRecentSearches() {
		this.recentSearches = this.getRecentSearches();
		const noRecentSearches =
			! this.recentSearches || this.recentSearches.length === 0;
		if ( noRecentSearches || this.searchInput.value !== '' ) {
			return;
		}
		let markup = `<section class="recent-results"><h5>Recent</h5>${ this.generateListGroup(
			this.recentSearches!,
			'recent-searches'
		) }</section>`;
		this.searchResults.innerHTML = markup;
	}

	/**
	 * Renders a spinner while results are loading
	 */
	private renderSpinner() {
		return '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
	}

	/**
	 * Generates a list group from search results.
	 *
	 * @param results The search results to display.
	 * @param id The id of the list group.
	 */
	private generateListGroup(
		results: SearchQueryResponse[] | LocalStorageQuery[],
		id?: string
	) {
		let markup = `<ul class="list-group ms-0" ${
			id ? `id="${ id }"` : ''
		}">`;
		markup += results
			.map( ( { link, title, excerpt } ) => {
				return `<li class="list-group-item position-relative">
				<a class="stretched-link d-inline-block text-decoration-none h6 mb-0" href="${ link }">${ title }</a>
				${ excerpt ? `<div class="text-muted fs-base">${ excerpt }</div>` : '' }
				</li>`;
			} )
			.join( '' );
		markup += '</ul>';
		return markup;
	}

	/**
	 * Add an input event listener to the search input field to perform a search when the user types.
	 */
	addSearchInputListener(
		performSearch: (
			query: string
		) => Promise< SearchQueryResponse[] | null >
	) {
		const typingDelay = 250;
		this.searchInput.addEventListener( 'input', () => {
			if ( this.searchTimeoutId ) {
				clearTimeout( this.searchTimeoutId );
			}
			this.searchTimeoutId = window.setTimeout( () => {
				this.searchResults.innerHTML = '';
				this.searchResults.innerHTML = this.renderSpinner();
				performSearch( this.searchInput.value ).then( ( results ) =>
					this.updateSearchResults( results )
				);
			}, typingDelay );
		} );
	}

	/**
	 * Update the search results in the DOM.
	 *
	 * @param results The search results to display.
	 */
	updateSearchResults( results: SearchQueryResponse[] | null ) {
		if ( this.searchInput.value === '' ) {
			this.showRecentSearches();
			return;
		}
		if ( ! results || results.length === 0 ) {
			this.searchResults.innerHTML = '<p>No Results found</p>';
			return;
		}
		this.searchResults.innerHTML = this.generateListGroup( results );
	}

	/**
	 * Handle the selection of a search result.
	 *
	 * @param callback The callback function to execute when a search result is selected.
	 */
	handleResultSelection( callback: ( result: string ) => void ) {
		this.searchResults.addEventListener( 'click', ( event ) => {
			event.preventDefault();
			const target = event.target as HTMLElement;
			if ( target.tagName === 'A' ) {
				callback( target.textContent! );
				window.location.href = target.getAttribute( 'href' )!;
			}
		} );
	}
}
