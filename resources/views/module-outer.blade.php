@if(get_class($core) == 'Tiuswebs\Modules\Elements\Tiuswebs\Content')
	{!! $value !!}
@else
	<section 
		id="{{$core->id}}" 
		class="{{$core->getComponentClass()}}"
		style="{{ strlen($core->have_background_color) > 0 ? "background-color: {$core->values?->background_color}" : '' }}; ">

		<div style="
			max-width: 1920px;
			margin: 0 auto;
			{{ isset($core->values->background_image) ? "background-image: url({$core->values->background_image});" : '' }}
		" @class([ $core->values->background_classes ?? '' => strlen($core?->have_background_color) > 0 ])>

		@if($core->have_container && $core->values->with_container)
		    <div class="container mx-auto">
		@endif
		@if($core->have_paddings && $core->have_background_color)
			<div class="{{$core->getDefaults()['padding_tailwind']}}" style="{{$core->getPaddingStyle()}}">
		@endif
		{!! $value !!}
		@if($core->have_paddings && $core->have_background_color)
			</div>
		@endif
		@if($core->have_container && $core->values->with_container)
			</div>
		@endif
			</div>
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