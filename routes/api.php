<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\cron\CronController;
use App\Http\Controllers\Augmont\RatesAugmontController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\customer\UserAuthController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\mf\MFController;
use App\Http\Controllers\mf\MFUserController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Augmont\InvoiceAugmontController;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\Users\UsersBankController;
use App\Http\Controllers\itr\ITRController;
use App\Http\Controllers\pms\PMSController;
use App\Http\Controllers\insurance\InsuranceController;
use App\Http\Controllers\Augmont\OrdersAugmontController;
use App\Http\Controllers\Augmont\BuyAugmontController;
use App\Http\Controllers\Augmont\SellAugmontController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\cms\FAQController;
use App\Http\Controllers\cms\BlogsController;
use App\Http\Controllers\marketing\EventController;
use App\Http\Controllers\Nsdl\SignzyController;
use App\Http\Controllers\mf\BSEController;
use App\Http\Controllers\mf\StarMFFileUploadServiceController;
use App\Http\Controllers\Payments\RazorpaySubscriptionController;
use App\Http\Controllers\Payments\RazorpayController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\cms\NewsLetterController;
use App\Mail\OptyEmail;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::domain('goldtest1.optymoney.com')->group(function () {
    Route::get('/home', [UserAuthController::class, 'home']);
    // redirect()->to('http://gold.optymoney.com')->send();
    
});

