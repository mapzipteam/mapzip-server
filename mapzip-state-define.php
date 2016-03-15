<?php

// state define -1~  
define("NON_KNOWN_ERROR",-1); 			// 알수없는 에러

// state define 100~ : about Database 
define("DB_CONNECTION_ERROR",101); 		// db connection error
define("SQL_QUERY_ERROR",103); 			// sql query fail 

// state define 200~ : about Login/Join / Leave
define("LOGIN_SUCCESS",200); 			// 로그인 성공
define("LOGIN_FAIL",201); 				// 로그인 실패
define("JOIN_SUCCESS",210); 			// 회원가입 성공
define("JOIN_FAIL_ALREADY_ERROR",211); 	// 이미 가입한 이력이 있음
define("JOIN_FAIL_INSERT_ERROR",212); 	// 회원가입자료가 db에 삽입이 안됨 
define("LEAVE_ALL_SUCCESS",221); 		// 회원 탈퇴 모든 단계를 빠짐없이 성공
define("LEAVE_ERROR_IGNORE",222); 		// 회원 탈퇴 수행중 무시할 수 있는 오류가 발생(로그확인부탁)
define("LEAVE_FAIL_SERIOUS",223); 		// 회원 탈퇴 중 치명적 오류 발생

// state define 500~ : about map_search / maps_setting
define("MAP_SEARCH_SUCCESS",501); 		// 다른사용자의 지도의 해쉬테그 조회성공
define("MAP_SEARCH_NO_MORE",502); 		// 다른사용자의 지도의 해쉬태그 조회결과 더 없음
define("MAP_SEARCH_FAIL",503); 			// 다른사용자의 지도의 해쉬태그 조회실패

define("MAP_SETTING_SUCCESS", 511); 	// 사용자의 지도가 새로운 내용으로 셋팅 완료
define("MAP_SETTING_FAIL", 512); 		// 사용자의 지도가 새로운 내용으로 셋팅 실패

// state define 600~ : about client_data
define("CLIENT_REVIEW_DATA_ENROLL_SUCCESS",601); 	// 리뷰 등록 택스트 데이터 등록 성공
define("CLIENT_REVIEW_IMAGE_MKDIR_SUCCESS",602); 	// 리뷰 등록 이미지 데이터 디렉토리 생성 성공
define("CLIENT_REVIEW_IMAGE_ENROLL_SUCCESS",603); 	// 리뷰 등록 이미지 데이터 등록 성공
define("CLIENT_REVIEW_DATA_DELETE_SUCCESS",604); 	// 리뷰 삭제 텍스트 데이터 삭제 성공
define("CLIENT_REVIEW_IMAGE_RMDIR_SUCCESS",605); 	// 리뷰 삭제 이미지 데이터 및 디렉토리 삭제 성공
define("CLIENT_REVIEW_IMAGE_RMDIR_NONE",606);  		// 리뷰 삭제 이미지 디렉토리가 애초에 없음(이미지없음)
define("CLIENT_REVIEW_DATA_UPDATE_SUCCESS",607); 	// 리뷰 갱신 텍스트 데이터 갱신 성공

define("CLIENT_MAP_ONE_CLEAR_SUCCESS", 608); 		// 사용자의 지도 하나를 초기화 성공

define("CLIENT_REVIEW_DATA_ENROLL_FAIL",611); 		// 리뷰 텍스트 데이터 등록 insert 오류
define("CLIENT_REVIEW_DATA_ENROLL_EXIST",612); 		// 리뷰 텍스트 데이터 등록 중복데이터 오류
define("CLIENT_REVIEW_IMAGE_MKDIR_EXIST",621); 		// 리뷰 이미지 데이터 디렉토리이름 중복발생
define("CLIENT_REVIEW_IMAGE_MKDIR_FAIL",622);  		// 리뷰 이미지 데이터 생성 실패
define("CLIENT_REVIEW_DATA_DELETE_FAIL",631);  		// 리뷰 텍스트 데이터 삭제 오류
define("CLIENT_REVIEW_IMAGE_RMDIR_FAIL",632);  		// 리뷰 이미지 데이터 및 디렉토리 삭제 실패
define("CLIENT_REVIEW_DATA_UPDATE_FAIL",641);  		// 리뷰 텍스트 데이터 갱신 오류

define("CLIENT_MAP_ONE_CLEAR_FAIL", 651); 			// 사용자의 지도 하나를 초기화 실패


// state define 700~ : about client_data/map_meta
define("CLIENT_REVIEW_META_DOWN_SUCCESS",701); 		// 가게 위경도, 가게 이름 정보 전달 성공, 로그인 성공 까지
define("CLIENT_REVIEW_META_DOWN_EMPTY",711); 		// 가게 위경도, 가게 이름이 해당 지도에 없음

define("CLIENT_REVIEW_DETAIL_DOWN_SUCCESS",702); 	// map_detail 성공 

// state define 800~ : about friend_home.php
define("FRIEND_HOME_SUCCESS",801); 			// 친구 홈 정보 받기 성공

// state define 900~ : about friend_show.php , friend_enroll.php, 
define("FRIEND_ITEM_SHOW_SUCCESS", 901); 	// success to show user's friend list
define("FRIEND_ITEM_SHOW_EMPTY",902); 		// if user's friend list is empty..

define("FRIEND_ITEM_DELETE_SUCCESS", 911); 	// 사용자의 맵갈피 정보 하나를 제거 성공
define("FRIEND_ITEM_DELETE_FAIL", 912); 	// 사용자의 맵갈피 정보 하나를 제거 실패

define("FRIEND_ITEM_ENROLL_SUCCESS", 921); 	// 사용자의 맵갈피 정보 하나 등록 성공
define("FRIEND_ITEM_ENROLL_FAIL", 922); 	// 사용자의 맵갈피 정보 하나 등록 실패

// state define 1000~ : about interact with users
define("USER_SOUND_INSERT_SUCCESS",1001); // success to insert in user sound to mz_user_sound table
define("USER_SOUND_INSERT_FAIL",1002); // fail it

//state define 1100~ : about mz_user_gcm
define("USER_GCM_UPDATE_FAIL",1101); // 로그인할때, 유저 GCM테이블에 업데이트가 실패
?>
