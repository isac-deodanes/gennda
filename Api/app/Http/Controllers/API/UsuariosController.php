<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsuariosController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'dui' => [
                'required',
                'string',
                'max:10',
                'unique:usuarios',
                'regex:/^[0-9]{8}-[0-9]{1}$/'
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $usuario = Usuarios::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'dui' => $request->dui,
            'password' => Hash::make($request->password),
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => '¡Usuario registrado exitosamente!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $usuario
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $usuario = Usuarios::where('email', $request->email)->first();
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json([
                'message' => 'Las credenciales proporcionadas son incorrectas.'
            ], 401);
        }
        $usuario->tokens()->delete();
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => '¡Inicio de sesión exitoso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $usuario
        ], 200);
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente.'
        ], 200);
    }
    
    public function index()
    {
        
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
        
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if ($user->foto_path) {
            Storage::disk('public')->delete($user->foto_path);
        }
        $path = $request->file('foto')->store('uploads/fotos', 'public');
        $user->foto_path = $path;
        $user->save();
        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes', 
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('usuarios')->ignore($user->id),
            ],
            'balance_actual' => 'sometimes|nullable|numeric|min:0',
            'ingreso_mensual' => 'sometimes|nullable|numeric|min:0'        
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user->update($validator->validated());
        return response()->json($user);
    }

    

}