<?php
if(! isset($value)) $value = null;
if(! isset($checked)) $checked = null;
if(! isset($title)) $title = null;
?>
<div class="form-group {!! $errors->has($name) ? 'has-error' : null !!}">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label for="{!! $name !!}">
                {!! Form::checkbox($name, $value, $checked) !!} {{ $title }}
                <p class="help-block">{!! $errors->first($name) !!}</p>
            </label>
        </div>
    </div>
</div>