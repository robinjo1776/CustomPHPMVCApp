<?php
include "../../dbConfig.php";

require_once BASEPATH . '/vendor/qb/vendor/autoload.php';

use QuickBooksOnline\API\DataService\DataService;

Helper::logText("------------ Start callback function ----------------");
session_start();
Helper::logText("Session Start");
function processCode()
{
    Helper::logText("Enter into ProcessCode function.");
    // Create SDK instance
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => CLIENT_ID,
        'ClientSecret' =>  CLIENT_SECRET,
        'RedirectURI' => Helper::fullbaseUrl() . OAUTH_REDIRECT_URI,
        'scope' => OAUTH_SCOPE,
        'baseUrl' => QB_ENVIRONMENT
    ));
    Helper::logText("Create SDK instance.");
    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    Helper::logText("Call OAuth2LoginHelper.");
    Helper::logText($_SERVER['QUERY_STRING']);
    $parseUrl = parseAuthRedirectUrl(htmlspecialchars_decode($_SERVER['QUERY_STRING']));
    Helper::logText(json_encode($parseUrl));

    /*
     * Update the OAuth2Token
     */
    Helper::logText("Exchange Authorization Code For Token");
    $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
    $dataService->updateOAuth2Token($accessToken);
    Helper::logText("Update OAuth2 Token");
    // var_dump($accessToken);
    // die();
    /*
     * Setting the accessToken for session variable
     */
    $_SESSION['sessionAccessToken'] = serialize($accessToken);
    Helper::logText("Save OAuth2 Token in session variable.");
    Helper::logText("------------ End callback function ----------------");
}

function parseAuthRedirectUrl($url)
{
    Helper::logText("Call ParseAuthRedirectUrl.");
    Helper::logText($url);
    parse_str($url, $qsArray);
    Helper::logText(json_encode($qsArray));

    return array(
        'code' => $qsArray['code'],
        'realmId' => $qsArray['realmId']
    );
}

$result = processCode();
