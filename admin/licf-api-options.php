<?php
if( isset($_POST['api-submit']) || isset( $_POST['licf_api_settings_nonce'] ) 
	|| wp_verify_nonce( isset($_POST['licf_api_settings_nonce']), 'licf_api_settings' ) )
{
	$UserCode = (isset($_POST['user-code'])) ? sanitize_text_field($_POST['user-code']) : '';
	$APIToken = (isset($_POST['api-token'])) ? sanitize_text_field($_POST['api-token']) : '';
	update_option('user-code', $UserCode);
	update_option('api-token', $APIToken);
	?>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e( 'Settings Saved.', 'licf-lacrm-integration-contactform' ); ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'licf-lacrm-integration-contactform' ); ?></span>
		</button>
	</div>
	<?php
}
$usercode = get_option('user-code');
$apitoken = get_option('api-token');
?>
<h1 class="mailbox-title"><?php _e( 'LA CRM API Settings', 'licf-lacrm-integration-contactform' ); ?></h1>
<div class="wrap">
	<div class="mail-settings-content">
		<form method="post">
			<?php wp_nonce_field( 'licf_api_settings', 'licf_api_settings_nonce' ); ?>
			<table class="table table-responsive">
				<tr class="from-row">
					<td class="mail-form-label mail-form-title">
						<label for="mail-from-label"><?php _e( 'User Code:', 'licf-lacrm-integration-contactform' ); ?></label>
					</td>
					<td>
						<input type="text" name="user-code" class="mail-from txtbox mail-form-input regular-text" value="<?php echo $usercode; ?>">
					</td>
				</tr>
				<tr class="from-row">
					<td class="mail-form-label mail-form-title">
						<label for="mail-from-label"><?php _e( 'API Token:', 'licf-lacrm-integration-contactform' ); ?></label>
					</td>
					<td>
						<input type="text" name="api-token" class="mail-from txtbox mail-form-input regular-text" value="<?php echo $apitoken; ?>">
					</td>
				</tr>
				<tr class="from-row">
					<td class="mail-form-label">
						<input type="submit" name="api-submit" class="api-submit submit-btn button button-primary" value="<?php _e( 'Save Changes', 'licf-lacrm-integration-contactform' ); ?>">
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>