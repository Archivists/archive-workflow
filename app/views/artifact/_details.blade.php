{{-- Atifact Details --}}
<!-- Form Actions -->
<div class="form-group form-inline pull-right">
	
	<div class="controls">
		
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
@else
	<img style="float: left; margin-right: 10px;" src="{{{ URL::to('/artifact/' . $carrier->archive_id . '/thumbnails/' . $artifact->name ) }}}"/>
	@if ($action == 'show')
		<p style="margin:10px;">There is no preview available for this artifact, however, you  may download the artifact by clicking on the 'Download' button to the right.</p>
	@else
		<p style="margin:10px;">Note: You can permenantly remove this artificact by clicking on the 'Delete' button to the right. This action cannot be undone.</p>
	@endif
	
	
@endif

<!-- ./ form actions -->