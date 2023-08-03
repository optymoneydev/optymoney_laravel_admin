<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authentication;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\crm\ClientController;
use App\Http\Controllers\itr\ITRController;
use App\Http\Controllers\insurance\InsuranceController;
use App\Http\Controllers\pms\PMSController;
use App\Http\Controllers\cms\SMSTemplateController;
use App\Http\Controllers\cms\EmailFormatController;
use App\Http\Controllers\cms\BlogsController;
use App\Http\Controllers\cms\FAQController;
use App\Http\Controllers\cms\HelpController;
use App\Http\Controllers\EA\EAController;
use App\Http\Controllers\ECA\ECAController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\marketing\CouponController;
use App\Http\Controllers\marketing\EventController;
use App\Http\Controllers\marketing\CampaignController;
use App\Http\Controllers\marketing\BulkEmailController;
use App\Http\Controllers\mf\MFController;
use App\Http\Controllers\cron\CronController;
use App\Http\Controllers\Empanel\EmpanelController;

use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\Augmont\RatesAugmontController;
use App\Http\Controllers\Payments\RazorpayController;
use App\Http\Controllers\Payments\RazorpayWebhookController;
use App\Http\Controllers\Payments\RazorpaySubscriptionController;
use App\Http\Controllers\Augmont\BuyAugmontController;
use App\Http\Controllers\Augmont\SellAugmontController;
use App\Http\Controllers\Augmont\InvoiceAugmontController;
use App\Http\Controllers\Augmont\OrdersAugmontController;
use App\Http\Controllers\Augmont\WithdrawAugmontController;
use App\Http\Controllers\Users\UsersBankController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\HTMLPDFController;
use App\Http\Controllers\ContactusController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Nsdl\SignzyController;
use App\Http\Controllers\GeneralController;
use App\Mail\TestAmazonSes;



