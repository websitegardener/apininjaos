<?php
class OXYNINJA_Splide_Carousel extends OxyEl {
  public $js_added = false;
  public $glight_added = false;
  public $zoom_added = false;
  private $blby_variace = false;

  function name() {
    return 'OxyNinja Slider';
  }

  function slug() {
		return "oxyninja_slider";
  }
  
  function icon() {
    return OXYNINJA_URI.'/icons/carousel.svg';
  }

  function enableFullPresets() {
    return true;
  }

  function button_place() {
    global $on_helper_integration;
    return $on_helper_integration->tab_slug . "::" . $on_helper_integration->subsection_elements;
  }

  function init() {
    $this->El->useAJAXControls();
    $this->enableNesting();

    $this->El->staticCSS(
      '
      .oxy-oxyninja-slider {
        width: 100%;
        position: relative;
      }
      .oxy-oxyninja-slider img {
        object-fit: cover;
      }
      #glightbox-body {
        z-index: 9999999999 !important;
      }
      .oxy-oxyninja-slider:focus,
      .oxy-oxyninja-slider *:focus {
        outline: 0;
      }
      .splide__list {
        margin: 0 !important;
        padding: 0 !important;
        height: 100%;
        display: flex;
        width: -webkit-max-content;
        width: max-content;
        will-change: transform;
      }
      .splide__track > .splide__list {
        width: 100%;
      }
      .splide__track > .splide__list > .splide__slide {
        transform: translate3d(0px, 0, 0);
        -webkit-transform: translate3d(0px, 0, 0);
        -moz-transform: translate3d(0px, 0, 0);
        -ms-transform: translate3d(0px, 0, 0);
        -o-transform: translate3d(0px, 0, 0);
        will-change: inherit;
      }
      .splide__list > .splide__slide:first-child {
        z-index: 2;
      }
      .splide--fade > .splide__track>.splide__list {
        display: flex !important;
      }
      .on-product-badges {
        position: absolute;
        z-index: 9;
      }
      .on-product-badges span:not(:first-of-type) {
        margin-left: 5px;
      }
      .on-product-badges span.on-new,
      .on-product-badges span.on-custom-badge,
      .on-product-badges span.on-sale {
        display: inline-block;
        background-color: white;
        color: black;
        font-size: 12px;
        padding: 6px 12px;
        line-height: 1.2;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
      }
      '
    );
    
