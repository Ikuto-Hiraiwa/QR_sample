<?php
	session_start();

	$url = site_url();
	global $wpdb;
	$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
	require_once( $myPath );

	$secret_id = $_GET['secret'];


	if(isset($_SESSION['secret'])){
		$secret_id = $_SESSION['secret'];
	}
	/*デバッグ用*//*
	echo 'SESSION : ' .$_SESSION['secret'].'<br>';
	echo 'GET :' .$_GET['secret'].'<br>';
	echo 'secret_id :'.$secret_id.'<br>';
	*/

	/*------------QRコードの有効か無効化を判定----------*/
	if($secret_id!=NULL){
		$qr_query = "SELECT `available` FROM `cats_qr` WHERE `secret`='".$secret_id."';";
		$qr_available = $wpdb->get_var($qr_query);
		//デバッグ
		//echo 'Available : ' .$qr_available.'<br>';
	}else{
		//デバッグ
		//echo 'Available Error!<br>';
	}
	
	if($secret_id==NULL){
		echo '<h2>QRコードをもう一度読み直してください。</h2><br>';
		echo '<a href="'.$url.'"><button>トップへ戻る</button></a>';
	}else if($qr_available==1 && $secret_id!=NULL){
		if (is_user_logged_in()){
			$user = wp_get_current_user();
			$user_name = $user->nickname;
			$login_user_id = $user->ID;

			echo '<h4>ようこそ、'.$user_name.'さん!</h4><br>';
			echo '<div class="col-sm-12 col-xs-12" align="center"><a href="'.$url.'/logout_check?secret='.$secret_id.'"><button>ログアウトする</button></a></div><br><br><br><br>';

				/*--------------投票時アーティスト表示部---------------*/
			$select_rank_query = "SELECT * FROM `wp585502usermeta` WHERE `meta_key` = 'artist_point' ORDER BY `wp585502usermeta`.`point` DESC";
			$result_rank = $wpdb->get_results($select_rank_query);

			/*----順位を出す---------*/
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


			/*-----------履歴表示-----------------*/
			$vote_rireki = $wpdb->get_var("SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`='".$login_user_id."' AND `meta_key`='rireki'");
			$unserialize = unserialize($vote_rireki);
			echo '<h3>【投票履歴】</h3><br>';
			if($unserialize==NULL){
				echo '<h4>履歴なし</h4><br>';
			}else{
				$single = True;
				echo '<table>
						<tr>
							<td>アーティスト</td>
							<td>アーティストランク</td>
							<td>総ポイント</td>
							<td>ランクアップまで</td>
						<tr>';
				foreach ($unserialize as $key => $value) {	
					$get_artist_name = get_user_meta($value , 'nickname' , $single);
					$get_artist_id = get_user_meta($value , 'user_login' , $single);
					$get_artist_point = get_user_meta($value , 'artist_point' , $single);
					//$get_artist_rank = $wpdb->get_var("SELECT `rank` FROM `cats_usermeta` WHERE `meta_key`='artist_point' AND `user_id`=".$value.";");
					if($array_rank[$value]==1){
						$rank_up_point = '王者';
					}else{
						$search_rank = $array_rank[$value]-1;
				  		$rank_up_rank = array_search($search_rank,$array_rank);
				  		$rank_up_point = get_user_meta($rank_up_rank, 'artist_point' , $single) - $get_artist_point;
					}
					$count++;
					if($rank_up_point==0 && $rank_up_point!='王者'){
						$rank_up_point = '同率順位';
					}
					echo '<tr>
							<td><a href="'.$url.'/vote/vote_check?id='.$value.'&secret='.$secret_id.'">'.$get_artist_name.'</a></td>';
							echo '<td>'.$array_rank[$value].'位</td>';
							echo '<td>'.$get_artist_point.'P</td>';
							if($rank_up_point == '王者'){
								echo '<td>'.$rank_up_point.'</td>';
							}else{
								echo '<td>'.$rank_up_point.'P</td>';
							}
							
					echo '</tr>';

					if($count>4){
						break;
					}
				}
				echo '</table><br><br><br>';
			}
		    /*-----ここまで------*/

		    echo '<h3>投票情報【総合】</h3><br>';

			$select_query = "SELECT * FROM `wp585502usermeta` WHERE `meta_key` = 'artist_point'";
			$result = $wpdb->get_results($select_query);
			//デバッグ用
			//var_dump($result);

			// foreach文で配列の中身を一行ずつ出力
			foreach ($result as $row) {				 
				  // データベースのフィールド名で出力
				 	//デバッグ用
				 	//echo $row->artist_rank .'位'.'[ID : '.$row->user_id .']';
				$single = true; 
			 	$profile_id = $row->user_id;
			  	$profile_login = get_user_meta($profile_id , 'user_login' , $single);
			  	$avatar = get_avatar($profile_id,150);
			  	$status = get_user_meta($profile_id , 'account_status' , $single);

			  	if($array_rank[$profile_id]!=1){
			  		$search_rank = $array_rank[$profile_id]-1;
			  		$rank_up_rank = array_search($search_rank,$array_rank);
			  		$rank_up_point = get_user_meta($rank_up_rank, 'artist_point' , $single) - get_user_meta($profile_id , 'artist_point' , $single);
			  	}
			  	/*-----ユーザの推し順位を出す*/

			  	$artist_rireki = $wpdb->get_var("SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`='".$profile_id."' AND `meta_key`='vote_rireki'");
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
			  	if($status=='approved'){
			  		/*if($row->rank==99999){
			  			$rank = '順位無し';
			  		}else{
			  			$rank = $row->rank.'位';
			  		}*/
				  		
				 	echo '
				 	<div class="container" style="border:#dcdcdc solid 1.5px; padding : 10px 10px 10px 10px;">
						<div class="row">
							<div class="col-sm-5 col-xs-5 col-md-5">
							 	<span class="author-thumbanil">'.$avatar.'</span><br>
								<table>
									<tr><td>'.get_user_meta($profile_id , 'nickname' , $single).'</td></tr>';
									if(get_user_meta($profile_id , 'artist_point' , $single)==0){
										echo '<tr><td>現在 : 順位無し</td></tr>';
									}else{
										echo '<tr><td>現在 : '.$array_rank[$profile_id].'位</td></tr>';
									}
									echo '<tr><td>現在 : '.get_user_meta($profile_id , 'artist_point' , $single).'P</td></tr>
								</table>
							</div> 
							<div class="col-sm-7 col-xs-7 col-md-7">';
							if($array_rank[$profile_id]!=1 && get_user_meta($profile_id , 'artist_point' , $single)!=0){
							 	echo '<p>ランクアップまで'.$rank_up_point.'P</p>';
							}else if(get_user_meta($profile_id , 'artist_point' , $single)==0){
								echo '<p>順位無し</p>';
							}else{
								echo '<p>王者</p>';
							}
							echo '<a href="'.$url.'/vote/vote_check?id='.$profile_id.'&secret='.$secret_id.'"><button>投票する</button></a><br><br><br>';
							echo '	
							</div><br><br>
							<div class="col-sm-5 col-xs-5 col-md-5">
								<table>
									<tr><td><h5>あなたの推しランク</h5>';
										echo $oshi_rank.'<br>';
										if($oshi_rank_up!=NULL){
											echo $oshi_rank_up.'</td></tr>';
										}
								echo '</table>
							</div>
					    </div>
					</div>
					<br><br>
					';
				}
			}
		}else{
			echo '<h3>ログインしていません。</h3><br>';
			echo '<div class="col-sm-6 col-xs-6">
					<a href="'.$url.'/vote-login?secret='.$secret_id.'"><button>ログインへ進む</button></a>
				  </div><br><br><br><br><br>';
			echo '<div class="col-sm-6 col-xs-6">
					<a href="'.$url.'/vote_anonymous?secret='.$secret_id.'"><button>無記名投票</button></a>
				  </div>';
			echo '<br><br><br><br><br><div class="col-sm-12 col-xs-12" align="center"><a href="'.$url.'/vote_fan_new?secret='.$secret_id.'"><button>新規登録へ進む</button></a></div>';
		}
	}else{
		echo '<h2>このQRコードは使用済です。</h2><br>';
		echo '<a href="'.$url.'"><button>トップへ戻る</button></a>';
	}
    
	session_destroy();
?>