Route::group(['middleware' => ['AuthCheck']], function() {
    Route::prefix('dashboard')->group(function () {
        Route::view('index', 'dashboard.index', ['articleName' => 'Article 1'])->name('index');
    });

    Route::prefix('hr')->group(function () {
        Route::post('newEmployee', [EmployeeController::class, 'newEmployee'])->name('newEmployee');
        Route::post('empCard/{id}/updateEmployeeForm', [EmployeeController::class, 'updateEmployee'])->name('updateEmployeeForm'); 
        Route::post('empCard/{id}/updatePersonal', [EmployeeController::class, 'updatePersonal'])->name('updatePersonal'); 
        Route::post('empCard/{id}/updateOfficial', [EmployeeController::class, 'updateOfficial'])->name('updateOfficial'); 
        Route::post('empCard/{id}/updateBank', [EmployeeController::class, 'updateBank'])->name('updateBank'); 
        Route::post('empCard/{id}/updateAddress', [EmployeeController::class, 'updateAddress'])->name('updateAddress'); 
        Route::post('empCard/{id}/updateDocuments', [EmployeeController::class, 'updateDocuments'])->name('updateDocuments'); 

        Route::post('addEmpCustMap', [EmployeeController::class, 'addEmpCustMap'])->name('addEmpCustMap');


        Route::get('empCards', [EmployeeController::class, 'getEmpCards'])->name('empCards'); 

        Route::get('empCustCards', [EmployeeController::class, 'getEmpClientCards'])->name('empCustCards');
        Route::view('emp-cust-cards', 'hr.emp-cust-cards')->name('emp-cust-cards');

        Route::view('employee-cards', 'hr.employee-cards')->name('employee-cards');

        Route::get('empCard/{id}/view', [EmployeeController::class, 'getEmpCard'])->name('empCard'); 
        Route::view('employee-profile', 'hr.employee-profile')->name('employee-profile');

        Route::get('empCard/{id}/edit', [EmployeeController::class, 'getEmpCardEdit'])->name('empCardEdit'); 
        Route::view('employee-edit-profile', 'hr.employee-edit-profile')->name('employee-edit-profile');
    });

    Route::prefix('crm')->group(function () {
        Route::get('clientsCards', [ClientController::class, 'getclientsCards'])->name('clientsCards'); 
        Route::view('client-cards', 'crm.client-cards')->name('client-cards');

        Route::get('clientCard/{id}/view', [ClientController::class, 'getClientCard'])->name('clientCard'); 
        Route::view('client-profile', 'crm.client-profile')->name('client-profile');

        Route::get('empclients', [ClientController::class, 'getEmpClientCard'])->name('empClientCard'); 

        Route::post('saveUser', [ClientController::class, 'saveUser'])->name('saveUser');
        
    });

    Route::prefix('itr')->group(function () {
        Route::get('itrFiled', [ITRController::class, 'getitrFiled'])->name('itrFiled'); 
        Route::view('itr-filed-cards', 'itr.itr-filed-cards')->name('itr-filed-cards');

        Route::post('itrVUpload', [ITRController::class, 'itrVUpload'])->name('itrVUpload');

        Route::get('itrHelpdesk', [ITRController::class, 'getItrHelpdesk'])->name('itrHelpdesk'); 
        Route::view('itr-helpdesk-cards', 'itr.itr-helpdesk-cards')->name('itr-helpdesk-cards');

        Route::get('helpdeskCard/{id}/view', [ITRController::class, 'getHelpdeskCard'])->name('helpdeskCard'); 
        Route::view('helpdesk-profile', 'itr.helpdesk-profile')->name('helpdesk-profile');

        Route::post('updateHelpdeskStatus', [ITRController::class, 'updateHelpdeskStatus'])->name('updateHelpdeskStatus');

        Route::get('getfile/{uid}/{folder}/{filename}', [GeneralController::class, 'getfile'])->name('getfile');
    });

    Route::prefix('docs')->group(function () {
        Route::get('getDocsByUser/{id}', [ITRController::class, 'getDocsByUser'])->name('getDocsByUser'); 
    });

    Route::prefix('cms')->group(function () {
        
        Route::view('faqs', 'cms.faq-cards')->name('faqs');
        Route::post('saveFaq', [FAQController::class, 'saveFaq'])->name('saveFaq');
        Route::get('getFaqs', [FAQController::class, 'getFaq'])->name('getFaqs'); 
        Route::post('faqById', [FAQController::class, 'faqById'])->name('faqById');
        Route::post('deletefaqById', [FAQController::class, 'deletefaqById'])->name('deletefaqById');

        Route::view('help', 'cms.help-cards')->name('help');
        Route::post('saveHelp', [HelpController::class, 'saveHelp'])->name('saveHelp');
        Route::get('getHelp', [HelpController::class, 'getHelp'])->name('getHelp'); 
        Route::post('helpById', [HelpController::class, 'helpById'])->name('helpById');
        Route::post('deletehelpById', [HelpController::class, 'deletehelpById'])->name('deletehelpById');

        Route::view('blogs', 'cms.blogs-cards')->name('blogs');
        Route::post('saveBlog', [BlogsController::class, 'saveBlog'])->name('saveBolg');
        Route::get('getBlogs', [BlogsController::class, 'getBlogs'])->name('getBlogs');
        Route::post('blogById', [BlogsController::class, 'blogById'])->name('blogById');
        Route::post('deleteBlogById', [BlogsController::class, 'deleteBlogById'])->name('deleteBlogById');

        Route::view('emailFormats', 'cms.emailFormats-cards')->name('emailFormats');
        Route::post('saveEmailFormats', [EmailFormatController::class, 'saveEmailFormats'])->name('saveEmailFormats');
        Route::get('getEmailFormat', [EmailFormatController::class, 'getEmailFormat'])->name('getEmailFormat'); 
        Route::post('emailFormatById', [EmailFormatController::class, 'emailFormatById'])->name('emailFormatById');
        Route::post('deleteEmailFormatById', [EmailFormatController::class, 'deleteEmailFormatById'])->name('deleteEmailFormatById');

        Route::view('smstemplate', 'cms.smstemplate-cards')->name('smstemplate');
        Route::post('saveSMSTemplate', [SMSTemplateController::class, 'saveSMSTemplate'])->name('saveSMSTemplate');
        Route::get('getSMSTemplates', [SMSTemplateController::class, 'getSMSTemplates'])->name('getSMSTemplates'); 
        Route::post('smsTemplateById', [SMSTemplateController::class, 'smsTemplateById'])->name('smsTemplateById');
        Route::post('deleteSMSById', [SMSTemplateController::class, 'deleteSMSById'])->name('deleteSMSById');
        
    });

    Route::prefix('marketing')->group(function () {
        
        Route::view('coupons', 'marketing.coupons-cards')->name('coupons');
        Route::post('saveCoupon', [CouponController::class, 'saveCoupon'])->name('saveCoupon');
        Route::get('getCoupons', [CouponController::class, 'getCoupons'])->name('getCoupons'); 
        Route::post('couponById', [CouponController::class, 'couponById'])->name('couponById');
        Route::post('deleteCouponById', [CouponController::class, 'deleteCouponById'])->name('deleteCouponById');

        Route::view('events', 'marketing.events-cards')->name('events');
        Route::post('saveEvent', [EventController::class, 'saveEvent'])->name('saveEvent');
        Route::get('getEvents', [EventController::class, 'getEvents'])->name('getEvents'); 
        Route::post('eventById', [EventController::class, 'eventById'])->name('eventById');
        Route::post('deleteEventById', [EventController::class, 'deleteEventById'])->name('deleteEventById');

        Route::view('eventUsers', 'marketing.eventUsers-cards')->name('eventUsers');
        Route::get('getEventUsers', [EventController::class, 'getEventUsers'])->name('getEventUsers'); 

        Route::view('campaigns', 'marketing.campaigns-cards')->name('campaigns');
        Route::get('getCampaigns', [CampaignController::class, 'getCampaigns'])->name('getCampaigns'); 
        
        Route::view('bulkemails', 'marketing.bulkemails-cards')->name('bulkemails');
        Route::post('sendBulkEmails', [BulkEmailController::class, 'sendBulkEmails'])->name('sendBulkEmails');
        
        
    });

    Route::prefix('mf')->group(function () {
        Route::view('schemes', 'mf.schemes-cards')->name('schemes');
        Route::get('getSchemes', [MFController::class, 'getSchemes'])->name('getSchemes'); 
        Route::post('getSchemeById', [MFController::class, 'getSchemeById'])->name('getSchemeById');

        Route::view('nav', 'mf.nav-cards')->name('nav');
        Route::get('getNav', [MFController::class, 'getNav'])->name('getNav'); 
        Route::post('getNavByScheme', [MFController::class, 'getNavByScheme'])->name('getNavByScheme');

        Route::post('getValuesByOptions', [MFController::class, 'getValuesByOptions'])->name('getValuesByOptions');
        Route::get('getNavOffers', [MFController::class, 'getNavOffers'])->name('getNavOffers'); 

        Route::get('getPortfolioByUser/{id}', [MFController::class, 'getPortfolioByUser'])->name('getPortfolioByUser'); 

        Route::post('getTransactionsById', [MFController::class, 'getTransactionsById'])->name('getTransactionsById');
        
        Route::view('mfsettings', 'mf.mfsettings-cards')->name('mfsettings');
        
    });

    Route::get('insurance', [InsuranceController::class, 'getInsurance'])->name('insurance'); 
    Route::get('api_insurance', [InsuranceController::class, 'getInsuranceAPI'])->name('api_insurance'); 
    Route::view('insurance-cards', 'insurance.insurance-cards')->name('insurance-cards');
    Route::post('saveInsurance', [InsuranceController::class, 'insuranceUpload'])->name('saveInsurance');
    Route::post('insuranceById', [InsuranceController::class, 'insuranceById'])->name('insuranceById'); 

    Route::prefix('insurance')->group(function () {
        Route::get('getInsuranceByUser/{id}', [InsuranceController::class, 'getInsuranceByUser'])->name('getInsuranceByUser'); 
    });

    Route::prefix('empanel')->group(function () {
        Route::view('empanel-cards', 'empanel.empanel-cards')->name('empanel-cards');
        Route::get('getEmpanel', [EmpanelController::class, 'getEmpanel'])->name('getEmpanel'); 
    });

    Route::prefix('pms')->group(function () {
        Route::get('getPmsByUser/{id}', [PMSController::class, 'getPmsByUser'])->name('getPmsByUser'); 
    });

    Route::get('pms', [PMSController::class, 'getPMS'])->name('pms'); 
    Route::get('api_pms', [PMSController::class, 'getPmsAPI'])->name('api_pms'); 
    Route::view('pms-cards', 'pms.pms-cards')->name('pms-cards');
    Route::post('savePMS', [PMSController::class, 'pmsUpload'])->name('savePMS');
    Route::post('pmsById', [PMSController::class, 'pmsById'])->name('pmsById'); 
    
    Route::get('getExpertAssistance', [EAController::class, 'getExpertAssistance'])->name('getExpertAssistance'); 
    Route::view('expertAssistance', 'expertAssistance.expertAssistance-cards')->name('expertAssistance');

    Route::get('getSubscription', [SubscriptionController::class, 'getSubscription'])->name('getSubscription'); 
    Route::view('subscription', 'subscription.Subscription-cards')->name('subscription');

    Route::prefix('users')->group(function () {
        Route::view('banks', 'users.banks')->name('banks');
        Route::get('userAccount', [UsersController::class, 'getUserData'])->name('userAccount'); 
        Route::view('user-profile', 'users.user-profile')->name('userProfile');
        Route::post('createBankAccount', [UsersBankController::class, 'createBankAccount'])->name('users.createBankAccount');
        Route::get('allUserBanks', [UsersBankController::class, 'allUserBanks'])->name('users.allUserBanks');
       
        Route::get('getDayWiseNewReg', [UsersController::class, 'getDayWiseNewReg'])->name('users.getDayWiseNewReg');
    });

    Route::get('razorpay', [RazorpayController::class, 'razorpay'])->name('razorpay');
    Route::post('razorpaypayment', [RazorpayController::class, 'payment'])->name('payment');
    
    Route::get('getSpecificPayment/{id}', [RazorpayController::class, 'getSpecificPayment'])->name('getSpecificPayment'); 

    Route::post('kycfileUpload', [KycController::class, 'augKYCUpload'])->name('kycfileUpload');

    Route::prefix('augmont')->group(function () {
        Route::get('merchantAuth', [AugmontController::class, 'merchantAuth'])->name('augmont.merchantAuth'); 
        Route::get('signzyAuth', [SignzyController::class, 'signzyGetURL'])->name('augmont.signzyAuth'); 

        Route::get('currentRates', [RatesAugmontController::class, 'currentRates'])->name('augmont.currentRates');
        Route::get('sipRates', [RatesAugmontController::class, 'sipRates'])->name('augmont.sipRates');
        Route::post('getCity', [AugmontController::class, 'getCity'])->name('augmont.getCity'); 
    
        Route::post('silverBuy', [BuyAugmontController::class, 'buyAugmont'])->name('augmont.buyAugmont'); 
        Route::post('goldBuy', [BuyAugmontController::class, 'buyAugmont'])->name('augmont.buyAugmont'); 
        Route::post('goldSipBuy', [BuyAugmontController::class, 'buySIPAugmont'])->name('augmont.buySIPAugmont'); 
        Route::post('silverSipBuy', [BuyAugmontController::class, 'buySIPAugmont'])->name('augmont.buySIPAugmont'); 

        Route::post("silverSell", function(Request $request){
            $data = ["silverGrams" => $request->silverSellGrams, "silverAmount" => $request->silverSellAmount, "silverPrice" => $request->silverSellPrice, "silverGST" => $request->silverSellGST, "silverBlockId" => $request->silverSellBlockId];
            return View::make('augmont.sellsilver', $data);
        });

        Route::post("goldSell", function(Request $request){
            $data = ["goldGrams" => $request->goldSellGrams, "goldAmount" => $request->goldSellAmount, "goldPrice" => $request->goldSellPrice, "goldGST" => $request->goldSellGST, "goldBlockId" => $request->goldSellBlockId];
            return View::make('augmont.sellgold', $data);
        });

        Route::post('createOrder', [BuyAugmontController::class, 'createOrder'])->name('augmont.createOrder');
        Route::post('createSipOrder', [BuyAugmontController::class, 'createSipOrder'])->name('augmont.createSipOrder');
        Route::post('saveOrder', [BuyAugmontController::class, 'saveOrder'])->name('augmont.saveOrder');
        Route::post('saveSipOrder', [BuyAugmontController::class, 'saveSipOrder'])->name('augmont.saveSipOrder');
        Route::post('saveSellOrder', [SellAugmontController::class, 'saveSellOrder'])->name('augmont.saveSellOrder');
        
        Route::get('orders', [AugmontController::class, 'orders'])->name('augmont.orders'); 
        Route::get('allOrders', [AugmontController::class, 'allOrders'])->name('augmont.allOrders'); 

        Route::get('OrdersById/{id}', [OrdersAugmontController::class, 'OrdersById'])->name('augmont.OrdersById'); 
        Route::get('OrdersByUsers/{id}', [OrdersAugmontController::class, 'OrdersByUsers'])->name('augmont.OrdersByUsers'); 
        Route::get('OrdersByTransactionId/{id}', [OrdersAugmontController::class, 'OrdersByTransactionId'])->name('augmont.OrdersByTransactionId'); 

        Route::get('sipList', [AugmontController::class, 'sipList'])->name('augmont.sipList'); 
        Route::get('metalCount', [AugmontController::class, 'metalCount'])->name('augmont.metalCount'); 
    
        Route::view('razorpayView', 'augmont.razorpayView')->name('augmont.razorpayView');
        Route::view('augmontorders', 'augmont.augmontorders')->name('augmont.augmontorders');
        Route::view('augmontsip', 'augmont.augmontsip')->name('augmont.augmontsip');
        Route::view('kyc', 'augmont.kyc')->name('augmont.kyc');
    
        Route::view('buygold', 'augmont.buygold')->name('augmont.buygold');
        Route::view('buysilver', 'augmont.buysilver')->name('augmont.buysilver');
        Route::view('buysipgold', 'augmont.buysipgold')->name('augmont.buysipgold');
        Route::view('buysipsilver', 'augmont.buysipsilver')->name('augmont.buysipsilver');
        // Route::get("augmontorders", [AugmontController::class, 'orders'])->name('augmont.augmontorders');
        Route::view('augmontReq', 'augmont.augmontReq')->name('augmont.augmontReq');
        
        Route::get('buyInvoice/{invoice}', [InvoiceAugmontController::class, 'buyInvoice'])->name('augmont.buyInvoice'); 
        Route::get('sellInvoice/{invoice}', [InvoiceAugmontController::class, 'sellInvoice'])->name('augmont.sellInvoice'); 
        Route::post('stopSubscription', [RazorpaySubscriptionController::class, 'cancelSubscriptionById'])->name('augmont.stopSubscription'); 
        Route::get('html-pdf', [HTMLPDFController::class, 'htmlPdf'])->name('htmlPdf');

        Route::get('emailInvoice/{invoice}', [InvoiceAugmontController::class, 'emailInvoice'])->name('augmont.buyInvoice'); 

        Route::post('saveAO', [BuyAugmontController::class, 'manualPostOrder'])->name('saveAO');

        Route::get('getDayWiseAugOrders/{day}', [AugmontController::class, 'getDayWiseAugOrders'])->name('augmont.getDayWiseAugOrders');

        Route::get('augmontSellStatusUpdate', [CronController::class, 'augmontSellStatusUpdate'])->name('augmont.augmontSellStatusUpdate');
    });
    
    Route::get('send_user_creation_email', [EmailController::class, 'send_user_creation_email'])->name('send_user_creation_email'); 
});

