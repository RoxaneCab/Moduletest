<?php


/*
 * Plugin Name:       Module test
 * Plugin URI:        
 * Description:       Module test à des fins d'évaluations!
 * Version:           1.0
 * Requires at least: 
 * Requires PHP:      
 * Author:            Roxane Cabit
 * Author URI:        https://roxanecab.github.io/CV.html
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html 
 * Update URI:        
 * Text Domain:       
*/

// CREATION DES PAGES EN BACKOFFICE (Module test Page et son sous menu: carte restaurant)


//consiste à s'exécuter en même temps qu'admin menu, va mettre en place les fonctions définies plus bas)
add_action('admin_menu', 'module_test_backoffice');
 

function module_test_backoffice(){
    add_menu_page( 
    	'Module test Page', //titre de la page
    	'Module Test',  //titre du menu
    	'manage_options', //aptitude
    	'test-plugin', //menu-slug (version simplifiée)
    	'module_test_menu' // fonction de rappel
        /* '1', //place dans le menu, par exemple 1 sera tout en haut */
    );
    add_submenu_page ( 
        'test-plugin', //slug du parent
        'Carte restaurant Page', //titre page
        'Carte restaurant', //titre menu
        'manage_options', //apt
        'entree', //slug
        'module_test_menu_sub', //fonction de rappel
    );
}
 
function module_test_menu(){
   // class=wrap est le 'défaut' deworpdress qui donne du padding etc...
   echo '<div class="wrap"><h1>Bienvenue dans Module test</h1>C\'est encore en construction</div>';
}
 
function module_test_menu_sub(){
    echo '<div class="wrap"><h1>Bienvenue dans ce sous menu</h1>C\'est encore en construction</div>';
}



// FORMULAIRE CARTE

    add_shortcode('moduletest_carte_form','moduletest_form');

function moduletest_form()
    {
        /* content variable */
        $content = '';

        $content .= '<form method="post" action="http://localhost/wordpressmodule/merci/">';

            $content .= '<input type="text" name="catégorie" placeholder="Catégorie" />';
            $content .= '<br />';

            $content .= '<input type="text" name="nom" placeholder="Nom" />';
            $content .= '<br />';

            $content .= '<input type="text" name="prix" placeholder="Prix" />';
            $content .= '<br />';

            $content .= '<textarea name="comments" placeholder="Description"></textarea>';
            $content .= '<br />';

            $content .= '<input type="submit" name="moduletest_submit_form" value="Ajouter à la carte!" />';

        $content .= '</form>';

        return $content;
    }

//transformer informations en html si envoie mail 
function set_html_content_type()
    {
        return 'text/html';

    }


    function moduletest_form_capture()
    {
        global $post,$wpdb;


// Si le formulaire est envoyé:mail
        if(array_key_exists('moduletest_submit_form',$_POST))
        {
            $to = "roxane.cabit@gmail.com";
            $subject = "Exemple pour envoie par mail";
            $body = '';

            $body .= 'Catégorie: '.$_POST['catégorie'].' <br /> ';
            $body .= 'Nom: '.$_POST['nom'].' <br /> ';
            $body .= 'Prix: '.$_POST['prix']. ' <br /> ';
            $body .= 'Description: '.$_POST['description'].' <br /> ';


            add_filter('wp_mail_content_type','set_html_content_type');
            
            wp_mail($to,$subject,$body);

            remove_filter('wp_mail_content_type','set_html_content_type');
         
      
// Envoyez le résultat à wp_produit

 $insertData = $wpdb->get_results(" INSERT INTO wp_produit (data) VALUES ('".$body."'') ");


        }

    }
    add_action('wp_head','moduletest_form_capture');
