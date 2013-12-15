{{-- Widget Details --}}
<!-- name -->
<div class="form-group">
	<label class="control-label" for="name">Name</label>
	<div class="controls">
		@if (isset($status)) {{{ $status->name }}} @endif
	</div>
</div>
<!-- ./ name -->

<!-- order -->
<div class="form-group">
	<label class="control-label" for="order">Order</label>
	<div class="controls">
		@if (isset($status)) {{{ $status->order }}} @endif
	</div>
</div>
<!-- ./ order -->

<!-- description -->
<div class="form-group">
	<label class="control-label" for="name">Description</label>
	<div class="controls">
		@if (isset($status)) {{{ $status->description }}} @endif
	</div>
</div>
<!-- ./ description -->

<!-- Form Actions -->
<div class="form-group">
	<div class="controls">
		@if ($action == 'show')
			<a href="{{{ URL::to('status') }}}" class="btn btn-primary">Close</a>
			<a href="{{{ URL::to('status/' . $status->id . '/edit') }}}" class="btn btn-primary">Edit Widget</a>
		@else
			<a href="{{{ URL::to('status') }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-danger">Delete</button>
		@endif
	</div>
</div>
<!-- ./ form actions -->