Route::prefix('cron')->group(function () {  
    Route::view('updateNav', 'cron.updateNav-cards')->name('updateNav');
    Route::get('getNavUpdates', [CronController::class, 'getNavUpdates'])->name('getNavUpdates'); 
    Route::post('getNavSchemes', [CronController::class, 'getNavSchemes'])->name('getNavSchemes'); 
    
    Route::view('camsTransactions', 'cron.camsTransactions-cards')->name('camsTransactions');
    Route::get('getCamsTransactions', [CronController::class, 'getCamsTransactions'])->name('getCamsTransactions'); 

    Route::view('karvyTransactions', 'cron.karvyTransactions-cards')->name('karvyTransactions');
    Route::get('getKarvyTransactions', [CronController::class, 'getKarvyTransactions'])->name('getKarvyTransactions'); 
    
    Route::get('camsEmails', [CronController::class, 'camsEmails'])->name('camsEmails'); 
    Route::get('camsEmails/{fileName}', [CronController::class, 'manualCamsData'])->name('augmont.manualCamsData'); 
    
    Route::get('karvyEmails', [CronController::class, 'karvyEmails'])->name('karvyEmails'); 
    Route::get('karvyEmails/{fileName}', [CronController::class, 'manualKarvyData'])->name('augmont.manualKarvyData'); 
    
    Route::get('schemeMaster', [CronController::class, 'schemeMaster'])->name('schemeMaster'); 
    Route::get('navPrice', [CronController::class, 'amfiData'])->name('navPrice'); 
    Route::get('navBSEPrice', [CronController::class, 'navBSEPrice'])->name('navBSEPrice'); 

    Route::get('updateExistingPrices', [CronController::class, 'updateExistingPrices'])->name('updateExistingPrices'); 
    
    Route::get('karvyEmails/consolidate/{pan}', [CronController::class, 'manualConsolidateKarvy'])->name('cron.karvyEmails.consolidate'); 
    Route::get('camsEmails/consolidate/{pan}', [CronController::class, 'manualConsolidateCams'])->name('cron.camsEmails.consolidate'); 
    
});

