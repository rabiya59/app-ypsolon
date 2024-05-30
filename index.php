<!DOCTYPE html>
<html>
<head>
<title>Ypsolap</title>
<style>
html { color-scheme: light dark; }
body { width: 35em; margin: 0 auto; font-family: Tahoma, Verdana, Arial, sans-serif; }
form {
    width: 100%;
    background-color: red;
    padding : 5px 0px;
}
.c100{
    width: 100%;
    margin: 20px;
}
label{
    display: inline-block;
    min-width: 25%;
}
input[type="submit"]{
    color: RGB(200,100,0);
    border-radius: 5px;
    padding: 5px 10px;
    font-size: 14px;
    border: 2px solid RGB(200,100,0);
}
input[type="submit"]:hover{
    background-color: RGB(200,100,0);
    color: #fff;
    cursor: pointer;
    box-shadow: 0px 0px 5px 0px #777;
}
TABLE.users {
    width: 500px;
}
TR.title {
    background-color: #ACD8EE;
    font-weight = bold;
}
TR.pair {
    background-color: #E7EBED;
}
TR.impair {
    background-color: white;
}

</style>
</head>
<body>
  <?php
   // Connexion à la base de données MariaDB
   $server = "localhost";
   $dbname = "db_ypsolap";
   $user = "userdb";
   $pass = "Password1234";

   try {
   //On se connecte à la BDD
   $dbco = new PDO("mysql:host=$server;dbname=$dbname",$user,$pass);
   $dbco->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo 'Connecté à la base';
  }

  catch(PDOException $e){
  echo 'Erreur : '.$e->getMessage();
  }
  ?>
  <center><h1> - Application YPSOLAP -</h1><h3>Version 0.1</h3></center>
  <p><a href="index.php">Formulaire des utilisateurs</a> | <a href="index.php?action=affusers">Liste des utilisateurs</a> </p>

  <?php
   switch ($_GET["action"]) {
   	case "envoi" :
	   	try {
	   		$nom = $_GET["nom"];
	   		$prenom = $_GET["prenom"];
	   		$promo = $_GET["promo"];

	  		$sth = $dbco->prepare("INSERT INTO tbl_users(nom, prenom, promo) VALUES (:nom, :prenom, :promo)");
	  		$sth->bindParam(':nom',$nom);
	  		$sth->bindParam(':prenom',$prenom);
	  		$sth->bindParam(':promo',$promo);
	  		$sth->execute();

			echo "<p>Utilisateur enregistré !</p>";
		}

		catch(PDOException $e){
	  		echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
	  	}
		break;
	case "affusers" :
		try {
			//On récupère les infos de la table
			$sth = $dbco->prepare("SELECT * FROM tbl_users");
                	$sth->execute();

			//On affiche les infos de la table
                	$data = $sth->fetchAll(PDO::FETCH_ASSOC);

			echo "<table class='users'>";
			echo "<tr class='title'><td>ID</td><td>Nom</td><td>Prenom</td><td>Promo</td><td>Actions</td></tr>";

			$cpt = 1;
              		foreach($data as $row){
				$color = $cpt % 2;
				if ($color == 0) {
					echo "<tr class='pair'><td>".$row['id']."</td><td>".$row['nom']."</td><td>".$row['prenom']."</td><td>".$row['promo']."</td><td> <a href='?action=delete&id=".$row['id']."'> Supprimer </a> </td></tr>";
				} else {
					echo "<tr class='impair'><td>".$row['id']."</td><td>".$row['nom']."</td><td>".$row['prenom']."</td><td>".$row['promo']."</td><td> <a href='?action=delete&id=".$row['id']."'> Supprimer </a> </td></tr>";
				}
				$cpt++;
			}
			echo "</table>";
			//break;
		}
		catch(PDOException $e){
                	echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
            	}
		break;
	case "delete" :
                try {
                        //On récupère les infos de la table
                        $sth = $dbco->prepare("DELETE FROM tbl_users WHERE id=".$_GET['id']."");
                        $sth->execute();

			header("Location:index.php?action=affusers");
                }
                catch(PDOException $e){
                        echo 'Impossible de traiter les données. Erreur : '.$e->getMessage();
                }
                break;
	default :
		  echo "<form action='index.php' method='GET'>";
		    echo "<div class='c100'>";
		      echo "<label for='nom'>Nom : </label>";
		      echo "<input type='text' id='nom' name='nom'>";
		    echo "</div>";
		    echo "<div class='c100'>";
		      echo "<label for='prenom'>Prenom : </label>";
		      echo "<input type='text' id='prenom' name='prenom'>";
		    echo "</div>";
		    echo "<div class='c100'>";
		      echo "<label for='promo'>Promo : </label>";
		      echo "<input type='text' id='promo' name='promo'>";
		    echo "</div>";

		    echo "<div class='c100' id='submit'>";
		      echo "<input type='hidden' id='action' name='action' value='envoi'>";
		      echo "<input type='submit' value='Envoyer'>";
		    echo "</div>";
		  echo "</form>";
  } // Fin du switch
  ?>
<br />
  <p>Projet Fil Rouge N°2 - Application Web [linux/nginx/mariadb/php] </p>
  <p>Tuteur du projet : Emir STAALI</p>
  <p><em>IForm Academy - 2024</em></p>

</body>
</html>
