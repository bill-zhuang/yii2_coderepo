<?php

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\library\bill\Curl;
use app\library\bill\Regex;
use app\library\bill\Util;
use yii\filters\AccessControl;
use yii\web\Response;

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
                            'get-baidu-music-link',
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

    public function actionGetBaiduMusicLink()
    {
        $jsonArray = [];
        $params = yii::$app->request->get('params', array());
        if (isset($params['downloadLink'])) {
            $downloadLink = trim($params['downloadLink']);
            if($downloadLink !== '') {
                $matchCount = preg_match(Regex::BAIDU_MUSIC_DOWNLOAD_LINK, $downloadLink, $matchId);
                if($matchCount) {
                    $realDownloadLink = 'http://music.baidu.com/data/music/file?link=&song_id=' . $matchId[1];
                    $headerInfo = Curl::getResponseHeaders($realDownloadLink);
                    if ($headerInfo['http_code'] == 302) {
                        $jsonArray['data'] = [
                            'downloadUrl' => $headerInfo['redirect_url'] . '&song_id=' . $matchId[1]
                        ];
                    } else {
                        $jsonArray['error'] = Util::getJsonResponseErrorArray(200, 'Fail to get baidu download music url.');
                    }
                } else {
                    $jsonArray['error'] = Util::getJsonResponseErrorArray(200, 'Request param downloadLink is invalid.');
                }
            } else {
                $jsonArray['error'] = Util::getJsonResponseErrorArray(200, 'Request param downloadLink not empty.');
            }
        } else {
            $jsonArray['error'] = Util::getJsonResponseErrorArray(200, 'Request param downloadLink not set.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $jsonArray;
    }
}