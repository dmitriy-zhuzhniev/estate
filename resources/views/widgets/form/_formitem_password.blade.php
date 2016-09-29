<?php
/**
 * Created by PhpStorm.
 * User: itdev13
 * Date: 29.09.16
 * Time: 15:50
 */
<?php if(! isset($value)) $value = null ?>
<div class="{!! $errors->has($name) ? 'has-error' : null !!}">
    <label for="{!! $name !!}">{{ $title }}</label>
    {!! Form::password($name, $value, array('placeholder' =>  $placeholder )) !!}
    <p class="help-block">{!! $errors->first($name) !!}</p>
</div>