<?php
/**
 * search.php
 * 
 * Customize the search results
 * 
 */


/**
 * 
 * :: SEARCH: Filter Search Results by Guest
 * 
 * For Search Results page / archive:
 * 
 *  - Displays guests who match the search term
 *  - Only runs on front end
 * 
 * TAGS: SEARCH, ELEMENTOR
 * 
 */
function ehw_filter_search_results_by_guest( $query ) {
  $query->set( 'post_type', ['guests'] );
}
add_action( 'elementor/query/ehw_filter_search_by_guest', 
'ehw_filter_search_results_by_guest' );



/**
 * 
 *  #DOESNT_WORK!!!
 * 
 * :: SEARCH: Filter Search Results by Episode
 * 
 * For Search Results page / archive:
 * 
 *  - Displays videos and episodes that match the search term
 *  - Only runs on front end
 * 
 * TAGS: SEARCH, ELEMENTOR
 * 
 */
function ehw_filter_search_results_by_episode($query)
{
 // if (is_tax('episodes'))
 $query->set('post_type', ['videos']);
 $query->set(
   'tax_query',
   [
     'taxonomy' => 'genre',
     'field' => 'slug',
     'terms' => ['action'],
     'operator' => 'NOT IN',
   ],
 );
}
add_action(
 'elementor/query/ehw_filter_search_by_episode',
 'ehw_filter_search_results_by_episode'
);