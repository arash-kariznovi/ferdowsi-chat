<!doctype html>
<!--- programmer: Omid MilaniFard --->
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/lab.css" type="text/css">
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<title></title>
</head>

<?php
include "header.inc.php";
include "PAS_shared_utils.php";
$mysql = pdodb::getInstance();
?>
<style>
    .menu-mobile {
        display: none;
    }
    @media only screen and (min-width: 768px) {
        .menu-date, .menu-name, .manu-items {
            display: block;
        }
        .menu-mobile {
            display: none;
        }
    }
    @media only screen and (max-width: 150px) {
        .menu-date, .menu-name, .manu-items {
            display: none;
        }
        .menu-mobile {
            display: block;
        }
    }
</style>

<body onload="loadMenueColor(); loadStatusColor();
window.addEventListener('storage', () => {
  // When local storage changes, dump the list to
  // the console.
  loadMenueColor();
  loadStatusColor();
});">
	<div class="menu-container">
		<div class="container-fluid">

			<div class="row text-white sfont menu-date">
				<div class="col-md-12">
					<?php echo PASUtils::GetCurrentDateShamsiName(); ?>
					<i class="fa fa-calendar mt-3 mr-1"></i>
				</div>

				<!-- exit button and show button on the side bar -->
				<div class="menu-showBtn position-absolute mt-3 mr-2" id="menu-showBtn" onclick="showMenu()" style="cursor:pointer">
					<i class="fa fa-list text-black p-2"></i>
				</div>
				<div class="menu-exitBtn position-absolute mt-2 mr-2 d-none" id="menu-exitBtn" onclick="exitMenu()" style="cursor:pointer">
					<i class="fa fa-close p-2" style="font-size:18px"></i>
				</div>
			</div>
			<div class="row text-white sfont menu-name mt-3">
				<div class="col-md-3">
					<b><?php echo $_SESSION["UserName"] ?></b> <i class="fa fa-user mr-1"></i>
				</div>
				<!-- status icon added -->
				<div class="menu-statusIcon position-absolute mr-2 d-none" id="menu-statusIcon">
					<i class="fa fa-circle p-2" style="font-size:12px;"></i>
				</div>
			</div>
			<div class="row text-white sfont menu-name mt-3">
				<div class="col-md-3">
					<b style="font-size:14px;">آخرین عملیات :  <?php echo $_SESSION["LastSeen"] ?></b> <i class="fa fa-clock-o mr-1"></i>
				</div>
			</div>
			<div class="row text-white sfont manu-items" dir="rtl">
				<div class="col-md-12">
					<a href='#' data-toggle="tooltip" title="تغییر رمز عبور" onclick='javascript: parent.document.getElementById("MainContent").src="ChangePassword.php"'>
						<i class="fa fa-key text-white p-2 mt-3"></i>
					</a>
					<!-- <a href='#' data-toggle="tooltip" title="کارهایی که انجام دادم" onclick='javascript: parent.document.getElementById("MainContent").src="MyActions.php"'> -->
					<div class=" ">
						<a class="card-link" onclick="cardBtn(1)" data-toggle="collapse" href="#Section1" title="قابلیت های سیستم">
							<div style="background-color: transparent;">
								<i class="fa fa-list  text-white p-2 mt-1"></i>
							</div>
						</a>
						<div class="collapse " data-parent="#accordion" id="Section1">
							<div class="card-body">
								<div class="">
									<div class="col align-center">
										<div class="panel-group" id="accordion" align="center">
											<?php
											$gres = $mysql->Execute("select * from sadaf.SystemFacilityGroups order by OrderNo");
											while ($grec = $gres->fetch()) {
											?>
												<div class="panel-heading  mb-1">
													<button type="button" class="MenuColor" data-toggle="collapse" data-parent="#accordion" href="#collapse<? echo $grec["GroupID"]  ?>">
														<?php echo $grec["GroupName"]; ?>
														<span class="caret"></span>
													</button>
												</div>
												<div id="collapse<? echo $grec["GroupID"]  ?>" class="panel-collapse collapse in mb-1">
													<div class="panel-body">
														<div class="list-group">
															<?php
															$res = $mysql->Execute("select * from sadaf.SystemFacilities where GroupID=".$grec["GroupID"]." order by OrderNo");

															while ($rec = $res->fetch()) {
																echo "<button type=\"button\" ";
																echo " class=\"list-group-item list-group-item-action\" onclick='javascript: parent.document.getElementById(\"MainContent\").src=\"" . $rec["PageAddress"] . "\"'>";
																echo $rec["FacilityName"] . "</button>";
															}
															?>
														</div>
													</div>
												</div>

											<?php } ?>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div style="width:100%">
					<a href='#' onclick='javascript: parent.document.getElementById("MainContent").src="Setting.php"' data-toggle="tooltip" title="تنظیمات" style="margin-right: 15px;">
						<i class="fa fa-cog text-white p-2 mt-1"></i>
					</a>
				</div>

				<div style="width:100%">
					<a href='javascript: parent.document.location="SignOut.php?logout=1"' data-toggle="tooltip" title="خروج" style="margin-right: 13px;">
						<i class="fa fa-sign-out text-white p-2 mt-1"></i>
					</a>
				</div>

			</div>

            <!-- <div class="row text-white sfont menu-mobile">
                <div class="col-md-12">
                    <i class="fa fa-list text-white p-2 menu-show mt-1"></i>

                </div>
            </div> -->
		</div>


	</div>													

	<script>
        $(document).ready(function (){
            $('.menu-show').on('click', function (){
                // document.getElementsByClassName('menu-container').style.width= '200px';
                $('.menu-container').css('width', '200px');
            })
        })
		function ColapseAll() {
			<?
			$gres = $mysql->Execute("select * from sadaf.SystemFacilityGroups order by OrderNo");
			while ($grec = $gres->fetch()) {
				echo "document.getElementById('tr_" . $grec["GroupID"] . "').style.display = 'none';\r\n";
			}
			?>
		}

		function ExpandOrColapse(tr_id) {
			ColapseAll();
			if (document.getElementById(tr_id).style.display == '')
				document.getElementById(tr_id).style.display = 'none';
			else
				document.getElementById(tr_id).style.display = '';
		}

		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});
		
		function showMenu(){
			parent.document.getElementById("menu-frame").style.transform = 'translateX(1%)';
			document.getElementById("menu-showBtn").classList.add('d-none');
			document.getElementById("menu-exitBtn").classList.add('d-block');
			document.getElementById("menu-statusIcon").classList.add('d-block');
			

			document.getElementById("menu-showBtn").classList.remove('d-block');
			document.getElementById("menu-exitBtn").classList.remove('d-none');
			document.getElementById("menu-statusIcon").classList.remove('d-none');
			loadStatusColor();
			loadMenueColor();
			// console.log(parent.window.innerWidth)
			if (parent.window.innerWidth > 980) {
				parent.document.getElementById("MainContent").style.transition = 'width 0.5s ease-in';
				parent.document.getElementById("MainContent").style.width = 'calc(100% - 276px)';	
			}
        }	

		function exitMenu() {
			parent.document.getElementById("menu-frame").style.transform = 'translateX(85%)';
			document.getElementById("menu-exitBtn").classList.add('d-none');
			document.getElementById("menu-statusIcon").classList.add('d-none');
			document.getElementById("menu-showBtn").classList.add('d-block');

			document.getElementById("menu-exitBtn").classList.remove('d-block');
			document.getElementById("menu-statusIcon").classList.remove('d-block');
			document.getElementById("menu-showBtn").classList.remove('d-none');
			parent.document.getElementById("MainContent").style.transition = 'width 0.35s ease-in';
			parent.document.getElementById("MainContent").style.width = '98%';

			loadMenueColor();
		}

		function loadMenueColor(){
			let color = localStorage.getItem("color2");
			document.body.style.backgroundColor = color;
		}

		function loadStatusColor() {
			let status = localStorage.getItem("status");
			console.log(status)
			if (status === "online") {
        		document.getElementById("menu-statusIcon").style.color = 'rgb(28, 240, 28)';    
			}
			else if (status === "busy") {
				document.getElementById("menu-statusIcon").style.color = 'rgb(240, 28, 39)';    
			}
			else {
				document.getElementById("menu-statusIcon").style.color = 'rgba(232, 255, 28, 0.993)';    
			}
		}

	</script>
</body>

</html>