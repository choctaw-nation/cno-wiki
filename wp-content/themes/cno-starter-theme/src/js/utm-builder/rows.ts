import {
	rows,
	setRows,
	setDragSourceIndex,
	dragSourceIndex,
	mediums,
	els,
} from './state';
import {
	buildLongUrl,
	sanitizeCampaign,
	sanitizeVendor,
	sanitizeContent,
	normalizeValue,
	setStatus,
} from './utils';

// ---------------------------------------------------------------------------
// Row reordering helpers
// ---------------------------------------------------------------------------

/**
 * Swaps a row one position upward in the rows array and re-renders the table.
 * Does nothing when the row is already at the top (index 0).
 */
export function moveRowUp( index: number ): void {
	if ( index <= 0 ) return;
	const temp = rows[ index - 1 ];
	rows[ index - 1 ] = rows[ index ];
	rows[ index ] = temp;
	renderRows();
	setStatus( 'Row moved up.' );
}

/**
 * Swaps a row one position downward in the rows array and re-renders the table.
 * Does nothing when the row is already at the bottom.
 */
export function moveRowDown( index: number ): void {
	if ( index >= rows.length - 1 ) return;
	const temp = rows[ index + 1 ];
	rows[ index + 1 ] = rows[ index ];
	rows[ index ] = temp;
	renderRows();
	setStatus( 'Row moved down.' );
}

/**
 * Moves a row from `fromIndex` to `toIndex` via splice/insert.
 * Used by drag-and-drop to reorder rows without buttons.
 * Silently returns when either index is null, equal, or out of bounds.
 */
export function moveRow(
	fromIndex: number | null,
	toIndex: number | null
): void {
	if ( fromIndex === null || toIndex === null ) return;
	if ( fromIndex === toIndex ) return;
	if ( fromIndex < 0 || toIndex < 0 ) return;
	if ( fromIndex >= rows.length || toIndex >= rows.length ) return;

	const movedRow = rows.splice( fromIndex, 1 )[ 0 ];
	rows.splice( toIndex, 0, movedRow );
	renderRows();
	setStatus( 'Row reordered.' );
}

// ---------------------------------------------------------------------------
// Table rendering
// ---------------------------------------------------------------------------

/**
 * Rebuilds the entire rows table from the current `rows` array.
 * Each row gets inline inputs for vendor, channel, and campaign that
 * recompute the long URL on change. Drag handles and up/down buttons
 * are rendered for desktop and mobile reordering respectively.
 * Displays an empty-state row when no rows exist.
 */
