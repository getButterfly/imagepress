<?php
if(!defined('ABSPATH')) exit;

class Cinnamon_Frontend_User_Manager {
	public function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'cinnamon_enqueue_scripts'));

		add_action('wp_ajax_cinnamon_ajax_login', array($this, 'cinnamon_ajax_login'));
		add_action('wp_ajax_nopriv_cinnamon_ajax_login', array($this, 'cinnamon_ajax_login'));
		add_action('wp_ajax_cinnamon_process_registration', array($this, 'cinnamon_process_registration'));
		add_action('wp_ajax_nopriv_cinnamon_process_registration', array($this, 'cinnamon_process_registration'));
		add_action('wp_ajax_cinnamon_process_psw_recovery', array($this, 'cinnamon_process_psw_recovery'));
		add_action('wp_ajax_nopriv_cinnamon_process_psw_recovery', array($this, 'cinnamon_process_psw_recovery'));

		add_shortcode('cinnamon-login', array($this,'cinnamon_user_frontend_shortcode'));
	}

	public function cinnamon_enqueue_scripts() {
		wp_enqueue_script('fum-script', IP_PLUGIN_URL . '/js/cinnamon-login.js', array('jquery'), false, true);
		wp_localize_script('fum-script', 'fum_script', array(
            'ajax'                          => admin_url('admin-ajax.php'),
            'redirecturl'                   => apply_filters('fum_redirect_to', $_SERVER['REQUEST_URI']),
            'loadingmessage'                => __('Checking credentials...', 'imagepress'),
            'registrationloadingmessage'    => __('Processing registration...', 'imagepress'),
		));
	}

	public function cinnamon_login_form() { ?>
        <div class="ip-tab">
            <ul class="ip-tabs active">
                <li class="current"><a href="#"><i class="fa fa-sign-in"></i> <?php _e('Log in', 'imagepress'); ?></a></li>
                <li class=""><a href="#"><i class="fa fa-user"></i> <?php _e('Sign up', 'imagepress'); ?></a></li>
                <li class=""><a href="#"><i class="fa fa-question-circle"></i> <?php _e('Lost password', 'imagepress'); ?></a></li>
            </ul>
            <div class="tab_content">
                <div class="ip-tabs-item" style="display: block;">
                    <?php if(!is_user_logged_in()) : ?>
                        <?php
                        $cinnamon_account_page = get_option('cinnamon_account_page');

                        $login_arguments = array(
                            'echo'           => true,
                            'remember'       => true,
                            'redirect'       => $cinnamon_account_page,
                            'form_id'        => 'form',
                            'id_username'    => 'user_login',
                            'id_password'    => 'user_pass',
                            'id_remember'    => 'rememberme',
                            'id_submit'      => 'wp-submit',
                            'label_username' => __('Username', 'imagepress'),
                            'label_password' => __('Password', 'imagepress'),
                            'label_remember' => __('Remember me', 'imagepress'),
                            'label_log_in'   => __('Log in', 'imagepress'),
                            'value_username' => '',
                            'value_remember' => true
                        );
                        wp_login_form($login_arguments);
                        ?>
                    <?php else : ?>
                        <p><?php echo __('You are already logged in.', 'imagepress'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="ip-tabs-item">
                    <?php if(!is_user_logged_in()) : ?>
                        <form action="register" method="post" id="regform" name="registrationform">
                            <h2><?php _e('Sign up', 'imagepress'); ?></h2>
                            <p><input type="text" name="user_login" id="reg_user" value="<?php if(isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="<?php _e('Username', 'imagepress'); ?>"></p>
                            <p><input type="email" name="user_email" id="reg_email" value="<?php if(isset($user_email)) echo esc_attr(stripslashes($user_email)); ?>" size="32" placeholder="<?php _e('Email address', 'imagepress'); ?>"></p>
                            <p><?php echo __('A password will be emailed to you.', 'imagepress'); ?></p>
                            <p><input type="submit" name="user-sumbit" id="user-submit" value="<?php esc_attr_e('Sign up', 'imagepress'); ?>"></p>
                            <input type="hidden" name="register" value="true">
                            <?php wp_nonce_field('ajax-form-nonce', 'security'); ?>
                        </form>
                    <?php else : ?>
                        <p><?php echo __('You are already logged in.', 'imagepress'); ?></p>
                    <?php endif; ?>
                </div>
                <div class="ip-tabs-item">
                    <form action="resetpsw" method="post" id="pswform" name="passwordform">
                        <h2><?php _e('Lost your password?', 'imagepress'); ?></h2>
                        <p><input type="text" name="forgot_login" id="forgot_login" class="input" value="<?php if(isset($user_login)) echo esc_attr(stripslashes($user_login)); ?>" size="32" placeholder="<?php _e('Username or email address', 'imagepress'); ?>"></p>
                        <p><input type="submit" name="fum-psw-sumbit" id="fum-psw-submit" value="<?php esc_attr_e('Reset password', 'imagepress'); ?>"></p>
                        <input type="hidden" name="forgotten" value="true">
                        <?php wp_nonce_field('ajax-form-nonce', 'security'); ?>
                    </form>
                </div>
            </div>
        </div>
    <?php
	}

	public function cinnamon_process_registration() {
		check_ajax_referer('ajax-form-nonce', 'security');

		$user_login = $_REQUEST['user_login'];
		$user_email = $_REQUEST['user_email'];
		
		$errors = register_new_user($user_login, $user_email);

        if(!is_wp_error($errors)) {
			echo json_encode(array(
				'registered' => true,
				'message' => __('Registration was successful!', 'imagepress'),
			));

			$user_id = $errors;
        } else {
			$registration_error_messages = $errors->errors;
			$display_errors = '<ul>';
                foreach($registration_error_messages as $error) {
                    $display_errors .= '<li>' . $error[0] . '</li>';
                }
			$display_errors .= '</ul>';

			echo json_encode(array(
				'registered' => false,
				'message' => sprintf(__('Something was wrong:</br> %s', 'imagepress'), $display_errors),
			));
        }

		die();
	}

	public function cinnamon_process_psw_recovery() {
		check_ajax_referer('ajax-form-nonce', 'security');

		if(is_email($_REQUEST['username']))
			$username = sanitize_email($_REQUEST['username']);
		else
			$username = sanitize_user($_REQUEST['username']);

		$user_forgotten = $this->cinnamon_retrieve_password($username);

		if(is_wp_error($user_forgotten)) {
			echo json_encode(array(
				'reset' => false,
				'message' => $user_forgotten->get_error_message(),
			));
		}
        else {
			echo json_encode(array(
				'reset' => true,
				'message' => __('Password reset. Please check your email.', 'imagepress'),
			));
		}

		die();
	}

	public function cinnamon_retrieve_password($user_data) {
		global $wpdb, $current_site;

		$errors = new WP_Error();
		if(empty($user_data)) {
			$errors->add('empty_username', __('Please enter a username or email address.', 'imagepress'));
		}
        else if(strpos($user_data, '@')) {
			$user_data = get_user_by('email', trim($user_data));
			if(empty($user_data))
				$errors->add('invalid_email', __('There is no user registered with that email address.', 'imagepress'));
		}
        else {
			$login = trim($user_data);
			$user_data = get_user_by('login', $login);
		}

        if($errors->get_error_code())
			return $errors;
		if(!$user_data) {
			$errors->add('invalidcombo', __('Invalid username or email address.', 'imagepress'));
			return $errors;
		}

		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;

		$allow = apply_filters('allow_password_reset', true, $user_data->ID);

		if (!$allow)
			return new WP_Error('no_password_reset', __('Password reset is not allowed for this user', 'imagepress'));
		else if(is_wp_error($allow))
			return $allow;


        $user_id = $user_data->ID;
        $password = wp_generate_password();
        wp_set_password($password, $user_id);

		$message = __('Someone requested that your password be reset for the following account: ', 'imagepress')  . $key . "\r\n\r\n";
		$message .= network_home_url('/') . "\r\n\r\n";
		$message .= sprintf( __('Username: %s'), $user_login ) . "\r\n\r\n";
		$message .= __('Your new password is ', 'imagepress') . $password . "\r\n\r\n";

		if(is_multisite())
			$blogname = $GLOBALS['current_site']->site_name;
		else
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

		$title   = sprintf(__('[%s] Password reset' ), $blogname);
		$title   = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);

		if($message && ! wp_mail($user_email, $title, $message)) {
			$errors->add('noemail', __('The e-mail could not be sent. Possible reason: your host may have disabled the mail() function.', 'imagepress'));

            return $errors;
            wp_die();
        }
        return true;
    }

	public function cinnamon_user_frontend_shortcode($atts, $content = null) {
        extract(shortcode_atts(array(
            'form' => '',
        ), $atts));
        ob_start();
        $this->cinnamon_login_form();
        return ob_get_clean();
    }
}

$cinnamon_frontend_user_manager = new Cinnamon_Frontend_User_Manager();
?>
