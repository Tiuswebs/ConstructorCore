<div class="col-sm-{{ $input->bootstrap_width() }} pb-4">
    <div class="row">
        <label class="col-sm-3" title="{{ $input->hover ?? '' }}">
            {{ $input->title }}
            @if (isset($input->help))
                <small class="text-gray-400 block text-xs">{!! $input->help !!}</small>
            @endif
        </label>
        <div class="col-sm-9">
            {!! $input->form() !!}
        </div>
    </div>
    
</div>
