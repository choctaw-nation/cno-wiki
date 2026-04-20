import { mediums, setMediums, els } from './state';
import { sanitizeMedium, setStatus } from './utils';

/**
 * Re-renders the medium chip list from the current `mediums` array.
 * Each chip displays the medium name and a remove button.
 * Shows an empty-state message when no mediums are present.
 */
export function renderMediums(): void {
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

/**
 * Reads the new-medium input, sanitizes it, and appends it to the mediums list.
 * Rejects empty values and duplicates, providing status feedback in both cases.
 */
export function addMedium(): void {
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

/**
 * Removes all mediums from the list and refreshes the chip display.
 * Shows a status message if the list was already empty.
 */
export function clearAllMediums(): void {
	if ( mediums.length === 0 ) {
		setStatus( 'No mediums to remove.' );
		return;
	}
	setMediums( [] );
	renderMediums();
	setStatus( 'All mediums removed.' );
}
