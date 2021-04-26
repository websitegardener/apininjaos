<section class="section">
    <div class="section-header">
        <h3 class="section-title">Enable Features</h3>
    </div>

    <div class="section-body">
        <?php
        $features = [
            "contextMenu" => [
                "label" => "Right-click menu",
                "haveOptions" => true,
            ],
            "clipboard" => [
                "label" => "Clipboard",
                "haveOptions" => true,
            ],
            "shortcuts" => [
                "label" => "Keyboard shortcuts",
                "haveOptions" => true,
            ],
            "conditionsEnhancer" => [
                "label" => "Enhanced conditions dialog",
            ],
            "structureEnhancer" => [
                "label" => "Enhanced structure panel",
                "haveOptions" => true,
            ],
            "contentEditorEnhancer" => [
                "label" => "Enhanced content editor",
            ],
            "advancedStylesReset" => [
                "label" => "Advanced styles reset",
            ],
            "disableEditLocking" => [
                "label" => "Disable edit locking",
            ],
            "disableCompositeElements" => [
                "label" => "Disable composite elements",
            ],
            "cssCacheRegeneration" => [
                "label" => "Auto regenerate CSS cache on save",
            ],
            "dynamicClasses" => [
                "label" => "Dynamic Classes",
            ],
            "classLock" => [
                "label" => "Class lock",
            ],
            "preserveAdvancedTabs" => [
                "label" => "Preserve advanced tabs state",
            ],
        ];

        $features = apply_filters("hydrogen_settings_features", $features);

        foreach ($features as $key => $feature) :
            $name = "settings[$key][enabled]";
            $value = $settings->$key->enabled;
            $id = $ui->nameToId($name);
            $description = $feature["description"] ?? "";
            $haveOptions = $feature["haveOptions"] ?? false;
            $isBeta = $feature["beta"] ?? false;
        ?>
            <div class="field">
                <div class="field-label">
                    <label for="<?= $id ?>">
                        <?= $feature["label"] ?>
                        <?php if ($isBeta) : ?>
                            <span class="badge-beta-option">Beta</span>
                        <?php endif ?>
                        <?php if ($description) echo $ui->popper($description); ?>
                    </label>
                </div>
                <div class="field-control">
                    <?= $ui->checkbox($name, $value) ?>

                    <?php if ($haveOptions) : ?>
                        <a href="#section-<?= strtolower($key) ?>" class="scroll-to-section">
                            <span class="dashicons dashicons-admin-generic" data-dependon="#<?= $id ?>"></span>
                        </a>
                    <?php endif ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</section>