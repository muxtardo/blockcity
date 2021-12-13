<!-- Footer Start -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                {{ date('Y') }} &copy; <b>{{ config('app.name') }}</b> - {{ __('All rights reserved') }}.
            </div>
            <div class="col-md-6">
                <div class="text-md-end footer-links d-none d-sm-block">
					<a href="{{ config('game.whitepaper', '#') }}" target="_blank">{{ __('Wiki') }}</a>
					<a href="{{ config('game.telegram', '#') }}" target="_blank">{{ __('Telegram') }}</a>
                    <a href="{{ config('game.discord', '#') }}" target="_blank">{{ __('Discord') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end Footer -->
