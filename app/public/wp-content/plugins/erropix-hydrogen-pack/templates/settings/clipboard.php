<section class="section" id="section-clipboard" data-dependon="#settings_clipboard_enabled">
    <div class="section-header">
        <h3 class="section-title">Clipboard</h3>
    </div>

    <div class="section-body">
        <?php
        $name = "settings[clipboard][colorSet]";
        $value = $settings->clipboard->colorSet;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Color set</label>
                <?= $ui->popper("This name will be used to group the global colors pasted from external sites.") ?>
            </div>
            <div class="field-control">
                <?= $ui->input($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[clipboard][folder]";
        $value = $settings->clipboard->folder;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Class folder</label>
                <?= $ui->popper("This name will be used to group the classes pasted from external sites.") ?>
            </div>
            <div class="field-control">
                <?= $ui->input($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[clipboard][keepActiveComponent]";
        $value = $settings->clipboard->keepActiveComponent;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Keep element active</label>
                <?= $ui->popper("Keep the focus on the current element after you paste new elements.") ?>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[clipboard][flashCopiedComponent]";
        $value = $settings->clipboard->flashCopiedComponent;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Animate copied elements</label>
                <?= $ui->popper("Play a flashing animation on the current element as an indication for a successful copy to clipboard.") ?>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>

        <?php
        $name = "settings[clipboard][processMediaImages]";
        $value = $settings->clipboard->processMediaImages;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Process media images</label>
                <?= $ui->popper("When you paste media library images from external sites, they will be updated to display images from the original site.") ?>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>
    </div>
</section>