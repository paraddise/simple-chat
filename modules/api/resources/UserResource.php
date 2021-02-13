<?php

namespace app\modules\api\resources;

class UserResource extends \app\models\User
{

    public function fields()
    {
        return ['id', 'username', 'access_token', 'email'];
    }

}