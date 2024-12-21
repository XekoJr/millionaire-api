<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class JWTAuthController extends Controller
{
    // Registo de um utilizador
    public function register(Request $request) 
    {
        // Podemos utilizar um validador para garantir que os dados enviados pelo utilizador são válidos.
        // Neste caso utilizamos o Validator do Laravel.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255', // Obrigatório, string e máximo de 255 caracteres
            'email' => 'required|string|email|max:255|unique:users', // Obrigatório, string, email, máximo de 255 caracteres e único na tabela users
            'password' => 'required|string|min:6', // Obrigatório, string, mínimo de 6 caracteres
        ]);

        // Se o validador falhar, devolvemos uma resposta com os erros
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Criamos um novo utilizador
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')), // Em vez de utilizarmos o método padrão de encriptação do Laravel, utilizamos o Hash para encriptar a password
        ]);

        $token = JWTAuth::fromUser($user); // Criamos um token JWT para o utilizador

        return response()->json(compact('user', 'token'), 201); // Devolvemos o utilizador e o token

    }

    // Autenticação de um utilizador
    public function login(Request $request) 
    {
        $credentials = $request->only('email', 'password'); // Obtemos as credenciais do utilizador

        try {
            if(!$token = JWTAuth::attempt($credentials)) { // Tentamos autenticar o utilizador
                return response()->json(['error' => 'invalid_credentials'], 400); // Se as credenciais estiverem erradas, devolvemos um erro
            }

            $user = JWTAuth::user(); // Obtemos o utilizador autenticado

            return response()->json(compact('user', 'token')); // Devolvemos o utilizador e o token
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500); // Se houver um erro ao criar o token, devolvemos um erro
        }
    }

    public function getUser() 
    {
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()) { // Obtemos o utilizador autenticado
                return response()->json(['User not found'], 404); // Se o utilizador não for encontrado, devolvemos um erro
            }
        } catch (JWTException $e) {
            return response()->json(['Token inválido'], $e->getStatusCode()); // Se o token estiver expirado, devolvemos um erro
        }

        return response()->json(compact('user')); // Devolvemos o utilizador
    }

    // Logout de um utilizador
    public function logout(Request $request)
    {
        try {
            $token = JWTAuth::getToken(); // Obtemos o token
            if (!$token) {
                return response()->json(['error' => 'Token not provided'], 400); // Se o token não for fornecido, devolvemos um erro
            }
            JWTAuth::invalidate($token); // Invalidamos o token
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not invalidate token'], 500); // Se houver um erro ao invalidar o token, devolvemos um erro
        }

        return response()->json(['success' => true]); // Devolvemos uma resposta de sucesso
    }
}
