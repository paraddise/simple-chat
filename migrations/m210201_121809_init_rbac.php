<?php

use yii\db\Migration;

/**
 * Class m210201_121809_init_rbac
 */
class m210201_121809_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $auth = \Yii::$app->authManager;

        // chatGuest
        $chatGuest = $auth->createRole('chatGuest');
        $auth->add($chatGuest);


        $sendMessageRule = new \app\rbac\SendMessageRule();
        $auth->add($sendMessageRule);

        // chatUser
        $sendMessage = $auth->createPermission('sendMessage');
        $sendMessage->description = 'User can send message in the chat';
        $sendMessage->ruleName = $sendMessageRule->name;
        $auth->add($sendMessage);

        $chatUser = $auth->createRole('chatUser');
        $auth->add($chatUser);
        $auth->addChild($chatUser, $chatGuest);
        $auth->addChild($chatUser, $sendMessage);

        $markMessageRule = new \app\rbac\MarkMessageRule();
        $auth->add($markMessageRule);

        $markMessage = $auth->createPermission('markMessage');
        $markMessage->description = 'Allow to mark chat message bad/good';
        $markMessage->ruleName = $markMessageRule->name;
        $auth->add($markMessage);


        // chatAdmin

        $chatAdmin = $auth->createRole('chatAdmin');
        $auth->add($chatAdmin);
        $auth->addChild($chatAdmin, $chatUser);
        $auth->addChild($chatAdmin, $markMessage);

        // general admin role
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        // general user role
        $user = $auth->createRole('user');
        $auth->add($user);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        return \Yii::$app->authManager->removeAll();
    }

}
