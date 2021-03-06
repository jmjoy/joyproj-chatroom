<!DOCTYPE html>
<html lang="zh-cn">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>聊天室 - JoyProj</title>

	<!-- Bootstrap -->
	<link href="/public/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		body {
			background: url("<?=base_url('public/img/room-bg.jpg')?>") no-repeat;
			background-size: 100%;
		}
		#msg_input { 
			width: 70%; 
		}
		#msg_panel, #user_panel, #user_list {
			height: 0;
		}
		#msg_panel, #user_list {
			overflow-x: hidden;
			overflow-y: scroll;
		}
		#user_table_td {
			padding-left: 5%;
		}
	</style>
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="/public/jquery/jquery-1.11.2.min.js"></script>
	<script src="/public/jquery/jquery.scrollTo-min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="/public/bootstrap/js/bootstrap.min.js"></script>
	
	<script type="text/javascript">
		var getUrl = "<?=site_url('room/getMsg/' . $cate_id)?>"
		var sendUrl = "<?=site_url('room/sendMsg/' . $cate_id)?>"
		var onlineUserUrl = "<?=site_url('room/getOnlineUser/' . $cate_id)?>"
	
		window.onload = function() {
			var bodyHeight = window.screen.availHeight
			var height = bodyHeight - 220
			$("#msg_panel").css("height", height)
			$("#user_panel").css("height", height)
			$("#user_list").css("height", height - 45)

			getMsg(<?=$timestamp?>)

			getOnlineUser()
			setInterval(getOnlineUser, 10000)
		};

		function send() {
			var msg = $("#msg_input").val()
			if (msg == "") {
				return
			}
			if (msg.length > 250) {
				alert("消息长度过长！（不能超过250个字符哦～）")
				return
			}
			$("#msg_input").val("")
			$.post(sendUrl, {"content": msg})
		}

		function onEnter(e) {
		    if(e.charCode == 13 || e.keyCode == 13) {
		        $('#msg_btn').click()
		        e.preventDefault()
			}
		}

		function getMsg(timestamp) {
			$.post(getUrl, {"timestamp": timestamp}, function(data) {
				display(data.msgs)
				getMsg(data.timestamp)
			}, "json")
		}

		function display(msgs) {
			for (var i in msgs) {
				var msg = msgs[i]
				var str = "<h5><strong>"+ msg.username +"</strong>&nbsp;&nbsp;&nbsp;<small>" + msg.ctime + "</small></h5><p>" + msg.content + "</p>"
			    $('#msg_list').append(str);
			}
		    $('#msg_panel').scrollTo('max')
		}

		function getOnlineUser() {
			$.post(onlineUserUrl, function(data) {
				$("#online_count").html(data.count)
				var str = ""
				for (var i in data.users) {
					str += '<tr><td id="user_table_td"> <span class="glyphicon glyphicon-user"></span> ' + data.users[i].username + '</td></tr>'
				}
				$("#online_user_table").html(str)
			}, 'json')
		}
		
	</script>
	</head>
	
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="javascript:void(0)">当前频道：<small><?=$cate_name?></small></a>
				</div>

				<div class="nav navbar-nav navbar-right">
					<a class="btn btn-default navbar-btn" href="<?=site_url('welcome/index')?>">退出聊天室</a>
				</div>
				
				<div class="nav navbar-nav navbar-right">
					<a class="navbar-brand" href="javascript:void(0)"><small>身份： <?=$username?></small></a>
				</div>
			</div>
		</nav>
		<br /><br /><br />
		<section class="container">
			<div class="row">
				<div class="col-md-9">
					<div id="msg_panel" class="panel panel-default">
				  		<div id="msg_list" class="panel-body">
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div id="user_panel" class="panel panel-default">
						<div class="panel-heading">
							当前在线会员：
							<span id="online_count" class="badge">0</span>
						</div>
						<div id="user_list">
							<table id="online_user_table" class="table table-condensed">
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
		<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
		<div class="container">
			<div class="navbar-form" role="search">
				<input id="msg_input" type="text" class="form-control disabled" autofocus onkeypress="onEnter(event)" <?=isset($_SESSION['user']['id'])?'':'disabled'?> >
				<button id="msg_btn" type="button" class="btn btn-default <?=isset($_SESSION['user']['id'])?'':'disabled'?>" onclick="send()" >发送</button>
			</div>
		</div>
		</nav>
	</body>
</html>
