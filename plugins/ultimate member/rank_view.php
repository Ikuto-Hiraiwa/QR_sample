<html>

<script language="javascript" type="text/javascript">
<!--
function Display(no){
    if(no == "no1"){
        document.getElementById("SW1").style.display = "block";
        document.getElementById("SW2").style.display = "none";
    }else if(no == "no2"){
        document.getElementById("SW1").style.display = "none";
        document.getElementById("SW2").style.display = "block";
    }
}
-->

</script>
<div class="container">
            <div class="row">
              <div class="col-sm-6 col-xs-12 col-md-6" align="center">
                  <br><a href="javascript:;" onclick="Display('no1')"><input type="button" value="全国総合"></a>
              </div>
              <div class="col-sm-6 col-xs-12 col-md-6" align="center">
                  <br><a href="javascript:;" onclick="Display('no2')"><input type="button" value="今月総合"></a>
              </div>
            </div>
</div>
<div id="SW1">

<?php
global $wpdb;
$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
require_once( $myPath );
$url = site_url();

echo '<br><br><div align="center"><h2>全国総合ランキング</h2></div>';

$select_query = "SELECT * FROM `wp585502usermeta` WHERE `meta_key` = 'artist_point' ORDER BY `wp585502usermeta`.`point` DESC";
$result = $wpdb->get_results($select_query);
//デバッグ用
//var_dump($result);


    $rank = 0;
    $before_point = NULL;
// foreach文で配列の中身を一行ずつ出力
foreach ($result as $row) {
 
    // データベースのフィールド名で出力
    //デバッグ用
    //echo $row->artist_rank .'位'.'[ID : '.$row->user_id .']';

    $single = true; 
    $profile_id = $row->user_id;
    $profile_login = get_user_meta($profile_id , 'user_login' , $single);
    $avatar = get_avatar($profile_id,300);
    $status = get_user_meta($profile_id , 'account_status' , $single);


    if($status=='approved'){
      /*if($row->rank==9999999){
        $rank = '順位無し';
      }else{
        $rank = $row->rank.'位';
      }*/
    $this_point = get_user_meta($profile_id , 'artist_point' , $single);
    if($before_point != $this_point || $before_point==NULL){
      $rank++;
    }

    echo '
    <div class="container">
    <div class="row">
      <div class="col-sm-12 col-xs-8">';

        if($this_point==0){
          echo '<span><h2>順位無し</h2></span>';
        }else{
            echo '<span><h2>'.$rank.'位</h2></span>';
      }

      if($rank<4){
          echo '</div>
          <div class="col-sm-4 col-xs-4">
            <span class="author-thumbanil"><a href="'.$url.'/user/'. $profile_login . '">'.$avatar.'</a></span>
          </div>
          <div class="col-sm-12 col-xs-12">
            <br>
                                  <table border="1">

                                      <tr>
                                          <td width="30%">ユーザー名</td>
                                          <td><span><a href="'.$url.'/user/'. $profile_login . '">'.get_user_meta($profile_id , 'nickname' , $single).'</a></span></td>
                                      </tr>
                                      <tr>
                                          <td>ポイント</td>
                                          <td><span>'.$this_point.'P</span></td>
                                      </tr>
                                      <tr>
                                          <td>コメント</td>
                                          <td><span>'.get_user_meta($profile_id , 'description' , $single).'</span></td>
                                      </tr>
                                  </table>
              </div>
          </div>
      </div>
      <br><br>
      ';
    }else if($rank<8 && $rank>3){
      echo '</div>
          <div class="col-sm-12 col-xs-12">
            <br>
                                  <table border="1">

                                      <tr>
                                          <td width="30%">ユーザー名</td>
                                          <td><span><a href="'.$url.'/user/'. $profile_login . '">'.get_user_meta($profile_id , 'nickname' , $single).'</a></span></td>
                                      </tr>
                                      <tr>
                                          <td>ポイント</td>
                                          <td><span>'.$this_point.'P</span></td>
                                      </tr>
                                      <tr>
                                          <td>コメント</td>
                                          <td><span>'.get_user_meta($profile_id , 'description' , $single).'</span></td>
                                      </tr>
                                  </table>
              </div>
          </div>
      </div>
      <br><br>
      ';
    }else{
      echo '</div>
          <div class="col-sm-12 col-xs-12">
            <br>
                                  <table border="1">

                      
                                      <tr>
                                          <td>ポイント</td>
                                          <td><span>'.$this_point.'P</span></td>
                                      </tr>
                                      
                                  </table>
              </div>
          </div>
      </div>
      <br><br>
      ';
    }
  $before_point = $this_point;

  }
}

