<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//Đường dẫn tới thư mục chứa bộ thư viện của mà bạn lưu.
require_once APPPATH . 'third_party/libraries/Google/autoload.php';

class Google {

    protected $client_id = '557748115922-bsb2imoplhdsidne5klqmffvvhf0en6g.apps.googleusercontent.com';
    protected $client_secret = 'a40d3t9elKOuFlJ3uyvIlEDf';
    protected $redirect_uri = 'http://localhost/shops_cms/google-login/';

    function __construct($configs = array()) {
        //parent::__construct();
        if(!empty($configs)){
            $this->client_id = $configs['client_id'];
            $this->client_secret = $configs['client_secret'];
            $this->redirect_uri = $configs['redirect_uri'];
        }
    }

    function login() {
        //incase of logout request, just unset the session var
        if (isset($_GET['logout'])) {
            unset($_SESSION['access_token']);
            header("Location: http://localhost/shops_cms");
        }

        /*         * **********************************************
          Make an API request on behalf of a user. In
          this case we need to have a valid OAuth 2.0
          token for the user, so we need to send them
          through a login flow. To do this we need some
          information from our API console project.
         * ********************************************** */
        $client = new Google_Client();
        $client->setClientId($this->client_id);
        $client->setClientSecret($this->client_secret);
        $client->setRedirectUri($this->redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");

        /*         * **********************************************
          When we create the service here, we pass the
          client to it. The client then queries the service
          for the required scopes, and uses that when
          generating the authentication URL later.
         * ********************************************** */
        $service = new Google_Service_Oauth2($client);

        /*         * **********************************************
          If we have a code back from the OAuth 2.0 flow,
          we need to exchange that with the authenticate()
          function. We store the resultant access token
          bundle in the session, and redirect to ourself.
         */

        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            header('Location: ' . filter_var($this->redirect_uri, FILTER_SANITIZE_URL));
            exit;
        }

        /*         * **********************************************
          If we have an access token, we can make
          requests, else we generate an authentication URL.
         * ********************************************** */
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
        } else {
            $authUrl = $client->createAuthUrl();
        }


//Display user info or display login url as per the info we have.
        echo '<div style="margin:20px">';
        if (isset($authUrl)) {
            header("Location: ". $authUrl);
            //show login url
            echo '<div align="center">';
            echo '<h3>Login with Google -- Demo</h3>';
            echo '<div>Please click login button to connect to Google.</div>';
            echo '<a class="login" href="' . $authUrl . '"><img src="images/google-login-button.png" /></a>';
            echo '</div>';
        } else {

            $user = $service->userinfo->get(); //get user info 
            /*
            //connect to database
            $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
            if ($mysqli->connect_error) {
                die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
            }
            // change character set to utf8
            if (!$mysqli->set_charset("utf8")) {
                printf("Error loading character set utf8: %s\n", $mysqli->error);
            } else {
                printf("Current character set: %s\n", $mysqli->character_set_name());
            }

            //check if user exist in database using COUNT
            $result = $mysqli->query("SELECT COUNT(google_id) as usercount FROM google_users WHERE google_id=$user->id");
            $user_count = $result->fetch_object()->usercount; //will return 0 if user doesn't exist
            */
            //show user picture
            echo '<img src="' . $user->picture . '" style="float: right;margin-top: 33px;" />';

            if (isset($user_count) && $user_count) { //if user already exist change greeting text to "Welcome Back"
                echo 'Welcome back ' . $user->name . '! [<a href="' . $this->redirect_uri . '?logout=1">Log Out</a>]';
            } else { //else greeting text "Thanks for registering"
                echo 'Hi ' . $user->name . ', Thanks for Registering! [<a href="' . $this->redirect_uri . '?logout=1">Log Out</a>]';
                //$statement = $mysqli->prepare("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link) VALUES (?,?,?,?,?)");
                //$statement->bind_param('issss', $user->id, $user->name, $user->email, $user->link, $user->picture);
                //$statement->execute();
                //echo $mysqli->error;
            }

            //print user details
            echo '<pre>';
            print_r($user);
            echo '</pre>';
        }
        echo '</div>';
    }
    
    function login1() {
        //incase of logout request, just unset the session var
        if (isset($_GET['logout'])) {
            unset($_SESSION['access_token']);
        }

        /*         * **********************************************
          Make an API request on behalf of a user. In
          this case we need to have a valid OAuth 2.0
          token for the user, so we need to send them
          through a login flow. To do this we need some
          information from our API console project.
         * ********************************************** */
        $client = new Google_Client();
        $client->setClientId($this->client_id);
        $client->setClientSecret($this->client_secret);
        $client->setRedirectUri($this->redirect_uri);
        $client->addScope("email");
        $client->addScope("profile");

        /*         * **********************************************
          When we create the service here, we pass the
          client to it. The client then queries the service
          for the required scopes, and uses that when
          generating the authentication URL later.
         * ********************************************** */
        $service = new Google_Service_Oauth2($client);

        /*         * **********************************************
          If we have a code back from the OAuth 2.0 flow,
          we need to exchange that with the authenticate()
          function. We store the resultant access token
          bundle in the session, and redirect to ourself.
         */

        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            header('Location: ' . filter_var($this->redirect_uri, FILTER_SANITIZE_URL));
            exit;
        }

        /*         * **********************************************
          If we have an access token, we can make
          requests, else we generate an authentication URL.
         * ********************************************** */
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
        } else {
            $authUrl = $client->createAuthUrl();
        }


//Display user info or display login url as per the info we have.
        echo '<div style="margin:20px">';
        if (isset($authUrl)) {
            //show login url
            echo '<div align="center">';
            echo '<h3>Login with Google -- Demo</h3>';
            echo '<div>Please click login button to connect to Google.</div>';
            echo '<a class="login" href="' . $authUrl . '"><img src="images/google-login-button.png" /></a>';
            echo '</div>';
        } else {

            $user = $service->userinfo->get(); //get user info 
            /*
            //connect to database
            $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
            if ($mysqli->connect_error) {
                die('Error : (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
            }
            // change character set to utf8
            if (!$mysqli->set_charset("utf8")) {
                printf("Error loading character set utf8: %s\n", $mysqli->error);
            } else {
                printf("Current character set: %s\n", $mysqli->character_set_name());
            }

            //check if user exist in database using COUNT
            $result = $mysqli->query("SELECT COUNT(google_id) as usercount FROM google_users WHERE google_id=$user->id");
            $user_count = $result->fetch_object()->usercount; //will return 0 if user doesn't exist
            */
            //show user picture
            echo '<img src="' . $user->picture . '" style="float: right;margin-top: 33px;" />';

            if (isset($user_count) && $user_count) { //if user already exist change greeting text to "Welcome Back"
                echo 'Welcome back ' . $user->name . '! [<a href="' . $this->redirect_uri . '?logout=1">Log Out</a>]';
            } else { //else greeting text "Thanks for registering"
                echo 'Hi ' . $user->name . ', Thanks for Registering! [<a href="' . $this->redirect_uri . '?logout=1">Log Out</a>]';
                //$statement = $mysqli->prepare("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link) VALUES (?,?,?,?,?)");
                //$statement->bind_param('issss', $user->id, $user->name, $user->email, $user->link, $user->picture);
                //$statement->execute();
                //echo $mysqli->error;
            }

            //print user details
            echo '<pre>';
            print_r($user);
            echo '</pre>';
        }
        echo '</div>';
    }

}
