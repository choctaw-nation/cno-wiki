<?php
/**
 * Search Form Template
 *
 * @package ChoctawNation
 */

$search_button_classes = array(
	'btn',
	'w-100',
	'border-1',
	'border-light',
	'border-opacity-75',
	'rounded-3',
	'd-flex',
	'fw-medium',
	'justify-content-between',
	'align-items-center',
	'px-2',
)
?>
<div class="flex-grow-1" id="site-search">
	<button type="button" aria-label="Search (Command+K)" class="<?php echo implode( ' ', $search_button_classes ); ?>" data-bs-toggle="modal" data-bs-target="#site-search-modal">
		<span class="d-flex align-items-center">
			<svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" class="opacity-75 text-light">
				<path d="M14.386 14.386l4.0877 4.0877-4.0877-4.0877c-2.9418 2.9419-7.7115 2.9419-10.6533 0-2.9419-2.9418-2.9419-7.7115 0-10.6533 2.9418-2.9419 7.7115-2.9419 10.6533 0 2.9419 2.9418 2.9419 7.7115 0 10.6533z"
						stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
				</path>
			</svg>
			<span class="ps-3 pe-4 opacity-75 text-white">Search</span>
		</span>
		<span class="py-1 px-2 rounded-1 bg-black bg-opacity-50 opacity-75 d-flex justify-content-center align-items-center">
			<?php
			$kbd_button_classes = array(
				'bg-transparent',
				'text-white',
				// 'opacity-75',
				'font-gill-sans',
			);
			?>
			<kbd class="<?php echo implode( ' ', $kbd_button_classes ); ?>">⌘</kbd>
			<kbd class="<?php echo implode( ' ', $kbd_button_classes ); ?>">K</kbd>
		</span>
	</button>
</div>
