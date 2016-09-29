<?php
/**
 * Created by PhpStorm.
 * User: itdev13
 * Date: 29.09.16
 * Time: 15:54
 */

Для активации аккаунта пройдите по <a href="{{ URL::to("activate/{$sentuser->getUserId()}/{$code}") }}">ссылке</a>