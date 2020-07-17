<?php

    function Lang($phrase) {

        static $lang = array(
            // --------nav bar phrases----------//

            'Se connecter'      => 'دخول',
            'Profil'            => 'الملف الشخصي',
            'Inscription'       => 'تسجيل',
            'Se deconnecter'    => 'خروج',
            'recherch'          => 'Search',
        );

        return $lang[$phrase];
    };

?>