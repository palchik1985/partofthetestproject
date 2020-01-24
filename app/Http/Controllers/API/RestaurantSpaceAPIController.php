<?php

namespace App\Http\Controllers\API;

use App\Criteria\PaginateCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateRestaurantSpaceAPIRequest;
use App\Http\Requests\API\DefaultAPIRequest;
use App\Http\Requests\API\UpdateRestaurantSpaceAPIRequest;
use App\Models\RestaurantSpace;
use App\Repositories\RestaurantSpaceRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

/**
 * Class RestaurantSpaceController
 * @package App\Http\Controllers\API
 */
class RestaurantSpaceAPIController extends AppBaseController
{
    
    /** @var  RestaurantSpaceRepository */
    private $restaurantSpaceRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="RestaurantSpace",
     *   description="Operations with the RestaurantSpaces."
     * ),
     * @SWG\Response(
     *          response="RestaurantSpaces",
     *          description="Array of RestaurantSpace objects",
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
     *                       @SWG\Items(ref="#/definitions/RestaurantSpaceGet"),
     *                 )
     *             ),
     *          )
     * ),
     * @SWG\Response(
     *          response="RestaurantSpace",
     *          ref="$/responses/200",
     *          description="RestaurantSpace object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/RestaurantSpaceGet"
     *              )
     *          )
     * ),
     */
    public function __construct(RestaurantSpaceRepository $restaurantSpaceRepo)
    {
        
        $this->restaurantSpaceRepository = $restaurantSpaceRepo;
    }
    
    /**
     * @param DefaultAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/restaurant_spaces",
     *      summary="Get a listing of the RestaurantSpaces.",
     *      tags={"RestaurantSpace"},
     *      @SWG\Parameter(ref="$/parameters/page"),
     *      @SWG\Response(response=200, ref="#/responses/RestaurantSpaces"),
     * )
     */
    
    public function index(DefaultAPIRequest $request)
    {
        
        $this->restaurantSpaceRepository->pushCriteria(new RequestCriteria($request));
        $this->restaurantSpaceRepository->pushCriteria(new PaginateCriteria($request));
        $spaces = $this->restaurantSpaceRepository->all();
        
        $data = $this->formDataArrayForResponse($spaces, $request, $this->restaurantSpaceRepository);
        
        return $this->sendResponse($data, 'Restaurant Spaces retrieved successfully');
    }
    
    /**
     * @param CreateRestaurantSpaceAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/restaurant_spaces",
     *      summary="Store a newly created RestaurantSpace in storage",
     *      tags={"RestaurantSpace"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          type="file"
     *      ),
     *              @SWG\Property(
     *                  property="size_x",
     *                  type="integer",
     *              ),
     *              @SWG\Property(
     *                  property="size_y",
     *                  type="integer",
     *              ),
     *      @SWG\Response(response=200, ref="#/responses/RestaurantSpace"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    
    public function store(CreateRestaurantSpaceAPIRequest $request)
    {
        
        $input = $request->all();
        
        $image = $request->file('image');
        if ( ! empty($image)) {
            $file_path        = $request->file('image')->store('img/spaces');
            $input[ 'image' ] = $image->hashName();
        }
        
        $restaurantSpace = $this->restaurantSpaceRepository->create($input);
        
        return $this->sendResponse($restaurantSpace->toArray(), 'Restaurant Space saved successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/restaurant_spaces/{id}",
     *      summary="Display the specified RestaurantSpace",
     *      tags={"RestaurantSpace"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of RestaurantSpace"),
     *      @SWG\Response(response=200, ref="#/responses/RestaurantSpace"),
     *      @SWG\Response(response="404", description="RestaurantSpace not found"),
     * )
     */
    
    public function show($id)
    {
        
        /** @var RestaurantSpace $restaurantSpace */
        $restaurantSpace = $this->restaurantSpaceRepository->find($id);
        
        if (empty($restaurantSpace)) {
            return $this->sendError('Restaurant Space not found');
        }
        
        return $this->sendResponse($restaurantSpace->toArray(), 'Restaurant Space retrieved successfully');
    }
    
    /**
     * @param int                             $id
     * @param UpdateRestaurantSpaceAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/restaurant_spaces/{id}",
     *      summary="Update the specified RestaurantSpace in storage",
     *      tags={"RestaurantSpace"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of RestaurantSpace"),
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="name",
     *          required=true,
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="description",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          type="file"
     *      ),
     *              @SWG\Property(
     *                  property="size_x",
     *                  type="integer",
     *              ),
     *              @SWG\Property(
     *                  property="size_y",
     *                  type="integer",
     *              ),
     *      @SWG\Response(response=200, ref="#/responses/RestaurantSpace"),
     *      @SWG\Response(response="404", description="RestaurantSpace not found"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    
    public function update($id, UpdateRestaurantSpaceAPIRequest $request)
    {
        
        $input = $request->all();
        
        /** @var RestaurantSpace $restaurantSpace */
        $restaurantSpace = $this->restaurantSpaceRepository->find($id);
        
        if (empty($restaurantSpace)) {
            return $this->sendError('Restaurant Space not found');
        }
        
        $image = $request->file('image');
        if ( ! empty($image)) {
            $file_path        = $request->file('image')->store('img/spaces');
            $input[ 'image' ] = $image->hashName();
        }
        
        $restaurantSpace = $this->restaurantSpaceRepository->update($input, $id);
        
        return $this->sendResponse($restaurantSpace->toArray(), 'RestaurantSpace updated successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Delete(
     *      path="/restaurant_spaces/{id}",
     *      summary="Remove the specified RestaurantSpace from storage",
     *      tags={"RestaurantSpace"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of RestaurantSpace"),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="RestaurantSpace not found"),
     * )
     */
    
    public function destroy($id)
    {
        
        /** @var RestaurantSpace $restaurantSpace */
        $restaurantSpace = $this->restaurantSpaceRepository->find($id);
        
        if (empty($restaurantSpace)) {
            return $this->sendError('Restaurant Space not found');
        }
        
        $restaurantSpace->delete();
        
        return $this->sendResponse($id, 'Restaurant Space deleted successfully');
    }
    
    
    /**
     * @param Request $request
     *
     * @return mixed
     *
     * @SWG\Get(
     *      path="/restaurant_spaces/dropdown",
     *      summary="Get a listing of the RestaurantSpaces for the dropdown without excluded ids",
     *      tags={"RestaurantSpace"},
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
     *          description="Array of RestaurantSpace objects",
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
        
        $spaces = $this->restaurantSpaceRepository->findWhereNotIn('id', $id_array, ['id', 'name']);
        
        return $this->sendResponse($spaces->toArray(), 'Spaces retrieved successfully');
    }
}
