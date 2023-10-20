ENVF Boost Based theme
==

Changes
==
- extend config to have a custom setting: Theme->userscourseindex
- new custom navbar including, 
        - mcms menu combined with primary navigation
        - movbile version of the mcms menu
        - add editswitch (not in dashboard)
- add back to top back in
- removed custom course icons
- add styles for questionnaire, customcertification icons
- restructure the scss so it nolonger inherits the pluginname_get_main_scss_content and generates its own
- turn shadows on as a bootstrap feature
- fix horizontal scrolls, fix the mcms squares
- improved footer a bit, closer to figma designs
- fixup the overridden template for the auth_psup overridden login for
- remove dependancy on the course format
- some styling improvements for the questionnaire questions


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