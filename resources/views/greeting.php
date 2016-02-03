<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>kappaIO Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
 
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" media="screen" rel="stylesheet" type="text/css">
<link href="/css/style-user.css" media="screen" rel="stylesheet" type="text/css">
<!--<link href="/img/logo.png" rel="shortcut icon" type="image/vnd.microsoft.icon">-->
 
<script type="text/javascript" src="/js/xhr.js"></script>
<!--<script type="text/javascript" data-rocketsrc="/js/widget.js"></script>-->
<!--[if lt IE 9]><script type="text/javascript" src="/js/html5shiv.js"></script><![endif]-->
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.3.3/underscore-min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/0.9.2/backbone-min.js" type="text/javascript"></script>
</head>
<body>
<header class="navbar navbar-static-top">
    <div class="container">
        <a class="logo" href="/">kappaIO.local</a>
<!--
        <ul class="nav nav-pills pull-right">
            <li><a role="button" class="sign-out-button" href="auth/logout">Log out</a>
        </li>
        </ul>
-->
    </div>
</header>

<script type="text/javascript"> 
server = true;
XHR.get('/widget', {}, function(x,pkg) {
    console.log("hello");
    console.log(pkg);
//    console.log(pkg);
	var codeString = pkg.data.replace(/<@=ROOT_URL@>/g, '<?php echo $REQUEST_URI; ?>').replace(/<@=MAIN_CONTAINER_ID@>/g, "HAN-Dashboard");
	$($.parseHTML( codeString , document, true )).appendTo("#HAN-Dashboard");
});
</script>
<div class="container">
 
<fieldset class="cbi-section">
<legend>
Home Area Network <span id="status-light" style="margin: 0px; width: 1px; height: 8px; display: inline-block;" class="label success"></span>
</legend>
<div id="HAN-Dashboard"></div>
</fieldset>
</div>
</body>
</html>
