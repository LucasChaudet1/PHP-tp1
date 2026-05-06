<?php

function restoreText($x)
{
    if(isset($_POST[$x])){
        echo ' value="' , htmlspecialchars($_POST[$x]) , '"' ;
    }

}

function restoreCiv($x){
    if(isset($_POST['civ']) && $_POST['civ'] == $x){
        echo "checked"; ;
    }
}

function restoreVille($x){
    if(isset($_POST['Ville']) && $_POST['Ville'] == $x){
        echo "selected"; ;
    }
}

function restoreSport($x){
    if(isset($_POST['sport']) && is_array($_POST['sport']) && in_array($x, $_POST['sport'])){
        echo "checked";
    }
}

function restoreDescription(){
    if(isset($_POST['description'])){
        echo htmlspecialchars($_POST['description']);
    }
}

?>


<html>
<head>
    <title> Formulaire d'inscription</title>
    <link rel="stylesheet" type="text/css" href="Style.css"/>
</head>
<body>
<h1>Formulaire d'inscription</h1>
<?php
if(isset($errors)){
    foreach ($errors as $error){
        echo '<p class="error">' . $error . '</p>';
    }
}


?>
<?php if (empty($alreadyResponded)) : ?>
<form action="" method="post" enctype="multipart/form-data">
    <div>
        <label for="prenom" class="label">Prénom: </label>
        <input name="Prenom" id="prenom" type="text" <?php restoreText('Prenom')?>>
    </div>
    <div>
        <label for="nom" class="label">Nom:</label>
        <input name="Nom" id="nom" type="text" <?php restoreText('Nom') ?>>
    </div>
    <div>
        <label for="mdp" class="label">Mot de passe</label>
        <input name="mdp" id="mdp" type="password">(3 caractères min.)
            </div>
            <div>
                <label for="cmdp" class="label">Confirmation mdp</label>
        <input name="confirmation" id="cmdp" type="password" >
            </div>
    <div>
        <label  for="civilité" class="label">Civilité</label>
        <input id="e1_1" type="radio" value="Homme" name="civ"
            <?php restoreCiv("Homme"); ?> >
        <label for="e1_1">Homme</label>

        <input id="e1_2" type="radio" value="Femme" name="civ"
            <?php restoreCiv("Femme"); ?> >
        <label for="e1_2">Femme</label>

    </div>
    <div>
        <label for="e2" class="label">Ville</label>
        <select id="e2" name="Ville">
            <option value=""></option>

            <option value="Cossé-Le-Vivien"
                <?php restoreVille("Cossé-Le-Vivien"); ?>>
                Cossé-Le-Vivien
            </option>

            <option value="Laval"
                    <?php restoreVille("Laval"); ?>>
                Laval
            </option>

            <option value="Craon"
                    <?php restoreVille("Craon"); ?>>
                Craon
            </option>

            <option value="Paris"
                    <?php restoreVille("Paris"); ?>>
                Paris
            </option>
        </select>

    </div>

    <div>
        <span class="label">Sport (optionnel)</span>

        <input id="e3_1" type="checkbox" name="sport[]" value="Footblall"
                <?php restoreSport("Football")  ?>>
        <label for="e3_1">Football</label>

        <input id="e3_2" type="checkbox" name="sport[]" value="Tennis"
                <?php restoreSport("Tennis")  ?>>
        <label for="e3_2">Tennis</label>

        <input id="e3_3" type="checkbox" name="sport[]" value="HandballEquitation"
                <?php restoreSport("HandballEquitation")  ?>>
        <label for="e3_3">Handball</label>

        <input id="e3_4" type="checkbox" name="sport[]" value="Equitation"
                <?php restoreSport("Equitation")  ?>>
        <label for="e3_4">Equitation</label>

        <input id="e3_5" type="checkbox" name="sport[]" value="Natation"
                <?php restoreSport("Natation")  ?>>
        <label for="e3_5">Natation</label>

        <input id="e3_6" type="checkbox" name="sport[]" value="6"
                <?php restoreSport("6")  ?>>
        <label for="e3_6">Golf</label>
    </div>

    <div>
        <label for="photo" class="label">Photo :</label>
        <input name="photo" id="photo" type="file"></div>

    <div>
        <label for="description" class="label">Description :</label>

        <textarea name="description"><?php restoreDescription(); ?></textarea>
    </div>

        <label for="soumettre" ></label>
        <input name="soumettre" id="soumettre" type="submit" value="Soumettre" >
</form>
<?php else: ?>
    <p class="error">Vous avez déjà répondu à ce formulaire. L’accès est bloqué.</p>
<?php endif; ?>
</body>
</html>
