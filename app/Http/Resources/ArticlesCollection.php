<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticlesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'messagem'=>'Artigos encotrados com sucesso!',
            'articles'=>$this->collection,
            'pagination'=>[
                'total'=>$this->total(),
                'per_page'=>$this->perPage(),
                'current_page'=>$this->currentPage(),
                'last_page'=>$this->lastPage(),
                'from'=>$this->firstItem(),
                'to'=>$this->lastItem(),
            ]
        ];
    }
}
