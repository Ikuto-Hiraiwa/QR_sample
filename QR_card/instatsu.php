<html>
		<head>
		<link rel="stylesheet" type="text/css" href="./a4.css" media="all">
		</head>
		<body>
<?php

global $wpdb;
$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
require_once( $myPath );
	$url = site_url();


if($_POST['shop_id']==NULL || $_POST['point']==NULL){
			echo '<h3>エラーが発生しました。戻って生成し直してください。</h3>';
			echo '<a href="'.$url.'/qr/"><button>もどる</button></a>';
			exit();
}else{
}
$single = true;

if(isset($_POST)){

	$shop_id = $_POST['shop_id'];
	$point = $_POST['point'];

	$max_query = "SELECT MAX(id) FROM `cats_qr`";
	$max_shop_query = "SELECT MAX(number) FROM `cats_qr` WHERE shop = '".$shop_id."';";
	$result = $wpdb->get_var($max_query);
	$shop_result = $wpdb->get_var($max_shop_query);
	$max_id = NULL;
	$shop_max_id=NULL;
	$shop_name_query = "SELECT `meta_value` FROM `wp585502usermeta` WHERE `user_id`=".$shop_id." AND `meta_key`='nickname';";
	$shop_name = $wpdb->get_var($shop_name_query);
	$avatar = get_avatar($shop_id,40);

	//idの最大値を取得
	if($result==NULL){
		$max_id = 1;
	}else{
		$max_id = $result+1;
	}
	//生成枚数を制限
	$max_id_limit = $max_id + 44;

	//shopの連番であるnumberの最大値を取得
	if($shop_result==NULL){
		$shop_max_id = 1;
	}else{
		$shop_max_id = $shop_result+1;
	}


	//secretを作成するランダム関数
	function makeRandStr($length=20){
	            $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
	            $r_str = null;
	            for ($i = 0; $i < $length; $i++) {
	                $r_str .= $str[rand(0, count($str) - 1)];
	            }
	      
	            return $r_str;
	}

	echo '<table>';
	$tr_count=0;

	for($max_id;$max_id<$max_id_limit;$max_id++){
		//echo '<span>ID : '.$max_id. ' -> '.makeRandStr().'</span><br>';
		$secret_id=makeRandStr();
		$wpdb->insert(
			'cats_qr',
			array(
				'id'=>$max_id,
				'number'=>$shop_max_id,
				'secret'=>$secret_id,
				'shop'=>$shop_id,
				'point'=>$point,
				'available'=>True
			)
		);
		if($tr_count==0){
			echo '<tr>';
		}

		echo '<td>
				<span>'.$shop_name.'</span>
				<img src="https://api.qrserver.com/v1/create-qr-code/?data='.$url.'/vote?secret='.$secret_id.'&size=65x65" alt="QRコード" />
				
			 </td>';

		$tr_count+=1;

		if($tr_count==4){
			$tr_count=0;
			echo '</tr>';
		}


		$shop_max_id++;
	}

	echo '</table>';
	echo '<h2>Sucess!!</h2>';
	echo '<h2><a href="'.$url.'/QR_card/delete.php">生成したデータを消去する</a></h2>';
}

if(isset($_POST)){
    $point = $_POST['point'];
    $shop_id = $_POST['shop_id'];
    echo '<ul>';
    echo '<li>ポイント : '.$point.'</li>';
    echo '<li>ショップID : '.$shop_id.'</li>';
    echo '<li>ショップ名 : '.get_user_meta($shop_id , 'nickname' , $single).'</li>';
    echo '<li>rand : '.makeRandStr().'</li>';
    echo '</ul>';
}

echo '<a href="'.$url.'/qr"><button>QR情報へ戻る</button></a>';



?>

</body>
</html>