Route::post('webhook', 'RazorpayWebhookController@handle');

Route::prefix('augmont')->group(function () {
    Route::post('orderResponse', [BuyAugmontController::class, 'orderResponse'])->name('augmont.orderResponse'); 
    Route::post('sipOrderResponse', [BuyAugmontController::class, 'sipOrderResponse'])->name('augmont.sipOrderResponse'); 

    Route::post('subscriptionCharged', [RazorpaySubscriptionController::class, 'subscriptionCharged'])->name('augmont.subscriptionCharged'); 
    Route::post('subscriptionActivated', [RazorpaySubscriptionController::class, 'subscriptionActivated'])->name('augmont.subscriptionActivated'); 
    Route::post('subscriptionAuthenticated', [RazorpaySubscriptionController::class, 'subscriptionAuthenticated'])->name('augmont.subscriptionAuthenticated'); 
    Route::post('subscriptionStatus', [RazorpaySubscriptionController::class, 'subscriptionStatus'])->name('augmont.subscriptionStatus'); 
    
    Route::post('paymentAuthorized', [RazorpaySubscriptionController::class, 'paymentAuthorized'])->name('augmont.paymentAuthorized'); 
    Route::post('paymentFailed', [RazorpaySubscriptionController::class, 'paymentFailed'])->name('augmont.paymentFailed'); 
    Route::post('paymentCaptured', [RazorpaySubscriptionController::class, 'paymentCaptured'])->name('augmont.paymentCaptured'); 
    Route::post('invoiceEvents', [RazorpaySubscriptionController::class, 'invoiceEvents'])->name('augmont.invoiceEvents'); 
});

