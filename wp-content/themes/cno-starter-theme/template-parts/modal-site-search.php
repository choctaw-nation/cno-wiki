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
						<input id="site-search-input" autocomplete="off" autocorrect="off" autocapitalize="off" enterkeyhint="go" spellcheck="false" placeholder="Search docs" maxlength="64"
								type="search" class="form-control">
					</div>
					<button type="reset" title="Clear the query" aria-label="Clear the query" hidden="">
						<svg width="20" height="20" viewBox="0 0 20 20">
							<path d="M10 10l5.09-5.09L10 10l5.09 5.09L10 10zm0 0L4.91 4.91 10 10l-5.09 5.09L10 10z" stroke="currentColor" fill="none" fill-rule="evenodd"
									stroke-linecap="round" stroke-linejoin="round">
							</path>
						</svg>
					</button>
				</form>
			</header>
			<div class="modal-body pt-4 pb-5" id="site-search-results"></div>
		</div>
	</div>
</div>
