<?php
/**
 * Template Name: UTM Form Builder
 *
 * @package ChoctawNation
 */


use ChoctawNation\Asset_Loader;
use ChoctawNation\Enqueue_Type;

$loader = new Asset_Loader( 'utmBuilder', Enqueue_Type::both, 'pages' );
get_header();
?>

<div class="container-xxl py-4">
	<h1><?php the_title(); ?></h1>

	<div class="card card-body mb-4">
		<h2 class="card-title">Base Details</h2>
		<div class="row g-3">
			<div class="col-12 col-md-4">
				<label for="siteUrl" class="form-label fw-semibold">Site URL</label>
				<input id="siteUrl" type="url" class="form-control" placeholder="https://choctawtravelplazas.com/" />
			</div>
			<div class="col-12 col-md-4">
				<label for="campaign" class="form-label fw-semibold">Campaign</label>
				<input id="campaign" type="text" class="form-control" placeholder="fy26-brand-campaign" />
			</div>
			<div class="col-12 col-md-4">
				<label for="vendor" class="form-label fw-semibold">Vendor</label>
				<input id="vendor" type="text" class="form-control" placeholder="townsquare-media" />
			</div>
		</div>

		<div class="d-flex flex-wrap gap-2 mt-3">
			<button id="generateBtn" class="btn btn-primary">Generate Rows for All Mediums</button>
			<button id="exportBtn" class="btn btn-secondary">Export CSV</button>
			<button id="clearBtn" class="btn btn-danger">Clear Rows</button>
		</div>
		<p class="text-muted small mt-1 mb-0">Generates new rows at the end only. Existing rows stay in place.</p>
		<div id="status" class="text-success fw-bold mt-2" style="min-height:20px"></div>
	</div>

	<div class="card card-body mb-4">
		<h2 class="card-title">Included Mediums</h2>
		<div class="row g-3 align-items-end mb-3">
			<div class="col">
				<label for="newMedium" class="form-label fw-semibold">Add Medium</label>
				<input id="newMedium" type="text" class="form-control" placeholder="example: ctv" />
				<p class="text-muted small mt-1 mb-0">You can add more mediums and remove any medium from the list below.</p>
			</div>
			<div class="col-auto">
				<div class="d-flex gap-2">
					<button id="addMediumBtn" class="btn btn-primary">Add Medium</button>
					<button id="clearMediumsBtn" class="btn btn-danger">Remove All</button>
				</div>
			</div>
		</div>
		<div id="mediumList" class="d-flex flex-wrap gap-2 mt-3"></div>
	</div>

	<div class="card card-body mb-4">
		<h2 class="card-title">Rows</h2>
		<p class="text-muted small mt-1 mb-0">Vendor, Channel, and Campaign are editable per row. Long URL updates automatically when those fields change. Drag rows by the handle to reorder
			them.</p>
		<div class="table-responsive mt-3">
			<table class="table table-bordered align-middle utm-table">
				<thead>
					<tr>
						<th class="col-1" scope="col">Drag</th>
						<th class="col" scope="col">Vendor</th>
						<th class="col" scope="col">Channel</th>
						<th class="col" scope="col">Campaign</th>
						<th class="col" scope="col">Long URL</th>
						<th class="col" scope="col">Remove Row</th>
					</tr>
				</thead>
				<tbody id="rowsBody"></tbody>
			</table>
		</div>
		<p class="small text-muted mt-2 mb-0">Exported CSV columns: Long URL, Channel, Campaign, Vendor</p>
	</div>
</div>




<?php
get_footer();