<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateMediaAPIRequest;
use App\Http\Requests\API\UpdateMediaAPIRequest;
use App\Models\Event;
use App\Models\Media;
use App\Repositories\MediaRepository;
use App\Rules\Videolink;
use Response;

/**
 * Class MediaController
 * @package App\Http\Controllers\API
 */
class MediaAPIController extends AppBaseController
{
    
    /** @var  MediaRepository */
    private $mediaRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="Media",
     *   description="Operations with the Media"
     * ),
     * @SWG\Response(
     *          response="Medias",
     *          description="Array of Media objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/MediaGet"),
     *              )
     *          )
     * ),
     * @SWG\Response(
     *          response="Media",
     *          ref="$/responses/200",
     *          description="Media object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/MediaGet"
     *              )
     *          )
     * ),
     */
    public function __construct(MediaRepository $mediaRepo)
    {
        
        $this->mediaRepository = $mediaRepo;
    }
    
    /**
     * @param CreateMediaAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/medias",
     *      summary="Store a newly created Media in storage",
     *      tags={"Media"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(
     *          name="title",
     *          description="any description for media content. Like in Instagram",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="type",
     *          required=true,
     *          in="formData",
     *          type="string",
     *          enum={"image","video"},
     *      ),
     *      @SWG\Parameter(
     *          name="mediable_id",
     *          description="id of object for media attach. For example, event_id",
     *          required=true,
     *          in="formData",
     *          type="integer",
     *          format="int32"
     *      ),
     *      @SWG\Parameter(
     *          name="mediable_type",
     *          description="Object for media attach",
     *          required=true,
     *          in="formData",
     *          type="string",
     *          enum={"event"},
     *      ),
     *      @SWG\Parameter(
     *          name="file",
     *          description="Add file here if type=image",
     *          in="formData",
     *          type="file"
     *      ),
     *      @SWG\Parameter(
     *          name="video_link",
     *          description="Add youtube link here if type=video",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Media"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     */
    
    public function store(CreateMediaAPIRequest $request)
    {
    
        $input = $request->except(['file', 'video_link']);
    
        if ($input[ 'mediable_type' ] == 'event') {
            $parentModel              = Event::find($input[ 'mediable_id' ]);
            $input[ 'mediable_type' ] = 'App\Models\Event';
        }
    
        if (empty($parentModel)) {
            return $this->sendError('Mediable object not found');
        }
    
        if ($input[ 'type' ] == 'image' && $input[ 'mediable_type' ] == 'App\Models\Event') {
            // save image to hard
            $image           = $request->file('file');
            $file_path       = $image->store('img/events/gallery');
            $input[ 'path' ] = $file_path;
        }
    
        if ($input[ 'type' ] == 'video' && $input[ 'mediable_type' ] == 'App\Models\Event') {
            $request->validate([
                'video_link' => [new Videolink()],
            ]);
            
            $input[ 'path' ] = $request->get('video_link');
        }
        
        
        
        $media = $this->mediaRepository->create($input);
        
        return $this->sendResponse($media->toArray(), 'Media saved successfully');
    }
    
    /**
     * @param int                   $id
     * @param UpdateMediaAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/medias/{id}",
     *      summary="Update the specified Media in storage",
     *      tags={"Media"},
     *      consumes={"multipart/form-data"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Media"),
     *      @SWG\Parameter(
     *          name="title",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Parameter(
     *          name="video_link",
     *          description="Add youtube link here if type=video",
     *          in="formData",
     *          type="string"
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Media"),
     *      @SWG\Response(response="404", description="Media not found"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     */
    
    public function update($id, UpdateMediaAPIRequest $request)
    {
    
        $input[ 'title' ] = $request->get('title');
        
        /** @var Media $media */
        $media = $this->mediaRepository->find($id);
        
        if (empty($media)) {
            return $this->sendError('Media not found');
        }
        
        $media = $this->mediaRepository->update($input, $id);
        
        return $this->sendResponse($media->toArray(), 'Media updated successfully');
    }
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Delete(
     *      path="/medias/{id}",
     *      summary="Remove the specified Media from storage",
     *      tags={"Media"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Media"),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="Media not found"),
     * )
     */
    
    public function destroy($id)
    {
        
        /** @var Media $media */
        $media = $this->mediaRepository->find($id);
        
        if (empty($media)) {
            return $this->sendError('Media not found');
        }
        
        $media->delete();
        
        return $this->sendResponse($id, 'Media deleted successfully');
    }
}
