<?php

// mysql
define("MYSQL_IP","localhost");
define("MAIN_DB","ljs93kr");
define("DB_PASSWORD","wnddnjsQkd");
define("USE_DB","ljs93kr");

// mysql - mz_user_info
define("USER_TABLE","mz_user_info");

// mysql - mz_user_gcm
define("GCM_TABLE","mz_user_gcm");

// mysql, web, admin - mz_update_table
define("UPDATE_TABLE","mz_update_table");

// mysql, web, user_sound - mz_user_sound
define("USER_SOUND_TABLE","mz_user_sound");

// mysql - mz_client_map
define("CLIENT_TABLE","mz_client_map");
define("CLIENT_TYPE_MAPMETA",1); // 지도 타입
define("CLIENT_TYPE_FRIEND",2); // 본인이 추가한 친구 타입
define("CLIENT_TYPE_FRIEND_ADDED",3); // 본인을 추가한 친구 타입

// mysql - mz_friend_table
define("FRIEND_TABLE","mz_friend_table"); // mapzip 유저간 맵갈피관계
define("FRIEND_TYPE_USER_LEAVE", 1); // 맵갈피 테이블에서 유저가 회원탈퇴할때 동작
define("FRIEND_TYPE_DELETE_ONLYTOID", 2); // 맵갈피 테이블에서 유저가 맵갈피 해제할때 동작

// mysql - mz_review_data
define("REVIEW_TABLE","mz_review_data");
define("REVIEW_TYPE_USER_LEAVE",1); // 해당 이용자의 모든 리뷰정보를 삭제할 때 동작
define("REVIEW_TYPE_DELETE_ONEMAP",2); // 이용자의 지도 하나의 리뷰정보만 삭제할 때 동작
define("REVIEW_TYPE_DELETE_ONEREVIEW",3); // 이용자의 하나의 리뷰정보만 삭제할 때 동작

?>