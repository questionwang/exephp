<!DOCTYPE html>
<html>
<head>

<?php header('Content-Type:text/html,charset=utf8;');
// ob_start();//开启输出缓冲
?>
<script type="text/javascript" src="public/jquery.min.js">
//alert("hi");
</script>
<title>php在线工具-<?php echo time(); ?></title>
<!-- 我还可以试试将上传来的文件的内容放在标签里面运行出来结果。 
 style="background-color:lightseagreen; border-radius: 5px 5px;color:orange;border-bottom: 1px solid black;font-size: 30px;line-height: 1em;"-->
</head>
<script type="text/javascript">alert("<?php echo 'wo'?>")</script>
<script type="text/javascript">
	(function() {
//		alert('ready')
	})();
	function keepcodes(){
		var codes=document.getElementById('php-r').value;
		var codeback=document.getElementById('back');
		codeback.value=codes;//这里要换成添加标签的操作才正规，标签的内容就是codes.
		// codeback.InnerHTML=codes;
	}
	function brush(){
		// window.history.back();
		// document.cookie='';

		window.location.href='http://www.wpdxs.com';
	}

	/*
		还有一种方式是在js中就设置cookie的值啊。
	*/
	function getCookie(cookieStr,name){
		// alert("cookieStr的类型为："+typeof(cookieStr))
		// alert(escape(cookieStr));
		var thevalue='';
		if (cookieStr != null) {
			var cookies 	=	cookieStr.split(";");	//这他妹的返回的都是Object类型的啊，还不是array类型的啊
			// alert(typeof(cookies))
//			var cookie=new Object();
			
			var cookiename  =   '';
			var cookievalue =   '';
			var i=0;
			for (i = 0 ; i <= cookies.length - 1; i++) {

				var cookie   =	cookies[i].split("=");
				// alert('|'+(cookie[0])+'|'+'|'+(name)+'|')
				var thename=cookie[0];
				if (thename == name ) {
					return thevalue=cookie[1];
				}
				// for(x in cookie){if (cookie[x] == name) {alert(cookie[x+1])}}
			}
		}
	}


	
	/*我打算在这里获得下面因为上传文件成功而改动的cookie的值，来判断是否弹出提示成功。*/
	// alert(document.cookie);
	// 将通过escape 编码后的cookie值反编码过来成为真正的值。
	// alert(unescape("URm48syIZQ%3D%3D"));
	var up_flag=getCookie(document.cookie,"up_ok1");//得到cookie的一个值作为上传的flag
	if (up_flag==1) {
		alert('文件上传成功')

		// 在这里将上传按钮设置成不能使用模式。
	}
</script>
<style type="text/css">
	#add-file{
		/*border: 1px solid red;*/
		background-color: green;
		height: auto;
		margin: 10px auto;
		width: 72%;
		padding-top: 1%;
		font-size: 25px;
	}
	
	#add-file span{
		color: yellow;
		display: block;
		padding-bottom: 1%;
		text-align: center;

	}
	#add-file form{
		width:60%;
		display: block;
		margin:0 20% 5% 20%;
		padding-bottom: 2%;
		padding-left: 10%;
	}
	#add-file form input:first-child{
		border-bottom: 1px solid red;
		width: 150px;
		margin-left: 16%;
	}
	#add-file form input:nth-child(2){
		border: 1px solid blue;
		width: auto;
		margin-left: 16%;

	}

	#add-codes{
		/*border: 2px solid red;*/
		margin-top: 5%;
		text-align: center;
		/*vertical-align: middle;*/
		display: block;
		width: 90%;
		margin-left: 5%;
	}
	#add-codes span{
		/*border: 2px solid green;*/
		display:block;
		font-size: 20px;
		text-align: center;
		font-size: 25px;
	}
	.end_result{
		margin: 3% 5%;
	}
	#run{
		width:30%;
		height:40px;
		margin-left:45%;
		color:green;
		display:block;
	}
	#again{
		width:20%;
		height:30px;
		margin-left:55%;
		color:red;
		display:block;
		margin-top: 10px;
	}
</style>
<!--066319--15907257336-->

<body>

