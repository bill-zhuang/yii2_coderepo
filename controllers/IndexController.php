<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\library\bill\Curl;
use app\library\bill\Regex;
use yii\filters\AccessControl;

class IndexController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'download-baidu-music',
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDownloadBaiduMusic()
    {
        $real_downloadlink = '';
        $download_link = trim(yii::$app->request->get('downlink', ''));
        if($download_link !== '')
        {
            $match_count = preg_match(Regex::BAIDU_MUSIC_DOWNLOAD_LINK, $download_link, $match_id);
            if($match_count)
            {
                $real_downloadlink = 'http://music.baidu.com/data/music/file?link=&song_id=' . $match_id[1];
                $bill_curl = new Curl($real_downloadlink);
                $header_info = $bill_curl->getResponseHeaders();
                if ($header_info['http_code'] == 302)
                {
                    $real_downloadlink = $header_info['redirect_url'] . '&song_id=' . $match_id[1];
                }
            }
        }

        echo json_encode($real_downloadlink);
        exit;
    }
}