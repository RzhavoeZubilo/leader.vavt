<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


defined('MOODLE_INTERNAL') || die();

// Чтобы список задач обновился, нужно обновить версию плагина!!!

// список задач и их настройка из интерфейса доступны здесь
// Администрирование / Сервер / Задачи / Планировщик задач
// там же можно задать другие интервалы для выполнения задачи или вообще выключить

/*
Поле минут для планирования задачи. Поле использует тот же формат, что и UNIX Cron. Некоторые примеры:
* Каждую минуту
* /5 Каждые 5 минут
2-10 Каждую минуту со 2 до 10 минуты часа (включительно)
2,6,9 2, 6 и 9 минута часа
*/

$tasks = array(
    array(
        'classname' => 'block_vavt_project\task\task_example_vavt',
        'blocking' => 0,
        'minute' => '47',
        'hour' => '1',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '7',
        'disabled' => 1
    ),
);
