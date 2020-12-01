{{$font->slug}}
@pushonce('header')
	<link href="{{$font->url}}" rel="stylesheet">
	<style type="text/css">
		.{{$font->slug}} {
			font-family: '{{$font->title}}'; 
		}
	</style>
@endpushonce