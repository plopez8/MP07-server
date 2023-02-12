<?php
/*
Plugin Name: Face Plugin
Description: Plugin de worpress para guardar una foto de tu cara cada dia
Version: 0.1
Author: Pau Lopez
*/
register_activation_hook(__FILE__, 'activacio');
register_deactivation_hook(__FILE__, 'desactivacio');


    function checkpost() {
        if (isset($_POST['submit'])) {
          saveimage();
        }
      }
    add_action('init', 'checkpost');


function saveimage(){
    $imagen = $_FILES['imageupload'];
    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['path'] . '/' . $imagen['name'];
    if (move_uploaded_file($imagen['tmp_name'], $upload_path)) {
      global $wpdb;
      $wpdb->insert('wp_fotos', array(
        'imagen' => $imagen['name'],
        'ruta' => $upload_path,
        'usuario' => wp_get_current_user()->user_login,
        'fecha' => date("Y-m-d"),
      ));
      debug_to_console("Error al guardar la imatge");
    } else {
      debug_to_console("Error al guardar la imatge");
    }
}



function activacio(){
    $process = plugin_dir_url( __FILE__ ) . "/upload.php";
    create_page("log", "cont", "publish", "page");
    create_page("add-log", '<form class="facelog_form" method="post" enctype="multipart/form-data">
    Hola'.wp_get_current_user()->user_login.', puja el teu log
    <select class="inline-input" name="today" id="date-today" onchange="changeDateSelect()" required>
        <option value="1">d\'avui</option>
        <option value="0">d\'un altre dia</option>
    </select>:
    <div class="facelog_date" id="facelog_setdate" style="display: none">
        <label> Data: </label><input name="date" class="inline-input" type="date">
    </div>

    <div class="facelog_upload">
        <input class="clear-input" type="file" name="imageupload" id="imatgeupload" required>
        <input type="submit" value="Puja" name="submit">
    </div>
</form>', "private", "page");
    create_table();
}

function desactivacio(){
    delete_page('log');
    delete_page('add-log');
    delete_table();
}

function delete_page($titol){
    $page = get_page_by_title($titol);
    if ($page) {
      wp_delete_post($page->ID, true);
    }
}


function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}


function create_page($titol, $contingut, $estat, $tipus) {
    $pagina = array(
      'post_title' => $titol,
      'post_content' => $contingut,
      'post_status' => $estat,
      'post_type' => $tipus
    );
    wp_insert_post($pagina);
}

function create_table(){
    global $wpdb;
    $tabla = $wpdb->prefix . "fotos";
    $sql = "CREATE TABLE $tabla (
        id INT(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        imagen VARCHAR(100),
        ruta VARCHAR(100),
        usuario VARCHAR(100),
        fecha DATE        
    );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function delete_table() {
    global $wpdb;
    $tabla = $wpdb->prefix . "fotos";
    $sql = "DROP TABLE IF EXISTS $tabla;";
    $wpdb->query($sql);
}

?>
