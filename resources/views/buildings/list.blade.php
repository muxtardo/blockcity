@forelse ($buildings as $building)
    <div class="col-md-6 col-xl-4">
        <div class="card ribbon-box">
            @if ($building->isNew())
                <div class="ribbon-two ribbon-two-blue text-uppercase"><span>{{ __('New') }}</span></div>
            @endif
            <div class="card-body product-box">
                <div class="bg-sky text-center d-flex align-items-center justify-content-center" style="min-height: 340px; position: relative;">
                    {{-- <div class="product-price-tag">
                        <i class="fa fa-star fa-fw text-warning"></i>
                        {{ $building->base->rarity }}
                    </div> --}}
                    {!! $building->getImage() !!}
                </div>

                <div class="product-info">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="font-16 mt-0 sp-line-1">
                                {{ $building->getName() }}
                            </h5>
                            <div class="text-warning mb-2 font-13">
                                {!! Str::repeat('<i class="fa fa-star"></i>', $building->base->rarity) !!}
                            </div>
                            <h5 class="m-0">
                                <span class="text-muted">{{ __('Production') }}:
                                    <span class="{{ $building->status->color }}">{{ __($building->status->name) }}</span>
                                </span>
                            </h5>
                        </div>
                        <div class="col-auto">
                            <div class="product-price-tag">
                                <i class="fa fa-users fa-fw"></i>
                                {{ $building->level }}
                            </div>
                        </div>
                    </div> <!-- end row -->

                    <div class="mt-1 text-center">
                        <div class="button-list row mb-1">
                            @if ($building->needRepair())
                                <button data-bs-toggle="tooltip" title="{{ $building->repairText() }}" type="button" data-id="{{ $building->id }}" class="col btn btn-danger waves-effect waves-light repair">
                                    <b>{{ __('Repair') }}</b>
                                </button>
                            @elseif (!$building->needRepair() && $building->canUpgrade())
                                <button data-bs-toggle="tooltip" title="{{ $building->upgradeText() }}" type="button" data-id="{{ $building->id }}" class="col btn btn-primary waves-effect waves-light upgrade">
                                    <b>{{ __('Upgrade') }}</b>
                                </button>
                            @endif
                            <button type="button" data-id="{{ $building->id }}" class="col btn btn-dark waves-effect waves-light sell">
                                <b>{{ __('Sell') }}</b>
                            </button>
                            <button type="button" data-id="{{ $building->id }}" class="col btn btn-success waves-effect waves-light claim" {{ !$building->canClaim() ? 'disabled' : '' }}>
                                <b>{{ __('Claim') }}</b>
                            </button>
                        </div>
                        <small class="text-muted">
                            <b>{{ __('House Vault') }}:</b>
                            {{ $building->progressClaim() }}% ( {{ currency($building->availableClaim()) }} {{ __('Coins') }} )
                        </small>
                    </div>

                    <div class="progress position-relative" style="height: 20px;">
                        <div class="progress-bar progress-bar-striped {{ $building->progressColor() }} {{ $building->progressClaim() < 100 ? 'progress-bar-animated' : '' }}" style="width: {{ $building->progressClaim() }}%"></div>
                    </div>

                    <div class="row text-center">
                        <div class="col-4">
                            <div class="mt-3">
                                <h4>{{ currency($building->getIncomes()) }}</h4>
                                <p class="mb-0 text-muted text-truncate">{{ __('Daily Claim') }}</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mt-3">
                                <h4>{{ currency($building->last_claim) }}</h4>
                                <p class="mb-0 text-muted text-truncate">{{ __('Last Claim') }}</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mt-3">
                                <h4>{{ currency($building->earnings) }}</h4>
                                <p class="mb-0 text-muted text-truncate">{{ __('Total Claim') }}</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- end product info-->
            </div>
        </div> <!-- end card-->
    </div> <!-- end col-->
@empty
    <div class="alert text-center col alert-danger bg-danger text-white border-0" role="alert">
        {{ __('Looks like you don\'t have any houses yet.') }}
        {{ __('But don\'t worry, you can purchase one at any time by clicking the "New Mint" button!') }}
    </div>
@endforelse