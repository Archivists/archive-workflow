{{-- Widget Details --}}

<div class="row">
	<div class="col-md-3">

		<div class="form-group">
			<label class="control-label" for="archive_id">Archive Number</label>
			<div class="controls">
				@if (isset($carrier)) {{{ $carrier->archive_id }}} @endif
			</div>
		</div>

		<div class="form-group">
			<label class="control-label" for="shelf_number">Shelf Number</label>
			<div class="controls">
				@if (isset($carrier)) {{{ $carrier->shelf_number }}} @endif
			</div>
		</div>
	</div>

	<div class="col-md-3">

		<div class="form-group">
			<label class="control-label" for="status">Status</label>
			<div class="controls">
				@if ($carrier->status) {{{ $carrier->status->name }}} @endif
			</div>
		</div>

		<div class="form-group">
			<label class="control-label" for="carrier_type">Carrier Type</label>
			<div class="controls">
				@if ($carrier->carrierType) {{{ $carrier->carrierType->name }}} @endif
			</div>
		</div>

	</div>

	<div class="col-md-3">
		
		<div class="form-group">
			<label class="control-label" for="sides">Sides</label>: @if ($carrier) {{{ $carrier->sides }}} @endif
		</div>

		<div class="form-group">
			<label class="control-label" for="created_by">Created By</label>
			<div class="controls">
				@if ($carrier->created_by) {{{ $carrier->created_by }}} @endif
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label" for="updated_by">Updated By</label>
			<div class="controls">
				@if ($carrier->updated_by) {{{ $carrier->updated_by }}} @endif
			</div>
		</div>

	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label class="control-label" for="notes">Notes: </label>@if ($carrier->notes) {{{ $carrier->notes }}} @endif 
		</div>
	</div>
</div>

<!-- Form Actions -->
<div class="form-group">
	<div class="controls">
		@if ($action == 'show')
			<a href="{{{ URL::to('carriers') }}}" class="btn btn-sm btn-primary">Close</a>
			<a href="{{{ URL::to('carriers/' . $carrier->id . '/edit') }}}" class="btn btn-sm btn-primary">Edit</a>
			<a href="{{{ URL::to('carriers/' . $carrier->id . '/status') }}}" class="btn btn-sm btn-success">Change Status</a>
		@else
			<a href="{{{ URL::to('carriers') }}}" class="btn btn-sm btn-primary">Cancel</a>
			<button type="submit" class="btn btn-sm btn-danger">Delete</button>
		@endif
	</div>
</div>
<!-- ./ form actions -->