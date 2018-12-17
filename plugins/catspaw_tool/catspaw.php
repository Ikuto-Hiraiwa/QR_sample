<?php
/*
Plugin Name: Cat's paw Sample
Plugin URI: https://catspaw.ml
Description: テキストの表示。
Author: Ikuto Hiraiwa
Version: 0.1
Author URI: https://ikutohiraiwa.ml
*/


//------------サンプルショートコード-------------//
function sample(){
	require "Shortcode/sample.php";
}
add_shortcode('shortcode_sample','sample');
//------------------------------------------------------//


//------------サンプルショートコード-------------//
function Point_view(){
        require "Shortcode/point_view.php";
}
add_shortcode('View_point' , 'Point_view');
//------------------------------------------------------//


//------------アーティストのランキング表示コード----------------//
function Rank_view(){
        require "Shortcode/rank_view.php";
}
add_shortcode('Ranking_area' , 'Rank_view');
//------------------------------------------------------//

//------------ファンのランキング表示コード----------------//
function Rank_view_fan(){
        require "Shortcode/rank_view_fan.php";
}
add_shortcode('Ranking_area_fan' , 'Rank_view_fan');
//------------------------------------------------------//

//------------QRコード作成前ページ----------------//
function QR_post(){
        require "Shortcode/qr_post.php";
}
add_shortcode('sc_qr_post' , 'QR_post');
//------------------------------------------------------//

//------------QRコード投票ページ----------------//
function QR_vote(){
        require "Shortcode/vote.php";
}
add_shortcode('sc_qr_vote' , 'QR_vote');
//------------------------------------------------------//


//------------投票確認ページ----------------//
function Vote_check(){
        require "Shortcode/vote_check.php";
}
add_shortcode('sc_vote_check' , 'Vote_check');
//------------------------------------------------------//

//------------投票完了ページ----------------//
function Vote_after(){
        require "Shortcode/vote_after.php";
}
add_shortcode('sc_vote_after' , 'Vote_after');
//------------------------------------------------------//

//------------QRコード無記名投票ページ----------------//
function QR_vote_anonymous(){
        require "Shortcode/vote_anonymous.php";
}
add_shortcode('sc_qr_vote_anonymous' , 'QR_vote_anonymous');
//------------------------------------------------------//

//------------無記名投票確認ページ----------------//
function Vote_check_anonymous(){
        require "Shortcode/vote_check_anonymous.php";
}
add_shortcode('sc_vote_check_anonymous' , 'Vote_check_anonymous');
//------------------------------------------------------//

//------------投票完了ページ----------------//
function Vote_after_anonymous(){
        require "Shortcode/vote_after_anonymous.php";
}
add_shortcode('sc_vote_after_anonymous' , 'Vote_after_anonymous');
//------------------------------------------------------//


//------------ログアウト完了リダイレクトページ----------------//
function Logout_result(){
        require "Shortcode/logout_result.php";
}
add_shortcode('sc_logout_result' , 'Logout_result');
//------------------------------------------------------//
?>