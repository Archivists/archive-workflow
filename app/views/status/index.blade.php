@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header clearfix">
		<h3 class="pull-left">{{{ $title }}}</h3>
		<div class="pull-right">
			<a href="{{{ URL::to('status/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>{{{ Lang::get('status/title.create_a_new_status') }}}</a>
		</div>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="status" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{{ Lang::get('status/table.name') }}}</th>
				<th>{{{ Lang::get('status/table.order') }}}</th>
				<th>{{{ Lang::get('status/table.description') }}}</th>
				<th>{{{ Lang::get('status/table.created_at') }}}</th>
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
			oTable = $('#status').dataTable( {
				"sDom": "<l><f><r>t<i><p>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sSearch": "Search:",
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('status/data') }}"
			});

			$("#status_filter input").addClass("form-control inline-control input-sm");
			$("#status_length select").addClass("form-control inline-control");
		});
	</script>
@stop