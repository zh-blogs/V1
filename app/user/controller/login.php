<?php

namespace app\user\controller;

class Login
{
    public function index()
    {
        return api();
    }

    public function github()
    {
        $github_client_id = getenv('GITHUB_CLIENT_ID', '');
        $redirect_uri = getenv('URL_FULL', '') . '/user/login/githubCallBack';
        $url = "https://github.com/login/oauth/authorize?client_id={$github_client_id}&scope=user:email+read:user&redirect_uri={$redirect_uri}";
        return redirect($url, 302);
    }

    public function githubCallBack()
    {
        $code = request()->get('code');
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

        return api(msg: 'github callback', data: $res);
    }
}
