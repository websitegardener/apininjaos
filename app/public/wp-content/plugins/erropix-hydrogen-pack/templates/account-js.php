<script id="fs-account-tweaks">
    (function($) {
        $("#pframe").remove();
        $("#fs_account .postbox:not(:first-child)").remove();
        $("#fs_account .fs-header-actions a[href='#fs_billing']").remove();

        $fs_account_details = $("#fs_account_details");
        $fs_account_details.find(".fs-field-user_name form").remove();
        $fs_account_details.find(".fs-field-email form").remove();
        $fs_account_details.find(".fs-field-site_public_key").remove();
        $fs_account_details.find(".fs-field-site_secret_key").remove();
        $fs_account_details.find(".fs-field-plan .button-group").remove();
        $fs_account_details.find(".fs-field-license_key input").remove();
        $fs_account_details.find(".fs-field-license_key .fs-toggle-visibility").remove();
        $fs_account_details.find(".fs-field-license_key button").remove();
    }(jQuery));
</script>