Route::prefix('augmont')->group(function () {
    Route::get('merchantAuth', [AugmontController::class, 'merchantAuth'])->name('augmont.merchantAuth'); 
    Route::get('currentRates', [RatesAugmontController::class, 'currentRates'])->name('augmont.currentRates');
    Route::post('getCity', [AugmontController::class, 'getCity'])->name('augmont.getCity'); 
});

Route::prefix('dashboard')->group(function () {
    Route::view('dashboard-02', 'dashboard.dashboard-02')->name('dashboard-02');
    Route::view('index', 'dashboard.index', ['articleName' => 'Article 1'])->name('index');
});

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route('dashboard/index');    
    }
    return redirect()->route('login');
})->name('/');

//Language Change
Route::get('lang/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'de', 'es','fr','pt', 'cn', 'ae'])) {
        abort(400);
    }   
    Session()->put('locale', $locale);
    Session::get('locale');
    return redirect()->back();
})->name('lang');

Route::prefix('authentication')->group(function () {
    Route::view('login', 'authentication.login')->name('login');
    Route::view('sign-up', 'authentication.sign-up')->name('sign-up');
    Route::view('unlock', 'authentication.unlock')->name('unlock');
    Route::view('forget-password', 'authentication.forget-password')->name('forget-password');
    Route::view('reset-password', 'authentication.reset-password')->name('reset-password');
    Route::view('maintenance', 'authentication.maintenance')->name('maintenance');

    Route::post('callbackRequest', [GeneralController::class, 'callbackRequest'])->name('authentication.callbackRequest'); 
    Route::post('post-login', [AuthController::class, 'postLogin'])->name('authentication.login.custom'); 
    Route::post('requestOTP', [AuthController::class, 'requestOTP'])->name('authentication.requestOTP');
    Route::post('verifyOTP', [AuthController::class, 'verifyOTP'])->name('authentication.verifyOTP'); 
    Route::post('createAccount', [AuthController::class, 'createAccount'])->name('authentication.createAccount');
    Route::post('validatePanAadhaar', [AuthController::class, 'validatePanAadhaar'])->name('authentication.validatePanAadhaar');
    Route::post('finishSignup', [AuthController::class, 'finishSignup'])->name('authentication.finishSignup');
    Route::post('profileAddressUpdate', [AuthController::class, 'profileAddressUpdate'])->name('profileAddressUpdate');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('requestFPOTP', [AuthController::class, 'requestFPOTP'])->name('authentication.requestFPOTP'); 
    Route::post('updateFP', [AuthController::class, 'updateFP'])->name('authentication.updateFP'); 
});

// Route::post("silverBuy", function(Request $request){
//     $data = ["silverGrams" => $request->silverGrams, "silverAmount" => $request->silverAmount, "silverPrice" => $request->silverPrice, "silverGST" => $request->silverGST, "silverBlockId" => $request->silverBlockId];
//     return View::make('augmont.buysilver', $data);
// });

Route::post('saveContact', [ContactusController::class, 'saveContact'])->name('saveContact'); 




Route::prefix('widgets')->group(function () {
    Route::view('general-widget', 'widgets.general-widget')->name('general-widget');
    Route::view('chart-widget', 'widgets.chart-widget')->name('chart-widget');
});

Route::prefix('page-layouts')->group(function () {
    Route::view('box-layout', 'page-layout.box-layout')->name('box-layout');    
    Route::view('layout-rtl', 'page-layout.layout-rtl')->name('layout-rtl');    
    Route::view('layout-dark', 'page-layout.layout-dark')->name('layout-dark');    
    Route::view('hide-on-scroll', 'page-layout.hide-on-scroll')->name('hide-on-scroll');    
    Route::view('footer-light', 'page-layout.footer-light')->name('footer-light');    
    Route::view('footer-dark', 'page-layout.footer-dark')->name('footer-dark');    
    Route::view('footer-fixed', 'page-layout.footer-fixed')->name('footer-fixed');    
}); 

Route::prefix('project')->group(function () {
    Route::view('projects', 'project.projects')->name('projects');
    Route::view('projectcreate', 'project.projectcreate')->name('projectcreate');
});

Route::view('file-manager', 'file-manager')->name('file-manager');
Route::view('kanban', 'kanban')->name('kanban');

Route::prefix('ecommerce')->group(function () {
    Route::view('product', 'apps.product')->name('product');
    Route::view('product-page', 'apps.product-page')->name('product-page');
    Route::view('list-products', 'apps.list-products')->name('list-products');
    Route::view('payment-details', 'apps.payment-details')->name('payment-details');
    Route::view('order-history', 'apps.order-history')->name('order-history');
    Route::view('invoice-template', 'apps.invoice-template')->name('invoice-template');
    Route::view('cart', 'apps.cart')->name('cart');
    Route::view('list-wish', 'apps.list-wish')->name('list-wish');
    Route::view('checkout', 'apps.checkout')->name('checkout');
    Route::view('pricing', 'apps.pricing')->name('pricing');
});

Route::prefix('email')->group(function () {
    Route::view('email-application', 'apps.email-application')->name('email-application');
    Route::view('email-compose', 'apps.email-compose')->name('email-compose');
});

Route::prefix('chat')->group(function () {
    Route::view('chat', 'apps.chat')->name('chat');
    Route::view('chat-video', 'apps.chat-video')->name('chat-video');
});

Route::prefix('users')->group(function () {
    Route::view('user-profile', 'apps.user-profile')->name('user-profile');
    Route::view('edit-profile', 'apps.edit-profile')->name('edit-profile');
    Route::view('user-cards', 'apps.user-cards')->name('user-cards');
});


