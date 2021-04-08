@if($core->have_background_color)
	<div style="background: {{$component->values->background_color}}">
@endif
@if($core->have_container && $component->values->with_container)
    <div class="container mx-auto">
@endif
@if($core->have_paddings)
	<div class="py-24" style="{{$component->getStyle()}}">
@endif
{!! $value !!}
@if($core->have_background_color)
	</div>
@endif
@if($core->have_container && $component->values->with_container)
    </div>
@endif
@if($core->have_paddings)
	</div>
@endif
