{{-- Widget Details --}}
<!-- name -->
<div class="form-group">
	<label class="control-label" for="shelf_number">Shelf Number</label>
	<div class="controls">
		@if (isset($carrier)) {{{ $carrier->shelf_number }}} @endif
	</div>
</div>
<!-- ./ name -->

<!-- Form Actions -->
<div class="form-group">
	<div class="controls">
		@if ($action == 'show')
			<a href="{{{ URL::to('carriers') }}}" class="btn btn-primary">Close</a>
			<a href="{{{ URL::to('carriers/' . $carrier->id . '/edit') }}}" class="btn btn-primary">Edit Widget</a>
		@else
			<a href="{{{ URL::to('carriers') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-danger">Delete</button>
		@endif
	</div>
</div>
<!-- ./ form actions -->