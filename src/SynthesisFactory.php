<?php


namespace SpeechSynthesis;


use SpeechSynthesis\Contracts\AudioSynthesisStrategy;
use SpeechSynthesis\Products\Config;

class SynthesisFactory
{
    private $synthesisRes = [];

    public function __construct($synthesisType,$filename, $config = [])
    {
        $configClass = Config::getInstance();
        // 增加配置
        if($config){
            $configClass->setConfig($config);
        }
        $config = $configClass->getConfig($synthesisType);
        if($config){
            $synthesisTypes = 'SpeechSynthesis\Products\\' . $synthesisType;
            $synthesisClass = new $synthesisTypes($config);
            if($synthesisClass instanceof AudioSynthesisStrategy){
                $this->synthesisRes = $synthesisClass->textToAudio($filename);
            }
        }
    }

    /**
     * 文件名
     * @return mixed
     */
    public function getSpeechFile():string
    {
        return $this->synthesisRes['data']['audio_name'];
    }

    /**
     * 返回错误信息
     * false代表没有错误
     * @return bool|mixed|string
     */
    public function getErrorMsg()
    {
        if(!$this->synthesisRes){
            return '出错了，没有任何数据';
        }
        if($this->synthesisRes['code'] === 0){
            return $this->synthesisRes['msg'];
        }
        return false;
    }
}