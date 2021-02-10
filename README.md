# Webtoon Viewer

각종 웹툰 공유사이트를 한곳에 모아서 볼 수 있는 프로그램입니다.
<br><br>

* 설치방법<br>
1) GIT 에서 바로 설치 : git clone https://github.com/enaskr/webtoon /웹서버docbase(예: /var/www/html)/webtoon<br><br>

2) Release에서 압축파일을 받아서 설치<br><br>
Webserver의 docbase 하위에 압축해제해서 넣는다<br><br>

* 기본 설정 방법<br>
lib 폴더의 권한을 777로 변경해주시면 됩니다.<br>
그리고, http://YOUR-SERVER/webtoon/install.php 에 접속해서 기본 설정을 한 후 이용해주세요.<br><br>
chmod 777 [Webserver docbase]/[압축해제폴더명]/lib/<br>
chown -R www-data:www-data [Webserver docbase]/[압축해제폴더명]  (웹서버의 User가 www-data 인 경우)<br>

* 위의 권한변경을 하지 않을 경우 초기 설정이 제대로 처리되지 않을 수 있습니다.<br>
* 위의 방법보다는 아래의 방법을 권장합니다.<br>
* 웹서버의 user명을 모르는 경우에는 위의 방법으로 설치를 하고, ssh로 접속해보면 User명을 알 수 있습니다.<br><br>

* 참고로 제가 테스트한 환경은 Oracle Cloud + Ubuntu 20 + apache2 + php 7.4 환경입니다.<br>
apt install apache2 php libapache2-mod-php7.4 php7.4-mbstring php7.4-gd php7.4-curl php7.4-xml apache2-dev vnstat php7.4-fpm php7.4-soap php7.4-gmp php7.4-json php7.4-common php7.4-zip php7.4-sqlite3 php7.4-bcmath php7.4-xmlrpc php7.4-bz2 zip unzip net-tools vsftpd python3-pip -y <br>
a2enmod rewrite <br>
a2enmod headers <br>
a2enmod ssl <br>
a2dismod -f autoindex <br>
a2enmod proxy_fcgi setenvif <br>
a2enconf php7.4-fpm <br>
systemctl reload apache2 <br>

* 위와 같이 설정한 시스템에서 정상작동중입니다.<br>

* 간혹 최종 웹툰 페이지의 이미지를 못불러오는 경우를 대비해서 해당 페이지 상단에 이미지 로고를 클릭하면 해당 사이트의 웹툰 상세화면으로 바로 이동해서 볼 수 있습니다.<br>

** 이 소스는 누구든지 퍼가셔서 수정하실 수 있습니다.<br><br>
** 더 좋은 프로그램이 되었다면 제게 살짝~ 알려주시면 감사하겠습니다.<br><br>


