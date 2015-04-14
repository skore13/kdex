<?php

session_start();
$sql = new SQLite3("data/data.sqlite");
?>
<!DOCTYPE html>
<html>
<head>
	<title>KD exchange</title>
	<script src="lightbox/jquery-1.11.0.min.js"></script>
	<script src="lightbox/lightbox.min.js"></script>
	<link href="lightbox/lightbox.css" rel="stylesheet" />
	<style>
	.list td {
		border-width:1px;
		border-style:solid;
		border-spacing: 0px;
		padding:5px;
	}
	</style>
</head>
<body>
	<h1>KDes aliance Viva Moravia [VMVR]</h1>
	<?php if(isset($_SESSION['error'])): ?>
		<div style="color: red; font-size: 1.5em">
		<?php echo $_SESSION['error'] ?>
		</div>
		<?php unset($_SESSION['error']); ?>
	<?php endif ?>
	
	<?php if(isset($_SESSION['name'])): 
		$results = $sql->query('SELECT volno FROM users WHERE ID='.$_SESSION['ID']);
		$row = $results->fetchArray();
		$count = $row["volno"];
		
		// Vybere uživatele, se kterými přihlášený nemá KD
		$stmt=$sql->prepare('SELECT * FROM users WHERE (SELECT COUNT(*) FROM dohody WHERE ID1=users.ID AND ID2=:id)=0 AND (SELECT COUNT(*) FROM dohody WHERE ID2=users.ID AND ID1=:id)=0 AND ID!=:id');
		$stmt->bindValue(':id', $_SESSION['ID'], SQLITE3_INTEGER);
		$nekd = $stmt->execute();
		// Vybere uživatele, se kterými přihlášený má KD
		$stmt=$sql->prepare('SELECT * FROM users WHERE (SELECT COUNT(*) FROM dohody WHERE ID1=users.ID AND ID2=:id)!=0 OR (SELECT COUNT(*) FROM dohody WHERE ID2=users.ID AND ID1=:id)!=0 AND ID!=:id');
		$stmt->bindValue(':id', $_SESSION['ID'], SQLITE3_INTEGER);
		$kd = $stmt->execute();
		?>
		Přihlášen jako <?php echo $_SESSION['name']; ?>. <a href="logout.php">Odhlásit se</a>
		<br><br>
	
		<form action="setkd.php" method="post">
			<label for="kdcount">Počet vašich volných KD: </label><input type="number" id="kdcount" name="kdcount" value="<?php echo $count ?>">
			<input type="submit" value="Přenastavit">
		</form>
		
		<h2> Hráči, se kterými můžete uzavřít KD </h2>
		<table>
			<tr><td>
			<table class="list">
				<tr> <td> Jméno hráče <td> Počet dostupných KD <td> Poslat žádost <td> Už s ním mám KD <td> Právě jsme uzavřeli KD
			<?php while($u = $nekd->fetchArray()):
				if($u["volno"]!=0): ?>
				<tr>
				<form action="deal.php" method="post">
					<input type="hidden" name="partnerID" value="<?php echo $u["ID"]; ?>">
					<td> <?php echo $u["name"]; ?>
					<td> <?php echo $u["volno"]; ?>
					<td> <a href="http://s13-cz.ikariam.gameforge.com/index.php?view=sendIKMessage&receiverId=<?php echo $u["ikaID"]; ?>" target="_blank">Žádost</a>
					<td> <button type="submit" name="time" value="past">Dříve</button>
					<td> <button type="submit" name="time" value="now">Teď</button>
				</form>
			<?php endif; endwhile ?>
			</table>
			<td style="width:50px">
			<td valign="top" style="max-width:361px">
			Rozdíl mezi volbami <b>Dříve</b> a <b>Teď</b> je, že volba teď sníží počet dostupných KD o 1
		</table>

		<br><br><br><br>
		
		<h2> Hráči, se kterými máte uzavřenou KD </h2>

		<table class="list">
			<tr> <td> Jméno hráče <td> Smazat KD <td> Zrušit KD a zvýšit počet dostupných KD
		<?php while($u = $kd->fetchArray()): ?>
			<tr>
			<form action="remove.php" method="post">
				<input type="hidden" name="partnerID" value="<?php echo $u["ID"]; ?>">
				<td> <?php echo $u["name"]; ?>
				<td> <button type="submit" name="add" value="no">Smazat</button>
				<td> <button type="submit" name="add" value="yes">Zrušit</button>
			</form>
		<?php endwhile ?>
		</table>
	<?php else: ?>
		<form action="login.php" method="post">
		<h2> Přihlášení </h2>
		<table>
		<tr> <td> <label for="name">Jméno: </label>     <td> <input id="name" name="name" placeholder="Jméno">                         <td> Stejné jméno jako v Ikariamu
		<tr> <td> <label for="password">Heslo: </label> <td> <input type="password" id="password" name="password" placeholder="Heslo"> <td> Pokud jste při registraci nějaké zadali
		<tr> <td> 					<td style="text-align:right"> <input type="submit" value="Přihlásit se">
		</table>
		</form>
	
		<form action="register.php" method="post">
		<h2> Registrace </h2>
		<table>
		<tr> <td> <label for="name-reg">Jméno: </label>     <td> <input id="name-reg" name="name" placeholder="Jméno">                         <td> Stejné jméno jako v Ikariamu
		<tr> <td> <label for="password-reg">Heslo: </label> <td> <input type="password" id="password-reg" name="password" placeholder="Heslo"> <td> Nepovinné
		<tr> <td> <label for="ikaID">ID: </label>          <td> <input id="ikaID" name="ikaID" placeholder="ID">                                        <td> <a href="id.png" data-lightbox="id" data-title="Kde najít Vaše ID.">Kde to najdu?</a>
		<tr> <td>                                       <td style="text-align:right"> <input type="submit" value="Registrovat se">
		</table>
		</form>
	<?php endif ?>
	<br><br><br>
	&copy;Created by skore13 in 2015 <a href="https://github.com/skore13/kdex">https://github.com/skore13/kdex</a>
</body>
</html><?php
$sql->close();

