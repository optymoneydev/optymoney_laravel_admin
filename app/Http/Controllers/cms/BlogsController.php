<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Blogs;
use View;
use File;


class BlogsController extends Controller
{
    public function getBlogs(Request $request) {
        $blogsData = Blogs::orderBy('id', 'DESC')
            ->get()
            ->toJson();
        $data = [
            'blogsCategory' => $this->getBlogsCategory(),
            'blogs' => $blogsData
        ];
        return $data;
    }

    public function getBlogsAPI(Request $request) {
        $blogsData = Blogs::where('status', '=', "publish")->orderBy('id', 'DESC')
            ->get()
            ->toJson();
        $data = [
            'blogsCategory' => $this->getBlogsCategory(),
            'blogs' => $blogsData
        ];
        return $data;
    }

    public function getBlogsByCategory(Request $request) {
        $blogsData = Blogs::where('post_category', '=', $request->category)
            ->where('status', '=', "publish")
            ->orderBy('id', 'DESC')
            ->get()
            ->toJson();
        $data = [
            'blogs' => $blogsData
        ];
        return $data;
    }

    public function getBlogsData(Request $request) {
        if($request->category == "default") {
            $blogsData = Blogs::where('status', '=', "publish")->orderBy('id', 'DESC')->offset((int)$request->end)
            ->skip($request->start)
            ->take($request->end)
            ->limit(9)
                ->get()
                ->toJson();
        } else {
            $blogsData = Blogs::where('status', '=', "publish")->where('post_category', '=', $request->category)->orderBy('id', 'DESC')->offset((int)$request->end)
            ->skip($request->start)
            ->take($request->end)
            ->limit(9)
            ->get()
            ->toJson();
        }
        return $blogsData;
    }
    
    public function getBlogsCategory() {
        $blogsCategoryData = Blogs::get(['post_category'])
                ->groupBy('post_category')
                ->sortBy("post_category");
        return $blogsCategoryData;
    }

    public function saveBlog(Request $request) {
        $id = $request->session()->get('id');

        $blog = new Blogs();
        if($request['id'] != "") {
            $blog = Blogs::find($request['id']);
            $blog->id = $request['id'];
            $blog->post_modified_by = $id;
            $blog->post_modified_ip = $request->ip();
        } else {
            $blog->post_created_by = $id;
            $blog->post_created_ip = $request->ip();
        }
        $coverimage = request('coverimage');
        $thumbnailimage = request('thumbnailimage');
        $iconimage = request('iconimage');
        
        $blog->post_author = $request['post_author'];
        $blog->post_content = $request['post_content'];
        $blog->title = $request['title'];
        $blog->post_keywords = $request['post_keywords'];
        $blog->status = $request['status'];
        $blog->post_category = $request['post_category'];
        $blog->alt_attr = $request['alt_attr'];
        $blog->meta_keywords = $request['meta_keywords'];
        $blog->meta_description = $request['meta_description'];
        if($coverimage != "") {
            $blog->coverimage = "cover_".$coverimage->getClientOriginalName();
        }
        if($thumbnailimage != "") {
            $blog->thumbnailimage = "thumbnail_".$thumbnailimage->getClientOriginalName();
        }
        if($iconimage != "") {
            $blog->iconimage = "icon_".$iconimage->getClientOriginalName();
        }
        
        
        $saveblog = $blog->save();
        
        $allowedfileExtension=['pdf','jpg','png','docx'];
        $path = public_path('uploads').'/blogs/'.$blog->id;

        if(!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        } else {
        }

        $arr = [];
        if($coverimage != "") {
            $filename = $coverimage->getClientOriginalName();
            $extension = $coverimage->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            if($check) {
                $file_upload_status = $coverimage->move($path, "cover_".$filename);
                $arr[] = "cover_".$filename;
            }
        }
        if($thumbnailimage != "") {
            $filename = $thumbnailimage->getClientOriginalName();
            $extension = $thumbnailimage->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            if($check) {
                $file_upload_status = $thumbnailimage->move($path, "thumbnail_".$filename);
                $arr[] = "thumbnail_".$filename;
            }
        }
        if($iconimage != "") {
            $filename = $iconimage->getClientOriginalName();
            $extension = $iconimage->getClientOriginalExtension();
            $check=in_array($extension,$allowedfileExtension);
            if($check) {
                $file_upload_status = $iconimage->move($path, "icon_".$filename);
                $arr[] = "icon_".$filename;
            }
        }
        
        if($saveblog==1) {
            if($request['id'] != "") {
                $data = [
                    'status_code' => 201,
                    'message' => 'Blog updated successfully.'
                ];
            } else {
                $data = [
                    'status_code' => 201,
                    'message' => 'Blog added successfully.'
                ];
            }
        } else {
            if($request['id'] != "") {
                $data = [
                    'status_code' => 400,
                    'message' => 'Blog updation failed.'
                ];
            } else {
                $data = [
                    'status_code' => 400,
                    'message' => 'Blog adding failed.'
                ];
            }
        }
        return $data;
    }

    public function blogById(Request $request) {
        $blogData = Blogs::where('id', '=', $request->id)->get()->first();
        return $blogData;
    }

    public function deleteBlogById(Request $request) {
        $blogData = Blogs::where('id', '=', $request->id)->delete();
        return $blogData;
    }

    public function getBlogDataBySlug(Request $request) {
        $blogData = Blogs::where('slug', '=', $request->slug)->get()->first();
        return $blogData;
    }


}
