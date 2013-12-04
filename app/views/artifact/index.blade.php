@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			{{{ $title }}} for {{{ $carrier->archive_id }}}

			<div class="pull-right">
				<a href="{{{ URL::to('carriers/' . $carrier->id) }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back </a>
				<a href="{{{ URL::to('carriers/' . $carrier->id . '/artifacts/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span> Create New Artifact </a>
			</div>
		</h3>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="artifacts" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{{ Lang::get('artifact/table.name') }}}</th>
				<th>{{{ Lang::get('artifact/table.created_at') }}}</th>
				<th>{{{ Lang::get('table.actions') }}}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
@stop

{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
			oTable = $('#artifacts').dataTable( {
				"sDom": "<r>t<i>",
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
		});
	</script>
@stop