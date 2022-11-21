<?php

namespace block_vavt_project\task;

class task_example_vavt extends \core\task\scheduled_task
{
    public function get_name()
    {
        return 'Тестовая задача для примера';
    }

    //обязательная функция, которая выполняется при запуске задачи
    public function execute()
    {
        \block_vavt_project\task_example\example_task::upload_event();
        // обязательно должна возвращать true
        return true;
    }



}

?>