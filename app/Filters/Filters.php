<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

abstract class Filters
{
     /** 
     * @var Request
     */
    protected $request;
    protected $builder;
    protected $filters = [] ;

    /**
     * ThreadFilter constructor
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    public function apply($builder)
    {
        $this->builder = $builder;


        foreach ($this->getFilters() as $filter => $value) {
            if(method_exists($this, $filter)){
                $this->$filter($value);
            }
        }

        //o que está acima representa o que está abaixo, só que adaptado para mais de um tipo de request
        // if ($this->request->has('by')) {
        //     $this->by($this->request->by);
        // }

        return $this->builder;
        
    }


    protected function getFilters()
    {
        $filters = array_intersect(array_keys($this->request->all()), $this->filters);

        //usando o metodo only nós evitamos do usuário tentar passar algum outro request alem dos que estão contidos no array de requests permitidos!
        //mas nesse caso usaremos o intersect() que permite alem dos filtros registrados, permite um array vazio (sem query), ou seja, permite executar os querys registrados (ex: 'by') e tambem nenhum filtro
        //de forma que a lista de threads sem filtros possa ser mostrada
        return $this->request->only($filters);
    }

}