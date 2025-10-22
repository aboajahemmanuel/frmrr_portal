<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FlutterwaveController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PasswordChnageController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DisclaimerController;
use App\Http\Controllers\RegulationController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SaveDocController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentRelationshipController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisclaimerController as AdminDisclaimerController;



// Test route for session timeout
Route::get('/keep-alive', function () {
    session()->put('last_activity', now());
    return response()->json(['status' => 'session extended']);
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "Cache cleared successfully!";
});

// Move all unprotected routes to appropriate middleware groups
// The welcome route should be protected by auth middleware
// Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Route::get('/newsalert', [WelcomeController::class, 'newsalert'])->name('newsalert');
// Route::get('/feedback', [WelcomeController::class, 'feedback'])->name('feedback');
// Route::get('/subscribe', [WelcomeController::class, 'subscribe'])->name('subscribe');

// Route::post('/contactpost', [WelcomeController::class, 'feedback_post'])->name('contactpost');

// Route::get('/alert/{id}', [WelcomeController::class, 'alert'])->name('alert');
Route::get('/disclaimer', [DisclaimerController::class, 'show'])->name('disclaimer');
Route::post('/disclaimer/accept', [DisclaimerController::class, 'accept'])->name('disclaimer.accept');
Route::get('/disclaimer/history', [DisclaimerController::class, 'history'])->name('disclaimer.history');

// Test page for session timeout
Route::get('/test-session-public', function() {
    return view('test-session-public');
});

// Test timeout page
Route::get('/test-timeout', function() {
    return view('test-timeout');
});





Auth::routes();

//Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth', 'check.disclaimer.profile']], function () {

    // Add the welcome route here so only authenticated users can access it
    Route::get('/', [WelcomeController::class, 'index'])->name('home');
    Route::get('/home', [WelcomeController::class, 'index']);
    
    // Add feedback and newsalert routes here so only authenticated users can access them
    Route::get('/newsalert', [WelcomeController::class, 'newsalert'])->name('newsalert');
    Route::get('/feedback', [WelcomeController::class, 'feedback'])->name('feedback');
    Route::get('/subscribe', [WelcomeController::class, 'subscribe'])->name('subscribe');
    Route::post('/contactpost', [WelcomeController::class, 'feedback_post'])->name('contactpost');
    Route::get('/alert/{id}', [WelcomeController::class, 'alert'])->name('alert');
    Route::get('/success', [WelcomeController::class, 'success_pay'])->name('success');
    
    Route::get('/document/download/{slug}/{payref}', [BrowseController::class, 'document_download'])->name('document_download');

    Route::get('/document/payment/success', [PaymentController::class, 'payment_success']);

    Route::post('paystore', [PaymentController::class, 'paystore'])->name('paystore');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'updateprofile'])->name('profile.update');

    Route::post('subscribe_payment', [SubscriptionController::class, 'subscribe_payment'])->name('subscribe_payment');;

    // subscribers
    Route::post('/save-document/{id}', [SaveDocController::class, 'saveDocument'])->name('save-document');
    Route::get('/saved-documents', [SaveDocController::class, 'showSavedDocuments'])->name('saved-documents');

    Route::get('/download/{id}', [SaveDocController::class, 'download'])->name('download');
    Route::get('/readDocument/{file}', [SaveDocController::class, 'readDoc'])->name('readDoc');
    
    // Browse routes
    Route::get('/category/{slug}', [BrowseController::class, 'index'])->name('categorypages');
    Route::get('/category/ceased/{slug}', [BrowseController::class, 'ceasedDoc'])->name('ceasedDoc');
    Route::get('/subCategory/{slug}', [BrowseController::class, 'subCategory'])->name('subCategory');
    Route::get('/subCategory/ceased/{slug}', [BrowseController::class, 'subCatceasedDoc'])->name('subCatceasedDoc');
    Route::get('/search-category-ceased/{slug}/{title}', [BrowseController::class, 'search_category_ceased'])->name('search_category_ceased');

    Route::get('/category/{slug}/{name}', [BrowseController::class, 'alphaname'])->name('alphaname');
    Route::get('/categoryname/{slug}/{yname}', [BrowseController::class, 'yearname'])->name('yearname');
    Route::get('/regulation/{slug}', [BrowseController::class, 'regulation'])->name('regulation');
    Route::get('/document/payment/{slug}', [BrowseController::class, 'payment'])->name('payment');
    Route::match(['get', 'post'], '/categorysearchcate/{category_id}', [BrowseController::class, 'categorysearchcate'])->name('categorysearchcate');

    Route::get('/downloads', [BrowseController::class, 'downloads'])->name('downloads');
    Route::post('deletedownlaod/{id}', [BrowseController::class, 'deletedownlaod'])->name('deletedownlaod');

    Route::get('/search_category', [BrowseController::class, 'search_category'])->name('search_category');

    // Search routes
    Route::match(['get', 'post'], '/search', [SearchController::class, 'search'])->name('search');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::match('get', '/categorysearch/{category_slug}/{title}', [SearchController::class, 'categorysearch'])->name('categorysearch');
    Route::get('/search_result', [SearchController::class, 'search_result'])->name('search_result');
    Route::get('/search_result_ceased/{title}', [SearchController::class, 'search_result_ceased'])->name('search_result_ceased');

    Route::get('/searchPost', [SearchController::class, 'searchPost'])->name('searchPost');
    Route::get('/searchPostAdvance', [SearchController::class, 'searchPostAdvance'])->name('searchPostAdvance');
    
    // Document Relationships Routes
    Route::get('regulations/{id}/related-documents', [DocumentRelationshipController::class, 'index'])->name('document-relationships.index');
    Route::post('document-relationships', [DocumentRelationshipController::class, 'store'])->name('document-relationships.store');
    Route::put('document-relationships/{id}', [DocumentRelationshipController::class, 'update'])->name('document-relationships.update');
    Route::delete('document-relationships/{id}', [DocumentRelationshipController::class, 'destroy'])->name('document-relationships.destroy');
    Route::post('document-relationships/link-version-history', [DocumentRelationshipController::class, 'linkVersionHistory'])->name('document-relationships.link-version-history');
    
    // News routes
    Route::get('news', [NewsController::class, 'index']);
    Route::get('add_news', [NewsController::class, 'add_news'])->name('add_news');
    Route::get('edit_news/{id}', [NewsController::class, 'edit_news'])->name('edit_news');
    Route::get('view_news/{id}', [NewsController::class, 'view_news'])->name('view_news');
    Route::post('news/store', [NewsController::class, 'store'])->name('newsStore');
    Route::post('updateNews/{id}', [NewsController::class, 'update'])->name('updateNews');
    Route::post('deleteNews/{id}', [NewsController::class, 'delete'])->name('deleteNews');
    Route::post('news_status/{id}', [NewsController::class, 'news_status'])->name('news_status');

    // Transactions routes
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('subcription_plan', [TransactionController::class, 'subcription_plan'])->name('subcription_plan');
    Route::get('subscribers', [TransactionController::class, 'subscribers'])->name('subscribers');
    Route::post('add_plan', [TransactionController::class, 'addSubcription'])->name('add_plan');
    Route::post('update_plan/{id}', [TransactionController::class, 'updateSubcription'])->name('update_plan');
    Route::post('delete_plan/{id}', [TransactionController::class, 'deleteSubcription'])->name('delete_plan');
    Route::post('/subscriptionstatus/{id}', [TransactionController::class, 'Subcriptionstatus'])->name('Subcriptionstatus');

    // Regulations routes
    Route::get('/regulations/search/{title}', [RegulationController::class, 'search']);
    Route::post('/ceased/{id}', [RegulationController::class, 'statusceased'])->name('statusCeased');
    Route::get('get-categories', [RegulationController::class, 'getCategory'])->name('getCategory');
    Route::get('regulations/create/{selectedValue}', [RegulationController::class, 'redirectToUrl'])->name('redirect');
    Route::get('edit_doc/{slug}', [RegulationController::class, 'edit_doc'])->name('edit_doc');
    Route::get('view_doc/{slug}', [RegulationController::class, 'view_doc'])->name('view_doc');
    Route::post('update_doc/{id}', [RegulationController::class, 'update_doc'])->name('update_doc');
    Route::post('update_index/{id}', [RegulationController::class, 'updateIndexCsv'])->name('update_index');
    
    // Roles and permissions routes for regular users (pending approvals)
    Route::get('/pending-roles', [RoleController::class, 'view'])->name('pending-roles.index');
    Route::get('/approve-roles/{id}', [RoleController::class, 'approve']);
    Route::get('/reject-roles/{id}', [RoleController::class, 'reject']);

    Route::get('/user-updates/{id}', [UserController::class, 'approveUpdate']);
    Route::post('/pending-updates/{id}/reject', [UserController::class, 'rejectUpdate'])->name('pending-updates.reject');
    
    // Admin Disclaimer Management Routes (user view)
    Route::get('disclaimers', [AdminDisclaimerController::class, 'index'])->name('admin.disclaimers.index');
    Route::get('disclaimers/{id}', [AdminDisclaimerController::class, 'show'])->name('admin.disclaimers.show');
    
    // Session Settings Routes (user view)
    Route::get('session-settings', [App\Http\Controllers\Admin\SessionSettingController::class, 'edit'])->name('admin.session-settings.edit');
    
   
});



