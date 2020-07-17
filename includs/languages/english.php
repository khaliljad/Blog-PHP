<?php

    function Lang($phrase) {

        static $lang = array(
            // --------nav bar phrases----------//

            'Se connecter'     => 'Sign up',
            'Profil'           => 'Members',
            'Inscription'      => 'Subscribe',
            'Se deconnecter'   => 'Sign Out',
            'recherch'         => 'Search',
            'Catégories'       => 'Categories',
            'articles'         => 'articles',
            'commentaires'     => 'comments',
        );

        return $lang[$phrase];
    };

?>


