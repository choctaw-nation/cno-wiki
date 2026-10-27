import wordpress from '@wordpress/eslint-plugin';
import { globalIgnores, includeIgnoreFile, defineConfig } from 'eslint/config';
import { fileURLToPath, URL } from 'url';

const gitignorePath = fileURLToPath( new URL( '.gitignore', import.meta.url ) );

export default defineConfig( [
	includeIgnoreFile( gitignorePath, 'Ignore .gitignore files' ),
	globalIgnores( [ 'wp-content/themes/cno-starter-theme/src/js/**/*.d.ts' ] ),
	...wordpress.configs.recommended,
	{
		files: [
			'wp-content/themes/cno-starter-theme/src/**/*.{js,ts,jsx,tsx}',
		],
		rules: {
			'jsdoc/require-param': 'off',
			'jsdoc/require-param-description': 'error',
			'jsdoc/require-param-name': 'off',
			'no-console': 'warn',
			'import/no-duplicates': 'error',
		},
	},
] );
