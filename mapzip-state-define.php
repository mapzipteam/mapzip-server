<?php

// state define -1~  
define("NON_KNOWN_ERROR",-1); // 알수없는 에러

// state define 100~ : about Database 
define("DB_CONNECTION_ERROR",101); // db connection error
define("SQL_QUERY_ERROR",103); // sql query fail 

// state define 200~ : about Login/Join
define("LOGIN_SUCCESS",200); // 로그인 성공
define("LOGIN_FAIL",201); // 로그인 실패
define("JOIN_SUCCESS",210); // 회원가입 성공
define("JOIN_FAIL_ALREADY_ERROR",211); // 이미 가입한 이력이 있음
define("JOIN_FAIL_INSERT_ERROR",212); // 회원가입자료가 db에 삽입이 안됨 

// state define 500~ : about map_search
define("MAP_SEARCH_SUCCESS",501);
define("MAP_SEARCH_NO_MORE",502);
define("MAP_SEARCH_FAIL",503);

// state define 600~ : about client_data
define("CLIENT_REVIEW_DATA_ENROLL_SUCCESS",601); // 리뷰 등록 택스트 데이터 등록 성공
define("CLIENT_REVIEW_IMAGE_MKDIR_SUCCESS",602); // 리뷰 등록 이미지 데이터 디렉토리 생성 성공
define("CLIENT_REVIEW_IMAGE_ENROLL_SUCCESS",603); // 리뷰 등록 이미지 데이터 등록 성공

define("CLIENT_REVIEW_DATA_ENROLL_FAIL",611); // 리뷰 텍스트 데이터 등록 insert 오류
define("CLIENT_REVIEW_DATA_ENROLL_EXIST",612); // 리뷰 텍스트 데이터 등록 중복데이터 오류
define("CLIENT_REVIEW_IMAGE_MKDIR_EXIST",621); // 리뷰 이미지 데이터 디렉토리이름 중복발생
define("CLIENT_REVIEW_IMAGE_MKDIR_FAIL",622);  // 리뷰 이미지 데이터 생성 실패
?>