?>
</div>
<div id="SW2" style="display:none;">




<?php
global $wpdb;
$myPath = '/home/idol-faily-tales/www/test/wp-load.php';
require_once( $myPath );
$url = site_url();

echo '<br><br><div align="center"><h2>今月のランキング</h2></div>';

$select_query = "SELECT * FROM `wp585502usermeta` WHERE `meta_key` = 'artist_this_month_point' ORDER BY `wp585502usermeta`.`point` DESC";
$result = $wpdb->get_results($select_query);
//デバッグ用
//var_dump($result);


    $rank = 0;
    $before_point = NULL;
// foreach文で配列の中身を一行ずつ出力
foreach ($result as $row) {
 
    // データベースのフィールド名で出力
    //デバッグ用
    //echo $row->artist_rank .'位'.'[ID : '.$row->user_id .']';

    $single = true; 
    $profile_id = $row->user_id;
    $profile_login = get_user_meta($profile_id , 'user_login' , $single);
    $avatar = get_avatar($profile_id,300);
    $status = get_user_meta($profile_id , 'account_status' , $single);


    if($status=='approved'){
      /*if($row->rank==9999999){
        $rank = '順位無し';
      }else{
        $rank = $row->rank.'位';
      }*/
      $this_point = get_user_meta($profile_id , 'artist_this_month_point' , $single);
    if($before_point != $this_point || $before_point==NULL){
      $rank++;
    }

    echo '
    <div class="container">
    <div class="row">
      <div class="col-sm-12 col-xs-8">';

        if($this_point==0){
          echo '<span><h2>順位無し</h2></span>';
        }else{
            echo '<span><h2>'.$rank.'位</h2></span>';
      }
     
      if($rank<4){
          echo '</div>
          <div class="col-sm-4 col-xs-4">
            <span class="author-thumbanil"><a href="'.$url.'/user/'. $profile_login . '">'.$avatar.'</a></span>
          </div>
          <div class="col-sm-12 col-xs-12">
            <br>
                                  <table border="1">

                                      <tr>
                                          <td width="30%">ユーザー名</td>
                                          <td><span><a href="'.$url.'/user/'. $profile_login . '">'.get_user_meta($profile_id , 'nickname' , $single).'</a></span></td>
                                      </tr>
                                      <tr>
                                          <td>ポイント</td>
                                          <td><span>'.$this_point.'P</span></td>
                                      </tr>
                                      <tr>
                                          <td>コメント</td>
                                          <td><span>'.get_user_meta($profile_id , 'description' , $single).'</span></td>
                                      </tr>
                                  </table>
              </div>
          </div>
      </div>
      <br><br>
      ';
    }else if($rank<8 && $rank>3){
      echo '</div>
          <div class="col-sm-12 col-xs-12">
            <br>
                                  <table border="1">

                                      <tr>
                                          <td width="30%">ユーザー名</td>
                                          <td><span><a href="'.$url.'/user/'. $profile_login . '">'.get_user_meta($profile_id , 'nickname' , $single).'</a></span></td>
                                      </tr>
                                      <tr>
                                          <td>ポイント</td>
                                          <td><span>'.$this_point.'P</span></td>
                                      </tr>
                                      <tr>
                                          <td>コメント</td>
                                          <td><span>'.get_user_meta($profile_id , 'description' , $single).'</span></td>
                                      </tr>
                                  </table>
              </div>
          </div>
      </div>
      <br><br>
      ';
    }else{
      echo '</div>
          <div class="col-sm-12 col-xs-12">
            <br>
                                  <table border="1">

                      
                                      <tr>
                                          <td>ポイント</td>
                                          <td><span>'.$this_point.'P</span></td>
                                      </tr>
                                      
                                  </table>
              </div>
          </div>
      </div>
      <br><br>
      ';
    }
  $before_point = $this_point;

  }
}

?>
</div>
</html>