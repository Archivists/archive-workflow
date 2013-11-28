{{-- Widget Details --}}
<!-- name -->
<div class="form-group">
	<label class="control-label" for="name">Name</label>
	<div class="controls">
		@if (isset($artifact)) {{{ $artifact->name }}} @endif
	</div>
</div>
<!-- ./ name -->

<!-- Form Actions -->
<div class="form-group">
	<div class="controls">
		@if ($action == 'show')
			<a href="{{{ URL::to('carriers/' . $carrier_id. '/artifacts') }}}" class="btn btn-primary">Close</a>
			<a href="{{{ URL::to('carriers/' . $carrier_id. '/artifacts/' . $artifact->id . '/edit') }}}" class="btn btn-primary">Edit Artifact</a>
		@else
			<a href="{{{ URL::to('carriers/' . $carrier_id. '/artifacts') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-danger">Delete</button>
		@endif
	</div>
</div>
<!-- ./ form actions -->