{{-- Create Role Form --}}
@if (isset($carrierType))
{{ Form::open(array('url' => URL::to('admin/carrier-types') . '/' . $carrierType->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('admin/carrier-types'), 'method' => 'post', 'class' => 'bf')) }}
@endif

	<!-- carrierType name -->
	<div class="form-group {{{ $errors->has('name') ? 'has-error' : '' }}}">
		<label class="control-label" for="name">Name</label>
		<div class="controls">
			<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($carrierType) ? $carrierType->name : null) }}}" />
			<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ carrierType name -->

	<!-- carrierType description -->
	<div class="form-group {{{ $errors->has('description') ? 'has-error' : '' }}}">
		<label class="control-label" for="description">Description</label>
		<div class="controls">
			<input class="form-control" type="text" name="description" id="description" value="{{{ Input::old('description', isset($carrierType) ? $carrierType->description : null) }}}" />
			<span class="help-block">{{{ $errors->first('description', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ carrierType name -->

	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			<a href="{{{ URL::to('admin/carrier-types') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->
{{ Form::close() }}