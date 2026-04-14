import '../../styles/pages/utm-builder.scss';
const defaultMediums = [ 'youtube', 'ott', 'sem', 'display', 'rgd', 'audio' ];
let mediums = [ ...defaultMediums ];
let rows = [];

const els = {
	siteUrl: document.getElementById( 'siteUrl' ),
	campaign: document.getElementById( 'campaign' ),
	vendor: document.getElementById( 'vendor' ),
	newMedium: document.getElementById( 'newMedium' ),
	mediumList: document.getElementById( 'mediumList' ),
	rowsBody: document.getElementById( 'rowsBody' ),
	status: document.getElementById( 'status' ),
	generateBtn: document.getElementById( 'generateBtn' ),
	addMediumBtn: document.getElementById( 'addMediumBtn' ),
	clearMediumsBtn: document.getElementById( 'clearMediumsBtn' ),
	exportBtn: document.getElementById( 'exportBtn' ),
	clearBtn: document.getElementById( 'clearBtn' ),
};

function normalizeValue( value ) {
	return String( value || '' ).trim();
}

function sanitizeMedium( value ) {
	return normalizeValue( value ).toLowerCase().replace( /\s+/g, '-' );
}

function sanitizeVendor( value ) {
	return normalizeValue( value ).toLowerCase().replace( /\s+/g, '-' );
}

function sanitizeCampaign( value ) {
	return normalizeValue( value ).toLowerCase().replace( /\s+/g, '-' );
}

function buildLongUrl( siteUrl, vendor, channel, campaign ) {
	const cleanSite = normalizeValue( siteUrl );
	if ( ! cleanSite ) return '';
	const url = new URL( cleanSite );
	url.searchParams.set( 'utm_source', sanitizeVendor( vendor ) );
	url.searchParams.set( 'utm_medium', sanitizeMedium( channel ) );
	url.searchParams.set( 'utm_campaign', sanitizeCampaign( campaign ) );
	return url.toString();
}

function renderMediums() {
	els.mediumList.innerHTML = '';
	mediums.forEach( ( medium, index ) => {
		const chip = document.createElement( 'div' );
		chip.className =
			'd-inline-flex align-items-center gap-2 border px-2 py-1 rounded-pill small';

		const text = document.createElement( 'span' );
		text.textContent = medium;

		const removeBtn = document.createElement( 'button' );
		removeBtn.type = 'button';
		removeBtn.className =
			'btn btn-link p-0 fw-bold lh-1 text-danger text-decoration-none';
		removeBtn.textContent = '×';
		removeBtn.title = 'Remove ' + medium;
		removeBtn.setAttribute( 'aria-label', 'Remove ' + medium );
		removeBtn.addEventListener( 'click', () => {
			mediums.splice( index, 1 );
			renderMediums();
			setStatus( 'Removed medium: ' + medium );
		} );

		chip.appendChild( text );
		chip.appendChild( removeBtn );
		els.mediumList.appendChild( chip );
	} );

	if ( mediums.length === 0 ) {
		const empty = document.createElement( 'div' );
		empty.className = 'text-muted small';
		empty.textContent = 'No mediums included yet. Add one above.';
		els.mediumList.appendChild( empty );
	}
}

function moveRowUp( index ) {
	if ( index <= 0 ) return;
	const temp = rows[ index - 1 ];
	rows[ index - 1 ] = rows[ index ];
	rows[ index ] = temp;
	renderRows();
	setStatus( 'Row moved up.' );
}

function moveRowDown( index ) {
	if ( index >= rows.length - 1 ) return;
	const temp = rows[ index + 1 ];
	rows[ index + 1 ] = rows[ index ];
	rows[ index ] = temp;
	renderRows();
	setStatus( 'Row moved down.' );
}

let dragSourceIndex = null;

function moveRow( fromIndex, toIndex ) {
	if ( fromIndex === null || toIndex === null ) return;
	if ( fromIndex === toIndex ) return;
	if ( fromIndex < 0 || toIndex < 0 ) return;
	if ( fromIndex >= rows.length || toIndex >= rows.length ) return;

	const movedRow = rows.splice( fromIndex, 1 )[ 0 ];
	rows.splice( toIndex, 0, movedRow );
	renderRows();
	setStatus( 'Row reordered.' );
}

