<div hydrogen-pack>
    <?php if ($settings->contentEditorEnhancer->enabled) : ?>
        <textarea id="hydrogen-link-input" style="display:none;"></textarea>
    <?php endif ?>

    <?php if ($settings->conditionsEnhancer->enabled) : ?>
        <script type="text/template" id="tpl-conditions-modal-header">
            <div>
                Show element if
                <label class="condition-type-toggle" ng-class="{'active':iframeScope.getOption('conditionstype')==''}">
                    <input type="radio" name="conditions_type" value="1" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['conditionstype']" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'conditionstype');iframeScope.checkResizeBoxOptions('conditionstype'); evalGlobalConditions(); evalGlobalConditionsInList()"> ALL
                </label>
                <label class="condition-type-toggle" ng-class="{'active':iframeScope.getOption('conditionstype')=='1'}">
                    <input type="radio" name="conditions_type" value="" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['conditionstype']" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'conditionstype');iframeScope.checkResizeBoxOptions('conditionstype'); evalGlobalConditions(); evalGlobalConditionsInList()"> ANY
                </label>
                of these conditions are true
            </div>
            <svg class="oxygen-close-icon" ng-click="hideDialogWindow()">
                <use xlink:href="#oxy-icon-cross"></use>
            </svg>
        </script>

        <script type="text/template" id="tpl-conditions-clear-button">
            <a ng-click="showConditionsMenu=false;iframeScope.clearConditions();" class="oxy-condition-menu-button">Clear</a>
        </script>
    <?php endif ?>

    <?php if ($settings->classLock->enabled) : ?>
        <?php
        $toolbar_icons_base = CT_FW_URI . "/toolbar/UI/oxygen-icons/currently-editing";
        $hydrogen_icons_base = $this->url("assets/images");
        ?>
        <script id="tpl-oxygen-classes-dropdown" type="text/html">
            <li>
                <input type="text" class="oxygen-classes-dropdown-input" placeholder="Enter class name..." ng-model="iframeScope.newcomponentclass.name" ng-change="iframeScope.updateSuggestedClasses()" ng-keypress="iframeScope.processClassNameInput($event, iframeScope.component.active.id)" focus-me="$parent.ctSelectBoxFocus" />
                <div class="oxygen-classes-dropdown-add-class" ng-click="iframeScope.tryAddClassToComponent(iframeScope.component.active.id)">add class...</div>
            </li>

            <li class="oxygen-classes-dropdown-heading" ng-show="iframeScope.suggestedClasses.length">
                <div>Suggested Classes</div>
            </li>

            <li class="oxygen-classes-dropdown-suggestions" ng-show="iframeScope.suggestedClasses.length">
                <ul class="oxygen-classes-suggestions">
                    <li ng-repeat="(key, className) in iframeScope.suggestedClasses" ng-click="iframeScope.addSuggestedClassToComponent(className)">
                        <div class='oxygen-active-selector-box-class'>class</div>
                        <div>{{className}}</div>
                    </li>
                </ul>
            </li>

            <li class="oxygen-classes-dropdown-heading" ng-show="iframeScope.suggestedClasses.length">
                <div>Existing Selectors</div>
            </li>

            <li ng-click="iframeScope.switchEditToId(true)" ng-hide="copySelectorFromClass||copySelectorFromID">
                <div class='oxygen-active-selector-box-id'>id</div>
                <div>{{iframeScope.getComponentSelector()}}</div>
                <img src='<?= $toolbar_icons_base ?>/copy-styles-to.svg' title="Copy styles to another selector" ng-click="activateCopySelectorMode(false,$event)" />
                <img src='<?= $toolbar_icons_base ?>/clear-styles.svg' class="oxygen-no-margin" title="Delete all styles from this selector" ng-click="iframeScope.clearSelectorOptions();$event.stopPropagation()" />
                <img src='<?= $toolbar_icons_base ?>/delete-selector.svg' class="oxygen-no-margin oxygen-disabled" />
            </li>

            <li ng-repeat="(key,className) in iframeScope.componentsClasses[iframeScope.component.active.id]" title="{{className}}" ng-hide="copySelectorFromClass||copySelectorFromID" ng-click="iframeScope.setCurrentClass(className)" ng-class="{'oxygen-class-locked':iframeScope.isClassLocked(className)}">
                <div class='oxygen-active-selector-box-class'>class</div>
                <div class='oxygen-active-selector-box-classname'>{{className}}</div>

                <img src='<?= $hydrogen_icons_base ?>/lock.svg' title="Lock class" ng-show="!iframeScope.isClassLocked(className)" ng-click="iframeScope.lockClass(className,$event)" />
                <img src='<?= $hydrogen_icons_base ?>/unlock.svg' title="Unlock class" ng-show="iframeScope.isClassLocked(className)" ng-click="iframeScope.unlockClass(className,$event)" />

                <img src='<?= $toolbar_icons_base ?>/copy-styles-to.svg' class="oxygen-no-margin" title="Copy styles to another selector" ng-click="$parent.activateCopySelectorMode(className,$event)" />
                <img src='<?= $toolbar_icons_base ?>/clear-styles.svg' class="oxygen-no-margin" title="Delete all styles from this selector" ng-click="iframeScope.clearSelectorOptions(className);$event.stopPropagation()" ng-class="{'oxygen-disabled':iframeScope.isClassLocked(className)}" />
                <img src='<?= $toolbar_icons_base ?>/delete-selector.svg' class="oxygen-no-margin" title="Remove class from component" ng-click="iframeScope.removeComponentClass(className)" />
            </li>

            <li title="Copy Styles Here" ng-click="iframeScope.copySelectorOptions()" ng-show="copySelectorFromClass||copySelectorFromID">
                <div class='oxygen-active-selector-box-id'>id</div>
                <div ng-class="{'oxygen-disabled':copySelectorFromID}">{{iframeScope.getComponentSelector()}}</div>
                <img src='<?= $toolbar_icons_base ?>/copy-styles-to.svg' ng-click="deactivateCopySelectorMode($event)" ng-class="{'ct-link-button-highlight':copySelectorFromID,'oxygen-disabled':!copySelectorFromID}" />
                <img src='<?= $toolbar_icons_base ?>/clear-styles.svg' class="oxygen-no-margin oxygen-disabled" />
                <img src='<?= $toolbar_icons_base ?>/delete-selector.svg' class="oxygen-no-margin oxygen-disabled" />
            </li>

            <li title="Copy Styles Here" ng-click="iframeScope.copySelectorOptions(className)" ng-show="copySelectorFromClass||copySelectorFromID" ng-repeat="(key,className) in iframeScope.componentsClasses[iframeScope.component.active.id]" ng-if="!iframeScope.isClassLocked(className)">
                <div class='oxygen-active-selector-box-class'>class</div>
                <div ng-class="{'oxygen-disabled':copySelectorFromClass==className}">{{className}}</div>
                <img src='<?= $toolbar_icons_base ?>/copy-styles-to.svg' ng-click="deactivateCopySelectorMode($event)" ng-class="{'ct-link-button-highlight':copySelectorFromClass==className,'oxygen-disabled':copySelectorFromClass!=className}" />
                <img src='<?= $toolbar_icons_base ?>/clear-styles.svg' class="oxygen-no-margin oxygen-disabled" />
                <img src='<?= $toolbar_icons_base ?>/delete-selector.svg' class="oxygen-no-margin oxygen-disabled" />
            </li>
        </script>
    <?php endif ?>
</div>

<div id="cf-rocket-loader-notice" style="display: none;">
    <b>Hydrogen Pack</b> doesn't work when Cloudflare's Rocket Loader is active! Please check <a href="https://www.cleanplugins.com/blog/disable-cloudflare-rocket-loader-in-the-oxygen-builder/" target="_blank">our guide</a> to fix this issue.
</div>