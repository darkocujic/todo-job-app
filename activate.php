<?php

require_once('includes/user.php');
$kor = new User();

if (array_key_exists('confirm', $_GET)) {
	$code = $_GET['confirm'];

	$query = $kor->runQuery("SELECT *
									FROM korisnik
									WHERE confirm_code=:code");
	$query->execute(array(":code"=>$code));
	$row = $query->fetch(PDO::FETCH_ASSOC);

	if ($row) {
		$activate = $kor->runQuery("UPDATE korisnik
									SET status='1'
									WHERE confirm_code=:code");
		$ok = $activate->execute(array(":code"=>$code));
		//activate->fetch(PDO::FETCH_ASSOC)
		if ($ok) {
			echo "uspješna aktivacija, preusmjeravanje na login";
			$kor->redirect("index.php");
		} else {
			echo "Greška pri aktivaciji. Pokušajte ponovno.";
		}
	} 
	else {
		echo "Greška pri aktivaciji, pokušajte ponovno.";
	}
}

