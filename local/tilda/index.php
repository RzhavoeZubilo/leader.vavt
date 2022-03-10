<?php
/**
 * Created by PhpStorm
 * User: Еремин Олег
 * Date: 10.03.2022
 * Time: 21:41
 */
require_once('../../config.php');
$PAGE->set_url('/local/alumni/index.php');

$PAGE->set_title("Наши преподаватели");
$PAGE->set_heading("Наши преподаватели");
//$PAGE->navbar->add("Выпускники");

echo $OUTPUT->header();


$cdnUrl = "https://store.tildacdn.com/api/getproductslist/?storepartuid=306738985131&recid=317270421&c=1646934918763&getparts=true&getoptions=true&slice=1&sort%5Btitle%5D=asc&size=100";

$postData = file_get_contents($cdnUrl);
$data = json_decode($postData, true);

$teacherCount = count($data['products']);

for ($i = 0; $i <= $teacherCount; $i++ ) {
    $teacher = new stdClass();
    $teacher->count = $i;
    $teacher->name = $data['products'][$i]['title'];
    $lnkphoto = json_decode ($data['products'][$i]['gallery']);
    $teacher->photo = $lnkphoto[0]->img;
    $teacher->description = $data['products'][$i]['descr'];
    $teacher->full_description = $data['products'][$i]['text'];

    $char_count = count($data['products'][$i]['characteristics']);
    for ($j = 0; $j <= $char_count; $j ++) {
        $characteristic = new stdClass();
        $characteristic->title = $data['products'][$i]['characteristics'][$j]['title'];
        $characteristic->value = $data['products'][$i]['characteristics'][$j]['value'];

        $teacher->characteristics = $characteristic;

    }

    $teachers[] = (array)$teacher;
}
//print_object($teachers);
$render = ['teacher' => $teachers];
echo $OUTPUT->render_from_template("local_tilda/teachers", $render);

echo $OUTPUT->footer();