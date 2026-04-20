import { rows, els } from './state';
import { csvEscape, sanitizeCampaign, setStatus } from './utils';

/**
 * Serializes the current rows array to a CSV file and triggers a browser download.
 *
 * Column order: Long URL, Channel, Campaign, Vendor.
 *
 * Implementation notes:
 * - A Blob is used so no server round-trip is needed.
 * - The object URL is revoked after 1 second to free browser memory while still
 *   giving the browser enough time to initiate the download.
 * - The download filename is derived from the sanitized campaign input so that
 *   exported files are self-descriptive (e.g. "fy26-brand-campaign.csv").
 * - A temporary <a> element is appended to the DOM, clicked programmatically,
 *   then immediately removed — the standard cross-browser download trigger.
 */
export function exportCsv(): void {
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

	// Revoke the object URL after the browser has had time to begin the download.
	window.setTimeout( () => {
		URL.revokeObjectURL( objectUrl );
	}, 1000 );

	setStatus( 'CSV exported.' );
}
