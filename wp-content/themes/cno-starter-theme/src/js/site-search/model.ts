export default class Model {
	private localStorageKey = 'searchQueries';
	private localStorageExpiration = 1000 * 60 * 60 * 24 * 7; // 1 week in milliseconds

	/**
	 * Perform a search using the WordPress REST API.
	 *
	 * @param query The search query to perform.
	 */
	async performSearch( query: string ): Promise< any[] | null > {
		if ( ! query ) return null;
		try {
			const response = await fetch(
				`/wp-json/cno/v1/search?s=${ encodeURIComponent( query ) }`
			);
			const results = await response.json();
			if ( results.length > 0 ) {
				this.storeQueryToLocalStorage( query );
			}
			return results as Array< any >;
		} catch ( error ) {
			console.error( 'Search error:', error );
			return null;
		}
	}

	getRecentSearches(): string[] {
		return this.getStoredQueries();
	}

	// Store the search query to local storage with expiration
	private storeQueryToLocalStorage( query: string ) {
		const storedQueries = this.getStoredQueries();
		storedQueries.unshift( query );
		this.storeItemWithExpiration( storedQueries );
	}

	/**
	 * Gets the stored search queries from local storage.
	 *
	 * @returns The stored search queries from local storage.
	 */
	private getStoredQueries(): string[] {
		const storedQueries = this.getItemWithExpiration();
		return storedQueries ? storedQueries : [];
	}

	/**
	 * Store an item in local storage with an expiration time.
	 *
	 * @param value The value to store.
	 */
	private storeItemWithExpiration( value: any ) {
		const now = new Date();
		const item = {
			value: value,
			expiry: now.getTime() + this.localStorageExpiration,
		};
		localStorage.setItem( this.localStorageKey, JSON.stringify( item ) );
	}

	/**
	 * Get an item from local storage with an expiration time.
	 *
	 * @returns The stored item or null if the item has expired or does not exist.
	 */
	private getItemWithExpiration() {
		const itemStr = localStorage.getItem( this.localStorageKey );
		if ( ! itemStr ) {
			return null;
		}
		const item = JSON.parse( itemStr );
		const now = new Date();

		// Compare the expiry time with the current time
		if ( now.getTime() > item.expiry ) {
			// If the item has expired, remove it from storage and return null
			localStorage.removeItem( this.localStorageKey );
			return null;
		}
		return item.value;
	}
}
