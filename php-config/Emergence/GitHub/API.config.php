<?php

/**
 * To generate:
 *
 * 1. Visit Settings page for team
 * 2. Open "OAuth applications" under "Developer settings"
 * 3. Click "Register a new application"
 * 4. Input hostname of current site for "Application name"
 * 5. Input hostname of current site with http:// prefix for "Homepage URL"
 * 6. Input http://hostname/connectorts/github/oauth for "Authorization callback URL"
 * 7. Click "Register application" to finish registering the application
 * 8. Fill in the clientId and clientSecret below with those provided by GitHub for the new application
 * 9. Open https://github.com/login/oauth/authorize?client_id=MY_CLIENT_ID&scope=repo with your clientId filled in
 * 10. Upon redirection back to the site, copy the access token printed and save as accessToken below
 *
 * To limit what organizations/repositories the token will grant access to, consider registering a "bot" user for
 * GitHub representing the application
 */

Emergence\GitHub\API::$clientId = '';
Emergence\GitHub\API::$clientSecret = '';
Emergence\GitHub\API::$accessToken = '';