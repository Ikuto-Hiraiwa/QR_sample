<?php
	global $wpdb;
	$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
	require_once( $myPath );
	$url = site_url();


	$vote_id = $_GET['id'];
	$secret_id = $_GET['secret'];
	//debug
	/*
	echo 'ID : '.$vote_id;
	echo '<br>Secret : '.$secret_id.'<br>';
	*/

	/*-----------QRコード有効かチェック----------*/
	$qr_query = "SELECT `available` FROM `cats_qr` WHERE `secret`='".$secret_id."';";
	$qr_available = $wpdb->get_var($qr_query);
	//debug
	//echo '<br>Available : '.$qr_available;



	if($secret_id==NULL){
		echo '<h2>QRコードをもう一度読み直してください。</h2><br>';
		echo '<a href="'.$url.'"><button>トップへ戻る</button></a>';
	}else if($qr_available==1){
		$single = true; 
		$avatar = get_avatar($vote_id,150);

		$select_rank_query = "SELECT * FROM `wp585502usermeta` WHERE `meta_key` = 'artist_point' ORDER BY `wp585502usermeta`.`point` DESC";
		$result_rank = $wpdb->get_results($select_rank_query);
		$rank = 0;
	    $before_point = NULL;
	    $array_rank = array();
	  	    foreach ($result_rank as $key) {
		    	$profile_id = $key->user_id;
		    	$this_point = get_user_meta($profile_id , 'artist_point' , $single);
			    if($before_point != $this_point || $before_point==NULL){
			      $rank++;
			    }
			    
			    $array_rank+=array($profile_id=>$rank);


			    $before_point = $this_point;
		    }
		if($array_rank[$vote_id]!=1){
		  	$search_rank = $array_rank[$vote_id]-1;
		  	$rank_up_rank = array_search($search_rank,$array_rank);
			$rank_up_point = get_user_meta($rank_up_rank, 'artist_point' , $single) - get_user_meta($vote_id , 'artist_point' , $single);
		}

					  	/*-----ユーザの推し順位を出す*/

			  	$artist_rireki = $wpdb->get_var("SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`='".$vote_id."' AND `meta_key`='vote_rireki'");
				$artist_rireki_unserialize = unserialize($artist_rireki);
				$user = wp_get_current_user();
//				$user_name = $user->nickname;
				$login_user_id = $user->ID;

				//var_dump($artist_rireki_unserialize);
				$count = 0;
				$flag = 0;
				$before_rank_point = 0;
				//debug
				//var_dump($artist_rireki_unserialize);
				arsort($artist_rireki_unserialize);
				//debug
				//var_dump($artist_rireki_unserialize);
				//echo $login_user_id;

				$oshi_rank = "このメンバーにはまだ投票していません";
				$oshi_rank_up = NULL;

				if ( array_key_exists($login_user_id, $artist_rireki_unserialize)!=false ) {
					foreach ($artist_rireki_unserialize as $key => $value) {	
						//$get_fan_name = get_user_meta($key , 'nickname' , $single);
						//$get_fan_id = get_user_meta($key , 'user_login' , $single);

						$count++;
						if($key==$login_user_id){
							$oshi_rank = '現在 : '.$count.'位 [ '.$value.' P]';
							if($count!=1){
								$up_point = $before_rank_point - $value;
								if($up_point==0){
									$oshi_rank_up =  '推しランクアップまで1P<br>';
								}else{
									$oshi_rank_up = '推しランクアップまで'.$up_point.'P<br>';
								}
								
							}
							$flag=1;
							break;
						}
						$before_rank_point = $value;
						//echo $count;
					}
				}
		

				/*-----ここまで-------*/


		echo '
			<div class="container">
					<div class="row">
						<div class="col-sm-12 col-xs-12 col-md-12" align="center">
						 	<span class="author-thumbanil">'.$avatar.'</span><br>
						</div>
						<div class="col-sm-12 col-xs-12 col-md-12">
							<table>
								<tr><td>'.get_user_meta($vote_id , 'nickname' , $single).'</td></tr>';
								if(get_user_meta($vote_id , 'artist_point' , $single)==0){
									echo '<tr><td>現在 : 順位無し</td></tr>';
								}else{
									echo '<tr><td>現在 : '.$array_rank[$vote_id].'位</td></tr>';
								}
								echo '<tr><td>現在 : '.get_user_meta($vote_id , 'artist_point' , $single).'P</td></tr>';
								if($array_rank[$vote_id]!=1 && get_user_meta($vote_id , 'artist_point' , $single)!=0){
									echo '<tr><td>ランクアップまで'.$rank_up_point.'P</td></tr>';
								}else if(get_user_meta($profile_id , 'artist_point' , $single)==0){
									echo'<tr><td>順位無し</td></tr>';
								}else{
									echo '<tr><td>王者</td></tr>';
								}
								echo '	
									<tr><td><h5>あなたの推しランク</h5>';
										echo $oshi_rank.'<br>';
										if($oshi_rank_up!=NULL){
											echo $oshi_rank_up.'</td></tr>';
										}
							echo '</table>
						</div>
					</div>
			</div>
			<br>';
		echo '<h3>このアーティストに投票しますか？</h3><br>';
		echo '
			<div align="center">
				<a href="'.$url.'/vote/vote_after?id='.$vote_id.'&secret='.$secret_id.'"><button>投票する</button>
			</div>';


	}else{
		echo '<h2>このQRコードは使用済です。</h2><br>';
		echo '<a href="'.$url.'"><button>トップへ戻る</button></a>';
	}

?>