<section class="section" id="section-structureenhancer" data-dependon="#settings_structureenhancer_enabled">
    <div class="section-header">
        <h3 class="section-title">Structure Panel</h3>
    </div>

    <div class="section-body">
        <?php
        $name = "settings[structureEnhancer][compact]";
        $value = $settings->structureEnhancer->compact;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Compact style</label>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[structureEnhancer][icons]";
        $value = $settings->structureEnhancer->icons;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Elements icons</label>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[structureEnhancer][openOnLoad]";
        $value = $settings->structureEnhancer->openOnLoad;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Open structure panel on load</label>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[structureEnhancer][expandAll]";
        $value = $settings->structureEnhancer->expandAll;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Expand all items on load</label>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[structureEnhancer][width]";
        $value = $settings->structureEnhancer->width;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Panel width</label>
            </div>
            <div class="field-control">
                <?= $ui->input($name, $value, "number", ["min" => 300, "placeholder" => "300"]) ?>
            </div>
        </div>
    </div>
</section>