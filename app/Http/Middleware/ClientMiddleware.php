<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Carbon\Carbon;
class ClientMiddleware{public function handle(Request$a,Closure$b){
$c=env('LI');$d=env('EX');$e=Carbon::now();
if(!$c)return response()->json(['m'=>'invalid'],403);
if(!$d)return response()->json(['m'=>'expired'],403);
if($d!="LIFETIME" && $d!=""){
    if($e->gt(Carbon::parse($d)))return response()->json(['m'=>'expired'],403);
}
if($c!=='ABC123456XYZ111')return response()->json(['m'=>'invalid'],403);
return $b($a);}}
