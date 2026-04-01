<?php

remove_action( 'wp_head', 'wp_shortlink_wp_head' );

remove_action('wp_head', 'wp_generator');

remove_action ('wp_head', 'rsd_link');

remove_action('wp_head', 'rest_output_link_wp_head', 10);

add_filter( 'rank_math/opengraph/facebook/og_locale', function( $content ) {
    return 'es_CL';
});

add_action( 'init', 'wpkama_disable_embed_route', 99 );
function wpkama_disable_embed_route(){

	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	add_filter( 'rewrite_rules_array', function ( $rules ){

		foreach( $rules as $rule => $rewrite ){
			if( false !== strpos( $rewrite, 'embed=true' ) ){
				unset( $rules[$rule] );
			}
		}

		return $rules;
	} );
}

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Page options',
		'menu_title'	=> 'Options theme',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}

function x_default() {
    if ( function_exists('get_field') ) {
        $x_default = get_field('x_default');
        if ( $x_default ) {
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($x_default) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'x_default', 6);

function custom_hreflang_link() {
    if ( function_exists('get_field') ) {
        $hreflangs = get_field('hreflangs');
        if ( $hreflangs ) {
			foreach ($hreflangs as $hreflang) {
				echo '<link rel="alternate" hreflang="'.$hreflang['hrflng'].'" href="'.$hreflang['link'].'">' . "\n";
			}
        }
    }
}
add_action('wp_head', 'custom_hreflang_link', 7);

add_action( 'after_setup_theme', function(){
	register_nav_menus( [
		'main' => 'Меню в шапке',
		'footer' => 'Меню в подвале'
	] );
} );

add_theme_support( 'post-thumbnails' );

remove_action('wp_head', 'rel_canonical');

function delete_intermediate_image_sizes( $sizes ){
	return array_diff( $sizes, [
		'medium_large',
		'large',
		'1536x1536',
		'2048x2048',
	] );
}

add_filter( 'intermediate_image_sizes', 'delete_intermediate_image_sizes' );

remove_action( 'wp_head', 'wp_shortlink_wp_head' );

function remove_image_size_attributes($html) {
	return str_replace('size-full', '', $html);
}

add_filter( 'the_content', 'remove_image_size_attributes' );

function wpassist_remove_block_library_css(){
	wp_dequeue_style('wp-block-library');
}

add_action( 'wp_enqueue_scripts', 'wpassist_remove_block_library_css' );

function my_init() {
    if ( !is_admin() ) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', false);
    }
}
add_action('init', 'my_init');

add_action( 'wp_enqueue_scripts', 'style_theme' );

function style_theme() {
    wp_enqueue_style( 'style', get_stylesheet_uri() );
}

function is_amp() {
	return ($_GET['amp']) ? true : false;
}

add_filter( 'upload_mimes', 'svg_upload_allow' );

function svg_upload_allow( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}

add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){

	if ( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ) {
		$dosvg = in_array( $real_mime, ['image/svg', 'image/svg+xml'] );
	}
	else {
		$dosvg = ( '.svg' === strtolower( substr($filename, -4) ) );
	}

	if ($dosvg) {
		if ( current_user_can('manage_options') ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		} else {
			$data['ext']  = false;
			$data['type'] = false;
		}
	}
	return $data;
}

function wrap_content($content){

	$result = str_replace(
		array( '<h2' ), 
		array( '</section><section class="section-block"><h2' ), 
	
	$content);

	$section__counter = 1;
	$header__counter = 1;

	$result = preg_replace_callback('|<section(.*)>|Uis', function($matches) {

		global $section__counter;
		$section__counter++;

		return '<section class="section-block" id="section__'. $section__counter .'">';
	
	}, $result);

	$content = preg_replace_callback('|<h2(.*)</h2>|Uis', function($matches) use (&$headers) {

		$match = trim(strip_tags($matches[1]));
		$match = strstr($match, '>');
		$match = str_replace('>', '', $match);
		$heading = strtolower($match);
		$heading = str_replace(
			array(' ', ',', '!', '?', ':', '.','&nbsp;','(',')','¿'), 
			array('-', '','','','','','','','',''),
			$heading
		);
		
		$dash = ( is_numeric($match[0]) ? '_' : '' );

		return '<h2 id="'.$dash.$heading.'">' . $match . '</h2>';
	
	}, $result);

	$content = str_replace('</section><section class="section-block" id="section__1">', '<section class="section-block" id="section__1">', $content );
	$content .= '<div class="section-content"></div>';
	$content = str_replace('<div class="section-content"></div>', '<div class="section-content"></div></section>', $content );
	$content = str_replace('frameborder="0"', '', $content);
	return $content;

}

