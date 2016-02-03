<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	return view('greeting', ['name' => 'BoBo', 'REQUEST_URI' => Request::url()]);
});
Route::any('test', function(){
    return Input::all();
});
Route::group(['prefix' => 'api'], function()
{
    Route::any('{uri?}', function($uri=null)
    {
        $cmd = 'rsserial -j ';
        
        $reqStr = json_encode(array(
            'method'    => Request::method(),
            'parameter' => Input::all(),
            'path'      => ($uri==null) ? [] : explode('/',$uri),
        ));
        $qStr = json_encode(array(
            'request' => $reqStr,
            'ver' => '0001',
        ));
        $qStr = str_replace("\\", "\\\\", $qStr);
        $qStr = '"'.str_replace("\"", "\\\"", $qStr).'"';
    
        //$qStr0 = '"{\"request\":\"{\\\\\"method\\\\\":\\\\\"GET\\\\\",\\\\\"parameter\\\\\":[],\\\\\"path\\\\\":[\\\\\"hello\\\\\"]}\",\"ver\":\"0001\"}"';
//        return shell_exec($cmd.$qStr);
        
        return  response(shell_exec($cmd.$qStr))->header('Content-Type',"application/json" );
    })->where(['uri' => '.*']);
});
Route::group(['prefix' => 'widget'], function()
{
    Route::any('{uri?}', function($uri=null)
    {
        $cmd = 'rsserial -j ';
        
        $reqStr = json_encode(array(
            'method'    => 'GET',
            'parameter' => array(),
            'path'      => array('widget_registry'),
        ));
        $qStr = json_encode(array(
            'request' => $reqStr,
            'ver' => '0001',
        ));
        $qStr = str_replace("\\", "\\\\", $qStr);
        $qStr = '"'.str_replace("\"", "\\\"", $qStr).'"';
        
        $rtn = json_decode(shell_exec($cmd.$qStr),true);
        $res = $rtn['result'];
        
        // create symlink to widget
        
        $widgetDir = __DIR__."/../../public/widgets";
        if (!file_exists($widgetDir)) {
            mkdir($widgetDir);    
        }
        
        $i=0;
        $codes='';
        foreach($res as $entry) {
            //$entry=$res[0];
            $root  = $entry["root"];
            $index = $entry["index"];
            $dir ="wgt".$i++;
            $code_ = file_get_contents($root."/".$index);
            $code_ = str_replace("<@=WIDGETROOT@>", "/widgets/".$dir, $code_);
            $code_ = str_replace("\n", "", $code_);
            //array_push($codes, $code_);
            $codes = $codes.$code_;
            $publicRoot = "widgets/".$dir;
            
            if (!file_exists($publicRoot)) {
                symlink($root, $publicRoot);
            } elseif (!is_link($publicRoot)) {
                unlink($publicRoot);
                symlink($root, $publicRoot);
            } elseif (readlink($publicRoot) != $root) { 
                unlink($publicRoot);
                symlink($root, $publicRoot);
            }
        }
        
        $final = array(
            'status' => 0,
            'data' => $codes,
        );
        return  response(json_encode($final,JSON_UNESCAPED_SLASHES))->header('Content-Type',"application/json" );
    })->where(['uri' => '.*']);
});
