<div class="card {{$panel->class}}" style="margin-bottom: 20px; {{$panel->style_width()}}">
    @if(strlen($panel->title) > 0)
        <h6 class="card-header">{{$panel->title}}</h6>
    @endif
    <div class="card-body pb-2">
    	<div class="row">
	        @foreach($panel->fields()->where('needs_to_be_on_panel', true) as $field)
	            {!! $field->formHtml(true) !!}
	        @endforeach
        </div>
        @foreach($panel->fields()->where('needs_to_be_on_panel', false) as $field)
            {!! $field->formHtml(true) !!}
        @endforeach
    </div>
</div>
