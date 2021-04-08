<section id="section-{{$core->id}}">
	@if($core->have_background_color)
		<div style="background: {{$component->values->background_color}}">
	@endif
	@if($core->have_container && $component->values->with_container)
	    <div class="container mx-auto">
	@endif
	@if($core->have_paddings)
		<div class="{{$component->getDefaults()['padding_tailwind']}}" style="{{$component->getStyle()}}">
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
</section>

@php $text_colors = $core->filterFields('TextColor'); $background_colors = $core->filterFields('BackgroundColor'); @endphp
@if($text_colors->count() > 0 || $background_colors->count() > 0)
	@push('header')
		<style type="text/css">
			@foreach($text_colors as $color => $value)
				@php $color = str_replace('_', '-', $color); @endphp
				#section-{{$core->id}} .{{$color}}, #section-{{$core->id}} .{{$color}} a {
					color: {{$value}};
				}
				#section-{{$core->id}} .hover\:{{$color}}:hover, #section-{{$core->id}} .hover\:{{$color}}:hover a {
					color: {{$value}};
				}
			@endforeach
			@foreach($background_colors as $color => $value)
				@php $color = str_replace('_', '-', $color); @endphp
				#section-{{$core->id}} .{{$color}}, #section-{{$core->id}} .{{$color}} a {
					background-color: {{$value}};
				}
				#section-{{$core->id}} .hover\:{{$color}}:hover, #section-{{$core->id}} .hover\:{{$color}}:hover a {
					background-color: {{$value}};
				}
			@endforeach
		</style>
	@endpush
@endif