Route::group(['middleware' => ['api', 'cors']], function ($router) {
    Route::group(['prefix' => 'customer', 'namespace' => 'customer'],function () {
        Route::post('/login', [UserAuthController::class, 'login']);
        Route::post('/simple_signup', [UserAuthController::class, 'simple_signup']);
        Route::post('/simple_signup_step2', [UserAuthController::class, 'simple_signup_step2']);
        Route::post('/career_signup', [UserAuthController::class, 'career_signup']);
        Route::post('/contact', [UsersController::class, 'saveContactUsForm']);
        Route::post('/subscriptionAPI', [SubscriptionController::class, 'subscriptionAPI']);
        
        Route::post('/forgot_sendOTP', [UserAuthController::class, 'forgot_sendOTP']);
        Route::post('/forgot_verifyOTP', [UserAuthController::class, 'forgot_verifyOTP']);
        Route::post('/forgot_submitPassword', [UserAuthController::class, 'forgot_submitPassword']);

        Route::post('requestOTPAPI', [UserAuthController::class, 'requestOTPAPI']);
        Route::post('verifyOTPAPI', [UserAuthController::class, 'verifyOTPAPI']);
        Route::post('createAccountAPI', [UserAuthController::class, 'createAccountAPI']);
        Route::post('validatePanAadhaarAPI', [UserAuthController::class, 'validatePanAadhaarAPI']);
        Route::post('finishSignupAPI', [UserAuthController::class, 'finishSignupAPI']);

        Route::post('interestedForm', [UserAuthController::class, 'interestedForm']);

        Route::post('getCity', [AugmontController::class, 'getCity']); 
        
        Route::post('requestFPOTP', [AuthController::class, 'requestFPOTP'])->name('authentication.requestFPOTP'); 
        Route::post('updateFP', [AuthController::class, 'updateFP'])->name('authentication.updateFP'); 

        Route::group(['middleware' => ['cors', 'jwt.auth']], function() {
            // Route::post('/logout', [UserAuthController::class, 'logout']);
            // Route::get('/tokenCheck', [UserAuthController::class, 'tokenCheck']);
            // Route::get('/user-profile', [UserAuthController::class, 'userProfile']);
        });
        Route::group(['middleware' => ['cors', 'jwt.verify']], function() {
            Route::post('/logout', [UserAuthController::class, 'logout']);
            Route::get('/tokenCheck', [UserAuthController::class, 'tokenCheck']);
            Route::get('/user-profile', [UserAuthController::class, 'userProfile']);
            Route::get('/bankAccountsAPI', [UsersBankController::class, 'allUserBanks']);
            Route::post('/savebankAccountAPI', [UsersBankController::class, 'savebankAccountAPI']);
            Route::get('/getBankAccountByIdAPI/{id}', [UsersBankController::class, 'getBankAccountByIdAPI']);
            Route::get('/deleteBankAccountByIdAPI/{id}', [UsersBankController::class, 'deleteBankAccountByIdAPI']);

            Route::post('/saveBasicInfoAPI', [UserAuthController::class, 'saveBasicInfoAPI']);
            Route::post('/uploadProfileImgAPI', [UserAuthController::class, 'uploadProfileImgAPI']);
            Route::post('/saveBasicInfoFromFoldAPI', [UserAuthController::class, 'saveBasicInfoFromFoldAPI']);
            
            Route::post('/checkPAN', [UserAuthController::class, 'checkPAN']);
            Route::post('/requestOTPForVerification', [UserAuthController::class, 'requestOTPForVerification']);
            Route::post('/activateFamilyMember', [UserAuthController::class, 'activateFamilyMember']);

            Route::get('/familyListAPI', [UserAuthController::class, 'familyListAPI']);

            Route::post('/uploadSignAndCheque', [UserAuthController::class, 'uploadSignAndCheque']);

            Route::get('signzyGetURL', [SignzyController::class, 'signzyGetURL'])->name('signzyGetURL'); 
            
            Route::get('createFatcaTest', [BSEController::class, 'createFatcaTest'])->name('createFatcaTest'); 
            Route::post('createBSE', [BSEController::class, 'createBSE'])->name('createBSE'); 
            Route::get('updateBSE', [BSEController::class, 'updateBSE'])->name('updateBSE'); 

        });

        Route::group(['prefix' => 'augmont', 'namespace' => 'augmont'],function () {
            // Route::get('/getDocs', [PMSController::class, 'getDocs']); 
            Route::group(['middleware' => ['cors', 'jwt.verify']], function() {
                Route::get('/getAugOrdersByUserAPI1', [OrdersAugmontController::class, 'getAugOrdersByUserAPI']); 
                Route::get('/getMetalCountAPI', [AugmontController::class, 'getMetalCountAPI']); 
                Route::post('silverBuy', [BuyAugmontController::class, 'buySilverAugmont'])->name('augmont.buySilverAugmont');
                Route::post('createOrder', [BuyAugmontController::class, 'createOrder'])->name('augmont.createOrder');
                Route::post('saveOrder', [BuyAugmontController::class, 'saveOrder'])->name('augmont.saveOrder');
                Route::post('goldBuy', [BuyAugmontController::class, 'buyGoldAugmont'])->name('augmont.buyGoldAugmont'); 

                Route::get('getInvoiceData/{invoice}', [InvoiceAugmontController::class, 'getInvoiceData'])->name('augmont.getInvoiceData');
                Route::post("silverSell", function(Request $request){
                    $data = ["redirectURL" => "../augmont/sellSilver", "silverGrams" => $request->silverSellGrams, "silverAmount" => $request->silverSellAmount, "silverPrice" => $request->silverSellPrice, "silverGST" => $request->silverSellGST, "silverBlockId" => $request->silverSellBlockId];
                    return $data;
                });
        
                Route::post("goldSell", function(Request $request){
                    $data = ["redirectURL" => "../augmont/sellGold", "goldGrams" => $request->goldSellGrams, "goldAmount" => $request->goldSellAmount, "goldPrice" => $request->goldSellPrice, "goldGST" => $request->goldSellGST, "goldBlockId" => $request->goldSellBlockId];
                    return $data;
                });
                Route::post('saveSellOrder', [SellAugmontController::class, 'saveSellOrder'])->name('augmont.saveSellOrder');

                Route::post('goldSipBuy', [BuyAugmontController::class, 'buySIPGoldAugmont'])->name('augmont.buySIPGoldAugmont'); 
                Route::post('silverSipBuy', [BuyAugmontController::class, 'buySIPSilverAugmont'])->name('augmont.buySIPSilverAugmont'); 

                Route::post('createSipOrder', [BuyAugmontController::class, 'createSipOrder'])->name('augmont.createSipOrder');
                Route::post('saveSipOrder', [BuyAugmontController::class, 'saveSipOrder'])->name('augmont.saveSipOrder');

                Route::get('sipList', [AugmontController::class, 'sipList'])->name('augmont.sipList'); 
                Route::get('api_orders', [AugmontController::class, 'api_orders'])->name('augmont.api_orders');
            });
        });

        Route::prefix('users')->group(function () {
            Route::get('userAccount', [UsersController::class, 'getUserData'])->name('userAccount'); 
            Route::post('createBankAccount', [UsersBankController::class, 'createBankAccount'])->name('users.createBankAccount');
            Route::get('allUserBanks', [UsersBankController::class, 'allUserBanks'])->name('users.allUserBanks');
            Route::post('profileAddressUpdate', [AuthController::class, 'profileAddressUpdate'])->name('profileAddressUpdate');
        });

        Route::post('kycfileUpload', [KycController::class, 'augKYCUpload'])->name('kycfileUpload');
    });

    Route::group(['prefix' => 'itr', 'namespace' => 'itr'],function () {
        Route::group(['middleware' => ['cors', 'jwt.verify']], function() {
            Route::get('/getDocs', [ITRController::class, 'getDocs']); 
        });
    });

    Route::prefix('marketing')->group(function () {
        Route::post('eventByCode', [EventController::class, 'eventByCode'])->name('eventByCode');
        Route::post('eventReg', [EventController::class, 'eventReg'])->name('eventReg');
        Route::post('eventFeedback', [EventController::class, 'eventFeedback'])->name('eventFeedback');
    });

    Route::prefix('blog')->group(function () {
        Route::get('getBlogs', [BlogsController::class, 'getBlogs'])->name('getBlogs');
        Route::get('getBlogsAPI', [BlogsController::class, 'getBlogsAPI'])->name('getBlogsAPI');
        Route::get('getBlogsData/{category}/{start}/{end}', [BlogsController::class, 'getBlogsData'])->name('getBlogsData');
        Route::get('getBlogs/{slug}', [BlogsController::class, 'getBlogDataBySlug'])->name('getBlogDataBySlug');
        Route::get('getBlogsByCategory/{category}', [BlogsController::class, 'getBlogsByCategory'])->name('getBlogsByCategory');
        Route::get('getLatestBlogs/{count}', [BlogsController::class, 'getLatestBlogs'])->name('getLatestBlogs');
        
    });

    Route::group(['prefix' => 'pms', 'namespace' => 'pms'],function () {
        // Route::get('/getDocs', [PMSController::class, 'getDocs']); 
        Route::group(['middleware' => ['cors', 'jwt.verify']], function() {
            Route::get('/getPmsByUserAPI', [PMSController::class, 'getPmsByUserAPI']); 
        });
    });

    Route::group(['prefix' => 'insurance', 'namespace' => 'insurance'],function () {
        // Route::get('/getDocs', [PMSController::class, 'getDocs']); 
        Route::group(['middleware' => ['cors', 'jwt.verify']], function() {
            Route::get('/getInsuranceByUserAPI', [InsuranceController::class, 'getInsuranceByUserAPI']); 
        });
    });

    Route::group(['prefix' => 'augmont', 'namespace' => 'augmont'],function () {
        // Route::get('/getDocs', [PMSController::class, 'getDocs']); 
        Route::group(['middleware' => ['cors', 'jwt.verify']], function() {
            Route::get('/getAugOrdersByUserAPI', [OrdersAugmontController::class, 'getAugOrdersByUserAPI']); 
            Route::get('/getMetalCountAPI', [AugmontController::class, 'getMetalCountAPI']); 
        });
    });

    Route::group(['prefix' => 'mf', 'namespace' => 'mf'],function () {
        // Route::get('/getDocs', [PMSController::class, 'getDocs']); 
        Route::group(['middleware' => ['cors', 'jwt.verify']], function() {
            Route::get('/getPortfolioByUserAPI', [MFController::class, 'getPortfolioByUserAPI']); 
            Route::get('/getPortfolioByUserAPI/{pan}', [MFController::class, 'getPortfolioByUserAPI']); 
            Route::get('/ucc_check', [MFUserController::class, 'ucc_check']); 
            Route::get('/mandateCheck', [MFUserController::class, 'mandateCheck']); 
            Route::post('purchaseLumpsum', [BSEController::class, 'purchaseLumpsum'])->name('purchaseLumpsum'); 
            Route::post('purchaseSip', [BSEController::class, 'purchaseSip'])->name('purchaseSip'); 

            Route::post('uploadMandateScanFile', [StarMFFileUploadServiceController::class, 'uploadMandateScanFile'])->name('uploadMandateScanFile'); 
        });

        Route::post('getSchemeTransactionsById', [MFController::class, 'getSchemeTransactionsById'])->name('getSchemeTransactionsById');
    });

    // Route::post('/login', [UserAuthController::class, 'login']);

    // Route::post('/register', [AuthController::class, 'register']);
    // Route::post('/validateRegistrationOTP', [AuthController::class, 'validateRegistrationOTP']);
    // Route::post('/logout', [AuthController::class, 'logout']);
    // Route::post('/refresh', [AuthController::class, 'refresh']);
    // Route::get('/user-profile', [AuthController::class, 'userProfile']);
    // Route::post('/forgotPassword', [AuthController::class, 'forgotPassword']);
    // Route::post('/validateFPOTP', [AuthController::class, 'validateFPOTP']);
    // Route::post('/updatePassword', [AuthController::class, 'updatePassword']);

    // Route::post('/updateUser', [UserController::class, 'updateUser']);
    // Route::post('/updateUserPic', [UserController::class, 'updateUserPic']);
    // Route::post('/updateUserPassword', [UserController::class, 'updateUserPassword']);

    // Route::get('/getEvents', [EventController::class, 'getEvents']);
    // Route::get('/eventById/{id}', [EventController::class, 'eventById']);
    // Route::post('/getEventsByDistance', [EventController::class, 'getEventsByDistance']);
    // Route::post('/saveEvent', [EventController::class, 'saveEvent']);
    // Route::post('/getEventsByCategory', [EventController::class, 'getEventsByCategory']);

    // Route::get('/getCategories', [CategoryController::class, 'getCategories']);
    // Route::post('/userCategories', [CategoryController::class, 'userCategories']);
    // Route::get('/getCategoriesByUser', [CategoryController::class, 'getCategoriesByUser']);

    // Route::get('/getUsersToInvite', [InviteController::class, 'getUsersToInvite']);
    // Route::post('/sendInviteToUser', [InviteController::class, 'sendInviteToUser']);
    // Route::post('/invitationStatusUpdate', [InviteController::class, 'invitationStatusUpdate']);

    // Route::post('/savePicture', [PictureController::class, 'savePicture']);
    // Route::post('/getPicsByEvent', [PictureController::class, 'getPicsByEvent']);
    // Route::post('/getPicturesByUser', [PictureController::class, 'getPicturesByUser']);

    // Route::post('/saveReview', [ReviewController::class, 'saveReview']);
    // Route::post('/getReviewsByEvent', [ReviewController::class, 'getReviewsByEvent']);
});
Route::prefix('itr')->group(function () {  
    Route::post('/itrRegistration', [ITRController::class, 'itrRegistrationAPI']);
});

