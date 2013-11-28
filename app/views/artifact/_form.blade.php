{{-- Create Role Form --}}
@if (isset($artifact))
{{ Form::open(array('url' => URL::to('artifacts') . '/' . $artifact->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('artifacts'), 'method' => 'post', 'class' => 'bf')) }}
@endif

	<!-- artifact name -->
	<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
		<label class="control-label" for="name">Name</label>
		<div class="controls">
			<input class="form-control" type="text" {{{ ((isset($artifact) && $artifact->name === 'admin') ? ' disabled="disabled"' : '') }}} name="name" id="name" value="{{{ Input::old('name', isset($artifact) ? $artifact->name : null) }}}" />
			<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ artifact name -->

	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			<a href="{{{ URL::to('artifacts') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->
{{ Form::close() }}