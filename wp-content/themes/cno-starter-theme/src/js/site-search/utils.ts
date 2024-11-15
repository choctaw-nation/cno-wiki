export interface SearchQueryResponse {
	title: string;
	link: string;
	excerpt: string;
	meta: {
		post_type: string;
		categories: string;
		frameworks: object | boolean;
		languages: object | boolean;
		website: object | boolean;
		tags: string;
	};
}

export interface LocalStorageQuery extends SearchQueryResponse {
	expiry: number;
}
