<?php


namespace app\modules\api\resources;


use app\models\ChatUser;

/**
 * Class ChatResource
 * @package app\modules\api\resources
 * @property array $permissions
 * @property MessageResource $last_message
 */
class ChatResource extends \app\models\Chat
{
    public $role = null;
    public $permissions = [
        'sendMessage' => false,
        'markMessage' => false
        ];
    public $last_message = null;

    public function loadRole()
    {
        $this->role = \Yii::$app->user->identity->getChatRole($this->id);
        return $this;
    }

    public function loadLastMessage()
    {
        $this->last_message = $this->getMessages(true)->orderBy('id DESC')->one();
        return $this;
    }

    public function loadPermissions()
    {
        foreach ($this->permissions as $key => $val)  {
            $this->permissions[$key] = \Yii::$app->user->can($key, ['chat' => $this->id]);
        }
        return $this;
    }

    public function fields()
    {
        return array_merge(parent::fields(), ['permissions', 'last_message', 'role', 'users']);
    }

    public function loadUsers() {

    }


    /*public function loadMessages($limit = 10, $offset = 0)
    {
        $this->messages = $this->getMessages()->orderBy('created_at DESC')->limit($limit)->offset($offset)->all();
        return $this;
    }*/
}