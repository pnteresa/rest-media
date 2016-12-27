<?php
/**
 * Plugin Name: Rest Storage
 * Plugin URI: http://teresa.id
 * Description: Upload media ke REST bonus kami
 * Version: 1.0.0
 * Author: Teresa Pranyoto
 * Author URI: http://teresa.id
 */

$endpoint = 'https://rest.kaleb.sisdis.ui.ac.id';
$bucket = "test-wp";
update_option('upload_url_path', "${endpoint}/${bucket}");

add_filter( 'wp_handle_upload', 'custom_upload_filter');
function custom_upload_filter( $file ) {
    global $endpoint, $bucket;
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

add_action( 'delete_attachment', 'delete_onstorage' );
function delete_onstorage( $post_id )
{

    global $endpoint, $bucket;
    $filepath = get_attached_file($post_id);



    $keyname = substr($filepath, strrpos($filepath, '/') + 1);


//    $postid = url_to_postid( $filepath );
//    $str = explode("/", $filepath);
//    echo "${str[count(${str})-3]}/${str[count(${str})-2]}";
    $url_wp = "${endpoint}/${bucket}/${keyname}";



//    $url = 'http://localhost/wp_sisdis_1/writer.php';
//    $data = array('content' => $url_wp);
//
//// use key 'http' even if you send the request to https://...
//    $options = array(
//        'http' => array(
//            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
//            'method'  => 'POST',
//            'content' => http_build_query($data)
//        )
//    );
//    $context  = stream_context_create($options);
//    $result = file_get_contents($url, false, $context);
//    return;

//    $result = "";
//    $result.="ehehe\n";
//    $result.="url: ${url}\n";
    $result.="keyname: ${keyname}\n";
//        echo "keyname: ${keyname}\n";
//        var_dump($e);
//    $result = ob_get_clean();



    try {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url_wp);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);



    } catch (Exception $e) {
        print_r($e);



    }
}



?>