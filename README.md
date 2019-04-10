# OAuth Gateway

We at Redoo Networks searched a way to provide a very easy OAuth Interface for clients, 
which are not able to setup an own API Access in Software. Additionally with OAuth 2.0, it is no longer possible to use a shared Client ID with unknown redirect URL. The redirect URL must be configured during setup of Application.

Because we don't want to share our private Client Secret, we need to find a way, 
which don't break into private information, but is able to forward OAuth Requests and Responses from Client CRM to OAuth Providers

The provider are based on the greatfull thephpleague/oauth2-client package, which already provide many services with examples.
[Link to Repository](https://github.com/thephpleague/oauth2-client)

## Process of get OAuth Data

The process is build in 3 steps:
- The CRM send a request to **start.php** with Callback URL, provider key and additional parameters
- This will return an URL, where you need to send the browser of user. This unique url know details about the request and will forward client to exact provider authentification. At no point, the OAuth Gateway can fetch any login details.
- When authentication is ready, the user is redirected from OAuth Provider to a unique URL of the Gateway (**request.php**), which use returned request token to get Accesst oken. Becaue it also know the URL, where the user should be forwarded, it will forward the data from provider the the given callback URL. At this point everything related to this authorization is delete from OAuth Gateway server. Acess Tokens are nevers stored in any log or any data file.

## Own Hosting

Because OAuth Authorization is theoretically a major privacy problem, you can self host this OAuth Gateway. It is licensed by MIT License and provided without any guarantee.  
After Clone, you need to run ``composer install`` to install dependencies.

When you are using our VtigerCRM or FlexSuite modules, you can define these 2 constants to switch all OAuth requests to this URL:

```php
if(!defined('OAUTH_CALLBACK_ADD')) {
    define('OAUTH_CALLBACK_ADD', '<your-url-to-gateway>/start.php');
}

if(!defined('OAUTH_CALLBACK_REQUEST')) {
    define('OAUTH_CALLBACK_REQUEST', '<your-url-to-gateway>/request.php');
}
```
