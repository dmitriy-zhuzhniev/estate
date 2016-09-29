<?php
/**
 * Created by PhpStorm.
 * User: itdev13
 * Date: 29.09.16
 * Time: 15:55
 */

Для создания нового пароля пройдите по <a href="{{ URL::to("reset/{$sentuser->getUserId()}/{$code}") }}">ссылке</a>