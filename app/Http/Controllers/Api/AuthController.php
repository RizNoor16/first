<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;

use App\User;

class AuthController extends Controller
{

    
    public function register(Request $request) {

        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
 
        $user = ModelsUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
 
        $accessToken = $user->createToken('authToken')->accessToken;
        return response([ 'user' => $user, 'access_token' => $accessToken]);
      
      }
      
      public function login(Request $request)
      {
       
          $credentials = [
              'email' => $request->email,
              'password' => $request->password
          ];
   
          if (auth()->attempt($credentials)){
        
             $user = ModelsUser::where('email',$request->email)->first(); 
             $accessToken = auth()->user()->createToken('authToken')->accessToken;
           
              return response()->json([
                'user_id' => $user->id,
                'email'=> $user->email,
                'accessToken' => $accessToken
              ], 200);
          } else {
              return response()->json(['error' => 'UnAuthorised'], 401);
          }
          
      }

      public function logout(Request $request)
      {
      
       $bearerToken = request()->bearerToken();
       $tokenId = Configuration::forUnsecuredSigner()->parser()->parse($bearerToken)->claims()->get('jti');
       $client = Token::find($tokenId)->client;
      
      $tokenRepository = app(TokenRepository::class);
      $refreshTokenRepository = app(RefreshTokenRepository::class);
  
    //   // Revoke an access token...
       $tokenRepository->revokeAccessToken($client);
     

      return response([
        'message' => 'You have been successfully logged out.'
      ], 200);
}
    
    
    //  public function logout(Request $request)
    //  {
    //    $token =$request->user()->token();
    //    $token->revoke();
    //    $response =["message"=>"You have been successfully logged out."];
    //    return response($response, 200); 

    //  }
      // public function logout()
      // {
      //     $user = Auth::user();
      //     $user->token()->revoke();
      //     $user->token()->delete();
  
      //     return response()->json(null, 204);        
      // }

      
      public function user(Request $request) {
        return response()->json($request->user());
      }
}
