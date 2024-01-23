
<?php
$bdd = new PDO('mysql:host=127.0.0.1;dbname=espace_membre', 'root', '');

if(isset($_POST['forminscription'])) {
   $pseudo = htmlspecialchars($_POST['pseudo']);
   $mail = htmlspecialchars($_POST['mail']);
   $mail2 = htmlspecialchars($_POST['mail2']);
   $mdp = sha1($_POST['mdp']);
   $mdp2 = sha1($_POST['mdp2']);
   if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
      $pseudolength = strlen($pseudo);
      if($pseudolength <= 255) {
         if($mail == $mail2) {
            if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
               $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
               $reqmail->execute(array($mail));
               $mailexist = $reqmail->rowCount();
               if($mailexist == 0) {
                  if($mdp == $mdp2) {
                     $insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
                     $insertmbr->execute(array($pseudo, $mail, $mdp));
                     $erreur = "Votre compte a bien été créé ! <a href=\"connexion.php\">Me connecter</a>";
                  } else {
                     $erreur = "Vos mots de passes ne correspondent pas !";
                  }
               } else {
                  $erreur = "Adresse mail déjà utilisée !";
               }
            } else {
               $erreur = "Votre adresse mail n'est pas valide !";
            }
         } else {
            $erreur = "Vos adresses mail ne correspondent pas !";
         }
      } else {
         $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !";
      }
   } else {
      $erreur = "Tous les champs doivent être complétés !";
   }
}
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .error {
      color: #FF0000;
    }
  </style>
</head>

<body>

  <div class="container mt-5">
    <h2 class="text-center">Formulaire d'Inscription</h2>
    <p class="text-center"><span class="error">* champs requis </span></p>
    <form method="post" action="" class="mx-auto" style="max-width: 400px;">
        <!-- Champ CSRF -->
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

        <div class="form-group">
            <label for="pseudo">Prénom - Nom :</label>
            <input type="text" placeholder="Votre nom complet" id="pseudo" name="pseudo" class="form-control" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" />
        </div>
        <div class="form-group">
            <label for="mail">Mail :</label>
            <input type="email" placeholder="Votre mail" id="mail" name="mail" class="form-control" value="<?php if(isset($mail)) { echo $mail; } ?>" />
        </div>
        <div class="form-group">
            <label for="mail2">Confirmation du mail :</label>
            <input type="email" placeholder="Confirmez votre mail" id="mail2" name="mail2" class="form-control" value="<?php if(isset($mail2)) { echo $mail2; } ?>" />
        </div>
        <div class="form-group">
            <label for="mdp">Mot de passe :</label>
            <input type="password" placeholder="Votre mot de passe" id="mdp" name="mdp" class="form-control" />
        </div>
        <div class="form-group">
            <label for="mdp2">Confirmation mot de passe :</label>
            <input type="password" placeholder="Confirmez votre mdp" id="mdp2" name="mdp2" class="form-control" />
        </div>
        <!-- <div class="g-recaptcha" data-sitekey="6LcXgU0pAAAAAEU2lpeGp384uNF6RYgdbantiFqh"></div> -->
        <div class="text-center">
            <input type="submit" name="forminscription" value="Je m'inscris" class="btn btn-primary" />
        </div>
    </form>
   <?php
   if(isset($erreur)) {
      echo '<font color="red">'.$erreur."</font>";
   }
   ?>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>

</html>
