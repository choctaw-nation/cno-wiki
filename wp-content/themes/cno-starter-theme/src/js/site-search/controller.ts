import Model from './model';
import View from './view';

export default class Controller {
	view: View;
	model: Model;

	constructor() {
		this.model = new Model();
		const recentSearches = this.model.getRecentSearches();
		this.view = new View( recentSearches );
		this.handleSearch();
	}

	private handleSearch() {
		this.view.addSearchInputListener(
			this.model.performSearch.bind( this.model )
		);
	}
}