Route::view('bookmark', 'apps.bookmark')->name('bookmark');
Route::view('contacts', 'apps.contacts')->name('contacts');
Route::view('task', 'apps.task')->name('task');
Route::view('calendar-basic', 'apps.calendar-basic')->name('calendar-basic');
Route::view('social-app', 'apps.social-app')->name('social-app');
Route::view('to-do', 'apps.to-do')->name('to-do');
Route::view('search', 'apps.search')->name('search');

Route::prefix('ui-kits')->group(function () {
    Route::view('state-color', 'ui-kits.state-color')->name('state-color');
    Route::view('typography', 'ui-kits.typography')->name('typography');
    Route::view('avatars', 'ui-kits.avatars')->name('avatars');
    Route::view('helper-classes', 'ui-kits.helper-classes')->name('helper-classes');
    Route::view('grid', 'ui-kits.grid')->name('grid');
    Route::view('tag-pills', 'ui-kits.tag-pills')->name('tag-pills');
    Route::view('progress-bar', 'ui-kits.progress-bar')->name('progress-bar');
    Route::view('modal', 'ui-kits.modal')->name('modal');
    Route::view('alert', 'ui-kits.alert')->name('alert');
    Route::view('popover', 'ui-kits.popover')->name('popover');
    Route::view('tooltip', 'ui-kits.tooltip')->name('tooltip');
    Route::view('loader', 'ui-kits.loader')->name('loader');
    Route::view('dropdown', 'ui-kits.dropdown')->name('dropdown');
    Route::view('accordion', 'ui-kits.accordion')->name('accordion');
    Route::view('tab-bootstrap', 'ui-kits.tab-bootstrap')->name('tab-bootstrap');
    Route::view('tab-material', 'ui-kits.tab-material')->name('tab-material');
    Route::view('box-shadow', 'ui-kits.box-shadow')->name('box-shadow');
    Route::view('list', 'ui-kits.list')->name('list');
});

Route::prefix('bonus-ui')->group(function () {
    Route::view('scrollable', 'bonus-ui.scrollable')->name('scrollable');
    Route::view('tree', 'bonus-ui.tree')->name('tree');
    Route::view('bootstrap-notify', 'bonus-ui.bootstrap-notify')->name('bootstrap-notify');
    Route::view('rating', 'bonus-ui.rating')->name('rating');
    Route::view('dropzone', 'bonus-ui.dropzone')->name('dropzone');
    Route::view('tour', 'bonus-ui.tour')->name('tour');
    Route::view('sweet-alert2', 'bonus-ui.sweet-alert2')->name('sweet-alert2');
    Route::view('modal-animated', 'bonus-ui.modal-animated')->name('modal-animated');
    Route::view('owl-carousel', 'bonus-ui.owl-carousel')->name('owl-carousel');
    Route::view('ribbons', 'bonus-ui.ribbons')->name('ribbons');
    Route::view('pagination', 'bonus-ui.pagination')->name('pagination');
    Route::view('breadcrumb', 'bonus-ui.breadcrumb')->name('breadcrumb');
    Route::view('range-slider', 'bonus-ui.range-slider')->name('range-slider');
    Route::view('image-cropper', 'bonus-ui.image-cropper')->name('image-cropper');
    Route::view('sticky', 'bonus-ui.sticky')->name('sticky');
    Route::view('basic-card', 'bonus-ui.basic-card')->name('basic-card');
    Route::view('creative-card', 'bonus-ui.creative-card')->name('creative-card');
    Route::view('tabbed-card', 'bonus-ui.tabbed-card')->name('tabbed-card');
    Route::view('dragable-card', 'bonus-ui.dragable-card')->name('dragable-card');
    Route::view('timeline-v-1', 'bonus-ui.timeline-v-1')->name('timeline-v-1');
    Route::view('timeline-v-2', 'bonus-ui.timeline-v-2')->name('timeline-v-2');
    Route::view('timeline-small', 'bonus-ui.timeline-small')->name('timeline-small');
});

Route::prefix('builders')->group(function () {
    Route::view('form-builder-1', 'builders.form-builder-1')->name('form-builder-1');
    Route::view('form-builder-2', 'builders.form-builder-2')->name('form-builder-2');
    Route::view('pagebuild', 'builders.pagebuild')->name('pagebuild');
    Route::view('button-builder', 'builders.button-builder')->name('button-builder');
});

Route::prefix('animation')->group(function () {
    Route::view('animate', 'animation.animate')->name('animate');
    Route::view('scroll-reval', 'animation.scroll-reval')->name('scroll-reval');
    Route::view('aos', 'animation.aos')->name('aos');
    Route::view('tilt', 'animation.tilt')->name('tilt');
    Route::view('wow', 'animation.wow')->name('wow');
});


Route::prefix('icons')->group(function () {
    Route::view('flag-icon', 'icons.flag-icon')->name('flag-icon');
    Route::view('font-awesome', 'icons.font-awesome')->name('font-awesome');
    Route::view('ico-icon', 'icons.ico-icon')->name('ico-icon');
    Route::view('themify-icon', 'icons.themify-icon')->name('themify-icon');
    Route::view('feather-icon', 'icons.feather-icon')->name('feather-icon');
    Route::view('whether-icon', 'icons.whether-icon')->name('whether-icon');
    Route::view('simple-line-icon', 'icons.simple-line-icon')->name('simple-line-icon');
    Route::view('material-design-icon', 'icons.material-design-icon')->name('material-design-icon');
    Route::view('pe7-icon', 'icons.pe7-icon')->name('pe7-icon');
    Route::view('typicons-icon', 'icons.typicons-icon')->name('typicons-icon');
    Route::view('ionic-icon', 'icons.ionic-icon')->name('ionic-icon');
});

