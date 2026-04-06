<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Cerca per indirizzo</title>


<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<link rel="stylesheet" href="cxc.css" type="text/css" media="all">
<!-- CSS -->
<style type="text/css">
	Body {
	text-align: center;
	}
.myForm {
font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
font-size: 0.8em;
padding: 1em;
border: 1px solid #ccc;
border-radius: 3px;
}

.myForm * {
box-sizing: border-box;
}

.myForm label {
font-size: 0.9em;
}

.myForm .audioOnly {
position: absolute;
width: 1px;
height: 1px;
padding: 0;
margin: -1px;
overflow: hidden;
clip: rect(0, 0, 0, 0);
border: 0;
}

h3 {
	font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
}

.myForm input {
border: 1px solid #ccc;
border-radius: 3px;
font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
font-size: 0.9em;
padding: 0.5em;
}
.myForm input[type="d"]
.myForm input[type="s"],
.myForm input[type="c"] {
width: 12em;
}

.myForm button {
padding: 0.7em;
border-radius: 0.5em;
background: #eee;
border: none;
font-weight: bold;
margin-left: 1.5em;
}

 .myForm button:hover {
background: #ccc;
cursor: pointer;
}
</style>

</head>
<body>
<?php
include "testata.php";

$db = new SQLite3('catasto.db');

?>
<label><h3>Ricerca per indirizzo</h3></label>
<form class="myForm" method="get" enctype="application/x-www-form-urlencoded" action="indirizzo_results.php">


<label class="input"  for="d" >dug </label> 
<input type="text" name="d" value="" style="width: 200px"  autofocus placeholder="dug : VIA, PIAZZA, VICOLO ...">


<label class="input"  for="s" >strada </label> 
<input type="text" name="s" value="" style="width: 400px" required placeholder="strada e civico, es: settembre 273">


<label class="input" for="c">civico</label> 
<input type="text" name="c"  style="width: 200px" placeholder="civico">

<!--


<label>
<input type="checkbox"> Remember me
</label>



<button>invia</button>
-->
<button>
<input type="submit" value="INVIA">
</button>
</form>

</body>
</html>

