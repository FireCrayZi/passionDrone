<?php

if(!empty($_POST) && !empty($_POST['email'])){
    require_once 'db.php';
    require_once 'functions.php';
    $req = $pdo->prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req->execute([$_POST['email']]);
    $user = $req->fetch();
    if($user){
        if(session_status()=== PHP_SESSION_NONE){
            session_start();
          }
          $reset_token = str_random(60);
         $req = $pdo->prepare('UPDATE users SET reset_token = ?, reset_at = NOW() WHERE id = ?');
         $req->execute([$reset_token, $user->id]);
        
          $_SESSION['flash']['success'] = 'Les instructions du rappel de mot de passe vous ont été envoyées par emails';
        mail($_POST['email'], 'Réinitiatilisation de votre mot de passe', "Afin de réinitialiser votre mot de passe merci de cliquer sur ce lien\n\nlocalhost/passionDrone/dronepassion/reset.php?id={$user->id}&token=$reset_token");
        header('Location: login.php');
        exit();
    }else{
        $_SESSION['flash']['danger'] = 'Aucun compte ne correspond à cet adresse';
    }
  }

?>
