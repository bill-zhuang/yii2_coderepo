<?php

namespace app\library\bill;

class Regex
{
    const BAIDU_MUSIC_DOWNLOAD_LINK = '!^(?:http://)?music\.baidu\.com/song/(\d+)/!';
    const CHINESE_CHARACTER = '/\p{Han}+/u';
}