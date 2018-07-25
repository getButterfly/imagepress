<?php
if (!defined('ABSPATH')) {
	exit;
}

class Cinnamon_Frontend_User_Manager {
	public function __construct() {
		add_action('wp_ajax_cinnamon_ajax_login', array($this, 'cinnamon_ajax_login'));
		add_action('wp_ajax_nopriv_cinnamon_ajax_login', array($this, 'cinnamon_ajax_login'));
		add_action('wp_ajax_cinnamon_process_registration', array($this, 'cinnamon_process_registration'));
		add_action('wp_ajax_nopriv_cinnamon_process_registration', array($this, 'cinnamon_process_registration'));
		add_action('wp_ajax_cinnamon_process_psw_recovery', array($this, 'cinnamon_process_psw_recovery'));
		add_action('wp_ajax_nopriv_cinnamon_process_psw_recovery', array($this, 'cinnamon_process_psw_recovery'));

		add_shortcode('cinnamon-login', array($this,'cinnamon_user_frontend_shortcode'));
	}

	public function cinnamon_login_form() { ?>
        <div class="ip-tab">
            <ul class="ip-tabs active">
                <li class="current"><a href="#"><?php echo is_user_logged_in() ? __('My account', 'imagepress') : __('Log in', 'imagepress'); ?></a></li>
                <?php if (get_option('users_can_register') && !is_user_logged_in()) { ?>
                    <li class=""><a href="#"><?php esc_html_e('Sign up', 'imagepress'); ?></a></li>
                <?php } ?>
                <?php if (!is_user_logged_in()) { ?>
                    <li class=""><a href="#"><?php esc_html_e('Lost password', 'imagepress'); ?></a></li>
                <?php } ?>
            </ul>
            <div class="tab_content">
                <div class="ip-tabs-item" style="display: block;">
                    <?php
                    if (!is_user_logged_in()) {
                        $accountPageUri = get_option('cinnamon_account_page');

                        $login_arguments = array(
                            'echo' => true,
                            'remember' => true,
                            'redirect' => $accountPageUri,
                            'form_id' => 'form',
                            'id_username' => 'user_login',
                            'id_password' => 'user_pass',
                            'id_remember' => 'rememberme',
                            'id_submit' => 'wp-submit',
                            'label_username' => __('Username', 'imagepress'),
                            'label_password' => __('Password', 'imagepress'),
                            'label_remember' => __('Remember me', 'imagepress'),
                            'label_log_in' => __('Log in', 'imagepress'),
                            'value_username' => '',
                            'value_remember' => true
                        );
                        wp_login_form($login_arguments);
                    } else { ?>
                        <p>
                            <a href="<?php echo getImagePressProfileUri(get_current_user_id(), false); ?>"><?php echo __('View profile', 'imagepress'); ?></a> |
                            <a href="<?php echo get_permalink(get_imagepress_option('cinnamon_edit_page')); ?>"><?php echo __('Edit profile', 'noir-ui'); ?></a>
                        </p>
                        <p><a href="<?php echo wp_logout_url(home_url()); ?>"><?php echo __('Log out', 'imagepress'); ?></a></p>
                    <?php } ?>
                </div>

                <?php if (get_option('users_can_register') && !is_user_logged_in()) { ?>
                    <div class="ip-tabs-item">
                        <form action="register" method="post" id="regform" name="registrationform">
                            <h2><?php esc_html_e('Sign up', 'imagepress'); ?></h2>
                            <p><input type="text" name="user_login" id="reg_user" value="<?php if (isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="<?php esc_html_e('Username', 'imagepress'); ?>"></p>
                            <p><input type="email" name="user_email" id="reg_email" value="<?php if (isset($user_email)) echo esc_attr(stripslashes($user_email)); ?>" size="32" placeholder="<?php esc_html_e('Email address', 'imagepress'); ?>"></p>
                            <p><?php esc_html_e('A password will be emailed to you.', 'imagepress'); ?></p>
                            <p><input type="submit" name="user-sumbit" id="user-submit" value="<?php esc_html_e('Sign up', 'imagepress'); ?>"></p>
                            <input type="hidden" name="register" value="true">
                            <?php wp_nonce_field('ajax-form-nonce', 'security'); ?>
                        </form>
                    </div>
                <?php } ?>

                <?php if (!is_user_logged_in()) { ?>
                    <div class="ip-tabs-item">
                        <form action="resetpsw" method="post" id="pswform" name="passwordform">
                            <h2><?php esc_html_e('Lost your password?', 'imagepress'); ?></h2>
                            <p><input type="text" name="forgot_login" id="forgot_login" class="input" value="<?php if (isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="<?php esc_html_e('Username or email address', 'imagepress'); ?>"></p>
                            <p><input type="submit" name="fum-psw-sumbit" id="fum-psw-submit" value="<?php esc_html_e('Reset password', 'imagepress'); ?>"></p>
                            <input type="hidden" name="forgotten" value="true">
                            <?php wp_nonce_field('ajax-form-nonce', 'security'); ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php }

	public function cinnamon_process_registration() {
        check_ajax_referer('ajax-form-nonce', 'security');

        $user_login = filter_input(INPUT_POST, 'user_login');
        $user_email = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);

        $errors = register_new_user($user_login, $user_email);

        $registered = true;
        $message = __('Registration was successful!', 'imagepress');

        if (is_wp_error($errors)) {
            $registrationErrors = $errors->errors;
            $display_errors = '<ul>';
                foreach ($registrationErrors as $error) {
                    $display_errors .= '<li>' . $error[0] . '</li>';
                }
            $display_errors .= '</ul>';

            $registered = false;
            $message = sprintf(__('Something was wrong:</br> %s', 'imagepress'), $display_errors);
        }

        echo json_encode(array(
            'registered' => $registered,
            'message' => $message,
        ));

        wp_die();
    }

