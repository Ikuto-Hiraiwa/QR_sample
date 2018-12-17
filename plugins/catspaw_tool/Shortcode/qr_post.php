<?php

global $wpdb;
$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
require_once( $myPath );
$url = site_url();

$select_query = "SELECT * FROM `wp585502usermeta` WHERE `meta_value` = 'um_shop_member'";
$result = $wpdb->get_results($select_query);
//var_dump($result);


echo '<table border="1">
		<tr><h3>QRコード作成情報</h3></tr>';
  echo '<tr>';
			echo '<td width="30%"><h4>管理者名</h4></td>';
			echo '<td width="30%"><h4>発行枚数</h4></td>';
			echo '<td width="30%"><h4>使用された枚数</h4></td>';
  echo '</tr>';

		foreach ($result as $row) {
			$profile_id = $row->user_id;
			$single = true;
			echo '<tr>';
			echo '<td width="30%">'.get_user_meta($profile_id , 'nickname' , $single).'</td>';
			$card_query = "SELECT MAX(number) FROM `cats_qr` WHERE shop='".$profile_id."';";
			$card_result = $wpdb->get_var($card_query);
			if($card_result==NULL){
				$card_result = 0;
			}
			echo '<td width="30%">'.$card_result.'枚</td>';
			$available_query = "SELECT SUM(available) FROM `cats_qr` WHERE shop='".$profile_id."';";
			$available_result = $wpdb->get_var($available_query);
			$used_card = $card_result - $available_result;
			echo '<td width="30%">'.$used_card.'枚</td>';
			echo '</tr>';

		}

echo '</table><br><br>';
		

echo '<form method="post" action="'.$url.'/QR_card/insatsu.php">';
echo '<span>管理者選択</span>';
echo '<select name="shop_id">';

foreach($result as $row){

	$profile_id = $row->user_id;
	$single = true;

	echo '<option value="'.$profile_id.'">' . get_user_meta($profile_id , 'nickname' , $single) . '</option>';

}
echo '</select><br><br>';

echo '<span>ポイント：</span>';
echo '<select name="point">';
echo '<option value="1">1</option>';
echo '<option value="2">2</option>';
echo '</select>';

echo '<br><br>
<input type="submit" value="送信">
</form>
';

?>
