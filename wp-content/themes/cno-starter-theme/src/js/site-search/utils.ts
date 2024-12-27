import { WP_Term } from 'wp-types';
export interface SearchQueryResponse {
	title: string;
	link: string;
	excerpt: string;
	meta: {
		post_type: string;
		categories: WP_Term[] | false;
		frameworks: WP_Term[] | false;
		languages: WP_Term[] | false;
		websites: WP_Term[] | false;
		tags: WP_Term[] | false;
	};
}

export interface LocalStorageQuery extends SearchQueryResponse {
	expiry: number;
}
