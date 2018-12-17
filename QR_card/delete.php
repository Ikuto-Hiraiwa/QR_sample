<?php

global $wpdb;
$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
require_once( $myPath );
	$url = site_url();


$single = true;
$qeury = "DELETE FROM `cats_qr` ORDER BY `id` DESC LIMIT 44;";
$result = $wpdb->query($qeury);

if($result!=NULL){
	echo '<br><br><h2>'.$result.'件のデータを消去しました。</h2>';
}else{
	echo '<br><br><h2>データの消去に失敗しました。</h2>';
}

echo '<a href="'.$url.'/qr"><button>QR情報へ戻る</button></a>';



?>