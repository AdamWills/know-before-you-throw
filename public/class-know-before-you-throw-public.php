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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Know_Before_You_Throw_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Know_Before_You_Throw_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/know-before-you-throw-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Know_Before_You_Throw_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Know_Before_You_Throw_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/know-before-you-throw-public.js', array( 'jquery' ), $this->version, false );

	}

	public function add_custom_query_var( $vars ) {
		$vars[] = $this->searchKey;
		return $vars;
	}

	public function get_search_results() {
		$searchTerm = get_query_var( $this->searchKey );
		global $wpdb;
		$sql = $wpdb->prepare(
			"SELECT i.keyword, c.name AS category, c.description AS description, c.link AS link, c2.name AS subCategory, c2.description AS subDescription, c2.id AS subCategoryID, c2.link AS subLink
				FROM kybt_items_version2 i 
				INNER JOIN kbyt_categories c ON i.cat_id = c.id 
				INNER JOIN kbyt_categories c2 ON i.cat_id = c2.id 
				WHERE keyword LIKE %s", '%' . $wpdb->esc_like($searchTerm) . '%');

		$results = $wpdb->get_results($sql);
		return $results;
	}

	public function display_search_results( $results ) {
		$searchTerm = sanitize_text_field( get_query_var( $this->searchKey ) );
		echo '<h2>'. __('Results for', $this->plugin_name) .': &lsquo;' . $searchTerm . '&rsquo;</h2>';


		if ( count( $results ) > 20 ) : ?>
			<div class="alert alert-warning"><?= __('Your search returned more than 20 results. You may want to be more specific with your search.', $this->plugin_name ); ?></div>
		<?php
		endif;

		echo '<dl>';
		foreach ($results as $result) :
			$details = $this->get_details( $result ); ?>
			
			<dt><?= $result->keyword; ?></dt>
			<dd><strong>Category:</strong> <?= $details->category ?></dd>
			<dd><?= $details->description ?></dd>
			<dd><a href="<?= $details->link; ?>"><?= __( 'More Details on', $this->plugin_name ); ?> <?= $result->keyword; ?></a></dd>

		<?php endforeach;
		echo '</dl>';
		
	}

	public function get_details( $result ) {
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

	

	public function display_plugin() {

		ob_start();
		include('partials/know-before-you-throw-public-display.php');

		if ( get_query_var( $this->searchKey ) ) :
			$results = $this->get_search_results();
			if ( count($results) > 0 ) :
				$this->display_search_results( $results );
			endif;

		endif;


		$sc = ob_get_contents();
		ob_end_clean();
		return $sc;
	
	}

}