Route::group(['middleware' => ['auth', 'check.admin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User management
    Route::resource('users', UserController::class);
    Route::post('/userstatus/{id}', [UserController::class, 'userstatus'])->name('userStatus');
    Route::post('/userUpdate/{id}', [UserController::class, 'update'])->name('userUpdate');
    Route::get('/admin_users', [UserController::class, 'Adminusers']);
    Route::get('/deactivated', [UserController::class, 'Deactivated']);
    Route::get('/eternal_users', [UserController::class, 'ExternalUsers']);
    Route::post('/status-user/{id}', [UserController::class, 'statususer'])->name('statusUser');
    Route::post('deleteUser/{id}', [UserController::class, 'destroy'])->name('deleteUser');

    // Role management
    Route::resource('roles', RoleController::class);
    Route::post('deleteRole/{id}', [RoleController::class, 'destroy'])->name('deleteRole');
    Route::post('/rolestatus/{id}', [RoleController::class, 'rolestatus'])->name('rolestatus');
    
    // Permission management
    Route::resource('permissions', PermissionController::class);
    Route::post('deletePermissions/{id}', [PermissionController::class, 'destroy'])->name('deletePermissions');
    Route::post('/permissionstatus/{id}', [PermissionController::class, 'permissionstatus'])->name('permissionstatus');

    // Category management
    Route::resource('categories', CategoryController::class);
    Route::post('deleteCategory/{id}', [CategoryController::class, 'destroy'])->name('deleteCategory');
    Route::post('catupdate/{id}', [CategoryController::class, 'update'])->name('catUpdate');
    Route::post('/catstatus/{id}', [CategoryController::class, 'catstatus'])->name('CatStatus');

    // Subcategory management
    Route::resource('subcategories', SubcategoryController::class);
    Route::post('deletesubcategory/{id}', [SubcategoryController::class, 'destroy'])->name('deletesubcategory');
    Route::post('/subcategorystatus/{id}', [SubcategoryController::class, 'subcategorystatus'])->name('subCatestatus');

    // Group management
    Route::get('groups', [GroupController::class, 'index']);
    Route::post('groups/store', [GroupController::class, 'store'])->name('groupStore');
    Route::post('updateGroup/{id}', [GroupController::class, 'edit'])->name('updateGroup');
    Route::post('deleteGroup/{id}', [GroupController::class, 'delete'])->name('deleteGroup');
    Route::post('/groupstatus/{id}', [GroupController::class, 'groupstatus'])->name('groupStatus');

    // Entity management
    Route::resource('entities', EntityController::class);
    Route::post('deleteEntity/{id}', [EntityController::class, 'destroy'])->name('deleteEntity');
    Route::post('/entitystatus/{id}', [EntityController::class, 'entitystatus'])->name('EntityStatus');
    Route::post('entityUpdate/{id}', [EntityController::class, 'update'])->name('entityUpdate');

    // Regulation management
    Route::resource('regulations', RegulationController::class);
    Route::get('get-categories', [RegulationController::class, 'getCategory'])->name('getCategory');
    Route::post('regulations/{id}', [RegulationController::class, 'destroy'])->name('deleteRegulations');
    Route::get('regulations/create/{selectedValue}', [RegulationController::class, 'redirectToUrl'])->name('redirect');
    Route::post('/regstatus/{id}', [RegulationController::class, 'regstatus'])->name('RegStatus');
    Route::get('edit_doc/{slug}', [RegulationController::class, 'edit_doc'])->name('edit_doc');
    Route::get('view_doc/{slug}', [RegulationController::class, 'view_doc'])->name('view_doc');
    Route::post('update_doc/{id}', [RegulationController::class, 'update_doc'])->name('update_doc');
    Route::post('update_index/{id}', [RegulationController::class, 'updateIndexCsv'])->name('update_index');
    Route::post('/ceased/{id}', [RegulationController::class, 'statusceased'])->name('statusCeased');
    
    // AJAX routes for related documents feature
    Route::get('get-subcategories/{categoryId}', [RegulationController::class, 'getSubcategoriesByCategory']);
    Route::get('get-related-documents/{categoryId}', [RegulationController::class, 'getRelatedDocumentsByCategory']);

    // Transaction management
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('subcription_plan', [TransactionController::class, 'subcription_plan'])->name('subcription_plan');
    Route::get('subscribers', [TransactionController::class, 'subscribers'])->name('subscribers');
    Route::post('add_plan', [TransactionController::class, 'addSubcription'])->name('add_plan');
    Route::post('update_plan/{id}', [TransactionController::class, 'updateSubcription'])->name('update_plan');
    Route::post('delete_plan/{id}', [TransactionController::class, 'deleteSubcription'])->name('delete_plan');
    Route::post('/subscriptionstatus/{id}', [TransactionController::class, 'Subcriptionstatus'])->name('Subcriptionstatus');

    // News management
    Route::get('news', [NewsController::class, 'index']);
    Route::get('add_news', [NewsController::class, 'add_news'])->name('add_news');
    Route::get('edit_news/{id}', [NewsController::class, 'edit_news'])->name('edit_news');
    Route::get('view_news/{id}', [NewsController::class, 'view_news'])->name('view_news');
    Route::post('news/store', [NewsController::class, 'store'])->name('newsStore');
    Route::post('updateNews/{id}', [NewsController::class, 'update'])->name('updateNews');
    Route::post('deleteNews/{id}', [NewsController::class, 'delete'])->name('deleteNews');

    // Logs
    Route::get('/logActivity', [LogController::class, 'logActivity']);

    // Pending approvals
    Route::get('/pending-roles', [RoleController::class, 'view'])->name('pending-roles.index');
    Route::get('/approve-roles/{id}', [RoleController::class, 'approve']);
    Route::get('/reject-roles/{id}', [RoleController::class, 'reject']);
    Route::get('/user-updates/{id}', [UserController::class, 'approveUpdate']);
    Route::post('/pending-updates/{id}/reject', [UserController::class, 'rejectUpdate'])->name('pending-updates.reject');
    
    // Session Settings Routes (admin management)
    Route::put('session-settings', [App\Http\Controllers\Admin\SessionSettingController::class, 'update'])->name('admin.session-settings.update');
});

// Public routes that don't require authentication
Route::post('/fogertpassword', [AuthController::class, 'forgetpassword'])->name('forgetpassword');
Route::get('/reset-password/{token}', [AuthController::class, 'resetpassword']);
Route::post('reset-password', [AuthController::class, 'resetpasswordsubmit'])->name('resetpasswordsubmit');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::post('/login/validate', [AuthController::class, 'validateLogin'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/user/verify/{token}', [AuthController::class, 'verifyEmail'])->name('user.verify');

Route::get('/payment/callback', [App\Http\Controllers\PaymentController::class, 'handleGatewayCallback']);

Route::post('/payment', [PaymentController::class, 'documentpay'])->name('docpayment');
Route::post('/charge', [PaymentController::class, 'charge'])->name('charge');

Route::post('/pay', [FlutterwaveController::class, 'initialize'])->name('pay');
// The callback url after a payment
Route::get('/rave/callback', [FlutterwaveController::class, 'callback'])->name('callback');

Route::get('/pdf-preview/{filename}', [PdfController::class, 'preview'])->name('pdf.preview');

Route::post('fetch-category', [CategoryController::class, 'fetchCategory']);
Route::post('fetch-sub', [CategoryController::class, 'fetchSub']);

Route::get('/password/change', [PasswordChnageController::class, 'showChangePasswordForm'])->name('password_change');
Route::post('/password/change', [PasswordChnageController::class, 'changePassword'])->name('password_update');