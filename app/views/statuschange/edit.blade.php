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
			<h3 class="pull-left">{{{ $title . ' - ' . $carrier->archive_id }}} </h3>
			<div class="pull-right">
				<a href="{{{ URL::to('carriers/' . $carrier->id ) }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
			</div>
		</div>

		<!-- Notifications -->
	    @include('notifications')
	    <!-- ./ notifications -->
		
		<!-- Status change form -->

		{{ Form::open(array('url' => URL::to('carriers') . '/' . $carrier->id . '/' . 'status', 'method' => 'put', 'class' => 'bf')) }}

		<!-- row -->
		<div class="row">

			<!-- column -->
			<div class="col-md-6">
				<!-- current status -->
				<div class="form-group bf status_from {{{ $errors->has('status') ? 'has-error' : '' }}}">
			        <label class="control-label" for="status">Current Status</label>
			        <div class="controls">
				    {{{ $carrier->status->name }}}<br/><br/>
				    {{{ $carrier->status->description }}}
					</div>
					<p>&nbsp;</p>
			    </div>
			    <!-- ./ current status -->
			</div>
			<!-- ./ end column -->

			<!-- column -->
			<div class="col-md-6">

				<!-- new status -->
				<div class="form-group bf status_to {{{ $errors->has('status') ? 'has-error' : '' }}}">
			        <label class="control-label" for="status">New Status</label>
			        <div class="controls">
			            <select class="form-control" name="status" id="status">
			            	<option value="">Select status...</option>
			                @foreach ($statuses as $status)
									<option value="{{{ $status->id }}}"{{{ ( $carrier->status && $status->order == $carrier->status->order + 1 ) ? ' selected="selected"' : '' }}}>{{{ $status->name }}}</option>
			                @endforeach
						</select>

						<span class="help-block">
							Select the new status for this carrier.
						</span>
			    	</div>
				</div>
				<!-- ./ new status --> 

			</div>
			<!-- ./ end column -->

		</div>	
		<!-- ./ end row -->

		<!-- Form Actions -->
		<div class="form-group">
			<div class="controls">
				<a href="{{{ URL::to('carriers/' . $carrier->id) }}}" class="btn btn-primary">Cancel</a>
				<button type="submit" class="btn btn-success">OK</button>
			</div>
		</div>

		{{ Form::close() }}

		<!-- ./ Status change form -->

	</div>
</div>
@stop
