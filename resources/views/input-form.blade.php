<div class="col-sm-{{ $input->bootstrap_width() }}">
    <div class="form-group row">
        <label class="col-form-label col-sm-3" title="{{ $input->hover ?? '' }}">{{ $input->title }}</label>
        <div class="col-sm-9">
            {!! $input->form() !!}
        </div>
        @if (isset($input->help))
            <small class="form-text text-muted">{!! $input->help !!}</small>
        @endif
    </div>
</div>
