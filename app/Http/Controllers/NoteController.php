<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Http\Resources\NoteResource;
use App\Http\Validation\NoteStoreValidation;
use App\Http\Validation\NoteUpdateValidation;

class NoteController extends Controller
{
    public function index()
    {
        $result_all = Note::get();
        if(count($result_all)==0){
            $this->response['err'] = false;
            $this->response['msg'] = "Success Note_Index";
            $this->response['data'] = "No Data";
            $this->response['code'] = 200;
            return response()->json($this->response,$this->response['code']); 
        }
        $this->response['err'] = false;
        $this->response['msg'] = "Success Note_Index";
        $this->response['data'] = NoteResource::collection($result_all);
        $this->response['code'] = 200;
        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        
        $validate_request = new NoteStoreValidation();
        $error = $validate_request->execValidation($request);

        if($error){
            $this->response['err'] = true;
            $this->response['msg'] = "Failed Note_store";
            $this->response['data'] = $error;
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']); 
        }

        $input =$request->input('data.attributes');
        $input['created_at'] = date('Y-m-d H:i:s');
        $input['created_by'] = $request->user_email;
        // dd($input);
        $note = Note::create($input);

        if(!$note ){
            $this->response['err'] = true;
            $this->response['msg'] = "Failed insert data";
            $this->response['data'] = [];
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']);  
        }

        $this->response['err'] = false;
        $this->response['msg'] = "Success Note_store";
        $this->response['data'] = (new NoteResource($note));
        $this->response['code'] = 200;
        return response()->json($this->response,$this->response['code']); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $result_find = Note::find($id);
        if(!$result_find){
            $this->response['err'] = true;
            $this->response['msg'] = "Data doesnt Exist";
            $this->response['data'] = [];
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']); 
        }

        $this->response['err'] = false;
        $this->response['msg'] = "Success Note_show";
        $this->response['data'] = (new NoteResource($result_find));
        $this->response['code'] = 200;
        return response()->json($this->response,$this->response['code']); 
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
        $validate_request = new NoteUpdateValidation();
        $error = $validate_request->execValidation($request);

        if($error){
            $this->response['err'] = true;
            $this->response['msg'] = "Failed Note_update";
            $this->response['data'] = $error;
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']); 
        }

        $result_find = Note::find($id);

        if(!$result_find){
            $this->response['err'] = true;
            $this->response['msg'] = "Data doesnt Exist";
            $this->response['data'] = [];
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']); 
        }

        $input =$request->input('data.attributes');
        $input['updated_at'] = date('Y-m-d H:i:s'); 
        $input['updated_by'] = $request->user_email; 
        
        $update =  $result_find->update($input);

        if(!$update){
            $this->response['err'] = true;
            $this->response['msg'] = "Data failed to Update";
            $this->response['data'] = [];
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']);
        }
        
        $this->response['err'] = false;
        $this->response['msg'] = "Success Note_update";
        $this->response['data'] = (new NoteResource($result_find));
        $this->response['code'] = 200;
        return response()->json($this->response,$this->response['code']); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $result_find = Note::find($id);
        if(!$result_find){
            $this->response['err'] = true;
            $this->response['msg'] = "Data doesnt Exist";
            $this->response['data'] = [];
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']); 
        }

        $delete = Note::destroy($id);

        if(!$delete){
            $this->response['err'] = true;
            $this->response['msg'] = "Data failed to delete";
            $this->response['data'] = [];
            $this->response['code'] = 422;
            return response()->json($this->response,$this->response['code']); 
        }

        $this->response['err'] = false;
        $this->response['msg'] = "Success Note_delete";
        $this->response['data'] = [];
        $this->response['code'] = 200;        
        return response()->json($this->response,$this->response['code']); 

    }
}
