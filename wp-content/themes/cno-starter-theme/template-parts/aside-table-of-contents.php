<?php
/**
 * The Table of Contents Sidebar
 *
 * @package ChoctawNation
 */

?>
<aside class="docs-toc-sidebar my-3 my-lg-0 mb-lg-5 px-sm-1 text-body-secondary">
	<button class="btn btn-link p-md-0 mb-0 text-decoration-none d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#tocContents" aria-expanded="false"
			aria-controls="tocContents">
		On this page
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-expand d-md-none ms-2" viewBox="0 0 16 16">
			<path fill-rule="evenodd"
				  d="M3.646 9.146a.5.5 0 0 1 .708 0L8 12.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708m0-2.292a.5.5 0 0 0 .708 0L8 3.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708" />
		</svg>
	</button>
	<strong class="d-none d-md-block h6 my-2 ms-3">On this page</strong>
	<hr class="d-none d-md-block my-2 ms-3">
	<div class="collapse docs-toc-sidebar-collapse" id="tocContents">
		<nav id="table-of-contents">
			<ul class="list-group"></ul>
		</nav>
	</div>
</aside>