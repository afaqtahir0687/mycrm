<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\AutomationWorkflowController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ContactAccountsController;
use App\Http\Controllers\SalesPipelineController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BulkActionController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CalendarController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('guest');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])
    ->name('register')
    ->middleware('guest');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])
    ->middleware('guest');

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Extension routes - outside auth middleware to allow extension access
// These need to be accessible without authentication but still use sessions
Route::middleware('web')->group(function () {
    Route::options('/data-scraping/receive-extension-data', function() {
        return response('', 200, [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, X-CSRF-TOKEN, X-Requested-With, Accept',
            'Access-Control-Max-Age' => '3600'
        ]);
    });
    Route::get('/data-scraping/csrf-token', [App\Http\Controllers\DataScrapingController::class, 'getCsrfToken'])->name('data-scraping.csrf-token');
    Route::post('/data-scraping/receive-extension-data', [App\Http\Controllers\DataScrapingController::class, 'receiveExtensionData'])->name('data-scraping.receive-extension');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/master-flow', [DashboardController::class, 'masterFlow'])->name('master-flow.index');
    
    Route::get('/data-scraping', [App\Http\Controllers\DataScrapingController::class, 'index'])->name('data-scraping.index');
    Route::post('/data-scraping/scrape', [App\Http\Controllers\DataScrapingController::class, 'scrape'])->name('data-scraping.scrape');
    Route::post('/data-scraping/import', [App\Http\Controllers\DataScrapingController::class, 'importToLeads'])->name('data-scraping.import');
    
    Route::get('/contact-accounts', [ContactAccountsController::class, 'index'])->name('contact-accounts.index');
    Route::get('/sales-pipeline', [SalesPipelineController::class, 'index'])->name('sales-pipeline.index');
    
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{lead}/assign', [LeadController::class, 'assign'])->name('leads.assign');
    Route::resource('accounts', AccountController::class);
    Route::resource('contacts', ContactController::class);
    Route::get('/contacts/get-lead-data/{lead}', [App\Http\Controllers\ContactController::class, 'getLeadData'])->name('contacts.get-lead-data');
    Route::resource('client-registration', App\Http\Controllers\ClientRegistrationController::class);
    Route::get('/client-registration/get-lead-data/{lead}', [App\Http\Controllers\ClientRegistrationController::class, 'getLeadData'])->name('client-registration.get-lead-data');
    Route::resource('deals', DealController::class);
    Route::resource('opportunities', OpportunityController::class);
    Route::post('/quotations/sync/fais-digital', [QuotationController::class, 'syncFromFaisDigital'])->name('quotations.sync-fais');
    Route::resource('quotations', QuotationController::class);
    Route::get('/quotations/{quotation}/print', [QuotationController::class, 'print'])->name('quotations.print');
    Route::resource('products', App\Http\Controllers\ProductController::class);
    Route::resource('services', App\Http\Controllers\ServiceController::class);
    Route::resource('agreements', App\Http\Controllers\AgreementController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', App\Http\Controllers\PaymentController::class);
    Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
    Route::resource('support-tickets', SupportTicketController::class);
    Route::get('/support-tickets/resolution', [SupportTicketController::class, 'resolution'])->name('support-tickets.resolution');
    Route::resource('tasks', TaskController::class);
    Route::post('/communications/record-lead-communication', [CommunicationController::class, 'recordLeadCommunication'])->name('communications.record-lead');
    Route::get('/communications/my-engagements', [CommunicationController::class, 'myEngagements'])->name('communications.my-engagements');
    Route::get('/communications/engagement-summary', [CommunicationController::class, 'engagementSummary'])->name('communications.engagement-summary');
    Route::resource('communications', CommunicationController::class);
    Route::resource('automation-workflows', AutomationWorkflowController::class);
    Route::resource('users', UserManagementController::class);
    
    // Contemporary Features Routes
    Route::resource('activities', ActivityController::class);
    Route::get('/activities/subject/{subject_type}/{subject_id}', [ActivityController::class, 'getSubjectActivities'])->name('activities.subject');
    
    Route::resource('email-templates', EmailTemplateController::class);
    Route::post('/email-templates/{emailTemplate}/render', [EmailTemplateController::class, 'render'])->name('email-templates.render');
    Route::get('/email-templates/get/templates', [EmailTemplateController::class, 'getTemplates'])->name('email-templates.get-templates');
    
    Route::resource('notifications', NotificationController::class);
    Route::get('/notifications/unread/count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    Route::get('/ai/lead-qualification', [AIController::class, 'leadQualification'])->name('ai.lead-qualification');
    Route::get('/ai/score-lead/{lead}', [AIController::class, 'scoreLead'])->name('ai.score-lead');
    Route::post('/ai/suggest-email', [AIController::class, 'suggestEmail'])->name('ai.suggest-email');
    Route::get('/ai/forecast-sales', [AIController::class, 'forecastSales'])->name('ai.forecast-sales');
    
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    
    Route::post('/bulk-action', [BulkActionController::class, 'bulkAction'])->name('bulk-action');
    
    Route::get('/search', [SearchController::class, 'globalSearch'])->name('search.global');
    Route::get('/search/quick', [SearchController::class, 'quickSearch'])->name('search.quick');
    
    Route::resource('calendar', CalendarController::class);
    Route::get('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.events');
    
    Route::get('/help/{form}', [App\Http\Controllers\HelpController::class, 'show'])->name('help.show');
    
    // Export/Import routes
    // Export/Import routes
    
    // Products
    Route::get('products/export/excel', [App\Http\Controllers\ProductController::class, 'exportExcel'])->name('products.export_excel');
    Route::get('products/export/pdf', [App\Http\Controllers\ProductController::class, 'exportPdf'])->name('products.export_pdf');
    Route::post('products/import/excel', [App\Http\Controllers\ProductController::class, 'importExcel'])->name('products.import_excel');
    
    // Services
    Route::get('services/export/excel', [App\Http\Controllers\ServiceController::class, 'exportExcel'])->name('services.export_excel');
    Route::get('services/export/pdf', [App\Http\Controllers\ServiceController::class, 'exportPdf'])->name('services.export_pdf');
    Route::post('services/import/excel', [App\Http\Controllers\ServiceController::class, 'importExcel'])->name('services.import_excel');
    
    // Payments
    Route::get('payments/export/excel', [App\Http\Controllers\PaymentController::class, 'exportExcel'])->name('payments.export_excel');
    Route::get('payments/export/pdf', [App\Http\Controllers\PaymentController::class, 'exportPdf'])->name('payments.export_pdf');
    Route::post('payments/import/excel', [App\Http\Controllers\PaymentController::class, 'importExcel'])->name('payments.import_excel');

    // Expenses
    Route::get('expenses/export/excel', [App\Http\Controllers\ExpenseController::class, 'exportExcel'])->name('expenses.export_excel');
    Route::get('expenses/export/pdf', [App\Http\Controllers\ExpenseController::class, 'exportPdf'])->name('expenses.export_pdf');
    Route::post('expenses/import/excel', [App\Http\Controllers\ExpenseController::class, 'importExcel'])->name('expenses.import_excel');

    // Invoices
    Route::get('invoices/export/excel', [App\Http\Controllers\InvoiceController::class, 'exportExcel'])->name('invoices.export_excel');
    Route::get('invoices/export/pdf', [App\Http\Controllers\InvoiceController::class, 'exportPdf'])->name('invoices.export_pdf');
    Route::post('invoices/import/excel', [App\Http\Controllers\InvoiceController::class, 'importExcel'])->name('invoices.import_excel');

    // Generic Fallback Routes for other modules (Invoices, Leads, etc.)
    Route::get('/{resource}/export/excel', function($resource) {
        $controller = 'App\\Http\\Controllers\\' . ucfirst(str_replace('-', '', ucwords($resource, '-'))) . 'Controller';
        if (class_exists($controller) && method_exists($controller, 'exportExcel')) {
            return (new $controller)->exportExcel();
        }
        return back()->with('error', 'Export functionality not implemented for this resource.');
    })->name('export.excel');
    
    Route::get('/{resource}/export/pdf', function($resource) {
        $controller = 'App\\Http\\Controllers\\' . ucfirst(str_replace('-', '', ucwords($resource, '-'))) . 'Controller';
        if (class_exists($controller) && method_exists($controller, 'exportPdf')) {
            return (new $controller)->exportPdf();
        }
        return back()->with('error', 'PDF export functionality not implemented for this resource.');
    })->name('export.pdf');
    
    Route::post('/{resource}/import/excel', function($resource) {
        $controller = 'App\\Http\\Controllers\\' . ucfirst(str_replace('-', '', ucwords($resource, '-'))) . 'Controller';
        if (class_exists($controller) && method_exists($controller, 'importExcel')) {
            return (new $controller)->importExcel(request());
        }
        return back()->with('error', 'Import functionality not implemented for this resource.');
    })->name('import.excel');

    // Keep the dynamic one for other resources if needed, or remove it. For now, removing to avoid conflict.
    // Dynamic routes removed to ensure reliability.
});
