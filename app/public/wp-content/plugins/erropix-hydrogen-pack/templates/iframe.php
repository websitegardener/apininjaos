<div hydrogen-pack>
    <?php if ($settings->clipboard->enabled) : ?>
        <textarea id="hydrogen-clipboard-input"></textarea>
    <?php endif ?>

    <?php if ($settings->contextMenu->enabled) : ?>
        <div id="hydrogen-contextual-menu" style="display:none">
            <?php
            $items = $settings->contextMenu->items;
            $clipboardEnabled = $settings->clipboard->enabled;
            ?>

            <?php if ($items->duplicate) : ?>
                <div data-command="duplicate">Duplicate</div>
            <?php endif ?>

            <?php if ($items->copy && $clipboardEnabled) : ?>
                <div data-command="copy">Copy</div>
            <?php endif ?>

            <?php if ($items->copyStyle && $clipboardEnabled) : ?>
                <div data-command="copyStyle">Copy Style</div>
            <?php endif ?>

            <?php if ($items->copyConditions && $clipboardEnabled) : ?>
                <div data-command="copyConditions" ng-class="{'disabled':!hydroScope.hasConditions}">Copy Conditions</div>
            <?php endif ?>

            <?php if ($items->cut && $clipboardEnabled) : ?>
                <div data-command="cut">Cut</div>
            <?php endif ?>

            <?php if ($items->paste && $clipboardEnabled) : ?>
                <div data-command="paste" ng-class="{'disabled':!hydroScope.clipboard}">Paste</div>
            <?php endif ?>

            <?php if ($items->saveReusable) : ?>
                <div data-command="saveReusable" ng-show="hydroScope.canSaveReusable">Make Re-usable</div>
            <?php endif ?>

            <?php if ($items->saveBlock) : ?>
                <div data-command="saveBlock" ng-show="hydroScope.canSaveBlock">Copy to Block</div>
            <?php endif ?>

            <?php if ($items->wrap) : ?>
                <div data-command="wrap">Wrap With DIV</div>
            <?php endif ?>

            <?php if ($items->wrapLink) : ?>
                <div data-command="wrapLink" ng-class="{'disabled':!hydroScope.canWrapLink}">Wrap With Link</div>
            <?php endif ?>

            <?php if ($items->switchTextComponent) : ?>
                <div data-command="switchTextComponent" ng-show="hydroScope.isTextComponent">Convert to {{hydroScope.convertTextLabel}}</div>
            <?php endif ?>

            <?php if ($items->showConditions) : ?>
                <div data-command="showConditions">Set Conditions</div>
            <?php endif ?>

            <?php if ($items->rename) : ?>
                <div data-command="rename">Rename</div>
            <?php endif ?>

            <?php if ($items->changeId) : ?>
                <div data-command="changeId">Change ID</div>
            <?php endif ?>

            <?php if ($items->delete) : ?>
                <div data-command="delete">Delete</div>
            <?php endif ?>
        </div>
    <?php endif ?>
</div>