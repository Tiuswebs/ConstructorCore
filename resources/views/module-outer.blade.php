<section id="section-{{$core->id}}">
	@if($core->have_background_color)
		<div style="background: {{$component->values->background_color}}">
	@endif
	@if($core->have_container && $component->values->with_container)
	    <div class="container mx-auto">
	@endif
	@if($core->have_paddings)
		<div class="{{$component->getDefaults()['padding_tailwind']}}" style="{{$component->getPaddingStyle()}}">
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

@php $text_colors = $core->getFieldsByInput('TextColor'); $background_colors = $core->getFieldsByInput('BackgroundColor'); @endphp
@if($text_colors->count() > 0 || $background_colors->count() > 0)
	@push('header')
		<style type="text/css">
			@foreach($text_colors as $color => $value)
				@php $color = str_replace('_', '-', $color); @endphp
				@if(Str::contains($color, 'hover'))
					#section-{{$core->id}} .{{$color}}:hover, #section-{{$core->id}} .{{$color}}:hover a {
						color: {{$value}};
					}
				@else
					#section-{{$core->id}} .{{$color}}, #section-{{$core->id}} .{{$color}} a {
						color: {{$value}};
					}
				@endif
			@endforeach
			@foreach($background_colors as $color => $value)
				@php $color = str_replace('_', '-', $color); @endphp
				@if(Str::contains($color, 'hover'))
					#section-{{$core->id}} .{{$color}}:hover, #section-{{$core->id}} .{{$color}}:hover a {
						background-color: {{$value}};
					}
				@else
					#section-{{$core->id}} .{{$color}}, #section-{{$core->id}} .{{$color}} a {
						background-color: {{$value}};
					}
				@endif
			@endforeach
		</style>
	@endpush
@endif