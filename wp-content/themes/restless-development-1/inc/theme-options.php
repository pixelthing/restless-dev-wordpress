<?php
/**
 * Restless Development Theme Options
 *
 * @package WordPress
 * @subpackage Restless_Development_1
 * @since Restless Development 1.0
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since Restless Development 1.0
 *
 */
function restlessdevelopment1_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'restlessdevelopment1-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
	wp_enqueue_script( 'restlessdevelopment1-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array( 'farbtastic' ), '2011-06-10' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'restlessdevelopment1_admin_enqueue_scripts' );

/**
 * Register the form setting for our restlessdevelopment1_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, restlessdevelopment1_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_theme_options_init() {

	register_setting(
		'restlessdevelopment1_options',       // Options group, see settings_fields() call in restlessdevelopment1_theme_options_render_page()
		'restlessdevelopment1_theme_options', // Database option, see restlessdevelopment1_get_theme_options()
		'restlessdevelopment1_theme_options_validate' // The sanitization callback, see restlessdevelopment1_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see restlessdevelopment1_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field(
		'color_scheme',  // Unique identifier for the field for this section
		__( 'Color Scheme', 'restlessdevelopment1' ), // Setting field label
		'restlessdevelopment1_settings_field_color_scheme', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see restlessdevelopment1_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field( 'link_color', __( 'Link Color',     'restlessdevelopment1' ), 'restlessdevelopment1_settings_field_link_color', 'theme_options', 'general' );
	add_settings_field( 'layout',     __( 'Default Layout', 'restlessdevelopment1' ), 'restlessdevelopment1_settings_field_layout',     'theme_options', 'general' );
}
add_action( 'admin_init', 'restlessdevelopment1_theme_options_init' );

/**
 * Change the capability required to save the 'restlessdevelopment1_options' options group.
 *
 * @see restlessdevelopment1_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see restlessdevelopment1_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function restlessdevelopment1_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_restlessdevelopment1_options', 'restlessdevelopment1_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'restlessdevelopment1' ),   // Name of page
		__( 'Theme Options', 'restlessdevelopment1' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'restlessdevelopment1_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	add_action( "load-$theme_page", 'restlessdevelopment1_theme_options_help' );
}
add_action( 'admin_menu', 'restlessdevelopment1_theme_options_add_page' );

function restlessdevelopment1_theme_options_help() {

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, Twenty Eleven, provides the following Theme Options:', 'restlessdevelopment1' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Color Scheme</strong>: You can choose a color palette of "Light" (light background with dark text) or "Dark" (dark background with light text) for your site.', 'restlessdevelopment1' ) . '</li>' .
				'<li>' . __( '<strong>Link Color</strong>: You can choose the color used for text links on your site. You can enter the HTML color or hex code, or you can choose visually by clicking the "Select a Color" button to pick from a color wheel.', 'restlessdevelopment1' ) . '</li>' .
				'<li>' . __( '<strong>Default Layout</strong>: You can choose if you want your site&#8217;s default layout to have a sidebar on the left, the right, or not at all.', 'restlessdevelopment1' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'restlessdevelopment1' ) . '</p>';

	$sidebar = '<p><strong>' . __( 'For more information:', 'restlessdevelopment1' ) . '</strong></p>' .
		'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'restlessdevelopment1' ) . '</p>' .
		'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'restlessdevelopment1' ) . '</p>';

	$screen = get_current_screen();

	if ( method_exists( $screen, 'add_help_tab' ) ) {
		// WordPress 3.3
		$screen->add_help_tab( array(
			'title' => __( 'Overview', 'restlessdevelopment1' ),
			'id' => 'theme-options-help',
			'content' => $help,
			)
		);

		$screen->set_help_sidebar( $sidebar );
	} else {
		// WordPress 3.2
		add_contextual_help( $screen, $help . $sidebar );
	}
}

/**
 * Returns an array of color schemes registered for Twenty Eleven.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_color_schemes() {
	$color_scheme_options = array(
		'light' => array(
			'value' => 'light',
			'label' => __( 'Light', 'restlessdevelopment1' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/light.png',
			'default_link_color' => '#1b8be0',
		),
		'dark' => array(
			'value' => 'dark',
			'label' => __( 'Dark', 'restlessdevelopment1' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/dark.png',
			'default_link_color' => '#e4741f',
		),
	);

	return apply_filters( 'restlessdevelopment1_color_schemes', $color_scheme_options );
}

/**
 * Returns an array of layout options registered for Twenty Eleven.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_layouts() {
	$layout_options = array(
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content on left', 'restlessdevelopment1' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Content on right', 'restlessdevelopment1' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
		'content' => array(
			'value' => 'content',
			'label' => __( 'One-column, no sidebar', 'restlessdevelopment1' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		),
	);

	return apply_filters( 'restlessdevelopment1_layouts', $layout_options );
}

/**
 * Returns the default options for Twenty Eleven.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_get_default_theme_options() {
	$default_theme_options = array(
		'color_scheme' => 'light',
		'link_color'   => restlessdevelopment1_get_default_link_color( 'light' ),
		'theme_layout' => 'content-sidebar',
	);

	if ( is_rtl() )
 		$default_theme_options['theme_layout'] = 'sidebar-content';

	return apply_filters( 'restlessdevelopment1_default_theme_options', $default_theme_options );
}

/**
 * Returns the default link color for Twenty Eleven, based on color scheme.
 *
 * @since Restless Development 1.0
 *
 * @param $string $color_scheme Color scheme. Defaults to the active color scheme.
 * @return $string Color.
*/
function restlessdevelopment1_get_default_link_color( $color_scheme = null ) {
	if ( null === $color_scheme ) {
		$options = restlessdevelopment1_get_theme_options();
		$color_scheme = $options['color_scheme'];
	}

	$color_schemes = restlessdevelopment1_color_schemes();
	if ( ! isset( $color_schemes[ $color_scheme ] ) )
		return false;

	return $color_schemes[ $color_scheme ]['default_link_color'];
}

/**
 * Returns the options array for Twenty Eleven.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_get_theme_options() {
	return get_option( 'restlessdevelopment1_theme_options', restlessdevelopment1_get_default_theme_options() );
}

/**
 * Renders the Color Scheme setting field.
 *
 * @since Twenty Eleven 1.3
 */
function restlessdevelopment1_settings_field_color_scheme() {
	$options = restlessdevelopment1_get_theme_options();

	foreach ( restlessdevelopment1_color_schemes() as $scheme ) {
	?>
	<div class="layout image-radio-option color-scheme">
	<label class="description">
		<input type="radio" name="restlessdevelopment1_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
		<input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['default_link_color'] ); ?>" />
		<span>
			<img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="136" height="122" alt="" />
			<?php echo $scheme['label']; ?>
		</span>
	</label>
	</div>
	<?php
	}
}

