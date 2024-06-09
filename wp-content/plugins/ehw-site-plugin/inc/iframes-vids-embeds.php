<?php
/**
 * iframes-vids-embeds.php
 * 
 * Customize iframes and video embeds.
 * 
 */


/**
 * 
 * :: ACF: Allow IFRAME & SCRIPT RENDERING
 * 
 * For Search Results page / archive:
 * 
 *  - Displays videos and episodes that match the search term
 *  - Only runs on front end
 * 
 * TAGS: IFRAMES, ACF, SCRIPT
 * 
 */
add_filter( 'wp_kses_allowed_html', 'acf_add_allowed_iframe_tag', 10, 2 );
function acf_add_allowed_iframe_tag( $tags, $context ) {
    if ( $context === 'post' ) {
        $tags['iframe'] = array(
            'src'             => true,
            'height'          => true,
            'width'           => true,
            'frameborder'     => true,
            'allowfullscreen' => true,
        );
		
		$tags['script'] = array(
		  'async'           => true,
		  'crossorigin'     => true,
		  'defer'           => true,
		  'integrity'       => true,
		  'nomodule'        => true,
		  'referrerpolicy'  => true,
		  'src'             => true,
		  'type'            => true,
		);		
		
    }
	
    return $tags;
}