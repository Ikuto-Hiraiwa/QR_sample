<?php


	global $wpdb;
	$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
	require_once( $myPath );
	$url = site_url();


	$secret_id = $_GET['secret'];


	/*デバッグ用*/
	/*
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
			echo '<h4>よううこそゲストさん!</h4><br>';

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
								echo '<a href="'.$url.'/vote_anonymous/vote_check_anonymous?id='.$profile_id.'&secret='.$secret_id.'"><button>投票する</button></a>
							</div>
					    </div>
					</div>
					<br><br>
					';
				}
			}
	}else{
		echo '<h2>このQRコードは使用済です。</h2><br>';
		echo '<a href="'.$url.'"><button>トップへ戻る</button></a>';
	}
    
?>