<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as ImageLib;

class ImageController extends Controller
{
  public function __construct()
  {
    $this->middleware(['jwt']);
  }

  public function index()
  {
  }

  public function store(Request $request)
  {
    if($request->hasFile('file'))
    {
      $imageFile = $request->file('file');
      $imageName = uniqid() . '-' . str_slug(preg_replace('/\\.[^.\\s]{3,4}$/', '', $imageFile->getClientOriginalName())) . '.' . $imageFile->getClientOriginalExtension();
      $imageFile->move(public_path('photos/upload'), $imageName);

      $image = Image::create([
        'url' => $imageName,
        'size' => File::size(public_path('photos/upload'), $imageName),
        'imageable_type' => $request->type,
        'imageable_id' => $request->id,
        'form_token' => $request->token,
      ]);

      ImageLib::make(public_path('/photos/upload/' . $imageName))
      ->fit(265, 149)
      ->save(public_path('/photos/upload/thumbs/' . $imageName));

      return $imageName;

    }
  }

  public function destroy(Request $request)
  {
    $image = Image::where('url', $request->url)->first();

    if($image)
    {
      if(File::exists(public_path('/photos/upload/' . $request->url)))
        File::delete(public_path('/photos/upload/' . $request->url));
      if(File::exists(public_path('/photos/upload/thumbs/' . $request->url)))
        File::delete(public_path('/photos/upload/thumbs/' . $request->url));

      $image->delete();

      return 'Image deleted';
    }
    else
      return 'Image not found';
  }
}
