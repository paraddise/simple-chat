<?php

namespace app\models;

use app\modules\api\resources\ChatResource;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 *
 * @property int    $id
 * @property string $username
 * @property string $access_token
 * @property string $auth_key
 * @property string $password
 * @property string $password_reset_token
 * @property string $email
 * @property string $verification_token
 * @property Chat[] $chats
 * @property ChatUser[] $chatUsers
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    const STATUS_CREATED = 1;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['username', 'password'], 'required'],
            ['username', 'string', 'length' => [4, 20]],
            ['access_token', 'unique'],
            ['auth_key', 'unique'],
            ['username', 'unique'],
            ['email', 'unique'],
            ['verification_token', 'unique'],
            ['password_reset_token', 'unique'],
        ]);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false
            ]
        ];
    }

    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @return \yii\db\ActiveQuery|ChatQuery
     */
    public function getChats($resource = false)
    {

        return $this->hasMany(($resource ? ChatResource::class : Chat::class), ['id' => 'chat_id'])->viaTable(ChatUser::tableName(),['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param $email
     * @return User|null
     */
    public function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne(['verification_token' => $token]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \Yii::$app->security->generateRandomString(128);
    }

    /**
     * Generates "access_token"
     */
    public function generateAccessToken()
    {
        $this->access_token = \Yii::$app->security->generateRandomString(255);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @param $id
     * @return bool
     */
    public function inChat($id)
    {
        return !!ChatUser::findOne(['user_id' => $this->id, 'chat_id' => $id]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatUsers()
    {
        return $this->hasMany(ChatUser::class, ['user_id' => 'id']);
    }

    /**
     * @param $chatId
     * @return string|false
     */
    public function getChatRole($chatId)
    {
        /** @var $chatUser ChatUser*/
        if (($chatUser = $this->getChatUsers()->andWhere(['chat_id' => $chatId])->one())) {
            return $chatUser->role;
        }

        return false;
    }
}
