<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://oxyninja.com
 * @since      3.1.0
 *
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Oxy_Ninja
 * @subpackage Oxy_Ninja/public
 * @author     OxyNinja <hello@oxyninja.com>
 */
class Oxy_Ninja_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    3.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    3.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    3.1.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    3.1.0
	 */
	public function enqueue_styles() {

		wp_register_style('splide', OXYNINJA_URI_PUBLIC . '/css/splide.min.css', [], '2.4.20', 'all' );
		wp_register_style('glightbox', OXYNINJA_URI_PUBLIC . '/css/glightbox.min.css', [], '3.0.7', 'all' );
		// TODO: Legacy - remove in future version
		wp_register_style('splide-css', OXYNINJA_URI_PUBLIC . '/css/splide.min.css', [], '2.4.20', 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    3.1.0
	 */
	public function enqueue_scripts() {

		wp_register_script(
			'splide',
			OXYNINJA_URI_PUBLIC . '/js/splide/splide.min.js',
			[],
			'2.4.20',
			false
		);

		wp_register_script(
      'splide-autoscroll',
      OXYNINJA_URI_PUBLIC .  '/js/splide/splide-extension-autoscroll.js',
      [],
      '0.1.0',
      false
    );

		wp_register_script(
      'glightbox',
      OXYNINJA_URI_PUBLIC .  '/js/glightbox/glightbox.min.js',
      [],
      '3.0.7',
      false
    );
	}

	/**
	 * Product Thumbnails Flipper
	 *
	 * @since    3.1.0
	 */
	public function oxyninja_second_product_thumbnail() {

		global $product;

		if (is_a($product, 'WC_Product')) {
			$attachment_ids = $product->get_gallery_image_ids();
	
			if ($attachment_ids) {
				$attachment_ids = array_values($attachment_ids);
				$secondary_image_id = $attachment_ids['0'];
	
				$secondary_image_alt = get_post_meta(
					$secondary_image_id,
					'_wp_attachment_image_alt',
					true
				);
	
				echo wp_get_attachment_image($secondary_image_id, 'shop_catalog', '', [
					'class' => 'wc-secondary-image attachment-woocommerce_thumbnail',
					'alt' => $secondary_image_alt,
				]);
			}
		}

	}

	/**
	 * WC New Badge - Legacy
	 *
	 * @since    3.1.0
	 */
	public function oxyninja_new_badge() {

		global $product;
		if (is_a($product, 'WC_Product')) {
			$newness_days = 30;
			$created = strtotime($product->get_date_created());
			if (time() - 60 * 60 * 24 * $newness_days < $created) {
				echo '<span class="wc-new-badge">' .
					esc_html__('New!', 'oxy-ninja') .
					'</span>';
			}
		}

	}

	/**
	 * WC New Badge SplideJS
	 *
	 * @since    3.2.0
	 */
	static function oxyninja_new_badge_splide() {

		global $product;
		if (is_a($product, 'WC_Product')) {
			$newness_days = 30;
			$created = strtotime($product->get_date_created());
			if (time() - 60 * 60 * 24 * $newness_days < $created) {
				echo '<span class="on-new">' .
					esc_html__('New!', 'oxy-ninja') .
					'</span>';
			}
		}

	}

	/**
	 * WC Sale Badge SplideJS
	 *
	 * @since    3.2.0
	 */
	static function oxyninja_sale_badge_splide() {

		global $product;
		if ( $product->is_on_sale() ) {
				echo '<span class="on-sale">' .
					esc_html__('Sale!', 'woocommerce') .
					'</span>';
		}

	}

	/**
	 * WC Sale Badge
	 *
	 * @since    3.1.0
	 */
	public function oxyninja_sale_badge() {

		global $product;
		if (is_a($product, 'WC_Product')) {
			if (!$product->is_on_sale()) {
				return;
			}
			$max_percentage = 0;
			if ($product->is_type('simple') || $product->is_type('bundle')) {
				$max_percentage =
					(($product->get_regular_price() - $product->get_sale_price()) /
						$product->get_regular_price()) *
					100;
			} elseif ($product->is_type('variable')) {
				foreach ($product->get_children() as $child_id) {
					$variation = wc_get_product( $child_id );
					$price = $variation->get_regular_price();
					$sale = $variation->get_sale_price();
					if ($price != 0 && !empty($sale)) {
						$percentage = (($price - $sale) / $price) * 100;
					}
					if (isset($percentage)) {
							if ($percentage > $max_percentage) {
									$max_percentage = $percentage;
							}
					}
				}
			}
			if ($max_percentage > 0 && $max_percentage < 99) {
				echo "<div class='wc-sale-badge'>-" . round($max_percentage) . "%</div>";
			} else if ($max_percentage >= 99.01) {
				echo "<div class='wc-sale-badge'>-99%</div>";
			}
		}

	}

}
