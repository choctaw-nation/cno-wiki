import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

import metadata from './block.json';

import BlockControls from './BlockControls';

registerBlockType( metadata.name, {
	edit: ( { attributes, setAttributes } ) => {
		return (
			<>
				<BlockControls
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>
				<div
					{ ...useInnerBlocksProps( useBlockProps(), {
						template: [
							[
								'core/paragraph',
								{
									placeholder:
										'Start typing here....this content will be hidden!',
								},
							],
						],
					} ) }
				/>
			</>
		);
	},
	save: () => <div { ...useInnerBlocksProps.save( useBlockProps.save() ) } />,
} );
