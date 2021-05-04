<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Hash;

class HashController extends Controller
{


    public function genereteHash(Request $request){
        try {
            
            $this->valideteArgsGenerate($request->all());

            $string = $request->input ?? "";

            $attempts = 0;
            
            do{
                $attempts += 1;
                $key = $this->genereteKey();
                $hash_generated = md5($string . $key);
            }while(strpos($hash_generated,"0000") !== 0);

            $result = [
                "key_found"=>$key,
                "hash"=>$hash_generated,
                "attempts"=>$attempts,
                "string_input"=>$string,
            ];

            $Hash = new Hash;
            $Hash->fill($result);
            $Hash->save();

            return response()->json($result,200);
            
        }catch(\Exception $Exception) {
            $statusCode = 500;     
            $message= "Ops! Algo deu errado";

            if (method_exists($Exception, 'getStatusCode')) {
                $statusCode = $Exception->getStatusCode();
                $message= $Exception->getMessage();
            }
            return response()->json(["message"=>$message],$statusCode);
            
        }
    }

    public function listing(Request $request) {
        try {

            $this->valideteArgsListing($request->all());

            $limit = isset($request->limit) ? $request->limit : 100;
            $limit = $limit > 100 ? 100 : $limit;

            $offset = isset($request->offset) ? $request->offset : 0;
            $attemptsLessThan = isset($request->attempts_less_than) ? $request->attempts_less_than : null;

            $Hash = new Hash;
            $listing = $Hash->listing($limit,$offset,$attemptsLessThan);
            return response()->json($listing,200);
            
        }catch(\Exception $Exception) {
            $statusCode = 500;     
            $message= "Ops! Algo deu errado";

            if (method_exists($Exception, 'getStatusCode')) {
                $statusCode = $Exception->getStatusCode();
                $message= $Exception->getMessage();
            }
            return response()->json(["message"=>$message],$statusCode);
            
        }
    }

    public function valideteArgsGenerate($args){
        $validator = Validator::make($args, [
            'input' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            abort(400,$validator->errors()->toJson());
        }
    }

    public function valideteArgsListing($args){
        $validator = Validator::make($args, [
            'limit' => 'integer',
            'offset' => 'integer',
            'attempts_less_than' => 'integer',
        ]);
        if ($validator->fails()) {
            abort(400,$validator->errors()->toJson());
        }
    }



    private function genereteKey($quantidades_bytes = 4) {
        try {
            $restultado_bytes = random_bytes($quantidades_bytes);
            $key = bin2hex($restultado_bytes);
            return $key;
        }catch(\Exception $Exception) {
            $statusCode = 500;     
            if (method_exists($Exception, 'getStatusCode')) {
                $statusCode = $Exception->getStatusCode();
            }
            abort($statusCode,$Exception->getMessage());
        }
    }

}
