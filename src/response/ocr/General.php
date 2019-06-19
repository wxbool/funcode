<?php

namespace funcode\response\ocr;

use funcode\BaseResponse;

class General extends BaseResponse {

    public function orgHeight()
    {
        return $this->_data['orgHeight'];
    }

    public function orgWidth()
    {
        return $this->_data['orgWidth'];
    }

    public function content()
    {
        return $this->_data['content'];
    }

    public function prismWordsInfo()
    {
        return $this->_data['prism_wordsInfo'];
    }

    public function angle()
    {
        return $this->_data['angle'];
    }

    /**
     * @param string $delimiter
     * @return string
     */
    public function contentWrap($delimiter = "\n")
    {
        $wordInfo = $this->_data['prism_wordsInfo'];
        if (!is_array($wordInfo)) {
            return '';
        }
        $text = '';
        $total = count($wordInfo);
        foreach ($wordInfo as $k => $word) {
            if ($total == ($k+1)) {
                $text .= $word['word'];
            } else {
                $text .= $word['word'] . $delimiter;
            }
        }
        return $text;
    }
}