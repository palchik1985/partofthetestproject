<?php

namespace App\Http\Controllers\API;

use App\Criteria\FindReservesByRelatedFieldsCriteria;
use App\Criteria\PaginateCriteria;
use App\Criteria\PastFutureReservesCriteria;
use App\Criteria\ReservesFromMobileCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateReserveAPIRequest;
use App\Http\Requests\API\UpdateReserveAPIRequest;
use App\Models\Client;
use App\Models\Reserve;
use App\Models\ReserveSmsId;
use App\Models\SMS;
use App\Repositories\ReserveRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Validator\Exceptions\ValidatorException;
use Response;

/**
 * Class ReserveController
 * @package App\Http\Controllers\API
 */
class ReserveAPIController extends AppBaseController
{
    
    /** @var  ReserveRepository */
    private $reserveRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="Reserve",
     *   description="Operations with the Reserves"
     * ),
     * @SWG\Response(
     *          response="Reserves",
     *          description="Array of Reserve objects",
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
     *                      @SWG\Items(ref="#/definitions/ReserveGet"),
     *                 )
     *             )
     *         )
     * ),
     * @SWG\Response(
     *          response="Reserve",
     *          ref="$/responses/200",
     *          description="Reserve object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/ReserveGet"
     *              )
     *          )
     * ),
     */
    public function __construct(ReserveRepository $reserveRepo)
    {
        
        $this->reserveRepository = $reserveRepo;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/reserves",
     *      summary="Get a listing of the Reserves.",
     *      tags={"Reserve"},
     *      @SWG\Parameter(ref="$/parameters/page"),
     *      @SWG\Parameter(
     *          name="client_name",
     *          in="query",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="table_name",
     *          in="query",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          name="event_name",
     *          in="query",
     *          type="string",
     *      ),
     *      @SWG\Parameter(
     *          in="query",
     *          name="past",
     *          type="boolean",
     *          default="false",
     *      ),
     *      @SWG\Parameter(
     *          in="query",
     *          name="from_mobile",
     *          type="boolean",
     *          default="false",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Reserves"),
     * )
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    
    public function index(Request $request)
    {
    
        $this->reserveRepository->pushCriteria(new ReservesFromMobileCriteria($request));
        $this->reserveRepository->pushCriteria(new RequestCriteria($request));
    
        $this->reserveRepository->pushCriteria(new FindReservesByRelatedFieldsCriteria($request));
        $this->reserveRepository->pushCriteria(new PastFutureReservesCriteria($request));
        $this->reserveRepository->pushCriteria(new PaginateCriteria($request));
        
        $reserves = $this->reserveRepository->with(['client', 'tables', 'event', 'sms_ids'])->all();
        foreach ($reserves as $i => $reserve) {
            $reserves[ $i ][ 'smses' ] = $reserve->smses;
        }
        
        $data = $this->formDataArrayForResponse($reserves, $request, $this->reserveRepository);
        
        return $this->sendResponse($data, 'Reserves retrieved successfully');
    }
    
    
    /**
     * @param CreateReserveAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/reserves",
     *      summary="Store a newly created Reserve in storage",
     *      tags={"Reserve"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="client_id",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="date_start",
     *          required=true,
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          description="default will be added 3 hours to date_start",
     *          name="date_finish",
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          name="persons_count",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="comment",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="prepayment",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="from_mobile",
     *          in="formData",
     *          type="integer",
     *          description="1 = true, 0 = false"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          type="file"
     *      ),
     *      @SWG\Parameter(
     *                  name="tables[0]",
     *                  description="table ID",
     *                  type="integer",
     *                  in="formData",
     *              ),
     *      @SWG\Parameter(
     *                  name="tables[1]",
     *                  description="table ID",
     *                  type="integer",
     *                  in="formData",
     *              ),
     *      @SWG\Parameter(
     *                  name="sms_send_time[0]",
     *                  description="datetime of send sms, YYYY-MM-DD HH:MM:SS",
     *                  type="string",
     *                  in="formData",
     *              ),
     *      @SWG\Response(response=200, ref="#/responses/Reserve"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    
    public function store(CreateReserveAPIRequest $request)
    {
        
        $input = $request->except('tables');
        
        $image = $request->file('image');
        if ( ! empty($image)) {
            $file_path        = $request->file('image')->store('img/reserves');
            $input[ 'image' ] = $image->hashName();
        }
        
        $reserve = $this->reserveRepository->create($input);
        
        $tables = $request->get('tables') ?? [];
        
        foreach ($tables as $table) {
            $reserve->tables()->attach((int)$table);
        }
        
        $smses = $request->get('sms_send_time') ?? [];
        
        foreach ($smses as $sms_date) {
    
            if ($sms_date < Carbon::now()) {
                continue;
            }
            // send sms
            $client = Client::find($reserve->client_id);
            $sms    = SMS::createSms($client, $sms_date, $reserve->persons_count);
            $sms->save();
            
            $sms_id_model = new ReserveSmsId(['sms_id' => $sms->id]);
            $reserve->sms_ids()->save($sms_id_model);
        }
        
        return $this->sendResponse($reserve->toArray(), 'Reserve saved successfully');
    }
    
    /**
     * @param CreateReserveAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/my_reserves",
     *      summary="Store a newly created Reserve in storage",
     *      tags={"Reserve"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="client_id",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="date_start",
     *          required=true,
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          description="default will be added 3 hours to date_start",
     *          name="date_finish",
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          name="persons_count",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="comment",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="prepayment",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          type="file"
     *      ),
     *      @SWG\Parameter(
     *                  name="tables[0]",
     *                  description="table ID",
     *                  type="integer",
     *                  in="formData",
     *              ),
     *      @SWG\Parameter(
     *                  name="tables[1]",
     *                  description="table ID",
     *                  type="integer",
     *                  in="formData",
     *              ),
     *      @SWG\Response(response=200, ref="#/responses/Reserve"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    public function my_reserves(Request $request)
    {
        
        $input = $request->except('tables');
        
        $image = $request->file('image');
        if ( ! empty($image)) {
            $file_path        = $request->file('image')->store('img/reserves');
            $input[ 'image' ] = $image->hashName();
        }
        
        $input[ 'from_mobile' ] = 1;
        
        $reserve = $this->reserveRepository->create($input);
        
        $tables = $request->get('tables') ?? [];
        
        foreach ($tables as $table) {
            $reserve->tables()->attach((int)$table);
        }
        
        // without smses
        //        $smses = $request->get('sms_send_time') ?? [];
        //
        //        foreach ($smses as $sms_date) {
        //            // send sms
        //            $client = Client::find($reserve->client_id);
        //            $sms    = SMS::createSms($client, $sms_date, $reserve->persons_count);
        //            $sms->save();
        //
        //            $sms_id_model = new ReserveSmsId(['sms_id' => $sms->id]);
        //            $reserve->sms_ids()->save($sms_id_model);
        //        }
        
        return $this->sendResponse($reserve->toArray(), 'Reserve saved successfully');
        
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/reserves/{id}",
     *      summary="Display the specified Reserve",
     *      tags={"Reserve"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Reserve"),
     *      @SWG\Response(response=200, ref="#/responses/Reserve"),
     *      @SWG\Response(response="404", description="Reserve not found"),
     * )
     */
    
    public function show($id)
    {
        
        /** @var Reserve $reserve */
        $reserve = $this->reserveRepository->with(['client', 'tables', 'event', 'sms_ids'])->find($id);
        
        if (empty($reserve)) {
            return $this->sendError('Reserve not found');
        }
        $reserve[ 'smses' ] = $reserve->smses;
        return $this->sendResponse($reserve->toArray(), 'Reserve retrieved successfully');
    }
    
    /**
     * @param int                     $id
     * @param UpdateReserveAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/reserves/{id}",
     *      summary="Update the specified Reserve in storage",
     *      tags={"Reserve"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Reserve"),
     *      @SWG\Parameter(
     *          name="restaurant_id",
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="client_id",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="date_start",
     *          required=true,
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          description="default will be added 3 hours to date_start",
     *          name="date_finish",
     *          in="formData",
     *          type="string",
     *          format="date-time"
     *      ),
     *      @SWG\Parameter(
     *          name="persons_count",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="comment",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="prepayment",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *                  name="tables[0]",
     *                  description="table ID",
     *                  type="integer",
     *                  in="formData",
     *              ),
     *      @SWG\Parameter(
     *                  name="tables[1]",
     *                  description="table ID",
     *                  type="integer",
     *                  in="formData",
     *              ),
     *      @SWG\Parameter(
     *                  name="sms_send_time[0]",
     *                  description="datetime of send sms, YYYY-MM-DD HH:MM:SS",
     *                  type="string",
     *                  in="formData",
     *              ),
     *      @SWG\Parameter(
     *                  name="sms_send_time[1]",
     *                  description="datetime of send sms, YYYY-MM-DD HH:MM:SS",
     *                  type="string",
     *                  in="formData",
     *              ),
     *      @SWG\Parameter(
     *          name="image",
     *          in="formData",
     *          type="file"
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Reserve"),
     *      @SWG\Response(response="404", description="Reserve not found"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     * @throws ValidatorException
     */
    
    public function update($id, UpdateReserveAPIRequest $request)
    {
        
        $input = $request->except('tables', 'sms_send_time');
        
        /** @var Reserve $reserve */
        $reserve = $this->reserveRepository->find($id);
        
        if (empty($reserve)) {
            return $this->sendError('Reserve not found');
        }
        
        $image = $request->file('image');
        if ( ! empty($image)) {
            $file_path        = $request->file('image')->store('img/tables');
            $input[ 'image' ] = $image->hashName();
        }
        
        $tables = $request->get('tables') ?? [];
        
        $reserve->tables()->detach();
        foreach ($tables as $table) {
            $reserve->tables()->attach((int)$table);
        }
        
        $smses = $request->get('sms_send_time') ?? [];
    
        //todo тут может возникнуть проблема, когда при обновлении прошедшего резерва у клиента, ему уйдут новые сообщения
        $old_smses = $reserve->smses;
        if (count($old_smses) > 0) {
            
            // delete old smses
            $result  = SMS::whereIn('id', $old_smses->pluck('id')->toArray())->delete();
            $result1 = ReserveSmsId::where('reserve_id', $reserve->id)->delete();
        }
        
        foreach ($smses as $sms_date) {
            if ($sms_date < Carbon::now()) {
                continue;
            }
            // send sms
            $client = Client::find($reserve->client_id);
            $sms    = SMS::createSms($client, $sms_date, $reserve->persons_count);
            $sms->save();
            
            $sms_id_model = new ReserveSmsId(['sms_id' => $sms->id]);
            $reserve->sms_ids()->save($sms_id_model);
        }
        
        $reserve = $this->reserveRepository->update($input, $id);
        
        return $this->sendResponse($reserve->toArray(), 'Reserve updated successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Delete(
     *      path="/reserves/{id}",
     *      summary="Remove the specified Reserve from storage",
     *      tags={"Reserve"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Reserve"),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="Reserve not found"),
     * )
     */
    
    public function destroy($id)
    {
        
        /** @var Reserve $reserve */
        $reserve = $this->reserveRepository->find($id);
        
        if (empty($reserve)) {
            return $this->sendError('Reserve not found');
        }
        
        $reserve->tables()->detach();
        $reserve->delete();
        
        return $this->sendResponse($id, 'Reserve deleted successfully');
    }
}
