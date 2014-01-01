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
				<a href="{{{ URL::to('carriers/' . $carrier->id ) }}}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
			</div>
		</div>

		<!-- Notifications -->
	    @include('notifications')
	    <!-- ./ notifications -->
		
		<!-- Status change form -->

		<p>Form will go here</p>

		<!-- ./ Status change form -->

	</div>
</div>
@stop
