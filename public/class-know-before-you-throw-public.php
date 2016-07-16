<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://adamwills.io
 * @since      1.0.0
 *
 * @package    Know_Before_You_Throw
 * @subpackage Know_Before_You_Throw/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Know_Before_You_Throw
 * @subpackage Know_Before_You_Throw/public
 * @author     Adam Wills <adam@adamwills.com>
 */
class Know_Before_You_Throw_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $searchKey;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->searchKey = 'searchkbyt';
		$this->limit = 20;

	}

	/**
	 * Adds support for custom query variables
	 *
	 * @since    1.0.0
	 * @param      array    $vars       Existing query variables
	 * @return     array    				    Filtered query variables
	 */
	public function add_custom_query_var( $vars ) {
		$vars[] = $this->searchKey;
		return $vars;
	}

	/**
	 * Queries the database to get the serach results based on a query string
	 *
	 * @since    1.0.0
	 * @return   array    				    Search Results!
	 */
	private function get_search_results( $search_term ) {
		global $wpdb;
		$sql = $wpdb->prepare(
			"SELECT i.keyword, c.name AS category, c.description AS description, c.link AS link, c2.name AS subCategory, c2.description AS subDescription, c2.id AS subCategoryID, c2.link AS subLink
				FROM kybt_items_version2 i
				INNER JOIN kbyt_categories c ON i.cat_id = c.id
				INNER JOIN kbyt_categories c2 ON i.cat_id = c2.id
				WHERE keyword LIKE %s LIMIT %d", '%' . $wpdb->esc_like($search_term) . '%', $this->limit
		);
		return $wpdb->get_results($sql);
	}

	/**
	 * Queries the database to get the serach results based on a query string
	 *
	 * @since    1.0.0
	 * @param 	 array 			$results 		Search results
	 */
	private function display_search_results( $search_term, $results ) {

		// if there are no results, display a message to the user
		if ( count( $results ) == 0 ) :
			echo '<h2>' . __( 'No Results Found', $this->plugin_name ) . '</h2>';
			echo '<p>';
			printf( esc_html__("There were no matches for '%s'. Please try another search term.", $this->plugin_name), $search_term );
			echo '</p>';
			return;
		endif;

		// if there are results, start prepping the output!
		echo '<h2>'. __('Results for', $this->plugin_name) .': &lsquo;' . esc_html( $search_term ) . '&rsquo;</h2>';

		// display actual results
		echo '<dl>';
		foreach ($results as $result) :
			$details = $this->get_details( $result ); ?>

			<dt><?= $result->keyword; ?></dt>
			<dd><strong>Category:</strong> <?= $details->category ?></dd>
			<dd><?= $details->description ?></dd>
			<dd><a href="<?= $details->link; ?>"><?= __( 'More Details on', $this->plugin_name ); ?> <?= $result->keyword; ?></a></dd>

		<?php endforeach;
		echo '</dl>';

		// if there's too many results, let the user know that they should refine their search
		if ( count( $results ) >= $this->limit ) : ?>
			<div class="alert alert-warning"><?php printf( __('Your search returned more than %d results. You may want to be more specific with your search.', $this->plugin_name ), $this->limit); ?></div>
		<?php
		endif;
	}

	/**
	 * Prepares the details of what should be displayed for a specific result
	 *
	 * @since    1.0.0
	 * @param 	 object 			$result 		A single result
	 * @return   object
	 */
	private function get_details( $result ) {
		// Not entirely sure of this subcategory logic - this was inherited
		$exclusions = array(18, 15);
		$details = new stdClass();
		if ( $result->category != $result->subCategory && ! in_array( $result->subCategoryID, $exclusions) ) {
			$details->category = $result->subCategory;
			$details->description = $result->subDescription;
			$details->link = $result->subLink;
		} else {
			$details->category = $result->category;
			$details->description = $result->description;
			$details->link = $result->link;
		}
		return $details;
	}

	/**
	 * Handles the output of the plugin
	 *
	 * @since    1.0.0
	 * @return   string
	 */
	public function display_plugin() {

		ob_start();
		include('partials/know-before-you-throw-public-display.php');
		$search_term = get_query_var( $this->searchKey );
		if ( $search_term ) :
			$results = $this->get_search_results( $search_term );
			$this->display_search_results( $search_term, $results );
		endif;
		$sc = ob_get_contents();
		ob_end_clean();
		return $sc;

	}

}
