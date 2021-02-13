<?php

namespace app\models;

use app\modules\api\resources\MessageResource;
use app\modules\api\resources\UserResource;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "chat".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $created_at
 * @property int $created_by
 *
 * @property User $createdBy
 * @property Message[] $messages
 * @property ChatUser[] $chatUsers
 * @property User[] $users
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'description'], 'string', 'max' => 255],
//            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages($resource = false)
    {
        return $this->hasMany(($resource ? MessageResource::class : Message::class), ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[ChatUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChatUsers()
    {
        return $this->hasMany(ChatUser::class, ['chat_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
     */
    public function getUsers($resource = false)
    {
        return $this->hasMany(($resource ? UserResource::class : User::class), ['id' => 'user_id'])
            ->viaTable(ChatUser::tableName(), ['chat_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChatQuery(get_called_class());
    }

    public function addUser($user, $role)
    {
        $model = new ChatUser();
        $model->chat_id = $this->id;
        $model->user_id = $user->id;
        $model->role = $role;
        return $model->save();
    }

}