<?php
	/*$onefilepath="php_exefiles/test.sql";
	$filearr=file($onefilepath);
	print_r($filearr);
	echo('fuck');
	die();*/

	//初始化要运行的文件中的代码。
	$up_file_codes=null;

	/*这里用来集中接受表单提交的数据*/
	$fileres=isset($_FILES['filetorun']) ? $_FILES['filetorun'] : null;  	//获得文件资源。

	// 改进中。。。。
	/*当没有选择文件而直接点击提交文件按钮时$fileres会是一个数组变量，但是数组里面的元素是空信息.
		这是就需要下面的条件语句中的条件了。
	*/
	// echo gettype($fileres);
	if ($fileres['size']>0 && !empty($fileres['name']))
	{

		print_r($fileres);
		$filetempname=$fileres['tmp_name'];//得到文件资源的临时名称。这个文件资源被保存在内存中了吧？(我猜的...)

		//获得文件MIME类型
		// $file_type=mime_content_type($fileres['tmp_name']);//echo $file_type;此函数现已被废弃，替换如下
		$ffres=finfo_open(FILEINFO_MIME);//资源resource类型
		$finfo_mime=(finfo_file($ffres,$filetempname));//返回由$ffres代表的资源的信息,类型是字符串类型，每个属性之间使用;（linux系统下面不知道是不是这样，可能是冒号:）隔开
		// echo $finfo_mime;die();
		//获得时间，用来给文件命名
		$time=date('YmdHis',time());

		//获得文件格式--后缀名
		$file_ext=preg_split('/\.{1}/', $fileres['name'])[1];

		//判断文件类型(这种属于白名单的形式，满足的情况不详)，判断文件大小(可以通过更改php.ini的几个值进行调节），判断错误代码（可以通过函数封装各个错误代码进行友性好提示）
		$req_mime  =  preg_match('/png|gif|jpeg|jpg|plain|octet-stream|x-php/i', $finfo_mime);//一个条
		$req_ext   =  in_array($file_ext, ['php','html','shtml','txt','png','jpg','gif','jpeg']);
		$req_size  =  $fileres['size'];//这个上是以B(字节)为单位的，后面如果需要看到文件大小的信息，单位上可以通过一个函数进行封装
		$req_error =  $fileres['error'];
		if (($req_error===0) && ($req_size < 2048*1024) && ($req_mime === 1) && ($req_ext === true)) {
			
			move_uploaded_file($filetempname,__DIR__.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$time.'.'.$file_ext);		//存储文件啊。
			
			//如果文件后缀名是php，就获得文件的内容
			if ($file_ext=='php') {

				$up_file_codes=file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$time.'.'.$file_ext);//获得文件中的代码

				//在这里我想对我保存的文件设置访问权限了
				/*
					chown
					chpri...
				
				*/
			}
			//设置变量进行标记上传成功，这个标记可以通过上面的js代码进行使用
			setcookie('up_ok1',true,time()+10);setcookie('up_ok2',true,time()+10);setcookie('up_ok3',true);
		}else{
			//操作失败就删除cookie，期望重新开始加载信息
			setcookie('up_ok1', '', time()-1);
			setcookie('up_ok2', '', time()-1);
			setcookie('up_ok3', '', time()-1);
			die('呀呀呀，诺曼底登陆失败，文件上传失败，请查找文件后缀名是否符合要求、文件大小是超过2M，');
		}
	}else{
		echo '文件尚未提交';
		// php中删除cookie的方式
		//js中删除cookie的方式 ==》http://www.cnblogs.com/xiaochaohuashengmi/archive/2010/06/13/1757658.html  注意js设置时间上的格式问题:date.toGMTString(); 
		setcookie('up_ok1', '', time()-1);
		setcookie('up_ok2', '', time()-1);
		setcookie('up_ok3', '', time()-1);
	}

	/*页面直接编辑的代码，简单地就先放在这里了。*/
	$thecodes_edit=isset($_POST['code']) ? $_POST['code'] :null;
	$thecodes_file=isset($up_file_codes) ? $up_file_codes : $thecodes_edit;//选择是使用文件里面的代码还是编辑框里面的代码片段
	$thecodes=str_replace(['<?php','?>',PHP_EOL], '', $thecodes_file);//最终不管是那种方式提交上来的代码都归结到一个变量中去，并且去掉所有的换行符号。
	
	
	// print_r(ob_end_flush());//将缓冲中的内容全部输出，并关闭输出缓冲
