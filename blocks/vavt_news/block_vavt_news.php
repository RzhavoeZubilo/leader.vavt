<?php
/**
 * User: densh
 * Date: 07.03.2022
 * Time: 00:44
 */


class block_vavt_news extends block_base
{
    public function init()
    {
        $this->title = get_string('vavt_news', 'block_vavt_news');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
        if (! empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = 'The content of our SimpleHTML block!';
        $this->content->footer = 'Footer here...';

        return $this->content;
    }

    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_vavt_news');
            } else {
                $this->title = $this->config->title;
            }

            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_vavt_news');
            }
        }
    }
}