<?php
/*
		CREATE TABLE SERVER_CONFIG 
			-- 서버 설정
		(
			CONF_NAME TEXT,		-- 설정명
			CONF_VALUE TEXT,	-- 설정값
			CONF_ADD1 TEXT,		-- 추가설정값1
			CONF_ADD2 TEXT,		-- 추가설정값2
			USE_YN TEXT,		-- 사용여부(Y/N)
			REGDTIME TEXT,		-- 등록일시
			UPTDTIME TEXT		-- 수정일시
		);
		INSERT INTO SERVER_CONFIG (CONF_NAME, CONF_VALUE, USE_YN, REGDTIME, UPTDTIME) VALUES('view_adult','Y', 'Y', '2020.11.09 00:00:00', '2020.11.09 00:00:00');
		INSERT INTO SERVER_CONFIG (CONF_NAME, CONF_VALUE, USE_YN, REGDTIME, UPTDTIME) VALUES('max_list','20', 'Y', '2020.11.09 00:00:00', '2020.11.09 00:00:00');
		INSERT INTO SERVER_CONFIG (CONF_NAME, CONF_VALUE, USE_YN, REGDTIME, UPTDTIME) VALUES('try_count','2', 'Y', '2020.11.09 00:00:00', '2020.11.09 00:00:00');
		INSERT INTO SERVER_CONFIG (CONF_NAME, CONF_VALUE, USE_YN, REGDTIME, UPTDTIME) VALUES('login_view','Y', 'Y', '2020.11.09 00:00:00', '2020.11.09 00:00:00');

		CREATE TABLE SITE_INFO 
		-- 사이트 정보
		(
			SITE_ID TEXT,	-- 사이트 ID
			SITE_NAME TEXT, -- 사이트명
			SITE_ALIAS TEXT, -- 사이트 별칭
			SITE_URL TEXT, -- 사이트 URL
			SITE_TYPE TEXT, -- 사이트 유형(WEBTOON/MANGA)
			SERVER_PATH TEXT, -- 서버 경로명
			USE_LEVEL TEXT, -- 사용가능 회원레벨
			SEARCH_URL TEXT, -- 검색 URL
			SEARCH_PARAM TEXT, -- 검색 파라메터
			RECENT_URL TEXT, -- 최신목록 URL
			RECENT_PARAM TEXT, -- 최신목록 파라메터
			ENDED_URL TEXT, -- 완결목록 URL
			ENDED_PARAM TEXT, -- 완결목록 파라메터
			LIST_URL TEXT, -- 작품리스트 URL
			LIST_PARAM TEXT, -- 작품리스트 파라메터
			VIEW_URL TEXT, -- 작품상세 URL
			VIEW_PARAM TEXT, -- 작품상세 파라메터
			NOTE TEXT, -- 기타정보
			MAIN_VIEW, -- 메인페이지 노출여부
			ORDER_NUM, -- 메인페이지 노출 순서
			UPDATE_YN, -- 주소 업데이트 성공여부
			UPDATE_EXECUTE, -- 주소업데이트 실행여부
			REGDTIME TEXT, -- 등록일시
			UPTDTIME TEXT, -- 수정일시
			USE_YN TEXT  -- 사용여부(Y/N)
		);

		INSERT INTO SITE_INFO VALUES('NEWTOKI','뉴토끼','NEWTOKI','https://newtoki95.com','webtoon','newtoki','1','/webtoon','bo_table=webtoon&stx={keyword}','/webtoon/p{page}','toon=%EC%9D%BC%EB%B0%98%EC%9B%B9%ED%88%B0','/webtoon/p{page}','toon=%EC%99%84%EA%B2%B0%EC%9B%B9%ED%88%B0','/bbs/board.php','bo_table=webtoon&wr_id={toonid}','/webtoon/{toondtlid}','','https://twitter.com/newtoki9','Y','10','Y','2020.11.09 00:00:00','2021.02.06 15:59:09','Y');
		INSERT INTO SITE_INFO VALUES('PROTOON','프로툰','PROTOON','https://protoon48.com','webtoon','protoon','10000','/search/main','tse_key_={keyword}','/toon/mais','typ_=normal','/toon/mais','typ_=ends&cpa_={page}','/toon/subs','gid_={toonid}&typ_={type}&cps_={page}','/toon/vies','idx_={toondtlid}&gid_={toonid}&typ_={type}','https://twitter.com/protoon_com','N',99999,'Y','2020.11.09 00:00:00','2021.02.06 15:59:11','Y');
		INSERT INTO SITE_INFO VALUES('TOONKOR','툰코','TOONKOR','https://tkor.mobi','webtoon','toonkor','1','/bbs/search.php','sfl=wr_subject||wr_content&stx={keyword}','/무료웹툰','fil=최신','/웹툰/완결','fil=최신','/{toonid}','','/{toondtlid}','','https://twitter.com/tkor_info','Y','99999','Y','2020.11.09 00:00:00','2021.02.06 15:59:03','Y');
		INSERT INTO SITE_INFO VALUES('FUNBE','펀비','FUNBE','https://funbe.fun','webtoon','funbe','10000','/bbs/search.php','sfl=wr_subject||wr_content&stx={keyword}','/무료웹툰','fil=최신','/웹툰/완결','fil=최신','/{toonid}','','/{toondtlid}','','https://jusoyo.5to.me/bbs/board.php?bo_table=webtoon&wr_id=7','N',99999,'Y',NULL,'2021.02.06 15:59:04','Y');
		INSERT INTO SITE_INFO VALUES('SPOWIKI','스포위키','SPOWIKI','https://spowiki54.com','webtoon','spowiki','100000','/bbs/board.php','bo_table=webtoon&wt_title={keyword}','/bbs/board.php','bo_table=webtoon','/bbs/board.php','bo_table=webtoon&wt_fin=1','/bbs/board.php','bo_table=webtoon&sca={toonid}','/bbs/board.php','bo_table=webtoon&wr_id={toondtlid}&sca={toonid}','','Y','99999','Y',NULL,'2021.02.06 15:59:13','Y');
		INSERT INTO SITE_INFO VALUES('COPYTOON','카피툰','COPYTOON','https://copytoon128.com','webtoon','copytoon','10000','/bbs/search_webtoon.php','stx={keyword}','/웹툰/작품','sort=latest','/웹툰/작품/완결','sort=latest','/{toonid}','','/{toondtlid}','','https://jusoshow.me','N',99999,'Y',NULL,'2021.02.06 15:59:02','Y');
		INSERT INTO SITE_INFO VALUES('TOONSARANG','툰사랑','TOONSARANG','https://toonsarang.art','webtoon','toonsarang','100000','/bbs/search_webtoon.php','stx={keyword}','/웹툰/작품','sort=latest','/웹툰/작품/완결','sort=latest','/{toonid}','','/{toondtlid}','','https://jusoshow.me/','N','20','Y',NULL,'2021.02.06 15:59:02','Y');
		INSERT INTO SITE_INFO VALUES('MANATOKI','마나토끼','MANATOKI','https://manatoki95.net','manga','manatoki','1','/comic/p1','bo_table=comic&stx={keyword}','/bbs/page.php','hid=update&page={page}','/comic/p{page}','publish=%EC%99%84%EA%B2%B0','/bbs/board.php','bo_table=comic&wr_id={toonid}','/comic/{toondtlid}','','https://twitter.com/newtoki9','Y','10','Y',NULL,'2021.02.06 15:59:09','Y');
		INSERT INTO SITE_INFO VALUES('11TOON','일일툰','11TOON','http://xn--1-4f7fa470i.com','manga','11toon','10000','/bbs/search_stx.php','stx={keyword}','/bbs/board.php','bo_table=toon_c&tablename=최신만화&type=upd&page={page}','/bbs/board.php','bo_table=toon_c&is_over=1&tablename=완결만화&page={page}','/bbs/board.php','bo_table=toons&title={toontitle}&is={toonid}&page={page}','/bbs/board.php','bo_table=toons&wr_id={toondtlid}&is={toonid}','http://11toon1.com','N',99999,'Y',NULL,'2021.02.06 15:59:05','Y');
		INSERT INTO SITE_INFO VALUES('11TOON2','일일툰백업','11TOON2','http://www.11toon11.com','manga','11toon2','10000','/bbs/search_stx.php','stx={keyword}','/bbs/board.php','bo_table=toon_c&tablename=최신만화&type=upd&page={page}','/bbs/board.php','bo_table=toon_c&is_over=1&tablename=완결만화&page={page}','/bbs/board.php','bo_table=toons&title={toontitle}&is={toonid}','/bbs/board.php','bo_table=toons&wr_id={toondtlid}&is={toonid}','http://11toon1.com','N',99999,'Y',NULL,'2020.12.05 20:00:28','Y');
		INSERT INTO SITE_INFO VALUES('MANY','마니코믹스','MANY','https://many35.com','manga','many','1','/bbs/search.php','stx={keyword}','/bbs/board.php','bo_table=comics&sop=and&sst=wr_datetime&sod=desc&type=alphabet&page={page}','/bbs/board.php','bo_table=comics&sop=and&sst=wr_datetime&sod=desc&type=alphabet&page={page}','/bbs/board.php','bo_table=comics&wr_id={toonid}','/bbs/board.php','bo_table=comics&wr_id={toondtlid}','https://twitter.com/manycomics77','N',99999,'Y',NULL,'2021.02.06 15:59:09','Y');
		INSERT INTO SITE_INFO VALUES('MANYW','마니코믹스','MANYW','https://many35.com','webtoon','manyw','1','/bbs/search.php','stx={keyword}','/bbs/board.php','bo_table=webtoon','/bbs/board.php','bo_table=webtoon_end','/bbs/board.php','bo_table=webtoon&wr_id={toonid}','/bbs/board.php','bo_table=webtoon&wr_id={toondtlid}','https://twitter.com/manycomics77','N',99999,'Y','2020.11.09 00:00:00','2021.02.06 15:59:09','Y');
		INSERT INTO SITE_INFO VALUES('MANAPANG','마나팡','MANAPANG','https://manapang6.com','manga','manapang','10000','/search/main','tse_key_={keyword}','/mana/mais','typ_=mana','/mana/mais','typ_=mana&yoi_=완결','/mana/subs','gid_={toonid}&typ_=mana&cps_={page}','/mana/vies','idx_={toondtlid}&gid_={toonid}&typ_=mana','','N',99999,'Y','2020.11.09 00:00:00','2021.02.06 15:59:16','Y');
		INSERT INTO SITE_INFO VALUES('SHARKTOON','샤크툰','SHARKTOON','https://www.sharktoon29.com','webtoon','sharktoon','10000','/bbs/search_webtoon.php','sfl=wr_subject||wr_content&stx={keyword}','/웹툰','sort=최신','/유료웹툰/완결','sort=최신','/{toonid}','','/{toondtlid}','','https://linktong1.com/bbs/board.php?bo_table=webtoon&wr_id=50','N',99999,'Y','2020.11.09 00:00:00','2021.02.06 15:59:18','Y');
		INSERT INTO SITE_INFO VALUES('WABOGOW','와보고','WABOGOW','https://wabogo.net','webtoon','wabogow','100000','/webtoon/weekly','skeyword={keyword}','/webtoon/weekly','','/webtoon/complete','','/webtoon/lists/{toonid}','','/webtoon/view/{toondtlid}','','','N',99999,'Y','2020.11.09 00:00:00','2020.12.21 19:03:17','Y');
		INSERT INTO SITE_INFO VALUES('WABOGO','와보고','WABOGO','https://wabogo.net','manga','wabogo','100000','/search','skeyword={keyword}','/comic','','/comic','publish=완결','/webtoon/lists/{toonid}','','/webtoon/view/{toondtlid}','','','N',99999,'Y','2020.11.09 00:00:00','2020.12.21 19:03:40','Y');
		INSERT INTO SITE_INFO VALUES('19ALLNET','19올넷','19ALLNET','https://www.allall43.net','manga','19allnet','1','/search','skeyword={keyword}','/cartoonpublish','','/cartooncompletion','','/cartoonpublish/weblist/{toonid}/page/{page}','','/cartoonpublish/view/{toonid}/{toondtlid}','','https://linkzip.site/board_SnzU08/658','N',99999,'Y','2020.11.09 00:00:00','2021.02.06 15:59:05','Y');
		INSERT INTO SITE_INFO VALUES('19ALLNETW','19올넷','19ALLNETW','https://www.allall43.net','webtoon','19allnetw','1','/search','skeyword={keyword}','/publish','','/completion','','/publish/weblist/{toonid}/page/{page}','','/publish/view/{toonid}/{toondtlid}','','https://linkzip.site/board_SnzU08/658','N','99999','Y','2020.11.09 00:00:00','2021.02.06 15:59:05','Y');

		INSERT INTO SITE_INFO VALUES('DOZI','도지코믹스','DOZI','https://dozi027.com','webtoon','dozi','1','/bbs/search.php','stx={keyword}','/bbs/board.php','bo_table=webtoon','/bbs/board.php','bo_table=webtoon_end','/bbs/board.php','bo_table=webtoon&wr_id={toonid}','/bbs/board.php','bo_table=webtoon&wr_id={toondtlid}','https://twitter.com/manycomics77','N',99999,'Y','2020.11.09 00:00:00','2021.02.06 15:59:09','Y','Y');


		INSERT INTO SITE_INFO VALUES('JAMICS','재믹스','JAMICS','https://jamics.work','webtoon','jamics','1','/bbs/search.php','sfl=wr_subject||wr_content&stx={keyword}','/무료웹툰','fil=최신','/웹툰/완결','fil=최신','/{toonid}','','/{toondtlid}','','https://korsite3.com','Y','99999','Y','2020.11.09 00:00:00','2021.02.06 15:59:03','Y','Y');

		INSERT INTO SITE_INFO VALUES('MARU','마루마루','MARU','https://marumaru216.com','manga','maru','1','/bbs/search.php','url=%2Fbbs%2Fsearch.php&stx={keyword}','/bbs/update','','/bbs/update','','/bbs/cmoic/{toonid}','','/bbs/cmoic/{toonid}/{toondtlid}','','https://jusoshow.me','Y','99999','Y','2021.10.09 00:00:00','2021.10.09 15:59:03','Y','Y');

		ALTER TABLE SITE_INFO ADD COLUMN UPDATE_EXECUTE;



		CREATE TABLE USER_INFO 
			-- 회원 정보
		(
			MBR_NO TEXT,		-- 회원번호
			USER_ID TEXT,		-- 회원아이디
			USER_PASSWD TEXT,	-- 비밀번호
			USER_NAME TEXT,		-- 회원성명
			EMAIL TEXT,		-- 이메일
			PHONE TEXT,		-- 전화번호
			USER_LEVEL TEXT,	-- 회원레벨
			USER_STATUS TEXT,	-- 회원상태
			LAST_LOGIN_DTIME TEXT,	-- 마지막 로그인일시
			LAST_LOGIN_IPADDRESS TEXT, -- 마지막 로그인IP
			LOGIN_FAIL_COUNT TEXT,	-- 로그인 실패횟수
			LOGIN_COUNT TEXT,	-- 로그인 횟수
			VIEW_ADULT,  -- 성인보기 가능여부(Y/N)
			USE_YN TEXT,		-- 사용여부(Y/N)
			REGDTIME TEXT,		-- 등록일시
			UPTDTIME TEXT		-- 수정일시
		);
		INSERT INTO USER_INFO(MBR_NO, USER_ID, USER_PASSWD, USER_NAME, EMAIL, PHONE, USER_LEVEL, USER_STATUS, LAST_LOGIN_DTIME, LAST_LOGIN_IPADDRESS, LOGIN_FAIL_COUNT, LOGIN_COUNT, USE_YN, REGDTIME, UPTDTIME)
		VALUES('20200101000001', 'admin', '9DE1E6935C88722C59F947F68C895BFA5B9612C83D9BA81494D9703A58D340D5', '관리자', 'admin@abc.com', '010-0000-0000', '99999', 'APPROVED', '2020.11.09 00:00:00', '1.1.1.1', '0', '0', 'Y', '2020.11.09 00:00:00', '2020.11.09 00:00:00');

		ALTER TABLE USER_INFO ADD COLUMN VIEW_ADULT;
		UPDATE USER_INFO SET VIEW_ADULT=(SELECT CONF_VALUE FROM SERVER_CONFIG WHERE CONF_NAME='view_adult');

		CREATE TABLE USER_SITE 
			-- 회원 추가 사이트
		(
			MBR_NO TEXT,	-- 회원번호
			SITE_ID TEXT	-- 사이트아이디,
			REGDTIME TEXT,	-- 등록일시
			UPTDTIME TEXT	-- 수정일시
		);


		CREATE TABLE USER_VIEW 
			-- 회원 열람 작품
		(
			MBR_NO TEXT,		-- 회원번호
			SITE_ID TEXT,		-- 사이트아이디
			TOON_ID TEXT,		-- 작품아이디
			TOON_URL TEXT,		-- 작품URL
			TOON_PARAM TEXT,	-- 작품 파라메터
			TOON_THUMBNAIL TEXT,	-- 작품 썸네일
			TOON_TITLE TEXT,	-- 작품 제목
			USE_YN TEXT,		-- 사용여부(Y/N)
			REGDTIME TEXT,		-- 등록일시
			UPTDTIME TEXT		-- 수정일시
		);


		CREATE TABLE USER_VIEW_DTL 
			-- 회원 열람 작품 상세(회차)
		(
			MBR_NO TEXT,		-- 회원번호
			SITE_ID TEXT,		-- 사이트아이디
			TOON_ID TEXT,		-- 작품아이디
			VIEW_ID TEXT,		-- 상세 아이디
			VIEW_URL TEXT,		-- 상세 URL
			VIEW_PARAM TEXT,	-- 상세 파라메터
			VIEW_TITLE TEXT,	-- 상세 제목
			USE_YN TEXT,		-- 사용여부(Y/N)
			REGDTIME TEXT,		-- 등록일시
			UPTDTIME TEXT		-- 수정일시
		);


		CREATE TABLE USER_WISH 
			-- 회원 관심 작품
		(
			MBR_NO TEXT,		-- 회원번호
			SITE_ID TEXT,		-- 사이트아이디
			TOON_ID TEXT,		-- 작품아이디
			TOON_URL TEXT,		-- 작품 URL
			TOON_PARAM TEXT,	-- 작품 파라메터
			TOON_THUMBNAIL TEXT,	-- 작품 썸네일
			USE_YN TEXT,		-- 사용여부
			REGDTIME TEXT,		-- 등록일시
			UPTDTIME TEXT		-- 수정일시
		);

		ALTER TABLE 테이블명 ADD COLUMN 컬럼명 [데이터 타입];

		ALTER TABLE SITE_INFO ADD COLUMN MAIN_VIEW;
		ALTER TABLE SITE_INFO ADD COLUMN ORDER_NUM;
		ALTER TABLE SITE_INFO ADD COLUMN UPDATE_YN;

*/
?>