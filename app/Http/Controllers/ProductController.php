<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

  // public function __construct
    
  public function __construct()
    {
        $this->middleware('auth')->except([
          'show','index'
      ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $products = product::latest()->paginate(5);
        return view('product.index',compact('products'))->with('i',(request()->input('page',1) -1 ) *5 );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      // dd($request->all());
    //   $validator = Validator::make($request->all(), [
    //     'name'=>'required',
    //     'detailes'=>'required',
    //     'image'=>'required|image|mimes:jpg,jpeg,png,svg,gif|max:1024',
    // ]);
          $request->validate([
            'name'=>'required',
            'detailes'=>'required',
            'image'=>'required|image|mimes:jpg,jpeg,png,svg,gif|max:1024',
          ]);
          // if ($validator->fails()) {
          //   return $validator->errors();
          // }
          
          $input=$request->all();
          if($image = $request->file('image'))
          {
            
            $destinationPath="Images/";
            $profileImage = date('YmdHis').".".$image->getClientOriginalExtension();
            $image->move($destinationPath,$profileImage);
            $input['image']=$profileImage;
          }

          product::create($input);
          return redirect()->route('product.index')->with('success','Product Add Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
      return view('product.show',compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        return view('product.update',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        $validator = Validator::make($request->all(), [
        'name'=>'required',
        'detailes'=>'required',
        'image'=>'image|mimes:jpg,jpeg,png,svg,gif|max:1024',
    ]);
    if ($validator->fails()) {
            return $validator->errors();
          }
      $input=$request->all();
      if($image = $request->file('image'))
      {
        
        $destinationPath="Images/";
        $profileImage = date('YmdHis').".".$image->getClientOriginalExtension();
        $image->move($destinationPath,$profileImage);
        $input['image']=$profileImage;
      }
      else{
        unset($input['image']);
      }

      $product->update($input);
      return redirect()->route('product.index')->with('success','Product Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        $product->delete();
        return redirect()->route('product.index')->with('success','Product Deleted Successfully');
    }
}
