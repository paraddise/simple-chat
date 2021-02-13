<?php

namespace app\modules\api\models;

use app\models\User;
use app\modules\api\resources\UserResource;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property UserResource $_user
 */
class SignUpForm extends Model
{
    public $username;
    public $email;
    public $password;

    protected $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'username', 'password'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => User::class ],
            ['email', 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => \Yii::$app->params['user.passwordMinLength']],
        ];
    }


    public function register()
    {
        $this->_user = new UserResource();

        if (!$this->validate()) {
            return false;
        }

        $this->_user->username = $this->username;
        $this->_user->setPassword($this->password);
        $this->_user->generateAuthKey();
        $this->_user->generateAccessToken();
        $this->_user->generateEmailVerificationToken();
        $this->_user->email = $this->email;

        return $this->_user->save() && $this->sendMail();
    }

    /**
     * @return bool
     */
    public function sendMail()
    {
        return true;
    }


    /**
     * @return UserResource|false
     */
    public function getUser()
    {
        return $this->_user;
    }
}
