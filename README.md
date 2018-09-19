# Login to Nextcloud via HTTP POST request [![codecov](https://codecov.io/gh/nextcloud/loginviapost/branch/master/graph/badge.svg)](https://codecov.io/gh/nextcloud/loginviapost)

**Warning:** This plugin allows login CSRF by design. You are likely better off
using a proper single-sign-on solution such as the [Nextcloud SAML application](https://github.com/nextcloud/user_saml).

## Description
Login users to your Nextcloud with a simple HTTP POST request from another web page,
the following form explains the usage  of this plugin:

```html
<html>
    <body>
        <form method="post" action="https://example.com/index.php/apps/loginviapost/login">
            <input type="text" name="username" />
            <input type="text" name="password" />
            <input type="submit" value="Submit" />
        </form>
    </body>
</html>

For use this plugin you need to edit core file in your nextcloud installation folder - lib/private/AppFramework/Http/Request.php#503 -> add "return FALSE;".

```
