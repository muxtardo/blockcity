@extends('partials/master')
@section('content')
	<div class="row exchange">
		<div class="col-lg-9">
			@include('exchange/partials/transactions', [
				'transactions'	=> $transactions
			])
		</div>
		<div class="col-lg-3">
			@include('exchange/partials/deposit')
			@include('exchange/partials/withdrawal')
			@include('exchange/partials/consult')
		</div>
	</div>
@endsection
@section('js-libs')
	<script src="{{ asset('assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
	<script src="{{ asset('assets/libs/autonumeric/autoNumeric.min.js') }}"></script>
@endsection
