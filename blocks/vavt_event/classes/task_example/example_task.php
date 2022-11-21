<?php

namespace block_vavt_event\task_example;
global $CFG;

raise_memory_limit(MEMORY_HUGE);

class example_task
{
    public static function upload_event()
    {
        return true;
    }
}