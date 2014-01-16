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
			<a href="{{{ URL::to('carriers/create') }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus"></span>{{{ Lang::get('carrier/title.create_a_new_carrier') }}}</a>
		</div>

		<div id="status_select" class="pull-right">
			{{ Form::open(array('url' => URL::to('carriers'), 'id' => 'status_form', 'method' => 'get', 'class' => 'form-inline')) }}
				<label class="control-label" for="status">Status: </label>
		        
		            <select class="form-control" name="status" id="status">
		            	<option value="all">All</option>
		                @foreach ($statuses as $status)
		                	<option value="{{{ $status->id }}}" {{{ ( $status->id == $selectedStatus) ? ' selected="selected"' : '' }}}>{{{ $status->name }}}</option>
		                @endforeach
					</select>
		    	
		  	
			{{ Form::close() }}
		</div>
	</div>

	<!-- Notifications -->
    @include('notifications')
    <!-- ./ notifications -->

	<table id="carriers" class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th>{{{ Lang::get('carrier/table.archive_id') }}}</th>
				<th style="width: 120px;">{{{ Lang::get('carrier/table.shelf_number') }}}</th>
				<th style="width: 250px;">{{{ Lang::get('carrier/table.status') }}}</th>
				<th style="width: 70px;">{{{ Lang::get('carrier/table.sides') }}}</th>
				<th style="width: 170px;">{{{ Lang::get('carrier/table.created_at') }}}</th>
				<th style="width: 100px;">{{{ Lang::get('table.actions') }}}</th>
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

			$("#carriers_filter input").addClass("form-control inline-control input-sm");
			$("#carriers_length select").addClass("form-control inline-control");

			$('#status').on('change', function(s) {
				$("form").submit();
			});

		});
	</script>
@stop