Route::prefix('buttons')->group(function () {
    Route::view('buttons', 'buttons.buttons')->name('buttons');
    Route::view('buttons-flat', 'buttons.buttons-flat')->name('buttons-flat');
    Route::view('buttons-edge', 'buttons.buttons-edge')->name('buttons-edge');
    Route::view('raised-button', 'buttons.raised-button')->name('raised-button');
    Route::view('button-group', 'buttons.button-group')->name('button-group');
});

Route::prefix('forms')->group(function () {
    Route::view('form-validation', 'forms.form-validation')->name('form-validation');
    Route::view('base-input', 'forms.base-input')->name('base-input');
    Route::view('radio-checkbox-control', 'forms.radio-checkbox-control')->name('radio-checkbox-control');
    Route::view('input-group', 'forms.input-group')->name('input-group');
    Route::view('megaoptions', 'forms.megaoptions')->name('megaoptions');
    Route::view('datepicker', 'forms.datepicker')->name('datepicker');
    Route::view('time-picker', 'forms.time-picker')->name('time-picker');
    Route::view('datetimepicker', 'forms.datetimepicker')->name('datetimepicker');
    Route::view('daterangepicker', 'forms.daterangepicker')->name('daterangepicker');
    Route::view('touchspin', 'forms.touchspin')->name('touchspin');
    Route::view('select2', 'forms.select2')->name('select2');
    Route::view('switch', 'forms.switch')->name('switch');
    Route::view('typeahead', 'forms.typeahead')->name('typeahead');
    Route::view('clipboard', 'forms.clipboard')->name('clipboard');
    Route::view('default-form', 'forms.default-form')->name('default-form');
    Route::view('form-wizard', 'forms.form-wizard')->name('form-wizard');
    Route::view('form-wizard-two', 'forms.form-wizard-two')->name('form-wizard-two');
    Route::view('form-wizard-three', 'forms.form-wizard-three')->name('form-wizard-three');
    Route::post('form-wizard-three', function(){
        return redirect()->route('form-wizard-three');
    })->name('form-wizard-three-post');
});

Route::prefix('tables')->group(function () {
    Route::view('bootstrap-basic-table', 'tables.bootstrap-basic-table')->name('bootstrap-basic-table');
    Route::view('bootstrap-sizing-table', 'tables.bootstrap-sizing-table')->name('bootstrap-sizing-table');
    Route::view('bootstrap-border-table', 'tables.bootstrap-border-table')->name('bootstrap-border-table');
    Route::view('bootstrap-styling-table', 'tables.bootstrap-styling-table')->name('bootstrap-styling-table');
    Route::view('table-components', 'tables.table-components')->name('table-components');
    Route::view('datatable-basic-init', 'tables.datatable-basic-init')->name('datatable-basic-init');
    Route::view('datatable-advance', 'tables.datatable-advance')->name('datatable-advance');
    Route::view('datatable-styling', 'tables.datatable-styling')->name('datatable-styling');
    Route::view('datatable-ajax', 'tables.datatable-ajax')->name('datatable-ajax');
    Route::view('datatable-server-side', 'tables.datatable-server-side')->name('datatable-server-side');
    Route::view('datatable-plugin', 'tables.datatable-plugin')->name('datatable-plugin');
    Route::view('datatable-api', 'tables.datatable-api')->name('datatable-api');
    Route::view('datatable-data-source', 'tables.datatable-data-source')->name('datatable-data-source');
    Route::view('datatable-ext-autofill', 'tables.datatable-ext-autofill')->name('datatable-ext-autofill');
    Route::view('datatable-ext-basic-button', 'tables.datatable-ext-basic-button')->name('datatable-ext-basic-button');
    Route::view('datatable-ext-col-reorder', 'tables.datatable-ext-col-reorder')->name('datatable-ext-col-reorder');
    Route::view('datatable-ext-fixed-header', 'tables.datatable-ext-fixed-header')->name('datatable-ext-fixed-header');
    Route::view('datatable-ext-html-5-data-export', 'tables.datatable-ext-html-5-data-export')->name('datatable-ext-html-5-data-export');
    Route::view('datatable-ext-key-table', 'tables.datatable-ext-key-table')->name('datatable-ext-key-table');
    Route::view('datatable-ext-responsive', 'tables.datatable-ext-responsive')->name('datatable-ext-responsive');
    Route::view('datatable-ext-row-reorder', 'tables.datatable-ext-row-reorder')->name('datatable-ext-row-reorder');
    Route::view('datatable-ext-scroller', 'tables.datatable-ext-scroller')->name('datatable-ext-scroller');
    Route::view('jsgrid-table', 'tables.jsgrid-table')->name('jsgrid-table');
});

Route::prefix('charts')->group(function () {
    Route::view('echarts', 'charts.echarts')->name('echarts');
    Route::view('chart-apex', 'charts.chart-apex')->name('chart-apex');
    Route::view('chart-google', 'charts.chart-google')->name('chart-google');
    Route::view('chart-sparkline', 'charts.chart-sparkline')->name('chart-sparkline');
    Route::view('chart-flot', 'charts.chart-flot')->name('chart-flot');
    Route::view('chart-knob', 'charts.chart-knob')->name('chart-knob');
    Route::view('chart-morris', 'charts.chart-morris')->name('chart-morris');
    Route::view('chartjs', 'charts.chartjs')->name('chartjs');
    Route::view('chartist', 'charts.chartist')->name('chartist');
    Route::view('chart-peity', 'charts.chart-peity')->name('chart-peity');
});

Route::view('sample-page', 'pages.sample-page')->name('sample-page');
Route::view('landing-page', 'pages.landing-page')->name('landing-page');
Route::view('home', 'pages.home')->name('home');
Route::view('internationalization', 'pages.internationalization')->name('internationalization');

Route::prefix('starter-kit')->group(function () {
});

