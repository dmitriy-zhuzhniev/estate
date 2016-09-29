<?php
/**
 * Created by PhpStorm.
 * User: itdev13
 * Date: 29.09.16
 * Time: 15:51
 */
<?php
if(! isset($value)) $value = null;
if(! isset($checked)) $checked = null;
if(! isset($title)) $title = null;
?>
<div class="{!! $errors->has($name) ? 'has-error' : null !!}">
    <label for="{!! $name !!}">{{ $title }}</label>
    {!! Form::checkbox($name, $value, $checked) !!}
    <p class="help-block">{!! $errors->first($name) !!}</p>
</div>