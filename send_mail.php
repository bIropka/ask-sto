<?php
/**
 * Settings
 */
 
//Адрес получателя
$adminEmail = 'golozubov1974@mail.ru';


//Адрес отправителя
$fromEmail = 'stoamk63@huxley.timeweb.ru';


/**
 * Proccess form
 */ 
$response['error'] = 0;
$response['mode'] = 0;
if(isset($_POST['callback']))
{
    $name = getPostValue('callback', 'name');
    $phone = getPostValue('callback', 'phone');
    $page = getPostValue('callback', 'page');
    if($name AND $phone)
    {
        if(!$page)
            $page = 'Заказать обратный звонок';
        else
            $page = mb_strtoupper($page, 'UTF-8');
        $message = "Данные из формы <$page>:\n";
        $message .= "Имя:  $name \n";
        $message .= "Телефон:  $phone \n";
        sendMail('Обратный звонок', $message);
        $response['mode'] = 1;
        echo json_encode($response);
    }
    else
        $response['error'] = 1;
}
elseif(isset($_POST['proposal']))
{
    $name = getPostValue('proposal', 'name');
    $phone = getPostValue('proposal', 'phone');
    $page = getPostValue('proposal', 'page');
    $mode = (int)getPostValue('proposal', 'mode');
    
    if($name AND $phone)
    {
        $message = "Заявка из страницы <$page>:\n";
        $message .= "Имя:  $name \n";
        $message .= "Телефон:  $phone \n";
        sendMail('Заявка', $message);
        $response['mode'] = $mode;
        echo json_encode($response);
    }
    else
        $response['error'] = 1;
}
else
    pageRedirect('/');

function getReturnUrl() 
{
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
}

function sendMail($subject, $message) 
{
    global $adminEmail, $fromEmail;
    $name = 'sto-amk63';//$_SERVER['SERVER_NAME'];
    
    $name='=?UTF-8?B?'.base64_encode($name).'?=';
	$subject='=?UTF-8?B?'.base64_encode($subject).'?=';
	$headers="From: $name <$fromEmail>\r\n".
		//"Reply-To: $fromEmail\r\n".
		"MIME-Version: 1.0\r\n".
		"Content-type: text/plain; charset=UTF-8";
    
	mail($adminEmail, $subject, $message, $headers);
}

function getPostValue($group, $key) 
{
    return (isset($_POST[$group][$key])) ? htmlspecialchars($_POST[$group][$key], ENT_QUOTES, 'UTF-8') : '';
}

function pageRedirect($url)
{
    header('Location: '.$url);
}