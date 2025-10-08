<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Carbon\Carbon;
class ClientMiddleware{public function handle(Request$a,Closure$b){
$c=env('LI');$d=env('EX');$e=Carbon::now();
if(!$c)return abort(403, 'Expired License. PLEASE CONTACT ADMIN');
if(!$d)return abort(403, 'Expired License');
if($d!="LIFETIME" && $d!=""){
    if($e->gt(Carbon::parse($d)))return abort(403, 'Expired License. PLEASE CONTACT ADMIN');
}
if($c!=='ABC123456XYZ111')return abort(403, 'Expired License. PLEASE CONTACT ADMIN');
return $b($a);}}
