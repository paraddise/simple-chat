<?php


namespace app\rbac;


use app\models\User;
use yii\rbac\Item;

class SendMessageRule extends \yii\rbac\Rule
{
    public $name = 'sendMessage';


    /**
     * @inheritDoc
     */
    public function execute($user, $item, $params)
    {
        if (!isset($params['chat'])) {
            return false;
        }
        $chat = $params['chat'];
        $user = User::findOne($user);
        $chatRole = $user->getChatRole($chat);
        if ($chatRole === false)  {
            return false;
        }

        $permissions = \Yii::$app->authManager->getPermissionsByRole($chatRole);
        return array_key_exists($item->name, $permissions);

    }
}