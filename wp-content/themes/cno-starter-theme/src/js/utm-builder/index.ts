/**
 * UTM Builder — entry point.
 *
 * This file is intentionally thin. Its only responsibilities are:
 *   1. Import the page stylesheet.
 *   2. Wire up DOM event listeners to the functions defined in feature modules.
 *   3. Seed default input values and perform the initial render.
 *
 * Feature logic lives in:
 *   - state.ts   — shared mutable state and DOM element references
 *   - types.ts   — TypeScript interfaces
 *   - utils.ts   — string sanitizers, URL builder, CSV escaper, status helper
 *   - mediums.ts — medium chip list (render / add / clear)
 *   - rows.ts    — UTM row table (render / move / generate / clear)
 *   - export.ts  — CSV download
 */
import '../../styles/pages/utm-builder.scss';

import { els } from './state';
import { addMedium, clearAllMediums, renderMediums } from './mediums';
import { generateRows, clearRows, renderRows } from './rows';
import { exportCsv } from './export';

// ---------------------------------------------------------------------------
// Event listeners
// ---------------------------------------------------------------------------

els.addMediumBtn.addEventListener( 'click', addMedium );
els.clearMediumsBtn.addEventListener( 'click', clearAllMediums );

// Allow the user to add a medium by pressing Enter inside the text input
// without also triggering any surrounding form submission.
els.newMedium.addEventListener( 'keydown', ( e ) => {
	if ( e.key === 'Enter' ) {
		e.preventDefault();
		addMedium();
	}
} );

els.generateBtn.addEventListener( 'click', generateRows );
els.exportBtn.addEventListener( 'click', exportCsv );
els.clearBtn.addEventListener( 'click', clearRows );

// ---------------------------------------------------------------------------
// Initial render
// ---------------------------------------------------------------------------

renderMediums();
renderRows();

// Pre-fill inputs with sensible defaults so users can generate rows immediately
// without having to type anything from scratch.
if ( ! els.siteUrl.value ) {
	els.siteUrl.value = 'https://choctawtravelplazas.com/';
}

if ( ! els.campaign.value ) {
	els.campaign.value = 'fy26-brand-campaign';
}

if ( ! els.vendor.value ) {
	els.vendor.value = 'townsquare-media';
}

if ( ! els.content.value ) {
	els.content.value = 'banner';
}
