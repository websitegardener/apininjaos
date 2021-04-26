<section class="section" id="section-sandbox" data-dependon="#settings_sandbox_enabled">
    <div class="section-header">
        <h3 class="section-title">Sandbox Mode</h3>
    </div>

    <div class="section-body">
        <?php if ($this->have_changes()) : ?>
            <div class="field field-vertical">
                <div class="field-label">
                    <label for="hydrogen-sandbox-link">Sandbox link</label>
                </div>
                <div class="field-control field-copy">
                    <input type="text" id="hydrogen-sandbox-link" value="<?= $this->secret_link ?>" readonly>
                    <div class="overlay">Link copied to your clipboard!</div>
                    <span class="copy-icon"></span>
                </div>
            </div>

            <div class="field field-vertical">
                <div class="field-label">
                    <label>Publish sandbox changes</label>
                </div>
                <div class="field-control">
                    <div class="slide-submit" style="--action-icon:'\f115'">
                        <a class="button slide-source">
                            <span class="dashicons"></span>
                        </a>
                        Slide to publish
                        <button class="button slide-target" type="button" name="action" value="<?= $this->action_sandbox_publish ?>" disabled>
                            <span class="dashicons"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="field field-vertical">
                <div class="field-label">
                    <label>Delete sandbox data</label>
                </div>

                <div class="field-control">
                    <div class="slide-submit" style="--action-icon:'\f182'">
                        <a class="button slide-source">
                            <span class="dashicons"></span>
                        </a>
                        Slide to delete
                        <button class="button slide-target" type="button" name="action" value="<?= $this->action_sandbox_delete ?>" disabled>
                            <span class="dashicons"></span>
                        </button>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="note">
                <span class="dashicons dashicons-info"></span>
                The sandbox is empty! You didn't make any changes yet.
            </div>
        <?php endif ?>
    </div>
</section>