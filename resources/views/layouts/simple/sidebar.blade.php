<div class="sidebar-wrapper">
	<div>
		<div class="logo-wrapper">
			<a href="{{route('/')}}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo.png')}}" alt=""><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt=""></a>
			<div class="back-btn"><i class="fa fa-angle-left"></i></div>
			<div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
		</div>
		<div class="logo-icon-wrapper"><a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div>
		<nav class="sidebar-main">
			<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
			<div id="sidebar-menu">
				<ul class="sidebar-links" id="simple-bar">
					<li class="back-btn">
						<a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a>
						<div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
					</li>
					<li class="sidebar-main-title">
						<div>
							<h6 class="lan-1">{{ trans('lang.General') }} </h6>
                     		<p class="lan-2">{{ trans('lang.Dashboards,widgets & layout.') }}</p>
						</div>
					</li>
					<li class="sidebar-list">
						<label class="badge badge-success">2</label><a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.Dashboards') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/dashboard' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/dashboard' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='index' ? 'active' : '' }}" href="{{route('index')}}">{{ trans('lang.Default') }}</a></li>
                     		<!-- <li><a class="lan-5 {{ Route::currentRouteName()=='dashboard-02' ? 'active' : '' }}" href="{{route('dashboard-02')}}">{{ trans('lang.Ecommerce') }}</a></li> -->
						</ul>
					</li>
					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
						<li class="sidebar-list">
							<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/hr' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.HR') }}</span>
								<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/hr' ? 'down' : 'right' }}"></i></div>
							</a>
							<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/hr' ? 'block;' : 'none;' }}">
								<li><a class="lan-4 {{ Route::currentRouteName()=='empCards' ? 'active' : '' }}" href="{{route('empCards')}}">{{ trans('lang.Employee') }}</a></li>
								<li><a class="lan-4 {{ Route::currentRouteName()=='empCustCards' ? 'active' : '' }}" href="{{route('empCustCards')}}">{{ trans('lang.assign_client') }}</a></li>
							</ul>
						</li>
					@endIf
					
					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin" || Session::get('emp')->department=="partner") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/crm' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.CRM') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/crm' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/crm' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='clientsCards' ? 'active' : '' }}" href="{{route('clientsCards')}}">{{ trans('lang.Clients') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='investInterestCards' ? 'active' : '' }}" href="{{route('investInterestCards')}}">{{ trans('lang.InvestInterest') }}</a></li>
						</ul>
					</li>
					@endIf

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/augmont' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.augmont') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/augmont' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/augmont' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='augmont.augmontorders' ? 'active' : '' }}" href="{{route('augmont.augmontorders')}}">{{ trans('lang.augmontorders') }}</a></li>
						</ul>
					</li>
					@endIf

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/mf' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.mf') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/mf' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/mf' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='mfsettings' ? 'active' : '' }}" href="{{route('mfsettings')}}">{{ trans('lang.mfsettings') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='schemes' ? 'active' : '' }}" href="{{route('schemes')}}">{{ trans('lang.schemes') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='nav' ? 'active' : '' }}" href="{{route('nav')}}">{{ trans('lang.nav') }}</a></li>
						</ul>
					</li>
					@endif

					@if(Session::get('emp')->department=="incometax" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/itr' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.itr') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/itr' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/itr' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='itrFiled' ? 'active' : '' }}" href="{{route('itrFiled')}}">{{ trans('lang.itrFiled') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='itrHelpdesk' ? 'active' : '' }}" href="{{route('itrHelpdesk')}}">{{ trans('lang.itrHelpdesk') }}</a></li>
						</ul>
					</li>
					@endif

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/empanel' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.empanel') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/empanel' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/empanel' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='getEmpanel' ? 'active' : '' }}" href="{{route('getEmpanel')}}">{{ trans('lang.empanel') }}</a></li>
						</ul>
					</li>
					@endif

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='insurance' ? 'active' : '' }}" href="{{route('insurance')}}">
							<i data-feather="git-pull-request"> </i><span>{{ trans('lang.insurance') }}</span>
						</a>
					</li>
					@endif

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='pms' ? 'active' : '' }}" href="{{route('pms')}}">
							<i data-feather="git-pull-request"> </i><span>{{ trans('lang.pms') }}</span>
						</a>
					</li>
					@endif

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/cms' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.cms') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/cms' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/cms' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='smstemplate' ? 'active' : '' }}" href="{{route('smstemplate')}}">{{ trans('lang.smstemplate') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='emailFormats' ? 'active' : '' }}" href="{{route('emailFormats')}}">{{ trans('lang.emailFormats') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='blogs' ? 'active' : '' }}" href="{{route('blogs')}}">{{ trans('lang.blogs') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='faqs' ? 'active' : '' }}" href="{{route('faqs')}}">{{ trans('lang.faq') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='help' ? 'active' : '' }}" href="{{route('help')}}">{{ trans('lang.help') }}</a></li>
						</ul>
					</li>
					@endif

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/marketing' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.marketing') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/marketing' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/marketing' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='coupons' ? 'active' : '' }}" href="{{route('coupons')}}">{{ trans('lang.coupons') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='events' ? 'active' : '' }}" href="{{route('events')}}">{{ trans('lang.events') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='eventUsers' ? 'active' : '' }}" href="{{route('eventUsers')}}">{{ trans('lang.eventUsers') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='campaigns' ? 'active' : '' }}" href="{{route('campaigns')}}">{{ trans('lang.campaigns') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='bulkemails' ? 'active' : '' }}" href="{{route('bulkemails')}}">{{ trans('lang.bulkemails') }}</a></li>
						</ul>
					</li>
					@endif

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='expertAssistance' ? 'active' : '' }}" href="{{route('expertAssistance')}}">
							<i data-feather="git-pull-request"> </i><span>{{ trans('lang.expertAssistance') }}</span>
						</a>
					</li>
					@endif

					@if(Session::get('emp')->department=="marketing" || Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='subscription' ? 'active' : '' }}" href="{{route('subscription')}}">
							<i data-feather="git-pull-request"> </i><span>{{ trans('lang.subscription') }}</span>
						</a>
					</li>
					@endif

					@if(Session::get('emp')->department=="admin") 
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/cron' ? 'active' : '' }}" href="#"><i data-feather="home"></i><span class="lan-3">{{ trans('lang.cron') }}</span>
							<div class="according-menu"><i class="fa fa-angle-{{request()->route()->getPrefix() == '/cron' ? 'down' : 'right' }}"></i></div>
						</a>
						<ul class="sidebar-submenu" style="display: {{ request()->route()->getPrefix() == '/cron' ? 'block;' : 'none;' }}">
							<li><a class="lan-4 {{ Route::currentRouteName()=='updateNav' ? 'active' : '' }}" href="{{route('updateNav')}}">{{ trans('lang.updateNav') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='camsTransactions' ? 'active' : '' }}" href="{{route('camsTransactions')}}">{{ trans('lang.camsTransactions') }}</a></li>
							<li><a class="lan-4 {{ Route::currentRouteName()=='karvyTransactions' ? 'active' : '' }}" href="{{route('karvyTransactions')}}">{{ trans('lang.karvyTransactions') }}</a></li>
						</ul>
					</li>
					@endif
				</ul>
			</div>
			<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
		</nav>
	</div>
</div>