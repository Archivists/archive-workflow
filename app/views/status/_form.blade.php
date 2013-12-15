{{-- Create Role Form --}}
@if (isset($status))
{{ Form::open(array('url' => URL::to('status') . '/' . $status->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('status'), 'method' => 'post', 'class' => 'bf')) }}
@endif

	<!-- status name -->
	<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
		<label class="control-label" for="name">Name</label>
		<div class="controls">
			<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($status) ? $status->name : null) }}}" />
			<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ status name -->

	<!-- status order -->
	<div class="form-group {{{ $errors->has('order') ? 'has-error' : '' }}}">
		<label class="control-label" for="order">Order</label>
		<div class="controls">
			<input class="form-control" type="text" name="order" id="order" value="{{{ Input::old('order', isset($status) ? $status->order : null) }}}" />
			<span class="help-block">{{{ $errors->first('order', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ status order -->

	<!-- status description -->
	<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
		<label class="control-label" for="description">Description</label>
		<div class="controls">
			<input class="form-control" type="text" name="description" id="description" value="{{{ Input::old('description', isset($status) ? $status->description : null) }}}" />
			<span class="help-block">{{{ $errors->first('description', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ status name -->

	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			<a href="{{{ URL::to('status') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->
{{ Form::close() }}