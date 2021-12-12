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
											<select class="form-select my-1 my-md-0" id="status-select" v-on:change="changeOrderBy($event)">
												@foreach ($filters as $filter => $value)
													<option value="{{ $filter }}" {{ ($filter == $filterDefault) ? 'selected' : '' }}>{{ __($value['name']) }}</option>
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
	<script src="https://unpkg.com/vue@next"></script>
	<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment-with-locales.min.js"></script>
@endsection
@section('js')
	<script type="text/javascript">
		// momentJs setting prefferences
		moment.locale('{{ config('app.locale') }}');
		moment.relativeTimeThreshold('s', 3); 
		moment.relativeTimeThreshold('ss', 3); // less than 3 seconds hidden the number of seconds
		moment.relativeTimeThreshold('m', 59); // greater than 59 seconds hidden the number of minutes
		moment.relativeTimeThreshold('h', 23); // greater than 20 minutes hidden the number of hours

		const config = {
			trans: {
				confirmTitle: '{{ __("Are you sure?") }}',
				confirmMint: '{{ __("You are about to spend :currency coins to mint a new building!") }}',
				confirm: '{{ __("You are about to spend :currency coins to :action this house!") }}',
				button: '{{ __("Yes, :action it!") }}',
				repair: "{{ __('Repair') }}",
    			upgrade: "{{ __('Upgrade') }}",
				mint: "{{ __('Mint') }}",
				comingSoonTitle: "{{ __('Coming soon!') }}",
				comingSoonMessage: "{{ __('This feature is not available yet') }}",
			},
			mint_cost: {{ config('game.mint_cost') }},
			min_claim: {{ config('game.min_claim') }},
		};
	</script>
@endsection
