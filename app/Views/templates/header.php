<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Bootstrap -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous"
 -->
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous" -->

    <!-- <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
	<!-- <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" /> -->
    
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- JQuery v3.1.1-->
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<!-- google-code-prettify -->
	<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.css" type="text/css" rel="stylesheet" /> -->
    
	<!-- Theme -->
	<!-- <link rel="stylesheet" href="theme/css/style.min.css?1362656653" /> -->

    <!-- Moment.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Icons -->
    <link href="argon/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="argon/assets/css/nucleo-svg.css" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <!-- CSS Files -->
    <link id="pagestyle" href="/argon/assets/css/argon-dashboard.css" rel="stylesheet" />

    <!-- Calendar component -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="/calendar-01/css/style.css">
    <!-- end of calendar component -->


    <title>CodeIgniter Tutorial</title>

    <style>
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		.iconcolor { color: #7A7474; }
        
        /* .align-middle { 
             vertical-align:center;
        } */

        .vertical-center {
            margin: 0;
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
        }
    </style>
   
</head>
<body>
    <div class="container border-bottom">
        <h1><?= esc($title) ?></h1>
    </div>