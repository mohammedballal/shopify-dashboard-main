<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ShopUser;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules;
use PragmaRX\Google2FA\Google2FA;

class RegisteredUserController extends Controller
{

    public function list(){
        if (!(Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin")))
            abort(404);
        if (\request()->ajax()){
            $users = User::with("roles")->get();
            $data = array();
            // parsing the users' data to table format of json
            foreach ($users as $user){
                if($user->id == Auth::user()->id)
                    continue;
                $html = null;
                if (Auth::user()->hasRole("Super Admin")){
                    if (!empty($user->tag_id) && $user->tag_id != NULL)
                        foreach (json_decode($user->tag_id) as $tag){
                            $html .= '<a href="#"><span class="badge badge-light-info mb-25" title="'.$tag.'">' .$tag .'</span></a> <br>';
                        }
                    $temp = [
                        'responsive_id'=>"",
                        'id'=>"$user->id",
                        'role'=>$user->roles->first()->name ?? "N/A",
                        "full_name"=>($user->name ?? ($user->first_name." ".$user->last_name)),
                        'email'=>"$user->email",
                        'commission'=>$user->commission.' %',
                        'phone'=>@$user->phone?"$user->phone":'-',
                        'status'=>$user->status,
                        'tag_id'=>$html ?? "N/A",
                        'avatar'=> @$user->avatar?asset('media/users/avatar/'.$user->avatar):false,
                    ];

                    array_push($data,$temp);
                }
                elseif(Auth::user()->hasRole("Admin") && $user->hasRole("User")){
                    if (!empty($user->tag_id) && $user->tag_id != NULL)
                        foreach (json_decode($user->tag_id) as $tag){
                            $html .= '<a href="#"><span class="badge badge-light-info mb-25" title="'.$tag.'">' .$tag .'</span></a> <br>';
                        }
                    $temp = [
                        'responsive_id'=>"",
                        'id'=>"$user->id",
                        "full_name"=>($user->name ?? ($user->first_name." ".$user->last_name)),
                        'username'=>"",
                        'email'=>"$user->email",
                        'commission'=>$user->commission.' %',
                        'phone'=>@$user->phone?"$user->phone":'-',
                        'tag_id'=>$html ?? "N/A",
                        'status'=>$user->status,
                        'avatar'=> @$user->avatar?asset('media/users/avatar/'.$user->avatar):false,
                    ];

                    array_push($data,$temp);
                }
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.user.list');
    }

    public function show(){
        if (!(Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin") ))
            abort(404);
        $user = User::find(\request()->get("user_id"));
        // ajax call for the users orders from the shopify API
        if (\request()->ajax()){
//            $users = User::with("roles")->get();
//            $data = array();
//            foreach ($users as $user){
//                $temp = [
//                    'responsive_id'=>"",
//                    'id'=>"$user->id",
//                    'role'=>$user->roles->first()->name ?? "N/A",
//                    "full_name"=>($user->name ?? ($user->first_name." ".$user->last_name)),
//                    'username'=>"",
//                    'email'=>"$user->email",
//                    'phone'=>"$user->phone",
//                    'tag_id'=>$user->id,
//                    'status'=>$user->status,
//                    'avatar'=>$user->id."-small.png",
//                ];
//                array_push($data,$temp);
//            }
//            return response()->json(["data"=>$data]);
        }
        return view('template.user.show',compact('user'));
    }

    public function create()
    {
        return redirect(route("login"));
//        return view('auth.register');
    }

    public function edit()
    {
        if (!(Auth::user()->roles->first()->name == "Admin" || Auth::user()->hasRole("Super Admin")))
            abort(404);
        $user_id = \request()->get("user_id");
        $user = User::with('shops')->where('id',$user_id)->first();
        $shops = ShopUser::where('user_id',$user_id)->pluck('shop_id')->toArray();
        return view("template.user.edit",compact("user",'shops'));
//        return view('auth.register');
    }

    public function profile()
    {
        $user_id = \request()->get("user_id");
        $user = User::findorfail($user_id);
        return view("template.user.profile",compact("user"));
//        return view('auth.register');
    }

    public function store(Request $request)
    {
        if (!(Auth::user()->roles->first()->name == "Admin" || Auth::user()->hasRole("Super Admin")))
            abort(404);
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required'],
            'tag_id' => ['required_if:role,==,2'],
            'commission' => ['required_if:role,==,2'],
        ]);
        $inputs = $request->except(['_token']);

        $inputs['name'] = $request->first_name.' '.$request->last_name;
        $inputs['password'] = Hash::make($request->password);
        $inputs['avatar'] = saveFile($request,public_path('media/users/avatar/'),'avatar',$request->first_name,'avatar');
        $inputs['tag_id'] = @$request->tags?json_encode($request->tags):json_encode(array());
        $inputs['commission'] = $request->commission??0;
        $user = User::create($inputs);
        $user->assignRole($request->role);
        if (@$request->shops){
            $user->shops()->sync($request->shops);
        }
        return redirect()->back()->with('success','User Successfully created.');
    }

    public function update(Request $request)
    {
        if (!(Auth::user()->roles->first()->name == "Admin" || Auth::user()->hasRole("Super Admin")))
            abort(404);
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],

            'password' => ['nullable', Rules\Password::defaults()],
            'role' => ['required'],
            'tags' => ['required'],
            'commission' => ['required'],
        ]);
        $inputs = $request->except(['_token']);
        $user = User::findorfail($request->id);
        $inputs['name'] = $request->first_name.' '.$request->last_name;
        $inputs['password'] = @$request->password?Hash::make($request->password):$user->password;
        $inputs['tag_id'] = json_encode($request->tags);
        if ($request->hasFile("avatar")){
            removeFile(public_path('media/users/avatar/'),$user -> avatar);
            $inputs['avatar'] = saveFile($request,public_path('media/users/avatar/'),'avatar',$request->first_name,'avatar');
        }
        $user->update($inputs);
        if (count($user->roles))
            $user->removeRole($user->roles->first());
        $user->assignRole($request->role);
        $user->shops()->sync($request->shops);
        return redirect(route("users.list"))->with('success',ucwords($request->first_name).' Successfully updated.');
    }

    public function profile_update(Request $request)
    {
        if (!($request->id == Auth::user()->id))
            abort(403);
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);
        $inputs = $request->except(['_token']);
        $user = User::findorfail($request->id);
        $inputs['name'] = $request->first_name.' '.$request->last_name;
        $inputs['password'] = @$request->password?Hash::make($request->password):$user->password;
        if ($request->hasFile("avatar")){
            removeFile(public_path('media/users/avatar/'),$user -> avatar);
            $inputs['avatar'] = saveFile($request,public_path('media/users/avatar/'),'avatar',$request->first_name,'avatar');
        }
        $user->update($inputs);
        if (@$request->shops){
            $user->shops()->sync($request->shops);
        }
        return redirect()->back()->with('success','Profile Successfully updated.');
    }

