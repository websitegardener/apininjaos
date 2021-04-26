<?php

if (!defined('ABSPATH')) {
  exit();
}

add_action("oxygen_add_plus_oxyninja_helpers", "on_right_panel_helpers");

function on_right_panel_helpers() {
  $arkos = trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9rZXk=') ) );
  $markos = trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=') ) );
  $type = 'oxyninja/helpers';
  if ($arkos && $markos === base64_decode('dmFsaWQ=')) {
    $result = Oxy_Ninja_Admin::ct_new_api_remote_get_on(
      'http://core.oxyninja.com',
      $type . '/',
      $arkos
    );
    $result2 = json_decode($result, true);
    if (array_key_exists("error",$result2)) {
      echo "<div>Please, contact OxyNinja Support.</div>";
    } else {
      foreach ($result2 as $key => $tab): ?>
        <div class="oxygen-add-section-element"
        data-searchid= "<?php echo str_replace(' ', '_', strtolower($tab['component']['options']['nicename']) ) ?>"
        data-searchname="<?php echo $tab['component']['options']['nicename'] ?>"
        data-searchcat="OxyNinja"
        ng-click="oxyninjaAddHelpers('<?php echo base64_encode(
        wp_json_encode($tab)
        ); ?>')">
        <?php switch ($tab['component']['options']['nicename']) {
          case "6 Columns":
            $icon_value = "6cols";
            break;
          case "5 Columns":
            $icon_value = "5cols";
            break;
          case "4 Columns":
            $icon_value = "4cols";
            break;
          case "3 Columns":
            $icon_value = "3cols";
            break;
          case "2 Columns":
            $icon_value = "2cols";
            break;
          case "2 Columns Full":
            $icon_value = "2cols-full";
            break;
          case "3 to 2 Columns":
            $icon_value = "3-2cols";
            break;
          case "2 to 3 Columns":
            $icon_value = "2-3cols";
            break;
          case "3 to 1 Columns":
            $icon_value = "3-1cols";
            break;
          case "1 to 3 Columns":
            $icon_value = "1-3cols";
            break;
          case "2 to 1 Columns":
            $icon_value = "2-1cols";
            break;
          case "1 to 2 Columns":
            $icon_value = "1-2cols";
            break;
          case "Grid 1":
            $icon_value = "grid-1";
            break;
          case "Grid 2":
            $icon_value = "grid-2";
            break;
          case "Grid 3":
            $icon_value = "grid-3";
            break;
          case "Grid 4":
            $icon_value = "grid-4";
            break;
          case "Button with icon":
            $icon_value = "button-with-icon";
            break;
          case "2 Buttons with icons":
            $icon_value = "2-Inline-buttons-with-icons";
            break;
          default:
            $icon_value = "default";
        } ?>
        <img style="padding:5px;" src="<?php echo OXYNINJA_URI . '/icons/' .
          $icon_value .
          '.svg'; ?>">
        <img style="padding:5px;" src="<?php echo OXYNINJA_URI . '/icons/' .
          $icon_value .
          '.svg'; ?>">
        <?php echo $tab['component']['options']['nicename']; ?>
      </div>
      <?php endforeach;
    }
  }
}
