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
			<a href="{{{ URL::to('categories/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>{{{ Lang::get('category/title.create_a_new_category') }}}</a>
		</div>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="categories" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>{{{ Lang::get('category/table.name') }}}</th>
				<th>{{{ Lang::get('category/table.description') }}}</th>
				<th>{{{ Lang::get('category/table.created_at') }}}</th>
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
			oTable = $('#categories').dataTable( {
				"sDom": "<l><f><r>t<i><p>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sSearch": "Search:",
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('categories/data') }}"
			});

			$("#categories_filter input").addClass("form-control inline-control input-sm");
			$("#categories_length select").addClass("form-control inline-control");
		});
	</script>
@stop