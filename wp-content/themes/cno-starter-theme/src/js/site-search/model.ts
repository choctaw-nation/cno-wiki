import { LocalStorageQuery, SearchQueryResponse } from './utils';

export default class Model {
	private LOCAL_STORAGE_KEY = 'searchQueries';
	/**
	 * One week in milliseconds.
	 */
	private LOCAL_STORAGE_EXPIRATION = 1000 * 60 * 60 * 24 * 7;

	private results: SearchQueryResponse[];

	/**
	 * Perform a search using the WordPress REST API.
	 *
	 * @param query The search query to perform.
	 */
	async performSearch(
		query: string
	): Promise< SearchQueryResponse[] | null > {
		if ( ! query ) return null;
		try {
			const response = await fetch(
				`/wp-json/cno/v1/search?s=${ encodeURIComponent( query ) }`
			);
			const results = await response.json();
			this.results = results;
			return results as SearchQueryResponse[];
		} catch ( error ) {
			console.error( 'Search error:', error );
			return null;
		}
	}

	/**
	 * Store the search query to local storage.
	 *
	 * @param query The search query to store.
	 */
	storeRecentSearch( query: string ) {
		const searchResult = this.results.find(
			( result ) => result.title === query
		)!;
		this.storeItemWithExpiration( searchResult );
	}

	getRecentSearches(): LocalStorageQuery[] {
		return this.getStoredQueries();
	}

	/**
	 * Gets the stored search queries from local storage.
	 *
	 * @returns The stored search queries from local storage.
	 */
	private getStoredQueries(): LocalStorageQuery[] {
		const storedQueries = this.getRecentSearchResults();
		return storedQueries ? storedQueries : [];
	}

	/**
	 * Store an item in local storage with an expiration time.
	 *
	 * @param query The query to store.
	 */
	private storeItemWithExpiration( query: SearchQueryResponse ) {
		const now = new Date();
		const item: LocalStorageQuery = {
			...query,
			expiry: now.getTime() + this.LOCAL_STORAGE_EXPIRATION,
		};
		const storedQueries = this.getStoredQueries();
		let updated = false;
		const updatedQueries = storedQueries.map( ( storedQuery ) => {
			if ( storedQuery && storedQuery.title === item.title ) {
				storedQuery.expiry = item.expiry;
				updated = true;
			}
			return storedQuery;
		} );
		if ( ! updated ) {
			updatedQueries.push( item );
		}
		localStorage.setItem(
			this.LOCAL_STORAGE_KEY,
			JSON.stringify( updatedQueries )
		);
	}

	/**
	 * Get an item from local storage with an expiration time.
	 *
	 * @returns The stored item or null if the item has expired or does not exist.
	 */
	private getRecentSearchResults(): LocalStorageQuery[] | null {
		const itemStr = localStorage.getItem( this.LOCAL_STORAGE_KEY );
		if ( ! itemStr ) {
			return null;
		}
		const recentResults: LocalStorageQuery[] = JSON.parse( itemStr );
		const now = new Date();
		const results = recentResults.map( ( result ) => {
			// Compare the expiry time with the current time
			if ( now.getTime() > result.expiry ) {
				// If the item has expired, remove it from storage and return null
				localStorage.removeItem( this.LOCAL_STORAGE_KEY );
				return null;
			} else {
				return result;
			}
		} );
		return results.filter( ( query ) => query !== null );
	}
}
