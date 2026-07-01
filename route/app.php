<?php
use think\facade\Route;

Route::get('health', 'Index/health');
Route::get('health/db', 'Health/db');

Route::get('api/services', 'api.Services/index');
Route::get('api/services/hot', 'api.Services/hot');
Route::get('api/services/:sid', 'api.Services/detail');

Route::get('api/articles', 'api.Articles/index');
Route::get('api/articles/:aid', 'api.Articles/detail');

Route::get('api/therapists', 'api.Therapists/index');
Route::get('api/therapists/:id', 'api.Therapists/detail');

Route::post('api/user/login', 'api.User/login');
Route::get('api/user/profile', 'api.User/profile');
Route::put('api/user/profile', 'api.User/updateProfile');

Route::get('api/appointments', 'api.Appointments/index');
Route::post('api/appointments', 'api.Appointments/create');
Route::post('api/appointments/:id/cancel', 'api.Appointments/cancel');

Route::get('api/service-cards', 'api.ServiceCards/index');

Route::get('api/pay/list', 'api.Pay/list');
Route::get('api/pay/query', 'api.Pay/query');
Route::post('api/pay/unifiedorder', 'api.Pay/unifiedorder');
