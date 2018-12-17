<?php
session_start();

	global $wpdb;
	$url = site_url();
	$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
	require_once( $myPath );

	$secret_id = $_GET['secret'];
	//echo 'GET : '.$_GET['secret'];
		wp_logout();
	if($secret_id!=NULL){
		wp_safe_redirect( $url.'/vote?secret='.$secret_id );
		exit();
	}else{
		echo '<h3>ログアウト完了しました。</h3>';
		echo '<br><a href="'.$url.'"><button>ホームへ戻る</button></a><br>';
	}

?>