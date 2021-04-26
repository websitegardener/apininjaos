<div class="wrap hydrogen-ui">
    <h1><?= get_admin_page_title() ?></h1>

    <div class="hydrogen-settings-container">
        <form action="<?= admin_url('admin-post.php') ?>" method="POST" data-dependon-context>
            <?php wp_nonce_field($this->nonce_name) ?>
            <input type="hidden" name="action" value="<?= $this->action_settings_save ?>">

            <?php
            include $this->get_template('settings/features');
            include $this->get_template('settings/contextmenu');
            include $this->get_template('settings/clipboard');
            include $this->get_template('settings/shortcuts');
            include $this->get_template('settings/structure');
            do_action("hydrogen_settings_boxes");
            include $this->get_template('settings/manage');
            ?>

            <aside class="main-actions">
                <div class="buttons">
                    <button class="button button-primary">Save Changes</button>
                    <a class="button" href="https://www.erropix.com/support/" target="_blank">Need Help?</a>
                </div>
            </aside>
        </form>
    </div>
</div>