{{$font->slug}}
@pushoncebykey("header:".$font->slug)
	<link href="{{$font->url}}" rel="stylesheet">
	<style type="text/css">
		.{{$font->slug}} {
			font-family: '{{$font->title}}'; 
		}
	</style>
@endpushoncebykey