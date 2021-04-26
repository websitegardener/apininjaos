<section class="section" id="section-contextmenu" data-dependon="#settings_contextmenu_enabled">
    <div class="section-header">
        <h3 class="section-title">Right-Click Menu</h3>
    </div>

    <div class="section-body">
        <?php
        $menuItems = [
            'duplicate' => "Duplicate",
            'copy' => "Copy",
            'copyStyle' => "Copy style",
            'copyConditions' => "Copy conditions",
            'cut' => "Cut",
            'paste' => "Paste",
            'saveReusable' => "Make re-usable",
            'saveBlock' => "Copy to block",
            'wrap' => "Wrap with div",
            'wrapLink' => "Wrap with link",
            'switchTextComponent' => "Switch text component",
            'showConditions' => "Set conditions",
            'rename' => "Rename",
            'changeId' => "Change ID",
            'delete' => "Delete"
        ];

        foreach ($menuItems as $index => $label) :
            $name = "settings[contextMenu][items][$index]";
            $value = $settings->contextMenu->items->$index;
        ?>
            <div class="field">
                <div class="field-label">
                    <label for="<?= $ui->nameToId($name) ?>"><?= $label ?></label>
                </div>
                <div class="field-control">
                    <?= $ui->checkbox($name, $value) ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</section>