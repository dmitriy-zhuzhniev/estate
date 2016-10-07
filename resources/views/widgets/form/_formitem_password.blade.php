<?php if(! isset($value)) $value = null ?>
<div class="form-group {!! $errors->has($name) ? 'has-error' : null !!}">
    <label for="{!! $name !!}" class="col-sm-2 control-label">{{ $title }}</label>
    <div class="col-sm-10">
        <input type="password" name="{!! $name !!}" value="{!! $value !!}" placeholder="{!! $placeholder !!}" class="form-control" />
        <p class="help-block">{!! $errors->first($name) !!}</p>
    </div>
</div>