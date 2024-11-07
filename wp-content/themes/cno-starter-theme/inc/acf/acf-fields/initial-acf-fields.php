<?php
/**
 * Sets up Global Hero Section for Pages.
 * See also: `initial-acf-fields.json` for importable fields (for use with ACF GUI)
 *
 * @package ChoctawNation
 * @subpackage ACF
 * @since 1.9
 */

add_action(
	'acf/include_fields',
	function () {
		if ( ! function_exists( 'acf_add_local_field_group' ) ) {
			return;
		}
		acf_add_local_field_group(
			array(
				'key'                   => 'group_6423001144e71',
				'title'                 => 'Page Template Options - Default Template',
				'fields'                => array(
					array(
						'key'               => 'field_63e674b076c37',
						'label'             => 'Section 1 (Hero)',
						'name'              => 'hero',
						'aria-label'        => '',
						'type'              => 'group',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'layout'            => 'block',
						'sub_fields'        => array(
							array(
								'key'               => 'field_63e674d176c38',
								'label'             => 'Headline',
								'name'              => 'headline',
								'aria-label'        => '',
								'type'              => 'text',
								'instructions'      => '',
								'required'          => 1,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'maxlength'         => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_63e674db76c39',
								'label'             => 'Subheadline',
								'name'              => 'subheadline',
								'aria-label'        => '',
								'type'              => 'textarea',
								'instructions'      => 'New lines ignored',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'default_value'     => '',
								'maxlength'         => '',
								'placeholder'       => '',
								'prepend'           => '',
								'append'            => '',
							),
							array(
								'key'               => 'field_63e674ea76c3a',
								'label'             => 'Enable Section Background Image?',
								'name'              => 'has_background_image',
								'aria-label'        => '',
								'type'              => 'true_false',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'message'           => 'Check to add background image',
								'default_value'     => 1,
								'ui'                => 0,
								'ui_on_text'        => '',
								'ui_off_text'       => '',
							),
							array(
								'key'               => 'field_63e6751b76c3b',
								'label'             => 'Background Image',
								'name'              => 'background_image',
								'aria-label'        => '',
								'type'              => 'image',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_63e674ea76c3a',
											'operator' => '==',
											'value'    => '1',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'return_format'     => 'array',
								'library'           => 'all',
								'min_width'         => '',
								'min_height'        => '',
								'min_size'          => '',
								'max_width'         => '',
								'max_height'        => '',
								'max_size'          => '',
								'mime_types'        => '',
								'preview_size'      => 'medium',
							),
							array(
								'key'               => 'field_63e676137dce1',
								'label'             => 'Enable Call-to-Action Button?',
								'name'              => 'has_cta',
								'aria-label'        => '',
								'type'              => 'true_false',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => 0,
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'message'           => 'Check to enable CTA button in Hero section',
								'default_value'     => 1,
								'ui'                => 0,
								'ui_on_text'        => '',
								'ui_off_text'       => '',
							),
							array(
								'key'               => 'field_63e6764d7dce2',
								'label'             => 'CTA',
								'name'              => 'cta',
								'aria-label'        => '',
								'type'              => 'link',
								'instructions'      => '',
								'required'          => 0,
								'conditional_logic' => array(
									array(
										array(
											'field'    => 'field_63e676137dce1',
											'operator' => '==',
											'value'    => '1',
										),
									),
								),
								'wrapper'           => array(
									'width' => '',
									'class' => '',
									'id'    => '',
								),
								'return_format'     => 'array',
							),
						),
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
						array(
							'param'    => 'page_template',
							'operator' => '!=',
							'value'    => 'templates/page-template-simple-page.php',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'acf_after_title',
				'style'                 => 'seamless',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 0,
			)
		);
	}
);
