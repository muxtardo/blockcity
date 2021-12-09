<div class="row">
	<div class="col-12">
		<div class="page-title-box">
			<div class="page-title-right">
				@if ($bc)
					<ol class="breadcrumb m-0">
						<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('app.name') }}</a></li>
						@foreach ($bc as $b)
							@if ($b['link'] == '#')
								<li class="breadcrumb-item active">{{ $b['page'] }}</li>
							@else
								<li class="breadcrumb-item">
									<a href="{{ url($b['link']) }}" class="breadcrumb-item">{{ $b['page'] }}</a>
								</li>
							@endif
						@endforeach
					</ol>
				@endif
			</div>
			<h4 class="page-title">{{ $pageTitle }}</h4>
		</div>
	</div>
</div>