add_filter('the_content', 'wrap_content');

function content_banner($atts){

		$atts = shortcode_atts( array(
			'link' => '#',
			'img' => '/wp-content/uploads/2023/10/banner-content.webp',
			'alt' => '1win Africa'
		), $atts );

    	$output = '<div class="banner-link">';
					if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
						$output .= '<button class="banner-img" on="tap:AMP.navigateTo(url=\''.$atts['link'].'\')">';
					else:
						$output .= '<button class="banner-img" onclick="location.href=\''.$atts['link'].'\'">';
					endif;
						$output .= '
							<img src="'.$atts['img'].'" alt="'.$atts['alt'].'">
						</button>
						<div class="banner-textcaption">
							<span>'.$atts['textcaption'].'</span>
						</div>
				</div>';

    	return $output;

}
add_shortcode( 'content-banner', 'content_banner' );

function content_button($atts){

	$atts = shortcode_atts( array(
		'name' => 'Registrarse en 1win',
		'link' => '#'
	), $atts );

	$output = '<div class="button-content">';
				if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
					$output .= '<button class="button content_btn" on="tap:AMP.navigateTo(url=\''.$atts['link'].'\')">';
				else:
					$output .= '<button class="button content_btn" onclick="location.href=\''.$atts['link'].'\'">';
				endif;
					$output .= '
						'.$atts['name'].'
					</button>
			</div>';

	return $output;

}
add_shortcode( 'content-button', 'content_button' );

function content_slots($atts){

	$atts = shortcode_atts( array(
		'link' => '#'
	), $atts );

	$output = '<div class="slot">
				<div class="slot__item">
					<div class="slot__item-overlay">
						<div class="slot__item-img">
							<img src="/wp-content/uploads/2025/03/gates-slot.webp" alt="Gates of Olympus">
						</div>
						<div class="slot__item-btn">';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=olympus\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=olympus\'">';
							endif;
								$output .= '
								Jugar
							</button>';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=olympus\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=olympus\'">';
							endif;
								$output .= '
								Demo
							</button>
						</div>
					</div>
				</div>
				<div class="slot__item">
					<div class="slot__item-overlay">
						<div class="slot__item-img">
							<img src="/wp-content/uploads/2025/03/bonanza-slot.webp" alt="Sweet Bonanza">
						</div>
						<div class="slot__item-btn">';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=bonanza\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=bonanza\'">';
							endif;
								$output .= '
								Jugar
							</button>';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=bonanza\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=bonanza\'">';
							endif;
								$output .= '
								Demo
							</button>
						</div>
					</div>
				</div>
				<div class="slot__item">
					<div class="slot__item-overlay">
						<div class="slot__item-img">
							<img src="/wp-content/uploads/2025/03/777-coins-slot.webp" alt="777 Coins">
						</div>
						<div class="slot__item-btn">';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=seven\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=seven\'">';
							endif;
								$output .= '
								Jugar
							</button>';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=seven\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=seven\'">';
							endif;
								$output .= '
								Demo
							</button>
						</div>
					</div>
				</div>
				<div class="slot__item">
					<div class="slot__item-overlay">
						<div class="slot__item-img">
							<img src="/wp-content/uploads/2025/03/wild-cash-slot.webp" alt="Wild Cash 9990">
						</div>
						<div class="slot__item-btn">';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=wild\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=wild\'">';
							endif;
								$output .= '
								Jugar
							</button>';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=wild\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=wild\'">';
							endif;
								$output .= '
								Demo
							</button>
						</div>
					</div>
				</div>
				<div class="slot__item">
					<div class="slot__item-overlay">
						<div class="slot__item-img">
							<img src="/wp-content/uploads/2025/03/coin-volcano-slot.webp" alt="Coin Volcano">
						</div>
						<div class="slot__item-btn">';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=volcano\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=volcano\'">';
							endif;
								$output .= '
								Jugar
							</button>';
							if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) :
								$output .= '<button class="button slots__btn" on="tap:AMP.navigateTo(url=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=volcano\')">';
							else:
								$output .= '<button class="button slots__btn" onclick="location.href=\'https://2.seolobster.com/click?key=74a8585e6e16bafca3e5&button=volcano\'">';
							endif;
								$output .= '
								Demo
							</button>
						</div>
					</div>
				</div>
			</div>';

	return $output;

}
add_shortcode( 'content-slots', 'content_slots' );