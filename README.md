GoogleTranslateBundle [![Build Status](https://travis-ci.org/spolischook/GoogleTranslateBundle.png?branch=master)](https://travis-ci.org/spolischook/GoogleTranslateBundle)[![knpbundles.com](http://knpbundles.com/Exercise/GoogleTranslateBundle/badge-short)](http://knpbundles.com/Exercise/GoogleTranslateBundle)
===========

About Bundle
------------
This bundle include service for translate with Google Translate
and command which translate messages in your Bundles

Install
------------------
### A) Add GoogleTranslateBundle to your composer.json

```yaml
{
    "require": {
        "exercise/google-translate-bundle": "*"
    }
}
```

### B) Enable the bundle

Enable the bundle in the your kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Exercise\GoogleTranslateBundle\ExerciseGoogleTranslateBundle(),
    );
}
```

### C) Configuration

Enter your private api key in configuration

```yml
# app/config/config.yml
exercise_google_translate:
    api_key: your_api_key
```

Usage
-----
You can it use as a service

```php
// Acme/DemoBundle/Controller/WelcomeController.php

public function indexAction() {

    ...

    $translator = $this->get('exercise_google_translate.translator');
    $translatedString = $translator->translate('Hello World!', 'en', 'fr');

    // Bonjour tout le monde!
    return new Response($translatedString);
}
```
or you can use console command to translate messages

```bash
app/console gtranslate:translate en fr AcmeDemoBundle
```

Bug tracking
------------
GoogleTranslateBundle uses [GitHub issues](https://github.com/Exercise/GoogleTranslateBundle/issues).
If you have found bug, please create an issue.

License
-------
What?