Route::prefix('redirects')->group(function () {  
    Route::get('fromsignzy', [SignzyController::class, 'fromsignzy'])->name('fromsignzy'); 
});

Route::prefix('mf')->group(function () {  
    Route::post('/autocompleteSchemes', [MFController::class, 'autocompleteSchemes']);
    Route::post('/getSchemeByName', [MFController::class, 'getSchemeByName']);
    Route::get('getNavOffers', [MFController::class, 'getNavOffers'])->name('getNavOffers');
    Route::post('/getSchemesByOfferAPI', [MFController::class, 'getSchemesByOfferAPI']);
});

// Route::middleware('auth:user-api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('getFaqs', [FAQController::class, 'getFaq'])->name('getFaqs'); 
Route::get('getFaqsByCategory/{category}', [FAQController::class, 'getFaqByCategory'])->name('getFaqByCategory'); 

Route::get('getNewsLetterAPI', [NewsLetterController::class, 'getNewsLetterAPI'])->name('getNewsLetterAPI'); 

// Route::group(['middleware' => ['cors', 'json.response']], function () {
//     Route::post('/login', 'AuthController@login')->name('login.api');
//     Route::post('/register','AuthController@register')->name('register.api');
//     Route::post('/logout', 'AuthController@logout')->name('logout.api');
// });

