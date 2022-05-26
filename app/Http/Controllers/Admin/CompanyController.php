<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Route;

use App\Admin;

use Auth;
use Config;

class CompanyController extends Controller
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
		
		$query 		= Admin::where('role', '=', '2');
		
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
		
		return view('Admin.company.index',compact(['lists', 'totalData']));	
	} 
	 
	public function create(Request $request)
	{
		return view('Admin.company.create');	
	}
	
	public function store(Request $request)
	{
		 /* $check = $this->checkAuthorizationAction('cmspages', $request->route()->getActionMethod(), Auth::user()->role);
			if($check)
			{
				return Redirect::to('/admin/dashboard')->with('error',config('constants.unauthorized'));
			} */	
		if ($request->isMethod('post')) 
		{
			$this->validate($request, [
										'company_name' => 'required|max:255',
										'first_name' => 'required|max:255',
										'last_name' => 'required|max:255',
										'email' => 'required|max:255|unique:admins',
										'password' => 'required|max:255',
										'phone' => 'required|max:255',
										'company_website' => 'required|max:255'
									  ]);
			
			$requestData 		= 	$request->all();
			
			$obj				= 	new Admin;
			$obj->company_name	=	@$requestData['company_name'];
			$obj->first_name	=	@$requestData['first_name'];
			$obj->last_name		=	@$requestData['last_name'];
			$obj->email			=	@$requestData['email'];
			$obj->password	=	Hash::make(@$requestData['password']);
			$obj->role	=	2;
			$obj->phone	=	@$requestData['phone'];
			$obj->company_website	=	@$requestData['company_website'];
			$obj->address	=	@$requestData['address'];
			$obj->status	=	@$requestData['status'];
			
			/* Profile Image Upload Function Start */						  
					if($request->hasfile('profile_img')) 
					{	
						$profile_img = $this->uploadFile($request->file('profile_img'), Config::get('constants.profile_imgs'));
					}
					else
					{
						$profile_img = NULL;
					}		
				/* Profile Image Upload Function End */	
			$obj->profile_img			=	@$profile_img;
			$saved				=	$obj->save();  
			
			if(!$saved)
			{
				return redirect()->back()->with('error', Config::get('constants.server_error'));
			}
			else
			{
				return Redirect::to('/admin/company')->with('success', 'Company added Successfully');
			}				
		}			
	}
	
	public function edit(Request $request, $id = NULL)
	{	
		if ($request->isMethod('post')) 
		{
			$requestData 		= 	$request->all();
			$this->validate($request, [
										'company_name' => 'required|max:255',
										'first_name' => 'required|max:255',
										'last_name' => 'required|max:255',
										'email' => 'required|max:255|unique:admins,email,'.$requestData['id'],
										
										'phone' => 'required|max:255',
										'company_website' => 'required|max:255'
									  ]);
			
			//$requestData 		= 	$request->all();
			
			$obj				= 	 Admin::find($requestData['id']);
			$obj->company_name	=	@$requestData['company_name'];
			$obj->first_name	=	@$requestData['first_name'];
			$obj->last_name		=	@$requestData['last_name'];
			$obj->email			=	@$requestData['email'];
			if(!empty(@$requestData['password']))
				{		
					$obj->password				=	Hash::make(@$requestData['password']);
					//$objAdmin->decrypt_password		=	@$requestData['password'];
				}
			$obj->role	=	2;
			$obj->phone	=	@$requestData['phone'];
			$obj->company_website	=	@$requestData['company_website'];
			$obj->address	=	@$requestData['address'];
			$obj->status	=	@$requestData['status'];
			
			/* Profile Image Upload Function Start */						  
					if($request->hasfile('profile_img')) 
			{	
				/* Unlink File Function Start */ 
					if($requestData['profile_img'] != '')
						{
							$this->unlinkFile($requestData['old_profile_img'], Config::get('constants.profile_imgs'));
						}
				/* Unlink File Function End */
				
				$profile_img = $this->uploadFile($request->file('profile_img'), Config::get('constants.profile_imgs'));
			}
			else
			{
				$profile_img = @$requestData['old_profile_img'];
			}		
				/* Profile Image Upload Function End */	
			$obj->profile_img			=	@$profile_img;
			$saved				=	$obj->save();  
			
			if(!$saved)
			{
				return redirect()->back()->with('error', Config::get('constants.server_error'));
			}
			else
			{
				return Redirect::to('/admin/company')->with('success', 'Company added Successfully');
			}				
		}	
		else
		{	
			if(isset($id) && !empty($id))
			{
				$id = $this->decodeString($id);	
				if(Admin::where('id', '=', $id)->exists()) 
				{
					$fetchedData = Admin::find($id);
					return view('Admin.company.edit', compact(['fetchedData']));
				}
				else
				{
					return Redirect::to('/admin/company')->with('error', 'Company'.Config::get('constants.not_exist'));
				}	
			}
			else
			{
				return Redirect::to('/admin/company')->with('error', Config::get('constants.unauthorized'));
			}		
		}				
	}
	
}
