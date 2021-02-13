<?php


namespace app\modules\api\controllers;


use app\models\LoginForm;
use app\modules\api\models\SignUpForm;
use app\modules\api\resources\UserResource;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;

class UserController extends \yii\rest\Controller
{


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);


        $behaviors['cors'] = Cors::class;
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'login' => ['post'],
                'sign-up' => ['post'],
            ]
        ];

        $behaviors['authenticator'] = $auth;
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class,
        ];
        $behaviors['authenticator']['except'] = ['login', 'sign-up'];

        return $behaviors;

    }

    public function actionInfo($id)
    {
        $model = UserResource::findOne($id);
        if (!$model) {
            \Yii::$app->response->statusCode = 404;
            return null;
        }
        return $model;
    }

    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post(), '') && $model->login()) {
            return $model->getUser()->toArray(['id', 'username', 'access_token']);
        }

        Yii::$app->response->statusCode = 422;

        return ['errors' => $model->errors];
    }

    public function actionSignUp()
    {
        $model = new SignUpForm();
        if ($model->load(\Yii::$app->request->post(), '') && $model->register()) {
            $userId = $model->getUser()->id;
            \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('user'), $userId);
            \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('chatAdmin'), $userId);
            \Yii::$app->authManager->assign(\Yii::$app->authManager->getRole('chatUser'), $userId);
            return $model->getUser()->toArray(['id', 'username', 'access_token']);
        }

        Yii::$app->response->statusCode = 422;
        return ['errors' => array_merge($model->errors, $model->getUser()->errors)];
    }
}