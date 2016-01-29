<?php
// GCM 서버에 푸시할때, mapzip을 구분하기위해 등록되어 있는 api 키
define("API_SERVER_KEY","AIzaSyD9qJ9IMJVed2MctQz6SzlpSuTzTJo8k4Q");

// GCM 서버에 한번에 푸시하는 최대 개수를 정의
define("MULTICAST_MAX", 1000);

// GCM Message
define("ADD_FRIEND_STRING"," 님이 회원님을 맵갈피에 추가하였습니다");
define("FRIEND_NEW_REVIEW_STRING"," 님이 새로운 리뷰를 등록하였습니다");

// gcm_push_each task type
define("WHEN_ADD_FRIEND",1); // 맵갈피에 추가되었을때 작동하는 gcm task type
define("WHEN_FRIEND_NEW_REVIEW",2); // 맵갈피에 추가한 유저가 새로운 리뷰를 업데이트 했을때 작동하는 gcm task type

//GCM Title
define("GCM_TITLE","Mapzip");
?>