<?php
/**
 *
 * @link              https://www.gradiweb.com
 * @since             1.0.0
 * @package           OneSignalApi
 * @wordpress-plugin
 * Plugin Name:       OneSignalApi
 * Plugin URI:        https://www.gradiweb.com
 * Description:       Plugin para enviar notificaciones push usando onesignal ( condicionales para idiomas )
 * Version:           1.0.0
 * Author:            GradiWeb
 * Author URI:        https://www.gradiweb.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       OneSignalApi
 * Domain Path:       /
 */

if (!defined('WPINC')) {
    die;
}

require plugin_dir_path(__FILE__) . 'includes/OneSignalApi.php';

add_action("admin_menu", "CreateMenu");
add_action("admin_menu", "SaveSettings");
add_action("admin_menu", "SendMessageTest");

function CreateMenu()
{
    add_menu_page('Settings OneSignal Api', 'OneSignal Api', 'manage_options', 'one-signal-api', 'Settings');
}

function SaveSettings()
{
    if (isset($_POST['settings_one_signal'])) {
        OneSignalApi::UpdateOption('onesignal_api_key', $_POST['onesignal_api_key']);
        OneSignalApi::UpdateOption('onesignal_app_id', $_POST['onesignal_app_id']);
        wp_redirect('/wp-admin/admin.php?page=one-signal-api');
        exit;
    }
}

function SendMessageTest()
{
    if (isset($_POST['send_test_push'])) {
        OneSignalApi::SendMessageBySegment(['TEST SEGMENT'],OneSignalApi::GetOption('onesignal_app_id'),OneSignalApi::GetOption('onesignal_api_key'));
    }
}

function Settings()
{
    if (isset($_POST['submit_image_selector']) && isset($_POST['image_attachment_id'])) {
        update_option('media_selector_attachment_id', absint($_POST['image_attachment_id']));
    }
    if (isset($GLOBALS["polylang"])) {
        $arrayLanguages = $GLOBALS["polylang"]->model->get_languages_list();
    }
    wp_enqueue_media();
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <div class="card col-md-11">
        <div class="card-heading"><h2>Configuración del Plugin</h2></div>
        <div class="card-body">
            <form method="post">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="intro">APP ID</label>
                                <input type="text" class="form-control" id="intro"
                                       value="<?php echo OneSignalApi::GetOption('onesignal_app_id') ?>"
                                       name="onesignal_app_id" placeholder="ID de aplicación One Signal">
                            </div>
                            <div class="form-group">
                                <label for="thanks">API KEY</label>
                                <input type="text" class="form-control" id="thanks"
                                       value="<?php echo OneSignalApi::GetOption('onesignal_api_key') ?>"
                                       name="onesignal_api_key" placeholder="API KEY One Signal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="languages"></label>
                            <select name="languages" id="languages" class="form-control">
                                <?php
                                    foreach($arrayLanguages as $language){
                                        echo "<option value='".$language->locale."'>".$language->name."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <!--                        <div class="col-md-6">-->
                        <!--                            <label for="">Logo que se mostrará en el formulario</label>-->
                        <!--                            <div class='image-preview-wrapper'>-->
                        <!--                                <img id='image-preview'-->
                        <!--                                     src=''-->
                        <!--                                     style="width: 100px; align-content: center">-->
                        <!--                            </div>-->
                        <!--                            <input id="upload_image_button" type="button" class="button" value="Subir Imagen"/>-->
                        <!--                            <input type='hidden' name='image_attachment_id' id='image_attachment_id'-->
                        <!--                                   value=''>-->
                        <!--                        </div>-->
                        <div class="col-md-12">
                            <input type="submit" name="settings_one_signal" value="Guardar Cambios"
                                   style="float: right"
                                   class="button-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card col-md-11">
        <div class="card-heading">
            <h2>Mensaje de Pruebas</h2> <br>
            <span>Debes tener configurado un listado de dispositivos de prueba</span>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="intro">Titulo</label>
                                <input type="text" class="form-control" id="intro"
                                       value=""
                                       name="test_title" placeholder="Titulo de prueba">
                            </div>
                            <div class="form-group">
                                <label for="thanks">Mensaje</label>
                                <input type="text" class="form-control" id="thanks"
                                       value=""
                                       name="test_message" placeholder="Mensaje de pruebas">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" name="send_test_push" value="Enviar mensaje"
                                   style="float: right"
                                   class="button-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
    <?php
}

function ActivatePlugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/OneSignalApiActivator.php';
    OneSignalApiActivator::activate();
}

function DeactivatePlugin()
{
    require_once plugin_dir_path(__FILE__) . 'includes/OneSignalApiDeactivator.php';
    OneSignalApiDeactivator::deactivate();
}

register_activation_hook(__FILE__, 'ActivatePlugin');
register_deactivation_hook(__FILE__, 'DeactivatePlugin');

add_action('admin_footer', 'media_selector_print_scripts');

function media_selector_print_scripts()
{
    $my_saved_attachment_post_id = get_option('media_selector_attachment_id', 0);
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function ($) {
            // Uploading files
            var file_frame;
            var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
            var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
            jQuery('#upload_image_button').on('click', function (event) {
                event.preventDefault();
                // If the media frame already exists, reopen it.
                if (file_frame) {
                    // Set the post ID to what we want
                    file_frame.uploader.uploader.param('post_id', set_to_post_id);
                    // Open frame
                    file_frame.open();
                    return;
                } else {
                    // Set the wp.media post id so the uploader grabs the ID we want when initialised
                    wp.media.model.settings.post.id = set_to_post_id;
                }
                // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image to upload',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false	// Set to true to allow multiple files to be selected
                });
                // When an image is selected, run a callback.
                file_frame.on('select', function () {
                    // We set multiple to false so only get one image from the uploader
                    attachment = file_frame.state().get('selection').first().toJSON();
                    // Do something with attachment.id and/or attachment.url here
                    $('#image-preview').attr('src', attachment.url).css('width', 'auto');
                    $('#image_attachment_id').val(attachment.id);
                    // Restore the main post ID
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
                // Finally, open the modal
                file_frame.open();
            });
            // Restore the main ID when the add media button is pressed
            jQuery('a.add_media').on('click', function () {
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        });
    </script><?php
}
