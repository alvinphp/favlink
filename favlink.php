<?php
/**
* @author      Alvin Gil
* @license     GNU General Public License v2 or later
* @copyright   Copyright (c) 2026 favlink. All rights reserved.
*/
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;

class plgContentFavlink extends CMSPlugin
{
public function onContentPrepare($context, &$article, &$params, $page = 0)
{
    $wa = Factory::getApplication()->getDocument()->getWebAssetManager();
    $wa->useScript('jquery');

    //Registramos el archivo JS del plugin
   $wa->registerAndUseScript(
        'plg_favlink_js', 
        'plugins/content/favlink/assets/jquery.gpfavicon-1.0.js', 
        ['jquery']
    );

    //expresion para generar el shortcode.
    $pattern = '/\{link\s+url="([^"]+)"\s+text="([^"]+)"\}/i';
    $article->text = preg_replace_callback($pattern, function($matches) {
        $url = $matches[1];
        $text = htmlspecialchars($matches[2], ENT_QUOTES, 'UTF-8');
        // genera el html para imprimir el enlace 
        return '<a href="' . $url . '" class="favlink">' . $text . '</a>';
    }, $article->text);

    // cargamos la funcion que busca el favicon
    $wa->addInlineScript("
    
        (function($) {
            $(document).ready(function() {
                // Seleccionamos los enlaces con la clase 'favlink'
                $('.favlink').gpFavicon({
                    
                });
            });
        })(jQuery);
    ", [], [], ['plg_favlink_js']);
}
}