@extends('layouts.master')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			{{{ $title }}}

			<div class="pull-right">
				<a href="{{{ URL::to('carriers/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>{{{ Lang::get('carrier/title.create_a_new_carrier') }}}</a>
			</div>
		</h3>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="carriers" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{{ Lang::get('carrier/table.archive_id') }}}</th>
				<th>{{{ Lang::get('carrier/table.shelf_number') }}}</th>
				<th>{{{ Lang::get('carrier/table.parts') }}}</th>
				<th>{{{ Lang::get('carrier/table.sides') }}}</th>
				<th>{{{ Lang::get('carrier/table.created_at') }}}</th>
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
			oTable = $('#carriers').dataTable( {
				"sDom": "<l><f><r>t<i><p>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sSearch": "Search:",
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('carriers/data') }}"
			});

			$("#roles_filter input").addClass("form-control inline-control input-sm");
			$("#roles_length select").addClass("form-control inline-control");
		});
	</script>
@stop