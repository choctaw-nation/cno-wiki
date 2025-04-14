<?php
/**
 * Search Form Template
 *
 * @package ChoctawNation
 */

$search_button_classes = array(
	'btn',
	'border-1',
	'border-light',
	'border-opacity-75',
	'rounded-3',
	'd-flex',
	'fw-medium',
	'justify-content-start',
	'align-items-center',
	'px-2',
	'w-100',
)
?>
<div class="ms-auto flex-lg-grow-1" id="site-search">
	<button type="button" aria-label="Search (Keyboard Forward Slash)" class="<?php echo implode( ' ', $search_button_classes ); ?>" data-bs-toggle="modal"
			data-bs-target="#site-search-modal">
		<svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" class="opacity-75 text-light">
			<path d="M14.386 14.386l4.0877 4.0877-4.0877-4.0877c-2.9418 2.9419-7.7115 2.9419-10.6533 0-2.9419-2.9418-2.9419-7.7115 0-10.6533 2.9418-2.9419 7.7115-2.9419 10.6533 0 2.9419 2.9418 2.9419 7.7115 0 10.6533z"
					stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
			</path>
		</svg>
		<span class="ps-3 pe-4 opacity-75 text-white d-none d-lg-inline">Type <kbd>/</kbd> to search</span>
	</button>
</div>