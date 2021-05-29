<section id="section-{{$core->id}}" class="{{$core->getComponentClass()}}">
	@if($core->have_background_color)
		<div style="background: {{$core->values->background_color}}">
	@endif
	@if($core->have_container && $core->values->with_container)
	    <div class="container mx-auto">
	@endif
	@if($core->have_paddings)
		<div class="{{$core->getDefaults()['padding_tailwind']}}" style="{{$core->getPaddingStyle()}}">
	@endif
	{!! $value !!}
	@if($core->have_background_color)
		</div>
	@endif
	@if($core->have_container && $core->values->with_container)
	    </div>
	@endif
	@if($core->have_paddings)
		</div>
	@endif
</section>

@php $styles = $core->getInlineStyles(); @endphp
@if($styles->count() > 0)
	@push('header')
		<style type="text/css">
			@foreach($styles as $class => $group)
				{!! $class !!} {
					@foreach($group as $item)
					{{$item['attribute']}}: {{$item['value']}};
					@endforeach
				}
			@endforeach
		</style>
	@endpush
@endif