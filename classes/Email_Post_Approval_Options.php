<?php
class Email_Post_Approval_Options {
    var $post_status_types;
    var $email_fields;

    public function __construct() {
        $this->post_status_types = [
            ['key' => 'publish', 'value' => 'Publish'],
            ['key' => 'pending', 'value' => 'Pending Review'],
            ['key' => 'draft', 'value' => 'Draft'],
            ['key' => 'future', 'value' => 'Future'],
            ['key' => 'private', 'value' => 'Private']
        ];
        $this->email_fields = [
            ['key' => 'title', 'value' => 'Post Title'],
            ['key' => 'post_author', 'value' => 'Post Author'],
            ['key' => 'post_date', 'value' => 'Publish Date'],
            ['key' => 'categories', 'value' => 'Categories'],
            ['key' => 'body', 'value' => 'Post Body'],
            ['key' => 'thumbnail', 'value' => 'Featured Image']
        ];

        add_action('admin_menu', [$this, 'add_options_page_to_menu']);
    }

    // Add options page to menu
    public function add_options_page_to_menu() {
        add_submenu_page('edit.php?post_type=' . get_imagepress_option('ip_slug'), __('Email Approval', 'imagepress'), __('Email Approval', 'imagepress'), 'manage_options', 'imagepress_email_post_approval', [$this, 'create_options_page']);
    }

    // Create options page
    public function create_options_page() {
        $option_values = [
            'send_to' => get_option('epa_send_to'),
            'post_statuses' => get_option('epa_post_statuses'),
            'email_fields' => get_option('epa_email_fields'),
            'default_author' => get_option('epa_default_author')
        ];

        if (isset($_POST['epa_form_submission'])) {
            $option_values = [
                'send_to' => $_POST['send_to'],
                'post_statuses' => $_POST['post_statuses'],
                'email_fields' => $_POST['email_fields'],
                'default_author' => $_POST['default_author']
            ];

            update_option('epa_send_to', $option_values['send_to']);
            update_option('epa_post_statuses', $option_values['post_statuses']);
            update_option('epa_email_fields', $option_values['email_fields']);
            update_option('epa_default_author', $option_values['default_author']);

            echo '<div id="message" class="updated fade"><p><strong>Settings	 saved.</strong></p></div>';
        }
        // Echo out HTML for form
        ?>
        <div class="wrap">
            <h2>ImagePress Email Post Approval Settings</h2>
            <form method="post" action="">
                <input type="hidden" name="epa_form_submission" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <label for="send_to">Send post approval email to:</label>
                        </th>
                        <td>
                            <input type="email" class="regular-text" name="send_to" id="send_to" value="<?php echo $option_values['send_to']; ?>">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="send_to">Default author for approved posts:</label>
                        </th>
                        <td>
                            <select name="default_author">
                                <?php
                                if (empty($option_values['default_author']) || $option_values['default_author'] === '0') {
                                    echo '<option value="0">None</option>';
                                } else {
                                    echo '<option value="0">None</option>';
                                }

                                foreach (get_users(['orderby' => 'display_name', 'fields' => ['ID', 'display_name']]) as $author) {
                                    $selected = ($option_values['default_author'] === $author->ID) ? 'selected' : '';
                                    echo '<option value="' . $author->ID . '" ' . $selected . '>' . $author->display_name . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label for="post_statuses">Send email when this post is saved as:</label>
                        </th>
                        <td>
                            <fieldset>
                                <?php
                                foreach ($this->post_status_types as $post_status) {
                                    if (is_array($option_values['post_statuses']) && array_search($post_status['key'], $option_values['post_statuses']) !== false) {
                                        $checked = 'checked';
                                    } else {
                                        $checked = '';
                                    }
                                    echo '<label><input name="post_statuses[]" type="checkbox" value="' . $post_status['key'] . '" ' . $checked . '> ' . $post_status['value'] . '</label><br>';
                                }
                                ?>
                            </fieldset>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">
                            <label>Include these fields in email:</label>
                        </th>
                        <td>
                            <fieldset>
                                <?php
                                foreach ($this->email_fields as $email_field) {
                                    if (is_array($option_values['email_fields']) && array_search($email_field['key'], $option_values['email_fields']) !== false) {
                                        $checked = 'checked';
                                    } else {
                                        $checked = '';
                                    }
                                    echo '<label><input name="email_fields[]" type="checkbox" value="' . $email_field['key'] . '" ' . $checked . '> ' . $email_field['value'] . '</label><br>';
                                }
                                ?>
                            </fieldset>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
                </p>
            </form>
        </div>
    <?php }
}

$email_post_approval_options = new Email_Post_Approval_Options;
