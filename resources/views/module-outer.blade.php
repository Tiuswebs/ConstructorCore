@if(get_class($core) == 'Tiuswebs\Modules\Elements\Tiuswebs\Content')
	{!! $value !!}
@else
	<section id="section-{{$core->id}}" class="{{$core->getComponentClass()}}">
		@if($core->have_background_color && isset($core->values->background_image) && strlen($core->values->background_image) > 0)
			<div style="background-image: url({{$core->values->background_image}})" class="{{$core->getDefaults()['background_classes']}}">
		@elseif(strlen($core->have_background_color) > 0)
			<div style="background: {{$core->values->background_color}}" class="{{$core->getDefaults()['background_classes']}}">
		@elseif($core->have_background_color)
			<div>
		@endif
		
		@if($core->have_container && $core->values->with_container)
		    <div class="container mx-auto">
		@endif
		@if($core->have_paddings && $core->have_background_color)
			<div class="{{$core->getDefaults()['padding_tailwind']}}" style="{{$core->getPaddingStyle()}}">
		@endif
		{!! $value !!}
		@if($core->have_container && $core->values->with_container)
		    </div>
		@endif
		@if($core->have_paddings && $core->have_background_color)
			</div>
		@endif
		@if($core->have_background_color)
			</div>
		@endif
	</section>

@endif
	
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