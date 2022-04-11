<?php

namespace app\login\controller;

use Webman\Http\Request;
use Webman\Http\Response;
use support\Db;
use common\GithubHelper;

class Github
{
    /**
     * 302 > github login
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $github_client_id = getenv('GITHUB_CLIENT_ID', '');
        $redirect_uri = getenv('API_URL', '') . '/login/github/callback';
        $url = "https://github.com/login/oauth/authorize?client_id={$github_client_id}&scope=read:user&redirect_uri={$redirect_uri}";
        return redirect($url, 302);
    }

    /**
     * github login callback
     *
     * @param Request $request
     * @return Response
     */
    public function callback(Request $request): Response
    {
        $code = $request->get('code');
        if (!$code) {
            return api(-2, 'invalid code');
        }

        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->post('https://github.com/login/oauth/access_token', [
                'query' => [
                    'client_id' => getenv('GITHUB_CLIENT_ID', ''),
                    'client_secret' => getenv('GITHUB_CLIENT_SECRET', ''),
                    'code' => $code,
                ],
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'timeout' => 10,
            ]);
            $res = json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return api(-1, 'request error or timeout');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return api(-1, '登录失败, 请重试');
        }
        if (!$res) return api(-1, 'invalid response');
        if (isset($res['error']) || !isset($res['access_token'])) return api(-1, $res['error']);
        $access_token = $res['access_token'];

        // get user info
        try {
            $response = $client->get('https://api.github.com/user', [
                'headers' => [
                    'Authorization' => "token ${access_token}",
                ],
                'timeout' => 10,
            ]);
            $res = json_decode($response->getBody()->getContents(), true);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return api(-1, 'request error or timeout');
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return api(-1, '登录失败, 请重试');
        }
        if (!$res) return api(-1, 'invalid response');

        // get github id
        if (!isset($res['id'])) return api(-1, 'invalid github id');
        $github_id = $res['id'];

        // check user
        $user_id = GithubHelper::getUserId($github_id);
        if ($user_id === false) {
            if (!GithubHelper::register($github_id)) {
                return api(false, '注册失败');
            }
        }
        $token = uniqid() . md5(sha1($user_id) . sha1($github_id));
        return redirect(getenv('WEB_URL', '') . "/github/?token=${token}", 302);
    }
}
