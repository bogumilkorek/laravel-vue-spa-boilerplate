<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Http\Resources\PageResource;
use App\Page;
use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['jwt'])->except(['show', 'getNav']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PageResource::collection(Page::orderBy('order', 'asc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PageRequest $request)
    {
        $page = Page::create($request->all() + ['order' => Page::max('order') + 1]);

        Image::where('form_token', $request->form_token)->update(['imageable_id' => $page->id, 'form_token' => NULL]);

        return new PageResource($page);
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
       if($slug == 'undefined')
            $page = Page::where('nav', 1)->orderBy('order', 'asc')->first();
       else         
            $page = Page::where('slug', $slug)->first();
            
       if($page)
            return new PageResource($page);
        else
            return response('Page not found', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PageRequest  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request, int $id)
    {
        $page = Page::findOrFail($id);
        
        if($page->update($request->all()))
            return new PageResource($page);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $page = Page::findOrFail($id);
       
        $images = Image::where([
            ['imageable_id', $page->id],
            ['imageable_type', 'page']
        ])->get();
    
          if($images->isNotEmpty())
          {
            foreach($images as $image)
            {
              if(File::exists(public_path('/photos/upload/' . $image->url)))
                File::delete(public_path('/photos/upload/' . $image->url));
              if(File::exists(public_path('/photos/upload/thumbs/' . $image->url)))
                File::delete(public_path('/photos/upload/thumbs/' . $image->url));
    
              $image->delete();
            }
          }

        if($page->delete())
            return new PageResource($page);
    }

    /**
     * Reorder pages (response from drag & drop).
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $newOrder) {
            $page = Page::findOrFail($newOrder);
            $page->order = $index + 1;
            $page->save();
        }
        return 'Order updated!';
    }

    /**
     * Get visible pages for nav
     *
     * @return \Illuminate\Http\Response
     */
    public function getNav()
    {
        return Page::where('nav', 1)->orderBy('order', 'asc')->get(['title', 'slug']);
    }
}
