<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use App\Models\Company;

use Illuminate\Support\Facades\Validator;

use Datatables;
 
class DataTableAjaxCRUDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Company::select('*'))
            ->addColumn('action', 'company-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('companies');
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
        //return $request->all();
        $productId = $request->id;
        $name = $request->name;
        $price = $request->price;
        $min_quan = $request->min_quan;
        $max_quan = $request->max_quan;
        $description = $request->description;
        $status = $request->status;
		if ($productId == "") {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'price' => 'required|integer',
                    'description' => 'required',
                    'status' => 'required',
                    'image' => 'required',
                    'active' => 'required',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'price' => 'required|integer',
                    'description' => 'required',
                    'status' => 'required',
                    'active' => 'required',
                    
                ]);
            }
              
				
            if ($validator->fails()) {
				return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
				//return response()->json(['error'=>$validator->errors()->all()]);
                // return back()->withErrors($validator)->withInput();
            }
			
		 $image = "";
		 if($request->hasFile('image')) {
            $fileName = time().'.'.$request->image->extension();  
			$request->image->move(public_path('images/product'), $fileName);
            $image = $fileName;
        }
        if($image == "")
		{
	      $image = $request->image_hidden;		
		}
        $company   =   Company::updateOrCreate(
                    [
                     'id' => $productId
                    ],
                    [
                    'name' => $name, 
                    'price' => $price,
                    'min_quan' => $min_quan,
                    'max_quan' => $max_quan,
                    'description' => $description,
                    'image' => $image,
                    'status' => $status,
                    ]);    
                         
        return Response()->json($company);
 
    }
      
      
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $company  = Company::where($where)->first();
      
        return Response()->json($company);
    }
      
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $company = Company::where('id',$request->id)->delete();
      
        return Response()->json($company);
    }
}