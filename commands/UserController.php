<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionCreate()
    {
        $model = new User();

        // Getting username
        do {
            $model->username = BaseConsole::input('Enter username: ');
        } while ($model::findByUsername($model->username) !== null);

        // Getting email
        do {
            $model->email = BaseConsole::input('Enter email: ');
        } while ($model::findByEmail($model->email) !== null);


        $model->password = \Yii::$app->security->generatePasswordHash(BaseConsole::input('Enter password: '));
        $model->generateAuthKey();
        $model->generateAccessToken();

        if ($model->validate() && $model->save()) {
            $isAdmin = self::parseBooleanInput(BaseConsole::input('Make this user admin[y/N]'));
            $role = \Yii::$app->authManager->getRole($isAdmin ? 'admin' : 'user');

            \Yii::$app->authManager->assign($role, $model->id);

            echo "User successfully created\n";
            return ExitCode::OK;
        }

        echo "Error occurred\nHere are model errors";
        print_r($model->errors);
        return ExitCode::DATAERR;
    }

    protected static function parseBooleanInput($input)
    {
        $trueValues = ['y', 'Y', 'Yes', 'YES', 'yes', 'true', '1'];
        return in_array($input, $trueValues);
    }
}
