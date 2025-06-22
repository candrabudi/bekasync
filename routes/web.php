<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CdrReportSyncController;
use App\Http\Controllers\DashboardCallCenterController;
use App\Http\Controllers\DashboardOmniController;
use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\GovernmentUnitController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IncidentSyncController;
use App\Http\Controllers\UserGovermentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('dashboard/call-center')->name('dashboard.call_center.')->group(function () {
    Route::get('/', [DashboardCallCenterController::class, 'index'])->name('index');
    Route::get('/get-dispatcher', [DashboardCallCenterController::class, 'getDispatcher'])->name('get_dispatcher');
    Route::get('/data-card-incident', [DashboardCallCenterController::class, 'dataCardIncident'])->name('data_card_incident');
    Route::get('/daily-report-chart-hourly', [DashboardCallCenterController::class, 'getDailyReportChartDataPerHour'])->name('daily_report_chart_hourly');
    Route::get('/get-daily-report-chart', [DashboardCallCenterController::class, 'getDailyReportChartDataPerDay'])->name('get_daily_report_chart');

    Route::get('/top5-incident-by-categories', [DashboardCallCenterController::class, 'getTop5IncidentByCategories'])->name('top5_incident_by_categories');
    Route::get('/top5-incident-by-districts', [DashboardCallCenterController::class, 'getTop5IncidentByDistrict'])->name('top5_incident_by_districts');
    Route::get('/top5-incident-by-dinas', [DashboardCallCenterController::class, 'getTop5IncidentByDinas'])->name('top5_incident_by_dinas');
    Route::get('/top5-responsive-dinas', [DashboardCallCenterController::class, 'getTop5MostResponsiveDinas'])->name('top5_responsive_dinas');
    Route::get('/hourly-stats', [DashboardCallCenterController::class, 'hourlyStats'])->name('cdr_hourly_stats');
    Route::get('/speedmeter-data', [DashboardCallCenterController::class, 'getSpeedmeterData'])->name('get_speedmeter_data');
    Route::get('/total-type-report', [DashboardCallCenterController::class, 'getTotalTypeReport'])->name('get_total_type_report');
    Route::get('/summary-call', [DashboardCallCenterController::class, 'wallBoardGetSummaryCall'])->name('wallboard_get_summary_call');
});

Route::prefix('dashboard/omni-channel')->name('dashboard.omni_channel.')->group(function () {
    Route::get('/', [DashboardOmniController::class, 'index'])->name('index');
    Route::get('/active-agent', [DashboardOmniController::class, 'activeAgent'])->name('activeAgent');
    Route::get('/conversations-summary', [DashboardOmniController::class, 'conversationsSummary'])->name('conversationsSummary');
    Route::get('/whatsapp-usage', [DashboardOmniController::class, 'whatsappUsage'])->name('whatsappUsage');
    Route::get('/agent-performance', [DashboardOmniController::class, 'agentPerformance'])->name('agentPerformance');
});

Route::prefix('dispatchers')->name('dispatchers.')->group(function () {
    Route::get('/', [DispatcherController::class, 'index'])->name('index');
    Route::get('/list', [DispatcherController::class, 'getDispatcherList'])->name('list');
});

Route::get('/incidents', [IncidentController::class, 'index'])->name('incidents.index');
Route::get('/incidents/data', [IncidentController::class, 'fetch'])->name('incidents.fetch');
Route::get('/incidents/{id}', [IncidentController::class, 'show'])->name('incidents.show');
Route::get('/incident/by-dinas/{a}', [IncidentController::class, 'showByDinas']);
Route::get('/incident/by-dinas/{a}/list', [IncidentController::class, 'getReportsByDinas']);


Route::get('/government-units', [GovernmentUnitController::class, 'index'])->name('government_units.index');
Route::get('/government-units/data', [GovernmentUnitController::class, 'data'])->name('government_units.data');

Route::get('/agencies', [UserGovermentController::class, 'index'])->name('agencies.index');
Route::get('/agencies/create', [UserGovermentController::class, 'create'])->name('agencies.create');
Route::post('/agencies/store', [UserGovermentController::class, 'store'])->name('agencies.store');
Route::get('/agencies/{id}/edit', [UserGovermentController::class, 'edit'])->name('agencies.edit');
Route::post('/agencies/{id}/update', [UserGovermentController::class, 'update'])->name('agencies.update');
Route::post('/agencies/{id}/delete', [UserGovermentController::class, 'destroy'])->name('agencies.destroy');

Route::get('/sync-incident-reports', [IncidentSyncController::class, 'sync']);
Route::get('/sync-cdr-reports', [CdrReportSyncController::class, 'sync']);

Route::prefix('sosmed')->controller(DashboardOmniController::class)->group(function () {
    Route::post('/active-agents', 'getActiveAgents');
    Route::post('/subscription', 'getSubscription');
    Route::post('/agent-performance', 'getAgentPerformance');
    Route::post('/conversation-summary', 'getConversationSummary');
    Route::post('/top-tags', 'getTopTags');
    Route::post('/wa-usage', 'getWaUsage');
});
