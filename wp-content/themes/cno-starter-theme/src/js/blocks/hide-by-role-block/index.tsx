import React from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import metadata from './block.json';

registerBlockType( metadata.name, {
	edit: () => {
		console.log( 'hello from the hide by role block!' );
		return (
			<div { ...useBlockProps() }>
				<h2>hello from the hide by role block!</h2>
				<InnerBlocks />
			</div>
		);
	},
	save: () => (
		<div { ...useBlockProps.save() }>
			<InnerBlocks.Content />
		</div>
	),
} );
