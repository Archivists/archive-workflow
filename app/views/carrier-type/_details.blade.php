{{-- Widget Details --}}
<!-- name -->
<div class="form-group">
	<label class="control-label" for="name">Name</label>
	<div class="controls">
		@if (isset($carrierType)) {{{ $carrierType->name }}} @endif
	</div>
</div>
<!-- ./ name -->

<!-- description -->
<div class="form-group">
	<label class="control-label" for="name">Description</label>
	<div class="controls">
		@if (isset($carrierType)) {{{ $carrierType->description }}} @endif
	</div>
</div>
<!-- ./ description -->

<!-- Form Actions -->
<div class="form-group">
	<div class="controls">
		@if ($action == 'show')
			<a href="{{{ URL::to('admin/carrier-types') }}}" class="btn btn-primary">Close</a>
			<a href="{{{ URL::to('admin/carrier-types/' . $carrierType->id . '/edit') }}}" class="btn btn-primary">Edit Category Type</a>
		@else
			<a href="{{{ URL::to('admin/carrier-types') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-danger">Delete</button>
		@endif
	</div>
</div>
<!-- ./ form actions -->