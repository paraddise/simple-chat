<?php


namespace app\modules\api\controllers;


use app\models\User;
use app\modules\api\resources\ChatResource;
use app\modules\api\resources\MessageResource;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;

class ChatController extends \yii\rest\Controller
{


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);
        $behaviors['cors'] = Cors::class;
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'send-message' => ['POST']
            ]
        ];

        $behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];


        return $behaviors;

    }

    public function actionIndex()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $chats = $user->getChats(true)->orderBy('updated_at DESC')->all();
        return array_map(fn($chat) => $chat->loadPermissions()->loadRole()->loadLastMessage(), $chats);
    }

    public function actionView($id)
    {
        $user = \Yii::$app->user->identity;
        if ( $user->inChat($id) ) {
            return ChatResource::findOne($id)->loadPermissions()->loadRole();
        }
        \Yii::$app->response->statusCode = 403;
        return "You haven't permissions to view this chat";
    }

    public function actionCreate()
    {
        $model = new ChatResource();
        $postData = \Yii::$app->request->post();
        $model->title = $postData['title'] ?? null;
        $model->description = $postData['description'] ?? null;
        if ($model->save()) {
            $role = \Yii::$app->authManager->getRole('chatAdmin');
            $model->addUser(\Yii::$app->user->identity, $role->name);
            return $model->loadPermissions()->loadLastMessage();
        }
        \Yii::$app->response->statusCode = 422;
        return ['errors' => $model->errors];
    }

    public function actionSendMessage($id)
    {

        /** @var User $user */
        if ( !\Yii::$app->user->can('sendMessage', ['chat' => $id])) {
            $this->response->statusCode = 403;
            return ['error' => 'You couldn\'t send message to this chat'];
        }
        $model = new MessageResource();
        $model->chat_id = $id;
        $model->text = \Yii::$app->request->post('text');
        if ($model->save()) {
            return $model;
        }
        \Yii::$app->response->statusCode = 500;
        return ['errors' => $model->errors];
    }

    public function actionMessages($chatId, $limit = 20, $offset = 0)
    {
        /** @var  $user User */
        $user = \Yii::$app->user->identity;
        $chat = $user->getChats(true)->andWhere(['id' => $chatId])->one();

        if ($chat === null) {
            \Yii::$app->response->statusCode = 403;
            return ["error" => "Rejected"];
        }
        $messages = $chat->getMessages()->orderBy('id')->limit($limit)->offset($offset)->all();
        return $messages;
    }



}