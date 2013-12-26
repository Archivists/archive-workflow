{{-- Atifact Details --}}
<!-- Form Actions -->
<div class="form-group form-inline">
	<div class="controls">
		<img src="{{{ URL::to('/artifact/' . $carrier->archive_id . '/thumbnails/' . $artifact->name ) }}}"/>
		@if ($action == 'show')
			<a href="{{{ URL::to('carriers/' . $carrier->id) }}}" class="btn btn-primary">Close</a>
			<a href="{{{ URL::to('/artifact/' . $carrier->archive_id . '/download/' . $artifact->name ) }}}" class="btn btn-success">Download Artifact</a>
		@else
			<a href="{{{ URL::to('carriers/' . $carrier->id) }}}" class="btn btn-primary">Cancel</a>
			<button type="submit" class="btn btn-danger">Delete</button>
		@endif
	</div>
</div>

@if (preg_match('/^.*\.(jpg|jpeg|png|gif|tiff|tif)$/i', $artifact->name) === 1)
	<img style="width: 100%;" src="{{{ URL::to('/artifact/' . $carrier->archive_id . '/previews/' . $artifact->name ) }}}"/>
@endif

<!-- ./ form actions -->