/**
 * Renders the Link Color setting field.
 *
 * @since Twenty Eleven 1.3
 */
function restlessdevelopment1_settings_field_link_color() {
	$options = restlessdevelopment1_get_theme_options();
	?>
	<input type="text" name="restlessdevelopment1_theme_options[link_color]" id="link-color" value="<?php echo esc_attr( $options['link_color'] ); ?>" />
	<a href="#" class="pickcolor hide-if-no-js" id="link-color-example"></a>
	<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e( 'Select a Color', 'restlessdevelopment1' ); ?>" />
	<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
	<br />
	<span><?php printf( __( 'Default color: %s', 'restlessdevelopment1' ), '<span id="default-color">' . restlessdevelopment1_get_default_link_color( $options['color_scheme'] ) . '</span>' ); ?></span>
	<?php
}

/**
 * Renders the Layout setting field.
 *
 * @since Twenty Eleven 1.3
 */
function restlessdevelopment1_settings_field_layout() {
	$options = restlessdevelopment1_get_theme_options();
	foreach ( restlessdevelopment1_layouts() as $layout ) {
		?>
		<div class="layout image-radio-option theme-layout">
		<label class="description">
			<input type="radio" name="restlessdevelopment1_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
			<span>
				<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
				<?php echo $layout['label']; ?>
			</span>
		</label>
		</div>
		<?php
	}
}

/**
 * Returns the options array for Twenty Eleven.
 *
 * @since Twenty Eleven 1.2
 */
function restlessdevelopment1_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
		<h2><?php printf( __( '%s Theme Options', 'restlessdevelopment1' ), $theme_name ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'restlessdevelopment1_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see restlessdevelopment1_theme_options_init()
 * @todo set up Reset Options action
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_theme_options_validate( $input ) {
	$output = $defaults = restlessdevelopment1_get_default_theme_options();

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], restlessdevelopment1_color_schemes() ) )
		$output['color_scheme'] = $input['color_scheme'];

	// Our defaults for the link color may have changed, based on the color scheme.
	$output['link_color'] = $defaults['link_color'] = restlessdevelopment1_get_default_link_color( $output['color_scheme'] );

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
		$output['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], restlessdevelopment1_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	return apply_filters( 'restlessdevelopment1_theme_options_validate', $output, $input, $defaults );
}

