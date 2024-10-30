<?php
//Admin enqueue scripts
function grchEnqueueScript($hook)
{
wp_register_style('admin-css', LICF_URL.'css/licf-admin-style.css');
wp_enqueue_style('admin-css');
}
add_action('admin_enqueue_scripts', 'grchEnqueueScript', 10, 1);
//
//Add custom admin options page
if(!function_exists('licf_api_options_page')){
    function licf_api_options_page()
    {
        add_menu_page(
            __('CF7 LA CRM API Settings'),
            'CF7 LA CRM API Settings',
            'manage_options',
            'licf_api_options',
            'licf_api_options',
            '',
            26
        );
    }
}
add_action( 'admin_menu', 'licf_api_options_page' );

//Include admin options page
if(!function_exists('licf_api_options')){
    function licf_api_options(){
        require_once(LICF_DIR.'admin/licf-api-options.php');
    }
}

//Add new tab to contact form
if(!function_exists('licf_cf7_add_tab')){
    function licf_cf7_add_tab( $panels ) {
        $panels["custom-redirect-settings"] = array("title"=>"LA CRM Integration","callback"=>"licf_set_apis");
        return $panels;
    }
}
add_filter( 'wpcf7_editor_panels', 'licf_cf7_add_tab' ); 

//Add callback for contact form custom tab
if(!function_exists('licf_set_apis')){
    function licf_set_apis(){
        $id = ( isset( $_GET['post'] ) ) ? $_GET['post'] : '' ;
        $ContactForm = WPCF7_ContactForm::get_instance( $id );
        $form_fields = $ContactForm->scan_form_tags();
        $UserCode = get_option('user-code');
        $APIToken = get_option('api-token');
        $EndpointURL = "https://api.lessannoyingcrm.com";
        $allow_crm = get_post_meta($ContactForm->id(), 'licf_allow_crm_contact_form', true);
        $module = get_post_meta($ContactForm->id(), 'licf_what_to_create_module_contact_form', true);
        $licf_crm_name = get_post_meta($ContactForm->id(), 'licf_crm_name', true);
        $licf_crm_email = get_post_meta($ContactForm->id(), 'licf_crm_email', true);
        $licf_crm_phone = get_post_meta($ContactForm->id(), 'licf_crm_phone', true);
        $licf_crm_company_name = get_post_meta($ContactForm->id(), 'licf_crm_company_name', true);
        $licf_crm_job_title = get_post_meta($ContactForm->id(), 'licf_crm_job_title', true);
        $licf_crm_contact_custom_fields = get_post_meta($ContactForm->id(), 'licf_crm_contact_custom_fields', true);
        $licf_crm_company_custom_fields = get_post_meta($ContactForm->id(), 'licf_crm_company_custom_fields', true);
        ?>
        <div class="wrap">
            <h1 class="mailbox-title"><?php _e( 'LA CRM Settings', 'licf-lacrm-integration-contactform' ); ?></h1>
            <div class="licf-contact-fields">
                <h3><?php _e( 'Contact Form Fields Tags', 'licf-lacrm-integration-contactform' ); ?></h3>
                <?php
                foreach ($form_fields as $key => $value) {
                    if($value->name != ''){
                        ?>
                        <span><?php echo '['.$value->name.']'; ?></span>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="mail-settings-content licf-settings">
                <form method="post">
                    <?php wp_nonce_field( 'field_settings', 'field_settings_nonce' ); ?>
                    <table class="display form-details">
                        <tbody class="form-details">
                            <tr>
                                <th><label><?php _e( 'Allow CRM:', 'licf-lacrm-integration-contactform' ); ?></label></th>
                                <td><input type="checkbox" name="allow-crm" value="yes" <?php if($allow_crm == 'yes'){ echo 'checked'; } ?>></td>
                            </tr>
                            <tr>
                                <th><label for="mail-from-label"><?php _e( 'Which module to use:', 'licf-lacrm-integration-contactform' ); ?></label></th>
                                <td>
                                    <select name="what-to-create">
                                        <option value=""><?php _e( 'Select Module', 'licf-lacrm-integration-contactform' ); ?></option>
                                        <option value="CreateContact" <?php if($module == 'CreateContact'){ echo 'selected'; } ?>><?php _e( 'Create Contact', 'licf-lacrm-integration-contactform' ); ?></option>
                                        <option value="new-company" <?php if($module == 'new-company'){ echo 'selected'; } ?>><?php _e( 'New Company', 'licf-lacrm-integration-contactform' ); ?></option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h3><?php _e( 'Select Fields From LA CRM:', 'licf-lacrm-integration-contactform' ); ?></h3>
                    <table class="display form-details">
                        <thead>
                            <tr>
                                <th><?php _e( 'LA CRM Fields', 'licf-lacrm-integration-contactform' ); ?></th>
                                <th><?php _e( 'Contact Form 7 Fields', 'licf-lacrm-integration-contactform' ); ?></th>
                            </tr>
                        </thead>
                        <tbody class="form-details">
                            <tr>
                                <th><label><?php _e( 'Name', 'licf-lacrm-integration-contactform' ); ?></label></th>
                                <td><input type="text" name="name" value="<?php echo $licf_crm_name; ?>"></td>
                            </tr>
                            <tr>
                                <th><label><?php _e( 'Email', 'licf-lacrm-integration-contactform' ); ?></label></th>
                                <td><input type="text" name="email" value="<?php echo $licf_crm_email; ?>"></td>
                            </tr>
                            <tr>
                                <th><label><?php _e( 'Phone', 'licf-lacrm-integration-contactform' ); ?></label></th>
                                <td><input type="text" name="phone" value="<?php echo $licf_crm_phone; ?>"></td>
                            </tr>
                            <tr>
                                <th><label><?php _e( 'Company Name', 'licf-lacrm-integration-contactform' ); ?></label></th>
                                <td><input type="text" name="company_name" value="<?php echo $licf_crm_company_name; ?>"></td>
                            </tr>
                            <tr>
                                <th><label><?php _e( 'Job Title', 'licf-lacrm-integration-contactform' ); ?></label></th>
                                <td><input type="text" name="job_title" value="<?php echo $licf_crm_job_title; ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    if($UserCode != '' && $APIToken != ''){
                        $PostData = array(
                            'UserCode' => $UserCode,
                            'APIToken' => $APIToken,
                            'Function' => 'GetCustomFields',
                        );
                        $Options = array(
                            'http' =>
                                array(
                                    'method'  => 'POST', //We are using the POST HTTP method.
                                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                                    'content' => http_build_query($PostData) // URL-encoded query string.
                                )
                        );
                        $StreamContext  = stream_context_create($Options);
                        $APIResult = file_get_contents("$EndpointURL?UserCode=$UserCode", false, $StreamContext);
                        $APIResult = json_decode($APIResult, true);
                        if(!empty($APIResult['Contact'])){
                            ?>
                            <h3><?php _e( 'LA CRM Custom Fields:', 'licf-lacrm-integration-contactform' ); ?></h3>
                            <table class="display form-details">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'Contact Custom Fields', 'licf-lacrm-integration-contactform' ); ?></th>
                                        <th><?php _e( 'Contact Forms Fields Tag', 'licf-lacrm-integration-contactform' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="form-details">
                                    <?php
                                    foreach ($APIResult['Contact'] as $key => $val) {
                                        ?>
                                        <tr>
                                            <th><label><?php echo $val['Name']; ?></label></th>
                                            <td><input type="text" name="contact_custom_fields[<?php echo $val['Name']; ?>]" value="<?php if($licf_crm_contact_custom_fields != ''){ echo trim($licf_crm_contact_custom_fields[$val['Name']],"[]"); } ?>"></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                        if(!empty($APIResult['Company'])){
                            ?>
                            <table class="display form-details">
                                <thead>
                                    <tr>
                                        <th><?php _e( 'Company Custom Fields', 'licf-lacrm-integration-contactform' ); ?></th>
                                        <th><?php _e( 'Contact Forms Fields Tag', 'licf-lacrm-integration-contactform' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="form-details">
                                    <?php
                                    foreach ($APIResult['Company'] as $key => $val) {
                                        ?>
                                        <tr>
                                            <th><label><?php echo $val['Name']; ?></label></th>
                                            <td><input type="text" name="company_custom_fields[<?php echo $val['Name']; ?>]" value="<?php if($licf_crm_company_custom_fields != ''){  echo trim($licf_crm_company_custom_fields[$val['Name']], "[]"); } ?>"></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        }
                    }
                    ?>
                </form>
            </div>
        </div>               
        <?php
    }
}

if(!function_exists('licf_sanitize_arr_text_field')){
    function licf_sanitize_arr_text_field( $array ) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = licf_sanitize_arr_text_field( $value );
            } else {
                $value = sanitize_text_field( $value );
            }
        }
        return $array;
    }
}

//Save contact form data using hook
if(!function_exists('licf_save_contact_form')){
    function licf_save_contact_form( $contact_form, $args, $context ) {
        $contact_form_id = $contact_form->id; 
        $allow_crm = (isset($_POST['allow-crm'])) ? sanitize_text_field($_POST['allow-crm']) : '';
        update_post_meta( $contact_form_id, 'licf_allow_crm_contact_form', $allow_crm );
        $what_to_create = (isset($_POST['what-to-create'])) ? sanitize_text_field($_POST['what-to-create']) : '';
        update_post_meta( $contact_form_id, 'licf_what_to_create_module_contact_form', $what_to_create );
        $name = (isset($_POST['name'])) ? sanitize_text_field($_POST['name']) : '';
        update_post_meta( $contact_form_id, 'licf_crm_name', trim($name,"[]") );
        $email = (isset($_POST['email'])) ? sanitize_text_field($_POST['email']) : '';
        update_post_meta( $contact_form_id, 'licf_crm_email', trim($email,'[]') );
        $phone = (isset($_POST['phone'])) ? sanitize_text_field($_POST['phone']) : '';
        update_post_meta( $contact_form_id, 'licf_crm_phone', trim($phone,'[]') );
        $company_name = (isset($_POST['company_name'])) ? sanitize_text_field($_POST['company_name']) : '';
        update_post_meta( $contact_form_id, 'licf_crm_company_name', trim($company_name,'[]') );
        $job_title = (isset($_POST['job_title'])) ? sanitize_text_field($_POST['job_title']) : '';
        update_post_meta( $contact_form_id, 'licf_crm_job_title', trim($job_title,'[]') );
        $custom_fields = (isset($_POST['contact_custom_fields'])) ? licf_sanitize_arr_text_field($_POST['contact_custom_fields']) : '';
        update_post_meta( $contact_form_id, 'licf_crm_contact_custom_fields', $custom_fields );
        $company_custom_fields = (isset($_POST['company_custom_fields'])) ? licf_sanitize_arr_text_field($_POST['company_custom_fields']) : '';
        update_post_meta( $contact_form_id, 'licf_crm_company_custom_fields', $company_custom_fields );
    }
}
add_action( 'wpcf7_save_contact_form', 'licf_save_contact_form', 10, 3 ); 

//Send contact form data to CRM
if(!function_exists('licf_sent_formdata')){
    function licf_sent_formdata( $form ){
        $form_id = $form->id();
        $UserCode = get_option('user-code');
        $APIToken = get_option('api-token');
        $EndpointURL = "https://api.lessannoyingcrm.com";
        $allow_crm = get_post_meta($form_id, 'licf_allow_crm_contact_form', true);
        $Function = get_post_meta($form_id, 'licf_what_to_create_module_contact_form', true);
        $licf_crm_name = get_post_meta($form_id, 'licf_crm_name', true);
        $licf_crm_email = get_post_meta($form_id, 'licf_crm_email', true);
        $licf_crm_phone = get_post_meta($form_id, 'licf_crm_phone', true);
        $licf_crm_company_name = get_post_meta($form_id, 'licf_crm_company_name', true);
        $licf_crm_job_title = get_post_meta($form_id, 'licf_crm_job_title', true);
        $licf_crm_contact_custom_fields = get_post_meta($form_id, 'licf_crm_contact_custom_fields', true);
        $licf_crm_company_custom_fields = get_post_meta($form_id, 'licf_crm_company_custom_fields', true);
        $submission = WPCF7_Submission::get_instance();
        if (!$submission){
            return;
        }
        $posted_data = $submission->get_posted_data();
        $FullName = $posted_data[$licf_crm_name];
        $Email = array(
            0 => array(
                "Text" => $posted_data[$licf_crm_email],
                "Type"=>"Work"
            )
        );
        $Phone = array(
            0=>array(
                "Text"=> $posted_data[$licf_crm_phone],
                "Type"=>"Work"
            )
        );
        $JobTitle = $posted_data[$licf_crm_job_title];
        
        if($allow_crm == 'yes'){
            if($Function == 'CreateContact'){
                $CustomFields = array();
                foreach( $licf_crm_contact_custom_fields as $key => $val ){
                    $CustomFields[$key] = $posted_data[trim($val, "[]")];
                }
                $Parameters = array(
                    "FullName"=>$FullName,
                    "Email"=>$Email,
                    "Phone" =>$Phone,
                    "Title"=>$JobTitle,
                    "CustomFields"=>$CustomFields
                );
                licf_callback_api($EndpointURL, $UserCode, $APIToken, $Function, $Parameters);
            }elseif($Function == 'new-company'){
                $Function = "CreateContact";
                $CustomFields = array();
                foreach( $licf_crm_company_custom_fields as $key => $val ){
                    $CustomFields[$key] = $posted_data[trim($val, "[]")];
                }
                $Parameters = array(
                    "CompanyName"=>$FullName,
                    "Email"=>$Email,
                    "CustomFields"=>$CustomFields
                );
                licf_callback_api($EndpointURL, $UserCode, $APIToken, $Function, $Parameters);
            }
        }
    }
}
add_action( 'wpcf7_mail_sent', 'licf_sent_formdata', 10, 1 );

//Callback API for saving data in crm
if(!function_exists('licf_callback_api')){
    function licf_callback_api($EndpointURL, $UserCode, $APIToken, $Function, $Parameters){
        $PostData = array(
        'UserCode' => $UserCode,
        'APIToken' => $APIToken,
        'Function' => $Function,
        'Parameters' => json_encode($Parameters),
        );
        $Options = array(
            'http' =>
                array(
                    'method'  => 'POST', //We are using the POST HTTP method.
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => http_build_query($PostData) // URL-encoded query string.
                )
        );
        $StreamContext  = stream_context_create($Options);
        $APIResult = file_get_contents("$EndpointURL?UserCode=$UserCode", false, $StreamContext);
        $APIResult = json_decode($APIResult, true);
        return $APIResult;
    }
}