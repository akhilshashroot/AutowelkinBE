<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiscussionTitle;
use App\Models\Admin;
use App\Models\User;
use App\Models\Discussion;

class HashBookController extends Controller
{
    public function index(Request $request)
    {
        $result = array();
		// $re = Discussion::getall($request->user_id);
        $authors=DiscussionTitle::select('discussion_title.id','u.fullname','discussion_title.title', 'discussion_title.user_id' ,'discussion_title.created', 'a.name')
        ->leftjoin('users as u', 'u.id', '=' ,'discussion_title.user_id')
        ->leftjoin('admin_login as  a', 'a.id', '=' ,'discussion_title.user_id')
        ->where('discussion_title.type', 'main')
        ->where('discussion_title.is_active',1)->orderby('discussion_title.id','desc')->get();

		// if($re == null){
		// 	$re = Admin::find($request->user_id);
		// 	$result['fullname'] = $re->name;
        //     $result['usertype'] = 'admin';
        //     // $name=$re->name;

		// }else{
		// 	$result['fullname'] = $re->fullname;
        //     $result['usertype'] = 'user';
        //     // $name=$re->fullname;

		// }
        // $result['user_id'] = $request->user_id;
        $list_data=[];
       foreach($authors as $author ){
        $list['id']=$author->id;

           if($author->fullname){
            $list['Author']=$author->fullname;

           }else{
            $list['Author']=$author->name;

           }
        $list['user_id']=$author->user_id;
        $list['title']=$author->title;
        $list['date']=$author->created->format('d-m-Y');
		array_push( $list_data, $list);
    }
      // $result['hashbook_data']=$list_data;
        return response()->json([
            'data' => $list_data,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'user_id' => 'required',
        ]);
        $title = $request->title;
		$user_id = $request->user_id;
        $discussion_title = new DiscussionTitle;
        $discussion_title->user_id =  $user_id ;
        $discussion_title->title =  $title ;
        $result = $discussion_title->save();
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $DiscussionTitle = DiscussionTitle::find($id);
        if(!$DiscussionTitle){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $DiscussionTitle->title =$request->title;
        $DiscussionTitle->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $result = DiscussionTitle::where('id',$id)->update(['is_active'=>0]);
        $result_del = DiscussionTitle::where('subtitle_id',$id)->update(['is_active'=>0]);
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
       
    }
    public function get_authors(Request $request)
    {
        $result = array();
	//	$re = Discussion::getall($request->user_id);
        $authors=DiscussionTitle::select('u.fullname', 'discussion_title.user_id', 'a.name')
        ->leftjoin('users as u', 'u.id', '=' ,'discussion_title.user_id')
        ->leftjoin('admin_login as a', 'a.id', '=' ,'discussion_title.user_id')
        ->groupby('discussion_title.user_id')->get();
        $list_data=[];
	   foreach($authors as $author){

        if($author->fullname){
            $list['Author']=$author->fullname;

           }else{
            $list['Author']=$author->name;

           }
        $list['user_id']=$author->user_id;
        $list['title']=$author->title;  
        array_push( $list_data, $list);

         }
		//$result['authors'] = $list_data;
        return response()->json([
            'data' =>$list_data,
            'message' => 'Success'
        ], 200);
       
    }

    
	public function create_subtitle(Request $request){
        $validated = $request->validate([
            'title' => 'required',
            'user_id' => 'required',
        ]);
		$title =  $request->title;
		$discussion_id = $request->discussion_id;
		$user_id =  $request->user_id;
        $Discussion = DiscussionTitle::find($discussion_id);
        if(!$Discussion){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $DiscussionTitle=new DiscussionTitle();
        $DiscussionTitle->title =$title;
        $DiscussionTitle->subtitle_id =$discussion_id;
        $DiscussionTitle->user_id =$user_id;
        $DiscussionTitle->type = 'sub';

        $DiscussionTitle->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);

	}

    public function post_comments(Request $request){
		
        $validated = $request->validate([
            'title' => 'required',
            'user_id' => 'required',
            'discussion_id'=> 'required',
        ]);
		$title =  $request->title;
		$discussion_id = $request->discussion_id;
		$user_id =  $request->user_id;
        $DiscussionTitle = DiscussionTitle::find($discussion_id);
        if(!$DiscussionTitle){
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
        }
        $Discussion=new Discussion();
        $Discussion->discussion =$title;
        $Discussion->d_id =$discussion_id;
        $Discussion->user_id =$user_id;

        $Discussion->save();   
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);

	}

    public function details(Request $request,$id,$user_id){
		$re = Discussion::getall($user_id);
		if($re == null){
			$re = Admin::find($user_id);
			$result['fullname'] = $re->name;
            $result['usertype'] = 'admin';
            $name=$re->name;

		}else{
			$result['fullname'] = $re->fullname;
            $result['usertype'] = 'user';
             $name=$re->fullname;

		}
        $result['user_id'] = $user_id;
		$result['discussion_id'] = $id;
		$result['subtitles'] = DiscussionTitle::get_subtitles($id);
		$discussion = DiscussionTitle::get_discussion_details($id);
        $discussion = Discussion::where('d_id',$id)->get();

        $list_data=[];
        foreach($discussion as $list){
            $listing['id']=$list->id;
            $name=Admin::find($list->user_id);
           if($name){
               $listing['name']=$name->name;
            }else{
              $fullname=User::find($list->user_id);
               $listing['name']=$fullname->fullname;
              }
            $listing['title']=$list->discussion;
            $listing['date']=$list->created;
            array_push( $list_data, $listing);

      }
      $result['discussion'] =$list_data;

	    return response()->json([
            'data' => $result,
            'message' => 'Success'
        ], 200);
	}
}

