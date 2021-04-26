<?php

/* Code from https://github.com/A-Maged/Build-Your-First-Custom-OxygenBuilder-Element */
class ON_HELPER_INTEGRATION
{
  /* we will need this later to show subsections */
  public $section_slug = "oxyninja";

  /* we will need this later to attach an element to a specific subsection */
  public $tab_slug = "oxyninja";

  /* slugs for different subsection (will be used later inside our elements) */
  public $subsection_helpers = "helpers";
  public $subsection_elements = "elements";

  public function __construct()
  {
    /* show a section in +Add */
    if (get_option('oxygen_vsb_enable_3rdp_designsets')) {
        add_action('oxygen_add_plus_sections', [$this, 'add_plus_sections']);
    }

    /* +Add subsections content */
    /* oxygen_add_plus_{$id}_section_content */
    if (get_option('oxygen_vsb_enable_3rdp_designsets')) {
        add_action("oxygen_add_plus_" . $this->section_slug . "_section_content", [
          $this, 'add_plus_subsections_content' ]);
    }
  }

  public function add_plus_sections()
  {
    /* show a section in +Add dropdown menu and name it "OxyNinja" */
    CT_Toolbar::oxygen_add_plus_accordion_section(
      $this->section_slug,
      __("OxyNinja")
    );
  }
 
  public function add_plus_subsections_content()
  {
      $dbksn = trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=') ) ) ?: false;

      if ($dbksn == base64_decode('dmFsaWQ=')) {
          echo "<h2>Helpers</h2>";
          do_action("oxygen_add_plus_" . $this->tab_slug . "_helpers");
    
          echo "<h2>Elements</h2>";
          do_action("oxygen_add_plus_" . $this->tab_slug . "_elements");
      } else {
        echo '<p>Please, add the OxyNinja Plugin <span style="background: linear-gradient(180deg, #26A0F5 0%, #0C89E1 100%);padding: 0px 10px;">license key</span> to the Oxygen > Settings > Licenses</p>';
      }
  }

}
