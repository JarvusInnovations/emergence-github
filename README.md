# emergence-github
GitHub integration for emergence sites


## Generating API credentials

1. Visit Settings page for team
2. Open "OAuth applications" under "Developer settings"
3. Click "Register a new application"
4. Input hostname of current site for "Application name"
5. Input hostname of current site with http:// prefix for "Homepage URL"
6. Input http://hostname/connectors/github/oauth for "Authorization callback URL"
7. Click "Register application" to finish registering the application
8. Fill in the `$clientId` and `$clientSecret` in `php-config/Emergence/GitHub/API.config.php` with those provided by GitHub for the new application
9. Open https://github.com/login/oauth/authorize?client_id=MY_CLIENT_ID&scope=repo with your clientId filled in
10. Upon redirection back to the site, copy the access token printed and save as `$accessToken` in `php-config/Emergence/GitHub/API.config.php`

To limit what organizations/repositories the token will grant access to, consider registering a "bot" user for
GitHub representing the application.


## Installing a webhook

1. Visit Settings page for desired repository
2. Open the "Webhooks" page
3. Click "Add webhook"
4. Input http://hostname.com/connectors/github/webhooks for "Payload URL"
5. Leave "Content type" set to `application/json`
6. Generate a random string for "Secret", also save to `$webhookSecret` in `php-config/Emergence/GitHub/Connector.config.php`
7. Choose desired events
8. Click "Add webhook" to finish

To process incoming webhook payloads, create scripts under `event-handlers/Emergence/GitHub/EVENT_NAME/`

[ngrok](https://ngrok.com) is helpful for testing and debugging webhooks