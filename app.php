<?php
error_reporting(E_ALL);
$_POST = json_decode(file_get_contents('php://input'), 1);
$text  = '';
$form  = $_POST['form'];
$items = $_POST['items'];
if ($form['name'] == 'Виджет "Форма"') {
    $subject = 'Заявка';
    $fields  = $_POST['fields'];
    if ($fields) {
        $name       = $fields[0]['value']; //*
        $phone      = $fields[1]['value']; //*
        $email      = $fields[2]['value'];
		$city      = $fields[3]['value'];
		$otdel      = $fields[4]['value'];
        $sposob_opl = $fields[5]['value']; //*
        $sposob_pol = $fields[6]['value']; //*
        $comment    = $fields[7]['value'];
        if ($name && $phone && $sposob_opl && $sposob_pol) {
            $items_info = '';
            foreach ($items as $k => $v) {
                $items_info .= <<<HTML
{$v[0]['value']} ({$v[4]['value']} шт) - {$v[1]['value']}<br>
HTML;
            }
            $text = <<<HTML
Имя и фамилия: {$name}<br>
Телефон: {$phone}<br>
Электронный адрес: {$email}<br>
Способ оплаты: {$sposob_opl}<br>
Способ получения: {$sposob_pol}<br>
Город: {$city}<br>
Отделение: {$otdel}<br>
Комментарий: {$comment}<br>
Товары:<br> {$items_info}
HTML;
        }
    }
} else if ($form['name'] == 'Виджет "Обратный звонок"') {
    $subject = 'Обратная связь';
    $fields  = $_POST['fields'];
    if ($fields) {
        $name  = $fields[0]['value'];
        $phone = $fields[1]['value']; //*
        if ($phone) {
            $text = <<<HTML
Имя: {$name}<br>
Телефон: {$phone}
HTML;
        }
    }
}
if ($text) {
    require 'PHPMailer/PHPMailerAutoload.php';
    
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->CharSet    = 'UTF-8';
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'liebermag@gmail.com';
    $mail->Password   = 'Ab159753258';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->setFrom('liebermag@gmail.com');
    $mail->addAddress('liebermag@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $text;
    $mail->AltBody = $text;
    if (!$mail->send())
        $result = array(
            'result' => 0
        );
    else
        $result = array(
            'result' => 1
        );
} else
    $result = array(
        'result' => 0
    );
echo json_encode($result);
?>