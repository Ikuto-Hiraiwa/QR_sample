<?php

	$vote_id = $_GET['id'];
	$secret_id = $_GET['secret'];
	$url = site_url();

	//debug
	/*
	echo 'ID : '.$vote_id;
	echo '<br>Secret : '.$secret_id.'<br>';
	*/

	$user = wp_get_current_user();
	//debug
	//echo 'User : '.$user->ID.'<br>';

	/*-----------QRコード有効かチェック----------*/
	$qr_query = "SELECT `available` FROM `cats_qr` WHERE `secret`='".$secret_id."';";
	$qr_available = $wpdb->get_var($qr_query);
	//debug
	//echo 'Available : '.$qr_available.'<br>';
	if($qr_available==1){
		/*--------------QRを無効に----------------*/
		$available_query = "UPDATE `cats_qr` SET `available`= 0 WHERE `secret`='".$secret_id."';";
		$wpdb->query($available_query);
		/*----------ポイント確認------------------^*/
		$point = $wpdb->get_var("SELECT `point` FROM `cats_qr` WHERE `secret`='".$secret_id ."';");
		
		/*----------アーティストにポイント等を追加---------*/
		$artist_point_query="UPDATE `wp585502usermeta` SET `point`= `point`+".$point.", `meta_value`= `meta_value`+".$point." WHERE `meta_key`='artist_point' AND `user_id`=".$vote_id.";";
		$artist_this_month_point_query="UPDATE `wp585502usermeta` SET `point`= `point`+".$point." , `meta_value`= `meta_value`+".$point." WHERE `meta_key`='artist_this_month_point' AND `user_id`=".$vote_id.";";
		$wpdb->query($artist_point_query);
		$wpdb->query($artist_this_month_point_query);

		/*------------ファンにポイント追加----------*/
		$fan_point_query="UPDATE `wp585502usermeta` SET `point`= `point`+".$point." , `meta_value`= `meta_value`+".$point." WHERE `meta_key`='fan_point' AND `user_id`=".$user->ID.";";
		$fan_this_month_point_query="UPDATE `wp585502usermeta` SET `point`= `point`+".$point." , `meta_value`= `meta_value`+".$point." WHERE `meta_key`='fan_this_month_point' AND `user_id`=".$user->ID.";";
		$wpdb->query($fan_point_query);	
		$wpdb->query($fan_this_month_point_query);	

		/*----------ショップにポイント追加*/
		$shop_number = $wpdb->get_var("SELECT `shop` FROM `cats_qr` WHERE `secret`='".$secret_id."';");
		$shop_poit_query="UPDATE `wp585502usermeta` SET `point`= `point`+".$point." , `meta_value`= `meta_value`+".$point." WHERE `meta_key`='activate_point' AND `user_id`=".$shop_number.";";
		$wpdb->query($shop_poit_query);

		/*----------アーティストの履歴に使用ユーザーを挿入---------*/
		$artist_rireki = $wpdb->get_var("SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`='".$vote_id."' AND `meta_key`='vote_rireki'");
		//アーティストの投票履歴が空の場合(誰からも投票されていない場合)
		if($artist_rireki==NULL){
			$rireki = array($user->ID => 1);
			$serial = serialize($rireki);

			$result = $wpdb->update('wp585502usermeta',array(
					'meta_value'=>$serial
				),
				array(
					'user_id'=>$vote_id,
					'meta_key'=>'vote_rireki'
				)
			);
		}else{
			//履歴がすでにある場合(投票されている場合)
			
			$artist_rireki_get = $wpdb->get_var("SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`='".$vote_id."' AND `meta_key`='vote_rireki'");
			$unserialize = unserialize($artist_rireki_get);
			$flag=0;

			foreach ($unserialize as $key => $value) {
				if($key==$user->ID){
					$value++;
					$unserialize[$key] = $value;
					$flag=1;
					break;
				}
			}
			
			if($flag==0){
				$unserialize += array($user->ID => 1);
			}
			
			$serial = serialize($unserialize);
			$result = $wpdb->update('wp585502usermeta',array(
					'meta_value'=>$serial
				),
				array(
					'user_id'=>$vote_id,
					'meta_key'=>'vote_rireki'
				)
			);
		}
		/*----------------------------ここまで------------------------*/


		/*------------ユーザーの投票履歴にアーティストを挿入--------------*/
		$fan_rireki = $wpdb->get_var("SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`='".$user->ID."' AND `meta_key`='rireki'");
		if($fan_rireki==NULL){
			$rireki = array($vote_id);
			$serial = serialize($rireki);

			$result = $wpdb->update('wp585502usermeta',array(
					'meta_value'=>$serial
				),
				array(
					'user_id'=>$user->ID,
					'meta_key'=>'rireki'
				)
			);
		}else{
			//履歴がすでにある場合(投票されている場合)
			$fan_rireki_get = $wpdb->get_var("SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`='".$user->ID."' AND `meta_key`='rireki'");
			$unserialize = unserialize($fan_rireki_get);

			foreach ($unserialize as $key => $value) {
				if($value==$vote_id){
					unset($unserialize[$key]);
					break;
				}
			}

			
			array_unshift($unserialize,$vote_id);
			$unserialize = array_values($unserialize);

			$serial = serialize($unserialize);
			$result = $wpdb->update('wp585502usermeta',array(
					'meta_value'=>$serial
				),
				array(
					'user_id'=>$user->ID,
					'meta_key'=>'rireki'
				)
			);
		}





		/*-----------QRコードに情報格納-----------*/
		$modified_query = "UPDATE `cats_qr` SET `modified`=CURRENT_TIMESTAMP(6) WHERE `secret`='".$secret_id."';";
		$use_user_query = "UPDATE `cats_qr` SET `use_user`=".$user->ID." WHERE `secret`='".$secret_id."';";
		$use_artist_query = "UPDATE `cats_qr` SET `user_artist`=".$vote_id." WHERE `secret`='".$secret_id."';";
		$wpdb->query($modified_query);
		$wpdb->query($use_user_query);
		$wpdb->query($use_artist_query);

		/*ユーザーid取得*/
		$account = get_user_meta($user->ID,'user_login',True);

		return '<div align="center"><h2>投票完了しました!</h2></div><br><div align="center"><a href="'.$url.'"><button>トップへ戻る</button></a></div><br><br><br><div align="center"><a href="'.$url.'/user/'.$account.'"><button>マイアカウント</button></a></div>';
	}else{
		return '<h2>このQRは使用済みです。</h2><br><a href="'.$url.'"><button>トップへ戻る</button></a>';
	}
	

?>