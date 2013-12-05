{{-- Create Role Form --}}
@if (isset($carrier))
{{ Form::open(array('url' => URL::to('carriers') . '/' . $carrier->id, 'method' => 'put', 'class' => 'bf')) }}
@else
{{ Form::open(array('url' => URL::to('carriers'), 'method' => 'post', 'class' => 'bf')) }}
@endif

	<!-- row -->
	<div class="row">

		<!-- column -->
		<div class="col-md-6">
		
			<!-- carrier shelfNumber -->
			<div class="form-group {{{ $errors->has('shelf_number') ? 'has-error' : '' }}}">
				<label class="control-label" for="shelf_number">Shelf Number</label>
				<div class="controls">
					<input class="form-control" type="text" name="shelf_number" id="shelf_number" value="{{{ Input::old('shelf_number', isset($carrier) ? $carrier->shelf_number : null) }}}" />
					<span class="help-block">{{{ $errors->first('shelf_number', ':message') }}}</span>
				</div>
			</div>
			<!-- ./ carrier shelfNumber -->

			<!-- carrier parts -->
			<div class="form-group {{{ $errors->has('parts') ? 'has-error' : '' }}}">
				<label class="control-label" for="parts">Part No.</label>
				<div class="controls">
					<input class="form-control" type="text" name="parts" id="parts" value="{{{ Input::old('parts', isset($carrier) ? $carrier->parts : 0) }}}" />
					<span class="help-block">{{{ $errors->first('parts', ':message') }}}</span>
				</div>
			</div>
			<!-- ./ carrier parts -->

			<!-- carrier sides -->
			<div class="form-group {{{ $errors->has('sides') ? 'has-error' : '' }}}">
				<label class="control-label" for="sides">Sides</label>
				<div class="controls">
					<select class="form-control" name="sides" id="sides">
		            	<option value="">Select sides...</option>
		                @foreach ($sides as $side)
							@if ($action == 'create')
		                		<option value="{{{ $side }}}" {{{ ( $side == $selectedSide) ? ' selected="selected"' : '' }}}>{{{ $side }}}</option>
		                	@else
								<option value="{{{ $side }}}" {{{ ( $side == $carrier->sides ) ? ' selected="selected"' : '' }}}>{{{ $side }}}</option>
							@endif
		                @endforeach
					</select>

					<span class="help-block">
						Select the sides for this carrier. Note: Select '0', if there is only one side to this carrier - including tapes that have been recorded on one side only.
					</span>
				</div>
			</div>
			<!-- ./ carrier sides -->

		</div>
		<!-- ./ column -->

		<!-- column -->
		<div class="col-md-6">

			<!-- carrier type -->
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
			<!-- ./ carrier type --> 

			<!-- carrier notes -->
			<div class="form-group">
				<label class="control-label" for="notes">Notes</label>
				<div class="controls">
					<textarea class="form-control" rows="5" name="notes" id="notes">{{{ Input::old('notes', isset($carrier) ? $carrier->notes : null) }}}</textarea>
				</div>
			</div>
			<!-- ./ carrier notes -->

		</div>
		<!-- ./ column -->

	</div> <!-- ./ row -->

	<!-- Form Actions -->
	<div class="form-group">
		<div class="controls">
			@if ($action == 'create')
	    		<a href="{{{ URL::to('carriers') }}}" class="btn btn-primary">Cancel</a>
	    	@else
				<a href="{{{ URL::to('carriers/' . $carrier->id) }}}" class="btn btn-primary">Cancel</a>
			@endif

			<button type="submit" class="btn btn-success">OK</button>
		</div>
	</div>
	<!-- ./ form actions -->
{{ Form::close() }}