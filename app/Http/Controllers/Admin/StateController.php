<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Route;

use App\State;

use Auth;
use Config;

class StateController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {	
        $this->middleware('auth:admin');
	}
	/**
     * All Cms Page.
     *
     * @return \Illuminate\Http\Response
     */ 
	public function index(Request $request)
	{ 
		
		$query 		= State::where('id', '!=', '')->with(['countrydata']);
		
		$totalData 	= $query->count();	//for all data
		
		if ($request->has('search_term')) 
		{
			$search_term 		= 	$request->input('search_term');
			if(trim($search_term) != '')
			{		
				$query->where('title', 'LIKE', '%' . $search_term . '%');
			}
		}
		
		if ($request->has('search_term') || $request->has('search_term_from') || $request->has('search_term_to')) 
		{
			$totalData 	= $query->count();//after search
		}
		
		$lists		= $query->orderby('id','DESC')->get();
		
		return view('Admin.state.index',compact(['lists', 'totalData']));	
	} 
	
	public function create(Request $request)
	{
		return view('Admin.state.create');	
	}
	
	public function store(Request $request)
	{
		if ($request->isMethod('post')) 
		{
			$this->validate($request, [
					'name' => 'required|max:255',
			]);
			$requestData 		= 	$request->all();
						
			$obj				= 	new State;
			$obj->name			=	@$requestData['name'];
			$obj->country_id			=	@$requestData['country_id'];
			$saved				=	$obj->save();  
			 
			if(!$saved)
			{
				return redirect()->back()->with('error', Config::get('constants.server_error'));
			}
			else
			{
				return Redirect::to('/admin/state')->with('success', 'State added Successfully');
			}
		}			
	}
	
	public function edit(Request $request, $id = NULL)
	{	
		//check authorization start	
			 /* $check = $this->checkAuthorizationAction('cmspages', $request->route()->getActionMethod(), Auth::user()->role);
			if($check)
			{
				return Redirect::to('/admin/dashboard')->with('error',config('constants.unauthorized'));
			} */	
		//check authorization end
	
		if ($request->isMethod('post')) 
		{
			$requestData 		= 	$request->all(); 
			//echo $requestData['id']; die;
			$this->validate($request, [
					'name' => 'required|max:255',
										
									  ]);
					
			$obj				= 	State::find(@$requestData['id']);
			$obj->name			=	@$requestData['name'];
			$obj->country_id			=	@$requestData['country_id']; 
			$saved				=	$obj->save();
			
			if(!$saved)
			{
				return redirect()->back()->with('error', Config::get('constants.server_error'));
			}
			else
			{
				return Redirect::to('/admin/state')->with('success', 'State'.Config::get('constants.edited'));
			}				
		}
		else
		{	
			if(isset($id) && !empty($id))
			{
				$id = $this->decodeString($id);	
				if(State::where('id', '=', $id)->exists()) 
				{
					$fetchedData = State::find($id);
					return view('Admin.state.edit', compact(['fetchedData']));
				}
				else
				{
					return Redirect::to('/admin/state')->with('error', 'State'.Config::get('constants.not_exist'));
				}	
			}
			else
			{
				return Redirect::to('/admin/state')->with('error', Config::get('constants.unauthorized'));
			}		
		}				
	}
	
}
