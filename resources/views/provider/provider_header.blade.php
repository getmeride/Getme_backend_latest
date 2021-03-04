<div class="pro-dashboard-head">
    <div class="container">
        <a href="{{ route('provider.profile.index') }}" class="pro-head-link <?php if(Route::current()->getName()=='provider.profile.index') { echo "active"; }?>">@lang('provider.profile.profile')</a>
        <a href="{{ route('provider.documents.index') }}" class="pro-head-link <?php if(Route::current()->getName()=='provider.documents.index') { echo "active"; }?>">@lang('provider.profile.manage_documents')</a>
        <a href="{{ route('provider.location.index') }}" class="pro-head-link <?php if(Route::current()->getName()=='provider.location.index') { echo "active"; }?>">@lang('provider.profile.update_location')</a>
        <a href="{{route('provider.wallet.transation')}}" class="pro-head-link <?php if(Route::current()->getName()=='provider.wallet.transation') { echo "active"; }?>">@lang('provider.profile.wallet_transaction')</a>
        @if(Setting::get('CARD')==1)
            <a href="{{ route('provider.cards') }}" class="pro-head-link <?php if(Route::current()->getName()=='provider.cards') { echo "active"; }?>">@lang('provider.card.list')</a>
        @endif    
        <a href="{{ route('provider.transfer') }}" class="pro-head-link <?php if(Route::current()->getName()=='provider.transfer') { echo "active"; }?>">@lang('provider.profile.cashout')</a>
        
        <a href="{{ route('provider.billing') }}" class="pro-head-link <?php if(Route::current()->getName()=='provider.billing') { echo "active"; }?>">Billing Information</a>

    </div>
</div>