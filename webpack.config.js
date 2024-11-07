const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

/** The name of the theme. Alter me! */
const THEME_NAME = 'cno-starter-theme';

/** The location of your theme. */
const THEME_DIR = `/wp-content/themes/${ THEME_NAME }`;

/**
 * Array of strings modeled after folder names (e.g. 'about-choctaw'). Inside of these folders, an `index.ts` file is expected. If that's not what you want, consider editing the `addEntries` function below.
 *
 * **Be sure to import page scss in these files**
 */
const appNames = [];

/**
 * For SCSS files (no leading `_`)
 * Array of strings modeled after scss names (e.g. 'we-are-choctaw')
 */
const styleSheets = []; // for scss only

module.exports = {
	...defaultConfig,
	...{
		entry: () => {
			return {
				global: `.${ THEME_DIR }/src/index.js`,
				'vendors/bootstrap': `.${ THEME_DIR }/src/js/vendors/bootstrap.js`,
				'modules/post-swiper': `.${ THEME_DIR }/src/js/PostSwiper.ts`,
				...addEntries( appNames, 'pages' ),
				...addEntries( styleSheets, 'styles' ),
			};
		},

		output: {
			path: __dirname + `${ THEME_DIR }/dist`,
			filename: `[name].js`,
		},
	},
};

/**
 * Helper function to add entries to the entries object. It takes an array of strings in either kebab-case or snake_case and returns an object with the key as the entry name and the value as the path to the entry file.
 * @param {array} array - Array of strings
 * @param {string} type - The type of entry. Either 'pages' or 'styles'
 */
function addEntries( array, type ) {
	if ( ! Array.isArray( array ) ) {
		throw new Error( `Expecting an array, received ${ typeof array }!` );
	}
	if ( 0 >= array.length ) {
		return {};
	}
	const entries = {};
	array.forEach( ( asset ) => {
		const assetOutput = snakeToCamel( asset );
		if ( type === 'styles' ) {
			entries[
				`pages/${ assetOutput }`
			] = `.${ THEME_DIR }/src/styles/pages/${ asset }.scss`;
		} else if ( type === 'pages' ) {
			entries[
				`pages/${ assetOutput }`
			] = `.${ THEME_DIR }/src/js/${ asset }/index.ts`;
		} else {
			throw new Error(
				`Invalid type! Expected "styles" or "pages", received "${ type }"`
			);
		}
	} );
	return entries;
}

/** A simple utility class to alter strings from kebab-case or snake_case to camelCase
 *
 * @param {string} str - The string to be converted
 */
function snakeToCamel( str ) {
	return str.replace( /([-_][a-z])/g, ( group ) =>
		group.toUpperCase().replace( '-', '' ).replace( '_', '' )
	);
}
