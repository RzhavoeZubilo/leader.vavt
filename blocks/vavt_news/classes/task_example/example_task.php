<?php

namespace block_vavt_news\task_example;
global $CFG;

raise_memory_limit(MEMORY_HUGE);

class example_task
{
    public static function upload_news()
    {
        return true;
    }
}