Route::prefix('cronapi')->group(function () {  
    // Route::view('updateNav', 'cron.updateNav-cards')->name('updateNav');
    // Route::get('getNavUpdates', [CronController::class, 'getNavUpdates'])->name('getNavUpdates'); 
    // Route::post('getNavSchemes', [CronController::class, 'getNavSchemes'])->name('getNavSchemes'); 
    
    
    // Route::view('camsTransactions', 'cron.camsTransactions-cards')->name('camsTransactions');
    // Route::get('getCamsTransactions', [CronController::class, 'getCamsTransactions'])->name('getCamsTransactions'); 

    Route::get('camsEmails', [CronController::class, 'camsEmails'])->name('camsEmails'); 
    Route::get('camsEmails/{fileName}', [CronController::class, 'manualCamsData'])->name('augmont.manualCamsData'); 
    
    Route::get('karvyEmails', [CronController::class, 'karvyEmails'])->name('karvyEmails'); 
    Route::get('karvyEmails/{fileName}', [CronController::class, 'manualKarvyData'])->name('augmont.manualKarvyData'); 
    
    Route::get('schemeMaster', [CronController::class, 'schemeMaster'])->name('schemeMaster'); 
    Route::get('navPrice', [CronController::class, 'amfiData'])->name('navPrice'); 

    // Route::view('karvyTransactions', 'cron.karvyTransactions-cards')->name('karvyTransactions');

    Route::post('karvyEmails/consolidate', [CronController::class, 'manualConsolidateKarvy'])->name('cron.karvyEmails.consolidate'); 
    Route::post('camsEmails/consolidate', [CronController::class, 'manualConsolidateCams'])->name('cron.camsEmails.consolidate'); 

    Route::post('orderResponse', [BuyAugmontController::class, 'orderResponse'])->name('augmont.orderResponse'); 
});

