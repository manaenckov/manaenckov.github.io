if (!isset($_REQUEST)) {return;}
$confirmationToken = 'c6f62e39';
$token = 'e1f4f10d500146d79132194ebad017aa54a01590b640528ece67705bc9925aa6fae7e75ef6f69fdca5c2f';
$secretKey = '123';
$data = json_decode(file_get_contents('php://input'));
if (strcmp($data->secret, $secretKey) !== 0 && strcmp($data->type, 'confirmation') !== 0) {return;}
switch ($data->type) {
case 'confirmation':
echo $confirmationToken; 
break;
case 'message_new':
$userId = $data->object->user_id;
$userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$userId}&v=5.0"));
$user_name = $userInfo->response[0]->first_name;
$request_params = array(
'message' => "{$user_name}, Ваше сообщение получено!
В ближайшее время админ группы на него ответит.",
'user_id' => $userId,
'access_token' => $token,
'v' => '5.0'
);
$get_params = http_build_query($request_params);
file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);
echo('ok');
break;
}