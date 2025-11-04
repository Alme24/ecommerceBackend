<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EtiquetaController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\HorarioTienda;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//Usuarios rutas
Route::apiResource('usuarios',UsuarioController::class);
Route::put('usuarios/{id}/cambiar-contrasena', [UsuarioController::class, 'cambiarContrasena']);

//Cuentas rutas
Route::apiResource('cuentas',CuentaController::class);
Route::get('cuentas/usuario/{user_id}',[CuentaController::class,'getByUserId']);

//Tiendas rutas
Route::apiResource('tiendas',TiendaController::class);
Route::get('tiendas/usuario/{user_id}',[TiendaController::class,'getByUserId']);

//Productos rutas
Route::apiResource('productos',ProductoController::class);
Route::get('productos/categoria/{categoria_id}', [ProductoController::class, 'getByCategoria']);
Route::get('productos/detalle/{id}',[ProductoController::class,'getProductoDetall']);

//Pedido rutas
Route::apiResource('pedidos',PedidoController::class);
Route::get('/tienda/{tienda_id}/pedidos/{estado_pedido}', [PedidoController::class, 'pedidosEstadoArtesano']);
Route::get('/usuario/{user_id}/pedidos/{estado_pedido}', [PedidoController::class, 'pedidosEstadoUser']);
Route::get('/pedidos/carrito/{carrito_id}', [PedidoController::class, 'pedidosPorCarrito']);


//Carrito rutas
Route::apiResource('carritos',CarritoController::class);

//Categoria rutas
Route::apiResource('categorias',CategoriaController::class);

//Etiqueta rutas
Route::apiResource('etiquetas',EtiquetaController::class);
Route::get('etiquetas/producto/{producto_id}', [EtiquetaController::class, 'getByProducto']);

//Resena rutas
Route::apiResource('resenas',ResenaController::class);
Route::get('/productos/{id}/resenas', [ResenaController::class, 'getResenasByProducto']);

//mensajeria
Route::get('/messages/{conversation}', [MessageController::class, 'index']);
Route::post('/messages', [MessageController::class, 'send']);

//Horarios
Route::prefix('tiendas/{tienda_id}')->group(function () {
    Route::get('/horarios', [HorarioTiendaController::class, 'index']);
    Route::post('/horarios', [HorarioTiendaController::class, 'store']);
});

Route::get('/horarios/{id}', [HorarioTiendaController::class, 'show']);
Route::put('/horarios/{id}', [HorarioTiendaController::class, 'update']);
Route::delete('/horarios/{id}', [HorarioTiendaController::class, 'destroy']);