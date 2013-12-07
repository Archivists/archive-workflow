@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="page-header clearfix">
			<h3 class="pull-left">{{{ $title }}}</h3>
			<div class="pull-right">
				<a href="{{{ URL::to('carriers') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
			</div>
		</div>

		<!-- Notifications -->
	    @include('notifications')
	    <!-- ./ notifications -->
	    
		<div class="details">
			@include('carrier/_details', compact('carrier'))
		</div>

		<h4>Supporting Artifacts</h4>

		{{ Form::open(array('url' => URL::to('carriers') . '/' . $carrier->id . '/artifacts', 'files' => true, 'method' => 'post', 'class' => 'form-inline')) }}
			<input type="file" name="artifact" id="artifact">
		  	<div class="form-group {{{ $errors->has('fileName') ? 'has-error' : '' }}}">
				<a id="fileSelect" class="btn btn-sm btn-primary">Select File</a>
				<input class="form-control" type="text" name="fileName" id="fileName" placeholder="Select file to upload." readonly value="" />
				<button type="submit" class="btn btn-sm btn-success">Upload Artifact</button>
			  	<button type="button" name="clear" id="clear" class="btn btn-sm btn-primary">Clear</button>  	
			  	<span class="help-block">{{{ $errors->first('fileName', ':message') }}}</span>
		  	</div>
		  	
		  	<span class="help-block">
				Select a file to associate as an artifact for this carrier. Files can include images, documents, and PDFs.
			</span>
		{{ Form::close() }}

		<table id="artifacts" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th></th>
					<th>{{{ Lang::get('artifact/table.name') }}}</th>
					<th>{{{ Lang::get('artifact/table.created_at') }}}</th>
					<th>{{{ Lang::get('table.actions') }}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
@stop

{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
			oTable = $('#artifacts').dataTable( {
				"sDom": "<r>t",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sSearch": "Search:",
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('carriers/' . $carrier->id . '/artifacts/data') }}"
			});

			$("#roles_filter input").addClass("form-control inline-control input-sm");
			$("#roles_length select").addClass("form-control inline-control");

			$('#fileSelect').on('click', function(s) {
  				// Use the native click() of the file input.
  				$('#artifact').click();
			});

			$('#artifact').on('change', function(s) {
				$('#fileName').val(this.value.replace(/.*(\/|\\)/i, ''));
			});

			$('#clear').on('click', function(s) {
  				// Use the native click() of the file input.
  				$('#fileName').val('');
			});

		});
	</script>
@stop