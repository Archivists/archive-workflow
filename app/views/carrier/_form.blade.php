{{-- Create Role Form --}}
@if (isset($carrier))
{{ Form::open(array('url' => URL::to('carriers') . '/' . $carrier->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('carriers'), 'method' => 'post', 'class' => 'bf')) }}
@endif

	<!-- carrier shelfNumber -->
	<div class="form-group {{{ $errors->has('shelf_number') ? 'has-error' : '' }}}">
		<label class="control-label" for="shelf_number">Shelf Number</label>
		<div class="controls">
			<input class="form-control" type="text" name="shelf_number" id="shelf_number" value="{{{ Input::old('shelf_number', isset($carrier) ? $carrier->shelf_number : null) }}}" />
			<span class="help-block">{{{ $errors->first('shelf_number', ':message') }}}</span>
		</div>
	</div>
	<!-- ./ carrier shelfNumber -->

	<!-- roles -->
	<div class="form-group {{{ $errors->has('carrier_type') ? 'has-error' : '' }}}">
        <label class="control-label" for="carrier_type">Carrier Type</label>
        <div class="controls">

            <select class="form-control" name="carrier_type" id="carrier_type">
            	<option value="">Select a carrier type...</option>
                @foreach ($carrierTypes as $type)
					@if ($action == 'create')
                		<option value="{{{ $type->id }}}" {{{ ( $type->id == $selectedType) ? ' selected="selected"' : '' }}}>{{{ $type->name }}}</option>
                	@else
						<option value="{{{ $type->id }}}"{{{ ( $carrier->carrierType && $type->id == $carrier->carrierType->id ) ? ' selected="selected"' : '' }}}>{{{ $type->name }}}</option>
					@endif
                @endforeach
			</select>

			<span class="help-block">
				Select a carrier type to assign to this carrier.
			</span>
    	</div>
	</div>
	<!-- ./ roles --> 

	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			<a href="{{{ URL::to('carriers') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->
{{ Form::close() }}