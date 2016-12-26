<?php
/**
 * Plugin Name: Rest Storage
 * Plugin URI: http://teresa.id
 * Description: Upload media ke REST bonus kami
 * Version: 1.0.0
 * Author: Teresa Pranyoto
 * Author URI: http://teresa.id
 */

//
//define('AM_BUCKET_NAME', get_option('amBucketName'));
//define('AM_HOSTNAME', get_option('amHostname'));
//define('AM_ACCESS_KEY', get_option('amAccessKeyId'));
//define('AM_SECRET_ACCESS_KEY', get_option('amSecretAccessKey'));

update_option('upload_url_path', 'https://rest.kaleb.sisdis.ui.ac.id/test-wp');

add_filter( 'wp_handle_upload', 'custom_upload_filter');
function custom_upload_filter( $file ) {
    $endpoint = 'https://rest.kaleb.sisdis.ui.ac.id';
    $bucket = "test-wp";
    $filepath = $file["file"];
    $keyname = substr($filepath, strrpos($filepath, '/') + 1);

    $url = "{$endpoint}/{$bucket}/${keyname}";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PUT, 1);

    $fh_res = fopen($filepath, 'r');

    curl_setopt($ch, CURLOPT_INFILE, $fh_res);
    curl_setopt($ch, CURLOPT_INFILESIZE, filesize($filepath));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $curl_response_res = curl_exec ($ch);
    fclose($fh_res);
    return $file;
}

// TODO: Delete media

?>