<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pages;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $pages;

    public function __construct(Pages $pages)
    {
        $this->pages=$pages;
    }

    public function index()
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        if(request()->ajax() || request()->wantsJson()){

      
            $sort = request()->has('sort')?request()->get('sort'):'title_en';
            $order = request()->has('order')?request()->get('order'):'asc';
            $search = request()->has('searchQuery')?request()->get('searchQuery'):'';
            $pages = $this->pages->where(function($query) use ($search)
            {
                if ($search) {
                    $query->where('title_en','like',"$search%")
                        ->orWhere('title_ar','like',"$search%");
                }
            })
            ->orderBy("$sort", "$order")->paginate(10);
            
            $paginator=[
                'total_count'  =>$pages->total(),
                'total_pages'  => $pages->lastPage(),
                'current_page' => $pages->currentPage(),
                'limit'        => $pages->perPage()
            ];
            return response([
                "data"        =>$pages->all(),
                "paginator"   =>$paginator,
                "status_code" =>200
            ],200);
        }
        return view('admin.pages.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        return view('admin.pages.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        // VALIDATION OF INPUT
       
        $validator = validator()->make($request->all(), [
            'title_en'   =>'required|max:255',
            'title_ar'   =>'required|max:255',
            'description_en' => 'required',
            'description_ar' => 'required',
            'slug'    => 'max:255|unique:pages|required',
        ]);
       
        if ($validator->fails()) {
            flash(trans("messages.parameters-fail-validation"),'danger');
            return back()->withErrors($validator)->withInput();
        }

        $input =array_only($request->all(),[
            "title_en","title_ar","description_en",'description_ar','slug'
        ]);
        
        $newPage = $this->pages->create($input);
        
        flash(trans('messages.page-add'),'success');
        return back();
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
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        $page=$this->pages->find($id);
        if(!$page){
            flash(trans("messages.page-not-found"),'info');
            return back();
        }
        
        return view('admin.pages.edit',compact('page'));
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
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        $page=$this->pages->find($id);
        if(!$page){
            flash(trans("messages.page-not-found"),'info');
            return back();
        }
        // VALIDATION OF INPUT
        $validator = validator()->make($request->all(), [
            'title_en'   =>'required|max:255',
            'title_ar'   =>'required|max:255',
            'description_en' => 'required',
            'description_ar' => 'required',
            'slug'    => 'max:255|required|unique:pages,slug,'.$id,
        ]);
        
        if ($validator->fails()) {
            flash(trans("messages.parameters-fail-validation"),'danger');
            return back()->withErrors($validator)->withInput();
        }
        # Prepare input
        $input =array_only($request->all(),[
            "title_en","title_ar","description_en",'description_ar','slug'
        ]);
        
        
        extract($input);

      
        $page->title_en         =$title_en;
        $page->title_ar         =$title_ar;
        $page->description_en   =$description_en;
        $page->slug             =$slug;
        $page->description_ar   =$description_ar;
        $page->save();
        
        # Respond in JSON
        flash(trans('messages.page-update'),'success');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        $page=$this->pages->find($id);
        $page->delete();
        return response([
            "data"=>[],
            "message"=>trans('messages.page-distroy'),
            "status_code"=>200
        ],200);
    }
    public function destroyBulk(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        $this->pages->destroy($request->all());
        return response([
            "data"=>[],
            "message"=>trans('messages.page-distroy'),
            "status_code"=>200
        ],200);
    }
    public function switchStatus(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        $validator = validator()->make($request->all(), [
            'id'   =>'required'
        ]);
        if ($validator->fails()) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        extract($request->all());
        $page= $this->pages->find($id);
        if($page){
            $newStatus = ($page->status=='1')?'0':'1';
            $page->status=$newStatus;
            $page->save();
            // Get New updated Object of User
            $updated = $page->toArray();

            if($request->wantsJson()){
                return response([
                    "data"        =>$updated,
                    "message"     =>trans('messages.page-status'),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.page-status'),'success');
            return back();
        }
        flash(trans('messages.page-update-fail'),'error');
        return back();
    }
    public function switchStatusBulk(Request $request){
        if (\Gate::denies('developerOnly') && \Gate::denies('pages')) {
            return back();
        }
        $input= $request->all();
        if (count($input)==0) {
            return response(["error"=>trans('messages.parameters-fail-validation')],422);
        }
        $pages= $this->pages->whereIn('id',$request->all())->get();
        if($pages->count() > 0){
            foreach ($pages as $page) {
                $newStatus = ($page->status=='1')?'0':'1';
                $page->status=$newStatus;
                $page->save();
            }

            if($request->wantsJson()){
                return response([
                    "data"=>[],
                    "message"     =>trans('messages.page-status',["status"=>"updated"]),
                    "status_code" =>200
                ],200);
            }
            flash(trans('messages.page-status',["status"=>"updated"]),'success');
            return back();
        }
        flash(trans('messages.page-update-fail'),'error');
        return back();
    }
}
