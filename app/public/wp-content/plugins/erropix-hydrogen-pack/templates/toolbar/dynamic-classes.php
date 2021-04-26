<div class="hydrogen-dynamic-classes ng-scope" ng-if="isShowTab('advanced', 'dynamic-classes')">
    <div class="oxygen-control-row">
        <div class="oxygen-control-wrapper">
            <a href="#" class="oxygen-ghost-button" ng-click="iframeScope.addDynamicClass()">Add Dynamic Class</a>
        </div>
    </div>

    <div class="hydrogen-dynamic-class-wrap" ng-repeat="className in iframeScope.component.options[iframeScope.component.active.id]['model']['dynamic-classes'] track by $index" ng-if="className!=null">
        <span class="ct-icon ct-remove-icon hydrogen-remove-dynamic-class" ng-Click="iframeScope.removeDynamicClass($event, $index);"></span>

        <div class="oxygen-control-row">
            <div class="oxygen-control-wrapper">
                <label class="oxygen-control-label">Class</label>

                <div class="oxygen-control">
                    <div class="oxygen-input">
                        <input type="text" spellcheck="false" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'dynamic-classes')" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['dynamic-classes'][$index]['value']">

                        <div class="oxygen-dynamic-data-browse" ctdynamicdata data="iframeScope.dynamicShortcodesContentMode" callback="iframeScope.insertShortcodeToDynamicClass" optionname="'dynamic-classes.'+$index+'.value'">data</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="oxygen-control-row">
            <div class="oxygen-control-wrapper">
                <label class="oxygen-control-label">Notes</label>

                <div class="oxygen-control">
                    <div class="oxygen-input">
                        <input type="text" spellcheck="false" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'dynamic-classes')" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['dynamic-classes'][$index]['notes']">
                    </div>
                </div>
            </div>
        </div>

        <div class="oxygen-control-row">
            <div class="oxygen-control-wrapper">
                <label class="oxygen-checkbox">
                    <input type="checkbox" ng-model="iframeScope.component.options[iframeScope.component.active.id]['model']['dynamic-classes'][$index]['sanitize']" ng-change="iframeScope.setOption(iframeScope.component.active.id, iframeScope.component.active.name,'dynamic-classes')" class="ng-pristine ng-untouched ng-valid">
                    <div class="oxygen-checkbox-checkbox" ng-class="{'oxygen-checkbox-checkbox-active':iframeScope.getOption('dynamic-classes.'+$index+'.sanitize')==true}">Sanitize data output <div class="oxy-tooltip">?<div class="oxy-tooltip-text">Remove all whitespace and invalid characters from the dynamic data output.</div>
                        </div>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div>