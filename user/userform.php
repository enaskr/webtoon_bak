<?php
	include('../lib/config.php');
	include($homepath.'lib/header.php');
	$rand_num = sprintf('%06d',rand(000000,999999));
	$mbr_no = $thisDate.$rand_num;
?>
<script type="text/javascript">
	var canJoin = false;
	function chkUserID(frm) {
		if ( frm.userid.value == "" ) {
			alert("아이디를 입력해주세요.");
			return false;
		}
		$.ajax({
			url:"./userIdCheck.php?userid="+frm.userid.value,
			type:"GET",
			success: function(result) {
				if (result == "Y") {
					alert("이미 사용중인 아이디입니다.");
				} else {
					alert("사용가능한 아이디입니다.");
					canJoin = true;
				}
			}
		});
	}
	function chkForm(frm) {
		if (canJoin) {
			if ( frm.userid.value == "" ) {
				alert("아이디를 입력해주세요.");
				return false;
			}
			if ( frm.userid.value.length < 4 ) {
				alert("아이디는 4글자 이상 입력해야합니다.");
				return false;
			}
			if ( frm.userpassword.value == "" ) {
				alert("비밀번호를 입력해주세요.");
				return false;
			}
			if ( frm.userpassword.value.length < 4 ) {
				alert("비밀번호는 4글자 이상 입력해야합니다.");
				return false;
			}
			if ( frm.username.value == "" ) {
				alert("성명을 입력해주세요.");
				return false;
			}
			if ( frm.useremail.value == "" && frm.userphone.value == "" ) {
				alert("이메일과 전화번호 중 1개는 반드시 입력해야합니다.");
				return false;
			}
			frm.submit();
		} else {
			alert("아이디 중복을 확인해주세요.");
			return false;
		}
	}

</script>
<div id='container'>
	<div class='item'>
		<dl>
			<dt>회원 등록</dt>
			<dd>
				<div class='group' style='padding:0px;'>
					<table style="line-height:1.5;border-color:#ffffff;" border=1 width="100%" cellspacing=0 cellpadding=0>
						<form name="userForm" method="post" action="<?= $homeurl ?>user/userjoin.php" id="userForm" onSubmit="chkForm(this);return false;">
						<tr style='background-color:#f8f8f8' height='50' align='center'><td width='30%'>회원번호</td><td><input style='border:none; line-height:48px; width:100%;text-align:center;' type='text' name='mbr_no' value="<?php echo $mbr_no; ?>" readOnly></td></tr>
						<tr style='background-color:#f8f8f8' height='50' align='center'><td>아이디<font color="red">*</font></td><td><input style='border:none; line-height:48px; width:70%;' type='text' name='userid' onChange='canJoin=false;'><input style='border:none; line-height:48px; width:30%;' type='button' value='중복확인' onClick='chkUserID(this.form);'></td></tr>
						<tr style='background-color:#f8f8f8' height='50' align='center'><td>비밀번호<font color="red">*</font></td><td><input style='border:none; line-height:48px; width:100%;' type='password' name='userpassword'></td></tr>
						<tr style='background-color:#f8f8f8' height='50' align='center'><td>성명<font color="red">*</font></td><td><input style='border:none; line-height:48px; width:100%;' type='text'name='username'></td></tr>
						<tr style='background-color:#f8f8f8' height='50' align='center'><td>이메일<font color="red">*</font></td><td><input style='border:none; line-height:48px; width:100%;' type='text' name='useremail'></td></tr>
						<tr style='background-color:#f8f8f8' height='50' align='center'><td>전화번호</td><td><input style='border:none; line-height:48px; width:100%;' type='text' name='userphone'></td></tr>
						<tr style='background-color:#f8f8f8' height='50' align='center'><td>회원등급</td><td><input style='border:none; line-height:48px; width:100%;' type='text' name='userlevel' value='1'></td></tr>
						<tr style='background-color:#f8f8f8' height='50' align='center'><td>상태</td><td>
						<select name='userstatus' style='border:none;background-color:#f8f8f8;width:100%;'>
							<option value='WAIT' selected>승인대기</option>
							<option value='REJECT' ">승인거절</option>
							<option value='OK'">정상</option>
							<option value='APPROVED'>승인완료</option>
						</select></td></tr>
						<tr style='background-color:#f8f8f8' height='50'><td colspan="2" align="center" valign="middle"><input style="border:none;line-height:48px;width:100%;font-weight:900;color:#0000ff;" type="submit" value="회원 등록"></td></tr>
					</table>
				</div>
			</dd>
		</dl>
	</div>
</body>
</html>
