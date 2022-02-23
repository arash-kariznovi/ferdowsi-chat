<!-- Programmer: Omid MilaniFard -->
<?php
	include "sys_config.class.php";
	require_once "DateUtils.inc.php";
	require_once "SharedClass.class.php";
	require_once "UI.inc.php";
	HTMLBegin();

	$message = "";
	if(isset($_REQUEST["UserID"]))
	{
		// در این نسخه از چارچوب نرم افزاری کلمه عبور به صورت متن خام ذخیره شده است
		// برای نسخه های عملیاتی حتما از رمزنگاری مناسب استفاده شود - مثال: md5
		// می توان از ldap هم استفاده کرد
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from sadaf.AccountSpecs 
											JOIN sadaf.persons using (PersonID) 
											where UserID=? and UserPassword=?");
		$res = $mysql->ExecuteStatement(array($_REQUEST["UserID"], $_REQUEST["UserPassword"]));
		
		if($trec = $res->fetch())
		{
			session_start();
            $query = "select * from sadaf.accountspecs where UserID='".$trec["UserID"]."' ";
            $res = $mysql->Execute($query);


            $result = $res->fetch();


			$_SESSION["UserID"] = $trec["UserID"];
			$_SESSION["SystemCode"] = 0;
			$_SESSION["PersonID"] = $trec["PersonID"];
            $_SESSION["LastSeen"] = $result["lastSeen"];

            $_SESSION["UserName"] = $trec["pfname"]." ".$trec["plname"];
			$_SESSION["LIPAddress"] = ip2long(SharedClass::getRealIpAddr());
			if($_SESSION["LIPAddress"]=="") {
                $_SESSION["LIPAddress"] = 0;
            }
			SharedClass::loadAndPerformTheme($mysql, $_SESSION["PersonID"]);
			echo "<script>document.location='main.php';</script>";
			die();
		}
		else
			$message = "نام کاربر یا کلمه عبور نادرست است";
	}
?>

<body >
<form method=post>

<div class="container-fluid">
<? if($message!="") { ?>
<div class="row">
	<div class="col-1" ></div>
	<div class="col-10" >
		<div class="alert alert-danger well" role="alert"><?php echo $message; ?></div>
	</div>
	<div class="col-1" ></div>
	</div>
</div>
<? } ?>
<div class="row">
<div class="col-3" ></div>
<div class="col-6" >
	<br>
	<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
		 2 شبكه اجتماعي دانشگاه فردوسي مشهد - نسخه آزمايشي
		</div>
	</div>
	<div class="portlet-body">
			<table class="table">
				<tr>
					<td>نام کاربری</td>			
				<td><input type=text name=UserID id=UserID class="form-control"></td>
				</tr>
				<tr>
					<td>کلمه رمز</td>			
					<td><input type=password id=UserPassword name=UserPassword class="form-control"></td>
				</tr>
				<tr>
					<td colspan=2 align=center>
					<button type="submit" class="btn btn-primary active">ورود</button>
					</td>
				</tr>
			</table>
	</div>

</div>
<div class="col-3" ></div>
</div>

</form>
</div>
</body>
