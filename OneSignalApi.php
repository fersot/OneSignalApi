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
require_once plugin_dir_path(__FILE__) . 'includes/OneSignalApiDeactivator.php';
require_once plugin_dir_path(__FILE__) . 'includes/OneSignalApiActivator.php';

add_action("admin_menu", "CreateMenu");
add_action("admin_menu", "SaveSettings");
add_action("admin_menu", "SaveLanguagesSettings");
add_action("admin_menu", "SendMessageTest");
add_action('admin_footer', 'MediaSelectorPrintScripts');
register_activation_hook(__FILE__, 'ActivatePlugin');
register_deactivation_hook(__FILE__, 'DeactivatePlugin');

function ActivatePlugin()
{
    OneSignalApiActivator::activate();
}

function DeactivatePlugin()
{
    OneSignalApiDeactivator::deactivate();
}

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

function SaveLanguagesSettings()
{
    if (isset($_POST['language_settings'])) {
        if (isset($GLOBALS["polylang"])) {
            $arrayLanguages = $GLOBALS["polylang"]->model->get_languages_list();
            foreach ($arrayLanguages as $language){
                OneSignalApi::UpdateOption('onesignal_language_'.$language->slug, $_POST['poly_'.$language->slug]);
            }
        }else{
           OneSignalApi::UpdateOption('default_onesignal_language', $_POST['poly_default']);
        }

        wp_redirect('/wp-admin/admin.php?page=one-signal-api');
        exit;
    }
}

function SendMessageTest()
{

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

    <div class="container">
        <div class="jumbotron jumbotron-fluid" style="background: linear-gradient(to bottom right, #14d66f, #39bee3);">
            <div class="container">
                <h1 class="display-4" style="color: white">One Signal API por <a href="https://www.gradiweb.com"
                                                                                 style="color: white" target="_blank">GradiWeb</a>
                </h1>
                <p class="lead" style="color: white">Plugin para el envio dinámico de notificaciones push mediante One
                    Signal.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-heading"><h5>Información One Signal</h5></div>
                    <div class="card-body">
                        <form method="post">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
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
            </div>
            <div class=" col-md-6">
                <div class="card">
                    <div class="card-heading">
                        <h5>Configuración del Plugin</h5> <br>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if (isset($GLOBALS["polylang"])): ?>
                                        <ul>
                                            <?php foreach ($arrayLanguages as $language): ?>
                                                <li data-value="<?php echo $language->locale ?>"><?php echo $language->name ?>

                                                    <input
                                                            type="text" class="form-control"
                                                            placeholder="Nombre del Segmento"
                                                            name="poly_<?php echo $language->slug ?>"
                                                            value="<?php echo OneSignalApi::GetOption('onesignal_language_'.$language->slug) ?>"
                                                    >
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <label for="default_one_signal_language">Default Segment</label>
                                        <input
                                                type="text" class="form-control"
                                                placeholder="Nombre del Segmento"
                                                name="poly_default"
                                                value="<?php echo OneSignalApi::GetOption('default_onesignal_language') ?>"
                                        >
                                        <br>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" name="language_settings" value="Guardar Cambios"
                                           style="float: right"
                                           class="button-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

function MediaSelectorPrintScripts()
{
    $my_saved_attachment_post_id = get_option('media_selector_attachment_id', 0);
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function ($) {
            var file_frame;
            var wp_media_post_id = wp.media.model.settings.post.id;
            var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>;
            jQuery('#upload_image_button').on('click', function (event) {
                event.preventDefault();
                if (file_frame) {
                    file_frame.uploader.uploader.param('post_id', set_to_post_id);
                    file_frame.open();
                    return;
                } else {
                    wp.media.model.settings.post.id = set_to_post_id;
                }
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image to upload',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false
                });
                file_frame.on('select', function () {
                    attachment = file_frame.state().get('selection').first().toJSON();
                    $('#image-preview').attr('src', attachment.url).css('width', 'auto');
                    $('#image_attachment_id').val(attachment.id);
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
                file_frame.open();
            });
            jQuery('a.add_media').on('click', function () {
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        });
    </script><?php
}


function InsertHead()
{
    echo OneSignalApi::SetToHeader();
}

function InsertFooter()
{
    $appId = OneSignalApi::GetOption('onesignal_app_id');
    echo OneSignalApi::SetToFooter($appId);
}

function InsertFiles()
{
    OneSignalApi::FilesToRoot();
}

add_action('init', 'InsertFiles');
add_action('wp_head', 'InsertHead');
add_action('wp_footer', 'InsertFooter');

add_action('transition_post_status', 'send_new_post', 10, 3);

// Listen for publishing of a new post
function send_new_post($new_status, $old_status, $post) {
    if('publish' === $new_status && 'publish' !== $old_status && $post->post_type === 'post') {
            OneSignalApi::SendMessage(OneSignalApi::GetOption('onesignal_app_id'), OneSignalApi::GetOption('onesignal_api_key'), $post);
    }
}