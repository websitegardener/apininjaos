<section class="section">
    <div class="section-header">
        <h3 class="section-title">Manage Settings</h3>
    </div>

    <div class="section-body">
        <?php if ($user_settings) : ?>
            <div class="field field-vertical">
                <div class="field-label">
                    <label for="hydrogen-exported-settings">Export settings</label>
                </div>
                <div class="field-control field-copy">
                    <input type="text" id="hydrogen-exported-settings" value="<?= $this->encode($user_settings) ?>" readonly>
                    <div class="overlay">Settings copied to your clipboard!</div>
                    <span class="copy-icon"></span>
                </div>
            </div>
        <?php endif ?>

        <div class="field field-vertical">
            <div class="field-label">
                <label for="hydrogen-inport-settings">Import settings</label>
            </div>
            <div class="field-control">
                <input type="text" name="import" id="hydrogen-inport-settings">
            </div>
        </div>

        <?php if ($user_settings) : ?>
            <div class="field field-vertical">
                <div class="field-label">
                    <label>Restore default settings</label>
                </div>

                <div class="field-control">
                    <div class="slide-submit" style="--action-icon:'\f171'">
                        <a class="button slide-source">
                            <span class="dashicons"></span>
                        </a>
                        Slide to reset
                        <button class="button slide-target" type="button" name="action" value="<?= $this->action_settings_reset ?>" disabled>
                            <span class="dashicons dashicons-update"></span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</section>