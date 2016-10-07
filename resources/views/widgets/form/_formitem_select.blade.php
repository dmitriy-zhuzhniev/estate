<?php if(! isset($value)) $value = null ?>
<div class="form-group {!! $errors->has($name) ? 'has-error' : null !!}">
    <label for="{!! $name !!}" class="col-sm-2 control-label">{{ $title }}</label>
    <div class="col-sm-10">
        {!! Form::select($name, $options, $value, ['class' => 'form-control']) !!}
        <p class="help-block">{!! $errors->first($name) !!}</p>
    </div>
</div>