Route::prefix('mf')->group(function () {
    Route::get('getPortfolioByUser/{id}', [MFController::class, 'getPortfolioByUser'])->name('getPortfolioByUser'); 
    Route::post('cams/camsByPAN', [CronController::class, 'updateCamsTransactionByPAN'])->name('updateCamsTransactionByPAN'); 
    Route::post('karvy/karvyByPAN', [CronController::class, 'updateKarvyTransactionByPAN'])->name('updateKarvyTransactionByPAN'); 
    Route::post('cams/camsByPANScheme', [CronController::class, 'updateCamsTransactionByPANScheme'])->name('updateCamsTransactionByPANScheme'); 
    Route::post('karvy/karvyByPANScheme', [CronController::class, 'updateKarvyTransactionByPANScheme'])->name('updateKarvyTransactionByPANScheme'); 
    Route::post('reevaluate', [CronController::class, 'reevaluate'])->name('reevaluate');
    Route::get('getSchemesAPI', [MFController::class, 'getSchemesAPI']); 
});

Route::prefix('augmont')->group(function () {
    Route::post('/home', [AugmontController::class, 'redirectToGold']);
    Route::get('merchantAuth', [AugmontController::class, 'merchantAuth'])->name('augmont.merchantAuth'); 
    Route::get('currentRates', [RatesAugmontController::class, 'currentRates'])->name('augmont.currentRates');
    Route::get('sipRates', [RatesAugmontController::class, 'sipRates'])->name('augmont.sipRates');
    Route::post('getCity', [AugmontController::class, 'getCity'])->name('augmont.getCity'); 
    Route::get('buyInvoice/{invoice}', [InvoiceAugmontController::class, 'buyInvoice'])->name('augmont.buyInvoice'); 
    Route::get('getInvoiceData/{invoice}', [InvoiceAugmontController::class, 'getInvoiceData'])->name('augmont.getInvoiceData'); 
    Route::get('api_orders/{uid}', [AugmontController::class, 'api_orders'])->name('augmont.api_orders'); 
    Route::get('api_sipList/{uid}', [AugmontController::class, 'api_sipList'])->name('augmont.api_sipList'); 
    Route::get('api_metalCount/{uid}', [AugmontController::class, 'api_metalCount'])->name('augmont.api_metalCount'); 
});