    add_action( 'wp_enqueue_scripts', array($this, 'load_splide') );
  }

  private function muzes() {
    return trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=') ) );
  }

  // Number of images in carousel under Proudct Image
  function carousel_images_under_main() {
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails",
      "slug" => "under_main_mw",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '5',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product' && iframeScope.getOption('oxy-oxyninja_slider_product_thumb')==='yes'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Page Width",
      "slug" => "under_main_pw",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '4',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product' && iframeScope.getOption('oxy-oxyninja_slider_product_thumb')==='yes'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Tablet",
      "slug" => "under_main_tablet",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '3',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product' && iframeScope.getOption('oxy-oxyninja_slider_product_thumb')==='yes'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Landscape",
      "slug" => "under_main_landscape",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '3',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product' && iframeScope.getOption('oxy-oxyninja_slider_product_thumb')==='yes'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Portrait",
      "slug" => "under_main_portrait",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product' && iframeScope.getOption('oxy-oxyninja_slider_product_thumb')==='yes'"
    );
  }

  // Number of images in carousel under the rest
  function carousel_images_under_rest() {
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails",
      "slug" => "rest_under_main_mw",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')==='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Page Width",
      "slug" => "rest_under_main_pw",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')==='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Tablet",
      "slug" => "rest_under_main_tablet",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')==='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Landscape",
      "slug" => "rest_under_main_landscape",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
        '6' => "Six",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')==='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Number of Thumbnails - Portrait",
      "slug" => "rest_under_main_portrait",
      "value" => [
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')==='gallery'"
    );
  }

  // Number of slider items per page
  function carousel_images() {
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Page",
      "slug" => "sg_mw",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
      ],
      "default" => '5',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Page - Page Width",
      "slug" => "sg_pw",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
      ],
      "default" => '4',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Page - Tablet",
      "slug" => "sg_tablet",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
      ],
      "default" => '3',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Page - Landscape",
      "slug" => "sg_landscape",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Page - Portrait",
      "slug" => "sg_portrait",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
      ],
      "default" => '1',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
  }

  // Number of slider items per move
  function carousel_images_move() {
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Move",
      "slug" => "sg_mw_m",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
      ],
      "default" => '5',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Move - Page Width",
      "slug" => "sg_pw_m",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
        '5' => "Five",
      ],
      "default" => '4',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Move - Tablet",
      "slug" => "sg_tablet_m",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
        '4' => "Four",
      ],
      "default" => '3',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Move - Landscape",
      "slug" => "sg_landscape_m",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
      ],
      "default" => '2',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Items Per Move - Portrait",
      "slug" => "sg_portrait_m",
      "value" => [
        '1' => "One",
        '2' => "Two",
        '3' => "Three",
      ],
      "default" => '1',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
  }

  // Carousel Images Gap
  function carousel_images_gap() {
    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Gap Between Items",
      "slug" => "sg_mw_g",
    ])
    ->setUnits('px', '')
    ->setRange('0', '100', '1')
    ->setValue('25')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Gap Between Items - Page Width",
      "slug" => "sg_pw_g",
    ])
    ->setUnits('px', '')
    ->setRange('0', '100', '1')
    ->setValue('20')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Gap Between Items - Tablet",
      "slug" => "sg_tablet_g",
    ])
    ->setUnits('px', '')
    ->setRange('0', '100', '1')
    ->setValue('15')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Gap Between Items - Landscape",
      "slug" => "sg_landscape_g",
    ])
    ->setUnits('px', '')
    ->setRange('0', '100', '1')
    ->setValue('10')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Gap Between Items - Portrait",
      "slug" => "sg_portrait_g",
    ])
    ->setUnits('px', '')
    ->setRange('0', '100', '1')
    ->setValue('5')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
  }

  // Height of slide
  function slider_height() {
    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Height",
      "slug" => "sg_mw_height",
    ])
    ->setUnits('px', 'px,em,vh')
    ->setRange('0', '1000', '1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_aspect_ratio')!=='square'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Height - Page Width",
      "slug" => "sg_pw_height",
    ])
    ->setUnits('px', 'px,em,vh')
    ->setRange('0', '1000', '1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width'  && iframeScope.getOption('oxy-oxyninja_slider_aspect_ratio')!=='square'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Height - Tablet",
      "slug" => "sg_tablet_height",
    ])
    ->setUnits('px', 'px,em,vh')
    ->setRange('0', '1000', '1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_aspect_ratio')!=='square'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Height - Landscape",
      "slug" => "sg_landscape_height",
    ])
    ->setUnits('px', 'px,em,vh')
    ->setRange('0', '1000', '1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_aspect_ratio')!=='square'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Height - Portrait",
      "slug" => "sg_portrait_height",
    ])
    ->setUnits('px', 'px,em,vh')
    ->setRange('0', '1000', '1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_aspect_ratio')!=='square'"
    );
  }

  function pagination() {
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Pagination",
      "slug" => "sg_mw_pag",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Pagination - Page Width",
      "slug" => "sg_pw_pag",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Pagination - Tablet",
      "slug" => "sg_tablet_pag",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Pagination - Landscape",
      "slug" => "sg_landscape_pag",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Pagination - Portrait",
      "slug" => "sg_portrait_pag",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
  }

  function padding() {
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Padding",
      "slug" => "sg_mw_padding",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Padding - Page Width",
      "slug" => "sg_pw_padding",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Padding - Tablet",
      "slug" => "sg_tablet_padding",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Padding - Landscape",
      "slug" => "sg_landscape_padding",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Padding - Portrait",
      "slug" => "sg_portrait_padding",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='fade' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
  }

  function padding_l() {
    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Left",
      "slug" => "sg_mw_padding_l",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_sg_mw_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Left",
      "slug" => "sg_pw_padding_l",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_sg_pw_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Left",
      "slug" => "sg_tablet_padding_l",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_sg_tablet_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Left",
      "slug" => "sg_landscape_padding_l",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_sg_landscape_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Left",
      "slug" => "sg_portrait_padding_l",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_sg_portrait_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
  }

  function padding_r() {
    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Right",
      "slug" => "sg_mw_padding_r",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_sg_mw_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Right",
      "slug" => "sg_pw_padding_r",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'page-width' && iframeScope.getOption('oxy-oxyninja_slider_sg_pw_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Right",
      "slug" => "sg_tablet_padding_r",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'tablet' && iframeScope.getOption('oxy-oxyninja_slider_sg_tablet_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Right",
      "slug" => "sg_landscape_padding_r",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-landscape' && iframeScope.getOption('oxy-oxyninja_slider_sg_landscape_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Padding Right",
      "slug" => "sg_portrait_padding_r",
      "value" => '0',
    ])
    ->setUnits('px', '')
    ->setRange('0','1000','1')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() == 'phone-portrait' && iframeScope.getOption('oxy-oxyninja_slider_sg_portrait_padding')=='true' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'"
    );
  }

  function carousel_settings() {
    if (class_exists('woocommerce')) {
      $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Related WooCommerce Products",
      "slug" => "is_related",
      "value" => [
        'related_no' => __('No', 'oxy-ninja'),
        'related_yes' => __('Yes', 'oxy-ninja'),
      ],
      "default" => 'related_no',
    ])->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='carousel'"
      );
    }

    $this->carousel_images();

    $this->carousel_images_move();

    $this->pagination();

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Arrows",
      "slug" => "arrows",
      "value" => [
        'true' => __('Show', 'oxy-ninja'),
        'false' => __('Hide', 'oxy-ninja'),
      ],
      "default" => 'true',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default'");

    $this->padding();
    $this->padding_l();
    $this->padding_r();

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Rewind",
      "slug" => "rewind",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && (iframeScope.getOption('oxy-oxyninja_slider_carousel_type')!=='loop' || iframeScope.getOption('oxy-oxyninja_slider_splide_type') === 'gallery')"
    );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Autoplay",
      "slug" => "autoplay",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default'");

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Autoplay Interval",
      "slug" => "autoplay_interval",
    ])
      ->setUnits('ms', 'ms')
      ->setRange('0', '7500', '100')
      ->setValue('5000')
      ->setParam(
        "ng_show",
        "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_autoplay')==='true'"
      );

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Pause on Hover",
      "slug" => "autoplay_pause",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => 'true',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_autoplay')==='true'");
  }

  function controls() {

    $zakladni_tabs = [
      'carousel' => __('Repeater', 'oxy-ninja'),
    ];

    if (defined('OXYNINJA_SPLIDE_ID')) {
        $zakladni_tabs['id'] = __('Images & Divs', 'oxy-ninja');
    }

    if (class_exists( 'woocommerce' )) {
      $zakladni_tabs['product'] = __('Product Image', 'oxy-ninja');
      $zakladni_tabs['woo-gallery'] = __('WooCommerce Gallery', 'oxy-ninja');
    }

    if (class_exists('Admin_2020')) {
      $zakladni_tabs['admin-2020'] = __('Admin 2020', 'oxy-ninja');
    }

    if ( !function_exists( 'is_plugin_active_for_network' ) ) {
      require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    if (!function_exists('oxyninja_is_plugin_active')) {
      function oxyninja_is_plugin_active($plugin) {
        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $plugin );
      }
    }

    if (oxyninja_is_plugin_active('advanced-custom-fields-pro/acf.php')) {
      $zakladni_tabs['acf'] = __('ACF Gallery', 'oxy-ninja');
    }
    if (oxyninja_is_plugin_active('happyfiles/happyfiles.php')) {
      $zakladni_tabs['happyfiles'] = __('HappyFiles', 'oxy-ninja');
    } elseif (oxyninja_is_plugin_active('happyfiles-pro/happyfiles-pro.php')) {
      $zakladni_tabs['happyfiles'] = __('HappyFiles Pro', 'oxy-ninja');
    }

    if (function_exists('types_render_field')) {
      $zakladni_tabs['toolset'] = __('Toolset', 'oxy-ninja');
    }

    $this->addOptionControl([
      "type" => "textfield",
      "name" => "Unique Slider Name",
      "slug" => "name",
    ]);

    $this->addOptionControl([
      "type" => "dropdown",
      "name" => "Slider Data Source",
      "slug" => "type",
      "value" => $zakladni_tabs,
      "default" => 'carousel',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default'");

    $this->addOptionControl([
      "type" => "textfield",
      "name" => "ACF Field",
      "slug" => "acf_field",
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='acf'");

    $this->addOptionControl([
      "type" => "textfield",
      "name" => "HappyFiles Category Name",
      "slug" => "happyfiles_field",
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='happyfiles'");

    $this->addOptionControl([
      "type" => "textfield",
      "name" => "Admin 2020 Folder ID",
      "slug" => "admin2020_field",
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='admin-2020'");

    $this->addOptionControl([
      "type" => "textfield",
      "name" => "Toolset Gallery Field",
      "slug" => "toolset_field",
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='toolset'");

    $this->addOptionControl([
      "type" => "radio",
      "name" => "Slider Type",
      "slug" => "splide_type",
      "value" => [
        'carousel' => __('Carousel', 'oxy-ninja'),
        'gallery' => __('Gallery', 'oxy-ninja'),
      ],
      "default" => 'carousel',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='woo-gallery' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='carousel' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='id'");

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Slider Type",
      "slug" => "carousel_type",
      "value" => [
        'slide' => __('Slide', 'oxy-ninja'),
        'loop' => __('Loop', 'oxy-ninja'),
        'fade' => __('Fade', 'oxy-ninja'),
      ],
      "default" => 'loop',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')!=='product' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')!=='gallery'");

    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Transition Speed",
      "slug" => "speed",
      "default" => '400',
    ])
    ->setUnits('ms', 'ms')
    ->setRange('0', '2500', '10')
    ->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default'");

    $this->addOptionControl([
      "type" => "dropdown",
      "name" => "Image Thumbnail Size",
      "slug" => "thumb_size",
      "value" => get_intermediate_image_sizes(),
      "default" => 'medium',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && (iframeScope.getOption('oxy-oxyninja_slider_type')==='happyfiles' || iframeScope.getOption('oxy-oxyninja_slider_type')==='admin-2020' || iframeScope.getOption('oxy-oxyninja_slider_type')==='woo-gallery' || iframeScope.getOption('oxy-oxyninja_slider_type')==='acf' || iframeScope.getOption('oxy-oxyninja_slider_type')==='toolset')");

    $this->carousel_images_under_rest();

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Image Aspect Ratio",
      "slug" => "aspect_ratio",
      "value" => [
        'original' => __('Original', 'oxy-ninja'),
        'square' => __('Square', 'oxy-ninja'),
      ],
      "default" => 'original',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product'");

    $oxyninja_show_gallery = $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Show Thumbnails",
      "slug" => "product_thumb",
      "value" => [
        'yes' => __('Yes', 'oxy-ninja'),
        'no' => __('No', 'oxy-ninja'),
      ],
      "default" => 'yes',
    ]);

    $oxyninja_show_gallery->setValueCSS([
			"yes" => "",
			"no" => "#primary-slider {margin-bottom: unset;}",
    ]);

    $oxyninja_show_gallery->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product'");
    
    $this->carousel_images_under_main();

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Thumbnails Fill",
      "slug" => "gallery_fill",
      "value" => [
        'default' => __('Default', 'oxy-ninja'),
        'fill' => __('Fill', 'oxy-ninja'),
      ],
      "default" => 'default',
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type') === 'product' && iframeScope.getOption('oxy-oxyninja_slider_product_thumb') === 'yes'");

    $this->slider_height();

    $this->carousel_images_gap();

    // Gallery Images Gap
    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Gap Between Items",
      "slug" => "gal_gap",
    ])
    ->setUnits('%', '%')
    ->setRange('0', '20', '0.1')
    ->setValue('2.25')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_splide_type')==='gallery'"
    );

    // Gallery Images Gap
    $this->addOptionControl([
      "type" => "slider-measurebox",
      "name" => "Gap Between Items",
      "slug" => "gal_gap_product",
    ])
    ->setUnits('%', '%')
    ->setRange('0', '20', '0.1')
    ->setValue('2.25')
    ->setParam(
      "ng_show",
      "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type')==='product'"
    );

    $oxyninja_effect = $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Product Effect",
      "slug" => "product_effect",
      "value" => [
        'none' => __('None', 'oxy-ninja'),
        'zoom' => __('Zoom', 'oxy-ninja'),
        'lightbox' => __('Lightbox', 'oxy-ninja'),
      ],
      "default" => 'none',
    ]);

    $oxyninja_effect->setValueCSS([
			"none" => "",
			"lightbox" => "#primary-slider .splide__list {cursor: zoom-in;} #secondary-slider {cursor: default;}",
      "zoom" => ""
    ]);

    $oxyninja_effect->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type') === 'product'");

    $oxyninja_lb = $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Lightbox",
      "slug" => "gallery_lightbox",
      "value" => [
        'false' => __('Disable', 'oxy-ninja'),
        'true' => __('Enable', 'oxy-ninja'),
      ],
      "default" => 'false',
    ]);

    $oxyninja_lb->setValueCSS([
			"false" => "",
			"true" => ".glightbox {cursor: zoom-in;}",
    ]);

    $oxyninja_lb->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && (iframeScope.getOption('oxy-oxyninja_slider_type') === 'acf' || iframeScope.getOption('oxy-oxyninja_slider_type') === 'woo-gallery' || iframeScope.getOption('oxy-oxyninja_slider_type') === 'happyfiles' || iframeScope.getOption('oxy-oxyninja_slider_type') === 'toolset')");

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Show Product Badges",
      "slug" => "product_badges",
      "value" => [
        'show' => __('Show', 'oxy-ninja'),
        'hide' => __('Hide', 'oxy-ninja'),
      ],
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type') === 'product'");

    /* Product Badges */
    $on_badges = $this->addControlSection("product_badges", __("Product Badges"), "assets/icon.png", $this);
    $on_badges_selector = "div.on-product-badges";

    $on_badges->addStyleControl([
      "name" => __('Badges Gap Offset'),
      "property" => 'margin-left',
      "selector" => 'div.on-product-badges span:not(:first-of-type)',
      "value" => '5',
      "unit" => 'px',
      "control_type" => "measurebox",
    ]);

    $on_badges->addStyleControls([
        ["name" => __('Top Offset'), "selector" => $on_badges_selector, "property" => 'top'],
        ["name" => __('Left Offset'), "selector" => $on_badges_selector, "property" => 'left'],
        ["name" => __('Right Offset'), "selector" => $on_badges_selector, "property" => 'right'],
        ["name" => __('Bottom Offset'), "selector" => $on_badges_selector, "property" => 'bottom'],
        ["name" => __('Badges Container Position'), "selector" => 'div.on-product-badges', "property" => 'position', "value" => 'absolute'],
        ["name" => __('Badges Container Width'), "selector" => 'div.on-product-badges', "property" => 'width',
          "unit" => 'auto'],
      ]
    );

    /* Sale Badge */
    $sales_badge = $on_badges->addControlSection("sales_badge", __("Sales Badge"), "assets/icon.png", $this);
    $sales_badge_selector = ".on-product-badges span.on-sale";

    $sales_badge->addOptionControl([
      "type" => "buttons-list",
      "name" => "Show Sale Badge",
      "slug" => "product_sale_badge",
      "condition" => "type=product",
      "value" => [
        'show' => __('Show', 'oxy-ninja'),
        'hide' => __('Hide', 'oxy-ninja'),
      ],
      "default" => 'show',
    ]);

    $sales_badge->addPreset(
      "padding",
      "sale_badge_padding",
      __("Sale Badge Padding"),
      $sales_badge_selector
    );


		$sales_badge->addStyleControls(
      [
        ["selector" => $sales_badge_selector, "property" => 'color'],
				["selector" => $sales_badge_selector, "property" => 'background-color'],
        ["selector" => $sales_badge_selector, "property" => 'border-radius'],
        ["selector" => $sales_badge_selector, "property" => 'width'],
        ["selector" => $sales_badge_selector, "property" => 'font-size'],
        ["selector" => $sales_badge_selector, "property" => 'text-transform'],
        ["selector" => $sales_badge_selector, "property" => 'font-family'],
        ["selector" => $sales_badge_selector, "property" => 'font-weight']
      ]
    );

    /* New Badge */
    $new_badge = $on_badges->addControlSection("new_badge", __("New Badge"), "assets/icon.png", $this);
    $new_badge_selector = ".on-product-badges span.on-new";

    $new_badge->addOptionControl([
      "type" => "buttons-list",
      "name" => "Show New Badge",
      "slug" => "product_new_badge",
      "condition" => "type=product",
      "value" => [
        'show' => __('Show', 'oxy-ninja'),
        'hide' => __('Hide', 'oxy-ninja'),
      ],
      "default" => 'show',
    ]);

    $new_badge->addPreset(
      "padding",
      "new_badge_padding",
      __("New Badge Padding"),
      $new_badge_selector
    );


    $new_badge->addStyleControls(
      [
        ["selector" => $new_badge_selector, "property" => 'color'],
        ["selector" => $new_badge_selector, "property" => 'background-color'],
        ["selector" => $new_badge_selector, "property" => 'border-radius'],
        ["selector" => $new_badge_selector, "property" => 'width'],
        ["selector" => $new_badge_selector, "property" => 'font-size'],
        ["selector" => $new_badge_selector, "property" => 'text-transform'],
        ["selector" => $new_badge_selector, "property" => 'font-family'],
        ["selector" => $new_badge_selector, "property" => 'font-weight']
      ]
    );

    /* Custom Badge */
    if (class_exists('ACF') || function_exists('types_render_field')) {
      $custom_badge = $on_badges->addControlSection("custom_badge", __("Custom Badge"), "assets/icon.png", $this);
      $custom_badge_selector = ".on-product-badges span.on-custom-badge";

      $custom_badge->addOptionControl([
        "type" => "buttons-list",
        "name" => "Show Custom Badge",
        "slug" => "product_custom_badge",
        "condition" => "type=product",
        "value" => [
          'show' => __('Show', 'oxy-ninja'),
          'hide' => __('Hide', 'oxy-ninja'),
        ],
        "default" => 'show',
      ]);

      $custom_badge->addPreset(
        "padding",
        "custom_badge_padding",
        __("Custom Badge Padding"),
        $custom_badge_selector
      );

      $custom_badge->addStyleControls([
        ["selector" => $custom_badge_selector, "property" => 'color'],
        ["selector" => $custom_badge_selector, "property" => 'background-color'],
        ["selector" => $custom_badge_selector, "property" => 'border-radius'],
        ["selector" => $custom_badge_selector, "property" => 'width'],
        ["selector" => $custom_badge_selector, "property" => 'font-size'],
        ["selector" => $custom_badge_selector, "property" => 'text-transform'],
        ["selector" => $custom_badge_selector, "property" => 'font-family'],
        ["selector" => $custom_badge_selector, "property" => 'font-weight']
      ]);
    }

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Focus",
      "slug" => "focus",
      "value" => [
        'hide' => __('Disable', 'oxy-ninja'),
        'show' => __('Enable', 'oxy-ninja'),
      ]
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type') === 'slide' && iframeScope.getOption('oxy-oxyninja_slider_type') !== 'product'");

    $this->carousel_settings();

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Lazy Load",
      "slug" => "lazy_load",
      "value" => [
        'false' => __('False', 'oxy-ninja'),
        'sequential' => __('True', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])->hidden();

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Live Preview",
      "slug" => "lp",
      "value" => [
        'false' => __('False', 'oxy-ninja'),
        'mid' => __('Mid', 'oxy-ninja'),
        'true' => __('True', 'oxy-ninja'),
      ],
      "default" => 'false',
    ])->hidden();

    // Extensions
    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "Extensions",
      "slug" => "extensions",
      "value" => [
        'none' => __('None', 'oxy-ninja'),
        'autoscroll' => __('AutoScroll', 'oxy-ninja'),
      ],
      "default" => "none",
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_type') !== 'product' && iframeScope.getOption('oxy-oxyninja_slider_focus') !== 'show' && iframeScope.getOption('oxy-oxyninja_slider_splide_type') !== 'gallery' && iframeScope.getOption('oxy-oxyninja_slider_carousel_type') !== 'fade'");

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "AutoScroll Speed",
      "slug" => "aspeed",
      "value" => [
        'slow' => __('Slow', 'oxy-ninja'),
        'normal' => __('Normal', 'oxy-ninja'),
        'fast' => __('Fast', 'oxy-ninja'),
      ],
      "default" => "slow",
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_extensions') === 'autoscroll'");

    $this->addOptionControl([
      "type" => "buttons-list",
      "name" => "AutoScroll Pause on Hover",
      "slug" => "apause",
      "value" => [
        'true' => __('Enable', 'oxy-ninja'),
        'false' => __('Disable', 'oxy-ninja'),
      ],
      "default" => "true",
    ])->setParam("ng_show", "iframeScope.getCurrentMedia() === 'default' && iframeScope.getOption('oxy-oxyninja_slider_extensions') === 'autoscroll'");

    $apply_html = '<div id="splide-previw-button" ng-click="iframeScope.splideBackToBasic()" style="margin-bottom:-15px;">Apply Params</div>';
    $this->addCustomControl($apply_html);

    $cc_html = '<div id="splide-previw-button" ng-click="iframeScope.splideLivePreview()">Live Preview</div>';
    $this->addCustomControl($cc_html)
    ->setParam(
      "ng_show",
      "iframeScope.getOption('oxy-oxyninja_slider_type')==='id' && iframeScope.getOption('oxy-oxyninja_slider_lp')==='mid'"
    );

    $cc_repeater_html = '<div id="splide-previw-button" ng-click="iframeScope.splideLivePreviewRepeater()">Live Preview</div>';
    $this->addCustomControl($cc_repeater_html)
    ->setParam(
      "ng_show",
      "iframeScope.getOption('oxy-oxyninja_slider_type')==='carousel' && iframeScope.getOption('oxy-oxyninja_slider_lp')==='mid'"
    );

    $product_badge_html = '<div id="on-badges-above"></div>';
    $this->addCustomControl($product_badge_html)
    ->setParam("ng_show", "iframeScope.getOption('oxy-oxyninja_slider_type') === 'product' && iframeScope.getOption('oxy-oxyninja_slider_product_badges') === 'show' ");
}

  function render($options, $defaults, $content) {
    // Slider name
    $slider_name = isset($options['name']) ? esc_attr($options['name'])
    : strtoupper(substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 8));

    // Carousel Images under Main
    $under_main_mw = isset($options['under_main_mw'])
    ? esc_attr($options['under_main_mw']) : 5;
    $under_main_pw = isset($options['under_main_pw'])
    ? esc_attr($options['under_main_pw']) : 4;
    $under_main_tablet = isset($options['under_main_tablet'])
    ? esc_attr($options['under_main_tablet']) : 3;
    $under_main_landscape = isset($options['under_main_landscape'])
    ? esc_attr($options['under_main_landscape']) : 3;
    $under_main_portrait = isset($options['under_main_portrait'])
    ? esc_attr($options['under_main_portrait']) : 2;

    // Carousel Images under rest
    $rest_under_main_mw = isset($options['rest_under_main_mw'])
    ? esc_attr($options['rest_under_main_mw']) : 2;
    $rest_under_main_pw = isset($options['rest_under_main_pw'])
    ? esc_attr($options['rest_under_main_pw']) : 2;
    $rest_under_main_tablet = isset($options['rest_under_main_tablet'])
    ? esc_attr($options['rest_under_main_tablet']) : 2;
    $rest_under_main_landscape = isset($options['rest_under_main_landscape'])
    ? esc_attr($options['rest_under_main_landscape']) : 2;
    $rest_under_main_portrait = isset($options['rest_under_main_portrait'])
    ? esc_attr($options['rest_under_main_portrait']) : 2;

    // Carousel Images per Page
    $sg_mw = isset($options['sg_mw']) ? esc_attr($options['sg_mw']) : 5;
    $sg_pw = isset($options['sg_pw']) ? esc_attr($options['sg_pw']) : 4;
    $sg_tablet = isset($options['sg_tablet']) ? esc_attr($options['sg_tablet']) : 3;
    $sg_landscape = isset($options['sg_landscape']) ? esc_attr($options['sg_landscape']) : 2;
    $sg_portrait = isset($options['sg_portrait']) ? esc_attr($options['sg_portrait']) : 1;

    // Carousel Images per Move
    $sg_mw_m = isset($options['sg_mw_m']) ? esc_attr($options['sg_mw_m']) : 5;
    $sg_pw_m = isset($options['sg_pw_m']) ? esc_attr($options['sg_pw_m']) : 4;
    $sg_tablet_m = isset($options['sg_tablet_m']) ? esc_attr($options['sg_tablet_m']) : 3;
    $sg_landscape_m = isset($options['sg_landscape_m']) ? esc_attr($options['sg_landscape_m']) : 2;
    $sg_portrait_m = isset($options['sg_portrait_m']) ? esc_attr($options['sg_portrait_m']) : 1;

    // Carousel Settings
    $carousel_type = isset($options['carousel_type']) ? esc_attr($options['carousel_type']) : 'loop';

    // Arrows + AutoPlay + Rewind
    $arrows = isset($options['arrows']) ? esc_attr($options['arrows']) : true;
    $autoplay = isset($options['autoplay']) ? esc_attr($options['autoplay']) : false;
    $autoplay_interval = isset($options['autoplay_interval']) ? esc_attr($options['autoplay_interval']) : 5000;
    $autoplay_pause = isset($options['autoplay_pause']) ? esc_attr($options['autoplay_pause']) : true;
    $rewind = isset($options['rewind']) ? esc_attr($options['rewind']) : false;

    // Pagination
    $sg_mw_pag = isset($options['sg_mw_pag']) ? esc_attr($options['sg_mw_pag']) : false;
    $sg_pw_pag = isset($options['sg_pw_pag']) ? esc_attr($options['sg_pw_pag']) : false;
    $sg_tablet_pag = isset($options['sg_tablet_pag']) ? esc_attr($options['sg_tablet_pag']) : false;
    $sg_landscape_pag = isset($options['sg_landscape_pag']) ? esc_attr($options['sg_landscape_pag']) : false;
    $sg_portrait_pag = isset($options['sg_portrait_pag']) ? esc_attr($options['sg_portrait_pag']) : false;

    // Padding Left
    $sg_mw_padding_l = isset($options['sg_mw_padding_l']) ? esc_attr($options['sg_mw_padding_l']) : 0;
    $sg_pw_padding_l = isset($options['sg_pw_padding_l']) ? esc_attr($options['sg_pw_padding_l']) : 0;
    $sg_tablet_padding_l = isset($options['sg_tablet_padding_l'])
    ? esc_attr($options['sg_tablet_padding_l']) : 0;
    $sg_landscape_padding_l = isset($options['sg_landscape_padding_l'])
    ? esc_attr($options['sg_landscape_padding_l']) : 0;
    $sg_portrait_padding_l = isset($options['sg_portrait_padding_l'])
    ? esc_attr($options['sg_portrait_padding_l']) : 0;

    // Padding Right
    $sg_mw_padding_r = isset($options['sg_mw_padding_r'])
    ? esc_attr($options['sg_mw_padding_r']) : 0;
    $sg_pw_padding_r = isset($options['sg_pw_padding_r'])
    ? esc_attr($options['sg_pw_padding_r']) : 0;
    $sg_tablet_padding_r = isset($options['sg_tablet_padding_r'])
    ? esc_attr($options['sg_tablet_padding_r']) : 0;
    $sg_landscape_padding_r = isset($options['sg_landscape_padding_r'])
    ? esc_attr($options['sg_landscape_padding_r']) : 0;
    $sg_portrait_padding_r = isset($options['sg_portrait_padding_r'])
    ? esc_attr($options['sg_portrait_padding_r']) : 0;

    // Carousel Gaps
    $sg_mw_g = isset($options['sg_mw_g']) ? esc_attr($options['sg_mw_g']) : 25;
    $sg_pw_g = isset($options['sg_pw_g']) ? esc_attr($options['sg_pw_g']) : 20;
    $sg_tablet_g = isset($options['sg_tablet_g']) ? esc_attr($options['sg_tablet_g']) : 15;
    $sg_landscape_g = isset($options['sg_landscape_g']) ? esc_attr($options['sg_landscape_g']) : 10;
    $sg_portrait_g = isset($options['sg_portrait_g']) ? esc_attr($options['sg_portrait_g']) : 5;

    // Height
    $sg_mw_height = isset($options['sg_mw_height'])
    ? esc_attr($options['sg_mw_height']) : 0;
    $sg_pw_height = isset($options['sg_pw_height'])
    ? esc_attr($options['sg_pw_height']) : 0;
    $sg_tablet_height = isset($options['sg_tablet_height'])
    ? esc_attr($options['sg_tablet_height']) : 0;
    $sg_landscape_height = isset($options['sg_landscape_height'])
    ? esc_attr($options['sg_landscape_height']) : 0;
    $sg_portrait_height = isset($options['sg_portrait_height'])
    ? esc_attr($options['sg_portrait_height']) : 0;
    
    // Field + Thumbnail
    $thumb_size = isset($options['thumb_size']) ? esc_attr($options['thumb_size']) : 'medium';
    $acf_field = isset($options['acf_field']) ? esc_attr($options['acf_field']) : '';
    $happyfiles_field = isset($options['happyfiles_field'])
    ? esc_attr($options['happyfiles_field']) : '';
    $admin2020_field = isset($options['admin2020_field'])
    ? esc_attr($options['admin2020_field']) : '';
    $toolset_field = isset($options['toolset_field']) ? esc_attr($options['toolset_field']) : '';

    // Lazy Load
    $lazy_load = isset($options['lazy_load']) ? esc_attr($options['lazy_load']) : false;

    // Speed
    $speed = isset($options['speed']) ? esc_attr($options['speed']) : 400;

    // Product Primery Aspect Ratio
    $aspect_ratio = isset($options['aspect_ratio']) ? esc_attr($options['aspect_ratio']) : 'original';

    // Breakpoints
    $sg_pw_bp = 1120;
    $sg_tablet_bp = 992;
    $sg_landscape_bp = 768;
    $sg_portrait_bp = 479;
    foreach (ct_get_global_settings() as $key => $value) {
      $key == 'max-width' && $sg_pw_bp = $value;
      if ($key == 'breakpoints') {
        foreach ($value as $key => $value) {
          $key == 'tablet' && $sg_tablet_bp = $value;
          $key == 'phone-landscape' && $sg_landscape_bp = $value;
          $key == 'phone-portrait' && $sg_portrait_bp = $value - 1;
        }
      }
    }

    // PerMove SplideJS Fix
    // ? Commented out on 3.3.3
    // $sg_mw === $sg_mw_m && $sg_mw_m = 0;
    // $sg_pw === $sg_pw_m && $sg_pw_m = 0;
    // $sg_tablet === $sg_tablet_m && $sg_tablet_m = 0;
    // $sg_landscape === $sg_landscape_m && $sg_landscape_m = 0;
    // $sg_portrait === $sg_portrait_m && $sg_portrait_m = 0;

    $glightbox_gallery = '';
    $glightbox_gallery_ul = 'ul';
    $glightbox_gallery_li = 'li';
    if (isset($options['gallery_lightbox']) &&
    esc_attr($options['gallery_lightbox']) === 'true') {
      $glightbox_gallery_ul = 'div';
      $glightbox_gallery_li = 'a';
      add_action('wp_footer', array( $this, 'glightbox_js' ));
      $glightbox_gallery = 'window.addEventListener("DOMContentLoaded", (e) => {
      var lightbox = GLightbox({selector: "#%%ELEMENT_ID%% .glightbox", touchNavigation: true, loop: true});});';
    }

    // Padding fix
    $sg_mw_padding = isset($options['sg_mw_padding']) ? esc_attr($options['sg_mw_padding']) : 'false';
    if ($sg_mw_padding === 'false') {
      $sg_mw_padding_m = "padding: false";
    } else {
      $sg_mw_padding_m = "padding: {
        left: '" . $sg_mw_padding_l . $options['sg_mw_padding_l_unit'] . "',
        right: '" . $sg_mw_padding_r . $options['sg_mw_padding_r_unit'] . "'}";
    }

    $sg_pw_padding = isset($options['sg_pw_padding']) ? esc_attr($options['sg_pw_padding']) : 'false';

    if ($sg_pw_padding === 'false') {
      $sg_pw_padding_m = "padding: false";
    } else {
      $sg_pw_padding_m = "padding: {
        left: '" . $sg_pw_padding_l . $options['sg_pw_padding_l_unit'] . "',
        right: '" . $sg_pw_padding_r . $options['sg_pw_padding_r_unit'] . "'}";
    }

    $sg_tablet_padding = isset($options['sg_tablet_padding']) ? esc_attr($options['sg_tablet_padding']) : 'false';

    if ($sg_tablet_padding === 'false') {
      $sg_tablet_padding_m = "padding: false";
    } else {
      $sg_tablet_padding_m = "padding: {
        left: '" . $sg_tablet_padding_l . $options['sg_tablet_padding_l_unit'] . "',
        right: '" . $sg_tablet_padding_r . $options['sg_tablet_padding_r_unit'] . "'}";
    }

    $sg_landscape_padding = isset($options['sg_landscape_padding'])
    ? esc_attr($options['sg_landscape_padding']) : 'false';

    if ($sg_landscape_padding === 'false') {
      $sg_landscape_padding_m = "padding: false";
    } else {
      $sg_landscape_padding_m = "padding: {
        left: '" . $sg_landscape_padding_l . $options['sg_landscape_padding_l_unit'] . "',
        right: '" . $sg_landscape_padding_r . $options['sg_landscape_padding_r_unit'] . "'}";
    }

    $sg_portrait_padding = isset($options['sg_portrait_padding'])
    ? esc_attr($options['sg_portrait_padding']) : 'false';

    if ($sg_portrait_padding === 'false') {
      $sg_portrait_padding_m = "padding: false";
    } else {
      $sg_portrait_padding_m = "padding: {
        left: '" . $sg_portrait_padding_l . $options['sg_portrait_padding_l_unit'] . "',
        right: '" . $sg_portrait_padding_r . $options['sg_portrait_padding_r_unit'] . "'}";
    }

    // Focus
    $focus = isset($options['focus']) ? esc_attr($options['focus']) : 'hide';
    $custom_focus = "focus: false";
    $custom_focus_trim = "trimSpace: true";
    if ($focus === 'show' && $carousel_type === 'slide') {
      $custom_focus = "focus: 'center'";
      $custom_focus_trim = "trimSpace: false";
    }

    // Dynamic Settings
    $custom_arrows_classes = "classes: {
      arrows: 'splide__arrows on-arrows',
      arrow: 'splide__arrow on-arrow',
      prev: 'splide__arrow--prev on-prev',
      next: 'splide__arrow--next on-next',
    }";

    $custom_keyboard = "keyboard: 'focused'";
    $custom_drag = "drag: true";

    $custom_autoplay = "autoplay: " . $autoplay;
    $custom_interval = "interval: " . $autoplay_interval;
    $custom_pauseOnHover = "pauseOnHover: " . $autoplay_pause;

    if (isset($options['type'])){
      if (esc_attr($options['splide_type']) === 'gallery') {
        // Padding fix
        $sg_mw_padding_m = "padding: false";
        $sg_pw_padding_m = "padding: false";
        $sg_tablet_padding_m = "padding: false";
        $sg_landscape_padding_m = "padding: false";
        $sg_portrait_padding_m = "padding: false";
      }
    }

    $custom_sg_mw_height = 'height: "' . $sg_mw_height . $options['sg_mw_height_unit'] . '"';
    $custom_sg_pw_height = 'height: "' . $sg_pw_height . $options['sg_pw_height_unit'] . '"';
    $custom_sg_tablet_height = 'height: "' . $sg_tablet_height . $options['sg_tablet_height_unit'] . '"';
    $custom_sg_landscape_height = 'height: "' . $sg_landscape_height . $options['sg_landscape_height_unit'] . '"';
    $custom_sg_portrait_height = 'height: "' . $sg_portrait_height . $options['sg_portrait_height_unit'] . '"';

    $sg_mw_height === 0 && $custom_sg_mw_height = 'height: 0';
    $sg_pw_height === 0 && $custom_sg_pw_height = 'height: 0';
    $sg_tablet_height === 0 && $custom_sg_tablet_height = 'height: 0';
    $sg_landscape_height === 0 && $custom_sg_landscape_height = 'height: 0';
    $sg_portrait_height === 0 && $custom_sg_portrait_height = 'height: 0';

    if (isset($options['gallery_lightbox']) &&
    esc_attr($options['gallery_lightbox']) === 'true') {
      $custom_keyboard = "keyboard: false";
    }
    
    if ($carousel_type === 'fade') {
      $sg_mw = 1;
      $sg_pw = 1;
      $sg_tablet = 1;
      $sg_landscape = 1;
      $sg_portrait = 1;

      $sg_mw_m = 1;
      $sg_pw_m = 1;
      $sg_tablet_m = 1;
      $sg_landscape_m = 1;
      $sg_portrait_m = 1;

      $sg_mw_g = 0;
      $sg_pw_g = 0;
      $sg_tablet_g = 0;
      $sg_landscape_g = 0;
      $sg_portrait_g = 0;

      $sg_mw_padding_m = "padding: false";
      $sg_pw_padding_m = "padding: false";
      $sg_tablet_padding_m = "padding: false";
      $sg_landscape_padding_m = "padding: false";
      $sg_portrait_padding_m = "padding: false";
    }

    $aSpeed = 1;
    if (isset($options['aspeed'])) {
      if ( esc_attr($options['aspeed']) === "normal" ) {
        $aSpeed = 1.6;
      } elseif ( esc_attr($options['aspeed']) === "fast" ) {
        $aSpeed = 2.4;
      }
    }

    $aPause = isset($options['apause']) ? esc_attr($options['apause']) : true;

    // Extensions
    $extension = '';
    if (isset($options['extensions'])) {
      if (esc_attr($options['extensions']) === "autoscroll") {
          $extension = "{ AutoScroll }";
          $custom_drag = "drag: false";
          $custom_autoplay = 'autoplay: false';
          $custom_interval = 'interval: false';
          $custom_pauseOnHover = 'pausOnHover: false';
          $sg_mw_pag = 'false';
          $sg_pw_pag = 'false';
          $sg_tablet_pag = 'false';
          $sg_landscape_pag = 'false';
          $sg_portrait_pag = 'false';
          $arrows = 'false';
          if ($carousel_type === 'fade') {
            $extension = '';
          }
      }
    }

    $iframe_url = parse_url(wp_get_referer());
    if (isset($iframe_url['query']) && strpos($iframe_url['query'], 'oxygen_iframe') !== false) {
      $custom_keyboard = "keyboard: false";
      $custom_drag = "drag: false";
      $custom_autoplay = "autoplay: false";
      $custom_interval = "interval: false";
      $custom_pauseOnHover = "pausOnHover: false";
      $extension = '';
    }

    $slider_basics = "type: '$carousel_type',
    perPage: $sg_mw,
    perMove: $sg_mw_m,
    gap: '" . $sg_mw_g . $options['sg_mw_g_unit'] . "',
    rewind: $rewind,
    arrows: $arrows,
    pagination: $sg_mw_pag,
    $custom_autoplay,
    $custom_interval,
    $custom_pauseOnHover,
    pauseOnFocus: true,
    $custom_keyboard,
    $custom_drag,
    lazyLoad: $lazy_load,
    $sg_mw_padding_m,
    $custom_arrows_classes,
    $custom_sg_mw_height,
    speed: $speed,
    aSpeed: $aSpeed,
    aPause: $aPause,
    $custom_focus,
    $custom_focus_trim,
    breakpoints: {
      $sg_pw_bp: {
        perPage: $sg_pw,
        perMove: $sg_pw_m,
        gap: '" . $sg_pw_g . $options['sg_pw_g_unit'] . "',
        pagination: $sg_pw_pag,
        $custom_sg_pw_height,
        $sg_pw_padding_m,
      },
      $sg_tablet_bp: {
        perPage: $sg_tablet,
        perMove: $sg_tablet_m,
        gap: '" . $sg_tablet_g . $options['sg_tablet_g_unit'] . "',
        pagination: $sg_tablet_pag,
        $custom_sg_tablet_height,
        $sg_tablet_padding_m,
      },
      $sg_landscape_bp: {
        perPage: $sg_landscape,
        perMove: $sg_landscape_m,
        gap: '" . $sg_landscape_g . $options['sg_landscape_g_unit'] . "',
        pagination: $sg_landscape_pag,
        $custom_sg_landscape_height,
        $sg_landscape_padding_m,
      },
      $sg_portrait_bp: {
        perPage: $sg_portrait,
        perMove: $sg_portrait_m,
        gap: '" . $sg_portrait_g . $options['sg_portrait_g_unit'] . "',
        pagination: $sg_portrait_pag,
        $custom_sg_portrait_height,
        $sg_portrait_padding_m,
      },
    },";

    $slider_gallery_primary = "type: 'fade',
    updateOnMove: true,
    pagination: false,
    rewind: $rewind,
    arrows: $arrows,
    $custom_autoplay,
    $custom_interval,
    $custom_pauseOnHover,
    pauseOnFocus: true,
    $custom_keyboard,
    $custom_drag,
    lazyLoad: $lazy_load,
    $custom_arrows_classes,
    $custom_sg_mw_height,
    speed: $speed,
    breakpoints: {
      $sg_pw_bp: {
        $custom_sg_pw_height,
      },
      $sg_tablet_bp: {
        $custom_sg_tablet_height,
      },
      $sg_landscape_bp: {
        $custom_sg_landscape_height,
      },
      $sg_portrait_bp: {
        $custom_sg_portrait_height,
      },
    },";

    $gal_gap = isset($options['gal_gap']) ? esc_attr($options['gal_gap']) : 2.25;
    $gal_gap_percent = $gal_gap . '%';
    // Desktop
    $gal_size_calc_d = 100 - $gal_gap * ($rest_under_main_mw - 1);
    $gal_size_d = $gal_size_calc_d / $rest_under_main_mw . '%';
    // Page Width
    $gal_size_calc_pw = 100 - $gal_gap * ($rest_under_main_pw - 1);
    $gal_size_pw = $gal_size_calc_pw / $rest_under_main_pw . '%';
    // Tablet
    $gal_size_calc_t = 100 - $gal_gap * ($rest_under_main_tablet - 1);
    $gal_size_t = $gal_size_calc_t / $rest_under_main_tablet . '%';
    // LandScape
    $gal_size_calc_ls = 100 - $gal_gap * ($rest_under_main_landscape - 1);
    $gal_size_ls = $gal_size_calc_ls / $rest_under_main_landscape . '%';
    // Portrait
    $gal_size_calc_port = 100 - $gal_gap * ($rest_under_main_portrait - 1);
    $gal_size_port = $gal_size_calc_port / $rest_under_main_portrait . '%';

    $slider_gallery_secondary = "fixedWidth: '$gal_size_d',
    fixedHeight: '$gal_size_d',
    isNavigation: true,
    gap: '$gal_gap_percent',
    pagination: false,
    arrows: false,
    cover: true,
    updateOnMove: true,
    pauseOnFocus: true,
    $custom_keyboard,
    $custom_drag,
    lazyLoad: $lazy_load,
    $sg_mw_padding_m,
    breakpoints: {
      $sg_pw_bp: {
        $sg_pw_padding_m,
        fixedWidth: '$gal_size_pw',
        fixedHeight: '$gal_size_pw',
      },
      $sg_tablet_bp: {
        $sg_tablet_padding_m,
        fixedWidth: '$gal_size_t',
        fixedHeight: '$gal_size_t',
      },
      $sg_landscape_bp: {
        $sg_landscape_padding_m,
        fixedWidth: '$gal_size_ls',
        fixedHeight: '$gal_size_ls',
      },
      $sg_portrait_bp: {
        $sg_portrait_padding_m,
        fixedWidth: '$gal_size_port',
        fixedHeight: '$gal_size_port',
      },
    },";

    $builder_fix = [
      "0" => "splideGridBreakpoint(iframeScope.getCurrentMedia())",
      "1" => "splideGridBreakpointGap(iframeScope.getCurrentMedia())"
    ];
    if ($carousel_type === 'fade') {
      $builder_fix = [
        "0" => "1",
        "1" => "0"
      ];
    }

    $wc_splide_columns = "if(jQuery('html').attr('ng-app') != 'CTFrontendBuilder'){ jQuery('#%%ELEMENT_ID%% .oxy-dynamic-list .oxy-repeater-pages-wrap').remove(); jQuery('#%%ELEMENT_ID%% .oxy-dynamic-list').first().addClass('splide__list'); jQuery('#%%ELEMENT_ID%% .splide__list').children().addClass('splide__slide'); var _ON_$slider_name = new Splide('#%%ELEMENT_ID%%', { $slider_basics }); _ON_$slider_name.mount($extension);}";

    $wc_splide_columns_builder = "function splideGridBreakpointGap(size) {
      switch (size) {
        case 'default':
          if (iframeScope.getOption('oxy-oxyninja_slider_sg_mw_g') === '') {
            return 25;
          } else {
            return iframeScope.getOption('oxy-oxyninja_slider_sg_mw_g');
          }
          break;
        case 'page-width':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_pw_g');
          break;
        case 'tablet':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_tablet_g');
          break;
        case 'phone-landscape':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_landscape_g');
          break;
        case 'phone-portrait':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_portrait_g');
          break;
      }
    }
    function splideGridBreakpoint(size) {
      switch (size) {
        case 'default':
          if (iframeScope.getOption('oxy-oxyninja_slider_sg_mw') === '') {
            return 4;
          } else {
            return iframeScope.getOption('oxy-oxyninja_slider_sg_mw');
          }
          break;
        case 'page-width':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_pw');
          break;
        case 'tablet':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_tablet');
          break;
        case 'phone-landscape':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_landscape');
          break;
        case 'phone-portrait':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_portrait');
          break;
      }
    }
    setTimeout(function(){
      jQuery('#%%ELEMENT_ID%% .oxy-repeater-pages-wrap').remove();
      jQuery('#%%ELEMENT_ID%% > .oxy-dynamic-list').css('display', 'grid');
      jQuery('#%%ELEMENT_ID%% > .oxy-dynamic-list').css('grid-template-columns', 'repeat(' + $builder_fix[0] + ', 1fr)');
      jQuery('#%%ELEMENT_ID%% > .oxy-dynamic-list').css('grid-column-gap', $builder_fix[1] + 'px');
      jQuery('#%%ELEMENT_ID%% > .oxy-dynamic-list').css('grid-row-gap', '10px');
      jQuery('#%%ELEMENT_ID%% > .oxy-dynamic-list').find('div').each(function() {
        jQuery(this).css('width', '100%');
      });
    }, 200); window['$' + '%%ELEMENT_ID%%'.replace(/-/g, '_')] = { $slider_basics };";

    $wc_splide_columns_images_divs = "if(jQuery('html').attr('ng-app') != 'CTFrontendBuilder'){jQuery('#%%ELEMENT_ID%% .splide__list').children().addClass('splide__slide'); var _ON_$slider_name = new Splide('#%%ELEMENT_ID%%', { $slider_basics }); _ON_$slider_name.mount($extension);}";

    $wc_splide_columns_id_builder = "function splideGridBreakpointGap(size) {
      switch (size) {
        case 'default':
          if (iframeScope.getOption('oxy-oxyninja_slider_sg_mw_g') === '') {
            return 25;
          } else {
            return iframeScope.getOption('oxy-oxyninja_slider_sg_mw_g');
          }
          break;
        case 'page-width':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_pw_g');
          break;
        case 'tablet':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_tablet_g');
          break;
        case 'phone-landscape':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_landscape_g');
          break;
        case 'phone-portrait':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_portrait_g');
          break;
      }
    }
    function splideGridBreakpoint(size) {
      switch (size) {
        case 'default':
          if (iframeScope.getOption('oxy-oxyninja_slider_sg_mw') === '') {
            return 4;
          } else {
            return iframeScope.getOption('oxy-oxyninja_slider_sg_mw');
          }
          break;
        case 'page-width':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_pw');
          break;
        case 'tablet':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_tablet');
          break;
        case 'phone-landscape':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_landscape');
          break;
        case 'phone-portrait':
          return iframeScope.getOption('oxy-oxyninja_slider_sg_portrait');
          break;
      }
    }
    setTimeout(function(){
        jQuery('#%%ELEMENT_ID%%').css('display', 'grid');
        jQuery('#%%ELEMENT_ID%%').css('grid-template-columns', 'repeat(' + $builder_fix[0] + ', 1fr)');
        jQuery('#%%ELEMENT_ID%%').css('grid-column-gap', $builder_fix[1] + 'px');
        jQuery('#%%ELEMENT_ID%%').css('grid-row-gap', '10px');
        jQuery('#%%ELEMENT_ID%%').find('img').each(function() {
          jQuery(this).css('width', '100%');
        });
        jQuery('#%%ELEMENT_ID%%').find('div').each(function() {
          jQuery(this).css('width', '100%');
        });
      }, 200); window['$' + '%%ELEMENT_ID%%'.replace(/-/g, '_')] = { $slider_basics }";
    
    $carousel_slider = "jQuery('#%%ELEMENT_ID%% .splide__list').children().addClass('splide__slide'); var _ON_$slider_name = new Splide('#%%ELEMENT_ID%%', { $slider_basics }); _ON_$slider_name.mount($extension);$glightbox_gallery";

    $gallery_slider = "var _ON_SEC_$slider_name = new Splide('#%%ELEMENT_ID%% .secondary-slider', { $slider_gallery_secondary }); _ON_SEC_$slider_name.mount();
    var _ON_$slider_name = new Splide('#%%ELEMENT_ID%% .primary-slider', { $slider_gallery_primary });
    _ON_$slider_name.sync(_ON_SEC_$slider_name).mount();$glightbox_gallery";

    // ACF + HappyFiles + Admin 2020 + Toolset
    if (isset($options['type']) && (esc_attr($options['type']) === 'happyfiles' || esc_attr($options['type']) === 'admin-2020') || esc_attr($options['type']) === 'acf' || esc_attr($options['type']) === 'toolset') {
      if (esc_attr($options['type']) === 'happyfiles') {
        $happy_terms = get_terms([
          'taxonomy' => 'happyfiles_category',
          'hide_empty' => false,
        ]);

        foreach ($happy_terms as $term) {
          if ($term->name === $happyfiles_field) :
            $termcat  = $term->term_id;
            $gallery = get_posts([
              'post_type' => 'attachment',
              'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
              'post_status' => ['publish', 'pending', 'draft', 'future', 'private','inherit'],
              'posts_per_page' => -1,
              'fields' => 'ids',
              'tax_query' => [[
                'taxonomy' => 'happyfiles_category',
                'field' => 'term_id',
                'terms' => $termcat]
              ]]
            );
          endif;
        }
      } elseif (esc_attr($options['type']) === 'admin-2020') {
        $gallery = get_posts( array(
          'post_type' => 'attachment',
          'post_mime_type' => 'image/jpeg,image/gif,image/jpg,image/png',
          'post_status' => ['publish', 'pending', 'draft', 'future', 'private','inherit'],
          'posts_per_page' => -1,
          'fields' => 'ids',
          'meta_query' => array(
            array(
              'key' => 'admin2020_folder',
              'value' => $admin2020_field,
              'compare' => '=',
            )
          )
        ));
        empty($admin2020_field) && $gallery = '';
      } elseif (esc_attr($options['type']) === 'acf') {
        $gallery = [];
        $gallery_q = get_field($acf_field) ?? '';
        if (is_array($gallery_q) && !empty($gallery_q)) {
          foreach ($gallery_q as $id) {
            array_push($gallery, $id['id']);
          }
          $gallery = array_values($gallery);
        }
      } elseif (esc_attr($options['type']) === 'toolset') {
        global $wpdb;
        $gallery = [];
        $gal_field = (types_render_field( $toolset_field, array( 'url' => 'true', 'separator' => ',' ) ));
        $gal_to_array = explode(",", $gal_field);
        if (is_array($gal_to_array)) {
          foreach ($gal_to_array as $image) {
            $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'attachment' AND guid=%s", $image));
            if ($attachment_id != NULL) {
              array_push($gallery, $attachment_id);
            } else {
              array_push($gallery, $image);
            }
          }
        }
      }
      
      if ($this->muzes() !== base64_decode('dmFsaWQ=')) {
        echo base64_decode('TWlzc2luZyBMaWNlbnNlIEtleQ==');
      } elseif (isset($gallery) && is_array($gallery))  {

        if (isset($options['splide_type']) && esc_attr($options['splide_type']) === 'carousel') {
        echo '<div class="splide__track">';
        echo '<' . $glightbox_gallery_ul . ' ' . 'class="splide__list">';
        foreach ($gallery as $image) :
          if (strlen($image) > 12) {
            echo '<' . $glightbox_gallery_li . ' ' . 'href="' . $image . '" class="glightbox">';
            echo '<img src="' . $image . '">';
          } else {
            echo '<' . $glightbox_gallery_li . ' ' . 'href="' . esc_url(wp_get_attachment_image_url($image, $size = 'large')) . '" class="glightbox">';
            echo wp_get_attachment_image($image, $thumb_size);
          }
          echo '</' . $glightbox_gallery_li . '>';
        endforeach;
        echo '</' . $glightbox_gallery_ul . '>';
        echo '</div>';
        $this->El->inlineJS($carousel_slider);

      } elseif (isset($options['splide_type']) && esc_attr($options['splide_type']) === 'gallery') {
        echo '<div class="primary-slider" style="position:relative;margin-bottom:' . $gal_gap_percent . ';">';
        echo '<div class="splide__track">';
        echo '<' . $glightbox_gallery_ul . ' ' . 'class="splide__list">';

        foreach ($gallery as $image) :
          if (strlen($image) > 12) {
            echo '<' . $glightbox_gallery_li . ' ' . 'href="' . $image . '" class="splide__slide glightbox">';
            echo '<img src="' . $image . '">';
          } else {
            echo '<' . $glightbox_gallery_li . ' ' . 'href="' . esc_url(wp_get_attachment_image_url($image, $size = 'large')) . '" class="splide__slide glightbox">';
            echo wp_get_attachment_image($image, $thumb_size);
          }
          echo '</' . $glightbox_gallery_li . '>';
        endforeach;

        echo '</' . $glightbox_gallery_ul . '>';
        echo '</div>';
        echo '</div>';

        echo '<div class="secondary-slider">';
        echo '<div class="splide__track">';
        echo '<ul class="splide__list">';

        foreach ($gallery as $image) :
          echo '<li class="splide__slide">';
          if (strlen($image) > 12) {
            echo '<img src="' . $image . '">';
          } else {
            echo wp_get_attachment_image($image, $thumb_size);
          }
          echo '</li>';
        endforeach;
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        $this->El->inlineJS($gallery_slider);
      }

    } else {
      if (esc_attr($options['type']) === 'happyfiles') {
        _e("No HappyFiles gallery data found", "oxy-ninja");
      } elseif (esc_attr($options['type']) === 'admin-2020') {
        _e("No Admin 2020 folder ID found", "oxy-ninja");
      } elseif (esc_attr($options['type']) === 'admin-2020') {
        _e("No ACF gallery field data found", "oxy-ninja");
      } elseif (esc_attr($options['type']) === 'toolset') {
        _e("No Toolset gallery field data found", "oxy-ninja");
      }
    }
    }

    // WooCommerce Gallery Output
    if (isset($options['type']) && esc_attr($options['type']) === 'woo-gallery') {
      $product = wc_get_product(get_the_ID());
      if ($this->muzes() !== base64_decode('dmFsaWQ=')) {
        echo base64_decode('TWlzc2luZyBMaWNlbnNlIEtleQ==');
      } elseif ($product) {
      echo '<div class="splide__track">';
      echo '<' . $glightbox_gallery_ul . ' ' . 'class="splide__list">';
      $attachment_ids = $product->get_gallery_image_ids();
      foreach ($attachment_ids as $image) :
        echo '<' . $glightbox_gallery_li . ' ' . 'href="' . esc_url(wp_get_attachment_image_url($image, $size = 'large')) . '" class="glightbox">';
        echo wp_get_attachment_image($image, $thumb_size);
        echo '</' . $glightbox_gallery_li . '>';
      endforeach;
      echo '</' . $glightbox_gallery_ul . '>';
      echo '</div>';
      $this->El->inlineJS($carousel_slider);
      } else {
        _e("No WooCommerce product found", "oxygen");
      }
    }

    // Images/Divs Output
    if (isset($options['type']) && esc_attr($options['type']) === 'id') {
      if ($this->muzes() !== base64_decode('dmFsaWQ=')) {
        echo base64_decode('TWlzc2luZyBMaWNlbnNlIEtleQ==');
      } elseif ($content) {
        echo '<div class="splide__track">';
        echo '<div class="splide__list">';
        echo do_shortcode($content);
        echo '</div>';
        echo '</div>';
        $this->El->builderInlineJS($wc_splide_columns_id_builder);
        $this->El->inlineJS($wc_splide_columns_images_divs);
      }
    }

    $gal_gap = isset($options['gal_gap_product']) ? esc_attr($options['gal_gap_product']) : 2.25;
    $gal_gap_percent = $gal_gap . '%';

    // Product Output
    if (isset($options['type']) && esc_attr($options['type']) === 'product') {
      if ($this->muzes() !== base64_decode('dmFsaWQ=')) {
        echo base64_decode('TWlzc2luZyBMaWNlbnNlIEtleQ==');
      } else {
        global $product, $post;
        $product = wc_get_product();

        !is_object($product) && $product = wc_get_product(get_the_ID());

        if ($product === false) {
          return;
        }

        $attachment_ids = [];
        $pravda = $product->has_child();

				$mix_match_aktive = class_exists( 'WC_Mix_and_Match' );

        if ($pravda && !$mix_match_aktive) {
          global $product;
          $variableProduct = new WC_Product_Variable(get_the_ID());
          $variationer = $variableProduct->get_available_variations();
          $same_variation = [];
          $varianty_check = [];
          $vsechny_varianty_check = [];
          $loop_stopper = false;
          foreach ($variationer as $vari) {
            $varid = $vari['variation_id'];
            $variation = new WC_Product_Variation($varid);
            $image_id = $variation->get_image_id();
            $image_v = $variation->get_id();
            $main_image = $product->get_image_id();
            if (!$loop_stopper) {
              array_push($same_variation, $image_id);
              array_push($varianty_check, $image_v);
            } else {
              if ($image_id != $main_image) {
                array_push($same_variation, $image_id);
                array_push($varianty_check, $image_v);
              }
            }
            $image_id == $main_image && $loop_stopper = true;
          }

          count(array_unique($same_variation)) == 1 && $varianty_check = [];

          // Check if duplicate
          if (!function_exists('has_dupes')) {
          function has_dupes($array) {
            $dupe_array = [];
            foreach ($array as $val) {
              !isset($dupe_array[$val]) && $dupe_array[$val] = 0;
              if (++$dupe_array[$val] >= count($array)) {
                  return false;
              }
            }
            return true;
          }
        }

          if (empty($varianty_check)) {
            $product_image = $product->get_image_id();

            !empty($product_image) && $attachment_ids[] = $product_image;

            $attachment_ids = array_merge($attachment_ids, $product->get_gallery_image_ids());
          } else {
            $attachment_ids = $varianty_check;
            $this->blby_variace = true;
          }
        } else {
          // Prevent errors on basic products
          $same_variation = 'retrograde';
          if (!function_exists('has_dupes')) {
            function has_dupes($array) {
                return true;
            }
          }

          $product_image = $product->get_image_id();

          !empty($product_image) && $attachment_ids[] = $product_image;

          $attachment_ids = array_merge($attachment_ids, $product->get_gallery_image_ids());
        }

        // DIV Wrapper Start
        echo '<div>';
        echo '<div id="primary-slider" style="position:relative;margin-bottom:' . $gal_gap_percent . ';">';
        echo '<div class="splide__track">';

        if (isset($options['product_badges']) &&
        esc_attr($options['product_badges']) === 'show') {
          echo '<div class="on-product-badges">';

          if (isset($options['product_sale_badge']) &&
          esc_attr($options['product_sale_badge']) === 'show') {
            Oxy_Ninja_Public::oxyninja_sale_badge_splide();
          }
          if (isset($options['product_new_badge']) &&
          esc_attr($options['product_new_badge']) === 'show') {
            Oxy_Ninja_Public::oxyninja_new_badge_splide();
          }
          if (isset($options['product_custom_badge']) &&
          esc_attr($options['product_custom_badge']) === 'show' && (class_exists('ACF') || function_exists('types_render_field'))) {
            if (class_exists('ACF')) :
              if ($gift = get_field('on_custom_product_badge')) :
                echo '<span class="on-custom-badge">';
                echo esc_html($gift);
                echo '</span>';
              endif;
            endif;
            if (function_exists('types_render_field')) :
              $gift = (types_render_field('on_toolset_badge'));
              if ($gift) :
                echo '<span class="on-custom-badge">';
                echo esc_html($gift);
                echo '</span>';
              endif;
            endif;
          }
          echo '</div>';
        }

        echo '<div class="splide__list">';
        $helper_array = array();
        foreach ($attachment_ids as $attachment_id) {
          if ($pravda && $this->blby_variace) {
            $variation = new WC_Product_Variation($attachment_id);
            $variationName = implode(" ", $variation->get_variation_attributes());
            $imageId = $variation->get_image_id();
            if (in_array($imageId, $helper_array)) {
              continue;
            } else {
              echo '<a class="splide__slide glightbox-product" prvni-id="' . $attachment_id . '" prvni-name="' . $variationName . '">';
              echo get_the_post_thumbnail($attachment_id, 'large');
              echo '</a>';
            }
            array_push($helper_array, $imageId);
          } else {
            echo '<a class="splide__slide glightbox-product">';
            echo wp_get_attachment_image($attachment_id, 'large');
            echo '</a>';
          }
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';

        echo '<div id="secondary-slider">';
        echo '<div class="splide__track">';

        echo '<ul class="splide__list">';
        if (isset($options['product_thumb']) &&
        esc_attr($options['product_thumb']) === 'yes' &&
        count($attachment_ids) > 1 && has_dupes($same_variation)) {
          $helper_array_dva = array();
          foreach ($attachment_ids as $attachment_id) {
            if ($pravda && $this->blby_variace) {
              $variation = new WC_Product_Variation($attachment_id);
              $variationName = implode(" ", $variation->get_variation_attributes());
              $imageId = $variation->get_image_id();
              if (in_array($imageId, $helper_array_dva)) {
                continue;
              } else {
                echo '<li class="splide__slide" druhy-id="' . $attachment_id . '" druhy-name="' . $variationName . '">';
                echo get_the_post_thumbnail($attachment_id, $thumb_size);
                echo '</li>';
              }
              array_push($helper_array_dva, $imageId);
              $attachment_ids = $helper_array_dva;
            } else {
              echo '<li class="splide__slide">';
              echo wp_get_attachment_image($attachment_id, $thumb_size);
              echo '</li>';
            }
          }
        } elseif (isset($options['product_thumb']) &&
        esc_attr($options['product_thumb']) === 'yes' &&
        count($attachment_ids) > 1) {
          foreach ($attachment_ids as $attachment_id) {
            echo '<li class="splide__slide">';
            echo wp_get_attachment_image($attachment_id, $thumb_size);
            echo '</li>';
          }
        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        // DIV Wrapper End
        echo '</div>';

        $under_filler = isset($options['gallery_fill']) ? esc_attr($options['gallery_fill']) : 'default';
        $gal_gap = isset($options['gal_gap_product']) ? esc_attr($options['gal_gap_product']) : 2.25;
        $gal_gap_percent = $gal_gap . '%';
        // Desktop
        $gal_images_desktop = count($attachment_ids);
        $gal_images_desktop > $under_main_mw && $gal_images_desktop = $under_main_mw;
        $under_filler === 'default' && $gal_images_desktop = $under_main_mw;
        $gal_size_calc_d = 100 - $gal_gap * ($gal_images_desktop - 1);
        $gal_size_d = $gal_size_calc_d / $gal_images_desktop . '%';
        // Page Width
        $gal_images_pw = count($attachment_ids);
        $gal_images_pw > $under_main_pw && $gal_images_pw = $under_main_pw;
        $under_filler === 'default' && $gal_images_pw = $under_main_pw;
        $gal_size_calc_pw = 100 - $gal_gap * ($gal_images_pw - 1);
        $gal_size_pw = $gal_size_calc_pw / $gal_images_pw . '%';
        // Tablet
        $gal_images_tablet = count($attachment_ids);
        $gal_images_tablet > $under_main_tablet && $gal_images_tablet = $under_main_tablet;
        $under_filler === 'default' && $gal_images_tablet = $under_main_tablet;
        $gal_size_calc_t = 100 - $gal_gap * ($gal_images_tablet - 1);
        $gal_size_t = $gal_size_calc_t / $gal_images_tablet . '%';
        // LandScape
        $gal_images_ls = count($attachment_ids);
        $gal_images_ls > $under_main_landscape && $gal_images_ls = $under_main_landscape;
        $under_filler === 'default' && $gal_images_ls = $under_main_landscape;
        $gal_size_calc_ls = 100 - $gal_gap * ($gal_images_ls - 1);
        $gal_size_ls = $gal_size_calc_ls / $gal_images_ls . '%';
        // Portrait
        $gal_images_port = count($attachment_ids);
        $gal_images_port > $under_main_portrait && $gal_images_port = $under_main_portrait;
        $under_filler === 'default' && $gal_images_port = $under_main_portrait;
        $gal_size_calc_port = 100 - $gal_gap * ($gal_images_port - 1);
        $gal_size_port = $gal_size_calc_port / $gal_images_port . '%';

        if ($pravda) {
            $varianty_js = count($same_variation);
            $varianty_js_dva = count(array_unique($same_variation));
        }

        $glightbox_product = false;
        if (isset($options['product_effect']) &&
        esc_attr($options['product_effect']) === 'lightbox') {
          add_action('wp_footer', array( $this, 'glightbox_js' ));
          $glightbox_product = 'window.addEventListener("DOMContentLoaded", (e) => {
            var lightboxProduct = GLightbox({ selector: "#%%ELEMENT_ID%% .glightbox-product img", touchNavigation: true, loop: true});});';
        }

        $zoomer = false;
        if (isset($options['product_effect']) && esc_attr($options['product_effect']) === 'zoom') {
          add_action('wp_footer', array( $this, 'zoom_js' ));
          $zoomer = 'window.addEventListener("load", (e) => { jQuery("#primary-slider .splide__slide").zoom();});';
        }

        $product_height = $custom_sg_mw_height;
        if ($aspect_ratio === 'square') {
          $product_height = "heightRatio: 1";
          $custom_sg_pw_height = "height: 0";
          $custom_sg_tablet_height = "height: 0";
          $custom_sg_landscape_height = "height: 0";
          $custom_sg_portrait_height = "height: 0";
        }

        if ($pravda && $varianty_js == $varianty_js_dva && $this->blby_variace) {
          $this->El->inlineJS("var secondarySlider = new Splide('#%%ELEMENT_ID%% #secondary-slider', {
            fixedWidth: '$gal_size_d',
            fixedHeight: '$gal_size_d',
            isNavigation: true,
            gap: '$gal_gap_percent',
            pagination: false,
            arrows: false,
            cover: true,
            updateOnMove: true,
            keyboard: 'focused',
            lazyLoad: $lazy_load,
            breakpoints: {
              $sg_pw_bp: {
                fixedWidth: '$gal_size_pw',
                fixedHeight: '$gal_size_pw',
              },
              $sg_tablet_bp: {
                fixedWidth: '$gal_size_t',
                fixedHeight: '$gal_size_t',
              },
              $sg_landscape_bp: {
                fixedWidth: '$gal_size_ls',
                fixedHeight: '$gal_size_ls',
              },
              $sg_portrait_bp: {
                fixedWidth: '$gal_size_port',
                fixedHeight: '$gal_size_port',
              },
            },
          }).mount();

          var primarySlider = new Splide('#%%ELEMENT_ID%% #primary-slider', {
            type: 'fade',
            updateOnMove: true,
            pagination: false,
            $product_height,
            rewind: $rewind,
            arrows: $arrows,
            $custom_autoplay,
            $custom_interval,
            $custom_pauseOnHover,
            pauseOnFocus: true,
            keyboard: 'focused',
            lazyLoad: $lazy_load,
            speed: $speed,
            $custom_arrows_classes,
            breakpoints: {
              $sg_pw_bp: {
                $custom_sg_pw_height,
              },
              $sg_tablet_bp: {
                $custom_sg_tablet_height,
              },
              $sg_landscape_bp: {
                $custom_sg_landscape_height,
              },
              $sg_portrait_bp: {
                $custom_sg_portrait_height,
              },
            },
          });

          primarySlider.sync(secondarySlider).mount(); primarySlider.on( 'moved', function(e) {
            let slide = primarySlider.root?.childNodes[1]?.childNodes[0]?.childNodes[e];
            let x = slide.getAttribute('prvni-id');
            let y = slide.getAttribute('prvni-name');
            let z = y.match(/\S+[a-z]*/g);
            if (jQuery(\".variations_form select\").val() != y) {
            for (let i = 0; i < z.length; i++) {
            jQuery(jQuery(\".variations_form select\")[i]).val(z[i]).trigger('change');
          }}});$glightbox_product $zoomer");

          wp_enqueue_script(
            'splide-variation',
            plugin_dir_url(__FILE__) . 'splide_variation.js',
            '',
            '1.0.0'
          );
        } else {
          $this->El->inlineJS("var secondarySlider = new Splide('#%%ELEMENT_ID%% #secondary-slider', {
            fixedWidth: '$gal_size_d',
            fixedHeight: '$gal_size_d',
            isNavigation: true,
            gap: '$gal_gap_percent',
            pagination: false,
            arrows: false,
            cover: true,
            updateOnMove: true,
            keyboard: 'focused',
            lazyLoad: $lazy_load,
            breakpoints: {
              $sg_pw_bp: {
                fixedWidth: '$gal_size_pw',
                fixedHeight: '$gal_size_pw',
              },
              $sg_tablet_bp: {
                fixedWidth: '$gal_size_t',
                fixedHeight: '$gal_size_t',
              },
              $sg_landscape_bp: {
                fixedWidth: '$gal_size_ls',
                fixedHeight: '$gal_size_ls',
              },
              $sg_portrait_bp: {
                fixedWidth: '$gal_size_port',
                fixedHeight: '$gal_size_port',
              },
            },
          }).mount();

          var primarySlider = new Splide('#%%ELEMENT_ID%% #primary-slider', {
            type: 'fade',
            updateOnMove: true,
            pagination: false,
            $product_height,
            rewind: $rewind,
            arrows: $arrows,
            $custom_autoplay,
            $custom_interval,
            $custom_pauseOnHover,
            pauseOnFocus: true,
            keyboard: 'focused',
            lazyLoad: $lazy_load,
            speed: $speed,
            $custom_arrows_classes,
            breakpoints: {
              $sg_pw_bp: {
                $custom_sg_pw_height,
              },
              $sg_tablet_bp: {
                $custom_sg_tablet_height,
              },
              $sg_landscape_bp: {
                $custom_sg_landscape_height,
              },
              $sg_portrait_bp: {
                $custom_sg_portrait_height,
              },
            },
          });

          primarySlider.sync(secondarySlider).mount(); $glightbox_product $zoomer");
        }
      }
    }

    if (isset($options['type']) && esc_attr($options['type']) === 'carousel') {
      if ($this->muzes() !== base64_decode('dmFsaWQ=')) {
        echo base64_decode('TWlzc2luZyBMaWNlbnNlIEtleQ==');
      } elseif ($content) {
        echo '<div class="splide__track">';
        if (isset($options['is_related']) &&
        esc_attr($options['is_related']) === 'related_yes') {
          global $post;
          if (empty(wc_get_related_products($post->ID))){
            return;
          }
          if (is_array(wc_get_related_products( $post->ID ))) {
            function on_dynamic_category_query($query) {
              global $post;
              if ($query->query['post_type'][0] == 'product') {
                  $query->set('orderby', 'rand');
                  $query->set('post__in', wc_get_related_products($post->ID, 10));
                  $query->set('no_found_rows', true);
              }
            }
          } else {
            function on_dynamic_category_query($query) {
              if ($query->query['post_type'][0] == 'product') {
                  $cat = wp_get_post_terms($post->ID, 'product_cat',
                  ['fields' => 'slugs',])[0];
                  $query->set('tax_query',
                  [
                    'relation' => 'AND',
                    [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $cat,
                    'include_children' => false
                    ],
                  ]
                  );
                  $query->set('orderby', 'rand');
                  $query->set('post__not_in', [$post->ID]);
                  $query->set('no_found_rows', true);
              }
            }
          }
          add_action('pre_get_posts', 'on_dynamic_category_query');

          echo do_shortcode($content);

          remove_action('pre_get_posts', 'on_dynamic_category_query');

          $this->El->inlineJS($wc_splide_columns);
          $this->El->builderInlineJS($wc_splide_columns_builder);
        } else {
          echo do_shortcode($content);
          $this->El->inlineJS($wc_splide_columns);
          $this->El->builderInlineJS($wc_splide_columns_builder);
        }
        echo '</div>';
      }
    }
    // Enqueue Scripts
    if ($this->js_added !== true) {
      add_action('wp_footer', function () {
        echo '<script type="text/javascript" id="splide-navi">jQuery( ".oxyninja" ).on("click",function() {
          let string = jQuery( this ).attr( "nav" ).split("-");
          let animation = "_ON_" + string[0].toUpperCase();
          if (string[1] === "fwd") {
            window[animation].go("+1");
          } else if (string[1] === "bwd") {
            window[animation].go("-1");
          }
        });</script>';
      });
      $this->js_added = true;
    }
  }

  function afterInit() {
    $this->removeApplyParamsButton();
  }

  function load_splide() {
    wp_enqueue_script('splide');
    wp_enqueue_style('splide');
    wp_enqueue_script('splide-autoscroll');

    wp_enqueue_script( 'wc-single-product' );
  }

  function glightbox_js() {
    if ($this->glight_added !== true) {
      wp_enqueue_script('glightbox');
      wp_enqueue_style('glightbox');
      $this->glight_added = true;
    }
  }

  function zoom_js() {
    if ($this->zoom_added !== true) {
      if (current_theme_supports('wc-product-gallery-zoom')) {
        wp_enqueue_script('zoom');
      }
      $this->zoom_added = true;
    }
  }
}

new OXYNINJA_Splide_Carousel();