<?php

class Home extends Controller {

    //! HTTP route pre-processor
    function beforeroute($f3) {
        if ($f3->get('SESSION.user_id') != $f3->get('user_id') ||
                $f3->get('SESSION.crypt') != $f3->get('password'))
        // Invalid session
            $f3->reroute('/login');
        if ($f3->get('SESSION.lastseen') + $f3->get('expiry') * 3600 < time())
        // Session has expired
            $f3->reroute('/logout');
        // Update session data
        $f3->set('SESSION.lastseen', time());
        // Prepare admin menu
        $f3->set('menu', array(
            '/admin/pages' => 'Pages',
            '/admin/assets' => 'Assets',
            '/logout' => 'Logout'
                )
        );
    }

    function login($f3) {
        $f3->clear('SESSION');
        if ($f3->get('eurocookie')) {
            $loc = Web\Geo::instance()->location();
            if (isset($loc['continent_code']) && $loc['continent_code'] == 'EU')
                $f3->set('message', 'The administrator pages of this Web site uses cookies ' .
                        'for identification and security. Without these ' .
                        'cookies, these pages would simply be inaccessible. By ' .
                        'using these pages you agree to this safety measure.');
        }
        $f3->set('COOKIE.sent', TRUE);
        if ($f3->get('message')) {
            $img = new Image;
            $f3->set('captcha', $f3->base64(
                            $img->captcha('fonts/thunder.ttf', 18, 5, 'SESSION.captcha')->
                                    dump(), 'image/png'));
        }
        $f3->set('inc', 'login.htm');
    }

    //! Process login form
    function auth($f3) {
        if (!$f3->get('COOKIE.sent'))
            $f3->set('message', 'Cookies must be enabled to enter this area');
        else {
            $crypt = $f3->get('password');
            $captcha = $f3->get('SESSION.captcha');
            if ($captcha && strtoupper($f3->get('POST.captcha')) != $captcha)
                $f3->set('message', 'Invalid CAPTCHA code');
            elseif ($f3->get('POST.user_id') != $f3->get('user_id') ||
                    crypt($f3->get('POST.password'), $crypt) != $crypt)
                $f3->set('message', 'Invalid user ID or password');
            else {
                $f3->clear('COOKIE.sent');
                $f3->clear('SESSION.captcha');
                $f3->set('SESSION.user_id', $f3->get('POST.user_id'));
                $f3->set('SESSION.crypt', $crypt);
                $f3->set('SESSION.lastseen', time());
                $f3->reroute('/admin/pages');
            }
        }
        $this->login($f3);
    }

    //! Terminate session
    function logout($f3) {
        $f3->clear('SESSION');
        $f3->reroute('/login');
    }

}