function renderRows() {
	els.rowsBody.innerHTML = '';

	rows.forEach( ( row, index ) => {
		const tr = document.createElement( 'tr' );

		const vendorTd = document.createElement( 'td' );
		vendorTd.dataset.label = 'Vendor';
		const vendorInput = document.createElement( 'input' );
		vendorInput.type = 'text';
		vendorInput.className = 'form-control';
		vendorInput.value = row.vendor;
		vendorInput.addEventListener( 'change', ( e ) => {
			rows[ index ].vendor = e.target.value;
			rows[ index ].longUrl = buildLongUrl(
				rows[ index ].siteUrl,
				rows[ index ].vendor,
				rows[ index ].channel,
				rows[ index ].campaign
			);
			renderRows();
		} );
		vendorTd.appendChild( vendorInput );

		const channelTd = document.createElement( 'td' );
		channelTd.dataset.label = 'Channel';
		const channelInput = document.createElement( 'input' );
		channelInput.type = 'text';
		channelInput.className = 'form-control';
		channelInput.value = row.channel;
		channelInput.addEventListener( 'change', ( e ) => {
			rows[ index ].channel = e.target.value;
			rows[ index ].longUrl = buildLongUrl(
				rows[ index ].siteUrl,
				rows[ index ].vendor,
				rows[ index ].channel,
				rows[ index ].campaign
			);
			renderRows();
		} );
		channelTd.appendChild( channelInput );

		const campaignTd = document.createElement( 'td' );
		campaignTd.dataset.label = 'Campaign';
		const campaignInput = document.createElement( 'input' );
		campaignInput.type = 'text';
		campaignInput.className = 'form-control';
		campaignInput.value = row.campaign;
		campaignInput.addEventListener( 'change', ( e ) => {
			rows[ index ].campaign = e.target.value;
			rows[ index ].longUrl = buildLongUrl(
				rows[ index ].siteUrl,
				rows[ index ].vendor,
				rows[ index ].channel,
				rows[ index ].campaign
			);
			renderRows();
		} );
		campaignTd.appendChild( campaignInput );

		const urlTd = document.createElement( 'td' );
		urlTd.dataset.label = 'Long URL';
		const urlDisplay = document.createElement( 'div' );
		urlDisplay.className = 'url-display form-control';
		urlDisplay.textContent = row.longUrl;
		urlTd.appendChild( urlDisplay );

		const dragTd = document.createElement( 'td' );
		const dragHandle = document.createElement( 'span' );
		dragHandle.className =
			'drag-handle d-none d-md-inline-block border rounded p-2 fs-5 lh-1';
		dragHandle.textContent = '⋮⋮';
		dragHandle.title = 'Drag to reorder';
		dragHandle.setAttribute( 'aria-hidden', 'true' );
		dragTd.appendChild( dragHandle );

		const upBtn = document.createElement( 'button' );
		upBtn.type = 'button';
		upBtn.className = 'btn btn-outline-secondary btn-sm d-md-none';
		upBtn.textContent = '↑';
		upBtn.setAttribute( 'aria-label', 'Move row up' );
		upBtn.addEventListener( 'click', () => moveRowUp( index ) );

		const downBtn = document.createElement( 'button' );
		downBtn.type = 'button';
		downBtn.className = 'btn btn-outline-secondary btn-sm d-md-none';
		downBtn.textContent = '↓';
		downBtn.setAttribute( 'aria-label', 'Move row down' );
		downBtn.addEventListener( 'click', () => moveRowDown( index ) );

		const reorderBtns = document.createElement( 'div' );
		reorderBtns.className = 'd-flex flex-column gap-1 d-md-none';
		reorderBtns.appendChild( upBtn );
		reorderBtns.appendChild( downBtn );
		dragTd.appendChild( reorderBtns );

		tr.draggable = false;
		dragHandle.addEventListener( 'mousedown', () => {
			tr.draggable = true;
		} );
		tr.addEventListener( 'dragstart', () => {
			dragSourceIndex = index;
			tr.classList.add( 'opacity-50' );
		} );
		tr.addEventListener( 'dragend', () => {
			tr.draggable = false;
			tr.classList.remove( 'opacity-50' );
			tr.classList.remove( 'drag-over' );
		} );
		tr.addEventListener( 'dragover', ( e ) => {
			e.preventDefault();
			tr.classList.add( 'drag-over' );
		} );
		tr.addEventListener( 'dragleave', () => {
			tr.classList.remove( 'drag-over' );
		} );
		tr.addEventListener( 'drop', ( e ) => {
			e.preventDefault();
			tr.classList.remove( 'drag-over' );
			moveRow( dragSourceIndex, index );
		} );

		const removeTd = document.createElement( 'td' );
		removeTd.dataset.label = 'Remove Row';
		const removeBtn = document.createElement( 'button' );
		removeBtn.type = 'button';
		removeBtn.className = 'btn btn-danger btn-sm';
		removeBtn.textContent = 'Remove';
		removeBtn.addEventListener( 'click', () => {
			rows.splice( index, 1 );
			renderRows();
			setStatus( 'Row removed.' );
		} );
		removeTd.appendChild( removeBtn );

		tr.appendChild( dragTd );
		tr.appendChild( vendorTd );
		tr.appendChild( channelTd );
		tr.appendChild( campaignTd );
		tr.appendChild( urlTd );
		tr.appendChild( removeTd );

		els.rowsBody.appendChild( tr );
	} );

	if ( rows.length === 0 ) {
		const tr = document.createElement( 'tr' );
		const td = document.createElement( 'td' );
		td.colSpan = 6;
		td.className = 'text-muted fst-italic';
		td.textContent =
			'No rows yet. Use Generate Rows for All Mediums to append rows.';
		tr.appendChild( td );
		els.rowsBody.appendChild( tr );
	}
}

