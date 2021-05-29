<?php declare(strict_types=1);


namespace Jdmm\NoticePush;

use Swlib\Http\ContentType;
use Swlib\SaberGM;
use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class NoticePush
 * @package Jdmm\NoticePush
 * @Bean()
 */
class NoticePush
{
    /**
     * @var http-client config
     */
    public $bean;

    /**
     * 专门服务于iot的推送
     * @author wangc
     * @param string $url
     * @param string $mac
     * @param array $message
     * @param string $appKey
     * @param $secret
     * @param int $ssl
     * @param string $gid
     * @return array|bool
     */
    public function iotPost( string $url,string $mac = '', array $message, string $appKey, $secret, $ssl = 0, $gid = '')
    {
        if(empty($mac) && empty($gid)){
            return false;
        }
        $data = [
            'audience' => [],
            'message' => $message,
            'options' => [
                'sendo' => rand(100000,999999)
            ]
        ];
        if(!empty($mac)){
            $data['audience']['mac'] = $mac;
        }
        if(!empty($gid)){
            $data['audience']['gid'] = $gid;
        }

        $options = $this->getOptions($appKey,$secret,$ssl);
        return $this->post($url,$data,$options);
    }

    private function post($url,$data,$options = [])
    {
        if($this->bean) {
            $result = $this->bean->post($url, $data, $options);
        } else {
            $result = SaberGM::post($url, $data, $options);
        }
        return $this->formatData($result);
    }

    private function formatData($res)
    {
        $body = $res->getParsedJsonArray();
        $statusCode = $res->getStatusCode();
        return ['statusCode' => $statusCode,'body' => $body];
    }

    /**
     * 分组消息注册
     * @author wangc
     * @param $url
     * @param $appKey
     * @param $secret
     * @param $gid
     * @param $aid
     * @param int $ssl
     * @return array
     */
    public function registerGroup($url, $appKey, $secret, $gid, $aid, $ssl = 0)
    {
        $options = $this->getOptions($appKey,$secret,$ssl);
        $data = [
            'gid' => $gid,
            'aid' => $aid,
        ];
        return $this->post($url,$data,$options);

    }

    private function getOptions($appKey,$secret,$ssl)
    {
        $options = [
            'headers' => [
                'Content-Type' => ContentType::JSON,
                'Authorization' => 'Basic ' . base64_encode($appKey.':'.$secret)
            ],
            'ssl' => $ssl
        ];

        return $options;
    }
}