<?php
$number=$_GET['SOA'];
$message=$_GET['Content'];
$identifiant=$_GET['MsgId'];
$num="2401";
$id="2401_croixrouge_acc";
$pass="B0GX60YOUS";
$message=strtoupper($message);



try
{
$bdd = new PDO('mysql:host=localhost;dbname=sms', 'root', 'server@root');
}
	catch(Exception $e)
{
die('Erreur : '.$e->getMessage());
}
$sql="INSERT INTO receive VALUES ('','$identifiant','$number','$message')";
$req=$bdd->query($sql);

	if (preg_match('#CRT#', $message)) {

		$contenue=explode(" ", $message);
		$code=$contenue[1];
		$data=$contenue[2];
		$commentaire=$contenue[3];


		// verification du code secret

		$requete1=$bdd->prepare("SELECT codeS FROM agent");
		$requete1->execute();
		$reponse1=$requete1->fetchAll(PDO::FETCH_COLUMN,0);

		if (in_array($code, $reponse1)) {
			// selection de l'ID de l'agent
			$requete3="SELECT id FROM agent WHERE codeS=$code";
			$requete3=$bdd->query($requete3);
			$reponse=$requete3->fetchAll(PDO::FETCH_COLUMN,0);
			$idag=$reponse[0];

			$donnee=explode(":", $data);
			$notation=$donnee[1];
			/*$d=$donnee[0];
			
			switch($d){
			case A:
			$type_id=1;
			break;
			case B:
			$type_id=2;
			break;
			case C:
			$type_id=3;
			break;
			case D:
			$type_id=4;
			break;
			case E:
			$type_id=5;
			break;
			case F:
			$type_id=6;
			break;}
			*/
			
		
			if ($donnee[0]=='A') {
				$type_id=1;
				
			}
				elseif ($donnee[0]=='B') {
					$type_id=2;
					
				}

		// insertion des données
				
			

		
		$requete2=$bdd->prepare("INSERT INTO donnee (notation,commentaire,agent_id,type_id) VALUES ('$notation','$commentaire','$idag','$type_id')");
		$requete2->execute();
		
		if ($requete2) {
				$send="Donnees%20bien%20enregistre";
				}
		else
				{
				$send='verifier';
				}
		}
		else{
			$send="Erreur!!%20verifiez%20votre%20code%20secret";
		}
		
	
	}
	else{
		$send="Erreur!!%20Verifiez%20la%20syntaxe%20de%20votre%20message";
	}


$url='http://41.207.182.252:50417/dispatcher/httpconnectserver/httpConnect.jsp?UserName=2401_croixrouge_acc&Password=B0GX60YOUS&DA='.$number.'&SOA=2401&Content='.$send.'&Flags=0';
$ch=curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$res=curl_exec($ch);

echo"
<html>
<head>
</head>
<body>
 status=0
</body>
</html>";
 ?> 
