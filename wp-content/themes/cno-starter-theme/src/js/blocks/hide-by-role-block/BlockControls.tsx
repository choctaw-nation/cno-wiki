import { useEffect, useState } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { Panel, Flex, PanelBody, ToggleControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';

const ADMIN_ROLE_KEY = 'administrator';

export default function BlockControls( { attributes, setAttributes } ) {
	const [ allowedRoles, setAllowedRoles ] = useState(
		attributes.allowedRoles || [ 'all' ]
	);
	const { roles, isResolving } = useSelect( ( select ) => {
		const users = select( 'core' ).getUsers();
		const rolesArr = users?.map( ( user ) => user.roles ).flat();
		const rolesSet = new Set< string >( rolesArr );
		const roles = Array.from( rolesSet ).map( ( role ) => ( {
			label: prettyPrintKebabCase( role ),
			value: role,
		} ) );
		const isResolving = select( 'core' ).isResolving( 'getUsers' );
		return { roles, isResolving };
	}, [] );

	useEffect( () => {
		if ( allowedRoles.includes( 'all' ) ) {
			setAttributes( { allowedRoles } );
		} else {
			let allowed = allowedRoles;
			if ( ! allowed.includes( ADMIN_ROLE_KEY ) ) {
				allowed = [ ...allowed, ADMIN_ROLE_KEY ];
			}
			setAttributes( { allowedRoles: allowed } );
		}
	}, [ allowedRoles ] );

	const blockIsPublic = allowedRoles.includes( 'all' );
	const blockCheckedStatus = ( value ) =>
		allowedRoles.includes( value ) ||
		blockIsPublic ||
		value === ADMIN_ROLE_KEY ||
		allowedRoles.includes( 'logged-in' );

	function handleChange( bool: boolean, value: string ) {
		if ( 'all' === value ) {
			setAllowedRoles( ( prev ) =>
				bool ? [ 'all' ] : prev.filter( ( role ) => role !== 'all' )
			);
		} else {
			setAllowedRoles( ( prev ) =>
				bool
					? [ ...prev, value ]
					: prev.filter( ( role ) => role !== value )
			);
		}
	}

	return (
		<InspectorControls>
			{ ! isResolving && (
				<Panel>
					<PanelBody title="Content Visibility">
						<Flex direction="column">
							<p>
								Hide/Show some content based on logged-in / user
								role statuses.
							</p>
							<ToggleControl
								__nextHasNoMarginBottom
								checked={ blockIsPublic }
								label={ `${
									blockIsPublic ? 'Public' : 'Private'
								}` }
								help="Toggling this “Off” enables role-based viewing permissions"
								onChange={ ( v: boolean ) =>
									handleChange( v, 'all' )
								}
							/>
						</Flex>
					</PanelBody>
					{ roles.length && ! blockIsPublic && (
						<PanelBody title="Visibility by Role">
							<Flex direction="column">
								<ToggleControl
									__nextHasNoMarginBottom
									checked={ blockCheckedStatus(
										'logged-in'
									) }
									label="All Logged In"
									help="Anyone who is logged in can view this"
									onChange={ ( v: boolean ) =>
										handleChange( v, 'logged-in' )
									}
								/>
								{ roles.map( ( { label, value } ) => (
									<ToggleControl
										key={ value }
										__nextHasNoMarginBottom
										checked={ blockCheckedStatus( value ) }
										disabled={ value === ADMIN_ROLE_KEY }
										help={
											value === ADMIN_ROLE_KEY
												? 'Administrators can always view all blocks.'
												: ''
										}
										label={ label }
										onChange={ ( v: boolean ) =>
											handleChange( v, value )
										}
									/>
								) ) }
							</Flex>
						</PanelBody>
					) }
				</Panel>
			) }
		</InspectorControls>
	);
}

/**
 * Converts a kebab-case string to a pretty printed string.
 * Example: 'editor-role' -> 'Editor Role'
 */
function prettyPrintKebabCase( str: string ): string {
	return str
		.split( '-' )
		.map( ( word ) => word.charAt( 0 ).toUpperCase() + word.slice( 1 ) )
		.join( ' ' );
}
