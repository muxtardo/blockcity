@extends('partials/master')
@section('content')
	<div class="row exchange">
		<div class="col-lg-9">
			<div class="card">
				<div class="card-body">
					<h4 class="header-title text-uppercase">{{ __('Account Transactions') }}</h4>
					<p class="sub-header">
						{{ __('List of transactions carried out on your account.') }}
					</p>

					<div class="table-responsive">
						<table class="table mb-0">
							<thead class="table-light">
								<tr>
									<th class="text-center">{{ __('Date') }}</th>
									<th class="text-center">{{ __('Type') }}</th>
									<th class="text-center">{{ __('Amount') }}</th>
									<th class="text-center">{{ __('Status') }}</th>
									<th class="text-center">{{ __('Link') }}</th>
									<th class="text-center">{{ __('Fee') }}</th>
									<th class="text-center">{{ __('Actions') }}</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($transactions as $transaction)
									<tr>
										<th class="text-center" scope="row">{{ $transaction->created_at }}</th>
										<td class="text-center">{{ Str::ucfirst($transaction->type) }}</td>
										<td class="text-center">{{ currency($transaction->amount) }}</td>
										<td class="text-center">
											<b class="text-{{ $transaction->statusColor() }}">{{ __(Str::ucfirst($transaction->status)) }}</b>
										</td>
										<td class="text-center">
											@if ($transaction->status == 'success' && $transaction->txid)
												<a href="https://bscscan.com/tx/{{ $transaction->txid }}" target="_blank">BscScan</a>
											@else
												--
											@endif
										</td>
										<td class="text-center">{{ $transaction->fee ? $transaction->fee . '%' : '--' }}</td>
										<td class="text-center">--</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div> <!-- end table-responsive-->
				</div>
			</div>
			<div class="paginator-center">
				{!! $transactions->render() !!}
			</div>
		</div>
		<div class="col-lg-3">
			<div class="card card-body">
				<h5 class="card-title text-uppercase">{{ __('Exchange Tokens for Coins') }}</h5>
				<p class="card-text text-center"><b>{{ __('Available') }}:</b> <span id="avtkns">0</span> {{ __('Tokens') }}</p>
				<form class="d-grid" id="form-exchange" onsubmit="return false;">
					<div class="form-group">
						<input type="text" name="amount" class="form-control amount mb-2" placeholder="{{ __('Amount') }}" required />
					</div>
					<button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('Exchange') }}</button>
				</form>
			</div>
			<div class="card card-body">
				<h5 class="card-title text-uppercase">{{ __('Withdraw Coins to Tokens') }}</h5>
				<p class="card-text text-center"><b>{{ __('Available') }}:</b> <span id="myCurrency">{{ currency(Auth::user()->currency) }}</span> {{ __('Coins') }}</p>
				<input type="text" class="form-control mb-2" placeholder="{{ __('Amount') }}" required />
				<a href="javascript:void(0);" class="btn btn-primary waves-effect waves-light">{{ __('Withdrawal') }}</a>
			</div>
			<div class="card card-body">
				<h5 class="card-title text-uppercase">{{ __('Verify Transaction') }}</h5>
				<form class="d-grid" id="form-check-hash" onsubmit="return false;">
					<div class="form-group">
						<input type="text" name="hash" class="form-control hash mb-2" placeholder="{{ __('Transaction Hash') }}" required />
					</div>
					<button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('Check this Hash') }}</button>
				</form>
			</div>
		</div>
	</div>
@endsection