Route::prefix('users')->group(function () {
    // Route::view('api_banks/{uid}', 'users.banks')->name('banks');
    // Route::get('api_userAccount/{uid}', [UsersController::class, 'getUserData'])->name('userAccount'); 
    // Route::view('api_user-profile/{uid}', 'users.user-profile')->name('userProfile');
    // Route::post('api_createBankAccount/{uid}', [UsersBankController::class, 'createBankAccount'])->name('users.createBankAccount');
    Route::get('api_allUserBanks/{uid}', [UsersBankController::class, 'api_allUserBanks'])->name('users.api_allUserBanks');
    Route::get('api_userprofile/{uid}', [UsersController::class, 'getUserDataByUID'])->name('users.api_userprofile');
   
    Route::get('getDayWiseNewReg', [UsersController::class, 'getDayWiseNewReg'])->name('users.getDayWiseNewReg');
});

Route::prefix('razorpay')->group(function () {
    Route::post('subscriptionEvents', [RazorpaySubscriptionController::class, 'subscriptionEvents'])->name('augmont.subscriptionEvents');
    Route::post('paymentWebhooks', [RazorpaySubscriptionController::class, 'paymentWebhooks'])->name('augmont.paymentWebhooks');
    Route::post('orderEventsWebhooks', [RazorpaySubscriptionController::class, 'orderEventsWebhooks'])->name('augmont.orderEventsWebhooks');
    Route::post('invoiceEventsWebhooks', [RazorpaySubscriptionController::class, 'invoiceEventsWebhooks'])->name('augmont.invoiceEventsWebhooks');
    Route::post('settlementEventsWebhooks', [RazorpaySubscriptionController::class, 'settlementEventsWebhooks'])->name('augmont.settlementEventsWebhooks');
    Route::post('fund_accountEventsWebhooks', [RazorpaySubscriptionController::class, 'fund_accountEventsWebhooks'])->name('augmont.fund_accountEventsWebhooks');
    Route::post('payoutEventsWebhooks', [RazorpaySubscriptionController::class, 'payoutEventsWebhooks'])->name('augmont.payoutEventsWebhooks');
    Route::post('refundEventsWebhooks', [RazorpaySubscriptionController::class, 'refundEventsWebhooks'])->name('augmont.refundEventsWebhooks');
    Route::post('transferEventsWebhooks', [RazorpaySubscriptionController::class, 'transferEventsWebhooks'])->name('augmont.transferEventsWebhooks');
    Route::post('accountEventsWebhooks', [RazorpaySubscriptionController::class, 'accountEventsWebhooks'])->name('augmont.accountEventsWebhooks');
    Route::post('paymentLinkEventsWebhooks', [RazorpaySubscriptionController::class, 'paymentLinkEventsWebhooks'])->name('augmont.paymentLinkEventsWebhooks');
    
    Route::get('fetchAllCustomers', [RazorpayController::class, 'fetchAllCustomers'])->name('augmont.fetchAllCustomers');
    
});

Route::get('getfile/{uid}/{filename}', [GeneralController::class, 'getfile'])->name('getfile');

Route::get('getDoc/{filename}', [GeneralController::class, 'getDoc'])->name('getDoc');

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    return "All Cache was cleared";
});

Route::get('send-email', function(){
    $mailData = [
        "name" => "Test NAME",
        "dob" => "12/12/1990",
        "to" => "saikrishnaporala@gmail.com"
    ];

    return response()->json(Mail::to("saikrishnaporala@gmail.com")->send(new OptyEmail($mailData)));
});