function setStatus( message ) {
	els.status.textContent = message || '';
}

function clearAllMediums() {
	if ( mediums.length === 0 ) {
		setStatus( 'No mediums to remove.' );
		return;
	}
	mediums = [];
	renderMediums();
	setStatus( 'All mediums removed.' );
}

function addMedium() {
	const raw = els.newMedium.value;
	const medium = sanitizeMedium( raw );
	if ( ! medium ) {
		setStatus( 'Enter a medium first.' );
		return;
	}
	if ( mediums.includes( medium ) ) {
		setStatus( 'Medium already exists: ' + medium );
		return;
	}
	mediums.push( medium );
	els.newMedium.value = '';
	renderMediums();
	setStatus( 'Added medium: ' + medium );
}

function generateRows() {
	const siteUrl = normalizeValue( els.siteUrl.value );
	const campaign = sanitizeCampaign( els.campaign.value );
	const vendor = sanitizeVendor( els.vendor.value );

	if ( ! siteUrl || ! campaign || ! vendor ) {
		setStatus( 'Please enter Site URL, Campaign, and Vendor.' );
		return;
	}

	try {
		new URL( siteUrl );
	} catch ( e ) {
		setStatus( 'Please enter a valid Site URL.' );
		return;
	}

	if ( mediums.length === 0 ) {
		setStatus( 'Add at least one medium.' );
		return;
	}

	const newRows = mediums.map( ( medium ) => ( {
		siteUrl: siteUrl,
		vendor: vendor,
		channel: medium,
		campaign: campaign,
		longUrl: buildLongUrl( siteUrl, vendor, medium, campaign ),
	} ) );

	rows = rows.concat( newRows );
	renderRows();
	setStatus( 'Added ' + newRows.length + ' row(s) to the end.' );
}

function csvEscape( value ) {
	const stringValue = String( value == null ? '' : value );
	if (
		stringValue.includes( '"' ) ||
		stringValue.includes( ',' ) ||
		stringValue.includes( '\n' )
	) {
		return '"' + stringValue.replace( /"/g, '""' ) + '"';
	}
	return stringValue;
}

function exportCsv() {
	if ( rows.length === 0 ) {
		setStatus( 'No rows to export.' );
		return;
	}

	const headers = [ 'Long URL', 'Channel', 'Campaign', 'Vendor' ];
	const lines = [ headers.join( ',' ) ];

	rows.forEach( ( row ) => {
		lines.push(
			[
				csvEscape( row.longUrl ),
				csvEscape( row.channel ),
				csvEscape( row.campaign ),
				csvEscape( row.vendor ),
			].join( ',' )
		);
	} );

	const blob = new Blob( [ lines.join( '\n' ) ], {
		type: 'text/csv;charset=utf-8;',
	} );
	const objectUrl = URL.createObjectURL( blob );
	const link = document.createElement( 'a' );
	const baseName = sanitizeCampaign( els.campaign.value ) || 'utm-export';
	link.href = objectUrl;
	link.download = baseName + '.csv';
	document.body.appendChild( link );
	link.click();
	document.body.removeChild( link );
	window.setTimeout( () => {
		URL.revokeObjectURL( objectUrl );
	}, 1000 );
	setStatus( 'CSV exported.' );
}

function clearRows() {
	rows = [];
	renderRows();
	setStatus( 'All rows cleared.' );
}

els.addMediumBtn.addEventListener( 'click', addMedium );
els.clearMediumsBtn.addEventListener( 'click', clearAllMediums );
els.newMedium.addEventListener( 'keydown', ( e ) => {
	if ( e.key === 'Enter' ) {
		e.preventDefault();
		addMedium();
	}
} );
els.generateBtn.addEventListener( 'click', generateRows );
els.exportBtn.addEventListener( 'click', exportCsv );
els.clearBtn.addEventListener( 'click', clearRows );

renderMediums();
renderRows();

if ( ! els.siteUrl.value ) {
	els.siteUrl.value = 'https://choctawtravelplazas.com/';
}

if ( ! els.campaign.value ) {
	els.campaign.value = 'fy26-brand-campaign';
}

if ( ! els.vendor.value ) {
	els.vendor.value = 'townsquare-media';
}
