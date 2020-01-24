<?php namespace Tests\Repositories;

use App\Models\Media;
use App\Repositories\MediaRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;

class MediaRepositoryTest extends TestCase
{
    
    use ApiTestTrait, DatabaseTransactions;
    
    /**
     * @var MediaRepository
     */
    protected $mediaRepo;
    
    public function setUp(): void
    {
        
        parent::setUp();
        $this->mediaRepo = \App::make(MediaRepository::class);
    }
    
    /**
     * @test create
     */
    public function test_create_media()
    {
        
        $media = factory(Media::class)->make()->toArray();
        
        $createdMedia = $this->mediaRepo->create($media);
        
        $createdMedia = $createdMedia->toArray();
        $this->assertArrayHasKey('id', $createdMedia);
        $this->assertNotNull($createdMedia[ 'id' ], 'Created Media must have id specified');
        $this->assertNotNull(Media::find($createdMedia[ 'id' ]), 'Media with given id must be in DB');
        $this->assertModelData($media, $createdMedia);
    }
    
    /**
     * @test read
     */
    public function test_read_media()
    {
        
        $media = factory(Media::class)->create();
        
        $dbMedia = $this->mediaRepo->find($media->id);
        
        $dbMedia = $dbMedia->toArray();
        $this->assertModelData($media->toArray(), $dbMedia);
    }
    
    /**
     * @test update
     */
    public function test_update_media()
    {
        
        $media     = factory(Media::class)->create();
        $fakeMedia = factory(Media::class)->make()->toArray();
        
        $updatedMedia = $this->mediaRepo->update($fakeMedia, $media->id);
        
        $this->assertModelData($fakeMedia, $updatedMedia->toArray());
        $dbMedia = $this->mediaRepo->find($media->id);
        $this->assertModelData($fakeMedia, $dbMedia->toArray());
    }
    
    /**
     * @test delete
     */
    public function test_delete_media()
    {
        
        $media = factory(Media::class)->create();
        
        $resp = $this->mediaRepo->delete($media->id);
        
        $this->assertTrue($resp);
        $this->assertNull(Media::find($media->id), 'Media should not exist in DB');
    }
}
