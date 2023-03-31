<?php
/**
 * Plugin Name: Custom Head Tags
 * Plugin URI: https://www.petitjardinvert.fr/custom-head-tags
 * Description: Este plugin permite agregar etiquetas personalizadas entre las etiquetas <head> del sitio.
 * Version: 1.0
 * Author: Tu Nombre
 * Author URI: https://www.petitjardinvert.fr
 **/

if (!defined('ABSPATH')) {
    exit; // Evita el acceso directo al archivo.
}

// Función para registrar la página de opciones
function custom_head_tags_menu() {
    add_options_page(
        'Custom Head Tags',
        'Custom Head Tags',
        'manage_options',
        'custom-head-tags',
        'custom_head_tags_options_page'
    );
}
add_action('admin_menu', 'custom_head_tags_menu');

// Función para generar el contenido de la página de opciones
function custom_head_tags_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('No tienes suficientes permisos para acceder a esta página.'));
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('custom_head_tags_options');
            do_settings_sections('custom-head-tags');
            submit_button('Guardar cambios');
            ?>
        </form>
    </div>
    <?php
}

// Función para registrar la configuración y campos del formulario
function custom_head_tags_settings() {
    register_setting(
        'custom_head_tags_options',
        'custom_head_tags_content',
        'custom_head_tags_sanitize_content'
    );

    add_settings_section(
        'custom_head_tags_section',
        'Etiquetas personalizadas para el head',
        'custom_head_tags_section_callback',
        'custom-head-tags'
    );

    add_settings_field(
        'custom_head_tags_content',
        'Contenido de las etiquetas',
        'custom_head_tags_content_callback',
        'custom-head-tags',
        'custom_head_tags_section'
    );
}
add_action('admin_init', 'custom_head_tags_settings');

// Funciones de devolución de llamada (callbacks)
function custom_head_tags_section_callback() {
    echo '<p>Ingresa el contenido de las etiquetas personalizadas que deseas agregar en el head del sitio.</p>';
}

function custom_head_tags_content_callback() {
    $content = get_option('custom_head_tags_content', '');
    echo '<textarea name="custom_head_tags_content" rows="10" cols="50" id="custom_head_tags_content" class="large-text code">' . esc_textarea($content) . '</textarea>';
}

// Función para sanear el contenido de las etiquetas
function custom_head_tags_sanitize_content($content) {
    return wp_kses_post($content);
}

function custom_head_tags() {
    $content = get_option('custom_head_tags_content', '');
    if (!empty($content)) {
        echo "<!-- Etiquetas personalizadas para el head -->\n";
        echo $content . "\n";
        echo "<!-- Fin de las etiquetas personalizadas para el head -->\n";
    }
}

add_action('wp_head', 'custom_head_tags');