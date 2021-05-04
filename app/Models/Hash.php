<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hash extends Model
{
    use HasFactory;

    protected $table = "hash";

    protected $fillable = [
        "string_input",
        "key_found",
        "hash",
        "attempts"
    ];

    public function listing(Int $limit,int $offset,Int $attemptsLessThan = null) {
        try {

            $listing = $this->select("id","string_input","key_found");

            if($attemptsLessThan){
                $listing =  $listing->where("attempts","<",$attemptsLessThan);
            }
            
            $totalResults = $listing->count();

            $listing =  $listing->limit($limit)->offset($offset);
           
            $listing = $listing->get()->toArray();

            
            return [
                "totalResults"=>$totalResults,
                "limit"=>$limit,
                "offset"=>$offset,
                "rows"=>$listing,
            ];


        }catch(\Exception $Exception) {
            $statusCode = 500;     
            if (method_exists($Exception, 'getStatusCode')) {
                $statusCode = $Exception->getStatusCode();
            }
            abort($statusCode,$Exception->getMessage());
        }
    }
}
