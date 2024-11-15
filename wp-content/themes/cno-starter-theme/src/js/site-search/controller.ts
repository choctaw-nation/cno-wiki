import Model from './model';
import View from './view';

export default class Controller {
	view: View;
	model: Model;

	constructor() {
		this.model = new Model();
		this.view = new View( this.model.getRecentSearches.bind( this.model ) );
		this.handleSearch();
	}

	/**
	 * Handle search functionality.
	 */
	private handleSearch() {
		this.view.addSearchInputListener(
			this.model.performSearch.bind( this.model )
		);
		this.view.handleResultSelection(
			this.model.storeRecentSearch.bind( this.model )
		);
	}
}
