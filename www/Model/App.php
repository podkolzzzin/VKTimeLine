<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 07.09.13
 * Time: 22:54
 * To change this template use File | Settings | File Templates.
 */
define('AUTH_LINK',"https://oauth.vk.com/authorize?client_id=".App::ID."&scope=".App::SCOPE."&redirect_uri=".App::REDIRECT, true);
define("LOGIN_PATTERN", "https://oauth.vk.com/access_token?client_id=".App::ID."&client_secret=".App::SECRET."&code=%s&redirect_uri=".App::REDIRECT, true);
define("API_LINK", "https://api.vk.com/method/%s?&access_token=%s&%s");
class App
{
    const ID = 3865592;
    const SECRET = 'vSUlIQxUYWYYtP4fpvS2';
    const SCOPE = 2;
    const REDIRECT = "http://vktimeline.com/app/finishlogin&response_type=code&v=5.0&display=page";
    const AUTH_LINK = AUTH_LINK;
    const LOGIN_PATTERN = LOGIN_PATTERN;
    const API_LINK = API_LINK;
}

