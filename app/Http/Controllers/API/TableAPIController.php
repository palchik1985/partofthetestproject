<?php

namespace App\Http\Controllers\API;

use App\Criteria\PaginateCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateTableAPIRequest;
use App\Http\Requests\API\DefaultAPIRequest;
use App\Http\Requests\API\UpdateTableAPIRequest;
use App\Models\Table;
use App\Repositories\TableRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class TableController
 * @package App\Http\Controllers\API
 */
class TableAPIController extends AppBaseController
{
    
    /** @var  TableRepository */
    private $tableRepository;
    
    /**
     * @param TableRepository $tableRepo
     *
     * @SWG\Tag(
     *   name="Table",
     *   description="Operations with the Tables"
     * ),
     * @SWG\Response(
     *          response="Tables",
     *          description="Array of Table objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(property="records", type="integer"),
     *                  @SWG\Property(property="perPage", type="integer"),
     *                  @SWG\Property(property="totalPages", type="integer"),
     *                  @SWG\Property(property="page", type="integer"),
     *                  @SWG\Property(
     *                       property="items",
     *                       type="array",
     *                       @SWG\Items(ref="#/definitions/TableGet"),
     *                 )
     *             )
     *          )
     * ),
     * @SWG\Response(
     *          response="Table",
     *          ref="$/responses/200",
     *          description="Table object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/TableGet"
     *              )
     *          )
     * ),
     *
     */
    public function __construct(TableRepository $tableRepo)
    {
        
        $this->tableRepository = $tableRepo;
    }
    
    /**
     * @param DefaultAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/tables",
     *      summary="Get a listing of the Tables.",
     *      tags={"Table"},
     *      @SWG\Parameter(ref="$/parameters/page"),
     *      @SWG\Response(response=200, ref="#/responses/Tables"),
     * )
     */
    
    public function index(DefaultAPIRequest $request)
    {
        
        $this->tableRepository->pushCriteria(new RequestCriteria($request));
        $this->tableRepository->pushCriteria(new PaginateCriteria($request));
        $tables = $this->tableRepository->all();
        
        $data = $this->formDataArrayForResponse($tables, $request, $this->tableRepository);
        
        return $this->sendResponse($data, 'Tables retrieved successfully');
    }
    
    /**
     * @param CreateTableAPIRequest $request
     *
     * @return Response
     *
     * @throws ValidatorException
     * @SWG\Post(
     *      path="/tables",
     *      summary="Store a newly created Table in storage",
     *      tags={"Table"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          description="restaurant_id",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          required=true,
     *          in="formData",
     *          description="name",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="seats_count",
     *          required=true,
     *          in="formData",
     *          description="seats_count",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          description="image",
     *          type="file"
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Table"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     */
    
    public function store(CreateTableAPIRequest $request)
    {
        
        $input = $request->all();
        
        $image = $request->file('image');
        if(!empty($image)){
            $file_path      = $request->file('image')->store('img/tables');
            $input['image'] = $image->hashName();
        }
        
        $table = $this->tableRepository->create($input);
        
        return $this->sendResponse($table->toArray(), 'Table saved successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/tables/{id}",
     *      summary="Display the specified Table",
     *      tags={"Table"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Table"),
     *      @SWG\Response(response=200, ref="#/responses/Table"),
     *      @SWG\Response(response="404", description="Table not found"),
     * )
     */
    
    public function show($id)
    {
        
        /** @var Table $table */
        $table = $this->tableRepository->find($id);
        
        if (empty($table)) {
            return $this->sendError('Table not found');
        }
        
        return $this->sendResponse($table->toArray(), 'Table retrieved successfully');
    }
    
    /**
     * @param int                   $id
     * @param UpdateTableAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/tables/{id}",
     *      summary="Update the specified Table in storage",
     *      tags={"Table"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Table"),
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          description="restaurant_id",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          required=true,
     *          in="formData",
     *          description="name",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="seats_count",
     *          required=true,
     *          in="formData",
     *          description="seats_count",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          description="image",
     *          type="file"
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Table"),
     *      @SWG\Response(response="404", description="Table not found"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    
    public function update($id, UpdateTableAPIRequest $request)
    {
        
        $input = $request->all();
        
        /** @var Table $table */
        $table = $this->tableRepository->find($id);
        
        if (empty($table)) {
            return $this->sendError('Table not found');
        }
        
        $image = $request->file('image');
        if(!empty($image)){
            $file_path      = $request->file('image')->store('img/tables');
            $input['image'] = $image->hashName();
        }
        
        $table = $this->tableRepository->update($input, $id);
        
        return $this->sendResponse($table->toArray(), 'Table updated successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Delete(
     *      path="/tables/{id}",
     *      summary="Remove the specified Table from storage",
     *      tags={"Table"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Table"),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="Table not found"),
     * )
     */
    
    public function destroy($id)
    {
        
        /** @var Table $table */
        $table = $this->tableRepository->find($id);
        
        if (empty($table)) {
            return $this->sendError('Table not found');
        }
        
        $table->delete();
        
        return $this->sendResponse($id, 'Table deleted successfully');
    }
    
    
    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @SWG\Get(
     *      path="/tables/dropdown",
     *      summary="Get a listing of the Tables for the dropdown without excluded ids",
     *      tags={"Table"},
     *      @SWG\Parameter(
     *          type="array",
     *          name="excluded_ids",
     *          in="query",
     *          @SWG\Items(
     *              type="integer"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response="200",
     *          description="Array of Table objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="items",
     *                  type="array",
     *                  @SWG\Items(
     *                      @SWG\Property(property="id", type="integer"),
     *                      @SWG\Property(property="name", type="string"),
     *                  ),
     *
     *             )
     *          )
     *      ),
     * )
     */
    public function dropdownWithExcluded(Request $request)
    {
        
        $id_array = $request->get('excluded_ids') ?? [];
        
        $tables = $this->tableRepository->findWhereNotIn('id', $id_array, ['id', 'name'])->toArray();
        
        foreach($tables as $i => $table){
            unset($tables[$i]['image_url']);
        }
        
        return $this->sendResponse($tables, 'Tables retrieved successfully');
    }
}