	public function cinnamon_process_psw_recovery() {
		check_ajax_referer('ajax-form-nonce', 'security');

        $username = filter_input(INPUT_POST, 'username');

		if (is_email($username)) {
			$username = sanitize_email($username);
        }

		$user_forgotten = $this->cinnamon_retrieve_password($username);

        $reset = true;
        $message = __('Password reset. Please check your email.', 'imagepress');

        if (is_wp_error($user_forgotten)) {
            $reset = false;
            $message = $user_forgotten->get_error_message();
		}

        echo json_encode(array(
            'reset' => $reset,
            'message' => $message,
        ));

		wp_die();
	}

	public function cinnamon_retrieve_password($user_data) {
		$errors = new WP_Error();

		if (empty($user_data)) {
			$errors->add('empty_username', __('Please enter a username or email address.', 'imagepress'));
		} else if (strpos($user_data, '@')) {
			$user_data = get_user_by('email', trim($user_data));
			if (empty($user_data)) {
				$errors->add('invalid_email', __('There is no user registered with that email address.', 'imagepress'));
			}
		} else {
			$login = trim($user_data);
			$user_data = get_user_by('login', $login);
		}

		if ($errors->get_error_code()) {
			return $errors;
		}

		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		$allow = apply_filters('allow_password_reset', true, $user_data->ID);

		if (!$allow) {
			return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', 'imagepress'));
		} else if (is_wp_error($allow)) {
			return $allow;
		}

		$user_id = $user_data->ID;
		$password = wp_generate_password();
		wp_set_password($password, $user_id);

		$message = __('Someone requested that your password be reset for the following account: ', 'imagepress')  . $key . "\r\n\r\n";
		$message .= network_home_url('/') . "\r\n\r\n";
		$message .= sprintf(__('Username: %s', 'imagepress'), $user_login) . "\r\n\r\n";
		$message .= __('Your new password is ', 'imagepress') . $password . "\r\n\r\n";

		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$title = sprintf(__('[%s] Password reset', 'imagepress'), $blogname);
		$title = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);

	    $headers[] = "MIME-Version: 1.0\r\n";
    	$headers[] = "Content-Type: text/html; charset=\"" . get_option('blog_charset') . "\"\r\n";

		if ($message && !wp_mail($user_email, $title, $message, $headers)) {
			$errors->add('noemail', __('The email could not be sent.', 'imagepress'));

			return $errors;
		}

		return true;
	}

	public function cinnamon_user_frontend_shortcode($atts) {
        extract(shortcode_atts(array(
            'form' => '',
        ), $atts));
        ob_start();
        $this->cinnamon_login_form();
        return ob_get_clean();
    }
}

$cinnamon_frontend_user_manager = new Cinnamon_Frontend_User_Manager();