/**
 * Enqueue the styles for the current color scheme.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_enqueue_color_scheme() {
	$options = restlessdevelopment1_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme )
		wp_enqueue_style( 'dark', get_template_directory_uri() . '/colors/dark.css', array(), null );

	do_action( 'restlessdevelopment1_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'restlessdevelopment1_enqueue_color_scheme' );

/**
 * Add a style block to the theme for the current link color.
 *
 * This function is attached to the wp_head action hook.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_print_link_color_style() {
	$options = restlessdevelopment1_get_theme_options();
	$link_color = $options['link_color'];

	$default_options = restlessdevelopment1_get_default_theme_options();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
	<style>
		/* Link color */
		a,
		#site-title a:focus,
		#site-title a:hover,
		#site-title a:active,
		.entry-title a:hover,
		.entry-title a:focus,
		.entry-title a:active,
		.widget_restlessdevelopment1_ephemera .comments-link a:hover,
		section.recent-posts .other-recent-posts a[rel="bookmark"]:hover,
		section.recent-posts .other-recent-posts .comments-link a:hover,
		.format-image footer.entry-meta a:hover,
		#site-generator a:hover {
			color: <?php echo $link_color; ?>;
		}
		section.recent-posts .other-recent-posts .comments-link a:hover {
			border-color: <?php echo $link_color; ?>;
		}
		article.feature-image.small .entry-summary p a:hover,
		.entry-header .comments-link a:hover,
		.entry-header .comments-link a:focus,
		.entry-header .comments-link a:active,
		.feature-slider a.active {
			background-color: <?php echo $link_color; ?>;
		}
	</style>
<?php
}
add_action( 'wp_head', 'restlessdevelopment1_print_link_color_style' );

/**
 * Adds Twenty Eleven layout classes to the array of body classes.
 *
 * @since Restless Development 1.0
 */
function restlessdevelopment1_layout_classes( $existing_classes ) {
	$options = restlessdevelopment1_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'content-sidebar', 'sidebar-content' ) ) )
		$classes = array( 'two-column' );
	else
		$classes = array( 'one-column' );

	if ( 'content-sidebar' == $current_layout )
		$classes[] = 'right-sidebar';
	elseif ( 'sidebar-content' == $current_layout )
		$classes[] = 'left-sidebar';
	else
		$classes[] = $current_layout;

	$classes = apply_filters( 'restlessdevelopment1_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'restlessdevelopment1_layout_classes' );

/**
 * Implements Twenty Eleven theme options into Theme Customizer
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 * @since Twenty Eleven 1.3
 */
function restlessdevelopment1_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$options  = restlessdevelopment1_get_theme_options();
	$defaults = restlessdevelopment1_get_default_theme_options();

	$wp_customize->add_setting( 'restlessdevelopment1_theme_options[color_scheme]', array(
		'default'    => $defaults['color_scheme'],
		'type'       => 'option',
		'capability' => 'edit_theme_options',
	) );

	$schemes = restlessdevelopment1_color_schemes();
	$choices = array();
	foreach ( $schemes as $scheme ) {
		$choices[ $scheme['value'] ] = $scheme['label'];
	}

	$wp_customize->add_control( 'restlessdevelopment1_color_scheme', array(
		'label'    => __( 'Color Scheme', 'restlessdevelopment1' ),
		'section'  => 'colors',
		'settings' => 'restlessdevelopment1_theme_options[color_scheme]',
		'type'     => 'radio',
		'choices'  => $choices,
		'priority' => 5,
	) );

	// Link Color (added to Color Scheme section in Theme Customizer)
	$wp_customize->add_setting( 'restlessdevelopment1_theme_options[link_color]', array(
		'default'           => restlessdevelopment1_get_default_link_color( $options['color_scheme'] ),
		'type'              => 'option',
		'sanitize_callback' => 'sanitize_hex_color',
		'capability'        => 'edit_theme_options',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'    => __( 'Link Color', 'restlessdevelopment1' ),
		'section'  => 'colors',
		'settings' => 'restlessdevelopment1_theme_options[link_color]',
	) ) );

	// Default Layout
	$wp_customize->add_section( 'restlessdevelopment1_layout', array(
		'title'    => __( 'Layout', 'restlessdevelopment1' ),
		'priority' => 50,
	) );

	$wp_customize->add_setting( 'restlessdevelopment1_theme_options[theme_layout]', array(
		'type'              => 'option',
		'default'           => $defaults['theme_layout'],
		'sanitize_callback' => 'sanitize_key',
	) );

	$layouts = restlessdevelopment1_layouts();
	$choices = array();
	foreach ( $layouts as $layout ) {
		$choices[$layout['value']] = $layout['label'];
	}

	$wp_customize->add_control( 'restlessdevelopment1_theme_options[theme_layout]', array(
		'section'    => 'restlessdevelopment1_layout',
		'type'       => 'radio',
		'choices'    => $choices,
	) );
}
add_action( 'customize_register', 'restlessdevelopment1_customize_register' );

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 * Used with blogname and blogdescription.
 *
 * @since Twenty Eleven 1.3
 */
function restlessdevelopment1_customize_preview_js() {
	wp_enqueue_script( 'restlessdevelopment1-customizer', get_template_directory_uri() . '/inc/theme-customizer.js', array( 'customize-preview' ), '20120523', true );
}
add_action( 'customize_preview_init', 'restlessdevelopment1_customize_preview_js' );