ENVF Boost Based theme
==

This is a theme for ENVF/Concours Veto PostBac. 

Local plugin for ENVF.

Do not forget to add the following in your config.php:

    define('PDF_CUSTOM_FONT_PATH', __DIR__ .'/theme/envf/tcpdffonts/' );
    $CFG->customscripts = __DIR__ .'/theme/envf/customscripts';

# Additional config

The setup class will setup most of the necessary values for the site, except:

* Recaptcha (recaptchapublickey and recaptchaprivatekey)
* Theme envf google analytics (theme_envf/ganalytics) 

Logos
==

Images
==

Images are mostly coming from:

* Unsplash : https://unsplash.com/ (https://unsplash.com/license)
* Pexels : https://www.pexels.com/fr-fr/license
* iStockPhoto (For the home page image: https://www.istockphoto.com/fr/photo/femme-v%C3%A9t%C3%A9rinaire-sur-le-lieu-de-travail-gm182910766-13904333?clarity=false, for
which a standard license has been purchased - https://www.istockphoto.com/fr/help/licenses)

Templates
==