<?php
/**
 * The Site Search Modal
 *
 * @package ChoctawNation
 */

?>
<div class="modal fade" id="site-search-modal" tabindex="-1" aria-label="Site Search Window" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<header class="modal-header">
				<form class="flex-grow-1">
					<div class="input-group">
						<label for="site-search-input" id="docsearch-label" class="input-group-text">
							<svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" class="DocSearch-Search-Icon">
								<path d="M14.386 14.386l4.0877 4.0877-4.0877-4.0877c-2.9418 2.9419-7.7115 2.9419-10.6533 0-2.9419-2.9418-2.9419-7.7115 0-10.6533 2.9418-2.9419 7.7115-2.9419 10.6533 0 2.9419 2.9418 2.9419 7.7115 0 10.6533z"
									  stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
								</path>
							</svg>
						</label>
						<input aria-autocomplete="both" aria-labelledby="docsearch-label" id="site-search-input" autocomplete="off" autocorrect="off" autocapitalize="off" enterkeyhint="go"
							   spellcheck="false" placeholder="Search docs" maxlength="64" type="search" class="form-control" aria-activedescendant="docsearch-recentSearches-item-0"
							   aria-controls="docsearch-recentSearches-list docsearch-favoriteSearches-list">
					</div>
					<button type="reset" title="Clear the query" aria-label="Clear the query" class="DocSearch-Reset" hidden="">
						<svg width="20" height="20" viewBox="0 0 20 20">
							<path d="M10 10l5.09-5.09L10 10l5.09 5.09L10 10zm0 0L4.91 4.91 10 10l-5.09 5.09L10 10z" stroke="currentColor" fill="none" fill-rule="evenodd"
								  stroke-linecap="round" stroke-linejoin="round">
							</path>
						</svg>
					</button>
				</form>
			</header>
			<div class="modal-body pt-4 pb-5" id="site-search-results"></div>
			<footer class="modal-footer justify-content-start">
				<ul class="list-unstyled d-flex ms-0 gap-3">
					<li class="d-flex align-items-center gap-2">
						<kbd class="DocSearch-Commands-Key">
							<svg width="15" height="15" aria-label="Enter key" role="img">
								<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
									<path d="M12 3.53088v3c0 1-1 2-2 2H4M7 11.53088l-3-3 3-3"></path>
								</g>
							</svg>
						</kbd>
						<span class="DocSearch-Label">to select</span>
					</li>
					<li class="d-flex align-items-center gap-2">
						<kbd class="DocSearch-Commands-Key">
							<svg width="15" height="15" aria-label="Arrow down" role="img">
								<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
									<path d="M7.5 3.5v8M10.5 8.5l-3 3-3-3"></path>
								</g>
							</svg>
						</kbd>
						<kbd class="DocSearch-Commands-Key">
							<svg width="15" height="15" aria-label="Arrow up" role="img">
								<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
									<path d="M7.5 11.5v-8M10.5 6.5l-3-3-3 3"></path>
								</g>
							</svg>
						</kbd>
						<span class="DocSearch-Label">to navigate</span>
					</li>
					<li class="d-flex align-items-center gap-2">
						<kbd class="DocSearch-Commands-Key">
							<svg width="15" height="15" aria-label="Escape key" role="img">
								<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
									<path
										  d="M13.6167 8.936c-.1065.3583-.6883.962-1.4875.962-.7993 0-1.653-.9165-1.653-2.1258v-.5678c0-1.2548.7896-2.1016 1.653-2.1016.8634 0 1.3601.4778 1.4875 1.0724M9 6c-.1352-.4735-.7506-.9219-1.46-.8972-.7092.0246-1.344.57-1.344 1.2166s.4198.8812 1.3445.9805C8.465 7.3992 8.968 7.9337 9 8.5c.032.5663-.454 1.398-1.4595 1.398C6.6593 9.898 6 9 5.963 8.4851m-1.4748.5368c-.2635.5941-.8099.876-1.5443.876s-1.7073-.6248-1.7073-2.204v-.4603c0-1.0416.721-2.131 1.7073-2.131.9864 0 1.6425 1.031 1.5443 2.2492h-2.956">
									</path>
								</g>
							</svg>
						</kbd>
						<span class="DocSearch-Label">to close</span>
					</li>
				</ul>
			</footer>
		</div>
	</div>
</div>