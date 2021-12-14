@extends('partials/master')

@section('content')
	@include('dashboard/partials/modal')

	<div class="row user-buildings">
		<div class="col-lg-3">
			<div class="user-houses-stats mb-2">
				<div>
					@include('dashboard.partials.mint')
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-dollar-sign',
						'color'		=> 'success',
						'id'		=> 'myCurrency',
						'counter'	=> currency(Auth::user()->currency),
						'title'		=> __('Coins')
					])
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-home',
						'color'		=> 'info',
						'id'		=> 'myBuildings',
						'counter'	=> $totalBuildings,
						'title'		=> __('Total Houses')
					])
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-dollar-sign',
						'color'		=> 'dark',
						'id'		=> 'myDailyClaim',
						'counter'	=> currency(Auth::user()->maxDailyClaim()),
						'title'		=> __('Max Daily Claim')
					])
					@include('dashboard.partials.widget', [
						'icon'		=> 'fe-users',
						'color'		=> 'primary',
						'id'		=> 'myWorkers',
						'counter'	=> Auth::user()->workers(),
						'title'		=> __('Total Citizens')
					])
				</div>
			</div>
		</div>

		<div class="col-lg-9 building-list">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-body">
							<div class="row justify-content-between">
								<div class="col-md-12">
									<form class="d-flex flex-wrap align-items-center">
										<label for="status-select" class="me-2">{{ __('Sort By') }}</label>
										<div class="me-sm-3">
											<select class="form-select my-1 my-md-0" id="status-select" v-model="orderBy" v-on:change="changeOrderBy($event)">
												@foreach ($filters as $filter => $value)
													<option value="{{ $filter }}">{{ __($value['name']) }}</option>
												@endforeach
											</select>
										</div>
									</form>
								</div>
								<!--
								<div class="col-md-4">
									<div class="text-md-end mt-3 mt-md-0">
										<button type="button" class="btn btn-success waves-effect waves-light me-1"><i class="mdi mdi-cog"></i></button>
										<button type="button" class="btn btn-danger waves-effect waves-light me-1"><i class="mdi mdi-plus-circle me-1"></i> Add New</button>
									</div>
								</div>--><!-- end col-->
							</div> <!-- end row -->
						</div>
					</div> <!-- end card -->
				</div><!-- end col-->
			</div>

			@include('dashboard.partials.buildings')
		</div>
	</div>
@endsection

@section('js-libs')
	<script src="https://unpkg.com/vue@3.2.26/dist/vue.global.prod.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment-with-locales.min.js"></script>
@endsection

@section('js')
	<script type="text/javascript">
		const config = {
			mint_cost: {{ config('game.mint_cost') }},
			min_claim: {{ config('game.min_claim') }},
		};
	</script>
@endsection
