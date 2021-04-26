<section class="section" id="section-shortcuts" data-dependon="#settings_shortcuts_enabled">
    <div class="section-header">
        <h3 class="section-title">Keyboard Shortcuts</h3>
    </div>

    <div class="section-body">
        <div class="note">
            <span class="dashicons dashicons-flag"></span> <strong>Notes</strong> <br>
            <ol>
                <li>To set a new shortcut, just click on the activation key field, and press the desired keys.</li>
                <li><b>Ctrl</b> stand for both <b>Control</b> on Windows keyboards, and <b>⌘ Command</b> on Mac keyboards.</li>
                <li><b>Ctrl</b>, <b>Alt</b>, and <b>Shift</b> keys are optional and not required for the shortcuts to work.</li>
            </ol>
        </div>

        <table class="fields-table">
            <thead>
                <tr class="table-header">
                    <th></th>
                    <th class="ckb">Ctrl</th>
                    <th class="ckb">Alt</th>
                    <th class="ckb">Shift</th>
                    <th class="key">Activation Key</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $shortcuts = $settings->shortcuts->hotkeys;
                foreach ($shortcuts as $id => $shortcut) : ?>
                    <?php
                    $basename = "settings[shortcuts][hotkeys][$id]";
                    $dependon = $shortcut->dependon;
                    ?>
                    <tr <?= $dependon ? "data-dependon=\"$dependon\"" : "" ?> class="hotkey-row">
                        <td><?= $shortcut->label ?></td>
                        <td><?= $ui->checkbox("{$basename}[ctrl]", $shortcut->ctrl, "", "", true) ?></td>
                        <td><?= $ui->checkbox("{$basename}[alt]", $shortcut->alt, "", "", true) ?></td>
                        <td><?= $ui->checkbox("{$basename}[shift]", $shortcut->shift, "", "", true) ?></td>
                        <td>
                            <div class="pos-rel">
                                <input type="text" name="<?= $basename ?>[key]" class="hotkey-input" value="<?= $shortcut->key ?>" readonly>
                                <span class="dashicons dashicons-no-alt hotkey-delete"></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</section>