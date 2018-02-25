<?php

namespace Tests\Feature;

use Tests\Traits\ApiAsTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Page;

class PageTest extends TestCase
{
    use RefreshDatabase, ApiAsTrait;
    
    public function getUser() {
        $user = User::first() ?? factory('App\User', 1)->create();
        return User::first();
    }

    /**
     * Get pages list
     *
     * @return void
     */
    public function testGetPages()
    {
        $pages = factory('App\Page', 10)->create();

        $this->apiAs($this->getUser(), 'GET', '/api/pages')->assertStatus(200);
    }

    /**
     * Get single page
     *
     * @return void
     */
    public function testGetSinglePage()
    {
        $page = factory('App\Page')->create();

        $this->apiAs($this->getUser(), 'GET', '/api/pages/' . Page::first()->id)->assertStatus(200);
    }

    /**
     * Try to get non-existing page
     *
     * @return void
     */
    public function testGetNonExistingPage()
    {
        $this->apiAs($this->getUser(), 'GET', '/api/pages/1')->assertStatus(404);
    }

    /**
     * Create new page
     *
     * @return void
     */
    public function testCreatePage()
    {
        $page = factory('App\Page')->make()->toArray();

        $this->apiAs($this->getUser(), 'POST', '/api/pages', $page)->assertStatus(201);
    }

    /**
     * Try to create new page without auth
     *
     * @return void
     */
    public function testCreatePageWithoutAuth()
    {
        $page = factory('App\Page')->make()->toArray();

        $this->apiAs(null, 'POST', '/api/pages', $page)->assertStatus(401);
    }

    /**
     * Try to create new page with duplicated title
     *
     * @return void
     */
    public function testCreatePageWithDuplicatedTitle()
    {
        $page = factory('App\Page')->create();

        $this->apiAs($this->getUser(), 'POST', '/api/pages', [
            $page->title, 
            $page->content
        ])->assertStatus(422);
    }

    /**
     * Update page
     *
     * @return void
     */
    public function testUpdatePage()
    {
        $page = factory('App\Page')->create();

        $this->apiAs($this->getUser(), 'PUT', '/api/pages/' . Page::first()->id, [
            'title' => 'Updated title',
            'content' => 'Updated content'
        ])->assertStatus(200);
    }

    /**
     * Try to update page without auth
     *
     * @return void
     */
    public function testUpdatePageWithoutAuth()
    {
        $page = factory('App\Page')->create();

        $this->apiAs(null, 'PUT', '/api/pages/' . Page::first()->id, [
            'title' => 'Updated title',
            'content' => 'Updated content'
        ])->assertStatus(401);
    }

    /**
     * Try to update page with duplicated title
     *
     * @return void
     */
    public function testUpdatePageWithDuplicatedTitle()
    {
        $page = factory('App\Page')->create();

        $this->apiAs($this->getUser(), 'PUT', '/api/pages/' . Page::first()->id, [
            'title' => $page->title,
            'content' => $page->content
        ])->assertStatus(422);
    }

     /**
     * Try to update non-existing page
     *
     * @return void
     */
    public function testUpdateNonExistingPage()
    {
        $this->apiAs($this->getUser(), 'PUT', '/api/pages/1', [
            'title' => 'Title',
            'content' => 'Content'
        ])->assertStatus(404);
    }

    /**
     * Delete page
     *
     * @return void
     */
    public function testDeletePage()
    {
        $page = factory('App\Page')->create();

        $this->apiAs($this->getUser(), 'DELETE', '/api/pages/' . Page::first()->id)->assertStatus(200);
    }

    /**
     * Try to delete non-existing page
     *
     * @return void
     */
    public function testDeleteNonExistingPage()
    {
        $this->apiAs($this->getUser(), 'DELETE', '/api/pages/1')->assertStatus(404);
    }

     /**
     * Try to delete page with duplicated title
     *
     * @return void
     */
    public function testDeletePageWithoutAuth()
    {
        $page = factory('App\Page')->create();

        $this->apiAs(null, 'DELETE', '/api/pages/' . Page::first()->id)->assertStatus(401);
    }
}