    public function register(Request $request)
    {
        return redirect(route("login"));
        // Commenting the User Registration as no Registration is allowed
//        $request->validate([
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'confirmed', Rules\Password::defaults()],
//        ]);
//
//        $user = User::create([
//            'name' => $request->name,
//            'email' => $request->email,
//            'password' => Hash::make($request->password),
//        ]);
//
//        event(new Registered($user));
//
//        Auth::login($user);
//
//        return redirect(RouteServiceProvider::HOME);
    }

    public function destroy(){
        if (!(Auth::user()->roles->first()->name == "Admin" || Auth::user()->hasRole("Super Admin")))
            abort(404);
        $user_id = \request()->get("user_id");
        $user = User::findorfail($user_id);
        if ($user){
            removeFile(public_path('media/users/avatar/'),$user -> avatar);
            if ($user->delete()){
                return redirect()->back()->with("success","User deleted successfully");
            }
            return redirect()->back()->with("error","Something went wrong");
        }
    }

    public function updateLayout(){
        $user = Auth::user();
        if (isset($user)){
            if ($user->layout){
                $user->layout = 0;
                $user->save();
                return Response::json('Layout updated Light',200);
            }else{
                $user->layout = 1;
                $user->save();
                return Response::json('Layout updated Dark',200);
            }
        }else{
            return Response::json('No User Found',403);
        }
    }

    public function security(){
        $user = User::find(\auth()->user()->id);
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');

//        // Save the registration data in an array
//        $registration_data = $request->all();

        // Add the secret key to the registration data
        $qr_data["google2fa_secret"] = $google2fa->generateSecretKey();
//        $user->google2fa_secret = $qr_data["google2fa_secret"];
//        $user->save();

        // Generate the QR image. This is the image the user will scan with their app
        // to set up two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->name.",".$user->id,
            $qr_data['google2fa_secret']
        );

        return view('template.user.account.security',compact('user','QR_Image','qr_data'));
    }

    public function securityStore(Request $request){
        $this->validate($request,[
            'authenticator_code'=>'required',
        ]);
        $user = User::find($request->user_id);
        $google2fa = app('pragmarx.google2fa');
//        dd($request->all());
        // Add the secret key to the User Record
        $secret = $request->input('authenticator_code');
        $valid = $google2fa->verifyKey($user->google2fa_secret ?? $request->google2fa_secret, $secret);
        if (!$valid)
            return redirect(route('user.security'))->with("error",'Authenticator Code is Invalid! Please try again');
        $user->google2fa_secret = $request->google2fa_secret;
        $msg = "2FA enabled Completed Successfully";
        if (empty($request->google2fa_secret))
            $msg = "2FA removed Successfully";
        if ($user->save()) {
            return redirect(route('user.security'))->with("success", $msg);
        }
        else
            return redirect(route('user.security'))->with("error",'Something went wrong');
    }
}