Route::prefix('others')->group(function () {
    Route::view('400', 'errors.400')->name('error-400');
    Route::view('401', 'errors.401')->name('error-401');
    Route::view('403', 'errors.403')->name('error-403');
    Route::view('404', 'errors.404')->name('error-404');
    Route::view('500', 'errors.500')->name('error-500');
    Route::view('503', 'errors.503')->name('error-503');
});

Route::prefix('authentication')->group(function () {
    Route::view('login', 'authentication.login')->name('login');
    Route::view('login-one', 'authentication.login-one')->name('login-one');
    Route::view('login-two', 'authentication.login-two')->name('login-two');
    Route::view('login-bs-validation', 'authentication.login-bs-validation')->name('login-bs-validation');
    Route::view('login-bs-tt-validation', 'authentication.login-bs-tt-validation')->name('login-bs-tt-validation');
    Route::view('login-sa-validation', 'authentication.login-sa-validation')->name('login-sa-validation');
    Route::view('sign-up', 'authentication.sign-up')->name('sign-up');
    Route::view('sign-up-one', 'authentication.sign-up-one')->name('sign-up-one');
    Route::view('sign-up-two', 'authentication.sign-up-two')->name('sign-up-two');
    Route::view('sign-up-wizard', 'authentication.sign-up-wizard')->name('sign-up-wizard');
    Route::view('unlock', 'authentication.unlock')->name('unlock');
    Route::view('forget-password', 'authentication.forget-password')->name('forget-password');
    Route::view('reset-password', 'authentication.reset-password')->name('reset-password');
    Route::view('maintenance', 'authentication.maintenance')->name('maintenance');
});

Route::view('comingsoon', 'comingsoon.comingsoon')->name('comingsoon');
Route::view('comingsoon-bg-video', 'comingsoon.comingsoon-bg-video')->name('comingsoon-bg-video');
Route::view('comingsoon-bg-img', 'comingsoon.comingsoon-bg-img')->name('comingsoon-bg-img');

Route::view('basic-template', 'email-templates.basic-template')->name('basic-template');
Route::view('email-header', 'email-templates.email-header')->name('email-header');
Route::view('template-email', 'email-templates.template-email')->name('template-email');
Route::view('template-email-2', 'email-templates.template-email-2')->name('template-email-2');
Route::view('ecommerce-templates', 'email-templates.ecommerce-templates')->name('ecommerce-templates');
Route::view('email-order-success', 'email-templates.email-order-success')->name('email-order-success');


Route::prefix('gallery')->group(function () {
    Route::view('/', 'apps.gallery')->name('gallery');
    Route::view('gallery-with-description', 'apps.gallery-with-description')->name('gallery-with-description');
    Route::view('gallery-masonry', 'apps.gallery-masonry')->name('gallery-masonry');
    Route::view('masonry-gallery-with-disc', 'apps.masonry-gallery-with-disc')->name('masonry-gallery-with-disc');
    Route::view('gallery-hover', 'apps.gallery-hover')->name('gallery-hover');
});

Route::prefix('blog')->group(function () {
    Route::view('/', 'apps.blog')->name('blog');
    Route::view('blog-single', 'apps.blog-single')->name('blog-single');
    Route::view('add-post', 'apps.add-post')->name('add-post');
});


// Route::view('faq', 'apps.faq')->name('faq');

Route::prefix('job-search')->group(function () {
    Route::view('job-cards-view', 'apps.job-cards-view')->name('job-cards-view');
    Route::view('job-list-view', 'apps.job-list-view')->name('job-list-view');
    Route::view('job-details', 'apps.job-details')->name('job-details');
    Route::view('job-apply', 'apps.job-apply')->name('job-apply');
});

Route::prefix('learning')->group(function () {
    Route::view('learning-list-view', 'apps.learning-list-view')->name('learning-list-view');
    Route::view('learning-detailed', 'apps.learning-detailed')->name('learning-detailed');
});

Route::prefix('maps')->group(function () {
    Route::view('map-js', 'apps.map-js')->name('map-js');
    Route::view('vector-map', 'apps.vector-map')->name('vector-map');
});

Route::prefix('editors')->group(function () {
    Route::view('summernote', 'apps.summernote')->name('summernote');
    Route::view('ckeditor', 'apps.ckeditor')->name('ckeditor');
    Route::view('simple-mde', 'apps.simple-mde')->name('simple-mde');
    Route::view('ace-code-editor', 'apps.ace-code-editor')->name('ace-code-editor');
});

Route::view('knowledgebase', 'apps.knowledgebase')->name('knowledgebase');
Route::view('support-ticket', 'apps.support-ticket')->name('support-ticket');
Route::view('landing-page', 'pages.landing-page')->name('landing-page');

Route::prefix('layouts')->group(function () {
    Route::view('compact-sidebar', 'admin_unique_layouts.compact-sidebar'); //default //Dubai
    Route::view('box-layout', 'admin_unique_layouts.box-layout');    //default //New York //
    Route::view('dark-sidebar', 'admin_unique_layouts.dark-sidebar');

    Route::view('default-body', 'admin_unique_layouts.default-body');
    Route::view('compact-wrap', 'admin_unique_layouts.compact-wrap');
    Route::view('enterprice-type', 'admin_unique_layouts.enterprice-type');

    Route::view('compact-small', 'admin_unique_layouts.compact-small');
    Route::view('advance-type', 'admin_unique_layouts.advance-type');
    Route::view('material-layout', 'admin_unique_layouts.material-layout');

    Route::view('color-sidebar', 'admin_unique_layouts.color-sidebar');
    Route::view('material-icon', 'admin_unique_layouts.material-icon');
    Route::view('modern-layout', 'admin_unique_layouts.modern-layout');
});

Route::get('layout-{light}', function($light){
    session()->put('layout', $light);
    session()->get('layout');
    if($light == 'vertical-layout')
    {
        return redirect()->route('pages-vertical-layout');
    }
    return redirect()->route('index');
    return 1;
});
Route::get('/clear-cache', function() {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');