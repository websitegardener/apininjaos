<?php
if (!defined('ABSPATH')) {
  exit();
}

function on_right_panel_tabs() {
  global $oxygen_toolbar; ?>

<div class="oxygen-sidebar-advanced-subtab oxygen-settings-main-tab on-main-tab" ng-click="switchTab
('settings','oxyninja');" ng-hide="hasOpenTabs('settings')">
    <img style="width:17px;margin-left:7px;" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA5NS4xOCAxMTQuMDMiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDojZjJmMmYyO30uY2xzLTJ7ZmlsbDojZTZlNmU2O30uY2xzLTN7ZmlsbDojMzMzO308L3N0eWxlPjwvZGVmcz48ZyBpZD0iVnJzdHZhXzIiIGRhdGEtbmFtZT0iVnJzdHZhIDIiPjxnIGlkPSJMYXllcl8xIiBkYXRhLW5hbWU9IkxheWVyIDEiPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTQ3LjU5LDE4Ljg1QTQ3LjU5LDQ3LjU5LDAsMSwwLDk1LjE4LDY2LjQ0LDQ3LjU5LDQ3LjU5LDAsMCwwLDQ3LjU5LDE4Ljg1WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTk1LjE4LDY2LjQ0QTQ3LjYxLDQ3LjYxLDAsMCwwLDYxLDIwLjc3Qzc3Ljk0LDUwLjMyLDQ2Ljc2LDYyLjk0LDQ2Ljc2LDYyLjk0UzYzLjU5LDc0LjU3LDU0LjUyLDk0LjJjNS42OSw2LjI3LDIuMDYsMTguMjMtMS42MSwxOS41M0E0Ny41OSw0Ny41OSwwLDAsMCw5NS4xOCw2Ni40NFoiLz48cGF0aCBjbGFzcz0iY2xzLTMiIGQ9Ik00Ny42Niw2Ny4zQTU3Ljc0LDU3Ljc0LDAsMCwxLDEyLDU1VjkxLjkxaDcxLjNWNTVBNTcuNyw1Ny43LDAsMCwxLDQ3LjY2LDY3LjNaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMjEuMjksNzIuMzVDMTkuNDcsNzIuMzUsMTgsNzQuNDQsMTgsNzdzMS40Nyw0LjY3LDMuMjksNC42N1MyNC41Nyw3OS42LDI0LjU3LDc3LDIzLjEsNzIuMzUsMjEuMjksNzIuMzVaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNTAuNDcsMjEuNTNsMjQtMTEuOHM0LDUuNzQtNS43OCwxNGMwLDAtNi45Miw0LjgyLTcuMywzLjIxQzYwLjY4LDIzLjg1LDUwLjQ3LDIxLjUzLDUwLjQ3LDIxLjUzWiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTU5LjQ3LDQuMjksNDQuMiwyMC4xN3MzLjcyLDEuOTQsMTEuMzEuMjlTNjIuNzcsNi4yNyw1OS40Nyw0LjI5WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTU5LjcxLDBsLTE3LDIyTDU5LjQyLDQuN1M2MS4yLDEyLjc5LDU4Ljg5LDE4bDE0LjI3LTYuMXMxLjMyLDkuMjQtMTcuNDEsMTdjMCwwLDguNzItLjI5LDE0LjU2LTQuOTVDNzcuNDUsMTguMjMsNzYuNzksMTIsNzYsOEw2Mi43NywxNC4xMVM2My44NCw0LjYyLDU5LjcxLDBaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNzQuNzYsNzIuMzVjLTEuODEsMC0zLjI5LDIuMDktMy4yOSw0LjY3czEuNDgsNC42NywzLjI5LDQuNjdTNzguMDUsNzkuNiw3OC4wNSw3Nyw3Ni41OCw3Mi4zNSw3NC43Niw3Mi4zNVoiLz48L2c+PC9nPjwvc3ZnPg==" />
    <?php _e("OxyNinja", "oxygen"); ?>
    <img src='<?php echo CT_FW_URI; ?>/toolbar/UI/oxygen-icons/advanced/open-section.svg' />
    </div>
    
    <div class="oxygen-sidebar-flex-panel" ng-if="isShowTab('settings','oxyninja')&&!hasOpenChildTabs('settings','oxyninja')">
    <?php $oxygen_toolbar->settings_home_breadcrumbs(__("OxyNinja", "oxygen")); ?>
    <?php do_action("on_right_panel_tabs_oxyninja"); ?>
    </div><?php
}

function on_right_panel_id_class_lock() { ?>
<div ng-init="lock = ['class','core', 'fw']">
    <div class='oxygen-control-wrapper' style='margin-bottom:14px;'>
        <label class="oxygen-control-label">ID/Class Lock</label>
        <div class='oxygen-control'>
            <div class="oxygen-button-list">
                <div class="oxygen-button-list-button on-button-hover" ng-repeat="key in lock track by key" ng-click="oxyninjaHelpersState(key)" ng-class="{'on-button-hover-active':iframeScope.elementPresets.oxyninja['type']==key}">{{key === "class" ? "Single: All" : key === "core" ? "Single: Core" : "Global: Core"}}</div>
            </div>
        </div>
    </div>
</div>
<?php
}

function on_right_panel_selectors_copy_paste() { ?>
<div class='oxygen-control-wrapper' style='margin-bottom:14px;flex-grow:unset;flex-basis:unset;'>
    <label class="oxygen-control-label">Copy/Paste All Selectors Between Sites</label>
    <div class='oxygen-control'>
        <div class="oxygen-button-list">
            <div class="oxygen-button-list-button on-button-hover" ng-click="oxyNinjaSelectorsCopy('selectors');">Copy</div>
            <div class="oxygen-button-list-button on-button-hover" onClick="oxyNinjaPasteRouter('selectors');">Paste</div>
        </div>
    </div>
</div>
<?php
}

function on_right_panel_settings_copy_paste() { ?>
    <div class='oxygen-control-wrapper' style='margin-bottom:14px;'>
        <label class="oxygen-control-label">Copy/Paste All Settings Between Sites</label>
        <div class='oxygen-control'>
            <div class="oxygen-button-list">
                <div class="oxygen-button-list-button on-button-hover" ng-click="oxyNinjaSelectorsCopy('settings');">Copy</div>
                <div class="oxygen-button-list-button on-button-hover" onClick="oxyNinjaPasteRouter('settings');">Paste</div>
            </div>
        </div>
    </div>
    <?php
}

$kjas = trim( get_option( base64_decode('b3h5bmluamFfbGljZW5zZV9zdGF0dXM=') ) ) ?: false;

if ($kjas == base64_decode('dmFsaWQ=')) {
  add_action("oxygen_vsb_settings_content", "on_right_panel_tabs");
  add_action("on_right_panel_tabs_oxyninja", "on_right_panel_id_class_lock");
  add_action("on_right_panel_tabs_oxyninja", "on_right_panel_selectors_copy_paste");
  // add_action("on_right_panel_tabs_oxyninja", "on_right_panel_settings_copy_paste");
}
