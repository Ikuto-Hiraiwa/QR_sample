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
							echo '</table>
						</div>
					</div>
			</div>
			<br>';
		echo '<h3>このアーティストに投票しますか？</h3><br>';
		echo '
			<div align="center">
				<a href="'.$url.'/vote_anonymous/vote_after_anonymous?id='.$vote_id.'&secret='.$secret_id.'"><button>投票する</button>
			</div>';


	}else{
		echo '<h2>このQRコードは使用済です。</h2><br>';
		echo '<a href="'.$url.'"><button>トップへ戻る</button></a>';
	}

?>