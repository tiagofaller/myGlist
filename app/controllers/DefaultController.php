<?php

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;

class DefaultController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        // start session
        session_start();

// init app with app id and secret
        FacebookSession::setDefaultApplication($this->f3->get('app_id'), $this->f3->get('app_secret'));

// login helper with redirect_uri
        $helper = new FacebookRedirectLoginHelper($this->f3->get('base_url'));

// see if a existing session exists
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            // create new session from saved access_token
            $session = new FacebookSession($_SESSION['fb_token']);

            // validate the access_token to make sure it's still valid
            try {
                if (!$session->validate()) {
                    $session = null;
                }
            } catch (Exception $e) {
                // catch any exceptions
                $session = null;
            }
        } else {
            // no session exists

            try {
                $session = $helper->getSessionFromRedirect();
            } catch (FacebookRequestException $ex) {
                // When Facebook returns an error
            } catch (Exception $ex) {
                // When validation fails or other local issues
                echo $ex->message;
            }
        }

// see if we have a session
        if (isset($session)) {
            
            // save the session
            $_SESSION['fb_token'] = $session->getToken();
            // create a session using saved token or the new one we generated at login
            $session = new FacebookSession($session->getToken());

            // graph api request for user data
            $request = new FacebookRequest($session, 'GET', '/me');
            $response = $request->execute();
            // get response
            $graphObject = $response->getGraphObject()->asArray();

            // print profile data
            echo '<pre>' . print_r($graphObject, 1) . '</pre>';

            // print logout url using session and redirect_uri (logout.php page should destroy the session)
            echo '<a href="' . $helper->getLogoutUrl($session, 'http://localhost/php-sdk-4.0/logout.php') . '">Logout</a>';
            
            $this->f3->set('view','login/sucess.html');
            
            
        } else {
            // show login url
            $this->f3->set('view','login/login.html');
            echo '<a href="' . $helper->getLoginUrl(array('email', 'user_friends')) . '">Login</a>';
        }
    }

}