?>

<div>

<!--	<a href="php_exefiles/wlz.html">王雷的简历-拉钩网</a>-->
</div>
<div id="add-file"> 
	<span>运行本地php文件:<p style="font-size:15px;line-height:1px;display:block;color:black;">期望使用正常的php脚本文件</p></span>
	<!--注意事项
	1)THis is an solution to convert Cyrillic and umlaut characters as file name when uplaoding files into needed encoding. 
		Was searching for it but could not find. Thus posting this. Just like this:	$value = mb_convert_encoding($value, "UTF-8");
	2)If $_FILES is empty, even when uploading, try adding enctype="multipart/form-data" to the form tag and make sure you have file uploads turned on.  -->
	<form method="post" action="#" enctype="multipart/form-data">
		<input type='file' name="filetorun" />
		<input type='submit' value="提交文件" class="post_btn"/>
	</form>
</div>


<div id="add-codes">
	<span>在线编辑php代码:</span>
	<form method="post" action="#">
		<textarea id="php-r" name="code" placeholder="edit here.." style="width:90%;height:300px;background-color:black;color:green;font-size:24px;" ><?php if(isset($up_file_codes)){echo str_replace(['<?php','?>'],'', $up_file_codes);}?></textarea>
		<input type='submit' value='表单提交运行' id="run" onclick="keepcodes()"/>
	</form>
	<div id="ajax_get"><input style="width: 320px;height:28px;margin:10px 40%;text-align: center;vertical-align: middle;font-size: large;border: 1px solid red;" type="button" value="点击我进行ajax方式运行">
	</div>
	<input	type="button" value="重新开始" id="again" onclick="brush()" />
</div>

<!--<input type="button" value="弹出编辑区域的内容" onclick="getTextArea()" />-->
<script type="text/javascript">

	function getTextArea(nodeId){
		var editNode=document.getElementById(nodeId);
		var nodeCode=editNode.value;
//		alert(typeof (nodeCode))
		return nodeCode;
		//不会了-----原生ajax请求不会啊,设定要传递的数据形式codes=$thecodes.
		//上面一句已经是历史了
	}

	var ajax_=document.getElementById('ajax_get');
	ajax_.onclick= function () {
		var xhr=new XMLHttpRequest();

		var nodeID='php-r';
		xhr.open("GET","http://www.wpdxs.com/php_exefiles/tophp_ajax_do.php?codes="+getTextArea(nodeID)+"&t="+Math.random(),true);
		xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xhr.send();
		xhr.onreadystatechange=function() {
			if (xhr.readyState === 1) {
//				alert(xhr.status);//闹着玩啊你
			}
			else if (xhr.readyState === 2) {
//				alert(xhr.status);//闹着玩啊你你
			}
			else if (xhr.readyState === 3) {
//				alert(xhr.status);//闹着玩啊你你你
			} else if (xhr.readyState === 4) {
				if (xhr.status === 200) {
					alert(typeof(xhr.responseText));
				document.getElementById('result').innerHTML=xhr.responseText;
				} else {
					alert('发生错误:' + xhr.status);
				}
			}
		}
	}

</script>
<div class="end_result">

	<textarea id="back"
		style="width:30%;height:150px;background-color:black;color:green;font-size:14px;display:inline;"></textarea>
	<textarea id="result" 
		style="width:60%;height:150px;background-color:white;color:green;font-size:25px;display:inline;"><?php




//		这个地方要进行判断了，对于命令行代码和文件代码是有不同的参数的，但是这样对于文件形式的执行，文件路径又是一个问题。目前这样做就是为了练习php对文件的处理能力。




			if (isset($thecodes)) {
				// 这里使用的命令是系统命令，所以要在系统环境path中添加运行程序的位置 
				exec('php -r '.'"'.$thecodes.'"',$result);//关于这个单双引号真是他m的dt,,高了好长时间才调试好。
				if (!empty($result)) {
					print_r($result);//代码中也会带有输出函数的啊
				}

			}
		?></textarea>
</div>

</body>
</html>
