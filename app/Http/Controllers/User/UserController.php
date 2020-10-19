<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']); //accessed publicallly by all clients

        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);

        $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);

        $this->middleware('scope:manage-account')->only(['show', 'update']);


        //policies
        $this->middleware('can:view,user')->only('show');
        $this->middleware('can:update,user')->only('update');
        $this->middleware('can:delete,user')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->allowedAdminAction();

        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validating the request
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
        $this->validate($request, $rules);

        //get all data from the request and  store in array
        $userData = $request->all();

        //add custom data to the userData array
        $userData['password'] = bcrypt($request->password);
        $userData['verified'] = User::UNVERIFIED_USER;
        $userData['verification_token'] = User::generateVerificationToken();
        $userData['admin'] = User::REGULAR_USER;

        //create a user based on data from the userData array
        $user = User::create($userData);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     ** @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {



        $rules = [
            'email' => 'email|unique:users,email,' . $user->id, //excepting user sending the request
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,   //shud be btn adminuser or reqular user
        ];


        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $this->validate($request, $rules); //validate request

            $user->verified = User::UNVERIFIED_USER; // set user to unverified if email changed
            $user->verification_token = User::generateVerificationToken(); //generate new token fr the user
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $this->validate($request, $rules); //validate request

            $user->password = bcrypt($request->password);
        }


        if ($request->has('admin')) {
            $this->allowedAdminAction();
            $this->validate($request, $rules); //validate request

            if (!$user->isVerified()) { //if user is not a veified user
                return $this->errorResponse('Only admin users can modify admin field', 409);
            }

            //else if he is verified
            $user->admin = $request->admin;
        }

        //check if anything has changed in the model
        if ($user->isClean()) {
            return $this->errorResponse('You have to specify a value to update', 422);
        }

        //save the changes if all is well
        $user->save();


        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $user->delete();

        return $this->showOne($user);
    }

    //returns current user  consuming the api throught the client

    public function me(Request $request)
    {
        $user = $request->user();

        return $this->showOne($user);
    }


    //verify the registered user through email using the verification token
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrfail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();

        return $this->showMessage("Your account has been verified successfully", 200);
    }

    //resends verification token
    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse("This user is already verified", 409);
        }

        retry(5, function () use ($user) {
            Mail::to($user)->send(new UserCreated($user)); //send an email a gain for user to verfiry his account
        }, 100);

        return $this->showMessage("Account has been verified successfully");
    }
}