export function renderRows(): void {
	els.rowsBody.innerHTML = '';

	rows.forEach( ( row, index ) => {
		const tr = document.createElement( 'tr' );

		// --- Vendor cell ---
		const vendorTd = document.createElement( 'td' );
		vendorTd.dataset.label = 'Vendor';
		const vendorInput = document.createElement( 'input' );
		vendorInput.type = 'text';
		vendorInput.className = 'form-control';
		vendorInput.value = row.vendor;
		vendorInput.addEventListener( 'change', ( e ) => {
			rows[ index ].vendor = ( e.target as HTMLInputElement ).value;
			rows[ index ].longUrl = buildLongUrl(
				rows[ index ].siteUrl,
				rows[ index ].vendor,
				rows[ index ].channel,
				rows[ index ].campaign,
				rows[ index ].content
			);
			renderRows();
		} );
		vendorTd.appendChild( vendorInput );

		// --- Channel cell ---
		const channelTd = document.createElement( 'td' );
		channelTd.dataset.label = 'Channel';
		const channelInput = document.createElement( 'input' );
		channelInput.type = 'text';
		channelInput.className = 'form-control';
		channelInput.value = row.channel;
		channelInput.addEventListener( 'change', ( e ) => {
			rows[ index ].channel = ( e.target as HTMLInputElement ).value;
			rows[ index ].longUrl = buildLongUrl(
				rows[ index ].siteUrl,
				rows[ index ].vendor,
				rows[ index ].channel,
				rows[ index ].campaign,
				rows[ index ].content
			);
			renderRows();
		} );
		channelTd.appendChild( channelInput );

		// --- Campaign cell ---
		const campaignTd = document.createElement( 'td' );
		campaignTd.dataset.label = 'Campaign';
		const campaignInput = document.createElement( 'input' );
		campaignInput.type = 'text';
		campaignInput.className = 'form-control';
		campaignInput.value = row.campaign;
		campaignInput.addEventListener( 'change', ( e ) => {
			rows[ index ].campaign = ( e.target as HTMLInputElement ).value;
			rows[ index ].longUrl = buildLongUrl(
				rows[ index ].siteUrl,
				rows[ index ].vendor,
				rows[ index ].channel,
				rows[ index ].campaign,
				rows[ index ].content
			);
			renderRows();
		} );
		campaignTd.appendChild( campaignInput );

		// --- Content cell ---
		const contentTd = document.createElement( 'td' );
		contentTd.dataset.label = 'Content';
		const contentInput = document.createElement( 'input' );
		contentInput.type = 'text';
		contentInput.className = 'form-control';
		contentInput.value = row.content;
		contentInput.addEventListener( 'change', ( e ) => {
			rows[ index ].content = ( e.target as HTMLInputElement ).value;
			rows[ index ].longUrl = buildLongUrl(
				rows[ index ].siteUrl,
				rows[ index ].vendor,
				rows[ index ].channel,
				rows[ index ].campaign,
				rows[ index ].content
			);
			renderRows();
		} );
		contentTd.appendChild( contentInput );

		// --- Long URL cell (read-only display) ---
		const urlTd = document.createElement( 'td' );
		urlTd.dataset.label = 'Long URL';
		const urlDisplay = document.createElement( 'div' );
		urlDisplay.className = 'url-display form-control';
		urlDisplay.textContent = row.longUrl;
		urlTd.appendChild( urlDisplay );

		// --- Drag / reorder cell ---
		const dragTd = document.createElement( 'td' );

		// Desktop: drag handle (hidden on small screens)
		const dragHandle = document.createElement( 'span' );
		dragHandle.className =
			'drag-handle d-none d-md-inline-block border rounded p-2 fs-5 lh-1';
		dragHandle.textContent = '⋮⋮';
		dragHandle.title = 'Drag to reorder';
		dragHandle.setAttribute( 'aria-hidden', 'true' );
		dragTd.appendChild( dragHandle );

		// Mobile: up/down buttons (hidden on medium+ screens)
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

		// Drag-and-drop: only becomes draggable after mousedown on the handle
		// to avoid accidental drags when clicking inputs.
		tr.draggable = false;
		dragHandle.addEventListener( 'mousedown', () => {
			tr.draggable = true;
		} );
		tr.addEventListener( 'dragstart', () => {
			setDragSourceIndex( index );
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

		// --- Remove cell ---
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
		tr.appendChild( contentTd );
		tr.appendChild( urlTd );
		tr.appendChild( removeTd );

		els.rowsBody.appendChild( tr );
	} );

	// Empty-state row
	if ( rows.length === 0 ) {
		const tr = document.createElement( 'tr' );
		const td = document.createElement( 'td' );
		td.colSpan = 7;
		td.className = 'text-muted fst-italic';
		td.textContent =
			'No rows yet. Use Generate Rows for All Mediums to append rows.';
		tr.appendChild( td );
		els.rowsBody.appendChild( tr );
	}
}

// ---------------------------------------------------------------------------
// Row generation / clearing
// ---------------------------------------------------------------------------

/**
 * Reads the Site URL, Campaign, and Vendor inputs and generates one UTM row
 * per medium in the current mediums list. New rows are appended to any
 * existing rows (they do not replace them).
 * Validates inputs and shows appropriate status messages on failure.
 */
export function generateRows(): void {
	const siteUrl = normalizeValue( els.siteUrl.value );
	const campaign = sanitizeCampaign( els.campaign.value );
	const vendor = sanitizeVendor( els.vendor.value );
	const content = sanitizeContent( els.content?.value || '' );

	if ( ! siteUrl || ! campaign || ! vendor ) {
		setStatus( 'Please enter Site URL, Campaign, and Vendor.' );
		return;
	}

	try {
		new URL( siteUrl );
	} catch {
		setStatus( 'Please enter a valid Site URL.' );
		return;
	}

	if ( mediums.length === 0 ) {
		setStatus( 'Add at least one medium.' );
		return;
	}

	const newRows = mediums.map( ( medium ) => ( {
		siteUrl,
		vendor,
		channel: medium,
		campaign,
		content,
		longUrl: buildLongUrl( siteUrl, vendor, medium, campaign, content ),
	} ) );

	setRows( rows.concat( newRows ) );
	renderRows();
	setStatus( 'Added ' + newRows.length + ' row(s) to the end.' );
}

/**
 * Removes all rows from the table and refreshes the display.
 */
export function clearRows(): void {
	setRows( [] );
	renderRows();
	setStatus( 'All rows cleared.' );
}
